<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use RuntimeException;

// Contenedor sencillo de dependencias. Guarda cómo construir cada clase.
final class Container
{
    private array $factories = [];
    private array $instances = [];




    // Registra la forma de construir una clase o interfaz
    public function bind(string $id, Closure $factory): void
    {
        $this->factories[$id] = $factory;
    }




    // Devuelve la instancia (la crea la primera vez y luego la reutiliza)
    public function get(string $id): object
    {
        if (!isset($this->instances[$id])) {
            if (!isset($this->factories[$id])) {
                throw new RuntimeException("No hay nada registrado para {$id}");
            }

            $this->instances[$id] = ($this->factories[$id])($this);
        }

        return $this->instances[$id];
    }
}
