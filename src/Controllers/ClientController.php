<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Services\ClientServiceInterface;
use App\Core\Controller;
use App\Exceptions\ValidationException;

// Recibe las peticiones de clientes y llama a ClientService.
final class ClientController extends Controller
{
    public function __construct(private ClientServiceInterface $service)
    {
    }




    // GET /clientes - listado
    public function index(): void
    {
        $this->view('clients/index', [
            'title' => 'Clientes',
            'clients' => $this->service->list(),
        ]);
    }




    // GET /clientes/crear - formulario vacío
    public function create(): void
    {
        $this->view('clients/form', [
            'title' => 'Registrar cliente',
            'old' => [],
            'errors' => [],
        ]);
    }




    // POST /clientes - guarda un cliente nuevo
    public function store(): void
    {
        try {
            $this->service->register($_POST);
            $this->flash('success', 'Cliente registrado correctamente.');
            $this->redirect('/clientes');
        } catch (ValidationException $ex) {
            $this->view('clients/form', [
                'title' => 'Registrar cliente',
                'old' => $_POST,
                'errors' => $ex->getErrors(),
            ]);
        }
    }
}
