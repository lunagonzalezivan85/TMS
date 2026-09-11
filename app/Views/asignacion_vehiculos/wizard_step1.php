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
                                <div class="wizard-step active">
                                    <div class="wizard-step-icon">
                                        <i class="fas fa-cogs fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="mt-2 text-primary">Paso 1</h6>
                                    <small class="text-primary">Tipo de Unidad</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="wizard-step">
                                    <div class="wizard-step-icon">
                                        <i class="fas fa-car fa-2x text-muted"></i>
                                    </div>
                                    <h6 class="mt-2 text-muted">Paso 2</h6>
                                    <small class="text-muted">Seleccionar Vehículo</small>
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

    <!-- Contenido Principal -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Seleccionar Tipo de Unidad
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

                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="text-center mb-4">
                                <h4>¿Qué tipo de unidad deseas asignar?</h4>
                                <p class="text-muted">Selecciona el tipo de unidad para ver los vehículos disponibles</p>
                            </div>

                            <?php if (empty($tipos_unidad)): ?>
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                    <h5>No hay tipos de unidad disponibles</h5>
                                    <p>Debes crear al menos un tipo de unidad antes de realizar asignaciones.</p>
                                    <a href="<?= base_url('tipo-unidad/create') ?>" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Crear Tipo de Unidad
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($tipos_unidad as $tipo): ?>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card tipo-unidad-card h-100" style="cursor: pointer;" 
                                                 onclick="seleccionarTipoUnidad(<?= $tipo['id'] ?>)">
                                                <div class="card-body text-center">
                                                    <div class="tipo-unidad-icon mb-3">
                                                        <i class="fas fa-cogs fa-3x text-primary"></i>
                                                    </div>
                                                    <h5 class="card-title"><?= esc($tipo['descripcion']) ?></h5>
                                                    <div class="mt-3">
                                                        <span class="badge bg-success">Activo</span>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-transparent text-center">
                                                    <small class="text-muted">Click para seleccionar</small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="text-center mt-4">
                                <a href="<?= base_url('asignacion-vehiculos') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                                </a>
                            </div>
                        </div>
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
    background: linear-gradient(to right, #0d6efd 33%, #dee2e6 33%, #dee2e6 100%);
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

.tipo-unidad-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
    border-radius: 15px;
    overflow: hidden;
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
}

.tipo-unidad-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    border-color: #0d6efd;
    background: linear-gradient(135deg, #ffffff, #f0f8ff);
}

.tipo-unidad-icon {
    transition: all 0.3s ease;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    border: 3px solid #e1f5fe;
}

.tipo-unidad-card:hover .tipo-unidad-icon {
    transform: scale(1.15) rotate(5deg);
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    border-color: #0d6efd;
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
}

.tipo-unidad-card:hover .tipo-unidad-icon i {
    color: white !important;
    transform: scale(1.1);
}

.card-body {
    padding: 2rem;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.wizard-step.active .wizard-step-icon {
    animation: pulse 2s infinite;
}
</style>

<script>
function seleccionarTipoUnidad(tipoId) {
    // Agregar efecto de loading
    const card = event.currentTarget;
    const originalContent = card.innerHTML;
    
    card.innerHTML = `
        <div class="card-body text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando vehículos...</p>
        </div>
    `;
    
    // Redirigir al paso 2
    setTimeout(() => {
        window.location.href = `<?= base_url('asignacion-vehiculos/wizard/step2/') ?>${tipoId}`;
    }, 500);
}

// Animación de entrada para las cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.tipo-unidad-card');
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
