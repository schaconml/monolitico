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

<form method="post" action="<?= $e($base) ?>/clientes" class="formulario-cliente" novalidate accept-charset="UTF-8">
    <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">

    <fieldset>
        <legend>Datos del cliente</legend>

        <section class="form-fila">
            <label for="name" class="form-label">Nombre completo</label>
            <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['name']) ?>" required>
            <?php if (isset($errors['name'])): ?>
                <p id="name-error" class="field-error"><?= $e($errors['name']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="driver_license" class="form-label">Número de licencia</label>
            <input type="text" id="driver_license" name="driver_license" class="form-control <?= isset($errors['driver_license']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['driver_license']) ?>" required>
            <?php if (isset($errors['driver_license'])): ?>
                <p id="driver_license-error" class="field-error"><?= $e($errors['driver_license']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" id="phone" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['phone']) ?>" maxlength="50">
            <?php if (isset($errors['phone'])): ?>
                <p id="phone-error" class="field-error"><?= $e($errors['phone']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= $e($vals['email']) ?>">
            <?php if (isset($errors['email'])): ?>
                <p id="email-error" class="field-error"><?= $e($errors['email']) ?></p>
            <?php endif; ?>
        </section>
    </fieldset>

    <section class="form-acciones">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="<?= $e($base) ?>/clientes" class="btn btn-outline-secondary">Cancelar</a>
    </section>
</form>
