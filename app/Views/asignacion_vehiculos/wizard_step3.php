<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('asignacion-vehiculos') ?>">Asignación de Vehículos</a></li>
            <li class="breadcrumb-item active">Nueva Asignación</li>
        </ol>
    </nav>

    <!-- Wizard Progress -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="wizard-progress">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="wizard-step completed">
                                    <div class="wizard-step-icon">
                                        <i class="fas fa-check fa-2x text-success"></i>
                                    </div>
                                    <h6 class="mt-2 text-success">Paso 1</h6>
                                    <small class="text-success">Tipo de Unidad</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="wizard-step completed">
                                    <div class="wizard-step-icon">
                                        <i class="fas fa-check fa-2x text-success"></i>
                                    </div>
                                    <h6 class="mt-2 text-success">Paso 2</h6>
                                    <small class="text-success">Vehículo Seleccionado</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="wizard-step active">
                                    <div class="wizard-step-icon">
                                        <i class="fas fa-user fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="mt-2 text-primary">Paso 3</h6>
                                    <small class="text-primary">Asignar Conductor</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Selección -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Tipo de Unidad</h6>
                </div>
                <div class="card-body">
                    <h5><?= esc($tipo_unidad['descripcion']) ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-car me-2"></i>Vehículo Seleccionado</h6>
                </div>
                <div class="card-body">
                    <h5><?= esc($vehiculo['placa']) ?></h5>
                    <p class="mb-0"><?= esc($vehiculo['marca']) ?> <?= esc($vehiculo['modelo']) ?> (<?= esc($vehiculo['anio']) ?>)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de Asignación -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Completar Asignación
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Errores de validación:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('asignacion-vehiculos/store') ?>" method="post" id="formAsignacion">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_vehiculo" value="<?= $vehiculo_id ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_conductor" class="form-label">
                                        <i class="fas fa-user me-1"></i>Conductor *
                                    </label>
                                    <?php if (empty($conductores)): ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>No hay conductores disponibles</strong>
                                            <p class="mb-2">Todos los conductores están asignados o no hay conductores registrados.</p>
                                            <a href="<?= base_url('conductores/create') ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus me-1"></i>Registrar Conductor
                                            </a>
                                        </div>
                                        <select class="form-select" id="id_conductor" name="id_conductor" disabled>
                                            <option value="">No hay conductores disponibles</option>
                                        </select>
                                    <?php else: ?>
                                        <select class="form-select conductor-select <?= session()->getFlashdata('errors')['id_conductor'] ?? false ? 'is-invalid' : '' ?>" 
                                                id="id_conductor" name="id_conductor" required>
                                            <option value="">Buscar y seleccionar conductor...</option>
                                            <?php foreach ($conductores as $conductor): ?>
                                                <option value="<?= $conductor['id'] ?>" 
                                                        data-dni="<?= esc($conductor['dni']) ?>"
                                                        data-telefono="<?= esc($conductor['telefono'] ?? 'No registrado') ?>"
                                                        data-fecha="<?= esc($conductor['fechaIngreso']) ?>"
                                                        <?= old('id_conductor') == $conductor['id'] ? 'selected' : '' ?>>
                                                    <?= esc($conductor['nombre_completo']) ?> - DNI: <?= esc($conductor['dni']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                    <?php if (session()->getFlashdata('errors')['id_conductor'] ?? false): ?>
                                        <div class="invalid-feedback">
                                            <?= session()->getFlashdata('errors')['id_conductor'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_asignacion" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Fecha de Asignación *
                                    </label>
                                    <input type="date" 
                                           class="form-control <?= session()->getFlashdata('errors')['fecha_asignacion'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="fecha_asignacion" 
                                           name="fecha_asignacion" 
                                           value="<?= old('fecha_asignacion', date('Y-m-d')) ?>" 
                                           required>
                                    <?php if (session()->getFlashdata('errors')['fecha_asignacion'] ?? false): ?>
                                        <div class="invalid-feedback">
                                            <?= session()->getFlashdata('errors')['fecha_asignacion'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motivo_asignacion" class="form-label">
                                <i class="fas fa-comment me-1"></i>Motivo de la Asignación *
                            </label>
                            <textarea class="form-control <?= session()->getFlashdata('errors')['motivo_asignacion'] ?? false ? 'is-invalid' : '' ?>" 
                                      id="motivo_asignacion" 
                                      name="motivo_asignacion" 
                                      rows="3" 
                                      placeholder="Describe el motivo de esta asignación..." 
                                      required><?= old('motivo_asignacion') ?></textarea>
                            <div class="form-text">Mínimo 10 caracteres, máximo 500 caracteres</div>
                            <?php if (session()->getFlashdata('errors')['motivo_asignacion'] ?? false): ?>
                                <div class="invalid-feedback">
                                    <?= session()->getFlashdata('errors')['motivo_asignacion'] ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Información del Conductor Seleccionado -->
                        <div id="conductor-info" class="alert alert-info" style="display: none;">
                            <h6><i class="fas fa-info-circle me-2"></i>Información del Conductor</h6>
                            <div id="conductor-details"></div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="<?= base_url('asignacion-vehiculos/wizard/step2/' . $tipo_unidad_id) ?>" 
                               class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Volver al Paso 2
                            </a>
                            <a href="<?= base_url('asignacion-vehiculos') ?>" 
                               class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <?php if (!empty($conductores)): ?>
                                <button type="submit" class="btn btn-success" id="btnSubmit">
                                    <i class="fas fa-check me-2"></i>Completar Asignación
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
.wizard-progress {
    position: relative;
    padding: 20px 0;
}

.wizard-progress::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 20%;
    right: 20%;
    height: 3px;
    background: linear-gradient(to right, #198754 33%, #198754 66%, #0d6efd 66%, #0d6efd 100%);
    z-index: 1;
    border-radius: 2px;
}

.wizard-step {
    position: relative;
    z-index: 2;
}

.wizard-step-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    background-color: #f8f9fa;
    border: 3px solid #dee2e6;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.wizard-step.active .wizard-step-icon {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    border-color: #0d6efd;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    animation: pulse 2s infinite;
}

.wizard-step.active .wizard-step-icon i {
    color: white !important;
}

.wizard-step.completed .wizard-step-icon {
    background: linear-gradient(135deg, #198754, #146c43);
    border-color: #198754;
    color: white;
}

.wizard-step.completed .wizard-step-icon i {
    color: white !important;
}

.wizard-step h6 {
    font-weight: 600;
    margin-bottom: 5px;
}

.wizard-step small {
    font-size: 0.85rem;
    font-weight: 500;
}

.card-body {
    padding: 2rem;
}

@keyframes pulse {
    0% { transform: scale(1.1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1.1); }
}

.form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.alert {
    border-radius: 15px;
    border: none;
}

/* Estilos personalizados para Select2 */
.select2-container--bootstrap-5 .select2-selection--single {
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.select2-container--bootstrap-5 .select2-selection--single:focus-within {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.select2-container--bootstrap-5 .select2-selection__rendered {
    padding: 0;
    line-height: 1.5;
    color: #495057;
}

.select2-container--bootstrap-5 .select2-selection__placeholder {
    color: #6c757d;
}

.select2-dropdown {
    border-radius: 10px;
    border: 2px solid #0d6efd;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.select2-results__option {
    padding: 10px 15px;
    transition: all 0.2s ease;
}

.select2-results__option--highlighted {
    background-color: #0d6efd !important;
    color: white !important;
}

.select2-search__field {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 8px 12px;
}

.conductor-select.is-invalid + .select2-container .select2-selection {
    border-color: #dc3545;
}

.conductor-select.is-invalid + .select2-container .select2-selection:focus-within {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const conductorSelect = document.getElementById('id_conductor');
    const conductorInfo = document.getElementById('conductor-info');
    const conductorDetails = document.getElementById('conductor-details');
    const form = document.getElementById('formAsignacion');
    const btnSubmit = document.getElementById('btnSubmit');

    // Datos de conductores para mostrar información
    const conductoresData = <?= json_encode($conductores) ?>;

    // Función para inicializar Select2
    function initializeSelect2() {
        // Verificar que jQuery esté disponible
        if (typeof jQuery === 'undefined') {
            console.log('Esperando jQuery...');
            setTimeout(initializeSelect2, 100);
            return;
        }

        // Verificar si Select2 ya está cargado
        if (typeof jQuery.fn.select2 === 'undefined') {
            console.log('Cargando Select2...');
            // Cargar Select2 dinámicamente
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
            script.onload = function() {
                console.log('Select2 cargado exitosamente');
                initializeSelect2Widget();
            };
            script.onerror = function() {
                console.error('Error al cargar Select2');
            };
            document.head.appendChild(script);
            return;
        }

        // Si Select2 ya está disponible, inicializar directamente
        initializeSelect2Widget();
    }

    // Función para inicializar el widget Select2
    function initializeSelect2Widget() {

        // Inicializar Select2 si hay conductores disponibles
        if (conductorSelect && !conductorSelect.disabled) {
        $('#id_conductor').select2({
            theme: 'bootstrap-5',
            placeholder: 'Buscar conductor por nombre o DNI...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return 'No se encontraron conductores';
                },
                searching: function() {
                    return 'Buscando...';
                },
                inputTooShort: function() {
                    return 'Escriba para buscar conductores';
                }
            },
            templateResult: function(option) {
                if (!option.id) {
                    return option.text;
                }
                
                const $option = $(option.element);
                const dni = $option.data('dni');
                const telefono = $option.data('telefono');
                
                return $(`
                    <div class="conductor-option">
                        <div class="fw-bold">${option.text.split(' - DNI:')[0]}</div>
                        <small class="text-muted">DNI: ${dni} | Tel: ${telefono}</small>
                    </div>
                `);
            },
            templateSelection: function(option) {
                if (!option.id) {
                    return option.text;
                }
                return option.text.split(' - DNI:')[0];
            }
        });

        // Event listener para Select2
        $('#id_conductor').on('select2:select', function(e) {
            const conductorId = e.params.data.id;
            mostrarInfoConductor(conductorId);
        });

        $('#id_conductor').on('select2:clear', function() {
            conductorInfo.style.display = 'none';
        });
        }
    }

    // Llamar a la función para inicializar Select2
    initializeSelect2();

    // Función para mostrar información del conductor
    function mostrarInfoConductor(conductorId) {
        if (conductorId) {
            const conductor = conductoresData.find(c => c.id == conductorId);
            if (conductor) {
                conductorDetails.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nombre:</strong> ${conductor.nombre_completo}<br>
                            <strong>DNI:</strong> ${conductor.dni}
                        </div>
                        <div class="col-md-6">
                            <strong>Teléfono:</strong> ${conductor.telefono || 'No registrado'}<br>
                            <strong>Fecha de Ingreso:</strong> ${new Date(conductor.fechaIngreso).toLocaleDateString()}
                        </div>
                    </div>
                `;
                conductorInfo.style.display = 'block';
            }
        } else {
            conductorInfo.style.display = 'none';
        }
    }

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validaciones básicas
        const conductor = conductorSelect.value;
        const fecha = document.getElementById('fecha_asignacion').value;
        const motivo = document.getElementById('motivo_asignacion').value;

        if (!conductor) {
            alert('Debe seleccionar un conductor');
            conductorSelect.focus();
            return;
        }

        if (!fecha) {
            alert('Debe seleccionar una fecha de asignación');
            document.getElementById('fecha_asignacion').focus();
            return;
        }

        if (motivo.length < 10) {
            alert('El motivo debe tener al menos 10 caracteres');
            document.getElementById('motivo_asignacion').focus();
            return;
        }

        // Confirmación
        if (confirm('¿Está seguro de realizar esta asignación? Esta acción cambiará el estado del vehículo a NO DISPONIBLE.')) {
            // Mostrar loading
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
            btnSubmit.disabled = true;
            
            // Enviar formulario
            this.submit();
        }
    });

    // Contador de caracteres para el motivo
    const motivoTextarea = document.getElementById('motivo_asignacion');
    const motivoHelp = motivoTextarea.nextElementSibling;
    
    motivoTextarea.addEventListener('input', function() {
        const length = this.value.length;
        motivoHelp.textContent = `${length}/500 caracteres (mínimo 10)`;
        
        if (length < 10) {
            motivoHelp.className = 'form-text text-danger';
        } else if (length > 500) {
            motivoHelp.className = 'form-text text-danger';
        } else {
            motivoHelp.className = 'form-text text-success';
        }
    });
});
</script>

<?= $this->endSection() ?>
