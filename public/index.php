<?php

declare(strict_types=1);

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Contracts\Repositories\ReservationRepositoryInterface;
use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Contracts\Services\ClientServiceInterface;
use App\Contracts\Services\ReportServiceInterface;
use App\Contracts\Services\ReservationServiceInterface;
use App\Contracts\Services\VehicleServiceInterface;
use App\Contracts\TransactionManagerInterface;
use App\Controllers\ClientController;
use App\Controllers\HomeController;
use App\Controllers\ReportController;
use App\Controllers\ReservationController;
use App\Controllers\VehicleController;
use App\Core\Container;
use App\Core\Database;
use App\Core\Router;
use App\Core\TransactionManager;
use App\Repositories\ClientRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\VehicleRepository;
use App\Services\ClientService;
use App\Services\ReportService;
use App\Services\ReservationService;
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

date_default_timezone_set($config['timezone']);

define('VIEWS_PATH', dirname(__DIR__) . '/views');
define('BASE_URL', $config['base_url']);




// Contenedor: aquí se decide qué clase concreta usa cada interfaz
$container = new Container();

$container->bind(Database::class, fn() => new Database($config['db']));
$container->bind(TransactionManagerInterface::class, fn(Container $c) => new TransactionManager($c->get(Database::class)));

$container->bind(VehicleRepositoryInterface::class, fn(Container $c) => new VehicleRepository($c->get(Database::class)));
$container->bind(ClientRepositoryInterface::class, fn(Container $c) => new ClientRepository($c->get(Database::class)));
$container->bind(ReservationRepositoryInterface::class, fn(Container $c) => new ReservationRepository($c->get(Database::class)));

// VehicleService necesita el repositorio de reservas para la regla RN-05
$container->bind(VehicleServiceInterface::class, fn(Container $c) => new VehicleService(
    $c->get(VehicleRepositoryInterface::class),
    $c->get(ReservationRepositoryInterface::class)
));
$container->bind(ClientServiceInterface::class, fn(Container $c) => new ClientService($c->get(ClientRepositoryInterface::class)));
$container->bind(ReservationServiceInterface::class, fn(Container $c) => new ReservationService(
    $c->get(ReservationRepositoryInterface::class),
    $c->get(VehicleRepositoryInterface::class),
    $c->get(ClientRepositoryInterface::class),
    $c->get(TransactionManagerInterface::class)
));
$container->bind(ReportServiceInterface::class, fn(Container $c) => new ReportService(
    $c->get(VehicleRepositoryInterface::class),
    $c->get(ReservationRepositoryInterface::class)
));

$container->bind(HomeController::class, fn(Container $c) => new HomeController($c->get(ReportServiceInterface::class)));
$container->bind(VehicleController::class, fn(Container $c) => new VehicleController($c->get(VehicleServiceInterface::class)));
$container->bind(ClientController::class, fn(Container $c) => new ClientController($c->get(ClientServiceInterface::class)));
$container->bind(ReservationController::class, fn(Container $c) => new ReservationController(
    $c->get(ReservationServiceInterface::class),
    $c->get(VehicleServiceInterface::class),
    $c->get(ClientServiceInterface::class)
));
$container->bind(ReportController::class, fn(Container $c) => new ReportController(
    $c->get(ReportServiceInterface::class),
    $c->get(VehicleServiceInterface::class),
    $c->get(ClientServiceInterface::class)
));




// Rutas de la aplicación
$router = new Router($container, BASE_URL);

$router->get('/', [HomeController::class, 'index']);

$router->get('/vehiculos', [VehicleController::class, 'index']);
$router->get('/vehiculos/crear', [VehicleController::class, 'create']);
$router->post('/vehiculos', [VehicleController::class, 'store']);
$router->get('/vehiculos/{id}/editar', [VehicleController::class, 'edit']);
$router->post('/vehiculos/{id}/editar', [VehicleController::class, 'update']);
$router->post('/vehiculos/{id}/estado', [VehicleController::class, 'changeStatus']);
$router->get('/vehiculos/{id}/historial', [ReportController::class, 'vehicleHistory']);

$router->get('/clientes', [ClientController::class, 'index']);
$router->get('/clientes/crear', [ClientController::class, 'create']);
$router->post('/clientes', [ClientController::class, 'store']);
$router->get('/clientes/{id}/historial', [ReportController::class, 'clientHistory']);

$router->get('/reservas', [ReservationController::class, 'index']);
$router->get('/reservas/crear', [ReservationController::class, 'create']);
$router->post('/reservas', [ReservationController::class, 'store']);
$router->get('/reservas/{id}/devolucion', [ReservationController::class, 'return']);
$router->post('/reservas/{id}/devolucion', [ReservationController::class, 'processReturn']);

$router->get('/reportes/disponibilidad', [ReportController::class, 'availability']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
