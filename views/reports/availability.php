<h1 class="h3 mb-3">Vehículos disponibles</h1>

<!-- RF-07: sin fechas muestra los disponibles ahora; con fechas, los libres en el rango -->
<form method="get" action="<?= $e($base) ?>/reportes/disponibilidad" class="row g-2 mb-3 align-items-end">
    <div class="col-auto">
        <label for="inicio" class="form-label">Desde</label>
        <input type="date" id="inicio" name="inicio" class="form-control" value="<?= $e($inicio) ?>">
    </div>
    <div class="col-auto">
        <label for="fin" class="form-label">Hasta</label>
        <input type="date" id="fin" name="fin" class="form-control" value="<?= $e($fin) ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary">Consultar</button>
        <a href="<?= $e($base) ?>/reportes/disponibilidad" class="btn btn-outline-secondary">Limpiar</a>
    </div>
</form>

<?php if ($rangoError !== null): ?>
    <p class="alert alert-danger" role="alert"><?= $e($rangoError) ?></p>
<?php elseif ($inicio === '' && $fin === ''): ?>
    <p class="text-muted">Consulta en tiempo real según el estado actual de la flota. Indique fechas para revisar un rango.</p>
<?php else: ?>
    <p class="text-muted">Consulta por rango de fechas.</p>
<?php endif; ?>

<?php if (count($vehicles) === 0 && $rangoError === null): ?>
    <p class="text-muted">No hay vehículos disponibles con ese criterio.</p>
<?php elseif (count($vehicles) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Categoría</th>
                    <th>Estado actual</th>
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
                            <span class="badge text-bg-<?= $e($v->getStatus()->badge()) ?>">
                                <?= $e($v->getStatus()->label()) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
