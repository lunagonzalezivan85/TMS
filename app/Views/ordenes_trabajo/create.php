<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
.wz-wrap{max-width:820px;margin:0 auto;}
.wz-steps{display:flex;gap:0;margin-bottom:2rem;}
.wz-step{flex:1;text-align:center;position:relative;}
.wz-step:not(:last-child)::after{content:'';position:absolute;top:20px;left:50%;width:100%;height:2px;background:#e2e8f0;z-index:0;}
.wz-step.done::after,.wz-step.active::after{background:#0d6efd;}
.wz-bubble{width:40px;height:40px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;position:relative;z-index:1;background:#e2e8f0;color:#64748b;transition:all .3s;}
.wz-step.active .wz-bubble{background:#0d6efd;color:#fff;box-shadow:0 0 0 4px #cfe2ff;}
.wz-step.done .wz-bubble{background:#198754;color:#fff;}
.wz-label{font-size:.75rem;margin-top:.35rem;color:#64748b;font-weight:500;}
.wz-step.active .wz-label{color:#0d6efd;font-weight:700;}
.wz-panel{display:none;}.wz-panel.active{display:block;}
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-0">
                <i class="fas fa-tools me-2 text-primary"></i>Nueva Orden de Trabajo
            </h1>
            <p class="mb-0 text-muted small">Completa la información para crear una nueva orden.</p>
        </div>
        <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Volver
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php $validationErrors = session()->getFlashdata('errors'); if (!empty($validationErrors) && is_array($validationErrors)): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <strong><i class="fas fa-exclamation-triangle me-2"></i>Errores de validación:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach ($validationErrors as $field => $msg): ?>
                <li><strong><?= esc($field) ?>:</strong> <?= esc(is_array($msg) ? implode(', ', $msg) : $msg) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="wz-wrap">
        <!-- Step indicators -->
        <div class="wz-steps">
            <div class="wz-step active" id="ind1">
                <div class="wz-bubble">1</div>
                <div class="wz-label">Vehículo</div>
            </div>
            <div class="wz-step" id="ind2">
                <div class="wz-bubble">2</div>
                <div class="wz-label">Detalle</div>
            </div>
            <div class="wz-step" id="ind3">
                <div class="wz-bubble">3</div>
                <div class="wz-label">Crear</div>
            </div>
        </div>

        <form action="<?= base_url('ordenes-trabajo/store') ?>" method="POST" id="formOrden" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- ═══════════════ STEP 1: Vehículo ═══════════════ -->
            <div class="wz-panel active" id="panel1">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-car text-primary me-2"></i>Selecciona el Vehículo y Problema
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-car me-1"></i>Vehículo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select select2" id="id_vehiculo" name="id_vehiculo" required>
                                <option value="">Seleccione un vehículo</option>
                                <?php foreach ($vehiculos as $vehiculo): ?>
                                    <option value="<?= $vehiculo['id'] ?>" <?= old('id_vehiculo') == $vehiculo['id'] ? 'selected' : '' ?>
                                        data-placa="<?= esc($vehiculo['placa']) ?>"
                                        data-marca="<?= esc($vehiculo['marca']) ?>"
                                        data-modelo="<?= esc($vehiculo['modelo']) ?>"
                                        data-km="<?= (int)($vehiculo['kilometraje'] ?? 0) ?>">
                                        <?= $vehiculo['placa'] ?> - <?= $vehiculo['marca'] ?> <?= $vehiculo['modelo'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-exclamation-circle me-1"></i>Tipo de Problema <span class="text-danger">*</span>
                            </label>
                            <select name="id_tipo_problema" id="id_tipo_problema" class="form-select select2" required>
                                <option value="">Seleccione un tipo de problema</option>
                                <?php foreach ($tiposProblema as $categoria => $items): ?>
                                    <optgroup label="<?= esc($categoria) ?>">
                                        <?php foreach ($items as $tipo): ?>
                                            <option value="<?= $tipo['id'] ?>" <?= old('id_tipo_problema') == $tipo['id'] ? 'selected' : '' ?>>
                                                <?= esc($tipo['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Info del vehículo seleccionado -->
                        <div id="vehInfoPreview" class="border rounded p-3 bg-light" style="display:none;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck-moving fa-2x text-primary me-3"></i>
                                <div>
                                    <div class="fw-bold" id="vehPlaca">—</div>
                                    <small class="text-muted" id="vehModelo">—</small>
                                </div>
                                <div class="ms-auto text-end">
                                    <small class="text-muted d-block">Kilometraje</small>
                                    <strong id="vehKm">—</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary px-4" onclick="goToStep(2)">
                        Continuar <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ═══════════════ STEP 2: Detalle ═══════════════ -->
            <div class="wz-panel" id="panel2">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-clipboard-list text-primary me-2"></i>Describe la Solicitud
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-user me-1"></i>Solicitante <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="solicitante" id="solicitante" class="form-control"
                                   placeholder="Nombre completo del solicitante" value="<?= old('solicitante') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-align-left me-1"></i>Descripción <span class="text-danger">*</span>
                            </label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="5"
                                      placeholder="Describe el problema o solicitud con al menos 10 caracteres" required><?= old('descripcion') ?></textarea>
                            <small class="text-muted">Mínimo 10 caracteres.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-camera me-1"></i>Foto (opcional)
                            </label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/png, image/jpeg">
                            <small class="text-muted">Formatos: JPG, JPEG, PNG. Máximo 5 MB.</small>
                            <div id="previewFoto" class="mt-2" style="display: none;">
                                <img src="#" alt="Previsualización" class="img-thumbnail" style="max-height: 200px; width: auto;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" onclick="goToStep(1)">
                        <i class="fas fa-arrow-left me-1"></i> Atrás
                    </button>
                    <button type="button" class="btn btn-primary px-4" onclick="goToStep(3)">
                        Continuar <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ═══════════════ STEP 3: Crear ═══════════════ -->
            <div class="wz-panel" id="panel3">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-check-circle text-success me-2"></i>Revisar y Crear
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="resumenContent" class="border rounded p-3 bg-light mb-3">
                            <p class="text-muted text-center mb-0 py-3">Revisa los datos ingresados.</p>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="confirmar_crear" required>
                            <label class="form-check-label" for="confirmar_crear">
                                <strong>Confirmo que los datos son correctos</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" onclick="goToStep(2)">
                        <i class="fas fa-arrow-left me-1"></i> Atrás
                    </button>
                    <button type="submit" class="btn btn-success px-4" id="btnCrear">
                        <i class="fas fa-save me-1"></i> Crear Orden
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentStep = 1;

function goToStep(step) {
    if (currentStep === 1 && step === 2) {
        const veh = document.getElementById('id_vehiculo').value;
        const tipo = document.getElementById('id_tipo_problema').value;
        if (!veh) { alert('Selecciona un vehículo'); return; }
        if (!tipo) { alert('Selecciona un tipo de problema'); return; }
    }
    if (currentStep === 2 && step === 3) {
        const solicitante = document.getElementById('solicitante').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        if (!solicitante || solicitante.length < 3) { alert('Ingresa el nombre del solicitante'); return; }
        if (!descripcion || descripcion.length < 10) { alert('La descripción debe tener al menos 10 caracteres'); return; }
    }

    document.querySelectorAll('.wz-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel' + step).classList.add('active');

    for (let i = 1; i <= 3; i++) {
        const ind = document.getElementById('ind' + i);
        ind.classList.remove('active', 'done');
        if (i < step) ind.classList.add('done');
        else if (i === step) ind.classList.add('active');
    }

    currentStep = step;
    if (step === 3) generarResumen();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function generarResumen() {
    const vehSelect = document.getElementById('id_vehiculo');
    const vehText = vehSelect.options[vehSelect.selectedIndex]?.text || '—';
    const tipoSelect = document.getElementById('id_tipo_problema');
    const tipoText = tipoSelect.options[tipoSelect.selectedIndex]?.text || '—';
    const solicitante = document.getElementById('solicitante').value || '—';
    const descripcion = document.getElementById('descripcion').value || '—';
    const foto = document.getElementById('foto').files[0];
    const fotoText = foto ? foto.name : 'Sin foto';

    document.getElementById('resumenContent').innerHTML = `
        <div class="row g-2">
            <div class="col-md-6"><small class="text-muted">Vehículo</small><div><strong>${vehText}</strong></div></div>
            <div class="col-md-6"><small class="text-muted">Tipo de problema</small><div><strong>${tipoText}</strong></div></div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-md-6"><small class="text-muted">Solicitante</small><div><strong>${solicitante}</strong></div></div>
            <div class="col-md-6"><small class="text-muted">Foto</small><div><strong>${fotoText}</strong></div></div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12"><small class="text-muted">Descripción</small><div>${descripcion}</div></div>
        </div>
    `;
}

// Select2 init
$(document).ready(function() {
    $('#id_vehiculo').select2({ placeholder: "Seleccionar vehículo", allowClear: true, width: '100%' });
    $('#id_tipo_problema').select2({ placeholder: "Seleccionar tipo de problema", allowClear: true, width: '100%' });

    // Mostrar info del vehículo al seleccionar
    $('#id_vehiculo').on('select2:select', function() {
        const opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) { $('#vehInfoPreview').hide(); return; }
        $('#vehPlaca').text(opt.dataset.placa || '—');
        $('#vehModelo').text((opt.dataset.marca || '') + ' ' + (opt.dataset.modelo || ''));
        $('#vehKm').text(parseInt(opt.dataset.km || 0).toLocaleString() + ' km');
        $('#vehInfoPreview').show();
    });
});

// Foto preview
document.getElementById('foto').addEventListener('change', function(e) {
    const input = e.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.querySelector('#previewFoto img').src = ev.target.result;
            document.getElementById('previewFoto').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        document.getElementById('previewFoto').style.display = 'none';
    }
});

// Validar confirmación antes de enviar
document.getElementById('formOrden').addEventListener('submit', function(e) {
    if (!document.getElementById('confirmar_crear').checked) {
        e.preventDefault();
        alert('Debes confirmar que los datos son correctos');
        return false;
    }
    document.getElementById('btnCrear').disabled = true;
    document.getElementById('btnCrear').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creando...';
});
</script>
<?= $this->endSection() ?>
