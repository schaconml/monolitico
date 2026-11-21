<header class="clientes-header">
    <h1>Clientes</h1>
    <a href="<?= $e($base) ?>/clientes/crear">Registrar cliente</a>
</header>

<?php if (count($clients) === 0): ?>
    <p class="empty-state">No hay clientes registrados.</p>
<?php else: ?>
    <section class="table-scroll" aria-labelledby="clientes-table-title">
        <h2 id="clientes-table-title" class="sr-only">Listado de clientes</h2>
        <table>
            <caption>Clientes registrados</caption>
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Licencia</th>
                    <th scope="col">Acciones</th>
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
                        <td>
                            <a href="<?= $e($base) ?>/clientes/<?= $e($c->getId()) ?>/historial">Historial</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>
