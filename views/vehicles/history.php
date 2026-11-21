<h1>Historial: <?= $e($vehicle->getBrand() . ' ' . $vehicle->getModel()) ?></h1>

<p>
    Año <?= $e($vehicle->getYear()) ?> · <?= $e($vehicle->getCategory()) ?>
    <data value="<?= $e($vehicle->getStatus()->value) ?>"><?= $e($vehicle->getStatus()->label()) ?></data>
</p>

<a href="<?= $e($base) ?>/vehiculos">Volver a vehículos</a>

<!-- RF-08: todas las reservas del vehículo, del más reciente al más antiguo -->
<?php if (count($reservations) === 0): ?>
    <p class="empty-state">El vehículo no tiene reservas registradas.</p>
<?php else: ?>
    <section class="table-scroll" aria-labelledby="historial-vehiculo-title">
        <h2 id="historial-vehiculo-title" class="sr-only">Historial de reservas del vehículo</h2>
        <table>
            <caption>Reservas de <?= $e($vehicle->getBrand() . ' ' . $vehicle->getModel()) ?></caption>
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Inicio</th>
                    <th scope="col">Fin</th>
                    <th scope="col">Devolución real</th>
                    <th scope="col">Estado</th>
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
                            <data value="<?= $e($r->getStatus()->value) ?>"><?= $e($r->getStatus()->label()) ?></data>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>
