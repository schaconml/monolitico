<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title ?? 'Gestor de Alquiler de Vehículos') ?> | Gestor de Alquiler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $e($base) ?>/assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= $e($base) ?>/">Gestor de Alquiler</a>
            <div class="navbar-nav">
                <a class="nav-link" href="<?= $e($base) ?>/vehiculos">Vehículos</a>
                <a class="nav-link" href="<?= $e($base) ?>/clientes">Clientes</a>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <!-- Mensaje de resultado (viene de Controller::flash) -->
        <?php if ($flash !== null): ?>
            <div class="alert alert-<?= $e($flash['type']) ?>" role="alert"><?= $e($flash['message']) ?></div>
        <?php endif; ?>
