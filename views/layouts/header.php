<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title ?? 'Gestor de Alquiler de Vehículos') ?> | Gestor de Alquiler</title>
    <link href="<?= $e($base) ?>/assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <a class="skip-link" href="#contenido">Saltar al contenido principal</a>

    <header class="site-header">
        <nav aria-label="Navegación principal">
            <ul class="site-nav">
                <li><a href="<?= $e($base) ?>/">Gestor de Alquiler</a></li>
                <li><a href="<?= $e($base) ?>/">Inicio</a></li>
                <li><a href="<?= $e($base) ?>/vehiculos">Vehículos</a></li>
                <li><a href="<?= $e($base) ?>/clientes">Clientes</a></li>
                <li><a href="<?= $e($base) ?>/reservas">Reservas</a></li>
                <li><a href="<?= $e($base) ?>/reportes/disponibilidad">Disponibilidad</a></li>
            </ul>
        </nav>
    </header>

    <main id="contenido">
        <!-- Mensaje de resultado (viene de Controller::flash) -->
        <?php if ($flash !== null): ?>
            <p class="mensaje" data-tipo="<?= $e($flash['type']) ?>" role="alert"><?= $e($flash['message']) ?></p>
        <?php endif; ?>
