<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Clientes</h1>
    <a href="<?= $e($base) ?>/clientes/crear" class="btn btn-primary">Registrar cliente</a>
</div>

<?php if (count($clients) === 0): ?>
    <p class="text-muted">No hay clientes registrados.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Licencia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $c): ?>
                    <tr>
                        <td><?= $e($c->getId()) ?></td>
                        <td><?= $e($c->getName()) ?></td>
                        <td><?= $e($c->getPhone()) ?></td>
                        <td><?= $e($c->getEmail()) ?></td>
                        <td><?= $e($c->getDriverLicense()) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
