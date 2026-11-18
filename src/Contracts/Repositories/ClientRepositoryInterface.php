<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Client;

// Operaciones de base de datos para clientes
interface ClientRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Client;

    public function findByLicense(string $license): ?Client;

    public function insert(Client $client): Client;
}
