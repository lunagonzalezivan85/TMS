<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
    .wizard-card { max-width: 900px; margin: 0 auto; border-radius: 24px; border: none; }
    .progress-stepper { display: flex; justify-content: space-between; position: relative; margin-bottom: 2rem; }
    .progress-stepper::before {
        content: ''; position: absolute; top: 18px; left: 0; right: 0; height: 4px;
        background: #e2e8f0; z-index: 0;
    }
    .step-node {
        display: flex; flex-direction: column; align-items: center; gap: 0.5rem;
        position: relative; z-index: 1; cursor: default;
    }
    .step-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #fff; border: 2px solid #e2e8f0; color: #94a3b8;
        font-weight: 700; transition: all 0.3s;
    }
    .step-node.active .step-circle { background: #4f46e5; border-color: #4f46e5; color: #fff; }
    .step-node.completed .step-circle { background: #4f46e5; border-color: #4f46e5; color: #fff; }
    .step-label { font-size: 0.75rem; font-weight: 600; color: #64748b; }
    .step-node.active .step-label { color: #4f46e5; }
    .wizard-step { display: none; animation: fadeIn 0.25s ease; }
    .wizard-step.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .bento-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; }
    .bento-card {
        border: 2px solid #e2e8f0; border-radius: 16px; padding: 1rem;
        cursor: pointer; text-align: center; transition: all 0.15s; background: #fff;
    }
    .bento-card:hover { border-color: #4f46e5; transform: translateY(-2px); box-shadow: 0 8px 16px rgba(0,0,0,0.05); }
    .bento-card.selected { border-color: #4f46e5; background: #eef2ff; }
    .bento-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; background: #eef2ff; color: #4f46e5; font-size: 1.25rem; }
    .summary-card { background: #f8fafc; border-radius: 16px; padding: 1.25rem; }
    .summary-row { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0; }
    .summary-row:last-child { border-bottom: none; }
    .summary-label { color: #64748b; font-size: 0.9rem; }
    .summary-value { font-weight: 600; color: #0f172a; text-align: right; }
    .nav-btns { display: flex; justify-content: space-between; margin-top: 1.5rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-car me-2 text-primary"></i>Agregar Vehículo</h4>
        <a href="<?= base_url('vehiculos') ?>" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i>Volver</a>
    </div>

    <div class="card wizard-card shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="progress-stepper">
                <div class="step-node active" data-step="1"><div class="step-circle">1</div><span class="step-label">Básicos</span></div>
                <div class="step-node" data-step="2"><div class="step-circle">2</div><span class="step-label">Identificación</span></div>
                <div class="step-node" data-step="3"><div class="step-circle">3</div><span class="step-label">Configuración</span></div>
                <div class="step-node" data-step="4"><div class="step-circle">4</div><span class="step-label">Confirmar</span></div>
            </div>

            <form id="wizardForm" action="<?= base_url('vehiculos/store') ?>" method="POST">
                <?= csrf_field() ?>

                <!-- PASO 1: Datos básicos -->
                <div class="wizard-step active" data-step="1">
                    <h5 class="fw-bold mb-1">Datos básicos del vehículo</h5>
                    <p class="text-muted mb-4">Información principal de identificación.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="placa" class="form-label fw-semibold">Placa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-4 py-3" id="placa" name="placa" value="<?= old('placa') ?>" placeholder="Ej: ABC-123" maxlength="20" required>
                        </div>
                        <div class="col-md-6">
                            <label for="codigo_unidad" class="form-label fw-semibold">Código de unidad</label>
                            <input type="text" class="form-control rounded-4 py-3" id="codigo_unidad" name="codigo_unidad" value="<?= old('codigo_unidad') ?>" placeholder="Ej: UNIDAD-001" maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label for="marca" class="form-label fw-semibold">Marca <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-4 py-3" id="marca" name="marca" value="<?= old('marca') ?>" placeholder="Ej: Toyota" maxlength="50" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modelo" class="form-label fw-semibold">Modelo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-4 py-3" id="modelo" name="modelo" value="<?= old('modelo') ?>" placeholder="Ej: Corolla" maxlength="50" required>
                        </div>
                        <div class="col-md-6">
                            <label for="anio" class="form-label fw-semibold">Año <span class="text-danger">*</span></label>
                            <input type="number" class="form-control rounded-4 py-3" id="anio" name="anio" value="<?= old('anio') ?>" min="1900" max="<?= date('Y') ?>" placeholder="<?= date('Y') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kilometraje" class="form-label fw-semibold">Kilometraje</label>
                            <div class="input-group">
                                <input type="number" class="form-control rounded-start-4 py-3" id="kilometraje" name="kilometraje" value="<?= old('kilometraje', '0') ?>" min="0" placeholder="0">
                                <span class="input-group-text rounded-end-4">km</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="id_color" class="form-label fw-semibold">Color</label>
                            <select class="form-select rounded-4 py-3" id="id_color" name="id_color">
                                <option value="">Seleccionar color</option>
                                <?php foreach ($colores as $colorId => $colorNombre): ?>
                                    <option value="<?= $colorId ?>" <?= old('id_color') == $colorId ? 'selected' : '' ?>><?= esc($colorNombre) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- PASO 2: Identificación -->
                <div class="wizard-step" data-step="2">
                    <h5 class="fw-bold mb-1">Identificación técnica</h5>
                    <p class="text-muted mb-4">Números de motor, chasis y asignación.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="numero_motor" class="form-label fw-semibold">Número de motor</label>
                            <input type="text" class="form-control rounded-4 py-3" id="numero_motor" name="numero_motor" value="<?= old('numero_motor') ?>" placeholder="Ingrese el número de motor" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label for="numero_chasis" class="form-label fw-semibold">Número de chasis</label>
                            <input type="text" class="form-control rounded-4 py-3" id="numero_chasis" name="numero_chasis" value="<?= old('numero_chasis') ?>" placeholder="Ingrese el número de chasis" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label for="codigo_centro_costo" class="form-label fw-semibold">Centro de costo <span class="text-danger">*</span></label>
                            <select class="form-select rounded-4 py-3" id="codigo_centro_costo" name="codigo_centro_costo" required>
                                <option value="">Seleccionar centro de costo</option>
                                <?php foreach ($centrosCosto as $codigo => $descripcion): ?>
                                    <option value="<?= $codigo ?>" <?= old('codigo_centro_costo') == $codigo ? 'selected' : '' ?>><?= esc($descripcion) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- PASO 3: Configuración -->
                <div class="wizard-step" data-step="3">
                    <h5 class="fw-bold mb-1">Configuración y operación</h5>
                    <p class="text-muted mb-4">Tipo de unidad, operación, consumo y conductor.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="idTipoUnidad" class="form-label fw-semibold">Tipo de unidad</label>
                            <select class="form-select rounded-4 py-3" id="idTipoUnidad" name="idTipoUnidad">
                                <option value="">Seleccionar tipo de unidad</option>
                                <?php foreach ($tiposUnidad as $tipoId => $tipoNombre): ?>
                                    <option value="<?= $tipoId ?>" <?= old('idTipoUnidad') == $tipoId ? 'selected' : '' ?>><?= esc($tipoNombre) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="idTipoOperacion" class="form-label fw-semibold">Tipo de operación</label>
                            <select class="form-select rounded-4 py-3" id="idTipoOperacion" name="idTipoOperacion">
                                <option value="">Seleccionar tipo de operación</option>
                                <?php foreach ($tiposOperacion as $tipoId => $tipoNombre): ?>
                                    <option value="<?= $tipoId ?>" <?= old('idTipoOperacion') == $tipoId ? 'selected' : '' ?>><?= esc($tipoNombre) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_tipo_vehiculo" class="form-label fw-semibold">Tipo de motor</label>
                            <select class="form-select rounded-4 py-3" id="id_tipo_vehiculo" name="id_tipo_vehiculo">
                                <option value="">Seleccionar tipo de motor</option>
                                <?php foreach ($tiposVehiculo as $tipoId => $tipoNombre): ?>
                                    <option value="<?= $tipoId ?>" <?= old('id_tipo_vehiculo') == $tipoId ? 'selected' : '' ?>><?= esc($tipoNombre) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_tipo_producto" class="form-label fw-semibold">Tipo de producto</label>
                            <select class="form-select rounded-4 py-3" id="id_tipo_producto" name="id_tipo_producto">
                                <option value="">Seleccionar tipo de producto</option>
                                <?php foreach ($tipoProducto as $tipoId => $tipoNombre): ?>
                                    <option value="<?= $tipoId ?>" <?= old('id_tipo_producto') == $tipoId ? 'selected' : '' ?>><?= esc($tipoNombre) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="rendimiento" class="form-label fw-semibold">Rendimiento</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control rounded-start-4 py-3" id="rendimiento" name="rendimiento" value="<?= old('rendimiento') ?>" placeholder="0.00">
                                <span class="input-group-text rounded-end-4">km/L</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="max_combustible" class="form-label fw-semibold">Capacidad máxima de combustible</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control rounded-start-4 py-3" id="max_combustible" name="max_combustible" value="<?= old('max_combustible') ?>" placeholder="0.00">
                                <span class="input-group-text rounded-end-4">L</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_consumo" class="form-label fw-semibold">Tipo de consumo</label>
                            <select class="form-select rounded-4 py-3" id="tipo_consumo" name="tipo_consumo">
                                <option value="">Seleccionar tipo</option>
                                <?php foreach ($tiposConsumo as $ref => $nombre): ?>
                                    <option value="<?= esc($ref) ?>" <?= old('tipo_consumo') == $ref ? 'selected' : '' ?>><?= esc($nombre) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_conductor" class="form-label fw-semibold">Conductor asignado</label>
                            <select class="form-select rounded-4 py-3" id="id_conductor" name="id_conductor">
                                <option value="">Sin asignar</option>
                                <?php foreach ($conductores as $conductor): ?>
                                    <option value="<?= $conductor['id'] ?>" <?= old('id_conductor') == $conductor['id'] ? 'selected' : '' ?>><?= esc($conductor['nombre_completo']) ?> - <?= esc($conductor['dni']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="disponible" class="form-label fw-semibold">Disponibilidad</label>
                            <select class="form-select rounded-4 py-3" id="disponible" name="disponible">
                                <option value="1" <?= old('disponible', '1') == '1' ? 'selected' : '' ?>>Disponible</option>
                                <option value="0" <?= old('disponible') === '0' ? 'selected' : '' ?>>No disponible</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check mt-md-4">
                                <input class="form-check-input" type="checkbox" id="compuesto" name="compuesto" value="1" <?= old('compuesto') ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="compuesto">Vehículo compuesto (hasta 2 conductores)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PASO 4: Confirmación -->
                <div class="wizard-step" data-step="4">
                    <h5 class="fw-bold mb-1">Confirmar y guardar</h5>
                    <p class="text-muted mb-4">Revisa la información antes de crear el vehículo.</p>
                    <div class="summary-card">
                        <div class="summary-row"><span class="summary-label">Placa</span><span class="summary-value" id="res-placa">-</span></div>
                        <div class="summary-row"><span class="summary-label">Marca / Modelo</span><span class="summary-value" id="res-marca-modelo">-</span></div>
                        <div class="summary-row"><span class="summary-label">Año</span><span class="summary-value" id="res-anio">-</span></div>
                        <div class="summary-row"><span class="summary-label">Color</span><span class="summary-value" id="res-color">-</span></div>
                        <div class="summary-row"><span class="summary-label">Kilometraje</span><span class="summary-value" id="res-kilometraje">-</span></div>
                        <div class="summary-row"><span class="summary-label">N.º Motor</span><span class="summary-value" id="res-motor">-</span></div>
                        <div class="summary-row"><span class="summary-label">N.º Chasis</span><span class="summary-value" id="res-chasis">-</span></div>
                        <div class="summary-row"><span class="summary-label">Centro de costo</span><span class="summary-value" id="res-centro">-</span></div>
                        <div class="summary-row"><span class="summary-label">Tipo de unidad</span><span class="summary-value" id="res-tipo-unidad">-</span></div>
                        <div class="summary-row"><span class="summary-label">Tipo de operación</span><span class="summary-value" id="res-tipo-operacion">-</span></div>
                        <div class="summary-row"><span class="summary-label">Conductor</span><span class="summary-value" id="res-conductor">-</span></div>
                        <div class="summary-row"><span class="summary-label">Disponibilidad</span><span class="summary-value" id="res-disponible">-</span></div>
                    </div>
                    <div class="alert alert-info rounded-4 d-flex align-items-center mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>El vehículo será creado con estado <strong>ACTIVO</strong> por defecto.</span>
                    </div>
                </div>

                <div class="nav-btns">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="btn-atras" onclick="prevStep()" style="display:none;"><i class="fas fa-arrow-left me-2"></i>Atrás</button>
                    <div></div>
                    <button type="button" class="btn btn-primary rounded-pill px-5" id="btn-siguiente" onclick="nextStep()">Siguiente<i class="fas fa-arrow-right ms-2"></i></button>
                    <button type="submit" class="btn btn-success rounded-pill px-5" id="btn-guardar" style="display:none;"><i class="fas fa-save me-2"></i>Guardar Vehículo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentStep = 1;
const totalSteps = 4;

function updateStepper() {
    document.querySelectorAll('.step-node').forEach(node => {
        const step = parseInt(node.dataset.step);
        node.classList.remove('active', 'completed');
        if (step < currentStep) node.classList.add('completed');
        if (step === currentStep) node.classList.add('active');
    });

    document.querySelectorAll('.wizard-step').forEach(el => {
        el.classList.remove('active');
        if (parseInt(el.dataset.step) === currentStep) el.classList.add('active');
    });

    document.getElementById('btn-atras').style.display = currentStep === 1 ? 'none' : 'inline-block';
    document.getElementById('btn-siguiente').style.display = currentStep === totalSteps ? 'none' : 'inline-block';
    document.getElementById('btn-guardar').style.display = currentStep === totalSteps ? 'inline-block' : 'none';

    if (currentStep === totalSteps) buildSummary();
}

function nextStep() {
    if (!validateStep(currentStep)) return;
    if (currentStep < totalSteps) {
        currentStep++;
        updateStepper();
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepper();
    }
}

function validateStep(step) {
    const container = document.querySelector('.wizard-step[data-step="' + step + '"]');
    const required = container.querySelectorAll('[required]');
    let valid = true;
    required.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            valid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    if (step === 1) {
        const anio = parseInt(document.getElementById('anio').value);
        const anioActual = new Date().getFullYear();
        if (anio && (anio < 1900 || anio > anioActual)) {
            document.getElementById('anio').classList.add('is-invalid');
            valid = false;
        }
    }
    return valid;
}

function textOf(selectId) {
    const el = document.getElementById(selectId);
    return el.options[el.selectedIndex].text;
}

function buildSummary() {
    document.getElementById('res-placa').textContent = document.getElementById('placa').value.toUpperCase();
    document.getElementById('res-marca-modelo').textContent = document.getElementById('marca').value + ' ' + document.getElementById('modelo').value;
    document.getElementById('res-anio').textContent = document.getElementById('anio').value;
    document.getElementById('res-color').textContent = textOf('id_color');
    document.getElementById('res-kilometraje').textContent = document.getElementById('kilometraje').value + ' km';
    document.getElementById('res-motor').textContent = document.getElementById('numero_motor').value || 'No ingresado';
    document.getElementById('res-chasis').textContent = document.getElementById('numero_chasis').value || 'No ingresado';
    document.getElementById('res-centro').textContent = textOf('codigo_centro_costo');
    document.getElementById('res-tipo-unidad').textContent = textOf('idTipoUnidad');
    document.getElementById('res-tipo-operacion').textContent = textOf('idTipoOperacion');
    document.getElementById('res-conductor').textContent = textOf('id_conductor');
    document.getElementById('res-disponible').textContent = document.getElementById('disponible').value === '1' ? 'Disponible' : 'No disponible';
}

// Formatear placa en mayúsculas
document.getElementById('placa').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

// Marca y modelo capitalizados
document.getElementById('marca').addEventListener('input', function() {
    const v = this.value;
    this.value = v ? v.charAt(0).toUpperCase() + v.slice(1).toLowerCase() : '';
});
document.getElementById('modelo').addEventListener('input', function() {
    const v = this.value;
    this.value = v ? v.charAt(0).toUpperCase() + v.slice(1).toLowerCase() : '';
});

// AJAX submit igual que el formulario anterior
$(document).ready(function() {
    $('#wizardForm').on('submit', function(e) {
        e.preventDefault();
        if (!validateStep(currentStep)) return;

        const btn = $('#btn-guardar');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Guardado', text: response.message, timer: 2000, showConfirmButton: false })
                        .then(() => window.location.href = '<?= base_url('vehiculos') ?>');
                } else {
                    if (response.errors) mostrarErrores(response.errors);
                    else Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'Error al crear el vehículo' });
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar Vehículo');
                }
            },
            error: function(xhr) {
                let mensaje = 'Error de conexión';
                if (xhr.responseJSON && xhr.responseJSON.message) mensaje = xhr.responseJSON.message;
                Swal.fire({ icon: 'error', title: 'Error', text: mensaje });
                btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar Vehículo');
            }
        });
    });

    function mostrarErrores(errores) {
        $.each(errores, function(campo, mensaje) {
            $('#' + campo).addClass('is-invalid');
            $('#error_' + campo).text(mensaje);
        });
    }

    $('#placa').on('blur', function() {
        const placa = $(this).val().trim();
        if (placa.length >= 6) {
            $.ajax({
                url: '<?= base_url('vehiculos/verificarPlaca') ?>',
                type: 'POST',
                data: { placa: placa },
                dataType: 'json',
                success: function(response) {
                    if (!response.disponible) {
                        $('#placa').addClass('is-invalid');
                        $('#error_placa').text('Esta placa ya está registrada');
                    } else {
                        $('#placa').removeClass('is-invalid');
                        $('#error_placa').text('');
                    }
                }
            });
        }
    });
});

updateStepper();
</script>
<?= $this->endSection() ?>
