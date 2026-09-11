<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $page_title ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('vehiculos') ?>">Vehículos</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>"><?= $vehiculo['placa'] ?></a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Editar Vehículo - <?= $vehiculo['placa'] ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form id="formVehiculo" action="<?= base_url('vehiculos/update/' . $vehiculo['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <!-- Información básica del vehículo -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-info-circle me-2"></i>Datos del Vehículo
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="placa" class="form-label">
                                    Placa <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('placa') ? 'is-invalid' : '' ?>" 
                                       id="placa" name="placa" value="<?= old('placa', $vehiculo['placa']) ?>" 
                                       placeholder="Ej: ABC-123" maxlength="20" required>
                                <div class="invalid-feedback" id="error_placa">
                                    <?= isset($validation) ? $validation->getError('placa') : '' ?>
                                </div>
                                <div class="form-text">Ingrese la placa del vehículo</div>
                            </div>
                            <div class="col-md-6">
                                <label for="marca" class="form-label">
                                    Marca <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('marca') ? 'is-invalid' : '' ?>" 
                                       id="marca" name="marca" value="<?= old('marca', $vehiculo['marca']) ?>" 
                                       placeholder="Ej: Toyota" maxlength="50" required>
                                <div class="invalid-feedback" id="error_marca">
                                    <?= isset($validation) ? $validation->getError('marca') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="modelo" class="form-label">
                                    Modelo <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('modelo') ? 'is-invalid' : '' ?>" 
                                       id="modelo" name="modelo" value="<?= old('modelo', $vehiculo['modelo']) ?>" 
                                       placeholder="Ej: Corolla" maxlength="50" required>
                                <div class="invalid-feedback" id="error_modelo">
                                    <?= isset($validation) ? $validation->getError('modelo') : '' ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="anio" class="form-label">
                                    Año <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control <?= isset($validation) && $validation->hasError('anio') ? 'is-invalid' : '' ?>" 
                                       id="anio" name="anio" value="<?= old('anio', $vehiculo['anio']) ?>" 
                                       min="1900" max="<?= date('Y') ?>" placeholder="<?= date('Y') ?>" required>
                                <div class="invalid-feedback" id="error_anio">
                                    <?= isset($validation) ? $validation->getError('anio') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="kilometraje" class="form-label">Kilometraje</label>
                                <div class="input-group">
                                    <input type="number" class="form-control <?= isset($validation) && $validation->hasError('kilometraje') ? 'is-invalid' : '' ?>" 
                                           id="kilometraje" name="kilometraje" value="<?= old('kilometraje', $vehiculo['kilometraje']) ?>" 
                                           min="0" placeholder="0">
                                    <span class="input-group-text">km</span>
                                    <div class="invalid-feedback" id="error_kilometraje">
                                        <?= isset($validation) ? $validation->getError('kilometraje') : '' ?>
                                    </div>
                                </div>
                                <div class="form-text">Kilometraje actual del vehículo</div>
                            </div>
                            <div class="col-md-6">
                                <label for="id_conductor" class="form-label">Conductor Asignado</label>
                                <select class="form-select" id="id_conductor" name="id_conductor">
                                    <option value="">Sin asignar</option>
                                    <?php foreach ($conductores as $conductor): ?>
                                        <option value="<?= $conductor['id'] ?>" 
                                                <?= old('id_conductor', $vehiculo['id_conductor']) == $conductor['id'] ? 'selected' : '' ?>>
                                            <?= $conductor['nombre_completo'] ?> - <?= $conductor['dni'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Conductor que manejará este vehículo</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="codigo_unidad" class="form-label">Código de Unidad</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('codigo_unidad') ? 'is-invalid' : '' ?>"
                                       id="codigo_unidad" name="codigo_unidad" value="<?= old('codigo_unidad', $vehiculo['codigo_unidad'] ?? '') ?>"
                                       placeholder="Ej: UNIDAD-001" maxlength="50">
                                <div class="invalid-feedback" id="error_codigo_unidad">
                                    <?= isset($validation) ? $validation->getError('codigo_unidad') : '' ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="disponible" class="form-label">Disponibilidad</label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('disponible') ? 'is-invalid' : '' ?>"
                                        id="disponible" name="disponible">
                                    <option value="1" <?= old('disponible', (string)($vehiculo['disponible'] ?? 1)) === '1' ? 'selected' : '' ?>>Disponible</option>
                                    <option value="0" <?= old('disponible', (string)($vehiculo['disponible'] ?? 1)) === '0' ? 'selected' : '' ?>>No disponible</option>
                                </select>
                                <div class="invalid-feedback" id="error_disponible">
                                    <?= isset($validation) ? $validation->getError('disponible') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="numero_motor" class="form-label">Número de Motor</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('numero_motor') ? 'is-invalid' : '' ?>"
                                       id="numero_motor" name="numero_motor" value="<?= old('numero_motor', $vehiculo['numero_motor'] ?? '') ?>"
                                       placeholder="Ingrese el número de motor" maxlength="100">
                                <div class="invalid-feedback" id="error_numero_motor">
                                    <?= isset($validation) ? $validation->getError('numero_motor') : '' ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="numero_chasis" class="form-label">Número de Chasis</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('numero_chasis') ? 'is-invalid' : '' ?>"
                                       id="numero_chasis" name="numero_chasis" value="<?= old('numero_chasis', $vehiculo['numero_chasis'] ?? '') ?>"
                                       placeholder="Ingrese el número de chasis" maxlength="100">
                                <div class="invalid-feedback" id="error_numero_chasis">
                                    <?= isset($validation) ? $validation->getError('numero_chasis') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_color" class="form-label">Color</label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('id_color') ? 'is-invalid' : '' ?>" id="id_color" name="id_color">
                                    <option value="">Seleccionar color</option>
                                    <?php if (!empty($colores)): ?>
                                        <?php foreach ($colores as $colorId => $colorNombre): ?>
                                            <option value="<?= $colorId ?>" <?= old('id_color', $vehiculo['id_color'] ?? '') == $colorId ? 'selected' : '' ?>>
                                                <?= esc($colorNombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback" id="error_id_color">
                                    <?= isset($validation) ? $validation->getError('id_color') : '' ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="id_tipo_vehiculo" class="form-label">Tipo de Vehículo</label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('id_tipo_vehiculo') ? 'is-invalid' : '' ?>"
                                        id="id_tipo_vehiculo" name="id_tipo_vehiculo">
                                    <option value="">Seleccionar tipo de vehículo</option>
                                    <?php if (!empty($tiposVehiculo)): ?>
                                        <?php foreach ($tiposVehiculo as $tipoId => $tipoNombre): ?>
                                            <option value="<?= $tipoId ?>" <?= old('id_tipo_vehiculo', $vehiculo['id_tipo_vehiculo'] ?? '') == $tipoId ? 'selected' : '' ?>>
                                                <?= esc($tipoNombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback" id="error_id_tipo_vehiculo">
                                    <?= isset($validation) ? $validation->getError('id_tipo_vehiculo') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rendimiento" class="form-label">Rendimiento</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" class="form-control <?= isset($validation) && $validation->hasError('rendimiento') ? 'is-invalid' : '' ?>"
                                           id="rendimiento" name="rendimiento" value="<?= old('rendimiento', $vehiculo['rendimiento'] ?? '') ?>"
                                           placeholder="0.00">
                                    <span class="input-group-text">km/L</span>
                                    <div class="invalid-feedback" id="error_rendimiento">
                                        <?= isset($validation) ? $validation->getError('rendimiento') : '' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="max_combustible" class="form-label">Capacidad Máxima de Combustible</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" class="form-control <?= isset($validation) && $validation->hasError('max_combustible') ? 'is-invalid' : '' ?>"
                                           id="max_combustible" name="max_combustible" value="<?= old('max_combustible', $vehiculo['max_combustible'] ?? '') ?>"
                                           placeholder="0.00">
                                    <span class="input-group-text">L</span>
                                    <div class="invalid-feedback" id="error_max_combustible">
                                        <?= isset($validation) ? $validation->getError('max_combustible') : '' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="idTipoUnidad" class="form-label">Tipo de Unidad</label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('idTipoUnidad') ? 'is-invalid' : '' ?>" 
                                        id="idTipoUnidad" name="idTipoUnidad">
                                    <option value="">Seleccionar tipo de unidad</option>
                                    <?php if (!empty($tiposUnidad)): ?>
                                        <?php foreach ($tiposUnidad as $tipo): ?>
                                            <option value="<?= $tipo['id'] ?>" <?= old('idTipoUnidad', $vehiculo['idTipoUnidad'] ?? '') == $tipo['id'] ? 'selected' : '' ?>>
                                                <?= esc($tipo['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback" id="error_idTipoUnidad">
                                    <?= isset($validation) ? $validation->getError('idTipoUnidad') : '' ?>
                                </div>
                                <div class="form-text">Tipo de unidad vehicular</div>
                            </div>
                            <div class="col-md-6">
                                <label for="idTipoOperacion" class="form-label">Tipo de Operación</label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('idTipoOperacion') ? 'is-invalid' : '' ?>" 
                                        id="idTipoOperacion" name="idTipoOperacion">
                                    <option value="">Seleccionar tipo de operación</option>
                                    <?php if (!empty($tiposOperacion)): ?>
                                        <?php foreach ($tiposOperacion as $tipo): ?>
                                            <option value="<?= $tipo['id'] ?>" <?= old('idTipoOperacion', $vehiculo['idTipoOperacion'] ?? '') == $tipo['id'] ? 'selected' : '' ?>>
                                                <?= esc($tipo['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback" id="error_idTipoOperacion">
                                    <?= isset($validation) ? $validation->getError('idTipoOperacion') : '' ?>
                                </div>
                                <div class="form-text">Tipo de operación que realizará</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="codigo_centro_costo" class="form-label">
                                    Centro de Costo <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('codigo_centro_costo') ? 'is-invalid' : '' ?>" 
                                        id="codigo_centro_costo" name="codigo_centro_costo" required>
                                    <option value="">Seleccionar centro de costo</option>
                                    <?php if (isset($centrosCosto)): ?>
                                        <?php foreach ($centrosCosto as $codigo => $descripcion): ?>
                                            <option value="<?= $codigo ?>" <?= old('codigo_centro_costo', $vehiculo['codigo_centro_costo'] ?? '') == $codigo ? 'selected' : '' ?>>
                                                <?= esc($descripcion) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback" id="error_codigo_centro_costo">
                                    <?= isset($validation) ? $validation->getError('codigo_centro_costo') : '' ?>
                                </div>
                                <div class="form-text">Centro de costo al que pertenece el vehículo</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="compuesto" name="compuesto" value="1" 
                                           <?= old('compuesto', $vehiculo['compuesto'] ?? 0) == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="compuesto">
                                        <strong>Vehículo Compuesto</strong>
                                    </label>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info me-1"></i>
                                    Marque esta opción si el vehículo es compuesto (permite hasta 2 conductores)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_consumo" class="form-label">
                                    <strong>Tipo de Consumo</strong>
                                </label>
                                <select class="form-select" id="tipo_consumo" name="tipo_consumo">
                                    <option value="">Seleccionar tipo</option>
                                    <?php foreach ($tiposConsumo as $ref => $nombre): ?>
                                        <option value="<?= esc($ref) ?>" <?= old('tipo_consumo', $vehiculo['tipo_consumo'] ?? '') == $ref ? 'selected' : '' ?>>
                                            <?= esc($nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info me-1"></i>
                                    Seleccione el tipo de consumo del vehículo
                                </div>
                            </div>
                        </div>

                        <!-- Estado y configuración -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-cog me-2"></i>Estado y Configuración
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="estado" class="form-label">
                                    Estado <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('estado') ? 'is-invalid' : '' ?>" 
                                        id="estado" name="estado" required>
                                    <option value="">Seleccionar estado</option>
                                    <option value="ACTIVO" <?= old('estado', $vehiculo['estado']) == 'ACTIVO' ? 'selected' : '' ?>>
                                        Activo
                                    </option>
                                    <option value="INACTIVO" <?= old('estado', $vehiculo['estado']) == 'INACTIVO' ? 'selected' : '' ?>>
                                        Inactivo
                                    </option>
                                    <option value="EN REPARACION" <?= old('estado', $vehiculo['estado']) == 'EN REPARACION' ? 'selected' : '' ?>>
                                        En Reparación
                                    </option>
                                </select>
                                <div class="invalid-feedback" id="error_estado">
                                    <?= isset($validation) ? $validation->getError('estado') : '' ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="motivo_inactividad" class="form-label">Motivo de Inactividad</label>
                                <textarea class="form-control" id="motivo_inactividad" name="motivo_inactividad" 
                                          rows="3" placeholder="Describe el motivo si el estado no es ACTIVO"><?= old('motivo_inactividad', $vehiculo['motivo_inactividad']) ?></textarea>
                                <div class="form-text">Solo requerido si el estado no es ACTIVO</div>
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
                                                <strong>Código:</strong> <?= $vehiculo['codigo_consecutivo'] ?><br>
                                                <strong>Creado:</strong> <?= date('d/m/Y H:i', strtotime($vehiculo['fechaRegistro'])) ?>
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <strong>Última modificación:</strong> <?= date('d/m/Y H:i', strtotime($vehiculo['fechaUpdate'])) ?>
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
                                    <a href="<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                                        <i class="fas fa-save me-2"></i>Actualizar Vehículo
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
    const estadoOriginal = '<?= $vehiculo['estado'] ?>';
    
    // Validación en tiempo real
    $('#formVehiculo').on('submit', function(e) {
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
                        window.location.href = '<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>';
                    }, 2000);
                } else {
                    // Mostrar errores de validación
                    if (response.errors) {
                        mostrarErrores(response.errors);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al actualizar el vehículo');
                    }
                    $('#btnGuardar').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Actualizar Vehículo');
                }
            },
            error: function(xhr) {
                let mensaje = 'Error de conexión';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }
                mostrarAlerta('error', mensaje);
                $('#btnGuardar').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Actualizar Vehículo');
            }
        });
    });

    // Mostrar/ocultar campo de motivo según el estado
    $('#estado').on('change', function() {
        const estado = $(this).val();
        const motivoField = $('#motivo_inactividad');
        
        if (estado === 'ACTIVO') {
            motivoField.prop('required', false);
            motivoField.closest('.col-md-6').hide();
        } else {
            motivoField.prop('required', true);
            motivoField.closest('.col-md-6').show();
        }
        
        // Si cambió el estado, mostrar alerta
        if (estado !== estadoOriginal) {
            mostrarAlertaCambioEstado(estado);
        }
    });

    // Inicializar visibilidad del campo motivo
    $('#estado').trigger('change');

    // Validaciones en tiempo real
    $('#placa').on('blur', function() {
        const placa = $(this).val().trim();
        const vehiculoId = '<?= $vehiculo['id'] ?>';
        if (placa.length >= 6) {
            verificarPlacaUnica(placa, vehiculoId);
        }
    });

    // Formatear placa en mayúsculas
    $('#placa').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });

    // Formatear marca y modelo
    $('#marca, #modelo').on('input', function() {
        const valor = $(this).val();
        $(this).val(valor.charAt(0).toUpperCase() + valor.slice(1).toLowerCase());
    });

    // Validar año
    $('#anio').on('input', function() {
        const anio = parseInt($(this).val());
        const anioActual = new Date().getFullYear();
        
        if (anio && (anio < 1900 || anio > anioActual)) {
            $(this).addClass('is-invalid');
            $('#error_anio').text(`El año debe estar entre 1900 y ${anioActual}`);
        } else {
            $(this).removeClass('is-invalid');
            $('#error_anio').text('');
        }
    });

    // Validar kilometraje
    $('#kilometraje').on('input', function() {
        const km = parseInt($(this).val());
        const kmOriginal = parseInt('<?= $vehiculo['kilometraje'] ?>');
        
        if (km && km < 0) {
            $(this).addClass('is-invalid');
            $('#error_kilometraje').text('El kilometraje no puede ser negativo');
        } else if (km && km < kmOriginal) {
            $(this).addClass('is-invalid');
            $('#error_kilometraje').text('El kilometraje no puede ser menor al registrado anteriormente (' + kmOriginal.toLocaleString() + ' km)');
        } else {
            $(this).removeClass('is-invalid');
            $('#error_kilometraje').text('');
        }
    });

    // Función para verificar placa única
    function verificarPlacaUnica(placa, vehiculoId) {
        $.ajax({
            url: '<?= base_url('vehiculos/verificarPlaca') ?>',
            type: 'POST',
            data: { placa: placa, id: vehiculoId },
            dataType: 'json',
            success: function(response) {
                if (!response.disponible) {
                    $('#placa').addClass('is-invalid');
                    $('#error_placa').text('Esta placa ya está registrada');
                } else {
                    $('#placa').removeClass('is-invalid');
                    $('#error_placa').text('');
                }
            }
        });
    }

    // Función para mostrar alerta de cambio de estado
    function mostrarAlertaCambioEstado(nuevoEstado) {
        let mensaje = '';
        let tipo = 'info';
        
        if (nuevoEstado === 'ACTIVO') {
            mensaje = 'El vehículo será marcado como ACTIVO y estará disponible para asignaciones.';
            tipo = 'success';
        } else if (nuevoEstado === 'INACTIVO') {
            mensaje = 'El vehículo será marcado como INACTIVO y no estará disponible para asignaciones.';
            tipo = 'warning';
        } else if (nuevoEstado === 'EN REPARACION') {
            mensaje = 'El vehículo será marcado como EN REPARACIÓN y no estará disponible hasta completar el mantenimiento.';
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

    // Inicializar Select2 para centros de costo
    $('#codigo_centro_costo').select2({
        theme: 'bootstrap-5',
        placeholder: 'Buscar centro de costo...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "No se encontraron centros de costo";
            },
            searching: function() {
                return "Buscando...";
            },
            inputTooShort: function() {
                return "Escriba para buscar";
            }
        },
        templateResult: function(option) {
            if (!option.id) {
                return option.text;
            }
            
            // Extraer código y descripción
            var text = option.text;
            var parts = text.split(' - ');
            if (parts.length >= 2) {
                var codigo = parts[0];
                var descripcion = parts.slice(1).join(' - ');
                
                var $result = $(
                    '<div class="d-flex flex-column">' +
                        '<div class="fw-bold text-primary">' + codigo + '</div>' +
                        '<div class="text-muted small">' + descripcion + '</div>' +
                    '</div>'
                );
                return $result;
            }
            
            return $('<div>' + text + '</div>');
        },
        templateSelection: function(option) {
            return option.text || option.id;
        }
    });

    // Manejar eventos de Select2
    $('#codigo_centro_costo').on('select2:select', function(e) {
        // Remover clase de error al seleccionar
        $(this).removeClass('is-invalid');
        $('#error_codigo_centro_costo').text('');
    });

    $('#codigo_centro_costo').on('select2:clear', function(e) {
        // Agregar clase de error si es requerido y se limpia
        if ($(this).prop('required')) {
            $(this).addClass('is-invalid');
            $('#error_codigo_centro_costo').text('Este campo es requerido');
        }
    });

    // Limpiar errores al escribir
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});
</script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Estilos personalizados para Select2 */
.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(2.25rem + 2px);
    border-radius: 10px;
}

.select2-container--bootstrap-5 .select2-selection--single {
    padding: 0.375rem 0.75rem;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding: 0;
    line-height: 1.5;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    height: calc(2.25rem + 2px);
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.select2-container--bootstrap-5 .select2-dropdown {
    border-radius: 10px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.select2-container--bootstrap-5 .select2-results__option--highlighted {
    background-color: #0d6efd;
    color: white;
}

/* Estado de error */
.is-invalid + .select2-container--bootstrap-5 .select2-selection {
    border-color: #dc3545;
}
</style>

<?= $this->endSection() ?>
