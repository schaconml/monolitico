<?php
// Valores del formulario (lo enviado antes, si hubo error)
$vals = [
    'name' => $old['name'] ?? '',
    'phone' => $old['phone'] ?? '',
    'email' => $old['email'] ?? '',
    'driver_license' => $old['driver_license'] ?? '',
];
?>

<h1 class="h3 mb-3"><?= $e($title) ?></h1>

<form method="post" action="<?= $e($base) ?>/clientes" class="row g-3" novalidate>
    <div class="col-md-6">
        <label for="name" class="form-label">Nombre completo</label>
        <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['name']) ?>" required>
        <div class="invalid-feedback"><?= $e($errors['name'] ?? '') ?></div>
    </div>

    <div class="col-md-6">
        <label for="driver_license" class="form-label">Número de licencia</label>
        <input type="text" id="driver_license" name="driver_license" class="form-control <?= isset($errors['driver_license']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['driver_license']) ?>" required>
        <div class="invalid-feedback"><?= $e($errors['driver_license'] ?? '') ?></div>
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">Teléfono</label>
        <input type="text" id="phone" name="phone" class="form-control" value="<?= $e($vals['phone']) ?>">
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['email']) ?>">
        <div class="invalid-feedback"><?= $e($errors['email'] ?? '') ?></div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="<?= $e($base) ?>/clientes" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
