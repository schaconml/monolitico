<<<<<<< HEAD
<header class="vehicles-header">
    <h1>Vehículos</h1>
    <a href="<?= $e($base) ?>/vehiculos/crear">Registrar vehículo</a>
</header>
=======
<section class="vehicles-header" aria-labelledby="vehiculos-title">
    <h1 id="vehiculos-title">Vehículos</h1>
    <a href="<?= $e($base) ?>/vehiculos/crear">Registrar vehículo</a>
</section>
>>>>>>> 035cf0eb1e72d0dd7723c05c1fa64aa4bfa56dc6

<!-- Filtro por estado -->
<form method="get" action="<?= $e($base) ?>/vehiculos" class="filtro-form">
    <fieldset>
        <legend class="sr-only">Filtrar vehículos por estado</legend>
        <select name="estado">
            <option value="">Todos los estados</option>
            <?php foreach (\App\Enums\VehicleStatus::cases() as $caso): ?>
                <option value="<?= $e($caso->value) ?>" <?= $filter === $caso ? 'selected' : '' ?>>
                    <?= $e($caso->label()) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </fieldset>
</form>

<?php if (count($vehicles) === 0): ?>
    <p class="empty-state">No hay vehículos para mostrar.</p>
<?php else: ?>
    <section class="table-scroll" aria-labelledby="vehiculos-table-title">
        <h2 id="vehiculos-table-title" class="sr-only">Listado de vehículos</h2>
        <table>
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
                            <data value="<?= $e($v->getStatus()->value) ?>"><?= $e($v->getStatus()->label()) ?></data>
                        </td>
                        <td>
                            <a href="<?= $e($base) ?>/vehiculos/<?= $e($v->getId()) ?>/historial">Historial</a>
                            <a href="<?= $e($base) ?>/vehiculos/<?= $e($v->getId()) ?>/editar">Editar</a>

                            <!-- Un vehículo alquilado no puede cambiar de estado a mano -->
                            <?php if (!$v->isRented()): ?>
                                <form method="post" action="<?= $e($base) ?>/vehiculos/<?= $e($v->getId()) ?>/estado" data-confirm="¿Desea cambiar el estado de este vehículo?">
                                    <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">
                                    <?php if ($v->isAvailable()): ?>
                                        <input type="hidden" name="status" value="mantenimiento">
                                        <button type="submit">Enviar a mantenimiento</button>
                                    <?php else: ?>
                                        <input type="hidden" name="status" value="disponible">
                                        <button type="submit">Marcar disponible</button>
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
