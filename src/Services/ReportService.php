<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\ReservationRepositoryInterface;
use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Contracts\Services\ReportServiceInterface;
use App\Enums\VehicleStatus;
use DateTimeImmutable;

// Consultas de solo lectura para disponibilidad, historiales y contadores (RF-07 a RF-10).
// Coordina los repositorios de vehículos y reservas; no ejecuta SQL propio.
final class ReportService implements ReportServiceInterface
{
    public function __construct(
        private VehicleRepositoryInterface $vehiculos,
        private ReservationRepositoryInterface $reservas,
    ) {
    }



    // RF-07: disponibles ahora (fechas nulas) o libres en el rango de fechas
    public function availableVehicles(?DateTimeImmutable $start, ?DateTimeImmutable $end): array
    {
        return $this->vehiculos->findAvailableForRange($start, $end);
    }



    // RF-08: historial completo de reservas de un vehículo
    public function vehicleHistory(int $vehicleId): array
    {
        return $this->reservas->findByVehicle($vehicleId);
    }



    // RF-09: historial completo de reservas de un cliente
    public function clientHistory(int $clientId): array
    {
        return $this->reservas->findByClient($clientId);
    }



    // RF-10: contadores por estado, completando con cero los estados sin vehículos
    public function fleetCounters(): array
    {
        $conteo = $this->vehiculos->countByStatus();

        $res = [];
        foreach (VehicleStatus::cases() as $estado) {
            $res[$estado->value] = $conteo[$estado->value] ?? 0;
        }

        return $res;
    }
}
