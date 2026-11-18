<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;

// Casos de uso de vehículos
interface VehicleServiceInterface
{
    public function list(?VehicleStatus $status = null): array;

    public function find(int $id): ?Vehicle;

    public function register(array $data): Vehicle;

    public function update(int $id, array $data): Vehicle;

    public function changeStatus(int $id, VehicleStatus $status): Vehicle;
}
