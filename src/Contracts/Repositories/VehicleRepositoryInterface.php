<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use DateTimeImmutable;

// Operaciones de base de datos para vehículos
interface VehicleRepositoryInterface
{
    public function findAll(?VehicleStatus $status = null): array;

    public function findById(int $id): ?Vehicle;

    // Bloquea el vehículo dentro de una transacción para evitar sobreventa (RN-02 y RN-03)
    public function findByIdForUpdate(int $id): ?Vehicle;

    public function insert(Vehicle $vehicle): Vehicle;

    public function update(Vehicle $vehicle): void;

    // RF-10: cantidad de vehículos por estado, con la forma [valor del estado => cantidad]
    public function countByStatus(): array;

    // RF-07: disponibles en tiempo real (fechas nulas) o sin cruce de reservas en el rango
    public function findAvailableForRange(?DateTimeImmutable $start, ?DateTimeImmutable $end): array;
}
