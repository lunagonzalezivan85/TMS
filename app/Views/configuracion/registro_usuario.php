<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumb as $item): ?>
                <?php if (!empty($item['url'])): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= $item['url'] ?>" class="text-decoration-none">
                            <?= $item['name'] ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $item['name'] ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-plus me-2"></i>
                <?= $title ?>
            </h1>
            <p class="text-muted mb-0">Crear un nuevo usuario en el sistema</p>
        </div>
        <div>
            <a href="<?= base_url('configuracion') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Volver
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <!-- Formulario de Registro -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus me-1"></i>
                        Información del Usuario
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Alertas -->
                    <div id="alertContainer"></div>

                    <form id="formRegistroUsuario" novalidate>
                        <div class="row">
                            <!-- Información Personal -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-1"></i>
                                    Información Personal
                                </h6>
                                
                                <!-- Nombre -->
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        Nombre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Nombre completo del usuario</div>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        Correo Electrónico <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Será usado para iniciar sesión</div>
                                </div>

                                <!-- Teléfono -->
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">
                                        Teléfono
                                    </label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono">
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Número de contacto (opcional)</div>
                                </div>

                                <!-- Dirección -->
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">
                                        Dirección
                                    </label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Dirección de residencia (opcional)</div>
                                </div>
                            </div>

                            <!-- Información del Sistema -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-cog me-1"></i>
                                    Configuración del Sistema
                                </h6>

                                <!-- Usuario -->
                                <div class="mb-3">
                                    <label for="usuario" class="form-label">
                                        Usuario <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Nombre de usuario único para el sistema</div>
                                </div>

                                <!-- Contraseña -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        Contraseña <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>

                                <!-- Confirmar Contraseña -->
                                <div class="mb-3">
                                    <label for="password_confirm" class="form-label">
                                        Confirmar Contraseña <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Debe coincidir con la contraseña</div>
                                </div>

                                <!-- Rol -->
                                <div class="mb-3">
                                    <label for="id_rol" class="form-label">
                                        Rol <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="id_rol" name="id_rol" required>
                                        <option value="">Seleccionar rol...</option>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol['id'] ?>">
                                                <?= esc($rol['nombre']) ?>
                                                <?php if (!empty($rol['descripcion'])): ?>
                                                    - <?= esc($rol['descripcion']) ?>
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">Rol que tendrá el usuario en el sistema</div>
                                </div>

                                <!-- Estado -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="estado" name="estado" checked>
                                        <label class="form-check-label" for="estado">
                                            Usuario Activo
                                        </label>
                                    </div>
                                    <div class="form-text">El usuario podrá acceder al sistema</div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la Empresa -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Información:</strong> El usuario será asociado automáticamente a su empresa actual.
                                    No podrá acceder a información de otras empresas.
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= base_url('configuracion') ?>'">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="fas fa-save me-1"></i>
                                        Crear Usuario
                                    </button>
                                </div>
                            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formRegistroUsuario');
    const btnSubmit = document.getElementById('btnSubmit');
    const alertContainer = document.getElementById('alertContainer');

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordVisibility('password', this);
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        togglePasswordVisibility('password_confirm', this);
    });

    // Validación en tiempo real
    form.addEventListener('input', function(e) {
        validateField(e.target);
    });

    // Validación especial para confirmación de contraseña
    document.getElementById('password_confirm').addEventListener('input', function() {
        validatePasswordConfirm();
    });

    document.getElementById('password').addEventListener('input', function() {
        validatePasswordConfirm();
    });

    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitForm();
        }
    });

    function togglePasswordVisibility(fieldId, button) {
        const field = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';

        // Limpiar estado anterior
        field.classList.remove('is-valid', 'is-invalid');

        switch (field.name) {
            case 'nombre':
                if (!value) {
                    isValid = false;
                    message = 'El nombre es requerido';
                } else if (value.length < 2) {
                    isValid = false;
                    message = 'El nombre debe tener al menos 2 caracteres';
                }
                break;

            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!value) {
                    isValid = false;
                    message = 'El correo electrónico es requerido';
                } else if (!emailRegex.test(value)) {
                    isValid = false;
                    message = 'Ingrese un correo electrónico válido';
                }
                break;

            case 'usuario':
                if (!value) {
                    isValid = false;
                    message = 'El usuario es requerido';
                } else if (value.length < 3) {
                    isValid = false;
                    message = 'El usuario debe tener al menos 3 caracteres';
                } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                    isValid = false;
                    message = 'Solo se permiten letras, números y guiones bajos';
                }
                break;

            case 'password':
                if (!value) {
                    isValid = false;
                    message = 'La contraseña es requerida';
                } else if (value.length < 6) {
                    isValid = false;
                    message = 'La contraseña debe tener al menos 6 caracteres';
                }
                break;

            case 'id_rol':
                if (!value) {
                    isValid = false;
                    message = 'Debe seleccionar un rol';
                }
                break;
        }

        // Aplicar estado visual
        if (field.hasAttribute('required') || value) {
            field.classList.add(isValid ? 'is-valid' : 'is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = message;
            }
        }

        return isValid;
    }

    function validatePasswordConfirm() {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm');
        const confirmValue = passwordConfirm.value;
        
        passwordConfirm.classList.remove('is-valid', 'is-invalid');
        
        if (confirmValue) {
            const isValid = password === confirmValue;
            passwordConfirm.classList.add(isValid ? 'is-valid' : 'is-invalid');
            
            const feedback = passwordConfirm.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = isValid ? '' : 'Las contraseñas no coinciden';
            }
            
            return isValid;
        }
        
        return false;
    }

    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        // Validar confirmación de contraseña
        if (!validatePasswordConfirm()) {
            isValid = false;
        }

        return isValid;
    }

    function submitForm() {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creando...';

        const formData = new FormData(form);
        
        // Convertir checkbox a valor numérico
        formData.set('estado', document.getElementById('estado').checked ? '1' : '0');

        fetch('<?= base_url('configuracion/crear-usuario') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.href = '<?= base_url('configuracion') ?>';
                }, 2000);
            } else {
                showAlert('danger', data.message);
                
                // Mostrar errores de validación específicos
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const fieldElement = document.getElementById(field);
                        if (fieldElement) {
                            fieldElement.classList.add('is-invalid');
                            const feedback = fieldElement.parentNode.querySelector('.invalid-feedback');
                            if (feedback) {
                                feedback.textContent = data.errors[field];
                            }
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Error al procesar la solicitud. Intente nuevamente.');
        })
        .finally(() => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-save me-1"></i>Crear Usuario';
        });
    }

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        alertContainer.innerHTML = alertHtml;
        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
</script>
<?= $this->endSection() ?>
