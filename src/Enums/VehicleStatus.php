<?php

declare(strict_types=1);

namespace App\Enums;

// Estados posibles de un vehículo (el valor es el que se guarda en la BD)
enum VehicleStatus: string
{
    case Available = 'disponible';
    case Rented = 'alquilado';
    case Maintenance = 'mantenimiento';




    // Texto para mostrar en pantalla
    public function label(): string
    {
        return match ($this) {
            self::Available => 'Disponible',
            self::Rented => 'Alquilado',
            self::Maintenance => 'En mantenimiento',
        };
    }




    // Color de Bootstrap para la etiqueta del estado
    public function badge(): string
    {
        return match ($this) {
            self::Available => 'success',
            self::Rented => 'warning',
            self::Maintenance => 'secondary',
        };
    }
}
