<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

// Maneja la conexión PDO. Se crea una sola vez y solo cuando se necesita.
final class Database
{
    private ?PDO $pdo = null;

    public function __construct(private array $config)
    {
    }




    // Devuelve la conexión, creándola si todavía no existe
    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $this->config['host'],
                $this->config['port'],
                $this->config['name']
            );

            $this->pdo = new PDO($dsn, $this->config['user'], $this->config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return $this->pdo;
    }
}
