<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-eye text-info"></i>
                        <?= $page_title ?>
                    </h1>
                    <p class="text-muted mb-0">Información detallada del tipo de operación</p>
                </div>
                <div>
                    <a href="<?= base_url('tipo-operacion') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <a href="<?= base_url('tipo-operacion/edit/' . $tipoOperacion['id']) ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Información Principal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-hashtag"></i> ID del Registro
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-secondary fs-6"><?= $tipoOperacion['id'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-toggle-on"></i> Estado Actual
                                </label>
                                <div class="form-control-plaintext">
                                    <?php if ($tipoOperacion['estado'] === 'ACTIVO'): ?>
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check-circle"></i> ACTIVO
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary fs-6">
                                            <i class="fas fa-times-circle"></i> INACTIVO
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-tag"></i> Descripción
                                </label>
                                <div class="form-control-plaintext border rounded p-3 bg-light">
                                    <h5 class="mb-0 text-dark"><?= esc($tipoOperacion['descripcion']) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Auditoría -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Información de Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-user-plus"></i> Creado por
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-info"><?= $tipoOperacion['UsuarioCrea'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-calendar-plus"></i> Fecha de Registro
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="text-dark">
                                        <?= date('d/m/Y H:i:s', strtotime($tipoOperacion['fechaRegistra'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($tipoOperacion['UsuarioEdita'] || $tipoOperacion['fechaActualiza']): ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-user-edit"></i> Última modificación por
                                    </label>
                                    <div class="form-control-plaintext">
                                        <?php if ($tipoOperacion['UsuarioEdita']): ?>
                                            <span class="badge bg-warning"><?= $tipoOperacion['UsuarioEdita'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Sin modificaciones</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-calendar-edit"></i> Fecha de Actualización
                                    </label>
                                    <div class="form-control-plaintext">
                                        <?php if ($tipoOperacion['fechaActualiza']): ?>
                                            <span class="text-dark">
                                                <?= date('d/m/Y H:i:s', strtotime($tipoOperacion['fechaActualiza'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">Sin modificaciones</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Panel de Acciones -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs"></i> Acciones Disponibles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Editar -->
                        <a href="<?= base_url('tipo-operacion/edit/' . $tipoOperacion['id']) ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Tipo de Operación
                        </a>

                        <!-- Cambiar Estado -->
                        <?php if ($tipoOperacion['estado'] === 'ACTIVO'): ?>
                            <button type="button" 
                                    class="btn btn-secondary btn-cambiar-estado" 
                                    data-id="<?= $tipoOperacion['id'] ?>" 
                                    data-estado="INACTIVO">
                                <i class="fas fa-toggle-off"></i> Desactivar
                            </button>
                        <?php else: ?>
                            <button type="button" 
                                    class="btn btn-success btn-cambiar-estado" 
                                    data-id="<?= $tipoOperacion['id'] ?>" 
                                    data-estado="ACTIVO">
                                <i class="fas fa-toggle-on"></i> Activar
                            </button>
                        <?php endif; ?>

                        <!-- Eliminar -->
                        <button type="button" 
                                class="btn btn-danger btn-eliminar" 
                                data-id="<?= $tipoOperacion['id'] ?>">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>

                        <hr>

                        <!-- Volver a la lista -->
                        <a href="<?= base_url('tipo-operacion') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Ver Todos los Tipos
                        </a>

                        <!-- Crear nuevo -->
                        <a href="<?= base_url('tipo-operacion/create') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> Crear Nuevo Tipo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Estadísticas de Uso
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-database fa-3x text-muted"></i>
                        </div>
                        <p class="text-muted">
                            Las estadísticas de uso de este tipo de operación se mostrarán aquí cuando esté integrado con otros módulos del sistema.
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Próximamente: Conteo de operaciones realizadas, reportes de uso, etc.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para cambiar estado -->
<div class="modal fade" id="modal-cambiar-estado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle text-warning"></i>
                    Confirmar Cambio de Estado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="mensaje-cambio-estado"></p>
                <p class="text-muted">¿Está seguro que desea realizar este cambio?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning" id="btn-confirmar-cambio-estado">
                    <i class="fas fa-check"></i> Confirmar Cambio
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modal-eliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este tipo de operación?</p>
                <div class="alert alert-danger">
                    <strong>¡Atención!</strong> Esta acción no se puede deshacer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="btn-confirmar-eliminar">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let tipoIdAccion = null;
    let nuevoEstado = null;

    // Cambiar estado
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-cambiar-estado')) {
            const btn = e.target.closest('.btn-cambiar-estado');
            tipoIdAccion = btn.dataset.id;
            nuevoEstado = btn.dataset.estado;
            
            const mensaje = nuevoEstado === 'ACTIVO' 
                ? 'El tipo de operación será activado y estará disponible para su uso.'
                : 'El tipo de operación será desactivado y no estará disponible para nuevas operaciones.';
            
            document.getElementById('mensaje-cambio-estado').textContent = mensaje;
            
            const modal = new bootstrap.Modal(document.getElementById('modal-cambiar-estado'));
            modal.show();
        }
    });

    // Confirmar cambio de estado
    document.getElementById('btn-confirmar-cambio-estado').addEventListener('click', function() {
        if (tipoIdAccion && nuevoEstado) {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cambiando...';
            
            fetch('<?= base_url('tipo-operacion/cambiarEstado') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `id=${tipoIdAccion}&estado=${nuevoEstado}`
            })
            .then(response => response.json())
            .then(data => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal-cambiar-estado'));
                modal.hide();
                
                if (data.success) {
                    mostrarAlerta('success', data.message);
                    // Recargar la página para mostrar los cambios
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarAlerta('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal-cambiar-estado'));
                modal.hide();
                mostrarAlerta('error', 'Error al cambiar el estado');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Cambio';
                tipoIdAccion = null;
                nuevoEstado = null;
            });
        }
    });

    // Eliminar tipo
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-eliminar')) {
            const btn = e.target.closest('.btn-eliminar');
            tipoIdAccion = btn.dataset.id;
            
            const modal = new bootstrap.Modal(document.getElementById('modal-eliminar'));
            modal.show();
        }
    });

    // Confirmar eliminación
    document.getElementById('btn-confirmar-eliminar').addEventListener('click', function() {
        if (tipoIdAccion) {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
            
            fetch(`<?= base_url('tipo-operacion/delete') ?>/${tipoIdAccion}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal-eliminar'));
                modal.hide();
                
                if (data.success) {
                    mostrarAlerta('success', data.message);
                    // Redirigir a la lista después de eliminar
                    setTimeout(() => {
                        window.location.href = '<?= base_url('tipo-operacion') ?>';
                    }, 1500);
                } else {
                    mostrarAlerta('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal-eliminar'));
                modal.hide();
                mostrarAlerta('error', 'Error al eliminar el tipo de operación');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-trash"></i> Eliminar';
                tipoIdAccion = null;
            });
        }
    });

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const icon = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alerta = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${icon}"></i> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alerta);
        
        // Auto-hide después de 5 segundos
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('fade')) {
                    alert.remove();
                }
            });
        }, 5000);
    }

    // Auto-hide alerts existentes después de 5 segundos
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
<?= $this->endSection() ?>
