<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReservationStatus;
use DateTimeImmutable;

// Entidad de una reserva (la lógica que la usa se construye en la Fase 4).
final class Reservation
{
    public function __construct(
        private ?int $id,
        private int $vehicleId,
        private int $clientId,
        private DateTimeImmutable $startDate,
        private DateTimeImmutable $endDate,
        private ReservationStatus $status = ReservationStatus::Active,
        private ?DateTimeImmutable $createdAt = null
    ) {
    }




    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicleId(): int
    {
        return $this->vehicleId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getStatus(): ReservationStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }




    // Setter del id (lo asigna el repositorio al insertar)
    public function setId(int $id): void
    {
        $this->id = $id;
    }




    // Reglas propias de la reserva
    public function isActive(): bool
    {
        return $this->status === ReservationStatus::Active;
    }

    public function complete(): void
    {
        $this->status = ReservationStatus::Completed;
    }

    public function cancel(): void
    {
        $this->status = ReservationStatus::Cancelled;
    }

    // Indica si el rango dado se cruza con el de esta reserva
    public function overlaps(DateTimeImmutable $start, DateTimeImmutable $end): bool
    {
        return $start <= $this->endDate && $end >= $this->startDate;
    }
}
