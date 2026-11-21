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

<h1><?= $e($title) ?></h1>

<form method="post" action="<?= $e($base . $accion) ?>" class="formulario-vehiculo" novalidate accept-charset="UTF-8">
    <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">

    <fieldset>
        <legend>Datos del vehículo</legend>

        <section class="form-fila">
            <label for="brand">Marca</label>
            <input type="text" id="brand" name="brand" <?= isset($errors['brand']) ? 'aria-invalid="true" aria-describedby="brand-error"' : '' ?> value="<?= $e($vals['brand']) ?>" required>
            <?php if (isset($errors['brand'])): ?>
                <p id="brand-error" class="field-error"><?= $e($errors['brand']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="model">Modelo</label>
            <input type="text" id="model" name="model" <?= isset($errors['model']) ? 'aria-invalid="true" aria-describedby="model-error"' : '' ?> value="<?= $e($vals['model']) ?>" required>
            <?php if (isset($errors['model'])): ?>
                <p id="model-error" class="field-error"><?= $e($errors['model']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="year">Año</label>
            <input type="number" id="year" name="year" <?= isset($errors['year']) ? 'aria-invalid="true" aria-describedby="year-error"' : '' ?> value="<?= $e($vals['year']) ?>" required>
            <?php if (isset($errors['year'])): ?>
                <p id="year-error" class="field-error"><?= $e($errors['year']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="category">Categoría</label>
            <input type="text" id="category" name="category" <?= isset($errors['category']) ? 'aria-invalid="true" aria-describedby="category-error"' : '' ?> value="<?= $e($vals['category']) ?>" placeholder="Sedán, camioneta, SUV..." required>
            <?php if (isset($errors['category'])): ?>
                <p id="category-error" class="field-error"><?= $e($errors['category']) ?></p>
            <?php endif; ?>
        </section>

        <!-- El estado inicial solo se elige al registrar; después se cambia desde el listado -->
        <?php if ($vehicle === null): ?>
            <section class="form-fila">
                <label for="status">Estado inicial</label>
                <select id="status" name="status">
                    <option value="disponible" <?= $vals['status'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="mantenimiento" <?= $vals['status'] === 'mantenimiento' ? 'selected' : '' ?>>En mantenimiento</option>
                </select>
            </section>
        <?php endif; ?>
    </fieldset>

    <section class="form-acciones">
        <button type="submit">Guardar</button>
        <a href="<?= $e($base) ?>/vehiculos">Cancelar</a>
    </section>
</form>
