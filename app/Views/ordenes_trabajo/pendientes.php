<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clock"></i> Solicitudes Pendientes
            <?php if (!$esAdministrador): ?>
                <small class="text-muted">(Asignadas a mí)</small>
            <?php endif; ?>
        </h1>
        <div>
            <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="<?= base_url('ordenes-trabajo/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nueva Orden
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar izquierdo con estados -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter"></i> Estados
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <!-- Todas las órdenes -->
                        <a href="<?= base_url('ordenes-trabajo') ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-list text-secondary"></i>
                                <span class="ml-2">Todas</span>
                            </div>
                            <span class="badge bg-secondary text-white badge-pill">
                                <?php 
                                $totalOrdenes = 0;
                                if (isset($conteoEstados) && is_array($conteoEstados)) {
                                    foreach ($conteoEstados as $estado => $count) {
                                        $totalOrdenes += is_array($count) ? ($count['total'] ?? 0) : $count;
                                    }
                                }
                                echo $totalOrdenes;
                                ?>
                            </span>
                        </a>
                        
                        <!-- Planificadas -->
                        <a href="<?= base_url('ordenes-trabajo') ?>?estado=PLANIFICADA" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-calendar-alt text-info"></i>
                                <span class="ml-2">Planificadas</span>
                            </div>
                            <span class="badge bg-info text-white badge-pill">
                                <?= $conteoEstados['PLANIFICADA'] ?? 0 ?>
                            </span>
                        </a>
                        
                        <!-- Pendientes - Activo -->
                        <a href="<?= base_url('ordenes-trabajo/pendientes') ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center active">
                            <div>
                                <i class="fas fa-clock text-warning"></i>
                                <span class="ml-2">Pendientes</span>
                            </div>
                            <span class="badge bg-warning text-dark badge-pill">
                                <?= $conteoEstados['PENDIENTE'] ?? 0 ?>
                            </span>
                        </a>
                        
                        <!-- Aprobadas -->
                        <a href="<?= base_url('ordenes-trabajo') ?>?estado=APROBADA" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-check-circle text-success"></i>
                                <span class="ml-2">Aprobadas</span>
                            </div>
                            <span class="badge bg-success text-white badge-pill">
                                <?= $conteoEstados['APROBADA'] ?? 0 ?>
                            </span>
                        </a>
                        
                        <!-- En Proceso -->
                        <a href="<?= base_url('ordenes-trabajo') ?>?estado=EN_PROCESO" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-cogs text-primary"></i>
                                <span class="ml-2">En Proceso</span>
                            </div>
                            <span class="badge bg-primary text-white badge-pill">
                                <?= $conteoEstados['EN_PROCESO'] ?? 0 ?>
                            </span>
                        </a>
                        
                        <!-- Finalizadas -->
                        <a href="<?= base_url('ordenes-trabajo') ?>?estado=FINALIZADA" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-check text-success"></i>
                                <span class="ml-2">Finalizadas</span>
                            </div>
                            <span class="badge bg-success text-white badge-pill">
                                <?= $conteoEstados['FINALIZADA'] ?? 0 ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Información del usuario -->
            <div class="card shadow mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-user"></i> Mi Información
                    </h6>
                </div>
                <div class="card-body">
                    <p><strong>Rol:</strong> <?= $esAdministrador ? 'Administrador' : 'Usuario' ?></p>
                    <p><strong>Vista:</strong> <?= $esAdministrador ? 'Todas las pendientes' : 'Solo mis asignadas' ?></p>
                    <p><strong>Total mostradas:</strong> <?= count($solicitudes) ?></p>
                </div>
            </div>
        </div>

        <!-- Lista de solicitudes pendientes -->
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-clock"></i> Solicitudes Pendientes
                    </h6>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($solicitudes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">ID</th>
                                        <th>Vehículo</th>
                                        <th>Descripción</th>
                                        <th width="100">Prioridad</th>
                                        <th width="120">Fecha</th>
                                        <th width="150">Solicitante</th>
                                        <?php if ($esAdministrador): ?>
                                            <th width="150">Asignado</th>
                                        <?php endif; ?>
                                        <th width="120">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($solicitudes as $solicitud): ?>
                                        <tr>
                                            <td class="font-weight-bold"><?= $solicitud['id'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-2">
                                                        <i class="fas fa-car text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold"><?= esc($solicitud['placa'] ?? 'N/A') ?></div>
                                                        <small class="text-muted"><?= esc($solicitud['marca'] ?? '') ?> <?= esc($solicitud['modelo'] ?? '') ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="<?= esc($solicitud['descripcion']) ?>">
                                                    <?= strlen($solicitud['descripcion']) > 50 ? substr(esc($solicitud['descripcion']), 0, 50) . '...' : esc($solicitud['descripcion']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $prioridadTexto = [
                                                    '1' => 'Baja',
                                                    '2' => 'Media', 
                                                    '3' => 'Alta',
                                                    '4' => 'Crítica'
                                                ];
                                                $prioridadClass = [
                                                    '1' => 'badge bg-success text-white',
                                                    '2' => 'badge bg-warning text-dark',
                                                    '3' => 'badge bg-danger text-white',
                                                    '4' => 'badge bg-dark text-white'
                                                ];
                                                ?>
                                                <span class="<?= $prioridadClass[$solicitud['prioridad']] ?? 'badge bg-secondary text-white' ?>">
                                                    <?= $prioridadTexto[$solicitud['prioridad']] ?? 'Prioridad ' . $solicitud['prioridad'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small><?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?></small><br>
                                                <small class="text-muted"><?= date('H:i', strtotime($solicitud['fecha_solicitud'])) ?></small>
                                            </td>
                                            <td>
                                                <small><?= esc($solicitud['nombre_solicitante'] ?? 'N/A') ?></small>
                                            </td>
                                            <?php if ($esAdministrador): ?>
                                                <td>
                                                    <small><?= esc($solicitud['nombre_asignado'] ?? 'Sin asignar') ?></small>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('ordenes-trabajo/show/' . $solicitud['id']) ?>" 
                                                       class="btn btn-outline-info btn-sm" title="Ver detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-success btn-sm" 
                                                            onclick="aprobarSolicitud(<?= $solicitud['id'] ?>)" title="Aprobar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <?php if ($esAdministrador): ?>
                                                        <a href="<?= base_url('ordenes-trabajo/edit/' . $solicitud['id']) ?>" 
                                                           class="btn btn-outline-warning btn-sm" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clock fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-muted">No hay solicitudes pendientes</h5>
                            <p class="text-muted">
                                <?= $esAdministrador ? 'No hay solicitudes pendientes en el sistema' : 'No tienes solicitudes pendientes asignadas' ?>
                            </p>
                            <a href="<?= base_url('ordenes-trabajo/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Nueva Solicitud
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function aprobarSolicitud(id) {
    if (confirm('¿Está seguro de aprobar esta solicitud?')) {
        $.ajax({
            url: '<?= base_url('ordenes-trabajo/cambiar-estado') ?>',
            method: 'POST',
            data: {
                id: id,
                estado: 'APROBADA',
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error al aprobar la solicitud');
            }
        });
    }
}

// Auto-refresh cada 30 segundos
setInterval(function() {
    location.reload();
}, 30000);
</script>
<?= $this->endSection() ?>
