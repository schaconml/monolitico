<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;

// Operaciones de base de datos para vehículos
interface VehicleRepositoryInterface
{
    public function findAll(?VehicleStatus $status = null): array;

    public function findById(int $id): ?Vehicle;

    public function insert(Vehicle $vehicle): Vehicle;

    public function update(Vehicle $vehicle): void;
}
