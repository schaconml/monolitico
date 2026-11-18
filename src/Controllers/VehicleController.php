<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Services\VehicleServiceInterface;
use App\Core\Controller;
use App\Enums\VehicleStatus;
use App\Exceptions\ValidationException;
use LogicException;

// Recibe las peticiones de vehículos y llama a VehicleService.
final class VehicleController extends Controller
{
    public function __construct(private VehicleServiceInterface $service)
    {
    }




    // GET /vehiculos - listado con filtro por estado
    public function index(): void
    {
        $filtro = VehicleStatus::tryFrom($_GET['estado'] ?? '');

        $this->view('vehicles/index', [
            'title' => 'Vehículos',
            'vehicles' => $this->service->list($filtro),
            'filter' => $filtro,
        ]);
    }




    // GET /vehiculos/crear - formulario vacío
    public function create(): void
    {
        $this->view('vehicles/form', [
            'title' => 'Registrar vehículo',
            'vehicle' => null,
            'old' => [],
            'errors' => [],
        ]);
    }




    // POST /vehiculos - guarda un vehículo nuevo
    public function store(): void
    {
        try {
            $this->service->register($_POST);
            $this->flash('success', 'Vehículo registrado correctamente.');
            $this->redirect('/vehiculos');
        } catch (ValidationException $ex) {
            $this->view('vehicles/form', [
                'title' => 'Registrar vehículo',
                'vehicle' => null,
                'old' => $_POST,
                'errors' => $ex->getErrors(),
            ]);
        }
    }




    // GET /vehiculos/{id}/editar - formulario con los datos actuales
    public function edit(string $id): void
    {
        $vehiculo = $this->service->find((int) $id);

        if ($vehiculo === null) {
            $this->flash('danger', 'El vehículo no existe.');
            $this->redirect('/vehiculos');
        }

        $this->view('vehicles/form', [
            'title' => 'Editar vehículo',
            'vehicle' => $vehiculo,
            'old' => [],
            'errors' => [],
        ]);
    }




    // POST /vehiculos/{id}/editar - guarda los cambios
    public function update(string $id): void
    {
        try {
            $this->service->update((int) $id, $_POST);
            $this->flash('success', 'Vehículo actualizado correctamente.');
            $this->redirect('/vehiculos');
        } catch (ValidationException $ex) {
            $this->view('vehicles/form', [
                'title' => 'Editar vehículo',
                'vehicle' => $this->service->find((int) $id),
                'old' => $_POST,
                'errors' => $ex->getErrors(),
            ]);
        } catch (LogicException $ex) {
            $this->flash('danger', $ex->getMessage());
            $this->redirect('/vehiculos');
        }
    }




    // POST /vehiculos/{id}/estado - pasa a mantenimiento o disponible
    public function changeStatus(string $id): void
    {
        $nuevo = VehicleStatus::tryFrom($_POST['status'] ?? '');

        try {
            if ($nuevo === null) {
                throw new LogicException('El estado indicado no es válido.');
            }

            $this->service->changeStatus((int) $id, $nuevo);
            $this->flash('success', 'Estado actualizado correctamente.');
        } catch (LogicException $ex) {
            $this->flash('danger', $ex->getMessage());
        }

        $this->redirect('/vehiculos');
    }
}
