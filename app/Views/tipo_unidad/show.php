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
                    <p class="text-muted mb-0">Información detallada del tipo de unidad</p>
                </div>
                <div>
                    <a href="<?= base_url('tipo-unidad') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <a href="<?= base_url('tipo-unidad/edit/' . $tipoUnidad['id']) ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Información principal -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Información General
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-hashtag"></i> ID
                                </label>
                                <div class="form-control-plaintext">
                                    <strong class="h5"><?= $tipoUnidad['id'] ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-toggle-on"></i> Estado
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge <?= $tipoUnidad['estado'] === 'ACTIVO' ? 'bg-success' : 'bg-secondary' ?> fs-6">
                                        <i class="fas <?= $tipoUnidad['estado'] === 'ACTIVO' ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                        <?= $tipoUnidad['estado'] ?>
                                    </span>
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
                                <div class="form-control-plaintext">
                                    <h4 class="text-primary"><?= esc($tipoUnidad['descripcion']) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de auditoría -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Información de Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-calendar-plus"></i> Fecha de Registro
                                </label>
                                <div class="form-control-plaintext">
                                    <strong><?= date('d/m/Y H:i:s', strtotime($tipoUnidad['fechaRegistra'])) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-calendar-edit"></i> Última Actualización
                                </label>
                                <div class="form-control-plaintext">
                                    <strong><?= date('d/m/Y H:i:s', strtotime($tipoUnidad['fechaActualiza'])) ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-user-plus"></i> Usuario Creador
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-info">
                                        ID: <?= $tipoUnidad['UsuarioCrea'] ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-user-edit"></i> Usuario Editor
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-warning">
                                        ID: <?= $tipoUnidad['UsuarioEdita'] ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="col-lg-4">
            <!-- Acciones rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('tipo-unidad/edit/' . $tipoUnidad['id']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Tipo de Unidad
                        </a>
                        
                        <button class="btn <?= $tipoUnidad['estado'] === 'ACTIVO' ? 'btn-secondary' : 'btn-success' ?> btn-cambiar-estado"
                                data-id="<?= $tipoUnidad['id'] ?>"
                                data-estado="<?= $tipoUnidad['estado'] === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO' ?>">
                            <i class="fas <?= $tipoUnidad['estado'] === 'ACTIVO' ? 'fa-times' : 'fa-check' ?>"></i>
                            Cambiar a <?= $tipoUnidad['estado'] === 'ACTIVO' ? 'Inactivo' : 'Activo' ?>
                        </button>
                        
                        <hr>
                        
                        <button class="btn btn-danger btn-eliminar"
                                data-id="<?= $tipoUnidad['id'] ?>"
                                data-descripcion="<?= esc($tipoUnidad['descripcion']) ?>">
                            <i class="fas fa-trash"></i> Eliminar Tipo de Unidad
                        </button>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-clock fa-3x text-info mb-2"></i>
                            <h6 class="text-muted">Días desde creación</h6>
                            <h4 class="text-primary">
                                <?php
                                $fechaCreacion = new DateTime($tipoUnidad['fechaRegistra']);
                                $fechaActual = new DateTime();
                                $diferencia = $fechaActual->diff($fechaCreacion);
                                echo $diferencia->days;
                                ?>
                            </h4>
                        </div>
                        
                        <div class="mb-3">
                            <i class="fas fa-edit fa-2x text-warning mb-2"></i>
                            <h6 class="text-muted">Última modificación</h6>
                            <p class="text-dark">
                                <?php
                                $fechaActualizacion = new DateTime($tipoUnidad['fechaActualiza']);
                                $diferencia = $fechaActual->diff($fechaActualizacion);
                                
                                if ($diferencia->days > 0) {
                                    echo "Hace {$diferencia->days} días";
                                } elseif ($diferencia->h > 0) {
                                    echo "Hace {$diferencia->h} horas";
                                } elseif ($diferencia->i > 0) {
                                    echo "Hace {$diferencia->i} minutos";
                                } else {
                                    echo "Hace unos segundos";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ayuda -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-question-circle"></i> Ayuda
                    </h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-info-circle text-info"></i> Los tipos de unidad definen las medidas utilizadas en el sistema</li>
                            <li><i class="fas fa-edit text-warning"></i> Puede editar la información en cualquier momento</li>
                            <li><i class="fas fa-toggle-on text-success"></i> Solo los tipos activos están disponibles para uso</li>
                            <li><i class="fas fa-exclamation-triangle text-danger"></i> Eliminar un tipo puede afectar registros existentes</li>
                        </ul>
                    </small>
                </div>
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
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este tipo de unidad?</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                <div class="alert alert-info">
                    <strong>Tipo de Unidad:</strong> <span id="tipo-eliminar-nombre"></span>
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
    let tipoIdEliminar = null;

    // Event listeners para botones
    document.addEventListener('click', function(e) {
        // Cambiar estado
        if (e.target.closest('.btn-cambiar-estado')) {
            const btn = e.target.closest('.btn-cambiar-estado');
            const id = btn.getAttribute('data-id');
            const nuevoEstado = btn.getAttribute('data-estado');

            if (confirm(`¿Está seguro que desea cambiar el estado a ${nuevoEstado}?`)) {
                cambiarEstado(id, nuevoEstado);
            }
        }

        // Eliminar
        if (e.target.closest('.btn-eliminar')) {
            const btn = e.target.closest('.btn-eliminar');
            tipoIdEliminar = btn.getAttribute('data-id');
            const descripcion = btn.getAttribute('data-descripcion');
            
            const nombreElement = document.getElementById('tipo-eliminar-nombre');
            if (nombreElement) {
                nombreElement.textContent = descripcion;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('modal-eliminar'));
            modal.show();
        }
    });

    // Confirmar eliminación
    const btnConfirmarEliminar = document.getElementById('btn-confirmar-eliminar');
    if (btnConfirmarEliminar) {
        btnConfirmarEliminar.addEventListener('click', function() {
            if (tipoIdEliminar) {
                eliminarTipo(tipoIdEliminar);
            }
        });
    }

    // Función para cambiar estado
    function cambiarEstado(id, nuevoEstado) {
        fetch('<?= base_url('tipo-unidad/cambiarEstado') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `id=${id}&estado=${nuevoEstado}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                mostrarAlerta('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('error', 'Error al cambiar el estado');
        });
    }

    // Función para eliminar tipo
    function eliminarTipo(id) {
        fetch(`<?= base_url('tipo-unidad/delete') ?>/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modal-eliminar'));
            if (modal) modal.hide();
            
            if (data.success) {
                mostrarAlerta('success', data.message);
                // Redirigir al índice después de un breve delay
                setTimeout(() => {
                    window.location.href = '<?= base_url('tipo-unidad') ?>';
                }, 1500);
            } else {
                mostrarAlerta('error', data.message);
            }
            tipoIdEliminar = null;
        })
        .catch(error => {
            console.error('Error:', error);
            const modal = bootstrap.Modal.getInstance(document.getElementById('modal-eliminar'));
            if (modal) modal.hide();
            mostrarAlerta('error', 'Error al eliminar el tipo de unidad');
            tipoIdEliminar = null;
        });
    }

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const icon = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alerta = document.createElement('div');
        alerta.className = `alert ${alertClass} alert-dismissible fade show`;
        alerta.setAttribute('role', 'alert');
        alerta.innerHTML = `
            <i class="fas ${icon}"></i> ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertBefore(alerta, container.firstChild);
            
            // Scroll hacia arriba para mostrar la alerta
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Auto-hide después de 5 segundos
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.remove();
            }
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
