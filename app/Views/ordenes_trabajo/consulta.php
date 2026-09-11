<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-search"></i> Consulta de Órdenes de Trabajo
        </h1>
        <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>

    <!-- Filtros avanzados -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('ordenes-trabajo/consulta') ?>">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                <option value="PLANIFICADA" <?= (isset($filtros['estado']) && $filtros['estado'] == 'PLANIFICADA') ? 'selected' : '' ?>>Planificada</option>
                                <option value="PENDIENTES" <?= (isset($filtros['estado']) && $filtros['estado'] == 'PENDIENTES') ? 'selected' : '' ?>>Pendientes</option>
                                <option value="APROBADAS" <?= (isset($filtros['estado']) && $filtros['estado'] == 'APROBADAS') ? 'selected' : '' ?>>Aprobadas</option>
                                <option value="EN_PROCESO" <?= (isset($filtros['estado']) && $filtros['estado'] == 'EN_PROCESO') ? 'selected' : '' ?>>En Proceso</option>
                                <option value="FINALIZADA" <?= (isset($filtros['estado']) && $filtros['estado'] == 'FINALIZADA') ? 'selected' : '' ?>>Finalizada</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_vehiculo">Vehículo</label>
                            <select name="id_vehiculo" id="id_vehiculo" class="form-control">
                                <option value="">Todos los vehículos</option>
                                <?php foreach ($vehiculos as $vehiculo): ?>
                                    <option value="<?= $vehiculo['id'] ?>" <?= (isset($filtros['id_vehiculo']) && $filtros['id_vehiculo'] == $vehiculo['id']) ? 'selected' : '' ?>>
                                        <?= esc($vehiculo['placa']) ?> - <?= esc($vehiculo['marca']) ?> <?= esc($vehiculo['modelo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#filtrosFecha">
                                    <i class="fas fa-calendar"></i> Filtros de Fecha
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="collapse" id="filtrosFecha">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_desde">Fecha Desde</label>
                                <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" 
                                       value="<?= $filtros['fecha_desde'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_hasta">Fecha Hasta</label>
                                <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" 
                                       value="<?= $filtros['fecha_hasta'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="<?= base_url('ordenes-trabajo/consulta') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar Filtros
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportarExcel()">
                            <i class="fas fa-file-excel"></i> Exportar Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Resultados de Búsqueda (<?= count($ordenes) ?>)
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
                                <th>Solicitante</th>
                                <th>Asignado</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Fecha Solicitud</th>
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
                                        <?= esc($orden['solicitante_nombre'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?= esc($orden['asignado_nombre'] ?? 'Sin asignar') ?>
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
                                            'PENDIENTES' => 'warning',
                                            'APROBADAS' => 'info',
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
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('ordenes-trabajo/show/' . $orden['id']) ?>" 
                                               class="btn btn-info btn-sm" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
                    <i class="fas fa-search fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-muted">No se encontraron órdenes</h5>
                    <p class="text-muted">Intenta ajustar los filtros de búsqueda o crear una nueva orden.</p>
                    <a href="<?= base_url('ordenes-trabajo/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Orden
                    </a>
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
                            <!-- Se cargará dinámicamente -->
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
        "order": [[ 7, "desc" ]], // Ordenar por fecha de solicitud descendente
        "pageLength": 25,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Select2 para vehículos
    $('#id_vehiculo').select2({
        placeholder: "Seleccionar vehículo",
        allowClear: true
    });
});

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

function exportarExcel() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.location.href = '<?= base_url('ordenes-trabajo/consulta') ?>?' + params.toString();
}
</script>
<?= $this->endSection() ?>
