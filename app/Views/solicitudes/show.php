<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    <?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
        <h1 class="h2">
            <i class="fas fa-file-alt me-2"></i><?= $title ?>
        </h1>
        <div class="btn-group">
            <a href="<?= base_url('solicitudes') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
            </a>
            <!-- Debug info -->
            <div class="alert alert-warning p-2 me-2" style="display: none;">
                Estado actual: <?= $solicitud['estado'] ?>
                Condición: <?= !in_array($solicitud['estado'], ['CERRADA', 'CANCELADA']) ? 'true' : 'false' ?>
            </div>
          
            <button class="btn btn-secondary" disabled>
                    <i class="fas fa-lock me-1"></i> No se puede cambiar estado
           </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Tarjeta de Información Principal -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información de la Solicitud
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Código de Solicitud</h6>
                            <p class="h5"><?= $solicitud['codigo_consecutivo'] ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Estado</h6>
                            <?= $this->include('partials/badge_estado', ['estado' => $solicitud['estado']]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Fecha de Solicitud</h6>
                            <p><?= date('d/m/Y H:i', strtotime($solicitud['fechaSolicitud'])) ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Última Actualización</h6>
                            <p><?= date('d/m/Y H:i', strtotime($solicitud['fechaUpdate'])) ?></p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">
                        <i class="fas fa-car me-2"></i>Datos del Vehículo
                    </h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted">Placa</h6>
                            <p><?= $solicitud['vehiculo']['placa'] ?? 'N/A' ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Marca</h6>
                            <p><?= $solicitud['vehiculo']['marca'] ?? 'N/A' ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Modelo</h6>
                            <p><?= $solicitud['vehiculo']['modelo'] ?? 'N/A' ?></p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">
                        <i class="fas fa-clipboard-list me-2"></i>Detalles de la Solicitud
                    </h5>
                    <div class="mb-3">
                        <h6 class="text-muted">Tipo de Problema</h6>
                        <p><?= $solicitud['tipo_problema']['nombre'] ?? 'No especificado' ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Descripción</h6>
                        <p class="text-justify"><?= nl2br(htmlspecialchars($solicitud['descripcion'])) ?></p>
                    </div>

                    <?php if (!empty($solicitud['observaciones'])): ?>
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-sticky-note me-2"></i>Observaciones
                            </h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($solicitud['observaciones'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial de la Solicitud -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Historial de la Solicitud
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php
                        // Ordenar el historial por fecha (más reciente primero)
                        $historial = [
                            [
                                'fecha' => $solicitud['fechaUpdate'],
                                'accion' => 'Solicitud ' . strtolower($solicitud['estado']),
                                'usuario' => $solicitud['usuarioEdita']
                            ],
                            [
                                'fecha' => $solicitud['fechaRegistro'],
                                'accion' => 'Solicitud creada',
                                'usuario' => $solicitud['usuarioCrea']
                            ]
                        ];
                        
                        // Ordenar por fecha descendente
                        usort($historial, function($a, $b) {
                            return strtotime($b['fecha']) - strtotime($a['fecha']);
                        });
                        
                        foreach ($historial as $item): 
                            $fecha = new DateTime($item['fecha']);
                        ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= $item['accion'] ?></h6>
                                    <small class="text-muted"><?= $fecha->format('d/m/Y H:i') ?></small>
                                </div>
                                <p class="mb-1">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i> 
                                        <?= $this->usuarioModel->getNombreCompleto($item['usuario']) ?: 'Usuario #' . $item['usuario'] ?>
                                    </small>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Información del Solicitante -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tie me-2"></i>Solicitante
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar avatar-xl bg-primary text-white rounded-circle mb-2">
                            <span class="h4 mb-0">
                                <?= strtoupper(substr($solicitud['solicitante']['nombre'] ?? '?', 0, 1)) ?>
                            </span>
                        </div>
                        <h5 class="mb-1">
                            <?= ($solicitud['solicitante']['nombre'] ?? 'N/A') . ' ' . ($solicitud['solicitante']['apellido'] ?? '') ?>
                        </h5>
                        <p class="text-muted mb-0">
                            <?= $solicitud['solicitante']['email'] ?? 'N/A' ?>
                        </p>
                    </div>
                    <div class="d-grid">
                        <a href="mailto:<?= $solicitud['solicitante']['email'] ?? '#' ?>" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-1"></i> Enviar Correo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('historial-orden-trabajo/create/' . $solicitud['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-exchange-alt me-1"></i> Cambiar Estado
                        </a>
                        <?php if ($solicitud['estado'] === 'PENDIENTE'): ?>
                            <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalCambiarEstado" data-estado="EN_PROCESO">
                                <i class="fas fa-play me-1"></i> Iniciar Proceso
                            </button>
                            <button type="button" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target="#modalCambiarEstado" data-estado="CANCELADA">
                                <i class="fas fa-times me-1"></i> Cancelar Solicitud
                            </button>
                        <?php elseif ($solicitud['estado'] === 'EN_PROCESO'): ?>
                            <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalCambiarEstado" data-estado="CERRADA">
                                <i class="fas fa-check me-1"></i> Marcar como Completada
                            </button>
                            <button type="button" class="btn btn-warning text-white mb-2" data-bs-toggle="modal" data-bs-target="#modalCambiarEstado" data-estado="PENDIENTE">
                                <i class="fas fa-undo me-1"></i> Volver a Pendiente
                            </button>
                        <?php elseif ($solicitud['estado'] === 'CERRADA'): ?>
                            <button type="button" class="btn btn-secondary mb-2" disabled>
                                <i class="fas fa-check-circle me-1"></i> Solicitud Completada
                            </button>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle me-1"></i> 
                                Esta solicitud fue cerrada el <?= date('d/m/Y', strtotime($solicitud['fechaUpdate'])) ?>
                            </p>
                        <?php else: // CANCELADA ?>
                            <button type="button" class="btn btn-secondary mb-2" disabled>
                                <i class="fas fa-ban me-1"></i> Solicitud Cancelada
                            </button>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle me-1"></i> 
                                Esta solicitud fue cancelada el <?= date('d/m/Y', strtotime($solicitud['fechaUpdate'])) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Documentos Adjuntos -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-paperclip me-2"></i>Documentos Adjuntos
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($solicitud['documentos'])): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($solicitud['documentos'] as $documento): ?>
                                <a href="<?= base_url('uploads/solicitudes/' . $documento['nombre_archivo']) ?>" 
                                   target="_blank" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <i class="fas fa-file-<?= $this->getFileIcon($documento['tipo_archivo']) ?> me-2"></i>
                                            <?= $documento['nombre_original'] ?>
                                        </h6>
                                        <small class="text-muted"><?= $this->formatBytes($documento['tamanio']) ?></small>
                                    </div>
                                    <small class="text-muted">
                                        Subido el <?= date('d/m/Y', strtotime($documento['fecha_subida'])) ?>
                                    </small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-folder-open fa-2x mb-2"></i>
                            <p class="mb-0">No hay documentos adjuntos</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!in_array($solicitud['estado'], ['CERRADA', 'CANCELADA'])): ?>
                        <hr>
                        <form id="formSubirDocumento" action="<?= base_url('solicitudes/subirDocumento/' . $solicitud['id']) ?>" 
                              method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="documento" class="form-label">Agregar Documento</label>
                                <input class="form-control" type="file" id="documento" name="documento" required>
                                <div class="form-text">Formatos permitidos: PDF, JPG, PNG (Máx. 5MB)</div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-upload me-1"></i> Subir Documento
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCambiarEstado" action="<?= base_url('solicitudes/updateStatus/' . $solicitud['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCambiarEstadoLabel">Cambiar Estado de la Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="estado" id="nuevo_estado" value="">
                    
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentario (Opcional)</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3" 
                                  placeholder="Agregue un comentario sobre el cambio de estado..."></textarea>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="estadoMensaje">Seleccione una acción para ver más detalles.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Configurar el modal de cambio de estado
        $('#modalCambiarEstado').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const nuevoEstado = button.data('estado');
            const form = $('#formCambiarEstado');
            const mensaje = $('#estadoMensaje');
            
            // Restablecer el formulario
            form[0].reset();
            
            if (nuevoEstado) {
                // Configurar según el estado seleccionado
                switch(nuevoEstado) {
                    case 'EN_PROCESO':
                        mensaje.html('La solicitud pasará a estado <strong>En Proceso</strong>. ¿Desea continuar?');
                        break;
                    case 'CERRADA':
                        mensaje.html('La solicitud se marcará como <strong>Completada</strong>. ¿Desea continuar?');
                        break;
                    case 'CANCELADA':
                        mensaje.html('La solicitud se <strong>cancelará</strong>. Esta acción no se puede deshacer. ¿Desea continuar?');
                        break;
                    case 'PENDIENTE':
                        mensaje.html('La solicitud volverá a estado <strong>Pendiente</strong>. ¿Desea continuar?');
                        break;
                }
                
                // Establecer el valor del estado oculto
                $('#nuevo_estado').val(nuevoEstado);
            }
        });
        
        // Manejar el envío del formulario de cambio de estado
        $('#formCambiarEstado').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const formData = form.serialize();
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    // Mostrar indicador de carga
                    form.find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
                },
                success: function(response) {
                    if (response.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            // Recargar la página para ver los cambios
                            window.location.reload();
                        });
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Ocurrió un error al actualizar el estado.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la solicitud. Por favor intente nuevamente.',
                        confirmButtonText: 'Aceptar'
                    });
                },
                complete: function() {
                    // Restaurar botón
                    form.find('button[type="submit"]')
                        .prop('disabled', false)
                        .html('<i class="fas fa-save me-1"></i> Guardar Cambios');
                }
            });
        });
        
        // Manejar el envío del formulario de subida de documentos
        $('#formSubirDocumento').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const formData = new FormData(this);
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    // Mostrar indicador de carga
                    form.find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Subiendo...');
                },
                success: function(response) {
                    if (response.success) {
                        // Mostrar mensaje de éxito y recargar
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        // Mostrar mensaje de error
                        let errorMessage = response.message || 'Ocurrió un error al subir el documento.';
                        
                        if (response.errors) {
                            errorMessage = '';
                            for (const field in response.errors) {
                                errorMessage += response.errors[field] + '<br>';
                            }
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
                            confirmButtonText: 'Entendido'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al subir el documento. Por favor intente nuevamente.',
                        confirmButtonText: 'Aceptar'
                    });
                },
                complete: function() {
                    // Restaurar botón
                    form.find('button[type="submit"]')
                        .prop('disabled', false)
                        .html('<i class="fas fa-upload me-1"></i> Subir Documento');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
