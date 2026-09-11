<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2"></i>Detalle del Usuario
            </h1>
            <p class="text-muted mb-0"><?= $usuario['nombre'] ?></p>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('usuarios') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <a href="<?= base_url('usuarios/edit/' . $usuario['id']) ?>" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle me-2"></i>Información Personal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-user me-1"></i>Nombre Completo
                                </label>
                                <p class="form-control-plaintext"><?= $usuario['nombre'] ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-user-tag me-1"></i>Usuario
                                </label>
                                <p class="form-control-plaintext">
                                    <code><?= $usuario['usuario'] ?></code>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-envelope me-1"></i>Correo Electrónico
                                </label>
                                <p class="form-control-plaintext">
                                    <?= $usuario['correo'] ?: '<span class="text-muted">No registrado</span>' ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-phone me-1"></i>Teléfono
                                </label>
                                <p class="form-control-plaintext">
                                    <?= $usuario['telefono'] ?: '<span class="text-muted">No registrado</span>' ?>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-user-shield me-1"></i>Rol
                                </label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary fs-6"><?= $usuario['rol_nombre'] ?></span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-building me-1"></i>Empresa
                                </label>
                                <p class="form-control-plaintext"><?= $usuario['empresa_nombre'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-calendar-plus me-1"></i>Fecha de Registro
                                </label>
                                <p class="form-control-plaintext">
                                    <?= date('d/m/Y H:i', strtotime($usuario['fechaRegistro'])) ?>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-calendar-edit me-1"></i>Última Actualización
                                </label>
                                <p class="form-control-plaintext">
                                    <?= $usuario['fechaUpdate'] ? date('d/m/Y H:i', strtotime($usuario['fechaUpdate'])) : 'Nunca' ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-user-plus me-1"></i>Creado por
                                </label>
                                <p class="form-control-plaintext">
                                    Usuario ID: <?= $usuario['usuarioCrea'] ?: 'Sistema' ?>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-user-edit me-1"></i>Editado por
                                </label>
                                <p class="form-control-plaintext">
                                    <?= $usuario['usuarioEdita'] ? 'Usuario ID: ' . $usuario['usuarioEdita'] : 'Nunca editado' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Estado del Usuario -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Estado
                    </h6>
                </div>
                <div class="card-body text-center">
                    <?php if ($usuario['estado'] === 'ACTIVO'): ?>
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-success">Usuario Activo</h5>
                        <p class="text-muted">El usuario puede acceder al sistema</p>
                        <button class="btn btn-warning btn-sm" onclick="cambiarEstado(<?= $usuario['id'] ?>, 'INACTIVO')">
                            <i class="fas fa-ban me-1"></i>Desactivar
                        </button>
                    <?php else: ?>
                        <div class="mb-3">
                            <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-danger">Usuario Inactivo</h5>
                        <p class="text-muted">El usuario no puede acceder al sistema</p>
                        <button class="btn btn-success btn-sm" onclick="cambiarEstado(<?= $usuario['id'] ?>, 'ACTIVO')">
                            <i class="fas fa-check me-1"></i>Activar
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('usuarios/edit/' . $usuario['id']) ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Usuario
                        </a>
                        
                        <button class="btn btn-outline-warning btn-sm" onclick="resetPassword(<?= $usuario['id'] ?>)">
                            <i class="fas fa-key me-2"></i>Resetear Contraseña
                        </button>
                        
                        <?php if ($usuario['id'] != session()->get('user_id')): ?>
                            <button class="btn btn-outline-danger btn-sm" onclick="eliminarUsuario(<?= $usuario['id'] ?>)">
                                <i class="fas fa-trash me-2"></i>Eliminar Usuario
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Variable para almacenar la acción pendiente
let pendingAction = null;

// Función para cambiar estado
function cambiarEstado(id, estado) {
    const mensaje = estado === 'ACTIVO' ? 'activar' : 'desactivar';
    
    $('#confirmModalLabel').text('Cambiar Estado');
    $('#confirmModalBody').html(`¿Estás seguro de que deseas ${mensaje} este usuario?`);
    $('#confirmAction').removeClass().addClass('btn btn-primary').text('Confirmar');
    
    pendingAction = function() {
        $.ajax({
            url: `<?= base_url('usuarios/cambiarEstado') ?>/${id}`,
            type: 'POST',
            data: { estado: estado },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    showAlert('error', response.error);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('error', response ? response.error : 'Error al cambiar el estado');
            }
        });
    };
    
    $('#confirmModal').modal('show');
}

// Función para eliminar usuario
function eliminarUsuario(id) {
    $('#confirmModalLabel').text('Eliminar Usuario');
    $('#confirmModalBody').html('<p>¿Estás seguro de que deseas eliminar este usuario?</p><p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>');
    $('#confirmAction').removeClass().addClass('btn btn-danger').text('Eliminar');
    
    pendingAction = function() {
        $.ajax({
            url: `<?= base_url('usuarios/delete') ?>/${id}`,
            type: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?= base_url('usuarios') ?>?success=' + encodeURIComponent(response.success);
                } else {
                    showAlert('error', response.error);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('error', response ? response.error : 'Error al eliminar el usuario');
            }
        });
    };
    
    $('#confirmModal').modal('show');
}

// Función para resetear contraseña
function resetPassword(id) {
    // Redirigir directamente a la página de resetear contraseña
    window.location.href = `<?= base_url('usuarios') ?>/${id}/reset-password`;
}

// Confirmar acción
$('#confirmAction').click(function() {
    $('#confirmModal').modal('hide');
    if (pendingAction) {
        pendingAction();
        pendingAction = null;
    }
});

// Función para mostrar alertas
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'warning' ? 'alert-warning' :
                      type === 'info' ? 'alert-info' : 'alert-danger';
    const icon = type === 'success' ? 'fas fa-check-circle' : 
                 type === 'warning' ? 'fas fa-exclamation-triangle' :
                 type === 'info' ? 'fas fa-info-circle' : 'fas fa-exclamation-triangle';
    
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
</script>
<?= $this->endSection() ?>
