<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Reservas</h1>
    <a href="<?= $e($base) ?>/reservas/crear" class="btn btn-primary">Nueva reserva</a>
</div>

<?php if (count($reservations) === 0): ?>
    <p class="text-muted">No hay reservas registradas.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Vehículo</th>
                    <th>Cliente</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Devolución real</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <?php $vehiculo = $vehicles[$r->getVehicleId()] ?? null; ?>
                    <?php $cliente = $clients[$r->getClientId()] ?? null; ?>
                    <tr>
                        <td><?= $e($r->getId()) ?></td>
                        <td><?= $vehiculo === null ? '-' : $e($vehiculo->getBrand() . ' ' . $vehiculo->getModel()) ?></td>
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
                        <td class="text-nowrap">
                            <?php if ($r->isActive()): ?>
                                <a href="<?= $e($base) ?>/reservas/<?= $e($r->getId()) ?>/devolucion" class="btn btn-sm btn-outline-success">Registrar devolución</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
