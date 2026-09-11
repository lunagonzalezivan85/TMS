<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit me-2"></i><?= $title ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('profile' . ($isOwnProfile ? '' : '/' . $user['id'])) ?>">Perfil</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
        <a href="<?= base_url('profile' . ($isOwnProfile ? '' : '/' . $user['id'])) ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver al Perfil
        </a>
    </div>

    <!-- Mensajes de alerta -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Editar Información del Perfil
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('profile/update' . ($isOwnProfile ? '' : '/' . $user['id'])) ?>" method="post" id="editProfileForm">
                        <?= csrf_field() ?>
                        
                        <!-- Información Personal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Información Personal
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label fw-semibold">
                                    <i class="fas fa-user me-1"></i>Nombre Completo *
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?= old('nombre', $user['nombre']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('nombre') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-1"></i>Correo Electrónico *
                                </label>
                                <input type="email" class="form-control" id="correo" name="correo" 
                                       value="<?= old('correo', $user['correo']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('correo')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('correo') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-1"></i>Teléfono
                                </label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?= old('telefono', $user['telefono']) ?>" placeholder="Opcional">
                                <?php if (isset($validation) && $validation->hasError('telefono')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('telefono') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label fw-semibold">
                                    <i class="fas fa-at me-1"></i>Nombre de Usuario
                                </label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?= $user['usuario'] ?>" readonly>
                                <small class="text-muted">El nombre de usuario no se puede cambiar</small>
                            </div>
                        </div>

                        <!-- Rol (solo para administradores editando otros usuarios) -->
                        <?php if ($canChangeRole && !empty($roles)): ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-warning border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-tag me-2"></i>Rol y Permisos
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="id_rol" class="form-label fw-semibold">
                                    <i class="fas fa-crown me-1"></i>Rol *
                                </label>
                                <select class="form-select" id="id_rol" name="id_rol" required>
                                    <option value="">Seleccionar rol...</option>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?= $rol['id'] ?>" <?= $rol['id'] == old('id_rol', $user['id_rol']) ? 'selected' : '' ?>>
                                            <?= esc($rol['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('id_rol')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('id_rol') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Cambio de Contraseña -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-danger border-bottom pb-2 mb-3">
                                    <i class="fas fa-lock me-2"></i>Cambiar Contraseña
                                </h6>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Deja estos campos vacíos si no deseas cambiar la contraseña
                                </p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nueva_clave" class="form-label fw-semibold">
                                    <i class="fas fa-key me-1"></i>Nueva Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" 
                                           placeholder="Mínimo 6 caracteres">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <?php if (isset($validation) && $validation->hasError('nueva_clave')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('nueva_clave') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="confirmar_clave" class="form-label fw-semibold">
                                    <i class="fas fa-check-double me-1"></i>Confirmar Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" 
                                           placeholder="Repetir nueva contraseña">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <?php if (isset($validation) && $validation->hasError('confirmar_clave')): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?= $validation->getError('confirmar_clave') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Información de la Empresa (solo lectura) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2 mb-3">
                                    <i class="fas fa-building me-2"></i>Información de la Empresa
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-building me-1"></i>Empresa
                                </label>
                                <input type="text" class="form-control" value="<?= esc($user['empresa_nombre']) ?>" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-id-card me-1"></i>RUC
                                </label>
                                <input type="text" class="form-control" value="<?= esc($user['empresa_ruc']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('profile' . ($isOwnProfile ? '' : '/' . $user['id'])) ?>" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Guardar Cambios
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const newPasswordInput = document.getElementById('nueva_clave');
    const confirmPasswordInput = document.getElementById('confirmar_clave');

    toggleNewPassword.addEventListener('click', function() {
        const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        newPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Validación de contraseñas coincidentes
    confirmPasswordInput.addEventListener('input', function() {
        const newPassword = newPasswordInput.value;
        const confirmPassword = this.value;
        
        if (confirmPassword && newPassword !== confirmPassword) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (confirmPassword) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        } else {
            this.classList.remove('is-invalid', 'is-valid');
        }
    });

    // Validación del formulario
    const form = document.getElementById('editProfileForm');
    form.addEventListener('submit', function(e) {
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (newPassword && newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            confirmPasswordInput.focus();
        }
    });
});
</script>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.border-bottom {
    border-bottom: 2px solid !important;
}

.is-valid {
    border-color: #28a745;
}

.is-invalid {
    border-color: #dc3545;
}
</style>
<?= $this->endsection() ?>
