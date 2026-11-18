<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Reservation;
use DateTimeImmutable;

// Casos de uso de reservas (se implementa en la Fase 4)
interface ReservationServiceInterface
{
    public function list(): array;

    // Busca una reserva por id (la usa la confirmación de devolución)
    public function find(int $id): ?Reservation;

    public function create(int $clientId, int $vehicleId, DateTimeImmutable $start, DateTimeImmutable $end): Reservation;

    public function registerReturn(int $reservationId): Reservation;
}
