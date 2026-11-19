<?php

declare(strict_types=1);

// Configuración general. Los valores se pueden cambiar con variables de entorno.
// La ruta base de la aplicación se detecta automáticamente en public/index.php
// a partir de SCRIPT_NAME (ej. /monolitico). Se puede forzar con APP_BASE_URL.
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'alquiler_vehiculos_db',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],
    // Ruta base de la aplicación (vacía si corre en la raíz del dominio).
    'base_url' => getenv('APP_BASE_URL') ?: '',
    // Zona horaria del negocio: no se depende de la configuración del servidor.
    'timezone' => getenv('APP_TIMEZONE') ?: 'America/Bogota',
];
