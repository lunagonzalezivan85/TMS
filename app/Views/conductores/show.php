<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$c = $conductor;
$estadoActivo = ($c['estado'] ?? '') === 'ACTIVO';
$antiguedad = '';
if (!empty($c['fechaIngreso'])) {
    $fi = new DateTime($c['fechaIngreso']);
    $diff = $fi->diff(new DateTime());
    $antiguedad = $diff->y . ' años, ' . $diff->m . ' meses';
} else {
    $antiguedad = 'No disponible';
}
?>

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-1"></i> <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Card superior: datos básicos -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-3">
            <!-- Avatar -->
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:72px;height:72px;font-size:1.75rem;font-weight:700;">
                <?= strtoupper(substr($c['nombre'] ?? 'C', 0, 1)) ?>
            </div>

            <!-- Info principal -->
            <div class="flex-grow-1 text-center text-md-start">
                <h4 class="fw-bold mb-1"><?= esc($c['nombre'] ?? '') ?> <?= esc($c['apellido'] ?? '') ?></h4>
                <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 mb-2">
                    <span class="badge bg-light text-dark border"><?= esc($c['codigo_consecutivo'] ?? '-') ?></span>
                    <span class="badge bg-light text-dark border"><i class="fas fa-id-card me-1"></i><?= esc($c['dni'] ?? '-') ?></span>
                    <?php if (!empty($c['carnet'])): ?>
                    <span class="badge bg-light text-dark border"><i class="fas fa-user-tag me-1"></i>Carnet: <?= esc($c['carnet']) ?></span>
                    <?php endif; ?>
                    <span class="badge <?= $estadoActivo ? 'bg-success' : 'bg-secondary' ?>"><?= esc($c['estado'] ?? 'INACTIVO') ?></span>
                </div>
                <div class="text-muted small">
                    <i class="fas fa-calendar-alt me-1"></i>Ingreso: <?= !empty($c['fechaIngreso']) ? date('d/m/Y', strtotime($c['fechaIngreso'])) : '-' ?>
                    &nbsp;|&nbsp;
                    <i class="fas fa-clock me-1"></i>Antigüedad: <?= $antiguedad ?>
                </div>
            </div>

            <!-- Acciones -->
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="<?= base_url('conductores') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i><span class="d-none d-lg-inline ms-1">Volver</span>
                </a>
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('conductores/edit/' . $c['id']) ?>"><i class="fas fa-edit me-2 text-primary"></i>Editar</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('conductores/documentos/' . $c['id']) ?>"><i class="fas fa-file-alt me-2 text-warning"></i>Documentos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if ($estadoActivo): ?>
                        <li><button class="dropdown-item text-warning cambiar-estado" data-id="<?= $c['id'] ?>" data-estado="INACTIVO"><i class="fas fa-pause me-2"></i>Desactivar</button></li>
                        <?php else: ?>
                        <li><button class="dropdown-item text-success cambiar-estado" data-id="<?= $c['id'] ?>" data-estado="ACTIVO"><i class="fas fa-play me-2"></i>Activar</button></li>
                        <?php endif; ?>
                        <li><button class="dropdown-item text-danger eliminar-conductor" data-id="<?= $c['id'] ?>"><i class="fas fa-trash me-2"></i>Eliminar</button></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- KPIs compactos -->
        <hr class="my-3">
        <div class="row text-center g-2">
            <div class="col-6 col-md-3">
                <div class="border rounded-3 p-2">
                    <div class="fs-5 fw-bold text-primary"><?= count($vehiculos_asignados) ?></div>
                    <div class="text-muted small">Vehículos</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded-3 p-2">
                    <div class="fs-5 fw-bold text-success"><?= $solicitudes_activas ?? 0 ?></div>
                    <div class="text-muted small">Solicitudes Activas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded-3 p-2">
                    <div class="fs-5 fw-bold text-info"><?= $mantenimientos_completados ?? 0 ?></div>
                    <div class="text-muted small">Mantenimientos</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded-3 p-2">
                    <div class="fs-5 fw-bold text-warning"><?= count($documentos ?? []) ?></div>
                    <div class="text-muted small">Documentos</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" id="conductorTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-info" type="button" role="tab">
            <i class="fas fa-user me-1"></i>Información
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-vehiculos" type="button" role="tab">
            <i class="fas fa-car me-1"></i>Vehículos
            <span class="badge bg-primary ms-1"><?= count($vehiculos_asignados) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-documentos" type="button" role="tab">
            <i class="fas fa-file-alt me-1"></i>Documentos
            <span class="badge bg-warning text-dark ms-1"><?= count($documentos ?? []) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-historial" type="button" role="tab">
            <i class="fas fa-history me-1"></i>Historial
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- Tab: Información -->
    <div class="tab-pane fade show active" id="tab-info" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Código</label>
                            <div class="fs-6"><?= esc($c['codigo_consecutivo'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Carnet</label>
                            <div class="fs-6"><?= esc($c['carnet'] ?? 'No disponible') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Nombre</label>
                            <div class="fs-6"><?= esc($c['nombre'] ?? '') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Apellido</label>
                            <div class="fs-6"><?= esc($c['apellido'] ?? '') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Documento</label>
                            <div class="fs-6"><?= esc($c['dni'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Fecha de Ingreso</label>
                            <div class="fs-6"><?= !empty($c['fechaIngreso']) ? date('d/m/Y', strtotime($c['fechaIngreso'])) : '-' ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Estado</label>
                            <div><span class="badge <?= $estadoActivo ? 'bg-success' : 'bg-secondary' ?>"><?= esc($c['estado'] ?? 'INACTIVO') ?></span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Fecha de Registro</label>
                            <div class="fs-6"><?= !empty($c['fechaRegistro']) ? date('d/m/Y H:i', strtotime($c['fechaRegistro'])) : '-' ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">PIN de Acceso</label>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-5 fw-bold font-monospace" id="pin-display">
                                    <?= !empty($c['pin_acceso']) ? esc($c['pin_acceso']) : '<span class="text-muted">Sin PIN</span>' ?>
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-generar-pin" data-id="<?= $c['id'] ?>">
                                    <i class="fas fa-key me-1"></i><?= !empty($c['pin_acceso']) ? 'Regenerar' : 'Generar' ?>
                                </button>
                            </div>
                            <small class="text-muted">PIN de 6 dígitos para acceso al portal de conductores</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Vehículos -->
    <div class="tab-pane fade" id="tab-vehiculos" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <?php if (empty($vehiculos_asignados)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-car fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted mb-3">No tiene vehículos asignados actualmente</p>
                        <a href="<?= base_url('asignacion-vehiculos/wizard') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Asignar Vehículo
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Placa</th>
                                    <th>Marca / Modelo</th>
                                    <th>Año</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vehiculos_asignados as $v): ?>
                                <tr>
                                    <td><?= esc($v['codigo_consecutivo'] ?? '-') ?></td>
                                    <td><strong><?= esc($v['placa'] ?? '') ?></strong></td>
                                    <td><?= esc($v['marca'] ?? '') ?> <?= esc($v['modelo'] ?? '') ?></td>
                                    <td><?= esc($v['anio'] ?? '-') ?></td>
                                    <td><span class="badge <?= ($v['estado'] ?? '') === 'ACTIVO' ? 'bg-success' : 'bg-secondary' ?>"><?= esc($v['estado'] ?? '') ?></span></td>
                                    <td class="text-end">
                                        <a href="<?= base_url('vehiculos/show/' . $v['id']) ?>" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tab: Documentos -->
    <div class="tab-pane fade" id="tab-documentos" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="fas fa-file-alt text-warning me-1"></i>Documentación</h6>
                    <a href="<?= base_url('conductores/documentos/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>Gestionar
                    </a>
                </div>
                <?php if (!empty($documentos)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Número</th>
                                    <th>Vencimiento</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documentos as $doc): ?>
                                <tr>
                                    <td><?= esc($doc['nombre_tipo_documento'] ?? $doc['tipo_documento'] ?? '-') ?></td>
                                    <td><?= esc($doc['numero_documento'] ?? '-') ?></td>
                                    <td><?= !empty($doc['fecha_vencimiento']) ? date('d/m/Y', strtotime($doc['fecha_vencimiento'])) : '-' ?></td>
                                    <td>
                                        <?php
                                        $estDoc = $doc['estado'] ?? 'VIGENTE';
                                        $cls = match($estDoc) { 'VENCIDO' => 'bg-danger', 'POR_VENCER' => 'bg-warning text-dark', default => 'bg-success' };
                                        ?>
                                        <span class="badge <?= $cls ?>"><?= esc($estDoc) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted mb-0">No hay documentos registrados</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tab: Historial -->
    <div class="tab-pane fade" id="tab-historial" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <?php if (!empty($historial_asignaciones)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Vehículo</th>
                                    <th>Tipo de Unidad</th>
                                    <th>Fecha Asignación</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial_asignaciones as $a): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($a['placa'] ?? '') ?></strong><br>
                                        <small class="text-muted"><?= esc($a['marca'] ?? '') ?> <?= esc($a['modelo'] ?? '') ?> <?= $a['anio'] ?? '' ? '(' . $a['anio'] . ')' : '' ?></small>
                                    </td>
                                    <td><span class="badge bg-info"><?= esc($a['tipo_unidad_descripcion'] ?? '-') ?></span></td>
                                    <td><?= !empty($a['fecha_asignacion']) ? date('d/m/Y H:i', strtotime($a['fecha_asignacion'])) : '<span class="text-muted">No registrada</span>' ?></td>
                                    <td>
                                        <?php
                                        $estHist = $a['estado'] ?? '';
                                        $clsHist = match($estHist) { 'ACTIVA' => 'bg-success', 'DESASIGNADA' => 'bg-secondary', 'CANCELADA' => 'bg-danger', default => 'bg-warning text-dark' };
                                        ?>
                                        <span class="badge <?= $clsHist ?>"><?= esc($estHist) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row text-center mt-3 g-2">
                        <div class="col-md-3">
                            <div class="border rounded-3 p-2">
                                <div class="fs-5 fw-bold"><?= count($historial_asignaciones) ?></div>
                                <div class="text-muted small">Total</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded-3 p-2">
                                <div class="fs-5 fw-bold text-success"><?= count(array_filter($historial_asignaciones, fn($x) => ($x['estado'] ?? '') === 'ACTIVA')) ?></div>
                                <div class="text-muted small">Activas</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded-3 p-2">
                                <div class="fs-5 fw-bold text-secondary"><?= count(array_filter($historial_asignaciones, fn($x) => ($x['estado'] ?? '') === 'DESASIGNADA')) ?></div>
                                <div class="text-muted small">Desasignadas</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded-3 p-2">
                                <div class="fs-5 fw-bold text-info"><?= count(array_unique(array_column($historial_asignaciones, 'tipo_unidad_descripcion'))) ?></div>
                                <div class="text-muted small">Tipos de Unidad</div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-car-side fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted mb-3">Sin historial de asignaciones</p>
                        <a href="<?= base_url('asignacion-vehiculos/wizard') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Asignar Vehículo
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Cambiar Estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado del Conductor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCambiarEstado">
                    <input type="hidden" id="conductor-id" name="id">
                    <input type="hidden" id="nuevo-estado" name="estado">
                    <div class="mb-3">
                        <label class="form-label">Motivo del cambio:</label>
                        <textarea class="form-control" id="motivo-cambio" name="motivo" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmar-cambio-estado">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este conductor?</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                <input type="hidden" id="eliminar-conductor-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmar-eliminacion">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
<?php if (session('success')): ?>
Swal.fire({ icon: 'success', title: '¡Éxito!', text: '<?= esc(session('success')) ?>', showConfirmButton: false, timer: 3000 });
<?php endif; ?>
<?php if (session('error')): ?>
Swal.fire({ icon: 'error', title: 'Error', text: '<?= esc(session('error')) ?>', showConfirmButton: true });
<?php endif; ?>

document.addEventListener('click', function(e) {
    // Cambiar estado
    const btnEstado = e.target.closest('.cambiar-estado');
    if (btnEstado) {
        e.preventDefault();
        document.getElementById('conductor-id').value = btnEstado.dataset.id;
        document.getElementById('nuevo-estado').value = btnEstado.dataset.estado;
        document.getElementById('motivo-cambio').value = '';
        new bootstrap.Modal(document.getElementById('modalCambiarEstado')).show();
        return;
    }

    // Eliminar
    const btnEliminar = e.target.closest('.eliminar-conductor');
    if (btnEliminar) {
        e.preventDefault();
        document.getElementById('eliminar-conductor-id').value = btnEliminar.dataset.id;
        new bootstrap.Modal(document.getElementById('modalEliminar')).show();
        return;
    }
});

// Confirmar cambio de estado
document.getElementById('confirmar-cambio-estado').addEventListener('click', function() {
    const formData = new FormData(document.getElementById('formCambiarEstado'));
    fetch('<?= base_url('conductores/cambiarEstado') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else Swal.fire('Error', data.message || 'Error', 'error');
    })
    .catch(() => Swal.fire('Error', 'Error al cambiar el estado', 'error'));
});

// Confirmar eliminación
document.getElementById('confirmar-eliminacion').addEventListener('click', function() {
    const id = document.getElementById('eliminar-conductor-id').value;
    fetch(`<?= base_url('conductores/delete') ?>/${id}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-HTTP-Method-Override': 'DELETE' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) window.location.href = '<?= base_url('conductores') ?>';
        else Swal.fire('Error', data.message || 'Error', 'error');
    })
    .catch(() => Swal.fire('Error', 'Error al eliminar', 'error'));
});

// Generar PIN de acceso
document.getElementById('btn-generar-pin').addEventListener('click', function() {
    const id = this.dataset.id;
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Generando...';

    fetch(`<?= base_url('conductores/generar-pin') ?>/${id}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('pin-display').innerHTML = data.pin;
            btn.innerHTML = '<i class="fas fa-key me-1"></i>Regenerar';
            Swal.fire({ icon: 'success', title: 'PIN generado', text: 'Nuevo PIN: ' + data.pin, timer: 3000, showConfirmButton: false });
        } else {
            Swal.fire('Error', data.message || 'Error al generar PIN', 'error');
            btn.innerHTML = '<i class="fas fa-key me-1"></i>Generar';
        }
        btn.disabled = false;
    })
    .catch(() => {
        Swal.fire('Error', 'Error al generar PIN', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-key me-1"></i>Generar';
    });
});
</script>
<?= $this->endSection() ?>
