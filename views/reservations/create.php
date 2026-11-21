<?php
// Valores del formulario: primero lo que se envió, si no vacío
$vals = [
    'client_id' => $old['client_id'] ?? '',
    'vehicle_id' => $old['vehicle_id'] ?? '',
    'start_date' => $old['start_date'] ?? '',
    'end_date' => $old['end_date'] ?? '',
];
?>

<h1><?= $e($title) ?></h1>

<?php if (count($clients) === 0 || count($vehicles) === 0): ?>
    <p>Para crear una reserva debe haber al menos un cliente y un vehículo registrados.</p>
    <a href="<?= $e($base) ?>/reservas">Volver</a>
<?php else: ?>
    <form method="post" action="<?= $e($base) ?>/reservas" class="formulario-reserva" novalidate accept-charset="UTF-8">
        <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">

        <fieldset>
            <legend>Datos de la reserva</legend>

            <p class="form-fila">
                <label for="client_id">Cliente</label>
                <select id="client_id" name="client_id" <?= isset($errors['client_id']) ? 'aria-invalid="true" aria-describedby="client_id-error"' : '' ?> required>
                    <option value="">Seleccione un cliente...</option>
                    <?php foreach ($clients as $c): ?>
                        <option value="<?= $e($c->getId()) ?>" <?= (string) $vals['client_id'] === (string) $c->getId() ? 'selected' : '' ?>>
                            <?= $e($c->getName()) ?> (licencia <?= $e($c->getDriverLicense()) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['client_id'])): ?>
                    <small id="client_id-error" class="field-error"><?= $e($errors['client_id']) ?></small>
                <?php endif; ?>
            </p>

            <p class="form-fila">
                <label for="vehicle_id">Vehículo</label>
                <select id="vehicle_id" name="vehicle_id" <?= isset($errors['vehicle_id']) ? 'aria-invalid="true" aria-describedby="vehicle_id-error"' : '' ?> required>
                    <option value="">Seleccione un vehículo...</option>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?= $e($v->getId()) ?>" <?= (string) $vals['vehicle_id'] === (string) $v->getId() ? 'selected' : '' ?>>
                            <?= $e($v->getBrand() . ' ' . $v->getModel() . ' (' . $v->getStatus()->label() . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['vehicle_id'])): ?>
                    <small id="vehicle_id-error" class="field-error"><?= $e($errors['vehicle_id']) ?></small>
                <?php endif; ?>
            </p>

            <p class="form-fila">
                <label for="start_date">Fecha de inicio</label>
                <input type="date" id="start_date" name="start_date" <?= isset($errors['start_date']) ? 'aria-invalid="true" aria-describedby="start_date-error"' : '' ?> value="<?= $e($vals['start_date']) ?>" min="<?= $e($hoy ?? '') ?>" required>
                <?php if (isset($errors['start_date'])): ?>
                    <small id="start_date-error" class="field-error"><?= $e($errors['start_date']) ?></small>
                <?php endif; ?>
            </p>

            <p class="form-fila">
                <label for="end_date">Fecha de fin</label>
                <input type="date" id="end_date" name="end_date" <?= isset($errors['end_date']) ? 'aria-invalid="true" aria-describedby="end_date-error"' : '' ?> value="<?= $e($vals['end_date']) ?>" required>
                <?php if (isset($errors['end_date'])): ?>
                    <small id="end_date-error" class="field-error"><?= $e($errors['end_date']) ?></small>
                <?php endif; ?>
            </p>
        </fieldset>

        <p class="form-acciones">
            <button type="submit">Guardar reserva</button>
            <a href="<?= $e($base) ?>/reservas">Cancelar</a>
        </p>
    </form>
<?php endif; ?>
