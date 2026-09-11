<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $page_title ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('tipo-motivo-combustible') ?>">Tipos de Motivo</a></li>
                    <li class="breadcrumb-item active"><?= esc($tipo['descripcion']) ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('tipo-motivo-combustible') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <a href="<?= base_url('tipo-motivo-combustible/edit/' . $tipo['id']) ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Información principal -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-gas-pump me-2"></i>Información del Tipo
                    </h6>
                    <?php
                    $estadoClass = $tipo['estado'] == 'ACTIVO' ? 'success' : 'secondary';
                    $estadoIcon = $tipo['estado'] == 'ACTIVO' ? 'check-circle' : 'times-circle';
                    ?>
                    <span class="badge bg-<?= $estadoClass ?> fs-6">
                        <i class="fas fa-<?= $estadoIcon ?> me-1"></i><?= $tipo['estado'] ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold text-muted" width="40%">ID:</td>
                                    <td><?= $tipo['id'] ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Descripción:</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-gas-pump text-primary me-2"></i>
                                            <span class="fw-bold"><?= esc($tipo['descripcion']) ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Estado:</td>
                                    <td>
                                        <span class="badge bg-<?= $estadoClass ?>">
                                            <i class="fas fa-<?= $estadoIcon ?> me-1"></i><?= $tipo['estado'] ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold text-muted" width="50%">Creado por:</td>
                                    <td><?= esc($tipo['usuario_crea_nombre'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Fecha de registro:</td>
                                    <td><?= date('d/m/Y H:i', strtotime($tipo['fecha_registro'])) ?></td>
                                </tr>
                                <?php if ($tipo['fecha_actualiza']): ?>
                                <tr>
                                    <td class="fw-bold text-muted">Última modificación:</td>
                                    <td><?= date('d/m/Y H:i', strtotime($tipo['fecha_actualiza'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Modificado por:</td>
                                    <td><?= esc($tipo['usuario_edita_nombre'] ?? 'N/A') ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral con acciones -->
        <div class="col-xl-4 col-lg-5">
            <!-- Acciones rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('tipo-motivo-combustible/edit/' . $tipo['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar Tipo
                        </a>
                        
                        <?php if ($tipo['estado'] == 'ACTIVO'): ?>
                        <button class="btn btn-warning cambiar-estado" 
                                data-id="<?= $tipo['id'] ?>" 
                                data-estado="INACTIVO">
                            <i class="fas fa-pause me-2"></i>Inactivar Tipo
                        </button>
                        <?php else: ?>
                        <button class="btn btn-success cambiar-estado" 
                                data-id="<?= $tipo['id'] ?>" 
                                data-estado="ACTIVO">
                            <i class="fas fa-play me-2"></i>Activar Tipo
                        </button>
                        <?php endif; ?>

                        <button class="btn btn-danger eliminar-tipo" 
                                data-id="<?= $tipo['id'] ?>" 
                                data-descripcion="<?= esc($tipo['descripcion']) ?>">
                            <i class="fas fa-trash me-2"></i>Eliminar Tipo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información Adicional
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Uso:</strong> Este tipo de motivo se utiliza para categorizar las solicitudes 
                        de combustible en el sistema de gestión vehicular.
                    </div>
                    
                    <?php if ($tipo['estado'] == 'INACTIVO'): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nota:</strong> Este tipo está inactivo y no aparecerá en los formularios 
                        de solicitud de combustible.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body">
                    <input type="hidden" id="tipo_id" name="id" value="<?= $tipo['id'] ?>">
                    <input type="hidden" id="nuevo_estado" name="estado">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="mensaje_cambio_estado"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Cambio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Tipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEliminar">
                <div class="modal-body">
                    <input type="hidden" id="eliminar_id" name="id" value="<?= $tipo['id'] ?>">
                    
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ¿Está seguro que desea eliminar el tipo "<strong><?= esc($tipo['descripcion']) ?></strong>"?
                        <br><br>
                        <strong>Esta acción no se puede deshacer.</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cambiar estado
    document.querySelectorAll('.cambiar-estado').forEach(button => {
        button.addEventListener('click', function() {
            const tipoId = this.dataset.id;
            const nuevoEstado = this.dataset.estado;
            
            document.getElementById('tipo_id').value = tipoId;
            document.getElementById('nuevo_estado').value = nuevoEstado;
            
            let mensaje = '';
            if (nuevoEstado === 'ACTIVO') {
                mensaje = 'El tipo será marcado como ACTIVO y estará disponible para su uso.';
            } else if (nuevoEstado === 'INACTIVO') {
                mensaje = 'El tipo será marcado como INACTIVO y no estará disponible para su uso.';
            }
            
            document.getElementById('mensaje_cambio_estado').textContent = mensaje;
            new bootstrap.Modal(document.getElementById('modalCambiarEstado')).show();
        });
    });

    // Procesar cambio de estado
    document.getElementById('formCambiarEstado').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('<?= base_url('tipo-motivo-combustible/cambiarEstado') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
                mostrarAlerta('success', data.message || 'Estado actualizado correctamente');
                
                // Recargar la página después de 2 segundos
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                mostrarAlerta('error', data.message || 'Error al cambiar el estado');
            }
        })
        .catch(error => {
            mostrarAlerta('error', 'Error de conexión');
        });
    });

    // Eliminar tipo
    document.querySelectorAll('.eliminar-tipo').forEach(button => {
        button.addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('modalEliminar')).show();
        });
    });

    // Procesar eliminación
    document.getElementById('formEliminar').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('eliminar_id').value;
        
        fetch('<?= base_url('tipo-motivo-combustible/delete/') ?>' + id, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
                mostrarAlerta('success', data.message || 'Tipo eliminado exitosamente');
                
                // Redireccionar después de 2 segundos
                setTimeout(() => {
                    window.location.href = '<?= base_url('tipo-motivo-combustible') ?>';
                }, 2000);
            } else {
                mostrarAlerta('error', data.message || 'Error al eliminar el tipo');
            }
        })
        .catch(error => {
            mostrarAlerta('error', 'Error de conexión');
        });
    });

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const icon = tipo === 'success' ? 'check-circle' : 'exclamation-triangle';
        
        const alerta = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${icon} me-2"></i>${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.container-fluid');
        container.insertAdjacentHTML('afterbegin', alerta);
        
        // Scroll hacia arriba para ver la alerta
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Auto-hide después de 5 segundos
        setTimeout(() => {
            const alertElement = document.querySelector('.alert');
            if (alertElement) {
                alertElement.style.opacity = '0';
                setTimeout(() => alertElement.remove(), 300);
            }
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
