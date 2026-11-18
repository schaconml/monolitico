<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Reservation;
use DateTimeImmutable;

// Operaciones de base de datos para reservas (se implementa en la Fase 4)
interface ReservationRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Reservation;

    public function findByVehicle(int $vehicleId): array;

    public function findByClient(int $clientId): array;

    // Reservas activas del vehículo que se cruzan con el rango de fechas
    public function findOverlapping(int $vehicleId, DateTimeImmutable $start, DateTimeImmutable $end): array;

    public function insert(Reservation $reservation): Reservation;

    public function update(Reservation $reservation): void;
}
