<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumb as $item): ?>
                <?php if (empty($item['url'])): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($item['name']) ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="<?= esc($item['url']) ?>"><?= esc($item['name']) ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-eye text-primary me-2"></i>
            Detalles del Acceso
        </h1>
        <div class="btn-group" role="group">
            <a href="<?= base_url('acceso') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Volver
            </a>
            <a href="<?= base_url('acceso/edit/' . $acceso['id']) ?>" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>
                Editar
            </a>
            <a href="<?= base_url('acceso/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Nuevo Acceso
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Información principal del acceso -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key me-2"></i>
                        Información del Acceso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-hashtag me-1"></i>
                                    ID del Acceso
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-primary fs-6">#<?= esc($acceso['id']) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    Estado
                                </label>
                                <div class="form-control-plaintext">
                                    <?php if ($acceso['estado'] == 1): ?>
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger fs-6">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Inactivo
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-users me-1"></i>
                                    Rol Asignado
                                </label>
                                <div class="form-control-plaintext">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info me-2">
                                            <i class="fas fa-user-tag me-1"></i>
                                            ROL
                                        </span>
                                        <strong><?= esc($acceso['rol_nombre']) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-bars me-1"></i>
                                    Menú Asignado
                                </label>
                                <div class="form-control-plaintext">
                                    <div class="d-flex align-items-center">
                                        <i class="<?= esc($acceso['menu_icono']) ?> me-2 text-primary"></i>
                                        <strong><?= esc($acceso['menu_nombre']) ?></strong>
                                    </div>
                                    <?php if (!empty($acceso['menu_url'])): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-link me-1"></i>
                                            URL: <?= esc($acceso['menu_url']) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de auditoría -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Información de Auditoría
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Fecha de Registro
                                </label>
                                <div class="form-control-plaintext">
                                    <?= date('d/m/Y H:i:s', strtotime($acceso['fecha_registro'])) ?>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= helper('date'); echo time_ago($acceso['fecha_registro']) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user-plus me-1"></i>
                                    Creado por
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-secondary">
                                        Usuario ID: <?= esc($acceso['usuario_crea']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($acceso['fecha_actualizacion'])): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-calendar-edit me-1"></i>
                                        Última Actualización
                                    </label>
                                    <div class="form-control-plaintext">
                                        <?= date('d/m/Y H:i:s', strtotime($acceso['fecha_actualizacion'])) ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= time_ago($acceso['fecha_actualizacion']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user-edit me-1"></i>
                                        Actualizado por
                                    </label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-secondary">
                                            Usuario ID: <?= esc($acceso['usuario_actualiza']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Acciones rápidas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('acceso/edit/' . $acceso['id']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Editar Acceso
                        </a>
                        
                        <button type="button" class="btn btn-<?= $acceso['estado'] == 1 ? 'secondary' : 'success' ?>" 
                                onclick="cambiarEstado(<?= $acceso['id'] ?>)">
                            <i class="fas fa-toggle-<?= $acceso['estado'] == 1 ? 'off' : 'on' ?> me-2"></i>
                            <?= $acceso['estado'] == 1 ? 'Desactivar' : 'Activar' ?>
                        </button>
                        
                        <button type="button" class="btn btn-danger" onclick="eliminarAcceso(<?= $acceso['id'] ?>)">
                            <i class="fas fa-trash me-2"></i>
                            Eliminar Acceso
                        </button>
                        
                        <hr>
                        
                        <a href="<?= base_url('acceso/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Crear Nuevo Acceso
                        </a>
                        
                        <a href="<?= base_url('acceso') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>
                            Ver Todos los Accesos
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información Adicional
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>¿Qué significa este acceso?</strong>
                        <p class="mb-0 mt-2">
                            Este acceso permite que todos los usuarios con el rol 
                            <strong>"<?= esc($acceso['rol_nombre']) ?>"</strong> 
                            puedan ver y usar el menú 
                            <strong>"<?= esc($acceso['menu_nombre']) ?>"</strong> 
                            en el sistema.
                        </p>
                    </div>

                    <?php if ($acceso['estado'] == 1): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Acceso Activo</strong>
                            <p class="mb-0 mt-2">
                                Este acceso está actualmente habilitado y funcionando.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Acceso Inactivo</strong>
                            <p class="mb-0 mt-2">
                                Este acceso está deshabilitado. Los usuarios con este rol 
                                no podrán ver el menú asignado.
                            </p>
                        </div>
                    <?php endif; ?>

                    <div class="mt-3">
                        <h6><i class="fas fa-chart-bar me-2"></i>Estadísticas</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-dot-circle me-2 text-primary"></i>ID del Acceso: <?= $acceso['id'] ?></li>
                            <li><i class="fas fa-dot-circle me-2 text-info"></i>ID del Rol: <?= $acceso['id_rol'] ?></li>
                            <li><i class="fas fa-dot-circle me-2 text-success"></i>ID del Menú: <?= $acceso['id_menu'] ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCambiarEstadoLabel">Confirmar Cambio de Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea cambiar el estado de este acceso?</p>
                <p class="text-muted">
                    El acceso será <span id="nuevoEstadoTexto"></span>.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarCambioEstado">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este acceso?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let accesoId = <?= $acceso['id'] ?>;
    let estadoActual = <?= $acceso['estado'] ?>;

    // Cambiar estado del acceso
    window.cambiarEstado = function(id) {
        const nuevoEstado = estadoActual == 1 ? 0 : 1;
        const textoEstado = nuevoEstado == 1 ? 'activado' : 'desactivado';
        
        document.getElementById('nuevoEstadoTexto').textContent = textoEstado;
        
        const modal = new bootstrap.Modal(document.getElementById('modalCambiarEstado'));
        modal.show();
    };

    // Confirmar cambio de estado
    document.getElementById('btnConfirmarCambioEstado').addEventListener('click', function() {
        fetch(`<?= base_url('acceso/cambiarEstado') ?>/${accesoId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                mostrarAlerta(data.message, 'error');
            }
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado'));
            modal.hide();
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al cambiar el estado del acceso', 'error');
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado'));
            modal.hide();
        });
    });

    // Eliminar acceso
    window.eliminarAcceso = function(id) {
        const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
        modal.show();
    };

    // Confirmar eliminación
    document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
        fetch(`<?= base_url('acceso/delete') ?>/${accesoId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                
                // Redirigir a la lista después de un breve delay
                setTimeout(() => {
                    window.location.href = '<?= base_url('acceso') ?>';
                }, 1500);
            } else {
                mostrarAlerta(data.message, 'error');
            }
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminar'));
            modal.hide();
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al eliminar el acceso', 'error');
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminar'));
            modal.hide();
        });
    });

    // Función para mostrar alertas
    function mostrarAlerta(mensaje, tipo) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass} me-2"></i>
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Insertar alerta al inicio del container
        const container = document.querySelector('.container-fluid');
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
