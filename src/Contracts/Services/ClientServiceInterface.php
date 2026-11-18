<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Client;

// Casos de uso de clientes
interface ClientServiceInterface
{
    public function list(): array;

    public function find(int $id): ?Client;

    public function register(array $data): Client;
}
