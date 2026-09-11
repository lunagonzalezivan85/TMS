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
                    <li class="breadcrumb-item"><a href="<?= base_url('tipo-motivo-combustible/show/' . $tipo['id']) ?>"><?= esc($tipo['descripcion']) ?></a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('tipo-motivo-combustible/show/' . $tipo['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Editar Tipo - <?= esc($tipo['descripcion']) ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form id="formTipo" action="<?= base_url('tipo-motivo-combustible/update/' . $tipo['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="descripcion" class="form-label">
                                    Descripción <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('descripcion') ? 'is-invalid' : '' ?>" 
                                       id="descripcion" name="descripcion" value="<?= old('descripcion', $tipo['descripcion']) ?>" 
                                       placeholder="Ej: Mantenimiento preventivo" maxlength="255" required>
                                <div class="invalid-feedback" id="error_descripcion">
                                    <?= isset($validation) ? $validation->getError('descripcion') : '' ?>
                                </div>
                                <div class="form-text">Ingrese una descripción clara del tipo de motivo</div>
                            </div>
                            <div class="col-md-4">
                                <label for="estado" class="form-label">
                                    Estado <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="">Seleccionar estado</option>
                                    <option value="1" <?= ($tipo['estado'] == 1) ? 'selected' : '' ?>>ACTIVO</option>
                                    <option value="0" <?= ($tipo['estado'] == 0) ? 'selected' : '' ?>>INACTIVO</option>
                                </select>
                                <div class="invalid-feedback" id="error_estado">
                                    <?= isset($validation) ? $validation->getError('estado') : '' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Información de auditoría -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-light">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Información de Auditoría
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <strong>ID:</strong> <?= $tipo['id'] ?><br>
                                                <strong>Creado por:</strong> <?= esc($tipo['usuario_crea_nombre'] ?? 'N/A') ?><br>
                                                <strong>Fecha de registro:</strong> <?= date('d/m/Y H:i', strtotime($tipo['fecha_registro'])) ?>
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <?php if ($tipo['fecha_actualiza']): ?>
                                                <strong>Última modificación:</strong> <?= date('d/m/Y H:i', strtotime($tipo['fecha_actualiza'])) ?><br>
                                                <strong>Modificado por:</strong> <?= esc($tipo['usuario_edita_nombre'] ?? 'N/A') ?>
                                                <?php else: ?>
                                                <strong>Sin modificaciones</strong>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('tipo-motivo-combustible/show/' . $tipo['id']) ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                                        <i class="fas fa-save me-2"></i>Actualizar Tipo
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
    // Estado original para detectar cambios
    const estadoOriginal = '<?= $tipo['estado'] ?>';
    
    // Validación en tiempo real
    $('#formTipo').on('submit', function(e) {
        e.preventDefault();
        
        // Limpiar errores previos
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Deshabilitar botón de envío
        $('#btnGuardar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...');
        
        // Enviar formulario
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Mostrar mensaje de éxito
                    mostrarAlerta('success', response.message);
                    
                    // Redireccionar después de 2 segundos
                    setTimeout(function() {
                        window.location.href = '<?= base_url('tipo-motivo-combustible/show/' . $tipo['id']) ?>';
                    }, 2000);
                } else {
                    // Mostrar errores de validación
                    if (response.errors) {
                        mostrarErrores(response.errors);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al actualizar el tipo');
                    }
                    $('#btnGuardar').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Actualizar Tipo');
                }
            },
            error: function(xhr) {
                let mensaje = 'Error de conexión';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }
                mostrarAlerta('error', mensaje);
                $('#btnGuardar').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Actualizar Tipo');
            }
        });
    });

    // Mostrar alerta de cambio de estado
    $('#estado').on('change', function() {
        const estado = $(this).val();
        
        if (estado !== estadoOriginal) {
            mostrarAlertaCambioEstado(estado);
        } else {
            $('.alert-cambio-estado').remove();
        }
    });

    // Validar descripción única
    $('#descripcion').on('blur', function() {
        const descripcion = $(this).val().trim();
        const tipoId = '<?= $tipo['id'] ?>';
        if (descripcion.length >= 3) {
            verificarDescripcionUnica(descripcion, tipoId);
        }
    });

    // Formatear descripción
    $('#descripcion').on('input', function() {
        const valor = $(this).val();
        $(this).val(valor.charAt(0).toUpperCase() + valor.slice(1));
    });

    // Función para verificar descripción única
    function verificarDescripcionUnica(descripcion, id) {
        $.ajax({
            url: '<?= base_url('tipo-motivo-combustible/verificarDescripcion') ?>',
            type: 'POST',
            data: { descripcion: descripcion, id: id },
            dataType: 'json',
            success: function(response) {
                if (!response.disponible) {
                    $('#descripcion').addClass('is-invalid');
                    $('#error_descripcion').text('Ya existe un tipo con esta descripción');
                } else {
                    $('#descripcion').removeClass('is-invalid');
                    $('#error_descripcion').text('');
                }
            }
        });
    }

    // Función para mostrar alerta de cambio de estado
    function mostrarAlertaCambioEstado(nuevoEstado) {
        let mensaje = '';
        let tipo = 'info';
        
        if (nuevoEstado === 'ACTIVO') {
            mensaje = 'El tipo será marcado como ACTIVO y estará disponible para su uso.';
            tipo = 'success';
        } else if (nuevoEstado === 'INACTIVO') {
            mensaje = 'El tipo será marcado como INACTIVO y no estará disponible para su uso.';
            tipo = 'warning';
        }
        
        if (mensaje) {
            const alertClass = tipo === 'success' ? 'alert-success' : tipo === 'warning' ? 'alert-warning' : 'alert-info';
            const icon = tipo === 'success' ? 'check-circle' : tipo === 'warning' ? 'exclamation-triangle' : 'info-circle';
            
            // Remover alertas previas de cambio de estado
            $('.alert-cambio-estado').remove();
            
            const alerta = `
                <div class="alert ${alertClass} alert-dismissible fade show alert-cambio-estado" role="alert">
                    <i class="fas fa-${icon} me-2"></i>${mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('#estado').closest('.row').after(alerta);
        }
    }

    // Función para mostrar errores de validación
    function mostrarErrores(errores) {
        $.each(errores, function(campo, mensaje) {
            $('#' + campo).addClass('is-invalid');
            $('#error_' + campo).text(mensaje);
        });
    }

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
        
        $('.container-fluid').prepend(alerta);
        
        // Scroll hacia arriba para ver la alerta
        $('html, body').animate({ scrollTop: 0 }, 300);
        
        // Auto-hide después de 5 segundos para alertas de error
        if (tipo === 'error') {
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }
    }

    // Limpiar errores al escribir
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});
</script>
<?= $this->endSection() ?>
