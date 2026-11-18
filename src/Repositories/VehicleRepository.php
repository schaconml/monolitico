<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Core\Database;
use App\Enums\ReservationStatus;
use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use DateTimeImmutable;
use PDO;
use PDOStatement;

// Lee y guarda vehículos en la tabla "vehiculos".
final class VehicleRepository implements VehicleRepositoryInterface
{
    // Columnas que se leen de la tabla (no se usa SELECT *)
    private const COLUMNAS = 'id, marca, modelo, anio, categoria, estado, created_at';

    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }




    // Lista todos los vehículos, o solo los de un estado
    public function findAll(?VehicleStatus $status = null): array
    {
        if ($status === null) {
            $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM vehiculos ORDER BY id DESC');
            $stmt->execute();
        } else {
            $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM vehiculos WHERE estado = :estado ORDER BY id DESC');
            $stmt->execute(['estado' => $status->value]);
        }

        return $this->toList($stmt);
    }




    // Busca un vehículo por id (null si no existe)
    public function findById(int $id): ?Vehicle
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM vehiculos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();

        if ($fila === false) {
            return null;
        }

        return $this->toModel($fila);
    }



    // Bloquea la fila del vehículo dentro de una transacción (SELECT ... FOR UPDATE).
    // La usa ReservationService al crear una reserva y al registrar la devolución.
    public function findByIdForUpdate(int $id): ?Vehicle
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM vehiculos WHERE id = :id FOR UPDATE');
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




    // RF-10: cuenta los vehículos agrupados por estado
    public function countByStatus(): array
    {
        $stmt = $this->pdo->prepare('SELECT estado, COUNT(*) AS cantidad FROM vehiculos GROUP BY estado');
        $stmt->execute();

        $conteo = [];
        foreach ($stmt->fetchAll() as $fila) {
            $conteo[$fila['estado']] = (int) $fila['cantidad'];
        }

        return $conteo;
    }



    // RF-07: disponibles en tiempo real (sin fechas) o sin reservas activas que se crucen con el rango.
    // Un vehículo alquilado puede aparecer libre para un rango futuro si su reserva termina antes.
    public function findAvailableForRange(?DateTimeImmutable $start, ?DateTimeImmutable $end): array
    {
        if ($start === null || $end === null) {
            $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM vehiculos WHERE estado = :estado ORDER BY id DESC');
            $stmt->execute(['estado' => VehicleStatus::Available->value]);

            return $this->toList($stmt);
        }

        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNAS . ' FROM vehiculos
             WHERE estado <> :mantenimiento
               AND NOT EXISTS (
                   SELECT 1 FROM reservas r
                   WHERE r.vehiculo_id = vehiculos.id
                     AND r.estado = :activa
                     AND r.fecha_inicio <= :fin
                     AND r.fecha_fin >= :inicio
               )
             ORDER BY id DESC'
        );
        $stmt->execute([
            'mantenimiento' => VehicleStatus::Maintenance->value,
            'activa' => ReservationStatus::Active->value,
            'fin' => $end->format('Y-m-d'),
            'inicio' => $start->format('Y-m-d'),
        ]);

        return $this->toList($stmt);
    }



    // Convierte el resultado de una consulta en una lista de vehículos
    private function toList(PDOStatement $stmt): array
    {
        $res = [];
        foreach ($stmt->fetchAll() as $fila) {
            $res[] = $this->toModel($fila);
        }

        return $res;
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
