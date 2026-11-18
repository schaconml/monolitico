<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Contracts\Services\ClientServiceInterface;
use App\Exceptions\ValidationException;
use App\Models\Client;

// Reglas de negocio de los clientes.
final class ClientService implements ClientServiceInterface
{
    public function __construct(private ClientRepositoryInterface $repo)
    {
    }




    // RF-04: lista los clientes
    public function list(): array
    {
        return $this->repo->findAll();
    }




    // Busca un cliente por id
    public function find(int $id): ?Client
    {
        return $this->repo->findById($id);
    }




    // RF-03: registra un cliente validando que la licencia no esté repetida
    public function register(array $data): Client
    {
        $errores = [];

        if (trim($data['name'] ?? '') === '') {
            $errores['name'] = 'El nombre es obligatorio.';
        }

        $correo = trim($data['email'] ?? '');
        if ($correo !== '' && filter_var($correo, FILTER_VALIDATE_EMAIL) === false) {
            $errores['email'] = 'El correo no tiene un formato válido.';
        }

        $licencia = trim($data['driver_license'] ?? '');
        if ($licencia === '') {
            $errores['driver_license'] = 'El número de licencia es obligatorio.';
        } elseif ($this->repo->findByLicense($licencia) !== null) {
            $errores['driver_license'] = 'Ya existe un cliente con esa licencia.';
        }

        if (count($errores) > 0) {
            throw new ValidationException($errores);
        }

        $cliente = new Client(
            null,
            trim($data['name']),
            trim($data['phone'] ?? ''),
            $correo,
            $licencia
        );

        return $this->repo->insert($cliente);
    }
}
