<?php

declare(strict_types=1);

// Configuración general. Los valores se pueden cambiar con variables de entorno.
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'alquiler_vehiculos_db',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],
    // Ruta base de la aplicación (vacía si corre en la raíz del dominio).
    'base_url' => rtrim(getenv('APP_BASE_URL') ?: '', '/'),
];
