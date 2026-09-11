<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$estadoActual = $orden['estado_orden'] ?? $orden['estado'] ?? 'N/A';
$estadoTexto = [
    'PENDIENTES' => 'Pendiente', 'APROBADAS' => 'Aprobada',
    'EN_PROCESO' => 'En Proceso', 'FINALIZADA' => 'Finalizada',
    'PLANIFICADA' => 'Planificada'
];
$badgeClass = [
    'PENDIENTES' => 'bg-warning text-dark', 'APROBADAS' => 'bg-success',
    'EN_PROCESO' => 'bg-primary', 'FINALIZADA' => 'bg-secondary',
    'PLANIFICADA' => 'bg-info'
];
$prioridadRaw = $orden['prioridad'] ?? null;
$prioridadMap = [
    '1' => ['label' => 'Baja', 'class' => 'bg-light text-dark'],
    '2' => ['label' => 'Media', 'class' => 'bg-warning text-dark'],
    '3' => ['label' => 'Alta', 'class' => 'bg-danger'],
    '4' => ['label' => 'Crítica', 'class' => 'bg-dark'],
    'BAJA' => ['label' => 'Baja', 'class' => 'bg-light text-dark'],
    'MEDIA' => ['label' => 'Media', 'class' => 'bg-warning text-dark'],
    'ALTA' => ['label' => 'Alta', 'class' => 'bg-danger'],
    'CRITICA' => ['label' => 'Crítica', 'class' => 'bg-dark'],
];
$prioridadInfo = $prioridadRaw && isset($prioridadMap[$prioridadRaw])
    ? $prioridadMap[$prioridadRaw]
    : ['label' => $prioridadRaw ?? 'N/A', 'class' => 'bg-secondary'];
$diasDesdeCreacion = !empty($orden['fecha_solicitud'])
    ? floor((strtotime('now') - strtotime($orden['fecha_solicitud'])) / (60 * 60 * 24))
    : null;
