<?php
// Valores del formulario: primero lo que se envió, si no vacío
$vals = [
    'client_id' => $old['client_id'] ?? '',
    'vehicle_id' => $old['vehicle_id'] ?? '',
    'start_date' => $old['start_date'] ?? '',
    'end_date' => $old['end_date'] ?? '',
];
$hoy = (new DateTimeImmutable('today'))->format('Y-m-d');
?>

<h1 class="h3 mb-3"><?= $e($title) ?></h1>

<?php if (count($clients) === 0 || count($vehicles) === 0): ?>
    <p class="text-muted">Para crear una reserva debe haber al menos un cliente y un vehículo registrados.</p>
    <a href="<?= $e($base) ?>/reservas" class="btn btn-outline-secondary">Volver</a>
<?php else: ?>
    <form method="post" action="<?= $e($base) ?>/reservas" class="row g-3" novalidate>
        <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">

        <div class="col-md-6">
            <label for="client_id" class="form-label">Cliente</label>
            <select id="client_id" name="client_id" class="form-select <?= isset($errors['client_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">Seleccione un cliente...</option>
                <?php foreach ($clients as $c): ?>
                    <option value="<?= $e($c->getId()) ?>" <?= (string) $vals['client_id'] === (string) $c->getId() ? 'selected' : '' ?>>
                        <?= $e($c->getName()) ?> (licencia <?= $e($c->getDriverLicense()) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback"><?= $e($errors['client_id'] ?? '') ?></div>
        </div>

        <div class="col-md-6">
            <label for="vehicle_id" class="form-label">Vehículo</label>
            <select id="vehicle_id" name="vehicle_id" class="form-select <?= isset($errors['vehicle_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">Seleccione un vehículo...</option>
                <?php foreach ($vehicles as $v): ?>
                    <option value="<?= $e($v->getId()) ?>" <?= (string) $vals['vehicle_id'] === (string) $v->getId() ? 'selected' : '' ?>>
                        <?= $e($v->getBrand() . ' ' . $v->getModel() . ' (' . $v->getStatus()->label() . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback"><?= $e($errors['vehicle_id'] ?? '') ?></div>
        </div>

        <div class="col-md-3">
            <label for="start_date" class="form-label">Fecha de inicio</label>
            <input type="date" id="start_date" name="start_date" class="form-control <?= isset($errors['start_date']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['start_date']) ?>" min="<?= $e($hoy) ?>" required>
            <div class="invalid-feedback"><?= $e($errors['start_date'] ?? '') ?></div>
        </div>

        <div class="col-md-3">
            <label for="end_date" class="form-label">Fecha de fin</label>
            <input type="date" id="end_date" name="end_date" class="form-control <?= isset($errors['end_date']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['end_date']) ?>" required>
            <div class="invalid-feedback"><?= $e($errors['end_date'] ?? '') ?></div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Guardar reserva</button>
            <a href="<?= $e($base) ?>/reservas" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
<?php endif; ?>
