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
                                <div class="wizard-step active">
                                    <div class="wizard-step-icon">
                                        <i class="fas fa-car fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="mt-2 text-primary">Paso 2</h6>
                                    <small class="text-primary">Seleccionar Vehículo</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="wizard-step">
                                    <div class="wizard-step-icon">
                                        <i class="fas fa-user fa-2x text-muted"></i>
                                    </div>
                                    <h6 class="mt-2 text-muted">Paso 3</h6>
                                    <small class="text-muted">Asignar Conductor</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tipo de Unidad Seleccionado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Tipo de Unidad Seleccionado:</strong> <?= esc($tipo_unidad['descripcion']) ?>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-car me-2"></i>
                        Vehículos Disponibles
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

                    <?php if (empty($vehiculos)): ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-car fa-3x mb-3 text-warning"></i>
                            <h5>No hay vehículos disponibles</h5>
                            <p>No se encontraron vehículos disponibles para el tipo de unidad <strong><?= esc($tipo_unidad['descripcion']) ?></strong>.</p>
                            <p class="text-muted">Los vehículos deben estar en estado ACTIVO y disponibilidad DISPONIBLE.</p>
                            <div class="mt-3">
                                <a href="<?= base_url('asignacion-vehiculos/wizard') ?>" class="btn btn-secondary me-2">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al Paso 1
                                </a>
                                <a href="<?= base_url('vehiculos/create') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Registrar Vehículo
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center mb-4">
                            <h4>Selecciona el vehículo a asignar</h4>
                            <p class="text-muted">Se encontraron <?= count($vehiculos) ?> vehículo(s) disponible(s)</p>
                        </div>

                        <div class="row">
                            <?php foreach ($vehiculos as $vehiculo): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card vehiculo-card h-100 <?= empty($vehiculo['tiene_documentacion']) ? 'border-danger' : '' ?>" style="cursor: pointer;" 
                                         onclick="seleccionarVehiculo(<?= $vehiculo['id'] ?>)">
                                        <div class="card-body">
                                            <?php if (empty($vehiculo['tiene_documentacion'])): ?>
                                            <div class="alert alert-danger py-2 mb-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <small><strong>Este vehículo no tiene documentación</strong></small>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <div class="vehiculo-header text-center mb-3">
                                                <div class="vehiculo-icon mb-2">
                                                    <i class="fas fa-car fa-3x text-primary"></i>
                                                </div>
                                                <h5 class="card-title text-primary"><?= esc($vehiculo['placa']) ?></h5>
                                            </div>
                                            
                                            <div class="vehiculo-details">
                                                <div class="row mb-2">
                                                    <div class="col-5"><strong>Marca:</strong></div>
                                                    <div class="col-7"><?= esc($vehiculo['marca']) ?></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-5"><strong>Modelo:</strong></div>
                                                    <div class="col-7"><?= esc($vehiculo['modelo']) ?></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-5"><strong>Año:</strong></div>
                                                    <div class="col-7"><?= esc($vehiculo['anio']) ?></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-5"><strong>Kilometraje:</strong></div>
                                                    <div class="col-7"><?= number_format($vehiculo['kilometraje']) ?> km</div>
                                                </div>
                                                <?php if ($vehiculo['tipo_operacion_descripcion']): ?>
                                                <div class="row mb-2">
                                                    <div class="col-5"><strong>Operación:</strong></div>
                                                    <div class="col-7">
                                                        <span class="badge bg-info"><?= esc($vehiculo['tipo_operacion_descripcion']) ?></span>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent text-center">
                                            <span class="badge bg-success">DISPONIBLE</span>
                                            <?php if (!empty($vehiculo['tiene_documentacion'])): ?>
                                            <span class="badge bg-primary ms-2">
                                                <i class="fas fa-file-alt me-1"></i>Con documentos
                                            </span>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <small class="text-muted">Click para seleccionar</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <a href="<?= base_url('asignacion-vehiculos/wizard') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Paso 1
                        </a>
                        <a href="<?= base_url('asignacion-vehiculos') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    background: linear-gradient(to right, #198754 33%, #0d6efd 33%, #0d6efd 66%, #dee2e6 66%, #dee2e6 100%);
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

.vehiculo-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
    border-radius: 15px;
    overflow: hidden;
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
}

.vehiculo-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    border-color: #0d6efd;
    background: linear-gradient(135deg, #ffffff, #f0f8ff);
}

.vehiculo-icon {
    transition: all 0.3s ease;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    background: linear-gradient(135deg, #e8f5e8, #c3e6cb);
    border: 3px solid #d4edda;
}

.vehiculo-card:hover .vehiculo-icon {
    transform: scale(1.15) rotate(5deg);
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    border-color: #0d6efd;
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
}

.vehiculo-card:hover .vehiculo-icon i {
    color: white !important;
    transform: scale(1.1);
}

.vehiculo-details {
    font-size: 0.9rem;
}

.card-body {
    padding: 2rem;
}

@keyframes pulse {
    0% { transform: scale(1.1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1.1); }
}
</style>

<script>
function seleccionarVehiculo(vehiculoId) {
    // Agregar efecto de loading
    const card = event.currentTarget;
    const originalContent = card.innerHTML;
    
    card.innerHTML = `
        <div class="card-body text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando conductores...</p>
        </div>
    `;
    
    // Redirigir al paso 3
    setTimeout(() => {
        window.location.href = `<?= base_url('asignacion-vehiculos/wizard/step3/' . $tipo_unidad_id . '/') ?>${vehiculoId}`;
    }, 500);
}

// Animación de entrada para las cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.vehiculo-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
<?= $this->endSection() ?>
