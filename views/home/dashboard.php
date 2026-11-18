<h1 class="h3 mb-3">Gestor de Alquiler de Vehículos</h1>
<p class="text-muted">Administre la flota y los clientes desde las siguientes secciones.</p>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Vehículos</h2>
                <p class="card-text">Registro, consulta y cambio de estado de la flota.</p>
                <a href="<?= $e($base) ?>/vehiculos" class="btn btn-primary">Ver vehículos</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Clientes</h2>
                <p class="card-text">Registro y consulta de clientes con su licencia de conducción.</p>
                <a href="<?= $e($base) ?>/clientes" class="btn btn-primary">Ver clientes</a>
            </div>
        </div>
    </div>
</div>
