<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Vehículos</h1>
    <a href="<?= $e($base) ?>/vehiculos/crear" class="btn btn-primary">Registrar vehículo</a>
</div>

<!-- Filtro por estado -->
<form method="get" action="<?= $e($base) ?>/vehiculos" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="estado" class="form-select">
            <option value="">Todos los estados</option>
            <?php foreach (\App\Enums\VehicleStatus::cases() as $caso): ?>
                <option value="<?= $e($caso->value) ?>" <?= $filter === $caso ? 'selected' : '' ?>>
                    <?= $e($caso->label()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
    </div>
</form>

<?php if (count($vehicles) === 0): ?>
    <p class="text-muted">No hay vehículos para mostrar.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
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
                        <td class="text-nowrap">
                            <a href="<?= $e($base) ?>/vehiculos/<?= $e($v->getId()) ?>/editar" class="btn btn-sm btn-outline-primary">Editar</a>

                            <!-- Un vehículo alquilado no puede cambiar de estado a mano -->
                            <?php if (!$v->isRented()): ?>
                                <form method="post" action="<?= $e($base) ?>/vehiculos/<?= $e($v->getId()) ?>/estado" class="d-inline" data-confirm="¿Desea cambiar el estado de este vehículo?">
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
    </div>
<?php endif; ?>
