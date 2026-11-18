<?php

declare(strict_types=1);

namespace App\Exceptions;

use DomainException;

// Se lanza cuando el rango de fechas no es válido: fin anterior al inicio (RN-01)
// o devolución registrada antes de la fecha de inicio de la reserva. La captura ReservationController.
final class InvalidDateRangeException extends DomainException
{
    public function __construct(string $message = 'La fecha de fin no puede ser anterior a la fecha de inicio.')
    {
        parent::__construct($message);
    }
}
