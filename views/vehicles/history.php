<h1 class="h3 mb-3">Historial: <?= $e($vehicle->getBrand() . ' ' . $vehicle->getModel()) ?></h1>

<p class="text-muted">
    Año <?= $e($vehicle->getYear()) ?> · <?= $e($vehicle->getCategory()) ?>
    <span class="badge text-bg-<?= $e($vehicle->getStatus()->badge()) ?> ms-1">
        <?= $e($vehicle->getStatus()->label()) ?>
    </span>
</p>

<a href="<?= $e($base) ?>/vehiculos" class="btn btn-outline-secondary btn-sm mb-3">Volver a vehículos</a>

<!-- RF-08: todas las reservas del vehículo, del más reciente al más antiguo -->
<?php if (count($reservations) === 0): ?>
    <p class="text-muted">El vehículo no tiene reservas registradas.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Cliente</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Devolución real</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <?php $cliente = $clients[$r->getClientId()] ?? null; ?>
                    <tr>
                        <td><?= $e($r->getId()) ?></td>
                        <td><?= $cliente === null ? '-' : $e($cliente->getName()) ?></td>
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
