<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\ReservationRepositoryInterface;
use App\Core\Database;
use App\Enums\ReservationStatus;
use App\Models\Reservation;
use DateTimeImmutable;
use PDO;
use PDOStatement;

// Lee y guarda reservas en la tabla "reservas".
// Los servicios de reservas y reportes consumen esta clase a través de su interfaz.
final class ReservationRepository implements ReservationRepositoryInterface
{
    // Columnas que se leen de la tabla (no se usa SELECT *)
    private const COLUMNAS = 'id, cliente_id, vehiculo_id, fecha_inicio, fecha_fin, fecha_devolucion_real, estado, created_at';

    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }



    // Lista todas las reservas (las usa el listado de reservas)
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT ' . self::COLUMNAS . ' FROM reservas ORDER BY id DESC');

        return $this->toList($stmt);
    }



    // Busca una reserva por id (null si no existe)
    public function findById(int $id): ?Reservation
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM reservas WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();

        return $fila === false ? null : $this->toModel($fila);
    }



    // Bloquea la fila de la reserva dentro de una transacción (SELECT ... FOR UPDATE)
    public function findByIdForUpdate(int $id): ?Reservation
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM reservas WHERE id = :id FOR UPDATE');
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();

        return $fila === false ? null : $this->toModel($fila);
    }



    // Historial de reservas de un vehículo (RF-08, incluye todos los estados)
    public function findByVehicle(int $vehicleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNAS . ' FROM reservas
             WHERE vehiculo_id = :vehiculo
             ORDER BY fecha_inicio DESC, id DESC'
        );
        $stmt->execute(['vehiculo' => $vehicleId]);

        return $this->toList($stmt);
    }



    // Historial de reservas de un cliente (RF-09, incluye todos los estados)
    public function findByClient(int $clientId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNAS . ' FROM reservas
             WHERE cliente_id = :cliente
             ORDER BY fecha_inicio DESC, id DESC'
        );
        $stmt->execute(['cliente' => $clientId]);

        return $this->toList($stmt);
    }



    // Reservas activas del vehículo que se cruzan con el rango pedido (RN-02)
    public function findOverlapping(int $vehicleId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNAS . ' FROM reservas
             WHERE vehiculo_id = :vehiculo
               AND estado = :estado
               AND fecha_inicio <= :fin
               AND fecha_fin >= :inicio'
        );
        $stmt->execute([
            'vehiculo' => $vehicleId,
            'estado' => ReservationStatus::Active->value,
            'fin' => $end->format('Y-m-d'),
            'inicio' => $start->format('Y-m-d'),
        ]);

        return $this->toList($stmt);
    }



    // Inserta una reserva nueva y le asigna el id generado
    public function insert(Reservation $reservation): Reservation
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reservas (cliente_id, vehiculo_id, fecha_inicio, fecha_fin, estado)
             VALUES (:cliente, :vehiculo, :inicio, :fin, :estado)'
        );
        $stmt->execute([
            'cliente' => $reservation->getClientId(),
            'vehiculo' => $reservation->getVehicleId(),
            'inicio' => $reservation->getStartDate()->format('Y-m-d'),
            'fin' => $reservation->getEndDate()->format('Y-m-d'),
            'estado' => $reservation->getStatus()->value,
        ]);

        $reservation->setId((int) $this->pdo->lastInsertId());

        return $reservation;
    }



    // Actualiza el estado de una reserva y su fecha real de devolución (la usa la devolución)
    public function update(Reservation $reservation): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE reservas
             SET estado = :estado, fecha_devolucion_real = :devolucion
             WHERE id = :id'
        );
        $stmt->execute([
            'estado' => $reservation->getStatus()->value,
            'devolucion' => $reservation->getActualReturnDate()?->format('Y-m-d'),
            'id' => $reservation->getId(),
        ]);
    }



    // Convierte el resultado de una consulta en una lista de reservas
    private function toList(PDOStatement $stmt): array
    {
        $res = [];
        foreach ($stmt->fetchAll() as $fila) {
            $res[] = $this->toModel($fila);
        }

        return $res;
    }



    // Convierte una fila de la BD en un objeto Reservation
    private function toModel(array $fila): Reservation
    {
        $devolucion = $fila['fecha_devolucion_real'];

        return new Reservation(
            (int) $fila['id'],
            (int) $fila['vehiculo_id'],
            (int) $fila['cliente_id'],
            $this->toDate($fila['fecha_inicio']),
            $this->toDate($fila['fecha_fin']),
            ReservationStatus::from($fila['estado']),
            $devolucion === null ? null : $this->toDate($devolucion),
            new DateTimeImmutable($fila['created_at'])
        );
    }



    // Convierte una fecha DATE (Y-m-d) en DateTimeImmutable a medianoche
    private function toDate(string $valor): DateTimeImmutable
    {
        $fecha = DateTimeImmutable::createFromFormat('Y-m-d|', $valor);

        return $fecha === false ? new DateTimeImmutable($valor) : $fecha;
    }
}
