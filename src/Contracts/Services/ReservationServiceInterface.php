<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Reservation;
use DateTimeImmutable;

// Casos de uso de reservas (se implementa en la Fase 4)
interface ReservationServiceInterface
{
    public function list(): array;

    public function create(int $clientId, int $vehicleId, DateTimeImmutable $start, DateTimeImmutable $end): Reservation;

    public function registerReturn(int $reservationId): Reservation;
}
