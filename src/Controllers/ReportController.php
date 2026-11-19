<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Services\ClientServiceInterface;
use App\Contracts\Services\ReportServiceInterface;
use App\Contracts\Services\VehicleServiceInterface;
use App\Core\Controller;
use DateTimeImmutable;

// Recibe las peticiones de reportes y llama a ReportService (RF-07 a RF-09).
final class ReportController extends Controller
{
    public function __construct(
        private ReportServiceInterface $service,
        private VehicleServiceInterface $vehiculos,
        private ClientServiceInterface $clientes,
    ) {
    }



    // GET /reportes/disponibilidad - vehículos libres ahora o en un rango de fechas
    public function availability(): void
    {
        $inicioValor = $_GET['inicio'] ?? '';
        $finValor = $_GET['fin'] ?? '';

        if (!is_string($inicioValor) || !is_string($finValor)) {
            $inicioValor = '';
            $finValor = '';
        }

        $inicio = $this->parseDate($inicioValor);
        $fin = $this->parseDate($finValor);

        // Forma de la petición: si llegan las dos fechas, el rango debe ser coherente
        $errorRango = ($inicio !== null && $fin !== null && $fin < $inicio)
            ? 'La fecha final no puede ser anterior a la fecha inicial.'
            : null;

        $this->view('reports/availability', [
            'title' => 'Vehículos disponibles',
            'vehicles' => $errorRango === null ? $this->service->availableVehicles($inicio, $fin) : [],
            'inicio' => $inicioValor,
            'fin' => $finValor,
            'rangoError' => $errorRango,
        ]);
    }



    // GET /vehiculos/{id}/historial - historial de alquileres del vehículo (RF-08)
    public function vehicleHistory(string $id): void
    {
        $vehiculo = $this->vehiculos->find((int) $id);

        if ($vehiculo === null) {
            $this->flash('danger', 'El vehículo no existe.');
            $this->redirect('/vehiculos');
        }

        $this->view('vehicles/history', [
            'title' => 'Historial del vehículo',
            'vehicle' => $vehiculo,
            'reservations' => $this->service->vehicleHistory((int) $id),
            'clients' => $this->mapById($this->clientes->list()),
        ]);
    }



    // GET /clientes/{id}/historial - historial de alquileres del cliente (RF-09)
    public function clientHistory(string $id): void
    {
        $cliente = $this->clientes->find((int) $id);

        if ($cliente === null) {
            $this->flash('danger', 'El cliente no existe.');
            $this->redirect('/clientes');
        }

        $this->view('clients/history', [
            'title' => 'Historial del cliente',
            'client' => $cliente,
            'reservations' => $this->service->clientHistory((int) $id),
            'vehicles' => $this->mapById($this->vehiculos->list()),
        ]);
    }



    // Convierte 'Y-m-d' del filtro en fecha; null si no viene o no es válida
    private function parseDate(string $valor): ?DateTimeImmutable
    {
        $fecha = DateTimeImmutable::createFromFormat('Y-m-d|', trim($valor));

        return $fecha === false ? null : $fecha;
    }



    // Indexa entidades por id para mostrar los nombres en los historiales
    private function mapById(array $entidades): array
    {
        $mapa = [];
        foreach ($entidades as $entidad) {
            $mapa[$entidad->getId()] = $entidad;
        }

        return $mapa;
    }
}
