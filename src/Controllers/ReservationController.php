<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Services\ClientServiceInterface;
use App\Contracts\Services\ReservationServiceInterface;
use App\Contracts\Services\VehicleServiceInterface;
use App\Core\Controller;
use App\Exceptions\ValidationException;
use DateTimeImmutable;
use LogicException;

// Recibe las peticiones de reservas y llama a ReservationService (RF-05 y RF-06).
final class ReservationController extends Controller
{
    public function __construct(
        private ReservationServiceInterface $service,
        private VehicleServiceInterface $vehiculos,
        private ClientServiceInterface $clientes,
    ) {
    }



    // GET /reservas - listado con el nombre del vehículo y del cliente
    public function index(): void
    {
        $this->view('reservations/index', [
            'title' => 'Reservas',
            'reservations' => $this->service->list(),
            'vehicles' => $this->mapById($this->vehiculos->list()),
            'clients' => $this->mapById($this->clientes->list()),
        ]);
    }



    // GET /reservas/crear - formulario con los selectores de cliente y vehículo
    public function create(): void
    {
        $hoy = (new DateTimeImmutable('today'))->format('Y-m-d');

        $this->view('reservations/create', [
            'title' => 'Nueva reserva',
            'clients' => $this->clientes->list(),
            'vehicles' => $this->vehiculos->list(),
            'old' => [],
            'errors' => [],
            'hoy' => $hoy,
        ]);
    }



    // POST /reservas - guarda la reserva; si inicia hoy el vehículo pasa a alquilado (RN-03)
    public function store(): void
    {
        try {
            $clientId = $_POST['client_id'] ?? 0;
            $vehicleId = $_POST['vehicle_id'] ?? 0;

            if (!is_string($clientId) && !is_int($clientId)) {
                throw new ValidationException(['client_id' => 'Seleccione un cliente.']);
            }
            if (!is_string($vehicleId) && !is_int($vehicleId)) {
                throw new ValidationException(['vehicle_id' => 'Seleccione un vehículo.']);
            }

            $this->service->create(
                (int) $clientId,
                (int) $vehicleId,
                $this->parseDate($_POST['start_date'] ?? '', 'start_date'),
                $this->parseDate($_POST['end_date'] ?? '', 'end_date'),
            );

            $this->flash('success', 'Reserva registrada correctamente.');
            $this->redirect('/reservas');
        } catch (ValidationException $ex) {
            $this->view('reservations/create', [
                'title' => 'Nueva reserva',
                'clients' => $this->clientes->list(),
                'vehicles' => $this->vehiculos->list(),
                'old' => $_POST,
                'errors' => $ex->getErrors(),
            ]);
        } catch (LogicException $ex) {
            // Incluye VehicleNotAvailableException y InvalidDateRangeException (RN-01 y RN-02)
            $this->flash('danger', $ex->getMessage());
            $this->redirect('/reservas');
        }
    }



    // GET /reservas/{id}/devolucion - confirmación de la devolución
    public function return(string $id): void
    {
        $reserva = $this->service->find((int) $id);

        if ($reserva === null) {
            $this->flash('danger', 'La reserva no existe.');
            $this->redirect('/reservas');
        }

        $hoy = (new DateTimeImmutable('today'))->format('d/m/Y');

        $this->view('reservations/return', [
            'title' => 'Registrar devolución',
            'reservation' => $reserva,
            'vehicle' => $this->vehiculos->find($reserva->getVehicleId()),
            'client' => $this->clientes->find($reserva->getClientId()),
            'hoy' => $hoy,
        ]);
    }



    // POST /reservas/{id}/devolucion - completa la reserva y libera el vehículo (RF-06)
    public function processReturn(string $id): void
    {
        try {
            $this->service->registerReturn((int) $id);
            $this->flash('success', 'Devolución registrada correctamente.');
        } catch (LogicException $ex) {
            $this->flash('danger', $ex->getMessage());
        }

        $this->redirect('/reservas');
    }



    // Convierte el texto del formulario en fecha (Y-m-d); lanza ValidationException si no es válida
    private function parseDate(string $valor, string $campo): DateTimeImmutable
    {
        if (!is_string($valor)) {
            throw new ValidationException([$campo => 'La fecha indicada no es válida.']);
        }

        $valor = trim($valor);
        $fecha = DateTimeImmutable::createFromFormat('Y-m-d|', $valor);

        if ($fecha === false) {
            throw new ValidationException([$campo => 'La fecha indicada no es válida.']);
        }

        // Rechazar fechas inválidas que PHP normaliza (ej. 30 de febrero → 2 de marzo)
        if ($fecha->format('Y-m-d') !== $valor) {
            throw new ValidationException([$campo => 'La fecha indicada no es válida.']);
        }

        return $fecha;
    }



    // Indexa entidades por id para mostrar sus nombres en el listado
    private function mapById(array $entidades): array
    {
        $mapa = [];
        foreach ($entidades as $entidad) {
            $mapa[$entidad->getId()] = $entidad;
        }

        return $mapa;
    }
}
