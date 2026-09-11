<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clock text-warning"></i> Bandeja de Pendientes
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

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count($ordenes) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Prioridad Alta/Crítica
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($ordenes, function($o) { return in_array($o['prioridad'], ['ALTA', 'CRITICA']); })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sin Asignar
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($ordenes, function($o) { return empty($o['asignado_nombre']); })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Más de 7 días
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $hace7Dias = date('Y-m-d', strtotime('-7 days'));
                                echo count(array_filter($ordenes, function($o) use ($hace7Dias) { 
                                    return $o['fecha_solicitud'] < $hace7Dias; 
                                }));
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones masivas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-tasks"></i> Acciones Masivas
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <button type="button" class="btn btn-info btn-block" onclick="aprobarSeleccionadas()">
                        <i class="fas fa-check"></i> Aprobar Seleccionadas
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary btn-block" onclick="asignarMasivo()">
                        <i class="fas fa-user-plus"></i> Asignación Masiva
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-secondary btn-block" onclick="exportarSeleccionadas()">
                        <i class="fas fa-file-export"></i> Exportar Seleccionadas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de órdenes pendientes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Órdenes Pendientes (<?= count($ordenes) ?>)
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($ordenes)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>ID</th>
                                <th>Vehículo</th>
                                <th>Solicitante</th>
                                <th>Descripción</th>
                                <th>Prioridad</th>
                                <th>Fecha Solicitud</th>
                                <th>Días Pendiente</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenes as $orden): ?>
                                <?php
                                $diasPendiente = (strtotime('now') - strtotime($orden['fecha_solicitud'])) / (60 * 60 * 24);
                                $diasPendiente = floor($diasPendiente);
                                ?>
                                <tr class="<?= $diasPendiente > 7 ? 'table-warning' : '' ?> <?= in_array($orden['prioridad'], ['ALTA', 'CRITICA']) ? 'table-danger' : '' ?>">
                                    <td>
                                        <input type="checkbox" class="orden-checkbox" value="<?= $orden['id'] ?>">
                                    </td>
                                    <td><?= $orden['id'] ?></td>
                                    <td>
                                        <strong><?= esc($orden['vehiculo_placa'] ?? 'N/A') ?></strong><br>
                                        <small class="text-muted"><?= esc($orden['vehiculo_marca'] ?? '') ?> <?= esc($orden['vehiculo_modelo'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <?= esc($orden['solicitante_nombre'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="<?= esc($orden['descripcion']) ?>">
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
                                        <span class="badge badge-<?= $diasPendiente > 7 ? 'danger' : ($diasPendiente > 3 ? 'warning' : 'info') ?>">
                                            <?= $diasPendiente ?> días
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('ordenes-trabajo/show/' . $orden['id']) ?>" 
                                               class="btn btn-info btn-sm" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="aprobarOrden(<?= $orden['id'] ?>)" 
                                                    title="Aprobar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <a href="<?= base_url('ordenes-trabajo/edit/' . $orden['id']) ?>" 
                                               class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if (empty($orden['asignado_nombre'])): ?>
                                                <button type="button" class="btn btn-primary btn-sm" 
                                                        onclick="asignarOrden(<?= $orden['id'] ?>)" 
                                                        title="Asignar">
                                                    <i class="fas fa-user-plus"></i>
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
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">¡Excelente! No hay órdenes pendientes</h5>
                    <p class="text-muted">Todas las órdenes han sido procesadas.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para asignar orden -->
<div class="modal fade" id="modalAsignarOrden" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar Orden de Trabajo</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAsignarOrden">
                    <input type="hidden" id="ordenIdAsignar" name="id">
                    <div class="form-group">
                        <label for="usuarioAsignado">Asignar a:</label>
                        <select name="id_asignado" id="usuarioAsignado" class="form-control" required>
                            <option value="">Seleccionar usuario</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarAsignacion()">
                    <i class="fas fa-user-plus"></i> Asignar
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
        "order": [[ 6, "asc" ]], // Ordenar por fecha de solicitud ascendente (más antiguas primero)
        "pageLength": 25
    });

    // Seleccionar todos
    $('#selectAll').change(function() {
        $('.orden-checkbox').prop('checked', this.checked);
    });
});

function aprobarOrden(ordenId) {
    if (confirm('¿Está seguro de aprobar esta orden?')) {
        $.ajax({
            url: '<?= base_url('ordenes-trabajo/cambiar-estado') ?>',
            method: 'POST',
            data: {
                id: ordenId,
                estado: 'APROBADAS',
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
                alert('Error al aprobar la orden');
            }
        });
    }
}

function aprobarSeleccionadas() {
    const seleccionadas = $('.orden-checkbox:checked').map(function() {
        return this.value;
    }).get();

    if (seleccionadas.length === 0) {
        alert('Por favor selecciona al menos una orden');
        return;
    }

    if (confirm('¿Está seguro de aprobar ' + seleccionadas.length + ' órdenes seleccionadas?')) {
        // Procesar cada orden seleccionada
        let procesadas = 0;
        seleccionadas.forEach(function(ordenId) {
            $.ajax({
                url: '<?= base_url('ordenes-trabajo/cambiar-estado') ?>',
                method: 'POST',
                data: {
                    id: ordenId,
                    estado: 'APROBADAS',
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    procesadas++;
                    if (procesadas === seleccionadas.length) {
                        location.reload();
                    }
                }
            });
        });
    }
}

function asignarOrden(ordenId) {
    $('#ordenIdAsignar').val(ordenId);
    
    // Cargar usuarios disponibles
    $.ajax({
        url: '<?= base_url('usuarios/getUsuarios') ?>',
        method: 'GET',
        success: function(response) {
            $('#usuarioAsignado').empty().append('<option value="">Seleccionar usuario</option>');
            if (response.success && response.data) {
                response.data.forEach(function(usuario) {
                    $('#usuarioAsignado').append(
                        '<option value="' + usuario.id + '">' + usuario.nombre + '</option>'
                    );
                });
            }
        }
    });
    
    $('#modalAsignarOrden').modal('show');
}

function confirmarAsignacion() {
    const ordenId = $('#ordenIdAsignar').val();
    const usuarioId = $('#usuarioAsignado').val();
    
    if (!usuarioId) {
        alert('Por favor selecciona un usuario');
        return;
    }
    
    $.ajax({
        url: '<?= base_url('ordenes-trabajo/asignar-orden') ?>',
        method: 'POST',
        data: {
            id: ordenId,
            id_asignado: usuarioId,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        success: function(response) {
            if (response.success) {
                $('#modalAsignarOrden').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error al asignar la orden');
        }
    });
}

function asignarMasivo() {
    const seleccionadas = $('.orden-checkbox:checked').map(function() {
        return this.value;
    }).get();

    if (seleccionadas.length === 0) {
        alert('Por favor selecciona al menos una orden');
        return;
    }

    // Implementar modal de asignación masiva
    alert('Función de asignación masiva en desarrollo');
}

function exportarSeleccionadas() {
    const seleccionadas = $('.orden-checkbox:checked').map(function() {
        return this.value;
    }).get();

    if (seleccionadas.length === 0) {
        alert('Por favor selecciona al menos una orden');
        return;
    }

    // Implementar exportación
    alert('Función de exportación en desarrollo');
}
</script>
<?= $this->endSection() ?>
