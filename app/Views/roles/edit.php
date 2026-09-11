<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-shield me-2"></i>Editar Rol
            </h1>
            <p class="text-muted mb-0"><?= $rol['nombre'] ?></p>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('admin/roles') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt me-2"></i>Actualizar Información del Rol
                    </h6>
                </div>
                <div class="card-body">
                    <form id="editRoleForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <!-- Información Básica -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Datos Básicos
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-tag me-1"></i>Nombre del Rol *
                                    </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= $rol['nombre'] ?>" placeholder="Ej: Administrador, Mecánico, Conductor" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>Descripción
                                    </label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                                              placeholder="Describe las responsabilidades y funciones de este rol"><?= $rol['descripcion'] ?></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="estado" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>Estado
                                    </label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="ACTIVO" <?= $rol['estado'] === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                                        <option value="INACTIVO" <?= $rol['estado'] === 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Información de Auditoría -->
                                <div class="card bg-light mt-4">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted">
                                            <i class="fas fa-info-circle me-2"></i>Información de Auditoría
                                        </h6>
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <small class="text-muted">
                                                    <strong>Creado:</strong> <?= date('d/m/Y H:i', strtotime($rol['fechaRegistro'])) ?>
                                                </small>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted">
                                                    <strong>Última actualización:</strong> 
                                                    <?= $rol['fechaUpdate'] ? date('d/m/Y H:i', strtotime($rol['fechaUpdate'])) : 'Nunca' ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permisos -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-key me-2"></i>Permisos del Sistema
                                </h6>
                                
                                <!-- Permisos por módulos -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="card-title mb-0">Configuración de Permisos</h6>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="all_permissions">
                                                <label class="form-check-label fw-bold text-warning" for="all_permissions">
                                                    <i class="fas fa-crown me-1"></i>Todos
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <!-- Gestión de Usuarios -->
                                            <div class="col-md-6 mb-3">
                                                <div class="border rounded p-2">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="fas fa-users me-1"></i>Usuarios
                                                    </h6>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="usuarios_ver" id="usuarios_ver">
                                                        <label class="form-check-label" for="usuarios_ver">Ver usuarios</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="usuarios_crear" id="usuarios_crear">
                                                        <label class="form-check-label" for="usuarios_crear">Crear usuarios</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="usuarios_editar" id="usuarios_editar">
                                                        <label class="form-check-label" for="usuarios_editar">Editar usuarios</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="usuarios_eliminar" id="usuarios_eliminar">
                                                        <label class="form-check-label" for="usuarios_eliminar">Eliminar usuarios</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gestión de Vehículos -->
                                            <div class="col-md-6 mb-3">
                                                <div class="border rounded p-2">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="fas fa-car me-1"></i>Vehículos
                                                    </h6>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="vehiculos_ver" id="vehiculos_ver">
                                                        <label class="form-check-label" for="vehiculos_ver">Ver vehículos</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="vehiculos_crear" id="vehiculos_crear">
                                                        <label class="form-check-label" for="vehiculos_crear">Crear vehículos</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="vehiculos_editar" id="vehiculos_editar">
                                                        <label class="form-check-label" for="vehiculos_editar">Editar vehículos</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="vehiculos_eliminar" id="vehiculos_eliminar">
                                                        <label class="form-check-label" for="vehiculos_eliminar">Eliminar vehículos</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Mantenimiento -->
                                            <div class="col-md-6 mb-3">
                                                <div class="border rounded p-2">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="fas fa-tools me-1"></i>Mantenimiento
                                                    </h6>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="mantenimiento_ver" id="mantenimiento_ver">
                                                        <label class="form-check-label" for="mantenimiento_ver">Ver solicitudes</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="mantenimiento_crear" id="mantenimiento_crear">
                                                        <label class="form-check-label" for="mantenimiento_crear">Crear solicitudes</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="mantenimiento_procesar" id="mantenimiento_procesar">
                                                        <label class="form-check-label" for="mantenimiento_procesar">Procesar mantenimiento</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reportes -->
                                            <div class="col-md-6 mb-3">
                                                <div class="border rounded p-2">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="fas fa-chart-bar me-1"></i>Reportes
                                                    </h6>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="reportes_ver" id="reportes_ver">
                                                        <label class="form-check-label" for="reportes_ver">Ver reportes</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="reportes_generar" id="reportes_generar">
                                                        <label class="form-check-label" for="reportes_generar">Generar reportes</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="reportes_exportar" id="reportes_exportar">
                                                        <label class="form-check-label" for="reportes_exportar">Exportar reportes</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Roles (solo para administradores) -->
                                            <div class="col-md-6 mb-3">
                                                <div class="border rounded p-2">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="fas fa-user-shield me-1"></i>Roles
                                                    </h6>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="roles_ver" id="roles_ver">
                                                        <label class="form-check-label" for="roles_ver">Ver roles</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="roles_crear" id="roles_crear">
                                                        <label class="form-check-label" for="roles_crear">Crear roles</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="roles_editar" id="roles_editar">
                                                        <label class="form-check-label" for="roles_editar">Editar roles</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="roles_eliminar" id="roles_eliminar">
                                                        <label class="form-check-label" for="roles_eliminar">Eliminar roles</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Configuración -->
                                            <div class="col-md-6 mb-3">
                                                <div class="border rounded p-2">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="fas fa-cog me-1"></i>Configuración
                                                    </h6>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="config_ver" id="config_ver">
                                                        <label class="form-check-label" for="config_ver">Ver configuración</label>
                                                    </div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                                                               value="config_editar" id="config_editar">
                                                        <label class="form-check-label" for="config_editar">Editar configuración</label>
                                                    </div>
                                                </div>
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
                                    <a href="<?= base_url('admin/roles') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>Actualizar Rol
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
    // Cargar permisos actuales del rol
    const permisosActuales = <?= json_encode($permisos ?? []) ?>;
    
    // Marcar permisos existentes
    if (permisosActuales && permisosActuales.length > 0) {
        permisosActuales.forEach(function(permiso) {
            const checkbox = $(`input[value="${permiso.modulo}_${permiso.accion}"]`);
            if (checkbox.length) {
                checkbox.prop('checked', true);
            }
        });
    }

    // Verificar si todos los permisos están seleccionados
    checkAllPermissions();

    // Manejar checkbox "Todos los permisos"
    $('#all_permissions').change(function() {
        const isChecked = $(this).is(':checked');
        $('input[name="permisos[]"]').prop('checked', isChecked);
    });

    // Si se desmarca algún permiso individual, desmarcar "Todos los permisos"
    $('input[name="permisos[]"]').change(function() {
        checkAllPermissions();
    });

    function checkAllPermissions() {
        const totalPermisos = $('input[name="permisos[]"]').length;
        const permisosSeleccionados = $('input[name="permisos[]"]:checked').length;
        
        $('#all_permissions').prop('checked', totalPermisos === permisosSeleccionados);
    }

    // Submit del formulario
    $('#editRoleForm').submit(function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        // Deshabilitar botón y mostrar loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...');
        
        // Limpiar errores previos
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        $.ajax({
            url: '<?= base_url('admin/roles/' . $rol['id'] . '/update') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redirigir con mensaje de éxito
                    window.location.href = '<?= base_url('admin/roles') ?>?success=' + encodeURIComponent(response.success);
                } else {
                    showAlert('error', response.error || 'Error al actualizar el rol');
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
                    showAlert('error', response ? response.error : 'Error al actualizar el rol');
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
