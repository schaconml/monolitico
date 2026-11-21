<h1>Vehículos disponibles</h1>

<!-- RF-07: sin fechas muestra los disponibles ahora; con fechas, los libres en el rango -->
<form method="get" action="<?= $e($base) ?>/reportes/disponibilidad" class="form-disponibilidad">
    <fieldset>
        <legend class="sr-only">Filtrar disponibilidad por rango de fechas</legend>
        <p class="form-fila">
            <label for="inicio">Desde</label>
            <input type="date" id="inicio" name="inicio" value="<?= $e($inicio) ?>">
        </p>
        <p class="form-fila">
            <label for="fin">Hasta</label>
            <input type="date" id="fin" name="fin" value="<?= $e($fin) ?>">
        </p>
        <p class="form-fila form-acciones">
            <button type="submit">Consultar</button>
            <a href="<?= $e($base) ?>/reportes/disponibilidad">Limpiar</a>
        </p>
    </fieldset>
</form>

<?php if ($rangoError !== null): ?>
    <p class="mensaje" role="alert"><?= $e($rangoError) ?></p>
<?php elseif ($inicio === '' && $fin === ''): ?>
    <p>Consulta en tiempo real según el estado actual de la flota. Indique fechas para revisar un rango.</p>
<?php else: ?>
    <p>Consulta por rango de fechas.</p>
<?php endif; ?>

<?php if (count($vehicles) === 0 && $rangoError === null): ?>
    <p class="empty-state">No hay vehículos disponibles con ese criterio.</p>
<?php elseif (count($vehicles) > 0): ?>
    <section class="table-scroll" aria-labelledby="disponibilidad-table-title">
        <h2 id="disponibilidad-table-title" class="sr-only">Vehículos disponibles</h2>
        <table>
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
                            <data value="<?= $e($v->getStatus()->value) ?>"><?= $e($v->getStatus()->label()) ?></data>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>
