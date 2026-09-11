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
                    <li class="breadcrumb-item active">Nuevo Tipo</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('tipo-motivo-combustible') ?>" class="btn btn-secondary">
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
                        <i class="fas fa-plus me-2"></i>Nuevo Tipo de Motivo de Combustible
                    </h6>
                </div>
                <div class="card-body">
                    <form id="formTipo" action="<?= base_url('tipo-motivo-combustible/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="descripcion" class="form-label">
                                    Descripción <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('descripcion') ? 'is-invalid' : '' ?>" 
                                       id="descripcion" name="descripcion" value="<?= old('descripcion') ?>" 
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
                                    <option value="1">ACTIVO</option>
                                    <option value="0">INACTIVO</option>
                                </select>
                                <div class="invalid-feedback" id="error_estado">
                                    <?= isset($validation) ? $validation->getError('estado') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Información:</strong> Los tipos de motivo de combustible se utilizan para categorizar 
                                    las razones por las cuales se solicita combustible para los vehículos.
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('tipo-motivo-combustible') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                                        <i class="fas fa-save me-2"></i>Guardar Tipo
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
    // Validación en tiempo real
    $('#formTipo').on('submit', function(e) {
        e.preventDefault();
        
        // Limpiar errores previos
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Deshabilitar botón de envío
        $('#btnGuardar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');
        
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
                        window.location.href = '<?= base_url('tipo-motivo-combustible') ?>';
                    }, 2000);
                } else {
                    // Mostrar errores de validación
                    if (response.errors) {
                        mostrarErrores(response.errors);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al crear el tipo');
                    }
                    $('#btnGuardar').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar Tipo');
                }
            },
            error: function(xhr) {
                let mensaje = 'Error de conexión';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }
                mostrarAlerta('error', mensaje);
                $('#btnGuardar').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar Tipo');
            }
        });
    });

    // Validar descripción única
    $('#descripcion').on('blur', function() {
        const descripcion = $(this).val().trim();
        if (descripcion.length >= 3) {
            verificarDescripcionUnica(descripcion);
        }
    });

    // Formatear descripción
    $('#descripcion').on('input', function() {
        const valor = $(this).val();
        $(this).val(valor.charAt(0).toUpperCase() + valor.slice(1));
    });

    // Función para verificar descripción única
    function verificarDescripcionUnica(descripcion) {
        $.ajax({
            url: '<?= base_url('tipo-motivo-combustible/verificarDescripcion') ?>',
            type: 'POST',
            data: { descripcion: descripcion },
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
