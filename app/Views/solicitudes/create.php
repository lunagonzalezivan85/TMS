<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
    .wizard-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .wizard-header {
        background: linear-gradient(135deg, #07b889 0%, #059669 100%);
        color: white;
        padding: 2rem;
        position: relative;
    }
    .wizard-header::after {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: radial-gradient(circle at 80% 20%, rgba(255,255,255,0.15) 0%, transparent 40%);
        pointer-events: none;
    }
    .progress-track {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 0 0.25rem;
        padding-top: 1.5rem;
    }
    .progress-track::before {
        content: '';
        position: absolute;
        top: 2.25rem;
        left: 0; right: 0;
        height: 4px;
        background: rgba(255,255,255,0.25);
        border-radius: 2px;
        z-index: 0;
    }
    .progress-fill {
        position: absolute;
        top: 2.25rem;
        left: 0;
        height: 4px;
        background: white;
        border-radius: 2px;
        z-index: 0;
        transition: width 0.4s ease;
    }
    .step-node {
        position: relative;
        z-index: 1;
        text-align: center;
        flex: 1;
    }
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        border: 2px solid rgba(255,255,255,0.4);
        transition: all 0.3s ease;
    }
    .step-node.active .step-circle,
    .step-node.completed .step-circle {
        background: white;
        color: #059669;
        border-color: white;
    }
    .step-label {
        display: block;
        margin-top: 0.4rem;
        font-size: 0.7rem;
        color: rgba(255,255,255,0.85);
        font-weight: 500;
    }
    .step-node.active .step-label,
    .step-node.completed .step-label {
        color: white;
        font-weight: 600;
    }
    .wizard-body {
        padding: 2.5rem;
        min-height: 420px;
    }
    .step-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    .step-subtitle {
        color: #64748b;
        margin-bottom: 1.75rem;
    }
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
    }
    .bento-card {
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
        position: relative;
    }
    .bento-card:hover {
        border-color: #07b889;
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(7,184,137,0.12);
    }
    .bento-card.selected {
        border-color: #07b889;
        background: #f0fdf9;
        box-shadow: 0 0 0 4px rgba(7,184,137,0.15);
    }
    .bento-card.selected::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: 10px;
        right: 12px;
        width: 24px;
        height: 24px;
        background: #07b889;
        color: white;
        border-radius: 50%;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bento-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 0.75rem;
    }
    .bento-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    .bento-desc {
        font-size: 0.8rem;
        color: #64748b;
    }
    .priority-baja .bento-icon { background: #dcfce7; color: #16a34a; }
    .priority-media .bento-icon { background: #fef9c3; color: #ca8a04; }
    .priority-alta .bento-icon { background: #ffedd5; color: #ea580c; }
    .priority-critica .bento-icon { background: #fee2e2; color: #dc2626; }
    .vehicle-result {
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }
    .vehicle-result:hover {
        border-color: #07b889;
        box-shadow: 0 8px 20px rgba(7,184,137,0.1);
    }
    .vehicle-result.selected {
        border-color: #07b889;
        background: #f0fdf9;
        box-shadow: 0 0 0 4px rgba(7,184,137,0.15);
    }
    .search-box {
        position: relative;
    }
    .search-box input {
        border-radius: 16px;
        padding-left: 3rem;
        height: 56px;
        font-size: 1.05rem;
        border: 2px solid #e2e8f0;
    }
    .search-box input:focus {
        border-color: #07b889;
        box-shadow: 0 0 0 4px rgba(7,184,137,0.1);
    }
    .search-box i {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.1rem;
    }
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 0.75rem;
    }
    .preview-item {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        position: relative;
        aspect-ratio: 1;
    }
    .preview-item img, .preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .nav-btns {
        display: flex;
        justify-content: space-between;
        padding: 1.5rem 2.5rem 2.5rem;
    }
    .summary-card {
        background: #f8fafc;
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px solid #e2e8f0;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-label { color: #64748b; font-weight: 500; }
    .summary-value { color: #1e293b; font-weight: 600; text-align: right; }
    .d-none { display: none !important; }
    textarea.form-control { resize: vertical; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="wizard-card">
                <div class="wizard-header">
                    <h3 class="fw-bold mb-1"><i class="fas fa-clipboard-list me-2"></i>Nueva Solicitud de Mantenimiento</h3>
                    <p class="mb-0 opacity-90">Sigue los pasos para reportar una avería o mantenimiento de vehículo</p>

                    <div class="progress-track mt-4">
                        <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
                        <div class="step-node active" data-step="1">
                            <div class="step-circle"><i class="fas fa-car"></i></div>
                            <span class="step-label">Vehículo</span>
                        </div>
                        <div class="step-node" data-step="2">
                            <div class="step-circle"><i class="fas fa-wrench"></i></div>
                            <span class="step-label">Tipo</span>
                        </div>
                        <div class="step-node" data-step="3">
                            <div class="step-circle"><i class="fas fa-tools"></i></div>
                            <span class="step-label">Problema</span>
                        </div>
                        <div class="step-node" data-step="4">
                            <div class="step-circle"><i class="fas fa-flag"></i></div>
                            <span class="step-label">Prioridad</span>
                        </div>
                        <div class="step-node" data-step="5">
                            <div class="step-circle"><i class="fas fa-edit"></i></div>
                            <span class="step-label">Detalle</span>
                        </div>
                        <div class="step-node" data-step="6">
                            <div class="step-circle"><i class="fas fa-check"></i></div>
                            <span class="step-label">Confirmar</span>
                        </div>
                    </div>
                </div>

                <form id="wizardForm" action="<?= base_url('solicitudes/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="wizard-body">
                        <!-- PASO 1: Vehículo -->
                        <div class="wizard-step" id="step-1">
                            <h4 class="step-title">Paso 1: Identificar el vehículo</h4>
                            <p class="step-subtitle">Busca por placa, número de motor o carnet del conductor asignado.</p>

                            <div class="search-box mb-4">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" id="buscarVehiculo" placeholder="Ej: ABC-123, motor-001 o CARNET-001" autocomplete="off">
                            </div>

                            <div id="vehiculosResultados" class="row g-3"></div>
                            <div id="sinResultados" class="text-center py-5 text-muted d-none">
                                <i class="fas fa-car-side fa-3x mb-3 opacity-25"></i>
                                <p>No se encontraron vehículos activos. Intenta con otra placa, motor o carnet.</p>
                            </div>

                            <input type="hidden" name="id_vehiculo" id="id_vehiculo" value="">
                        </div>

                        <!-- PASO 2: Tipo de mantenimiento -->
                        <div class="wizard-step d-none" id="step-2">
                            <h4 class="step-title">Paso 2: Tipo de mantenimiento</h4>
                            <p class="step-subtitle">Selecciona la naturaleza de la solicitud.</p>

                            <div class="bento-grid">
                                <label class="bento-card" onclick="selectTipo('PREVENTIVO')">
                                    <input type="radio" name="tipo_mantenimiento" value="PREVENTIVO" class="d-none">
                                    <div class="bento-icon" style="background:#e0f2fe;color:#0284c7;">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="bento-title">Preventivo</div>
                                    <div class="bento-desc">Mantenimiento programado</div>
                                </label>
                                <label class="bento-card" onclick="selectTipo('CORRECTIVO')">
                                    <input type="radio" name="tipo_mantenimiento" value="CORRECTIVO" class="d-none">
                                    <div class="bento-icon" style="background:#fef3c7;color:#d97706;">
                                        <i class="fas fa-wrench"></i>
                                    </div>
                                    <div class="bento-title">Correctivo</div>
                                    <div class="bento-desc">Reparación de falla</div>
                                </label>
                                <label class="bento-card" onclick="selectTipo('EMERGENCIA')">
                                    <input type="radio" name="tipo_mantenimiento" value="EMERGENCIA" class="d-none">
                                    <div class="bento-icon" style="background:#fee2e2;color:#dc2626;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="bento-title">Emergencia</div>
                                    <div class="bento-desc">Falla crítica, vehículo fuera de servicio</div>
                                </label>
                            </div>
                        </div>

                        <!-- PASO 3: Tipo de problema -->
                        <div class="wizard-step d-none" id="step-3">
                            <h4 class="step-title">Paso 3: Tipo de problema</h4>
                            <p class="step-subtitle">Selecciona la categoría que mejor describa la avería.</p>

                            <div class="bento-grid">
                                <?php foreach ($tiposProblema as $tp): ?>
                                    <label class="bento-card" onclick="selectProblema(this)">
                                        <input type="radio" name="id_tipo_problema" value="<?= $tp['id'] ?>" class="d-none" required>
                                        <div class="bento-icon" style="background:#f3e8ff;color:#9333ea;">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div class="bento-title"><?= esc($tp['nombre']) ?></div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- PASO 4: Prioridad -->
                        <div class="wizard-step d-none" id="step-4">
                            <h4 class="step-title">Paso 4: Prioridad</h4>
                            <p class="step-subtitle">Indica la urgencia de atención.</p>

                            <div class="bento-grid">
                                <label class="bento-card priority-baja" onclick="selectPrioridad(this)">
                                    <input type="radio" name="prioridad" value="1" class="d-none">
                                    <div class="bento-icon"><i class="fas fa-arrow-down"></i></div>
                                    <div class="bento-title">Baja</div>
                                    <div class="bento-desc">Puede esperar</div>
                                </label>
                                <label class="bento-card priority-media" onclick="selectPrioridad(this)">
                                    <input type="radio" name="prioridad" value="2" class="d-none">
                                    <div class="bento-icon"><i class="fas fa-minus"></i></div>
                                    <div class="bento-title">Media</div>
                                    <div class="bento-desc">Atención pronto</div>
                                </label>
                                <label class="bento-card priority-alta" onclick="selectPrioridad(this)">
                                    <input type="radio" name="prioridad" value="3" class="d-none">
                                    <div class="bento-icon"><i class="fas fa-arrow-up"></i></div>
                                    <div class="bento-title">Alta</div>
                                    <div class="bento-desc">Urgente</div>
                                </label>
                                <label class="bento-card priority-critica" onclick="selectPrioridad(this)">
                                    <input type="radio" name="prioridad" value="4" class="d-none">
                                    <div class="bento-icon"><i class="fas fa-fire"></i></div>
                                    <div class="bento-title">Crítica</div>
                                    <div class="bento-desc">Inmoviliza el vehículo</div>
                                </label>
                            </div>
                        </div>

                        <!-- PASO 5: Descripción y detalles -->
                        <div class="wizard-step d-none" id="step-5">
                            <h4 class="step-title">Paso 5: Descripción y detalles</h4>
                            <p class="step-subtitle">Describe la avería, indica la ubicación y adjunta evidencia.</p>

                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="descripcion" class="form-label fw-semibold">Descripción detallada</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control rounded-4" rows="4" minlength="10" placeholder="Describe la falla, síntomas y cualquier detalle relevante..." required></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="condicion_movilidad" class="form-label fw-semibold">Condición de movilidad</label>
                                    <select name="condicion_movilidad" id="condicion_movilidad" class="form-select rounded-4 py-3">
                                        <option value="OPERATIVO" selected>Operativo</option>
                                        <option value="INMOVILIZADO">Inmovilizado</option>
                                        <option value="ARRASTRE">Solo arrastre</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="ubicacion" class="form-label fw-semibold">Ubicación actual</label>
                                    <input type="text" name="ubicacion" id="ubicacion" class="form-control rounded-4 py-3" placeholder="Ej: Taller central, Ruta 32 km 15">
                                </div>

                                <div class="col-12">
                                    <label for="evidencia" class="form-label fw-semibold">Evidencias (foto/video)</label>
                                    <input type="file" name="evidencia" id="evidencia" class="form-control rounded-4 py-3" accept="image/*,video/*" onchange="previewEvidencia(this)">
                                    <small class="text-muted">Máximo 5 MB por archivo.</small>
                                    <div id="evidenciaPreview" class="preview-grid mt-3"></div>
                                </div>
                            </div>
                        </div>

                        <!-- PASO 6: Confirmación -->
                        <div class="wizard-step d-none" id="step-6">
                            <h4 class="step-title">Paso 6: Confirmar y enviar</h4>
                            <p class="step-subtitle">Revisa la información antes de crear la solicitud.</p>

                            <div class="summary-card mb-4">
                                <div class="summary-row">
                                    <span class="summary-label">Vehículo</span>
                                    <span class="summary-value" id="res-vehiculo">-</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Conductor</span>
                                    <span class="summary-value" id="res-conductor">-</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Tipo de mantenimiento</span>
                                    <span class="summary-value" id="res-tipo">-</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Problema</span>
                                    <span class="summary-value" id="res-problema">-</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Prioridad</span>
                                    <span class="summary-value" id="res-prioridad">-</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Condición</span>
                                    <span class="summary-value" id="res-condicion">-</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Ubicación</span>
                                    <span class="summary-value" id="res-ubicacion">-</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Descripción</span>
                                    <span class="summary-value" id="res-descripcion" style="max-width:60%;">-</span>
                                </div>
                            </div>

                            <div class="alert alert-light border rounded-4 d-flex align-items-center">
                                <i class="fas fa-info-circle text-success me-2 fs-5"></i>
                                <div>
                                    <strong>Estado inicial:</strong> PENDIENTE<br>
                                    <small class="text-muted">La solicitud pasará por aprobación, diagnóstico, asignación de mecánico y finalización.</small>
                                </div>
                            </div>

                            <div id="avisoInmovilizacion" class="alert alert-warning border-warning rounded-4 d-flex align-items-center d-none">
                                <i class="fas fa-ban text-warning me-2 fs-5"></i>
                                <div>
                                    <strong>El vehículo será marcado como "En Mantenimiento"</strong><br>
                                    <small class="text-muted">Por tener prioridad Crítica, tipo Emergencia o condición Inmovilizado, el vehículo quedará no disponible hasta que se finalice el mantenimiento.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nav-btns">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2 d-none" id="btn-atras" onclick="prevStep()">
                            <i class="fas fa-arrow-left me-2"></i>Atrás
                        </button>
                        <div></div>
                        <button type="button" class="btn btn-success rounded-pill px-5 py-2" id="btn-siguiente" onclick="nextStep()">
                            Siguiente<i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-success rounded-pill px-5 py-2 d-none" id="btn-enviar">
                            <i class="fas fa-paper-plane me-2"></i>Enviar solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
let currentStep = 1;
const totalSteps = 6;
let selectedVehicle = null;

function updateProgress() {
    const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
    document.getElementById('progress-fill').style.width = progress + '%';

    document.querySelectorAll('.step-node').forEach(node => {
        const step = parseInt(node.dataset.step);
        node.classList.remove('active', 'completed');
        if (step < currentStep) node.classList.add('completed');
        if (step === currentStep) node.classList.add('active');
    });
}

function showStep(step) {
    document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('d-none'));
    document.getElementById('step-' + step).classList.remove('d-none');

    document.getElementById('btn-atras').classList.toggle('d-none', step === 1);
    document.getElementById('btn-siguiente').classList.toggle('d-none', step === totalSteps);
    document.getElementById('btn-enviar').classList.toggle('d-none', step !== totalSteps);

    currentStep = step;
    updateProgress();
}

function validateStep(step) {
    if (step === 1) {
        if (!document.getElementById('id_vehiculo').value) {
            Swal.fire('Vehículo requerido', 'Busca y selecciona un vehículo activo.', 'warning');
            return false;
        }
    } else if (step === 2) {
        if (!document.querySelector('input[name="tipo_mantenimiento"]:checked')) {
            Swal.fire('Tipo requerido', 'Selecciona el tipo de mantenimiento.', 'warning');
            return false;
        }
    } else if (step === 3) {
        if (!document.querySelector('input[name="id_tipo_problema"]:checked')) {
            Swal.fire('Problema requerido', 'Selecciona el tipo de problema.', 'warning');
            return false;
        }
    } else if (step === 4) {
        if (!document.querySelector('input[name="prioridad"]:checked')) {
            Swal.fire('Prioridad requerida', 'Selecciona la prioridad.', 'warning');
            return false;
        }
    } else if (step === 5) {
        const desc = document.getElementById('descripcion').value.trim();
        if (desc.length < 10) {
            Swal.fire('Descripción requerida', 'La descripción debe tener al menos 10 caracteres.', 'warning');
            return false;
        }
    }
    return true;
}

function nextStep() {
    if (!validateStep(currentStep)) return;
    if (currentStep === totalSteps - 1) buildSummary();
    showStep(currentStep + 1);
}

function prevStep() {
    showStep(currentStep - 1);
}

// Búsqueda de vehículo
let searchTimeout;
document.getElementById('buscarVehiculo').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) {
        document.getElementById('vehiculosResultados').innerHTML = '';
        document.getElementById('sinResultados').classList.add('d-none');
        return;
    }
    searchTimeout = setTimeout(() => buscarVehiculo(q), 400);
});

async function buscarVehiculo(q) {
    const container = document.getElementById('vehiculosResultados');
    container.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-success"></i></div>';

    try {
        const res = await fetch(`${baseUrl}solicitudes/buscar-vehiculo?q=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        container.innerHTML = '';

        if (!json.success || json.data.length === 0) {
            document.getElementById('sinResultados').classList.remove('d-none');
            return;
        }
        document.getElementById('sinResultados').classList.add('d-none');

        json.data.forEach(v => {
            const col = document.createElement('div');
            col.className = 'col-md-6';
            col.innerHTML = `
                <div class="vehicle-result" onclick="seleccionarVehiculo(${v.id}, this)">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="fw-bold mb-1 text-success">${v.placa}</h5>
                            <p class="mb-1">${v.marca} ${v.modelo} ${v.anio ? '(' + v.anio + ')' : ''}</p>
                            <p class="mb-0 small text-muted"><i class="fas fa-road me-1"></i>${parseInt(v.kilometraje).toLocaleString()} km</p>
                        </div>
                        <i class="fas fa-car fa-2x text-muted opacity-25"></i>
                    </div>
                    <div class="mt-2 pt-2 border-top">
                        <p class="mb-0 small"><i class="fas fa-user me-1"></i>${v.conductor}</p>
                        ${v.carnet ? '<p class="mb-0 small text-muted">Carnet: ' + v.carnet + '</p>' : ''}
                    </div>
                </div>
            `;
            container.appendChild(col);
        });
    } catch (err) {
        console.error(err);
        Swal.fire('Error', 'No se pudo buscar el vehículo. Intenta de nuevo.', 'error');
    }
}

function seleccionarVehiculo(id, el) {
    document.querySelectorAll('.vehicle-result').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('id_vehiculo').value = id;

    selectedVehicle = {
        placa: el.querySelector('h5').textContent,
        info: el.querySelector('p.mb-1').textContent,
        conductor: el.querySelector('.border-top p').textContent.replace('Carnet: ', '')
    };
}

function selectTipo(tipo) {
    document.querySelectorAll('input[name="tipo_mantenimiento"]').forEach(r => {
        r.closest('.bento-card').classList.remove('selected');
    });
    document.querySelector('input[name="tipo_mantenimiento"][value="' + tipo + '"]').closest('.bento-card').classList.add('selected');
}

function selectProblema(el) {
    document.querySelectorAll('input[name="id_tipo_problema"]').forEach(r => {
        r.closest('.bento-card').classList.remove('selected');
    });
    el.classList.add('selected');
}

function selectPrioridad(el) {
    document.querySelectorAll('input[name="prioridad"]').forEach(r => {
        r.closest('.bento-card').classList.remove('selected');
    });
    el.classList.add('selected');
}

function previewEvidencia(input) {
    const preview = document.getElementById('evidenciaPreview');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const url = URL.createObjectURL(file);
        const div = document.createElement('div');
        div.className = 'preview-item';
        if (file.type.startsWith('video')) {
            div.innerHTML = `<video src="${url}" controls></video>`;
        } else {
            div.innerHTML = `<img src="${url}" alt="Evidencia">`;
        }
        preview.appendChild(div);
    }
}

function buildSummary() {
    document.getElementById('res-vehiculo').textContent = selectedVehicle ? selectedVehicle.placa + ' ' + selectedVehicle.info : '-';
    document.getElementById('res-conductor').textContent = selectedVehicle ? selectedVehicle.conductor.replace('Carnet: ', '') : '-';

    const tipo = document.querySelector('input[name="tipo_mantenimiento"]:checked')?.value || '-';
    document.getElementById('res-tipo').textContent = tipo;

    const problema = document.querySelector('input[name="id_tipo_problema"]:checked')?.closest('.bento-card')?.querySelector('.bento-title')?.textContent || '-';
    document.getElementById('res-problema').textContent = problema;

    const prioridadMap = {1:'Baja', 2:'Media', 3:'Alta', 4:'Crítica'};
    const prioridad = document.querySelector('input[name="prioridad"]:checked')?.value;
    document.getElementById('res-prioridad').textContent = prioridadMap[prioridad] || '-';

    document.getElementById('res-condicion').textContent = document.getElementById('condicion_movilidad').value;
    document.getElementById('res-ubicacion').textContent = document.getElementById('ubicacion').value || '-';
    document.getElementById('res-descripcion').textContent = document.getElementById('descripcion').value;

    const condicion = document.getElementById('condicion_movilidad').value;
    const inmovilizar = (parseInt(prioridad) === 4) || (tipo === 'EMERGENCIA') || (condicion === 'INMOVILIZADO');
    document.getElementById('avisoInmovilizacion').classList.toggle('d-none', !inmovilizar);
}

// Anti doble submit
document.getElementById('wizardForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('btn-enviar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
});

updateProgress();
</script>
<?= $this->endSection() ?>
