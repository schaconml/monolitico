<?php

declare(strict_types=1);

namespace App\Exceptions;

use DomainException;

// Se lanza cuando el vehículo no puede reservarse: está en mantenimiento o tiene
// reservas activas que se cruzan con las fechas pedidas (RN-02). La captura ReservationController.
final class VehicleNotAvailableException extends DomainException
{
    public function __construct(string $message = 'El vehículo no está disponible para las fechas solicitadas.')
    {
        parent::__construct($message);
    }
}
