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




    // Busca la ruta que coincide y ejecuta el controlador (usa Container para crearlo)
    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if ($this->basePath !== '' && str_starts_with($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
        }

        $path = '/' . trim($path, '/');

        foreach ($this->routes as $ruta) {
            if ($ruta['method'] !== $method) {
                continue;
            }

            if (preg_match($ruta['regex'], $path, $coincidencias) === 1) {
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
        }

        http_response_code(404);
        echo 'Página no encontrada';
    }
}
