<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

// Pantalla de inicio (el dashboard con contadores llega en la Fase 5).
final class HomeController extends Controller
{
    // GET /
    public function index(): void
    {
        $this->view('home/dashboard', ['title' => 'Inicio']);
    }
}
