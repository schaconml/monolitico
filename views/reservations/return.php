<?php
// Datos de la confirmación (los entrega ReservationController)
$clienteNombre = $client === null ? '-' : $client->getName();
$vehiculoNombre = $vehicle === null ? '-' : $vehicle->getBrand() . ' ' . $vehicle->getModel();
?>

<h1 class="h3 mb-3"><?= $e($title) ?></h1>

<?php if (!$reservation->isActive()): ?>
    <p class="text-muted">Esta reserva ya está completada o cancelada, no puede devolverse.</p>
    <a href="<?= $e($base) ?>/reservas" class="btn btn-outline-secondary">Volver</a>
<?php else: ?>
    <p class="text-muted">Revise los datos y confirme. El vehículo quedará disponible si no tiene otra reserva vigente.</p>

    <dl class="detalle-reserva">
        <dt>Vehículo</dt>
        <dd><?= $e($vehiculoNombre) ?></dd>

        <dt>Cliente</dt>
        <dd><?= $e($clienteNombre) ?></dd>

        <dt>Inicio</dt>
        <dd><?= $e($reservation->getStartDate()->format('d/m/Y')) ?></dd>

        <dt>Fin previsto</dt>
        <dd><?= $e($reservation->getEndDate()->format('d/m/Y')) ?></dd>

        <dt>Fecha de devolución</dt>
        <dd>Hoy, <?= $e($hoy) ?></dd>
    </dl>

    <form method="post" action="<?= $e($base) ?>/reservas/<?= $e($reservation->getId()) ?>/devolucion" data-confirm="¿Desea registrar la devolución y liberar el vehículo?">
        <input type="hidden" name="_token" value="<?= $e($csrfToken) ?>">
        <button type="submit" class="btn btn-success">Confirmar devolución</button>
        <a href="<?= $e($base) ?>/reservas" class="btn btn-outline-secondary">Cancelar</a>
    </form>
<?php endif; ?>
