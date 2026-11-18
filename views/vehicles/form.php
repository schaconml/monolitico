<?php
// Valores del formulario: primero lo que se envió, luego el vehículo, si no vacío
$vals = [
    'brand' => $old['brand'] ?? ($vehicle?->getBrand() ?? ''),
    'model' => $old['model'] ?? ($vehicle?->getModel() ?? ''),
    'year' => $old['year'] ?? ($vehicle?->getYear() ?? ''),
    'category' => $old['category'] ?? ($vehicle?->getCategory() ?? ''),
    'status' => $old['status'] ?? 'disponible',
];
$accion = $vehicle === null ? '/vehiculos' : '/vehiculos/' . $vehicle->getId() . '/editar';
?>

<h1 class="h3 mb-3"><?= $e($title) ?></h1>

<form method="post" action="<?= $e($base . $accion) ?>" class="row g-3" novalidate>
    <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">

    <div class="col-md-6">
        <label for="brand" class="form-label">Marca</label>
        <input type="text" id="brand" name="brand" class="form-control <?= isset($errors['brand']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['brand']) ?>" required>
        <div class="invalid-feedback"><?= $e($errors['brand'] ?? '') ?></div>
    </div>

    <div class="col-md-6">
        <label for="model" class="form-label">Modelo</label>
        <input type="text" id="model" name="model" class="form-control <?= isset($errors['model']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['model']) ?>" required>
        <div class="invalid-feedback"><?= $e($errors['model'] ?? '') ?></div>
    </div>

    <div class="col-md-4">
        <label for="year" class="form-label">Año</label>
        <input type="number" id="year" name="year" class="form-control <?= isset($errors['year']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['year']) ?>" required>
        <div class="invalid-feedback"><?= $e($errors['year'] ?? '') ?></div>
    </div>

    <div class="col-md-4">
        <label for="category" class="form-label">Categoría</label>
        <input type="text" id="category" name="category" class="form-control <?= isset($errors['category']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['category']) ?>" placeholder="Sedán, camioneta, SUV..." required>
        <div class="invalid-feedback"><?= $e($errors['category'] ?? '') ?></div>
    </div>

    <!-- El estado inicial solo se elige al registrar; después se cambia desde el listado -->
    <?php if ($vehicle === null): ?>
        <div class="col-md-4">
            <label for="status" class="form-label">Estado inicial</label>
            <select id="status" name="status" class="form-select">
                <option value="disponible" <?= $vals['status'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="mantenimiento" <?= $vals['status'] === 'mantenimiento' ? 'selected' : '' ?>>En mantenimiento</option>
            </select>
        </div>
    <?php endif; ?>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="<?= $e($base) ?>/vehiculos" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
