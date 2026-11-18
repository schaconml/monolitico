<?php

declare(strict_types=1);

namespace App\Enums;

// Estados posibles de una reserva
enum ReservationStatus: string
{
    case Active = 'activa';
    case Completed = 'completada';
    case Cancelled = 'cancelada';




    // Texto para mostrar en pantalla
    public function label(): string
    {
        return match ($this) {
            self::Active => 'Activa',
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
        };
    }
}
