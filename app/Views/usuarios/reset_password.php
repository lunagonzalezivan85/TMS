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
            <li class="breadcrumb-item"><a href="<?= base_url('usuarios') ?>">Usuarios</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('usuarios/' . $usuario['id']) ?>"><?= esc($usuario['nombre']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Resetear Contraseña</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Información del Usuario -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-shield me-2"></i>
                        Resetear Contraseña de Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> <?= esc($usuario['nombre']) ?></p>
                            <p><strong>Usuario:</strong> <?= esc($usuario['usuario']) ?></p>
                            <p><strong>Correo:</strong> <?= esc($usuario['correo'] ?: 'No especificado') ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Rol:</strong> <span class="badge bg-primary"><?= esc($usuario['rol_nombre']) ?></span></p>
                            <p><strong>Empresa:</strong> <?= esc($usuario['empresa_nombre']) ?></p>
                            <p><strong>Estado:</strong> 
                                <span class="badge <?= $usuario['estado'] === 'ACTIVO' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= esc($usuario['estado']) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opciones de Reseteo -->
            <div class="row">
                <!-- Resetear con Nueva Contraseña -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-key me-2"></i>
                                Establecer Nueva Contraseña
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Establece una contraseña específica para el usuario.</p>
                            
                            <form id="resetPasswordForm">
                                <div class="mb-3">
                                    <label for="nueva_clave" class="form-label">Nueva Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" 
                                               minlength="6" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirmar_clave" class="form-label">Confirmar Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" 
                                               minlength="6" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-save me-2"></i>
                                    Establecer Contraseña
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Generar Contraseña Temporal -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-random me-2"></i>
                                Generar Contraseña Temporal
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Genera automáticamente una contraseña temporal aleatoria de 8 caracteres.</p>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>La contraseña temporal se mostrará una sola vez. Asegúrate de comunicársela al usuario de forma segura.</small>
                            </div>

                            <button type="button" class="btn btn-secondary w-100" id="generateTempPassword">
                                <i class="fas fa-dice me-2"></i>
                                Generar Contraseña Temporal
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('usuarios/' . $usuario['id']) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver al Usuario
                        </a>
                        <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>
                            Lista de Usuarios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar contraseña temporal -->
<div class="modal fade" id="tempPasswordModal" tabindex="-1" aria-labelledby="tempPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tempPasswordModalLabel">
                    <i class="fas fa-check-circle me-2"></i>
                    Contraseña Temporal Generada
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>¡IMPORTANTE!</strong> Esta contraseña se mostrará solo una vez. Cópiala y comunícasela al usuario de forma segura.
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>Usuario:</strong></label>
                    <p id="tempUserName" class="fs-5"></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>Contraseña Temporal:</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control text-center fs-4 fw-bold text-primary" 
                               id="tempPasswordDisplay" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="copyPassword" title="Copiar contraseña">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="copyAndClose">
                    <i class="fas fa-copy me-2"></i>
                    Copiar y Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.getElementById('resetPasswordForm');
    const generateBtn = document.getElementById('generateTempPassword');
    const tempModal = new bootstrap.Modal(document.getElementById('tempPasswordModal'));
    
    // Toggle password visibility
    document.getElementById('togglePassword1').addEventListener('click', function() {
        togglePasswordVisibility('nueva_clave', this);
    });
    
    document.getElementById('togglePassword2').addEventListener('click', function() {
        togglePasswordVisibility('confirmar_clave', this);
    });
    
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Validar coincidencia de contraseñas
    document.getElementById('confirmar_clave').addEventListener('input', function() {
        const password = document.getElementById('nueva_clave').value;
        const confirm = this.value;
        
        if (confirm && password !== confirm) {
            this.classList.add('is-invalid');
            this.nextElementSibling.textContent = 'Las contraseñas no coinciden';
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Enviar formulario de reseteo
    resetForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Validar que las contraseñas coincidan
        const password = document.getElementById('nueva_clave').value;
        const confirm = document.getElementById('confirmar_clave').value;
        
        if (password !== confirm) {
            showAlert('error', 'Las contraseñas no coinciden');
            return;
        }
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch('<?= base_url('usuarios/' . $usuario['id'] . '/reset-password') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                Swal.fire({
                    title: '¡Contraseña Actualizada!',
                    text: result.success,
                    icon: 'success',
                    confirmButtonText: 'Continuar',
                    confirmButtonColor: '#10b981',
                    allowOutsideClick: false
                }).then((swalResult) => {
                    if (swalResult.isConfirmed && result.redirect) {
                        window.location.href = result.redirect;
                    }
                });
            } else {
                showAlert('error', result.error || 'Error al resetear la contraseña');
                if (result.validation) {
                    showValidationErrors(result.validation);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'Error de conexión');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
    
    // Generar contraseña temporal
    generateBtn.addEventListener('click', async function() {
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generando...';
        this.disabled = true;
        
        try {
            const response = await fetch('<?= base_url('usuarios/' . $usuario['id'] . '/generate-temp-password') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Mostrar contraseña temporal en modal
                document.getElementById('tempUserName').textContent = result.usuario_nombre + ' (' + result.usuario_usuario + ')';
                document.getElementById('tempPasswordDisplay').value = result.temp_password;
                tempModal.show();
                
                // Mostrar notificación de éxito con SweetAlert2
                Swal.fire({
                    title: '¡Contraseña Temporal Generada!',
                    text: result.success,
                    icon: 'success',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#10b981',
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            } else {
                showAlert('error', result.error || 'Error al generar contraseña temporal');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'Error de conexión');
        } finally {
            this.innerHTML = originalText;
            this.disabled = false;
        }
    });
    
    // Copiar contraseña al portapapeles
    document.getElementById('copyPassword').addEventListener('click', function() {
        copyToClipboard();
    });
    
    document.getElementById('copyAndClose').addEventListener('click', function() {
        copyToClipboard();
        tempModal.hide();
    });
    
    function copyToClipboard() {
        const passwordInput = document.getElementById('tempPasswordDisplay');
        passwordInput.select();
        passwordInput.setSelectionRange(0, 99999);
        
        try {
            document.execCommand('copy');
            Swal.fire({
                title: '¡Copiado!',
                text: 'Contraseña copiada al portapapeles',
                icon: 'success',
                timer: 1500,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        } catch (err) {
            console.error('Error al copiar:', err);
            Swal.fire({
                title: 'Error',
                text: 'No se pudo copiar la contraseña',
                icon: 'error',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#ef4444'
            });
        }
    }
    
    function showAlert(type, message) {
        const config = {
            title: type === 'success' ? '¡Éxito!' : 'Error',
            text: message,
            icon: type === 'success' ? 'success' : 'error',
            confirmButtonText: 'Entendido',
            confirmButtonColor: type === 'success' ? '#10b981' : '#ef4444',
            timer: type === 'success' ? 3000 : null,
            timerProgressBar: type === 'success',
            showCloseButton: true,
            allowOutsideClick: false
        };
        
        Swal.fire(config);
    }
    
    function showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.classList.add('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = errors[field];
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
