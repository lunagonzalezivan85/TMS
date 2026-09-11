<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Header del Wizard -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center bg-primary text-white px-4 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-rocket me-2"></i>
                    <h5 class="mb-0 fw-bold">Configura tu Sistema GMV</h5>
                </div>
                <p class="text-muted mt-2 mb-0">Crea tu empresa y cuenta de administrador en solo 2 pasos</p>
            </div>
            
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Paso 1: Información de la Empresa</h4>
                            <small class="opacity-75">Registra los datos básicos de tu organización</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Progress Bar Mejorada -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                <i class="fas fa-building me-1"></i> Empresa
                            </span>
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                <i class="fas fa-user-shield me-1"></i> Administrador
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-gradient-primary progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted fw-bold">Paso 1 de 2 - 50% Completado</small>
                        </div>
                    </div>

                    <!-- Alertas -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form id="empresaForm" action="<?= base_url('register/step1') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Sección de Información Básica -->
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Información Básica
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_empresa" class="form-label fw-semibold">
                                            <i class="fas fa-building me-2 text-primary"></i>
                                            Nombre de la Empresa *
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg <?= isset($validation) && $validation->hasError('nombre_empresa') ? 'is-invalid' : '' ?>" 
                                               id="nombre_empresa" 
                                               name="nombre_empresa" 
                                               value="<?= old('nombre_empresa', $empresa_data['nombre'] ?? '') ?>" 
                                               placeholder="Ej: Transportes El Buen Viaje S.A.S">
                                        <?php if (isset($validation) && $validation->hasError('nombre_empresa')): ?>
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <?= $validation->getError('nombre_empresa') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nit" class="form-label fw-semibold">
                                            <i class="fas fa-id-card me-2 text-primary"></i>
                                            NIT *
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg <?= isset($validation) && $validation->hasError('nit') ? 'is-invalid' : '' ?>" 
                                               id="nit" 
                                               name="nit" 
                                               value="<?= old('nit', $empresa_data['nit'] ?? '') ?>" 
                                               placeholder="123456789-0">
                                        <?php if (isset($validation) && $validation->hasError('nit')): ?>
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <?= $validation->getError('nit') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Ubicación -->
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Ubicación
                            </h6>
                            <div class="mb-3">
                                <label for="direccion" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    Dirección Completa *
                                </label>
                                <textarea class="form-control form-control-lg <?= isset($validation) && $validation->hasError('direccion') ? 'is-invalid' : '' ?>" 
                                          id="direccion" 
                                          name="direccion" 
                                          rows="3" 
                                          placeholder="Ej: Calle 123 #45-67, Barrio Centro, Ciudad, Departamento"><?= old('direccion', $empresa_data['direccion'] ?? '') ?></textarea>
                                <?php if (isset($validation) && $validation->hasError('direccion')): ?>
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <?= $validation->getError('direccion') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Sección de Contacto -->
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-phone me-2"></i>
                                Información de Contacto
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono_empresa" class="form-label fw-semibold">
                                            <i class="fas fa-phone me-2 text-primary"></i>
                                            Teléfono Principal *
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg <?= isset($validation) && $validation->hasError('telefono_empresa') ? 'is-invalid' : '' ?>" 
                                               id="telefono_empresa" 
                                               name="telefono_empresa" 
                                               value="<?= old('telefono_empresa', $empresa_data['telefono'] ?? '') ?>" 
                                               placeholder="(601) 234-5678">
                                        <?php if (isset($validation) && $validation->hasError('telefono_empresa')): ?>
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <?= $validation->getError('telefono_empresa') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="correo_empresa" class="form-label fw-semibold">
                                            <i class="fas fa-envelope me-2 text-primary"></i>
                                            Correo Corporativo *
                                        </label>
                                        <input type="email" 
                                               class="form-control form-control-lg <?= isset($validation) && $validation->hasError('correo_empresa') ? 'is-invalid' : '' ?>" 
                                               id="correo_empresa" 
                                               name="correo_empresa" 
                                               value="<?= old('correo_empresa', $empresa_data['correo'] ?? '') ?>" 
                                               placeholder="contacto@tuempresa.com">
                                        <?php if (isset($validation) && $validation->hasError('correo_empresa')): ?>
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <?= $validation->getError('correo_empresa') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nota informativa -->
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lightbulb text-info me-3 fs-4"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">¡Casi listo!</h6>
                                    <p class="mb-0 small">Después de completar estos datos, crearás tu cuenta de administrador en el siguiente paso.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-center">
                            <a href="<?= base_url('login') ?>" class="btn btn-outline-secondary btn-lg w-100 w-md-auto" style="min-width: 200px;">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Login
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm w-100 w-md-auto" id="btnContinuar" style="min-width: 200px;">
                                Continuar al Paso 2
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('empresaForm');
    const btnContinuar = document.getElementById('btnContinuar');
    
    form.addEventListener('submit', function() {
        btnContinuar.disabled = true;
        btnContinuar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
    });
    
    // Validación en tiempo real
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
