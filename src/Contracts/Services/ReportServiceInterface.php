<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Reservation;
use App\Models\Vehicle;
use DateTimeImmutable;

// Casos de uso de solo lectura: disponibilidad, historiales y contadores (RF-07 a RF-10)
interface ReportServiceInterface
{
    // RF-07: vehículos disponibles ahora (fechas nulas) o libres en el rango de fechas
    public function availableVehicles(?DateTimeImmutable $start, ?DateTimeImmutable $end): array;


    // RF-08: historial completo de alquileres de un vehículo
    public function vehicleHistory(int $vehicleId): array;


    // RF-09: historial completo de alquileres de un cliente
    public function clientHistory(int $clientId): array;


    // RF-10: contadores de la flota, con la forma [valor del estado => cantidad]
    public function fleetCounters(): array;
}
