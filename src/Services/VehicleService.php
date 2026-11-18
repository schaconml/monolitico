<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Contracts\Services\VehicleServiceInterface;
use App\Enums\VehicleStatus;
use App\Exceptions\ValidationException;
use App\Models\Vehicle;
use LogicException;

// Reglas de negocio de los vehículos.
final class VehicleService implements VehicleServiceInterface
{
    public function __construct(private VehicleRepositoryInterface $repo)
    {
    }




    // Lista vehículos, con filtro opcional por estado
    public function list(?VehicleStatus $status = null): array
    {
        return $this->repo->findAll($status);
    }




    // Busca un vehículo por id
    public function find(int $id): ?Vehicle
    {
        return $this->repo->findById($id);
    }




    // RF-01: registra un vehículo nuevo (valida con validate())
    public function register(array $data): Vehicle
    {
        $this->validate($data);

        // Un vehículo nuevo no puede nacer alquilado
        $estado = VehicleStatus::tryFrom($data['status'] ?? '');
        if ($estado === null || $estado === VehicleStatus::Rented) {
            $estado = VehicleStatus::Available;
        }

        $vehiculo = new Vehicle(
            null,
            trim($data['brand']),
            trim($data['model']),
            (int) $data['year'],
            trim($data['category']),
            $estado
        );

        return $this->repo->insert($vehiculo);
    }




    // Edita los datos básicos de un vehículo (el estado se cambia con changeStatus)
    public function update(int $id, array $data): Vehicle
    {
        $this->validate($data);

        $vehiculo = $this->repo->findById($id);
        if ($vehiculo === null) {
            throw new LogicException('El vehículo no existe.');
        }

        $vehiculo->setBrand(trim($data['brand']));
        $vehiculo->setModel(trim($data['model']));
        $vehiculo->setYear((int) $data['year']);
        $vehiculo->setCategory(trim($data['category']));

        $this->repo->update($vehiculo);

        return $vehiculo;
    }




    // RF-02: pasa el vehículo a mantenimiento o disponible (no se permite si está alquilado)
    public function changeStatus(int $id, VehicleStatus $status): Vehicle
    {
        $vehiculo = $this->repo->findById($id);
        if ($vehiculo === null) {
            throw new LogicException('El vehículo no existe.');
        }

        if ($status === VehicleStatus::Maintenance) {
            $vehiculo->markAsMaintenance();
        } elseif ($status === VehicleStatus::Available) {
            $vehiculo->markAsAvailable();
        } else {
            throw new LogicException('El estado "alquilado" solo cambia al crear una reserva.');
        }

        $this->repo->update($vehiculo);

        return $vehiculo;
    }




    // Revisa los campos del formulario y lanza ValidationException si hay errores
    private function validate(array $data): void
    {
        $errores = [];

        if (trim($data['brand'] ?? '') === '') {
            $errores['brand'] = 'La marca es obligatoria.';
        }

        if (trim($data['model'] ?? '') === '') {
            $errores['model'] = 'El modelo es obligatorio.';
        }

        $anio = filter_var($data['year'] ?? '', FILTER_VALIDATE_INT);
        $maximo = (int) date('Y') + 1;
        if ($anio === false || $anio < 1950 || $anio > $maximo) {
            $errores['year'] = "El año debe estar entre 1950 y {$maximo}.";
        }

        if (trim($data['category'] ?? '') === '') {
            $errores['category'] = 'La categoría es obligatoria.';
        }

        if (count($errores) > 0) {
            throw new ValidationException($errores);
        }
    }
}
