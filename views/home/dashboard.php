<h1 class="h3 mb-3">Gestor de Alquiler de Vehículos</h1>

<!-- RF-10: contadores de la flota por estado -->
<section class="mb-4" aria-labelledby="flota-title">
    <h2 id="flota-title" class="h5">Estado de la flota</h2>
    <ul class="stats-list">
        <?php foreach (\App\Enums\VehicleStatus::cases() as $estado): ?>
            <li class="stats-item">
                <article class="card text-center">
                    <p class="h2 mb-1"><?= (int) ($counters[$estado->value] ?? 0) ?></p>
                    <data class="badge text-bg-<?= $e($estado->badge()) ?>" value="<?= $e($estado->value) ?>"><?= $e($estado->label()) ?></data>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<p class="text-muted">Administre la flota y los clientes desde las siguientes secciones.</p>

<section aria-labelledby="modulos-title">
    <h2 id="modulos-title" class="sr-only">Módulos de la aplicación</h2>
    <ul class="modulos-grid">
        <li>
            <article class="card h-100">
                <h2 class="h5 card-title">Vehículos</h2>
                <p class="card-text">Registro, consulta y cambio de estado de la flota.</p>
                <a href="<?= $e($base) ?>/vehiculos" class="btn btn-primary">Ver vehículos</a>
            </article>
        </li>
        <li>
            <article class="card h-100">
                <h2 class="h5 card-title">Clientes</h2>
                <p class="card-text">Registro y consulta de clientes con su licencia de conducción.</p>
                <a href="<?= $e($base) ?>/clientes" class="btn btn-primary">Ver clientes</a>
            </article>
        </li>
        <li>
            <article class="card h-100">
                <h2 class="h5 card-title">Reservas</h2>
                <p class="card-text">Creación de reservas y registro de devoluciones.</p>
                <a href="<?= $e($base) ?>/reservas" class="btn btn-primary">Ver reservas</a>
            </article>
        </li>
        <li>
            <article class="card h-100">
                <h2 class="h5 card-title">Disponibilidad</h2>
                <p class="card-text">Consulta de vehículos libres ahora o por rango de fechas.</p>
                <a href="<?= $e($base) ?>/reportes/disponibilidad" class="btn btn-primary">Consultar</a>
            </article>
        </li>
    </ul>
</section>
