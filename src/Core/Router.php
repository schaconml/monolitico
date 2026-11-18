<?php

declare(strict_types=1);

namespace App\Core;

// Enrutador: relaciona método HTTP + ruta con un método de controlador.
final class Router
{
    private array $routes = [];

    public function __construct(private Container $container, private string $basePath = '')
    {
    }




    // Registra una ruta GET
    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }




    // Registra una ruta POST
    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }




    // Convierte {param} en una expresión regular y guarda la ruta
    private function add(string $method, string $path, array $handler): void
    {
        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path) . '$#';
        $this->routes[] = ['method' => $method, 'regex' => $regex, 'handler' => $handler];
    }




    // Busca la ruta que coincide y ejecuta el controlador (usa Container para crearlo).
    // Antes de despachar un POST verifica el token CSRF; si no coincide responde 403.
    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if ($this->basePath !== '' && str_starts_with($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
        }

        $path = '/' . trim($path, '/');

        $coincideRuta = false;

        foreach ($this->routes as $ruta) {
            if (preg_match($ruta['regex'], $path, $coincidencias) !== 1) {
                continue;
            }

            $coincideRuta = true;

            if ($ruta['method'] !== $method) {
                continue;
            }

            if ($method === 'POST' && !$this->csrfValido()) {
                http_response_code(403);
                echo 'Solicitud rechazada: token de seguridad inválido.';
                return;
            }

            $params = [];
            foreach ($coincidencias as $clave => $valor) {
                if (is_string($clave)) {
                    $params[$clave] = $valor;
                }
            }

            [$clase, $accion] = $ruta['handler'];
            $controlador = $this->container->get($clase);
            $controlador->{$accion}(...$params);
            return;
        }

        if ($coincideRuta) {
            http_response_code(405);
            echo 'Método no permitido para esta ruta.';
            return;
        }

        http_response_code(404);
        echo 'Página no encontrada';
    }



    // Compara el token del formulario con el guardado en sesión por Controller::csrfToken
    private function csrfValido(): bool
    {
        $token = $_POST['_token'] ?? '';
        $guardado = $_SESSION['csrf_token'] ?? '';

        return is_string($token) && $token !== '' && is_string($guardado) && hash_equals($guardado, $token);
    }
}
