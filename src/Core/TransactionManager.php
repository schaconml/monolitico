<?php

declare(strict_types=1);

namespace App\Core;

use App\Contracts\TransactionManagerInterface;
use PDO;

// Implementa las transacciones sobre la conexión PDO compartida por los repositorios.
// Los servicios la usan para coordinar cambios en varias tablas (reservas y vehículos).
final class TransactionManager implements TransactionManagerInterface
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }



    // Abre la transacción (BEGIN)
    public function begin(): void
    {
        $this->pdo->beginTransaction();
    }



    // Confirma los cambios (COMMIT)
    public function commit(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }



    // Cancela los cambios (ROLLBACK)
    public function rollback(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }
}
