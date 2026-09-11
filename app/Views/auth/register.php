<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<section class="py-5" style="min-height: 100vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="feature-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h2 class="fw-bold">Crear Cuenta</h2>
                            <p class="text-muted">Únete a GMV Sistema y gestiona tus vehículos</p>
                        </div>
                        
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="<?= base_url('auth/register') ?>" method="post" id="registerForm">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label fw-semibold">
                                        <i class="fas fa-user me-2"></i>Nombre
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" 
                                           placeholder="Tu nombre" required value="<?= old('first_name') ?>">
                                    <?php if (isset($validation) && $validation->hasError('first_name')): ?>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <?= $validation->getError('first_name') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label fw-semibold">
                                        <i class="fas fa-user me-2"></i>Apellido
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" 
                                           placeholder="Tu apellido" required value="<?= old('last_name') ?>">
                                    <?php if (isset($validation) && $validation->hasError('last_name')): ?>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <?= $validation->getError('last_name') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                       placeholder="tu@email.com" required value="<?= old('email') ?>">
                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-2"></i>Teléfono
                                </label>
                                <input type="tel" class="form-control form-control-lg" id="phone" name="phone" 
                                       placeholder="Tu número de teléfono" value="<?= old('phone') ?>">
                                <?php if (isset($validation) && $validation->hasError('phone')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('phone') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" 
                                           placeholder="Mínimo 8 caracteres" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <small>La contraseña debe tener al menos 8 caracteres</small>
                                </div>
                                <?php if (isset($validation) && $validation->hasError('password')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('password') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2"></i>Confirmar Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="password_confirm" name="password_confirm" 
                                           placeholder="Repite tu contraseña" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('password_confirm') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="user_type" class="form-label fw-semibold">
                                    <i class="fas fa-users me-2"></i>Tipo de Usuario
                                </label>
                                <select class="form-select form-select-lg" id="user_type" name="user_type" required>
                                    <option value="">Selecciona tu tipo</option>
                                    <option value="conductor" <?= old('user_type') == 'conductor' ? 'selected' : '' ?>>Conductor</option>
                                    <option value="administrador" <?= old('user_type') == 'administrador' ? 'selected' : '' ?>>Administrador de Flota</option>
                                    <option value="mecanico" <?= old('user_type') == 'mecanico' ? 'selected' : '' ?>>Mecánico</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('user_type')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('user_type') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> 
                                    y la <a href="#" class="text-decoration-none">política de privacidad</a>
                                </label>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Crear Cuenta
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-0">¿Ya tienes una cuenta?</p>
                            <a href="<?= base_url('login') ?>" class="btn btn-outline-primary mt-2">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="<?= base_url() ?>" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_confirm');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthIndicator = document.getElementById('passwordStrength');
        
        if (password.length >= 8) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });
    
    // Password confirmation validation
    document.getElementById('password_confirm').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password === confirmPassword && confirmPassword.length > 0) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else if (confirmPassword.length > 0) {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });
    
    // Form validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirm').value;
        const terms = document.getElementById('terms').checked;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Las contraseñas no coinciden.');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 8 caracteres.');
            return false;
        }
        
        if (!terms) {
            e.preventDefault();
            alert('Debes aceptar los términos y condiciones.');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creando cuenta...';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
<?= $this->endSection() ?>
