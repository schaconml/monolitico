<h1 class="h3 mb-3">Vehículos disponibles</h1>

<!-- RF-07: sin fechas muestra los disponibles ahora; con fechas, los libres en el rango -->
<form method="get" action="<?= $e($base) ?>/reportes/disponibilidad" class="form-disponibilidad mb-3">
    <fieldset>
        <legend class="sr-only">Filtrar disponibilidad por rango de fechas</legend>
        <section class="form-fila">
            <label for="inicio" class="form-label">Desde</label>
            <input type="date" id="inicio" name="inicio" class="form-control" value="<?= $e($inicio) ?>">
        </section>
        <section class="form-fila">
            <label for="fin" class="form-label">Hasta</label>
            <input type="date" id="fin" name="fin" class="form-control" value="<?= $e($fin) ?>">
        </section>
        <section class="form-fila form-acciones">
            <button type="submit" class="btn btn-outline-secondary">Consultar</button>
            <a href="<?= $e($base) ?>/reportes/disponibilidad" class="btn btn-outline-secondary">Limpiar</a>
        </section>
    </fieldset>
</form>

<?php if ($rangoError !== null): ?>
    <p class="alert alert-danger" role="alert"><?= $e($rangoError) ?></p>
<?php elseif ($inicio === '' && $fin === ''): ?>
    <p class="text-muted">Consulta en tiempo real según el estado actual de la flota. Indique fechas para revisar un rango.</p>
<?php else: ?>
    <p class="text-muted">Consulta por rango de fechas.</p>
<?php endif; ?>

<?php if (count($vehicles) === 0 && $rangoError === null): ?>
    <p class="text-muted empty-state">No hay vehículos disponibles con ese criterio.</p>
<?php elseif (count($vehicles) > 0): ?>
    <section class="table-scroll" aria-labelledby="disponibilidad-table-title">
        <h2 id="disponibilidad-table-title" class="sr-only">Vehículos disponibles</h2>
        <table class="table table-striped align-middle">
            <caption>Vehículos disponibles según el criterio consultado</caption>
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Año</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Estado actual</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $v): ?>
                    <tr>
                        <td><?= $e($v->getId()) ?></td>
                        <td><?= $e($v->getBrand()) ?></td>
                        <td><?= $e($v->getModel()) ?></td>
                        <td><?= $e($v->getYear()) ?></td>
                        <td><?= $e($v->getCategory()) ?></td>
                        <td>
                            <data class="badge text-bg-<?= $e($v->getStatus()->badge()) ?>" value="<?= $e($v->getStatus()->value) ?>"><?= $e($v->getStatus()->label()) ?></data>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>
