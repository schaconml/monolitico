<?php

declare(strict_types=1);

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Contracts\Services\ClientServiceInterface;
use App\Contracts\Services\VehicleServiceInterface;
use App\Controllers\ClientController;
use App\Controllers\HomeController;
use App\Controllers\VehicleController;
use App\Core\Container;
use App\Core\Database;
use App\Core\Router;
use App\Repositories\ClientRepository;
use App\Repositories\VehicleRepository;
use App\Services\ClientService;
use App\Services\VehicleService;

// Con el servidor interno de PHP los archivos estáticos se sirven solos
if (PHP_SAPI === 'cli-server') {
    $archivo = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($archivo)) {
        return false;
    }
}

require dirname(__DIR__) . '/vendor/autoload.php';

session_start();

$config = require dirname(__DIR__) . '/config/config.php';

define('VIEWS_PATH', dirname(__DIR__) . '/views');
define('BASE_URL', $config['base_url']);




// Contenedor: aquí se decide qué clase concreta usa cada interfaz
$container = new Container();

$container->bind(Database::class, fn() => new Database($config['db']));

$container->bind(VehicleRepositoryInterface::class, fn(Container $c) => new VehicleRepository($c->get(Database::class)));
$container->bind(ClientRepositoryInterface::class, fn(Container $c) => new ClientRepository($c->get(Database::class)));

$container->bind(VehicleServiceInterface::class, fn(Container $c) => new VehicleService($c->get(VehicleRepositoryInterface::class)));
$container->bind(ClientServiceInterface::class, fn(Container $c) => new ClientService($c->get(ClientRepositoryInterface::class)));

$container->bind(HomeController::class, fn() => new HomeController());
$container->bind(VehicleController::class, fn(Container $c) => new VehicleController($c->get(VehicleServiceInterface::class)));
$container->bind(ClientController::class, fn(Container $c) => new ClientController($c->get(ClientServiceInterface::class)));




// Rutas de la aplicación
$router = new Router($container, BASE_URL);

$router->get('/', [HomeController::class, 'index']);

$router->get('/vehiculos', [VehicleController::class, 'index']);
$router->get('/vehiculos/crear', [VehicleController::class, 'create']);
$router->post('/vehiculos', [VehicleController::class, 'store']);
$router->get('/vehiculos/{id}/editar', [VehicleController::class, 'edit']);
$router->post('/vehiculos/{id}/editar', [VehicleController::class, 'update']);
$router->post('/vehiculos/{id}/estado', [VehicleController::class, 'changeStatus']);

$router->get('/clientes', [ClientController::class, 'index']);
$router->get('/clientes/crear', [ClientController::class, 'create']);
$router->post('/clientes', [ClientController::class, 'store']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
