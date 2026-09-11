<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Clasificación de Orden
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$estadoActual = $orden['estado_orden'] ?? $orden['estado'] ?? 'N/A';
$prioridad = $orden['prioridad'] ?? null;
$prioridadMap = [
    '1' => ['label' => 'Baja', 'class' => 'success', 'icon' => 'fa-arrow-down'],
    '2' => ['label' => 'Media', 'class' => 'warning', 'icon' => 'fa-equals'],
    '3' => ['label' => 'Alta', 'class' => 'danger', 'icon' => 'fa-arrow-up'],
    '4' => ['label' => 'Crítica', 'class' => 'dark', 'icon' => 'fa-exclamation'],
    'BAJA' => ['label' => 'Baja', 'class' => 'success', 'icon' => 'fa-arrow-down'],
    'MEDIA' => ['label' => 'Media', 'class' => 'warning', 'icon' => 'fa-equals'],
    'ALTA' => ['label' => 'Alta', 'class' => 'danger', 'icon' => 'fa-arrow-up'],
    'CRITICA' => ['label' => 'Crítica', 'class' => 'dark', 'icon' => 'fa-exclamation'],
];
$prioridadInfo = $prioridad && isset($prioridadMap[$prioridad])
    ? $prioridadMap[$prioridad]
    : ['label' => $prioridad ?? 'N/A', 'class' => 'secondary', 'icon' => 'fa-minus'];
