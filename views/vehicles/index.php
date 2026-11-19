<section class="vehicles-header mb-3" aria-labelledby="vehiculos-title">
    <h1 id="vehiculos-title" class="h3 mb-0">Vehículos</h1>
    <a href="<?= $e($base) ?>/vehiculos/crear" class="btn btn-primary">Registrar vehículo</a>
</section>

<!-- Filtro por estado -->
<form method="get" action="<?= $e($base) ?>/vehiculos" class="filtro-form mb-3">
    <fieldset>
        <legend class="sr-only">Filtrar vehículos por estado</legend>
        <select name="estado" class="form-select">
            <option value="">Todos los estados</option>
            <?php foreach (\App\Enums\VehicleStatus::cases() as $caso): ?>
                <option value="<?= $e($caso->value) ?>" <?= $filter === $caso ? 'selected' : '' ?>>
                    <?= $e($caso->label()) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
    </fieldset>
</form>

<?php if (count($vehicles) === 0): ?>
    <p class="text-muted empty-state">No hay vehículos para mostrar.</p>
<?php else: ?>
    <section class="table-scroll" aria-labelledby="vehiculos-table-title">
        <h2 id="vehiculos-table-title" class="sr-only">Listado de vehículos</h2>
        <table class="table table-striped align-middle">
            <caption>Vehículos registrados en la flota</caption>
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Año</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
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
                        <td class="text-nowrap">
                            <a href="<?= $e($base) ?>/vehiculos/<?= $e($v->getId()) ?>/historial" class="btn btn-sm btn-outline-secondary">Historial</a>
                            <a href="<?= $e($base) ?>/vehiculos/<?= $e($v->getId()) ?>/editar" class="btn btn-sm btn-outline-primary">Editar</a>

                            <!-- Un vehículo alquilado no puede cambiar de estado a mano -->
                            <?php if (!$v->isRented()): ?>
                                <form method="post" action="<?= $e($base) ?>/vehiculos/<?= $e($v->getId()) ?>/estado" data-confirm="¿Desea cambiar el estado de este vehículo?">
                                    <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">
                                    <?php if ($v->isAvailable()): ?>
                                        <input type="hidden" name="status" value="mantenimiento">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Enviar a mantenimiento</button>
                                    <?php else: ?>
                                        <input type="hidden" name="status" value="disponible">
                                        <button type="submit" class="btn btn-sm btn-outline-success">Marcar disponible</button>
                                    <?php endif; ?>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>
