<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-cog"></i> Mis Órdenes de Trabajo
        </h1>
        <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filtros
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('ordenes-trabajo/mis-ordenes') ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                <option value="PLANIFICADA" <?= (isset($filtros['estado']) && $filtros['estado'] == 'PLANIFICADA') ? 'selected' : '' ?>>Planificada</option>
                                <option value="PENDIENTE" <?= (isset($filtros['estado']) && $filtros['estado'] == 'PENDIENTE') ? 'selected' : '' ?>>Pendientes</option>
                                <option value="APROBADA" <?= (isset($filtros['estado']) && $filtros['estado'] == 'APROBADA') ? 'selected' : '' ?>>Aprobadas</option>
                                <option value="EN_PROCESO" <?= (isset($filtros['estado']) && $filtros['estado'] == 'EN_PROCESO') ? 'selected' : '' ?>>En Proceso</option>
                                <option value="FINALIZADA" <?= (isset($filtros['estado']) && $filtros['estado'] == 'FINALIZADA') ? 'selected' : '' ?>>Finalizada</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prioridad">Prioridad</label>
                            <select name="prioridad" id="prioridad" class="form-control">
                                <option value="">Todas las prioridades</option>
                                <option value="BAJA" <?= (isset($filtros['prioridad']) && $filtros['prioridad'] == 'BAJA') ? 'selected' : '' ?>>Baja</option>
                                <option value="MEDIA" <?= (isset($filtros['prioridad']) && $filtros['prioridad'] == 'MEDIA') ? 'selected' : '' ?>>Media</option>
                                <option value="ALTA" <?= (isset($filtros['prioridad']) && $filtros['prioridad'] == 'ALTA') ? 'selected' : '' ?>>Alta</option>
                                <option value="CRITICA" <?= (isset($filtros['prioridad']) && $filtros['prioridad'] == 'CRITICA') ? 'selected' : '' ?>>Crítica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="<?= base_url('ordenes-trabajo/mis-ordenes') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de órdenes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Mis Órdenes Asignadas (<?= count($ordenes) ?>)
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($ordenes)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehículo</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Fecha Solicitud</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenes as $orden): ?>
                                <tr>
                                    <td><?= $orden['id'] ?></td>
                                    <td>
                                        <strong><?= esc($orden['vehiculo_placa'] ?? 'N/A') ?></strong><br>
                                        <small class="text-muted"><?= esc($orden['vehiculo_marca'] ?? '') ?> <?= esc($orden['vehiculo_modelo'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="<?= esc($orden['descripcion']) ?>">
                                            <?= esc($orden['descripcion']) ?>
                                        </div>
                                        <?php if (!empty($orden['tipo_problema_nombre'])): ?>
                                            <small class="text-info">
                                                <i class="fas fa-tag"></i> <?= esc($orden['tipo_problema_nombre']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $badgeClass = [
                                            'PENDIENTE' => 'warning',
                                            'APROBADA' => 'info',
                                            'EN_PROCESO' => 'primary',
                                            'FINALIZADA' => 'success',
                                            'PLANIFICADA' => 'secondary'
                                        ];
                                        ?>
                                        <span class="badge badge-<?= $badgeClass[$orden['estado']] ?? 'secondary' ?>">
                                            <?= esc($orden['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $prioridadClass = [
                                            'BAJA' => 'success',
                                            'MEDIA' => 'warning',
                                            'ALTA' => 'danger',
                                            'CRITICA' => 'dark'
                                        ];
                                        ?>
                                        <span class="badge badge-<?= $prioridadClass[$orden['prioridad']] ?? 'secondary' ?>">
                                            <?= esc($orden['prioridad']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y H:i', strtotime($orden['fecha_solicitud'])) ?>
                                    </td>
                                    <td>
                                        <?= $orden['fecha_asignacion'] ? date('d/m/Y H:i', strtotime($orden['fecha_asignacion'])) : '-' ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('ordenes-trabajo/show/' . $orden['id']) ?>" 
                                               class="btn btn-info btn-sm" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($orden['estado'] !== 'FINALIZADA'): ?>
                                                <a href="<?= base_url('ordenes-trabajo/edit/' . $orden['id']) ?>" 
                                                   class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-primary btn-sm" 
                                                        onclick="cambiarEstado(<?= $orden['id'] ?>, '<?= $orden['estado'] ?>')" 
                                                        title="Cambiar estado">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
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
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-muted">No tienes órdenes asignadas</h5>
                    <p class="text-muted">Las órdenes que te sean asignadas aparecerán aquí.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado de Orden</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCambiarEstado">
                    <input type="hidden" id="ordenId" name="id">
                    <div class="form-group">
                        <label for="nuevoEstado">Nuevo Estado</label>
                        <select name="estado" id="nuevoEstado" class="form-control" required>
                            <option value="">Seleccionar estado</option>
                            <option value="PENDIENTE">Pendientes</option>
                            <option value="APROBADA">Aprobadas</option>
                            <option value="EN_PROCESO">En Proceso</option>
                            <option value="FINALIZADA">Finalizada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarCambioEstado()">
                    <i class="fas fa-save"></i> Guardar Cambio
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[ 5, "desc" ]], // Ordenar por fecha de solicitud descendente
        "pageLength": 25
    });
});

function cambiarEstado(ordenId, estadoActual) {
    $('#ordenId').val(ordenId);
    $('#nuevoEstado').val('');
    
    // Remover opciones no válidas según el estado actual
    $('#nuevoEstado option').show();
    if (estadoActual === 'FINALIZADA') {
        $('#modalCambiarEstado').modal('hide');
        return;
    }
    
    $('#modalCambiarEstado').modal('show');
}

function confirmarCambioEstado() {
    const ordenId = $('#ordenId').val();
    const nuevoEstado = $('#nuevoEstado').val();
    
    if (!nuevoEstado) {
        alert('Por favor selecciona un estado');
        return;
    }
    
    $.ajax({
        url: '<?= base_url('ordenes-trabajo/cambiar-estado') ?>',
        method: 'POST',
        data: {
            id: ordenId,
            estado: nuevoEstado,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        success: function(response) {
            if (response.success) {
                $('#modalCambiarEstado').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error al cambiar el estado');
        }
    });
}
</script>
<?= $this->endSection() ?>
