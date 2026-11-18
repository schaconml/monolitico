<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

// Se lanza cuando los datos de un formulario no cumplen las reglas. Guarda un error por campo.
final class ValidationException extends RuntimeException
{
    public function __construct(private array $errors)
    {
        parent::__construct('Los datos enviados no son válidos.');
    }




    // Devuelve los errores con formato [campo => mensaje]
    public function getErrors(): array
    {
        return $this->errors;
    }
}
