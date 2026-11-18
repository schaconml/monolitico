<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Core\Database;
use App\Models\Client;
use DateTimeImmutable;
use PDO;

// Lee y guarda clientes en la tabla "clientes".
final class ClientRepository implements ClientRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }




    // Lista todos los clientes
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM clientes ORDER BY id DESC');

        $res = [];
        foreach ($stmt->fetchAll() as $fila) {
            $res[] = $this->toModel($fila);
        }

        return $res;
    }




    // Busca un cliente por id (null si no existe)
    public function findById(int $id): ?Client
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clientes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();

        if ($fila === false) {
            return null;
        }

        return $this->toModel($fila);
    }




    // Busca un cliente por número de licencia (lo usa ClientService para evitar duplicados)
    public function findByLicense(string $license): ?Client
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clientes WHERE numero_licencia = :licencia');
        $stmt->execute(['licencia' => $license]);
        $fila = $stmt->fetch();

        if ($fila === false) {
            return null;
        }

        return $this->toModel($fila);
    }




    // Inserta un cliente nuevo y le asigna el id generado
    public function insert(Client $client): Client
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO clientes (nombre, telefono, correo, numero_licencia)
             VALUES (:nombre, :telefono, :correo, :licencia)'
        );
        $stmt->execute([
            'nombre' => $client->getName(),
            'telefono' => $client->getPhone(),
            'correo' => $client->getEmail(),
            'licencia' => $client->getDriverLicense(),
        ]);

        $client->setId((int) $this->pdo->lastInsertId());

        return $client;
    }




    // Convierte una fila de la BD en un objeto Client
    private function toModel(array $fila): Client
    {
        return new Client(
            (int) $fila['id'],
            $fila['nombre'],
            (string) $fila['telefono'],
            (string) $fila['correo'],
            $fila['numero_licencia'],
            new DateTimeImmutable($fila['created_at'])
        );
    }
}
