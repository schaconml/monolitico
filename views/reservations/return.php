<?php
// Datos de la confirmación (los entrega ReservationController)
$clienteNombre = $client === null ? '-' : $client->getName();
$vehiculoNombre = $vehicle === null ? '-' : $vehicle->getBrand() . ' ' . $vehicle->getModel();
$hoy = (new DateTimeImmutable('today'))->format('d/m/Y');
?>

<h1 class="h3 mb-3"><?= $e($title) ?></h1>

<?php if (!$reservation->isActive()): ?>
    <p class="text-muted">Esta reserva ya está completada o cancelada, no puede devolverse.</p>
    <a href="<?= $e($base) ?>/reservas" class="btn btn-outline-secondary">Volver</a>
<?php else: ?>
    <p class="text-muted">Revise los datos y confirme. El vehículo quedará disponible si no tiene otra reserva vigente.</p>

    <dl class="row mb-3">
        <dt class="col-sm-3">Vehículo</dt>
        <dd class="col-sm-9"><?= $e($vehiculoNombre) ?></dd>

        <dt class="col-sm-3">Cliente</dt>
        <dd class="col-sm-9"><?= $e($clienteNombre) ?></dd>

        <dt class="col-sm-3">Inicio</dt>
        <dd class="col-sm-9"><?= $e($reservation->getStartDate()->format('d/m/Y')) ?></dd>

        <dt class="col-sm-3">Fin previsto</dt>
        <dd class="col-sm-9"><?= $e($reservation->getEndDate()->format('d/m/Y')) ?></dd>

        <dt class="col-sm-3">Fecha de devolución</dt>
        <dd class="col-sm-9">Hoy, <?= $e($hoy) ?></dd>
    </dl>

    <form method="post" action="<?= $e($base) ?>/reservas/<?= $e($reservation->getId()) ?>/devolucion" data-confirm="¿Desea registrar la devolución y liberar el vehículo?">
        <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">
        <button type="submit" class="btn btn-success">Confirmar devolución</button>
        <a href="<?= $e($base) ?>/reservas" class="btn btn-outline-secondary">Cancelar</a>
    </form>
<?php endif; ?>
