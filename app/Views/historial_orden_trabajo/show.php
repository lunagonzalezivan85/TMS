<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Detalles del Historial #<?= $historial['id'] ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detalles del Historial #<?= $historial['id'] ?></h4>
                    <div class="d-flex">
                        <a href="<?= site_url('historial-orden-trabajo') ?>" class="btn btn-secondary btn-sm me-2">
                            <i class="fas fa-arrow-left"></i> Volver al listado
                        </a>
                        <a href="<?= site_url('historial-orden-trabajo/create/' . $historial['id_solicitud']) ?>" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-history"></i> Ver historial completo
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Información del Historial</h5>
                                </div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-4">ID:</dt>
                                        <dd class="col-sm-8"><?= $historial['id'] ?></dd>
                                        
                                        <dt class="col-sm-4">Solicitud:</dt>
                                        <dd class="col-sm-8">
                                            <?php if (!empty($solicitud['codigo_consecutivo'])): ?>
                                                <a href="<?= site_url('solicitudes/show/' . $historial['id_solicitud']) ?>">
                                                    <?= $solicitud['codigo_consecutivo'] ?>
                                                </a>
                                            <?php else: ?>
                                                Solicitud #<?= $historial['id_solicitud'] ?>
                                            <?php endif; ?>
                                        </dd>
                                        
                                        <dt class="col-sm-4">Vehículo:</dt>
                                        <dd class="col-sm-8">
                                            <?php if (!empty($solicitud['id_vehiculo'])): ?>
                                                <a href="<?= site_url('vehiculos/show/' . $solicitud['id_vehiculo']) ?>">
                                                    <?= $solicitud['placa'] ?? 'Vehículo #' . $solicitud['id_vehiculo'] ?>
                                                </a>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </dd>
                                        
                                        <dt class="col-sm-4">Estado:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-<?= getEstadoBadgeClass($historial['estado']) ?>">
                                                <?= $historial['estado'] ?>
                                            </span>
                                        </dd>
                                        
                                        <dt class="col-sm-4">Fecha de Registro:</dt>
                                        <dd class="col-sm-8">
                                            <?= date('d/m/Y H:i:s', strtotime($historial['fecha_registro'])) ?>
                                        </dd>
                                        
                                        <dt class="col-sm-4">Registrado por:</dt>
                                        <dd class="col-sm-8">
                                            Usuario #<?= $historial['usuario_registra'] ?>
                                            <?php if (!empty($historial['usuario_nombre'])): ?>
                                                (<?= $historial['usuario_nombre'] ?>)
                                            <?php endif; ?>
                                        </dd>
                                        
                                        <?php if (!empty($historial['referencia'])): ?>
                                            <dt class="col-sm-4">Referencia:</dt>
                                            <dd class="col-sm-8">
                                                <?= $historial['referencia'] ?>
                                            </dd>
                                        <?php endif; ?>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Comentario</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($historial['comentario'])): ?>
                                        <div class="p-3 bg-light rounded">
                                            <?= nl2br(htmlspecialchars($historial['comentario'])) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-muted">
                                            <em>No se registraron comentarios para este cambio de estado.</em>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($historial['referencia'])): ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5>Documento de Referencia</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> 
                                            Referencia: <strong><?= $historial['referencia'] ?></strong>
                                        </div>
                                        <!-- Aquí podrías agregar un visor de documentos si es necesario -->
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Acciones</h5>
                                </div>
                                <div class="card-body">
                                    <a href="<?= site_url('historial-orden-trabajo') ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Volver al listado
                                    </a>
                                    
                                    <a href="<?= site_url('historial-orden-trabajo/create/' . $historial['id_solicitud'] . '#historial-' . $historial['id'] ) ?>" 
                                       class="btn btn-primary">
                                        <i class="fas fa-history"></i> Ver en el historial completo
                                    </a>
                                    
                                    <?php if (session('user_type') === 'ADMIN'): ?>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Eliminar registro
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<?php if (session('user_type') === 'ADMIN'): ?>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea eliminar este registro de historial?</p>
                    <p class="text-danger">
                        <strong>Advertencia:</strong> Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form action="<?= site_url('historial-orden-trabajo/delete/' . $historial['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Sí, eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Manejar el modal de confirmación de eliminación
    var deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            // Aquí podrías agregar lógica adicional si es necesario
        });
    }
});
</script>
<?= $this->endSection() ?>
