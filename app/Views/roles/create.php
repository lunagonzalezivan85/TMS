<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-shield me-2"></i>Crear Rol
            </h1>
            <p class="text-muted mb-0">Define un nuevo rol con sus permisos correspondientes</p>
        </div>
        <a href="<?= base_url('admin/roles') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt me-2"></i>Información del Rol
                    </h6>
                </div>
                <div class="card-body">
                    <form id="createRoleForm">
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
                                           placeholder="Ej: Administrador, Mecánico, Conductor" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>Descripción
                                    </label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                                              placeholder="Describe las responsabilidades y funciones de este rol"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="estado" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>Estado
                                    </label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="ACTIVO" selected>Activo</option>
                                        <option value="INACTIVO">Inactivo</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Permisos -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-key me-2"></i>Permisos del Sistema
                                </h6>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Nota:</strong> Los permisos se pueden configurar después de crear el rol, 
                                    o puedes seleccionar algunos permisos básicos ahora.
                                </div>

                                <!-- Permisos básicos por módulos -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Permisos Básicos</h6>
                                        
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="all_permissions">
                                                    <label class="form-check-label fw-bold" for="all_permissions">
                                                        <i class="fas fa-crown text-warning me-1"></i>
                                                        Administrador Total (Todos los permisos)
                                                    </label>
                                                </div>
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
                                        <i class="fas fa-save me-2"></i>Crear Rol
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
    // Manejar checkbox "Todos los permisos"
    $('#all_permissions').change(function() {
        const isChecked = $(this).is(':checked');
        $('input[name="permisos[]"]').prop('checked', isChecked);
    });

    // Si se desmarca algún permiso individual, desmarcar "Todos los permisos"
    $('input[name="permisos[]"]').change(function() {
        const totalPermisos = $('input[name="permisos[]"]').length;
        const permisosSeleccionados = $('input[name="permisos[]"]:checked').length;
        
        $('#all_permissions').prop('checked', totalPermisos === permisosSeleccionados);
    });

    // Submit del formulario
    $('#createRoleForm').submit(function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        // Deshabilitar botón y mostrar loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Creando...');
        
        // Limpiar errores previos
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        $.ajax({
            url: '<?= base_url('admin/roles/store') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redirigir con mensaje de éxito
                    window.location.href = '<?= base_url('admin/roles') ?>?success=' + encodeURIComponent(response.success);
                } else {
                    showAlert('error', response.error || 'Error al crear el rol');
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
                    showAlert('error', response ? response.error : 'Error al crear el rol');
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
