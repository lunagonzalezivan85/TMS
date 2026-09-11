<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    Nueva Solicitud de Mantenimiento - GMV
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">Solicitud de Mantenimiento</h4>
                    <p class="text-muted mb-0">Complete el formulario paso a paso para registrar una nueva solicitud</p>
                </div>
                <div class="card-body p-4">
                    <!-- Barra de progreso -->
                    <div class="mb-5">
                        <div class="progress" style="height: 8px;">
                            <div id="progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%;" 
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="text-center">
                                <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 30px; height: 30px;">1</div>
                                <div class="small mt-1">Datos del Conductor</div>
                            </div>
                            <div class="text-center">
                                <div class="step-number bg-light border rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 30px; height: 30px;">2</div>
                                <div class="small mt-1 text-muted">Tipo de Mantenimiento</div>
                            </div>
                            <div class="text-center">
                                <div class="step-number bg-light border rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 30px; height: 30px;">3</div>
                                <div class="small mt-1 text-muted">Descripción</div>
                            </div>
                            <div class="text-center">
                                <div class="step-number bg-light border rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 30px; height: 30px;">4</div>
                                <div class="small mt-1 text-muted">Confirmación</div>
                            </div>
                        </div>
                    </div>

                    <form id="formSolicitud" method="post" action="<?= base_url('solicitudes/store') ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <!-- Paso 1: Datos del Conductor -->
                        <div class="step-content active" id="step-1">
                            <!-- Contenido del paso 1 -->
                            <div class="text-center mb-5">
                                <div class="bg-primary bg-opacity-10 d-inline-flex p-3 rounded-circle mb-4">
                                    <i class="fas fa-user-tie fa-2x text-primary"></i>
                                </div>
                                <h3 class="h4 mb-3">Datos del Conductor</h3>
                                <p class="text-muted">Ingrese el número de carnet del conductor</p>
                            </div>
                            
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="input-group input-group-lg mb-4">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-id-card text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg" id="numero_carnet" 
                                               name="numero_carnet" placeholder="Ej: 12345678" required>
                                        <button class="btn btn-primary" type="button" id="buscarConductorBtn">
                                            <i class="fas fa-search me-2"></i> Buscar
                                        </button>
                                    </div>
                                    
                                    <!-- Información del conductor -->
                                    <div id="conductor-info" class="card border-start border-4 border-primary mb-4 d-none">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                                    <i class="fas fa-user-tie fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0" id="nombre-conductor">-</h5>
                                                    <span class="text-muted" id="carnet-conductor">-</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center bg-light p-3 rounded">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="fas fa-car text-primary"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-bold" id="vehiculo-info">-</p>
                                                    <p class="mb-0 small text-muted">
                                                        <span id="vehiculo-placa">-</span> • 
                                                        <span id="vehiculo-tipo">-</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="id_conductor" name="id_conductor">
                                        <input type="hidden" id="id_vehiculo" name="id_vehiculo">
                                    </div>
                                    
                                    <div class="d-grid gap-2 mt-4">
                                        <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(1)" id="btn-siguiente-1" disabled>
                                            Siguiente <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 2: Tipo de Mantenimiento -->
                        <div class="step-content d-none" id="step-2">
                            <div class="text-center mb-5">
                                <div class="bg-primary bg-opacity-10 d-inline-flex p-3 rounded-circle mb-4">
                                    <i class="fas fa-tools fa-2x text-primary"></i>
                                </div>
                                <h3 class="h4 mb-3">Tipo de Mantenimiento</h3>
                                <p class="text-muted">Seleccione el tipo de mantenimiento que desea solicitar</p>
                            </div>
                            
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="d-grid gap-3">
                                        <!-- Opción 1: Preventivo -->
                                        <input type="radio" class="btn-check" name="tipo_mantenimiento" id="preventivo" value="PREVENTIVO" autocomplete="off" required>
                                        <label class="btn btn-outline-primary btn-lg p-4 text-start" for="preventivo">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-4">
                                                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1">Mantenimiento Preventivo</h5>
                                                    <p class="mb-0 text-muted small">Mantenimiento programado para prevenir fallos futuros</p>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <!-- Opción 2: Correctivo -->
                                        <input type="radio" class="btn-check" name="tipo_mantenimiento" id="correctivo" value="CORRECTIVO" autocomplete="off">
                                        <label class="btn btn-outline-primary btn-lg p-4 text-start" for="correctivo">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-4">
                                                    <i class="fas fa-wrench fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1">Mantenimiento Correctivo</h5>
                                                    <p class="mb-0 text-muted small">Reparación de fallas existentes en el vehículo</p>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <!-- Opción 3: Emergencia -->
                                        <input type="radio" class="btn-check" name="tipo_mantenimiento" id="emergencia" value="EMERGENCIA" autocomplete="off">
                                        <label class="btn btn-outline-danger btn-lg p-4 text-start" for="emergencia">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-4">
                                                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1">Emergencia</h5>
                                                    <p class="mb-0 text-muted small">Falla crítica que impide el uso del vehículo</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <!-- Navegación -->
                                    <div class="d-flex justify-content-between mt-5">
                                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                            <i class="fas fa-arrow-left me-2"></i> Regresar
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep(2)" id="btn-siguiente-2" disabled>
                                            Siguiente <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-content d-none" id="step-3">
                            <!-- Contenido del paso 3 -->
                            <p>Paso 3 - Descripción del Problema</p>
                        </div>

                        <div class="step-content d-none" id="step-4">
                            <!-- Contenido del paso 4 -->
                            <p>Paso 4 - Confirmación</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Variables globales
