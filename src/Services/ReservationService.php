<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Contracts\Repositories\ReservationRepositoryInterface;
use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Contracts\Services\ReservationServiceInterface;
use App\Contracts\TransactionManagerInterface;
use App\Enums\VehicleStatus;
use App\Exceptions\InvalidDateRangeException;
use App\Exceptions\ValidationException;
use App\Exceptions\VehicleNotAvailableException;
use App\Models\Reservation;
use DateTimeImmutable;
use LogicException;
use Throwable;

// Casos de uso de reservas: crear con validación de disponibilidad (RF-05, RN-01 a RN-03)
// y registrar la devolución (RF-06, RN-04). Ambas operaciones son transaccionales.
final class ReservationService implements ReservationServiceInterface
{
    public function __construct(
        private ReservationRepositoryInterface $reservas,
        private VehicleRepositoryInterface $vehiculos,
        private ClientRepositoryInterface $clientes,
        private TransactionManagerInterface $transaccion,
    ) {
    }



    // Lista todas las reservas
    public function list(): array
    {
        return $this->reservas->findAll();
    }



    // Busca una reserva por id (la usa la pantalla de devolución)
    public function find(int $id): ?Reservation
    {
        return $this->reservas->findById($id);
    }



    // RF-05: crea la reserva validando RN-01 y RN-02 y aplicando RN-03 dentro de una transacción
    public function create(int $clientId, int $vehicleId, DateTimeImmutable $start, DateTimeImmutable $end): Reservation
    {
        $this->validate($clientId, $vehicleId, $start, $end);

        $this->transaccion->begin();

        try {
            // RN-02: bloquear el vehículo para evitar sobreventa entre peticiones simultáneas
            $vehiculo = $this->vehiculos->findByIdForUpdate($vehicleId);
            if ($vehiculo === null) {
                throw new LogicException('El vehículo no existe.');
            }

            if ($vehiculo->getStatus() === VehicleStatus::Maintenance) {
                throw new VehicleNotAvailableException('El vehículo está en mantenimiento y no puede reservarse.');
            }

            if ($this->clientes->findById($clientId) === null) {
                throw new LogicException('El cliente no existe.');
            }

            if (count($this->reservas->findOverlapping($vehicleId, $start, $end)) > 0) {
                throw new VehicleNotAvailableException('El vehículo ya tiene una reserva que se cruza con esas fechas.');
            }

            $reserva = new Reservation(null, $vehicleId, $clientId, $start, $end);
            $this->reservas->insert($reserva);

            // RN-03: si la reserva inicia hoy, el vehículo pasa a "alquilado" en la misma transacción
            $hoy = new DateTimeImmutable('today');
            if ($start->format('Y-m-d') === $hoy->format('Y-m-d')) {
                $vehiculo->markAsRented();
                $this->vehiculos->update($vehiculo);
            }

            $this->transaccion->commit();

            return $reserva;
        } catch (Throwable $ex) {
            $this->transaccion->rollback();
            throw $ex;
        }
    }



    // RF-06: registra la devolución, completa la reserva y libera el vehículo si corresponde (RN-04)
    public function registerReturn(int $reservationId): Reservation
    {
        $this->transaccion->begin();

        try {
            $reserva = $this->reservas->findByIdForUpdate($reservationId);
            if ($reserva === null) {
                throw new LogicException('La reserva no existe.');
            }

            // RN-06: una reserva completada o cancelada no vuelve a devolverse
            if (!$reserva->isActive()) {
                throw new LogicException('La reserva ya está completada o cancelada.');
            }

            $hoy = new DateTimeImmutable('today');
            if ($hoy < $reserva->getStartDate()) {
                throw new InvalidDateRangeException('La devolución no puede registrarse antes de la fecha de inicio de la reserva.');
            }

            $reserva->complete($hoy);
            $this->reservas->update($reserva);

            $this->liberarVehiculo($reserva->getVehicleId(), $hoy);

            $this->transaccion->commit();

            return $reserva;
        } catch (Throwable $ex) {
            $this->transaccion->rollback();
            throw $ex;
        }
    }



    // RN-04: el vehículo vuelve a "disponible" solo si no tiene otra reserva activa vigente hoy
    private function liberarVehiculo(int $vehicleId, DateTimeImmutable $hoy): void
    {
        $vehiculo = $this->vehiculos->findByIdForUpdate($vehicleId);
        if ($vehiculo === null) {
            throw new LogicException('El vehículo de la reserva no existe.');
        }

        if (count($this->reservas->findOverlapping($vehicleId, $hoy, $hoy)) > 0) {
            return;
        }

        $vehiculo->releaseFromRental();
        $this->vehiculos->update($vehiculo);
    }



    // Revisa la forma de los datos y aplica RN-01 (fin >= inicio) y la fecha de inicio no retroactiva
    private function validate(int $clientId, int $vehicleId, DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $errores = [];

        if ($clientId <= 0) {
            $errores['client_id'] = 'Seleccione un cliente.';
        }

        if ($vehicleId <= 0) {
            $errores['vehicle_id'] = 'Seleccione un vehículo.';
        }

        $hoy = new DateTimeImmutable('today');
        if ($start < $hoy) {
            $errores['start_date'] = 'La fecha de inicio no puede ser anterior a la de hoy.';
        }

        if ($end < $start) {
            $errores['end_date'] = 'La fecha de fin no puede ser anterior a la de inicio.';
        }

        if (count($errores) > 0) {
            throw new ValidationException($errores);
        }
    }
}
