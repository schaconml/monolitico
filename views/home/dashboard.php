<h1>Gestor de Alquiler de Vehículos</h1>

<!-- RF-10: contadores de la flota por estado -->
<section aria-labelledby="flota-title">
    <h2 id="flota-title">Estado de la flota</h2>
    <ul class="stats-list">
        <?php foreach (\App\Enums\VehicleStatus::cases() as $estado): ?>
            <li class="stats-item">
<<<<<<< HEAD
                    <p class="contador"><?= (int) ($counters[$estado->value] ?? 0) ?></p>
                    <data value="<?= $e($estado->value) ?>"><?= $e($estado->label()) ?></data>
=======
                <article>
                    <p><?= (int) ($counters[$estado->value] ?? 0) ?></p>
                    <data value="<?= $e($estado->value) ?>"><?= $e($estado->label()) ?></data>
                </article>
>>>>>>> 035cf0eb1e72d0dd7723c05c1fa64aa4bfa56dc6
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<p>Administre la flota y los clientes desde las siguientes secciones.</p>

<section aria-labelledby="modulos-title">
    <h2 id="modulos-title" class="sr-only">Módulos de la aplicación</h2>
    <ul class="modulos-grid">
        <li>
<<<<<<< HEAD
            <h3>Vehículos</h3>
            <p>Registro, consulta y cambio de estado de la flota.</p>
            <a href="<?= $e($base) ?>/vehiculos">Ver vehículos</a>
        </li>
        <li>
            <h3>Clientes</h3>
            <p>Registro y consulta de clientes con su licencia de conducción.</p>
            <a href="<?= $e($base) ?>/clientes">Ver clientes</a>
        </li>
        <li>
            <h3>Reservas</h3>
            <p>Creación de reservas y registro de devoluciones.</p>
            <a href="<?= $e($base) ?>/reservas">Ver reservas</a>
        </li>
        <li>
            <h3>Disponibilidad</h3>
            <p>Consulta de vehículos libres ahora o por rango de fechas.</p>
            <a href="<?= $e($base) ?>/reportes/disponibilidad">Consultar</a>
=======
            <article>
                <h2>Vehículos</h2>
                <p>Registro, consulta y cambio de estado de la flota.</p>
                <a href="<?= $e($base) ?>/vehiculos">Ver vehículos</a>
            </article>
        </li>
        <li>
            <article>
                <h2>Clientes</h2>
                <p>Registro y consulta de clientes con su licencia de conducción.</p>
                <a href="<?= $e($base) ?>/clientes">Ver clientes</a>
            </article>
        </li>
        <li>
            <article>
                <h2>Reservas</h2>
                <p>Creación de reservas y registro de devoluciones.</p>
                <a href="<?= $e($base) ?>/reservas">Ver reservas</a>
            </article>
        </li>
        <li>
            <article>
                <h2>Disponibilidad</h2>
                <p>Consulta de vehículos libres ahora o por rango de fechas.</p>
                <a href="<?= $e($base) ?>/reportes/disponibilidad">Consultar</a>
            </article>
>>>>>>> 035cf0eb1e72d0dd7723c05c1fa64aa4bfa56dc6
        </li>
    </ul>
</section>
