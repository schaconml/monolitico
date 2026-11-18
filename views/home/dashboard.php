<h1 class="h3 mb-3">Gestor de Alquiler de Vehículos</h1>

<!-- RF-10: contadores de la flota por estado -->
<section class="mb-4">
    <h2 class="h5">Estado de la flota</h2>
    <div class="row g-3">
        <?php foreach (\App\Enums\VehicleStatus::cases() as $estado): ?>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <p class="h2 mb-1"><?= (int) ($counters[$estado->value] ?? 0) ?></p>
                        <span class="badge text-bg-<?= $e($estado->badge()) ?>"><?= $e($estado->label()) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<p class="text-muted">Administre la flota y los clientes desde las siguientes secciones.</p>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Vehículos</h2>
                <p class="card-text">Registro, consulta y cambio de estado de la flota.</p>
                <a href="<?= $e($base) ?>/vehiculos" class="btn btn-primary">Ver vehículos</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Clientes</h2>
                <p class="card-text">Registro y consulta de clientes con su licencia de conducción.</p>
                <a href="<?= $e($base) ?>/clientes" class="btn btn-primary">Ver clientes</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Reservas</h2>
                <p class="card-text">Creación de reservas y registro de devoluciones.</p>
                <a href="<?= $e($base) ?>/reservas" class="btn btn-primary">Ver reservas</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Disponibilidad</h2>
                <p class="card-text">Consulta de vehículos libres ahora o por rango de fechas.</p>
                <a href="<?= $e($base) ?>/reportes/disponibilidad" class="btn btn-primary">Consultar</a>
            </div>
        </div>
    </div>
</div>
