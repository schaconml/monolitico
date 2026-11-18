<?php

declare(strict_types=1);

namespace App\Contracts;

// Delimita las transacciones que orquestan los servicios (patrón del contrato de persistencia).
// El límite transaccional vive en el servicio y la implementación está en App\Core.
interface TransactionManagerInterface
{
    // Abre la transacción (BEGIN)
    public function begin(): void;


    // Confirma los cambios (COMMIT)
    public function commit(): void;


    // Cancela los cambios (ROLLBACK)
    public function rollback(): void;
}