?>
<style>
.ot-hero{background:linear-gradient(135deg,#1e3a5f 0%,#2d5a87 100%);border-radius:.75rem;color:#fff;padding:1.75rem;box-shadow:0 .5rem 2rem rgba(30,58,95,.2);}
.ot-card{border:none;border-radius:.75rem;box-shadow:0 .125rem .5rem rgba(15,23,42,.06);}
.ot-card:hover{box-shadow:0 .25rem 1rem rgba(15,23,42,.08);}
.ot-icon{width:42px;height:42px;border-radius:.5rem;display:flex;align-items:center;justify-content:center;font-size:1.1rem;}
.ot-kv{border-bottom:1px solid #f1f5f9;padding:.7rem 0;}
.ot-kv:last-child{border-bottom:0;}
.ot-label{color:#94a3b8;font-size:.7rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;margin-bottom:.15rem;}
.ot-value{color:#1e293b;font-weight:600;font-size:.9rem;}
.ot-stat{border-radius:.5rem;padding:1rem;text-align:center;}
</style>

<div class="container-fluid">
    <!-- Hero -->
    <div class="ot-hero mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
            <div>
                <div class="mb-2 d-flex gap-2 flex-wrap">
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">Orden de Trabajo</span>
                    <span class="badge <?= $badgeClass[$estadoActual] ?? 'bg-secondary' ?> rounded-pill px-3 py-2"><?= esc($estadoTexto[$estadoActual] ?? $estadoActual) ?></span>
                    <span class="badge <?= $prioridadInfo['class'] ?> rounded-pill px-3 py-2">Prioridad <?= esc($prioridadInfo['label']) ?></span>
                </div>
                <h1 class="h3 mb-1 fw-bold">#<?= esc($orden['codigo_consecutivo'] ?? $orden['id']) ?></h1>
                <p class="mb-0 opacity-75"><?= esc($orden['tipo_problema_nombre'] ?? 'Detalle de la orden de trabajo') ?></p>
            </div>
            <div class="mt-3 mt-lg-0 d-flex gap-2">
                <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-1"></i> Acciones
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('ordenes-trabajo/edit/' . $orden['id']) ?>">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a></li>
                        <li><a class="dropdown-item" href="<?= base_url('ordenes-trabajo/clasificacion/' . $orden['id']) ?>">
                            <i class="fas fa-tags me-2"></i>Clasificar
                        </a></li>
                        <?php if ($estadoActual !== 'FINALIZADA'): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= site_url('historial-orden-trabajo/create/' . $orden['id']) ?>">
                            <i class="fas fa-exchange-alt me-2"></i>Cambiar Estado
                        </a></li>
                        <?php if ($estadoActual === 'PENDIENTES'): ?>
                        <li><button type="button" class="dropdown-item" onclick="cambiarEstado('EN_PROCESO')">
                            <i class="fas fa-play me-2 text-primary"></i>Iniciar Trabajo
                        </button></li>
                        <li><button type="button" class="dropdown-item" onclick="cambiarEstado('APROBADAS')">
                            <i class="fas fa-check me-2 text-success"></i>Aprobar
                        </button></li>
                        <?php endif; ?>
                        <?php if ($estadoActual === 'APROBADAS'): ?>
                        <li><button type="button" class="dropdown-item" onclick="cambiarEstado('EN_PROCESO')">
                            <i class="fas fa-play me-2 text-primary"></i>Iniciar Trabajo
                        </button></li>
                        <?php endif; ?>
                        <?php if ($estadoActual === 'EN_PROCESO'): ?>
                        <li><a class="dropdown-item" href="<?= base_url('ordenes-trabajo/realizar/' . $orden['id']) ?>">
                            <i class="fas fa-tools me-2 text-primary"></i>Realizar Orden de Trabajo
                        </a></li>
                        <li><button type="button" class="dropdown-item" onclick="cambiarEstado('FINALIZADA')">
                            <i class="fas fa-check-circle me-2 text-success"></i>Finalizar
                        </button></li>
                        <?php endif; ?>
                        <?php if (empty($orden['asignado_nombre'])): ?>
                        <li><button type="button" class="dropdown-item" onclick="asignarOrden()">
                            <i class="fas fa-user-plus me-2 text-info"></i>Asignar
                        </button></li>
                        <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Columna izquierda -->
        <div class="col-lg-8">
            <!-- Info general -->
            <div class="card ot-card mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="ot-icon bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-info-circle"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold">Información General</h6>
                            <small class="text-muted">Resumen principal de la orden</small>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light bg-opacity-50">
                                <div class="ot-label">Código</div>
                                <div class="ot-value fs-5 text-primary"><?= esc($orden['codigo_consecutivo'] ?? 'N/A') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light bg-opacity-50">
                                <div class="ot-label">Estado</div>
                                <span class="badge rounded-pill <?= $badgeClass[$estadoActual] ?? 'bg-secondary' ?> mt-1"><?= esc($estadoTexto[$estadoActual] ?? $estadoActual) ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light bg-opacity-50">
                                <div class="ot-label">Prioridad</div>
                                <span class="badge rounded-pill <?= $prioridadInfo['class'] ?> mt-1"><?= esc($prioridadInfo['label']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="ot-label mb-2">Descripción del Problema</div>
                    <div class="bg-light rounded p-3 border">
                        <?= nl2br(esc($orden['descripcion'])) ?>
                    </div>

                    <?php if (!empty($orden['tipo_problema_nombre'])): ?>
                    <div class="mt-3">
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                            <i class="fas fa-tag me-1"></i><?= esc($orden['tipo_problema_nombre']) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Fechas -->
            <div class="card ot-card mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="ot-icon bg-info bg-opacity-10 text-info me-3"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold">Cronología</h6>
                            <small class="text-muted">Seguimiento temporal de la orden</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="ot-kv">
                                <div class="ot-label"><i class="fas fa-paper-plane me-1"></i>Solicitud</div>
                                <div class="ot-value"><?= !empty($orden['fecha_solicitud']) ? date('d/m/Y H:i', strtotime($orden['fecha_solicitud'])) : 'N/A' ?></div>
                            </div>
                            <div class="ot-kv">
                                <div class="ot-label"><i class="fas fa-calendar-check me-1"></i>Planificación</div>
                                <div class="ot-value"><?= !empty($orden['fecha_planificacion']) ? date('d/m/Y', strtotime($orden['fecha_planificacion'])) : 'Sin planificación' ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ot-kv">
                                <div class="ot-label"><i class="fas fa-user-check me-1"></i>Asignación</div>
                                <div class="ot-value"><?= !empty($orden['fecha_asignacion']) ? date('d/m/Y H:i', strtotime($orden['fecha_asignacion'])) : 'Sin asignación' ?></div>
                            </div>
                            <div class="ot-kv">
                                <div class="ot-label"><i class="fas fa-flag-checkered me-1"></i>Finalización</div>
                                <div class="ot-value"><?= !empty($orden['fecha_cierre']) ? date('d/m/Y H:i', strtotime($orden['fecha_cierre'])) : 'Pendiente' ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicadores -->
            <div class="card ot-card mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="ot-icon bg-success bg-opacity-10 text-success me-3"><i class="fas fa-chart-line"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold">Indicadores</h6>
                            <small class="text-muted">Tiempos de gestión</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="ot-stat bg-primary bg-opacity-10">
                                <div class="fs-3 fw-bold text-primary"><?= $diasDesdeCreacion ?? '—' ?></div>
                                <small class="text-muted">Días desde creación</small>
                            </div>
                        </div>
                        <?php if (!empty($orden['fecha_asignacion'])): ?>
                        <?php $diasAsig = floor((strtotime('now') - strtotime($orden['fecha_asignacion'])) / 86400); ?>
                        <div class="col-4">
                            <div class="ot-stat bg-info bg-opacity-10">
                                <div class="fs-3 fw-bold text-info"><?= $diasAsig ?></div>
                                <small class="text-muted">Días desde asignación</small>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($orden['fecha_cierre']) && !empty($orden['fecha_solicitud'])): ?>
                        <?php $tiempoTotal = floor((strtotime($orden['fecha_cierre']) - strtotime($orden['fecha_solicitud'])) / 86400); ?>
                        <div class="col-4">
                            <div class="ot-stat bg-success bg-opacity-10">
                                <div class="fs-3 fw-bold text-success"><?= $tiempoTotal ?></div>
                                <small class="text-muted">Días total</small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha -->
        <div class="col-lg-4">
            <!-- Vehículo -->
            <div class="card ot-card mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="ot-icon bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-car"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold">Vehículo</h6>
                            <small class="text-muted"><?= esc($orden['marca'] ?? '') ?> <?= esc($orden['modelo'] ?? '') ?></small>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:64px;height:64px;">
                            <i class="fas fa-truck-moving fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-bold text-primary mb-0"><?= esc($orden['placa'] ?? 'N/A') ?></h4>
                    </div>
                    <div class="row g-0">
                        <div class="col-6">
                            <?php if (!empty($orden['anio'])): ?>
                            <div class="ot-kv"><div class="ot-label">Año</div><div class="ot-value"><?= esc($orden['anio']) ?></div></div>
                            <?php endif; ?>
                            <?php if (!empty($orden['kilometraje'])): ?>
                            <div class="ot-kv"><div class="ot-label">Kilometraje</div><div class="ot-value"><?= number_format($orden['kilometraje']) ?> km</div></div>
                            <?php endif; ?>
                            <?php if (!empty($orden['numero_motor'])): ?>
                            <div class="ot-kv"><div class="ot-label">Nº Motor</div><div class="ot-value"><?= esc($orden['numero_motor']) ?></div></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <?php if (!empty($orden['modelo_motor'])): ?>
                            <div class="ot-kv"><div class="ot-label">Modelo Motor</div><div class="ot-value"><?= esc($orden['modelo_motor']) ?></div></div>
                            <?php endif; ?>
                            <?php if (isset($orden['disponible'])): ?>
                            <div class="ot-kv"><div class="ot-label">Disponible</div>
                                <span class="badge rounded-pill <?= $orden['disponible'] ? 'bg-success' : 'bg-danger' ?>"><?= $orden['disponible'] ? 'Sí' : 'No' ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (isset($orden['compuesto'])): ?>
                            <div class="ot-kv"><div class="ot-label">Compuesto</div>
                                <span class="badge rounded-pill <?= $orden['compuesto'] ? 'bg-info' : 'bg-secondary' ?>"><?= $orden['compuesto'] ? 'Sí' : 'No' ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($orden['estado_vehiculo'])): ?>
                    <div class="mt-2">
                        <?php
                        $estVehClass = ['ACTIVO' => 'bg-success', 'INACTIVO' => 'bg-secondary', 'EN REPARACION' => 'bg-warning text-dark'];
                        ?>
                        <span class="badge rounded-pill <?= $estVehClass[$orden['estado_vehiculo']] ?? 'bg-light text-dark' ?>">
                            <?= esc($orden['estado_vehiculo']) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Personal -->
            <div class="card ot-card mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="ot-icon bg-warning bg-opacity-10 text-warning me-3"><i class="fas fa-users"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold">Personal</h6>
                            <small class="text-muted">Responsables involucrados</small>
                        </div>
                    </div>
                    <div class="ot-kv">
                        <div class="ot-label"><i class="fas fa-user me-1"></i>Solicitante</div>
                        <div class="ot-value"><?= esc($orden['nombre_solicitante'] ?? $orden['solicitante'] ?? 'N/A') ?></div>
                    </div>
                    <div class="ot-kv">
                        <div class="ot-label"><i class="fas fa-user-cog me-1"></i>Asignado a</div>
                        <div class="ot-value">
                            <?php $asignado = $orden['nombre_asignado'] ?? $orden['asignado_nombre'] ?? null; ?>
                            <?php if ($asignado): ?>
                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1"><?= esc($asignado) ?></span>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Sin asignar</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold"><i class="fas fa-exchange-alt me-2 text-primary"></i>Cambiar Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de cambiar el estado de la orden a <strong id="nuevoEstadoTexto" class="text-primary"></strong>?</p>
                <input type="hidden" id="nuevoEstadoValor">
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmarCambioEstado()">
                    <i class="fas fa-check me-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal asignar -->
<div class="modal fade" id="modalAsignar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2 text-info"></i>Asignar Orden</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="usuarioAsignado" class="form-label fw-medium">Asignar a:</label>
                <select id="usuarioAsignado" class="form-select" required>
                    <option value="">Seleccionar usuario</option>
                </select>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmarAsignacion()">
                    <i class="fas fa-user-plus me-1"></i>Asignar
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function cambiarEstado(nuevoEstado) {
    $('#nuevoEstadoValor').val(nuevoEstado);
    $('#nuevoEstadoTexto').text(nuevoEstado);
    var modal = new bootstrap.Modal(document.getElementById('modalCambiarEstado'));
    modal.show();
}

function confirmarCambioEstado() {
    const nuevoEstado = $('#nuevoEstadoValor').val();
    $.ajax({
        url: '<?= base_url('ordenes-trabajo/cambiar-estado') ?>',
        method: 'POST',
        data: { id: <?= $orden['id'] ?>, estado: nuevoEstado, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
        success: function(response) {
            if (response.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
                location.reload();
            } else { alert('Error: ' + response.message); }
        },
        error: function() { alert('Error al cambiar el estado'); }
    });
}

function asignarOrden() {
    $.ajax({
        url: '<?= base_url('usuarios/getUsuarios') ?>',
        method: 'GET',
        success: function(response) {
            $('#usuarioAsignado').empty().append('<option value="">Seleccionar usuario</option>');
            if (response.success && response.data) {
                response.data.forEach(function(usuario) {
                    $('#usuarioAsignado').append('<option value="' + usuario.id + '">' + usuario.nombre + '</option>');
                });
            }
        }
    });
    var modal = new bootstrap.Modal(document.getElementById('modalAsignar'));
    modal.show();
}

function confirmarAsignacion() {
    const usuarioId = $('#usuarioAsignado').val();
    if (!usuarioId) { alert('Por favor selecciona un usuario'); return; }
    $.ajax({
        url: '<?= base_url('ordenes-trabajo/asignar-orden') ?>',
        method: 'POST',
        data: { id: <?= $orden['id'] ?>, id_asignado: usuarioId, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
        success: function(response) {
            if (response.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalAsignar')).hide();
                location.reload();
            } else { alert('Error: ' + response.message); }
        },
        error: function() { alert('Error al asignar la orden'); }
    });
}
</script>
<?= $this->endSection() ?>
