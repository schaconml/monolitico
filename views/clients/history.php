<h1 class="h3 mb-3">Historial: <?= $e($client->getName()) ?></h1>

<p class="text-muted">Licencia de conducción: <?= $e($client->getDriverLicense()) ?></p>

<a href="<?= $e($base) ?>/clientes" class="btn btn-outline-secondary btn-sm mb-3">Volver a clientes</a>

<!-- RF-09: todas las reservas del cliente, del más reciente al más antiguo -->
<?php if (count($reservations) === 0): ?>
    <p class="text-muted">El cliente no tiene reservas registradas.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Vehículo</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Devolución real</th>
                    <th>Estado</th>
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
                            <span class="badge text-bg-<?= $e($r->getStatus()->badge()) ?>">
                                <?= $e($r->getStatus()->label()) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
