<?php

declare(strict_types=1);

namespace App\Core;

// Controlador base: aquí están las utilidades comunes (mostrar vistas, redirigir, mensajes).
abstract class Controller
{
    // Carga una vista dentro del layout. $data se convierte en variables dentro de la vista.
    protected function view(string $name, array $data = []): void
    {
        $e = fn(mixed $valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
        $base = BASE_URL;

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        extract($data);

        require VIEWS_PATH . '/layouts/header.php';
        require VIEWS_PATH . '/' . $name . '.php';
        require VIEWS_PATH . '/layouts/footer.php';
    }




    // Redirige a otra ruta de la aplicación
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }




    // Guarda un mensaje que se muestra una vez en la siguiente pantalla (lo lee header.php)
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}
