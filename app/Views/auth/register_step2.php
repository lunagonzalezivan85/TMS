<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Header del Wizard -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center bg-success text-white px-4 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-user-shield me-2"></i>
                    <h5 class="mb-0 fw-bold">Crear Usuario Administrador</h5>
                </div>
                <p class="text-muted mt-2 mb-0">¡Último paso! Crea tu cuenta de administrador</p>
            </div>
            
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-success text-white border-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="fas fa-user-shield text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Paso 2: Usuario Administrador</h4>
                            <small class="opacity-75">Configura tu cuenta para gestionar el sistema</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Progress Bar Mejorada -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="fas fa-check me-1"></i> Empresa
                            </span>
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="fas fa-user-shield me-1"></i> Administrador
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-gradient-success progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-success fw-bold">
                                <i class="fas fa-check-circle me-1"></i>
                                Paso 2 de 2 - ¡Completando registro!
                            </small>
                        </div>
                    </div>

                    <!-- Resumen de la empresa -->
                    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-building text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold text-dark"><?= esc($empresa_data['nombre']) ?></h5>
                                    <small class="text-success fw-medium">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Empresa registrada exitosamente
                                    </small>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                        <i class="fas fa-id-card text-primary me-3 fs-5"></i>
                                        <div>
                                            <small class="text-muted text-uppercase fw-medium">NIT/RUC</small>
                                            <div class="fw-bold text-dark"><?= esc($empresa_data['ruc']) ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                        <i class="fas fa-envelope text-primary me-3 fs-5"></i>
                                        <div>
                                            <small class="text-muted text-uppercase fw-medium">Email</small>
                                            <div class="fw-bold text-dark"><?= esc($empresa_data['correo']) ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                        <i class="fas fa-phone text-primary me-3 fs-5"></i>
                                        <div>
                                            <small class="text-muted text-uppercase fw-medium">Teléfono</small>
                                            <div class="fw-bold text-dark"><?= esc($empresa_data['telefono']) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

                    <form id="usuarioForm" action="<?= base_url('register/step2') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Sección de Datos Personales -->
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-user me-2"></i>
                                Datos Personales
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_usuario" class="form-label fw-semibold">
                                            <i class="fas fa-user me-2 text-success"></i>
                                            Nombre Completo *
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg <?= isset($validation) && $validation->hasError('nombre_usuario') ? 'is-invalid' : '' ?>" 
                                               id="nombre_usuario" 
                                               name="nombre_usuario" 
                                               value="<?= old('nombre_usuario', $usuario_data['nombre'] ?? '') ?>" 
                                               placeholder="Ej: Juan Pérez García">
                                        <?php if (isset($validation) && $validation->hasError('nombre_usuario')): ?>
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <?= $validation->getError('nombre_usuario') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Credenciales -->
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-key me-2"></i>
                                Credenciales de Acceso
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuario" class="form-label fw-semibold">
                                            <i class="fas fa-at me-2 text-success"></i>
                                            Nombre de Usuario *
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg <?= isset($validation) && $validation->hasError('usuario') ? 'is-invalid' : '' ?>" 
                                               id="usuario" 
                                               name="usuario" 
                                               value="<?= old('usuario', $usuario_data['usuario'] ?? '') ?>" 
                                               placeholder="admin_empresa">
                                        <?php if (isset($validation) && $validation->hasError('usuario')): ?>
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <?= $validation->getError('usuario') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="clave" class="form-label fw-semibold">
                                            <i class="fas fa-lock me-2 text-success"></i>
                                            Contraseña *
                                        </label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control form-control-lg <?= isset($validation) && $validation->hasError('clave') ? 'is-invalid' : '' ?>" 
                                                   id="clave" 
                                                   name="clave" 
                                                   placeholder="Mínimo 6 caracteres">
                                            <button class="btn btn-outline-success" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if (isset($validation) && $validation->hasError('clave')): ?>
                                                <div class="invalid-feedback">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    <?= $validation->getError('clave') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Usa al menos 6 caracteres con letras y números
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirmar_clave" class="form-label fw-semibold">
                                            <i class="fas fa-shield-alt me-2 text-success"></i>
                                            Confirmar Contraseña *
                                        </label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control form-control-lg <?= isset($validation) && $validation->hasError('confirmar_clave') ? 'is-invalid' : '' ?>" 
                                                   id="confirmar_clave" 
                                                   name="confirmar_clave" 
                                                   placeholder="Repite la contraseña">
                                            <button class="btn btn-outline-success" type="button" id="toggleConfirmPassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if (isset($validation) && $validation->hasError('confirmar_clave')): ?>
                                                <div class="invalid-feedback">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    <?= $validation->getError('confirmar_clave') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="correo_usuario" class="form-label fw-semibold">
                                            <i class="fas fa-envelope me-2 text-success"></i>
                                            Correo Electrónico *
                                        </label>
                                        <input type="email" 
                                               class="form-control form-control-lg <?= isset($validation) && $validation->hasError('correo_usuario') ? 'is-invalid' : '' ?>" 
                                               id="correo_usuario" 
                                               name="correo_usuario" 
                                               value="<?= old('correo_usuario', $usuario_data['correo'] ?? '') ?>" 
                                               placeholder="tu@email.com">
                                        <?php if (isset($validation) && $validation->hasError('correo_usuario')): ?>
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <?= $validation->getError('correo_usuario') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono_usuario" class="form-label fw-semibold">
                                            <i class="fas fa-phone me-2 text-success"></i>
                                            Teléfono
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg <?= isset($validation) && $validation->hasError('telefono_usuario') ? 'is-invalid' : '' ?>" 
                                               id="telefono_usuario" 
                                               name="telefono_usuario" 
                                               value="<?= old('telefono_usuario', $usuario_data['telefono'] ?? '') ?>" 
                                               placeholder="(123) 456-7890">
                                        <?php if (isset($validation) && $validation->hasError('telefono_usuario')): ?>
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <?= $validation->getError('telefono_usuario') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del rol -->
                        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-warning bg-opacity-20 rounded-circle p-3 me-3">
                                        <i class="fas fa-crown text-warning fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold text-dark">Rol: Administrador</h5>
                                        <small class="text-muted fw-medium">Permisos completos del sistema</small>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3 col-6">
                                        <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                            <i class="fas fa-users text-warning me-3 fs-5"></i>
                                            <small class="fw-bold text-dark">Usuarios</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                            <i class="fas fa-car text-warning me-3 fs-5"></i>
                                            <small class="fw-bold text-dark">Vehículos</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                            <i class="fas fa-tools text-warning me-3 fs-5"></i>
                                            <small class="fw-bold text-dark">Mantenimiento</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                            <i class="fas fa-chart-bar text-warning me-3 fs-5"></i>
                                            <small class="fw-bold text-dark">Reportes</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nota final -->
                        <div class="alert alert-success border-0 bg-success bg-opacity-10 mb-4 p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-20 rounded-circle p-3 me-4 flex-shrink-0">
                                    <i class="fas fa-rocket text-success fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading mb-2 text-success fw-bold">¡Todo listo para comenzar!</h6>
                                    <p class="mb-0 text-dark">Al completar el registro, podrás acceder inmediatamente al sistema con permisos de administrador.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-center">
                            <a href="<?= base_url('register/step1') ?>" class="btn btn-outline-secondary btn-lg w-100 w-md-auto" style="min-width: 200px;">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 1
                            </a>
                            <button type="submit" class="btn btn-success btn-lg shadow-sm w-100 w-md-auto" id="btnRegistrar" style="min-width: 200px;">
                                <i class="fas fa-rocket me-2"></i>
                                Completar Registro
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
    const form = document.getElementById('usuarioForm');
    const btnRegistrar = document.getElementById('btnRegistrar');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const claveInput = document.getElementById('clave');
    const confirmarClaveInput = document.getElementById('confirmar_clave');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = claveInput.getAttribute('type') === 'password' ? 'text' : 'password';
        claveInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmarClaveInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmarClaveInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Form submission
    form.addEventListener('submit', function() {
        btnRegistrar.disabled = true;
        btnRegistrar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...';
    });
    
    // Validación en tiempo real
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
    
    // Validar que las contraseñas coincidan
    confirmarClaveInput.addEventListener('blur', function() {
        if (this.value !== claveInput.value) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value.length >= 6) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
    
    claveInput.addEventListener('input', function() {
        if (confirmarClaveInput.value !== '' && confirmarClaveInput.value !== this.value) {
            confirmarClaveInput.classList.add('is-invalid');
            confirmarClaveInput.classList.remove('is-valid');
        } else if (confirmarClaveInput.value === this.value && this.value.length >= 6) {
            confirmarClaveInput.classList.remove('is-invalid');
            confirmarClaveInput.classList.add('is-valid');
        }
    });
});
</script>
<?= $this->endSection() ?>
