<section class="reservas-header mb-3" aria-labelledby="reservas-title">
    <h1 id="reservas-title" class="h3 mb-0">Reservas</h1>
    <a href="<?= $e($base) ?>/reservas/crear" class="btn btn-primary">Nueva reserva</a>
</section>

<?php if (count($reservations) === 0): ?>
    <p class="text-muted empty-state">No hay reservas registradas.</p>
<?php else: ?>
    <section class="table-scroll" aria-labelledby="reservas-table-title">
        <h2 id="reservas-table-title" class="sr-only">Listado de reservas</h2>
        <table class="table table-striped align-middle">
            <caption>Reservas registradas</caption>
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Vehículo</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Inicio</th>
                    <th scope="col">Fin</th>
                    <th scope="col">Devolución real</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
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
                            <data class="badge text-bg-<?= $e($r->getStatus()->badge()) ?>" value="<?= $e($r->getStatus()->value) ?>"><?= $e($r->getStatus()->label()) ?></data>
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
    </section>
<?php endif; ?>