let currentStep = 1;
const totalSteps = 4;

// Inicializar el wizard
document.addEventListener('DOMContentLoaded', function() {
    updateProgressBar();
    setupEventListeners();
});

// Configurar event listeners
function setupEventListeners() {
    // Buscar conductor
    document.getElementById('buscarConductorBtn').addEventListener('click', buscarConductor);
    document.getElementById('numero_carnet').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarConductor();
        }
    });

    // Habilitar botón siguiente cuando se seleccione un tipo de mantenimiento
    const tiposMantenimiento = document.querySelectorAll('input[name="tipo_mantenimiento"]');
    tiposMantenimiento.forEach(tipo => {
        tipo.addEventListener('change', function() {
            document.getElementById('btn-siguiente-2').disabled = false;
        });
    });
}

// Navegación entre pasos
function nextStep(step) {
    if (validateStep(step)) {
        document.getElementById(`step-${step}`).classList.remove('active');
        document.getElementById(`step-${step}`).classList.add('d-none');
        currentStep = step + 1;
        document.getElementById(`step-${currentStep}`).classList.remove('d-none');
        document.getElementById(`step-${currentStep}`).classList.add('active');
        updateProgressBar();
    }
}

function prevStep(step) {
    document.getElementById(`step-${step}`).classList.remove('active');
    document.getElementById(`step-${step}`).classList.add('d-none');
    currentStep = step - 1;
    document.getElementById(`step-${currentStep}`).classList.remove('d-none');
    document.getElementById(`step-${currentStep}`).classList.add('active');
    updateProgressBar();
}

// Actualizar barra de progreso
function updateProgressBar() {
    const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
    document.getElementById('progress-bar').style.width = `${progress}%`;
    
    // Actualizar pasos
    document.querySelectorAll('.step-number').forEach((el, index) => {
        if (index + 1 < currentStep) {
            el.classList.remove('bg-light', 'border');
            el.classList.add('bg-primary', 'text-white');
        } else if (index + 1 === currentStep) {
            el.classList.remove('bg-light', 'border');
            el.classList.add('bg-primary', 'text-white');
        } else {
            el.classList.remove('bg-primary', 'text-white');
            el.classList.add('bg-light', 'border');
        }
    });
}

// Validar paso actual
function validateStep(step) {
    let isValid = true;
    
    // Validaciones específicas por paso
    if (step === 1) {
        const conductorInfo = document.getElementById('conductor-info');
        if (conductorInfo.classList.contains('d-none')) {
            alert('Debe buscar y seleccionar un conductor');
            isValid = false;
        }
    } else if (step === 2) {
        const tipoSeleccionado = document.querySelector('input[name="tipo_mantenimiento"]:checked');
        if (!tipoSeleccionado) {
            alert('Por favor seleccione un tipo de mantenimiento');
            isValid = false;
        } else {
            // Habilitar el botón de siguiente
            document.getElementById('btn-siguiente-2').disabled = false;
        }
    }
    
    return isValid;
}

// Buscar conductor
function buscarConductor() {
    const carnet = document.getElementById('numero_carnet').value.trim();
    
    if (!carnet) {
        alert('Por favor ingrese un número de carnet');
        return;
    }
    
    // Aquí iría la llamada AJAX para buscar el conductor
    // Por ahora simulamos una respuesta exitosa
    setTimeout(() => {
        const conductorInfo = document.getElementById('conductor-info');
        document.getElementById('nombre-conductor').textContent = 'Juan Pérez Martínez';
        document.getElementById('carnet-conductor').textContent = carnet;
        document.getElementById('vehiculo-info').textContent = 'Toyota Hilux 2022';
        document.getElementById('vehiculo-placa').textContent = 'ABC-123';
        document.getElementById('vehiculo-tipo').textContent = 'Camioneta';
        document.getElementById('id_conductor').value = '123';
        document.getElementById('id_vehiculo').value = '456';
        
        conductorInfo.classList.remove('d-none');
        document.getElementById('btn-siguiente-1').disabled = false;
        
        alert('Conductor encontrado correctamente');
    }, 1000);
}
</script>
<?= $this->endSection() ?>
