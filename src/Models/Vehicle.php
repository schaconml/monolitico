<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VehicleStatus;
use DateTimeImmutable;
use LogicException;

// Entidad de un vehículo. Solo guarda datos y las reglas propias del vehículo.
final class Vehicle
{
    public function __construct(
        private ?int $id,
        private string $brand,
        private string $model,
        private int $year,
        private string $category,
        private VehicleStatus $status = VehicleStatus::Available,
        private ?DateTimeImmutable $createdAt = null
    ) {
    }




    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getStatus(): VehicleStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }




    // Setters (el estado no tiene setter: cambia con los métodos markAs...)
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }




    // Consultas de estado
    public function isAvailable(): bool
    {
        return $this->status === VehicleStatus::Available;
    }

    public function isRented(): bool
    {
        return $this->status === VehicleStatus::Rented;
    }




    // Cambios de estado. Los usa VehicleService (y luego ReservationService).
    public function markAsRented(): void
    {
        if ($this->status !== VehicleStatus::Available) {
            throw new LogicException('Solo un vehículo disponible puede alquilarse.');
        }

        $this->status = VehicleStatus::Rented;
    }

    public function markAsAvailable(): void
    {
        if ($this->status === VehicleStatus::Rented) {
            throw new LogicException('El vehículo está alquilado, debe devolverse primero.');
        }

        $this->status = VehicleStatus::Available;
    }

    public function markAsMaintenance(): void
    {
        if ($this->status === VehicleStatus::Rented) {
            throw new LogicException('El vehículo está alquilado, debe devolverse primero.');
        }

        $this->status = VehicleStatus::Maintenance;
    }
}
