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

        // Validar que los campos presentes sean strings (evitar inyección de arreglos como name[])
        foreach (['name', 'phone', 'email', 'driver_license'] as $campo) {
            if (array_key_exists($campo, $data) && !is_string($data[$campo])) {
                $errores[$campo] = 'El campo ' . $campo . ' debe ser un texto.';
            }
        }

        $name = trim($data['name'] ?? '');
        if ($name === '') {
            $errores['name'] = 'El nombre es obligatorio.';
        } elseif (mb_strlen($name) > 150) {
            $errores['name'] = 'El nombre no puede superar los 150 caracteres.';
        }

        $correo = trim($data['email'] ?? '');
        if ($correo !== '' && filter_var($correo, FILTER_VALIDATE_EMAIL) === false) {
            $errores['email'] = 'El correo no tiene un formato válido.';
        } elseif ($correo !== '' && mb_strlen($correo) > 100) {
            $errores['email'] = 'El correo no puede superar los 100 caracteres.';
        }

        $licencia = trim($data['driver_license'] ?? '');
        if ($licencia === '') {
            $errores['driver_license'] = 'El número de licencia es obligatorio.';
        } elseif (mb_strlen($licencia) > 50) {
            $errores['driver_license'] = 'El número de licencia no puede superar los 50 caracteres.';
        } elseif ($this->repo->findByLicense($licencia) !== null) {
            $errores['driver_license'] = 'Ya existe un cliente con esa licencia.';
        }

        $phone = trim($data['phone'] ?? '');
        if ($phone !== '' && mb_strlen($phone) > 50) {
            $errores['phone'] = 'El teléfono no puede superar los 50 caracteres.';
        }

        if (count($errores) > 0) {
            throw new ValidationException($errores);
        }

        $cliente = new Client(
            null,
            $name,
            $phone,
            $correo,
            $licencia
        );

        return $this->repo->insert($cliente);
    }
}
