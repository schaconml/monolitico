<?php
// Valores del formulario (lo enviado antes, si hubo error)
$vals = [
    'name' => $old['name'] ?? '',
    'phone' => $old['phone'] ?? '',
    'email' => $old['email'] ?? '',
    'driver_license' => $old['driver_license'] ?? '',
];
?>

<h1><?= $e($title) ?></h1>

<form method="post" action="<?= $e($base) ?>/clientes" class="formulario-cliente" novalidate accept-charset="UTF-8">
    <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">

    <fieldset>
        <legend>Datos del cliente</legend>

        <section class="form-fila">
            <label for="name">Nombre completo</label>
            <input type="text" id="name" name="name" <?= isset($errors['name']) ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?> value="<?= $e($vals['name']) ?>" required>
            <?php if (isset($errors['name'])): ?>
                <p id="name-error" class="field-error"><?= $e($errors['name']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="driver_license">Número de licencia</label>
            <input type="text" id="driver_license" name="driver_license" <?= isset($errors['driver_license']) ? 'aria-invalid="true" aria-describedby="driver_license-error"' : '' ?> value="<?= $e($vals['driver_license']) ?>" required>
            <?php if (isset($errors['driver_license'])): ?>
                <p id="driver_license-error" class="field-error"><?= $e($errors['driver_license']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="phone">Teléfono</label>
            <input type="text" id="phone" name="phone" <?= isset($errors['phone']) ? 'aria-invalid="true" aria-describedby="phone-error"' : '' ?> value="<?= $e($vals['phone']) ?>" maxlength="50">
            <?php if (isset($errors['phone'])): ?>
                <p id="phone-error" class="field-error"><?= $e($errors['phone']) ?></p>
            <?php endif; ?>
        </section>

        <section class="form-fila">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" <?= isset($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?> value="<?= $e($vals['email']) ?>">
            <?php if (isset($errors['email'])): ?>
                <p id="email-error" class="field-error"><?= $e($errors['email']) ?></p>
            <?php endif; ?>
        </section>
    </fieldset>

    <section class="form-acciones">
        <button type="submit">Guardar</button>
        <a href="<?= $e($base) ?>/clientes">Cancelar</a>
    </section>
</form>
