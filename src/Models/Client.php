<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeImmutable;

// Entidad de un cliente.
final class Client
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $phone,
        private string $email,
        private string $driverLicense,
        private ?DateTimeImmutable $createdAt = null
    ) {
    }




    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDriverLicense(): string
    {
        return $this->driverLicense;
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
}
