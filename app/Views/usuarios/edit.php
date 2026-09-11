<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit me-2"></i>Editar Usuario
            </h1>
            <p class="text-muted mb-0"><?= $usuario['nombre'] ?></p>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('usuarios/show/' . $usuario['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit me-2"></i>Actualizar Información
                    </h6>
                </div>
                <div class="card-body">
                    <form id="editUserForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <!-- Información Personal -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Datos Personales
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nombre Completo *
                                    </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= $usuario['nombre'] ?>" placeholder="Ingresa el nombre completo" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="correo" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control" id="correo" name="correo" 
                                           value="<?= $usuario['correo'] ?>" placeholder="usuario@email.com">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Teléfono
                                    </label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                                           value="<?= $usuario['telefono'] ?>" placeholder="Número de teléfono">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Información del Sistema -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-cog me-2"></i>Configuración del Sistema
                                </h6>

                                <div class="mb-3">
                                    <label for="usuario" class="form-label">
                                        <i class="fas fa-user-tag me-1"></i>Nombre de Usuario *
                                    </label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" 
                                           value="<?= $usuario['usuario'] ?>" placeholder="Nombre de usuario único" required>
                                    <div class="form-text">Solo letras, números y guiones bajos</div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="clave" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Nueva Contraseña
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="clave" name="clave" 
                                               placeholder="Dejar vacío para mantener la actual">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Solo completar si deseas cambiar la contraseña</div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="id_rol" class="form-label">
                                        <i class="fas fa-user-shield me-1"></i>Rol *
                                    </label>
                                    <select class="form-select" id="id_rol" name="id_rol" required>
                                        <option value="">Selecciona un rol</option>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol['id'] ?>" <?= $rol['id'] == $usuario['id_rol'] ? 'selected' : '' ?>>
                                                <?= $rol['nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="id_empresa" class="form-label">
                                        <i class="fas fa-building me-1"></i>Empresa *
                                    </label>
                                    <select class="form-select" id="id_empresa" name="id_empresa" required>
                                        <option value="">Selecciona una empresa</option>
                                        <?php foreach ($empresas as $empresa): ?>
                                            <option value="<?= $empresa['id'] ?>" <?= $empresa['id'] == $usuario['id_empresa'] ? 'selected' : '' ?>>
                                                <?= $empresa['nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Auditoría -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted">
                                            <i class="fas fa-info-circle me-2"></i>Información de Auditoría
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Creado:</strong> <?= date('d/m/Y H:i', strtotime($usuario['fechaRegistro'])) ?>
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Última actualización:</strong> 
                                                    <?= $usuario['fechaUpdate'] ? date('d/m/Y H:i', strtotime($usuario['fechaUpdate'])) : 'Nunca' ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('usuarios/show/' . $usuario['id']) ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>Actualizar Usuario
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
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#clave');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Validación en tiempo real del nombre de usuario
    $('#usuario').on('input', function() {
        const value = $(this).val();
        const regex = /^[a-zA-Z0-9_]+$/;
        
        if (value && !regex.test(value)) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Solo se permiten letras, números y guiones bajos');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Submit del formulario
    $('#editUserForm').submit(function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        // Deshabilitar botón y mostrar loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...');
        
        // Limpiar errores previos
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        $.ajax({
            url: '<?= base_url('usuarios/update/' . $usuario['id']) ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redirigir con mensaje de éxito
                    window.location.href = '<?= base_url('usuarios/show/' . $usuario['id']) ?>?success=' + encodeURIComponent(response.success);
                } else {
                    showAlert('error', response.error || 'Error al actualizar el usuario');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                
                if (response && response.validation) {
                    // Mostrar errores de validación
                    $.each(response.validation, function(field, message) {
                        const input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(message);
                    });
                } else {
                    showAlert('error', response ? response.error : 'Error al actualizar el usuario');
                }
                
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
        
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.container-fluid').prepend(alert);
        
        // Auto-hide después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
