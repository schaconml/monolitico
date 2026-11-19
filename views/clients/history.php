<h1 class="h3 mb-3">Historial: <?= $e($client->getName()) ?></h1>

<p class="text-muted">Licencia de conducción: <?= $e($client->getDriverLicense()) ?></p>

<a href="<?= $e($base) ?>/clientes" class="btn btn-outline-secondary btn-sm mb-3">Volver a clientes</a>

<!-- RF-09: todas las reservas del cliente, del más reciente al más antiguo -->
<?php if (count($reservations) === 0): ?>
    <p class="text-muted empty-state">El cliente no tiene reservas registradas.</p>
<?php else: ?>
    <section class="table-scroll" aria-labelledby="historial-cliente-title">
        <h2 id="historial-cliente-title" class="sr-only">Historial de reservas del cliente</h2>
        <table class="table table-striped align-middle">
            <caption>Reservas de <?= $e($client->getName()) ?></caption>
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Vehículo</th>
                    <th scope="col">Inicio</th>
                    <th scope="col">Fin</th>
                    <th scope="col">Devolución real</th>
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <?php $vehiculo = $vehicles[$r->getVehicleId()] ?? null; ?>
                    <tr>
                        <td><?= $e($r->getId()) ?></td>
                        <td><?= $vehiculo === null ? '-' : $e($vehiculo->getBrand() . ' ' . $vehiculo->getModel()) ?></td>
                        <td><time datetime="<?= $e($r->getStartDate()->format('Y-m-d')) ?>"><?= $e($r->getStartDate()->format('d/m/Y')) ?></time></td>
                        <td><time datetime="<?= $e($r->getEndDate()->format('Y-m-d')) ?>"><?= $e($r->getEndDate()->format('d/m/Y')) ?></time></td>
                        <td>
                            <?php if ($r->getActualReturnDate() === null): ?>
                                -
                            <?php else: ?>
                                <time datetime="<?= $e($r->getActualReturnDate()->format('Y-m-d')) ?>"><?= $e($r->getActualReturnDate()->format('d/m/Y')) ?></time>
                            <?php endif; ?>
                        </td>
                        <td>
                            <data class="badge text-bg-<?= $e($r->getStatus()->badge()) ?>" value="<?= $e($r->getStatus()->value) ?>"><?= $e($r->getStatus()->label()) ?></data>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>