$estadoClass = [
    'PENDIENTES' => 'warning',
    'APROBADAS' => 'info',
    'EN_PROCESO' => 'primary',
    'FINALIZADA' => 'success',
    'PLANIFICADA' => 'secondary'
];
?>
<style>
    .classification-hero {
        background: linear-gradient(135deg, #07b889 0%, #079c78 100%);
        border-radius: 1.25rem;
        color: #fff;
        padding: 1.5rem;
        box-shadow: 0 1rem 2.5rem rgba(7, 184, 137, .18);
    }

    .classification-card {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 .75rem 1.75rem rgba(15, 23, 42, .08);
    }

    .classification-icon {
        align-items: center;
        background: rgba(7, 184, 137, .12);
        border-radius: .9rem;
        color: #07b889;
        display: inline-flex;
        height: 2.6rem;
        justify-content: center;
        width: 2.6rem;
    }

    .classification-label {
        color: #64748b;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .classification-value {
        color: #0f172a;
        font-weight: 700;
    }

    .classification-kv {
        border-bottom: 1px solid #eef2f7;
        padding: .8rem 0;
    }

    .classification-kv:last-child {
        border-bottom: 0;
    }

    .classification-form .form-label {
        color: #334155;
        font-weight: 700;
    }
</style>
<div class="container-fluid">
    <div class="classification-hero mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
            <div>
                <div class="mb-2">
                    <span class="badge bg-light text-dark px-3 py-2">Clasificación</span>
                    <span class="badge bg-<?= $estadoClass[$estadoActual] ?? 'secondary' ?> text-white px-3 py-2"><?= esc($estadoActual) ?></span>
                    <span class="badge bg-<?= $prioridadInfo['class'] ?> text-white px-3 py-2">Prioridad <?= esc($prioridadInfo['label']) ?></span>
                </div>
                <h1 class="h3 mb-1 font-weight-bold">Orden #<?= esc($orden['codigo_consecutivo'] ?? $orden['id']) ?></h1>
                <p class="mb-0 opacity-75">Define tipo de problema, prioridad y responsable asignado.</p>
            </div>
            <div class="mt-3 mt-lg-0">
                <a href="<?= base_url('ordenes-trabajo/show/' . $orden['id']) ?>" class="btn btn-light btn-sm mr-2">
                    <i class="fas fa-eye me-1"></i> Ver orden
                </a>
                <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card classification-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <span class="classification-icon mr-3"><i class="fas fa-info-circle"></i></span>
                        <div>
                            <h5 class="mb-0 font-weight-bold">Información de la orden</h5>
                            <small class="text-muted">Datos principales antes de clasificar</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <div class="classification-label">Vehículo</div>
                                <div class="classification-value"><?= esc($orden['placa'] ?? 'N/A') ?></div>
                                <small class="text-muted"><?= esc(trim(($orden['marca'] ?? '') . ' ' . ($orden['modelo'] ?? ''))) ?></small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <div class="classification-label">Solicitante</div>
                                <div class="classification-value"><?= esc($orden['nombre_solicitante'] ?? $orden['solicitante'] ?? 'N/A') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <div class="classification-label">Estado actual</div>
                                <span class="badge bg-<?= $estadoClass[$estadoActual] ?? 'secondary' ?> text-white mt-1"><?= esc($estadoActual) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="classification-label mb-2">Descripción</div>
                    <div class="bg-light p-4 rounded">
                        <?= nl2br(esc($orden['descripcion'] ?? 'Sin descripción')) ?>
                    </div>
                </div>
            </div>

            <div class="card classification-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <span class="classification-icon mr-3"><i class="fas fa-pen-alt"></i></span>
                        <div>
                            <h5 class="mb-0 font-weight-bold">Actualizar clasificación</h5>
                            <small class="text-muted">Completa los campos requeridos para continuar el flujo</small>
                        </div>
                    </div>
                    <form action="<?= base_url('ordenes-trabajo/clasificacion/' . $orden['id']) ?>" method="POST" class="classification-form">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="id_tipo_problema" class="form-label">
                                <i class="fas fa-exclamation-circle me-1"></i>Tipo de problema <span class="text-danger">*</span>
                            </label>
                            <select name="id_tipo_problema" id="id_tipo_problema" class="form-select select2" required>
                                <option value="">Seleccione un tipo de problema</option>
                                <?php foreach ($tiposProblema as $categoria => $items): ?>
                                    <optgroup label="<?= esc($categoria) ?>">
                                        <?php foreach ($items as $tipo): ?>
                                            <option value="<?= $tipo['id'] ?>" <?= ($orden['id_tipo_problema'] ?? null) == $tipo['id'] ? 'selected' : '' ?>>
                                                <?= esc($tipo['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="prioridad" class="form-label">
                                <i class="fas fa-flag me-1"></i>Prioridad <span class="text-danger">*</span>
                            </label>
                            <select name="prioridad" id="prioridad" class="form-select" required>
                                <option value="1" <?= ($orden['prioridad'] ?? '') == '1' ? 'selected' : '' ?>>1 - Baja</option>
                                <option value="2" <?= ($orden['prioridad'] ?? '') == '2' ? 'selected' : '' ?>>2 - Media</option>
                                <option value="3" <?= ($orden['prioridad'] ?? '') == '3' ? 'selected' : '' ?>>3 - Alta</option>
                                <option value="4" <?= ($orden['prioridad'] ?? '') == '4' ? 'selected' : '' ?>>4 - Crítica</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="id_asignado" class="form-label">
                                <i class="fas fa-user-cog me-1"></i>Asignar a
                            </label>
                            <select name="id_asignado" id="id_asignado" class="form-select select2">
                                <option value="">Sin asignar</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= $usuario['id'] ?>" <?= ($orden['id_asignado'] ?? null) == $usuario['id'] ? 'selected' : '' ?>>
                                        <?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('ordenes-trabajo/show/' . $orden['id']) ?>" class="btn btn-light mr-2">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card classification-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="classification-icon mr-3"><i class="fas fa-clipboard-list"></i></span>
                        <div>
                            <h5 class="mb-0 font-weight-bold">Resumen</h5>
                            <small class="text-muted">Estado actual de la clasificación</small>
                        </div>
                    </div>
                    <div class="classification-kv">
                        <div class="classification-label">Prioridad actual</div>
                        <span class="badge bg-<?= $prioridadInfo['class'] ?> text-white mt-1">
                            <i class="fas <?= $prioridadInfo['icon'] ?> me-1"></i><?= esc($prioridadInfo['label']) ?>
                        </span>
                    </div>
                    <div class="classification-kv">
                        <div class="classification-label">Asignado a</div>
                        <div class="classification-value"><?= esc($orden['nombre_asignado'] ?? 'Sin asignar') ?></div>
                    </div>
                    <div class="classification-kv">
                        <div class="classification-label">Fecha solicitud</div>
                        <div class="classification-value"><?= !empty($orden['fecha_solicitud']) ? date('d/m/Y H:i', strtotime($orden['fecha_solicitud'])) : 'N/A' ?></div>
                    </div>
                    <?php if (!empty($orden['fecha_asignacion'])): ?>
                        <div class="classification-kv">
                            <div class="classification-label">Fecha asignación</div>
                            <div class="classification-value"><?= date('d/m/Y H:i', strtotime($orden['fecha_asignacion'])) ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($orden['url_foto'])): ?>
                        <div class="mt-3">
                            <a href="<?= base_url($orden['url_foto']) ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>Ver foto adjunta
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
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Seleccione una opción',
        allowClear: true,
        width: '100%'
    });
});
</script>
<?= $this->endSection() ?>
