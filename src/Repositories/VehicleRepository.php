<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Core\Database;
use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use DateTimeImmutable;
use PDO;

// Lee y guarda vehículos en la tabla "vehiculos".
final class VehicleRepository implements VehicleRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }




    // Lista todos los vehículos, o solo los de un estado
    public function findAll(?VehicleStatus $status = null): array
    {
        if ($status === null) {
            $stmt = $this->pdo->query('SELECT * FROM vehiculos ORDER BY id DESC');
        } else {
            $stmt = $this->pdo->prepare('SELECT * FROM vehiculos WHERE estado = :estado ORDER BY id DESC');
            $stmt->execute(['estado' => $status->value]);
        }

        $res = [];
        foreach ($stmt->fetchAll() as $fila) {
            $res[] = $this->toModel($fila);
        }

        return $res;
    }




    // Busca un vehículo por id (null si no existe)
    public function findById(int $id): ?Vehicle
    {
        $stmt = $this->pdo->prepare('SELECT * FROM vehiculos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();

        if ($fila === false) {
            return null;
        }

        return $this->toModel($fila);
    }




    // Inserta un vehículo nuevo y le asigna el id generado
    public function insert(Vehicle $vehicle): Vehicle
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO vehiculos (marca, modelo, anio, categoria, estado)
             VALUES (:marca, :modelo, :anio, :categoria, :estado)'
        );
        $stmt->execute([
            'marca' => $vehicle->getBrand(),
            'modelo' => $vehicle->getModel(),
            'anio' => $vehicle->getYear(),
            'categoria' => $vehicle->getCategory(),
            'estado' => $vehicle->getStatus()->value,
        ]);

        $vehicle->setId((int) $this->pdo->lastInsertId());

        return $vehicle;
    }




    // Actualiza todos los campos editables de un vehículo
    public function update(Vehicle $vehicle): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE vehiculos
             SET marca = :marca, modelo = :modelo, anio = :anio, categoria = :categoria, estado = :estado
             WHERE id = :id'
        );
        $stmt->execute([
            'marca' => $vehicle->getBrand(),
            'modelo' => $vehicle->getModel(),
            'anio' => $vehicle->getYear(),
            'categoria' => $vehicle->getCategory(),
            'estado' => $vehicle->getStatus()->value,
            'id' => $vehicle->getId(),
        ]);
    }




    // Convierte una fila de la BD en un objeto Vehicle
    private function toModel(array $fila): Vehicle
    {
        return new Vehicle(
            (int) $fila['id'],
            $fila['marca'],
            $fila['modelo'],
            (int) $fila['anio'],
            (string) $fila['categoria'],
            VehicleStatus::from($fila['estado']),
            new DateTimeImmutable($fila['created_at'])
        );
    }
}
