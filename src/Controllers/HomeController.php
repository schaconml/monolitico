<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Services\ReportServiceInterface;
use App\Core\Controller;

// Pantalla de inicio con los contadores de la flota (RF-10).
final class HomeController extends Controller
{
    public function __construct(private ReportServiceInterface $reportes)
    {
    }



    // GET / - contadores de la flota y accesos a los módulos
    public function index(): void
    {
        $this->view('home/dashboard', [
            'title' => 'Inicio',
            'counters' => $this->reportes->fleetCounters(),
        ]);
    }
}
