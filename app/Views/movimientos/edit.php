<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('movimientos') ?>">Movimientos</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('movimientos/show/' . $movimiento['id']) ?>"><?= esc($movimiento['codigo']) ?></a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('movimientos/show/' . $movimiento['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <form id="movimiento-form">
        <input type="hidden" name="id_movimiento" value="<?= $movimiento['id'] ?>">
        
        <div class="row">
            <!-- Información del Movimiento -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle"></i> Información del Movimiento
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo">Código</label>
                                    <input type="text" class="form-control" id="codigo" value="<?= esc($movimiento['codigo']) ?>" readonly>
                                    <small class="text-muted">El código no se puede modificar</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_movimiento">Tipo de Movimiento <span class="text-danger">*</span></label>
                                    <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                                        <option value="">Seleccione un tipo</option>
                                        <?php foreach ($tipos_movimiento as $key => $tipo): ?>
                                            <option value="<?= $key ?>" <?= $movimiento['tipo_movimiento'] == $key ? 'selected' : '' ?>>
                                                <?= esc($tipo) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_movimiento">Fecha del Movimiento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha_movimiento" name="fecha_movimiento" 
                                           value="<?= date('Y-m-d', strtotime($movimiento['fecha_movimiento'])) ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orden_trabajo">Orden de Trabajo (Referencia)</label>
                                    <select class="form-control" id="orden_trabajo" name="referencia" style="width: 100%;">
                                        <?php if (!empty($movimiento['referencia'])): ?>
                                            <option value="<?= esc($movimiento['referencia']) ?>" selected>
                                                <?= esc($movimiento['referencia']) ?>
                                            </option>
                                        <?php else: ?>
                                            <option value="">Seleccione una orden de trabajo...</option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-muted">Busque y seleccione una orden de trabajo en proceso</small>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="origen-destino-row" style="<?= $movimiento['tipo_movimiento'] == 'TRANSFERENCIA' ? '' : 'display: none;' ?>">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_origen">Código Origen</label>
                                    <input type="text" class="form-control" id="codigo_origen" name="codigo_origen" 
                                           value="<?= esc($movimiento['codigo_origen']) ?>" 
                                           placeholder="Código de origen">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_destino">Código Destino</label>
                                    <input type="text" class="form-control" id="codigo_destino" name="codigo_destino" 
                                           value="<?= esc($movimiento['codigo_destino']) ?>" 
                                           placeholder="Código de destino">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles del Movimiento -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list"></i> Detalles del Movimiento
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" id="agregar-detalle">
                            <i class="fas fa-plus"></i> Agregar Material
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="detalles-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="35%">Material</th>
                                        <th width="15%">Cantidad</th>
                                        <th width="15%">Precio Unit.</th>
                                        <th width="10%">Impuesto %</th>
                                        <th width="15%">Total</th>
                                        <th width="10%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="detalles-tbody">
                                    <?php if (!empty($movimiento['detalles'])): ?>
                                        <?php foreach ($movimiento['detalles'] as $index => $detalle): ?>
                                            <tr data-index="<?= $index ?>">
                                                <td>
                                                    <strong><?= esc($detalle['material_nombre']) ?></strong><br>
                                                    <small class="text-muted">Código: <?= esc($detalle['codigo_consecutivo']) ?> | Unidad: <?= esc($detalle['unidad_medida']) ?></small>
                                                    <input type="hidden" name="materiales[]" value="<?= $detalle['id_material'] ?>">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control cantidad-input" name="cantidades[]" 
                                                           value="<?= $detalle['cantidad'] ?>" step="0.01" min="0.01" data-index="<?= $index ?>">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control precio-input" name="precios[]" 
                                                           value="<?= $detalle['precio'] ?>" step="0.01" min="0" data-index="<?= $index ?>">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control impuesto-input" name="impuestos[]" 
                                                           value="<?= $detalle['impuesto'] ?>" step="0.01" min="0" max="100" data-index="<?= $index ?>">
                                                </td>
                                                <td class="total-linea">$<?= number_format($detalle['total_linea'], 2) ?></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger eliminar-detalle" data-index="<?= $index ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total General:</th>
                                        <th id="total-general">$<?= number_format($movimiento['monto'], 2) ?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div id="sin-detalles" class="text-center py-4" style="<?= !empty($movimiento['detalles']) ? 'display: none;' : '' ?>">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay materiales agregados al movimiento</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-save"></i> Acciones
                        </h6>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block mb-2" id="guardar-cambios">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="<?= base_url('movimientos/show/' . $movimiento['id']) ?>" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>

                <!-- Estado Actual -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-info-circle"></i> Estado Actual
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <?php
                        $estadoBadges = [
                            0 => '<span class="badge badge-secondary badge-lg">Borrador</span>',
                            1 => '<span class="badge badge-warning badge-lg">Pendiente</span>',
                            2 => '<span class="badge badge-info badge-lg">Aprobado</span>',
                            3 => '<span class="badge badge-success badge-lg">Procesado</span>',
                            4 => '<span class="badge badge-danger badge-lg">Cancelado</span>'
                        ];
                        echo $estadoBadges[$movimiento['estado']] ?? '<span class="badge badge-secondary badge-lg">Desconocido</span>';
                        ?>
                        <p class="mt-2 mb-0 text-muted">
                            <small>Solo se pueden editar movimientos en estado Borrador o Pendiente</small>
                        </p>
                    </div>
                </div>

                <!-- Información -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-info-circle"></i> Información
                        </h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <strong>Tipos de Movimiento:</strong><br>
                            • <strong>Entrada:</strong> Ingreso de materiales al inventario<br>
                            • <strong>Salida:</strong> Salida de materiales del inventario<br>
                            • <strong>Transferencia:</strong> Movimiento entre ubicaciones<br>
                            • <strong>Ajuste Positivo:</strong> Incremento por ajuste<br>
                            • <strong>Ajuste Negativo:</strong> Decremento por ajuste
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal para Seleccionar Material -->
<div class="modal fade" id="materialModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-search"></i> Seleccionar Material
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="buscar-material">Buscar Material</label>
                    <select class="form-control" id="buscar-material" style="width: 100%;">
                        <option value="">Escriba para buscar...</option>
                    </select>
                </div>
                <div id="material-info" style="display: none;">
                    <div class="alert alert-info">
                        <strong>Material Seleccionado:</strong><br>
                        <span id="material-nombre"></span><br>
                        <small>Código: <span id="material-codigo"></span> | Unidad: <span id="material-unidad"></span> | Costo: $<span id="material-costo"></span></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cantidad">Cantidad <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="cantidad" step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="precio">Precio Unitario <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="precio" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="impuesto">Impuesto %</label>
                            <input type="number" class="form-control" id="impuesto" step="0.01" min="0" max="100" value="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="agregar-material-btn">
                    <i class="fas fa-plus"></i> Agregar Material
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let detalleIndex = <?= count($movimiento['detalles']) ?>;
    let materialSeleccionado = null;

    // Inicializar Select2 para búsqueda de materiales
    $('#buscar-material').select2({
        theme: 'bootstrap4',
        placeholder: 'Escriba para buscar materiales...',
        allowClear: true,
        ajax: {
            url: '<?= base_url('movimientos/buscarMateriales') ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
    });

    // Inicializar Select2 para búsqueda de órdenes de trabajo
    $('#orden_trabajo').select2({
        theme: 'bootstrap4',
        placeholder: 'Busque una orden de trabajo en proceso...',
        allowClear: true,
        ajax: {
            url: '<?= base_url('movimientos/buscarOrdenesEnProceso') ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        },
        templateResult: function(data) {
            if (data.loading) return data.text;
            
            var markup = '<div class="select2-result-repository clearfix">' +
                '<div class="select2-result-repository__meta">' +
                '<div class="select2-result-repository__title">' + data.codigo + '</div>';
            
            if (data.descripcion) {
                markup += '<div class="select2-result-repository__description">' + data.descripcion + '</div>';
            }
            
            if (data.vehiculo) {
                markup += '<div class="select2-result-repository__statistics">' +
                    '<div class="select2-result-repository__forks"><i class="fas fa-car"></i> ' + data.vehiculo + '</div>' +
                    '</div>';
            }
            
            markup += '</div></div>';
            return markup;
        },
        templateSelection: function(data) {
            return data.codigo || data.text;
        }
    });

    // Mostrar/ocultar campos origen-destino según tipo de movimiento
    $('#tipo_movimiento').change(function() {
        const tipo = $(this).val();
        if (tipo === 'TRANSFERENCIA') {
            $('#origen-destino-row').show();
        } else {
            $('#origen-destino-row').hide();
            $('#codigo_origen, #codigo_destino').val('');
        }
    });

    // Agregar detalle
    $('#agregar-detalle').click(function() {
        $('#materialModal').modal('show');
        limpiarModalMaterial();
    });

    // Selección de material
    $('#buscar-material').on('select2:select', function(e) {
        const data = e.params.data;
        materialSeleccionado = data;
        
        $('#material-nombre').text(data.nombre);
        $('#material-codigo').text(data.codigo);
        $('#material-unidad').text(data.unidad_medida);
        $('#material-costo').text(parseFloat(data.costo_unitario).toFixed(2));
        $('#precio').val(data.costo_unitario);
        $('#material-info').show();
    });

    // Agregar material a la tabla
    $('#agregar-material-btn').click(function() {
        if (!materialSeleccionado) {
            alert('Debe seleccionar un material');
            return;
        }

        const cantidad = parseFloat($('#cantidad').val());
        const precio = parseFloat($('#precio').val());
        const impuesto = parseFloat($('#impuesto').val()) || 0;

        if (!cantidad || cantidad <= 0) {
            alert('La cantidad debe ser mayor a 0');
            return;
        }

        if (!precio || precio < 0) {
            alert('El precio debe ser mayor o igual a 0');
            return;
        }

        // Verificar si el material ya existe
        const materialExiste = $(`input[name="materiales[]"][value="${materialSeleccionado.id}"]`).length > 0;
        if (materialExiste) {
            alert('Este material ya ha sido agregado');
            return;
        }

        agregarFilaDetalle(materialSeleccionado, cantidad, precio, impuesto);
        $('#materialModal').modal('hide');
        actualizarTotal();
    });

    // Función para agregar fila de detalle
    function agregarFilaDetalle(material, cantidad, precio, impuesto) {
        const subtotal = cantidad * precio;
        const montoImpuesto = subtotal * (impuesto / 100);
        const total = subtotal + montoImpuesto;

        const fila = `
            <tr data-index="${detalleIndex}">
                <td>
                    <strong>${material.nombre}</strong><br>
                    <small class="text-muted">Código: ${material.codigo} | Unidad: ${material.unidad_medida}</small>
                    <input type="hidden" name="materiales[]" value="${material.id}">
                </td>
                <td>
                    <input type="number" class="form-control cantidad-input" name="cantidades[]" 
                           value="${cantidad}" step="0.01" min="0.01" data-index="${detalleIndex}">
                </td>
                <td>
                    <input type="number" class="form-control precio-input" name="precios[]" 
                           value="${precio}" step="0.01" min="0" data-index="${detalleIndex}">
                </td>
                <td>
                    <input type="number" class="form-control impuesto-input" name="impuestos[]" 
                           value="${impuesto}" step="0.01" min="0" max="100" data-index="${detalleIndex}">
                </td>
                <td class="total-linea">$${total.toFixed(2)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger eliminar-detalle" data-index="${detalleIndex}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#detalles-tbody').append(fila);
        $('#sin-detalles').hide();
        detalleIndex++;
    }

    // Eliminar detalle
    $(document).on('click', '.eliminar-detalle', function() {
        $(this).closest('tr').remove();
        actualizarTotal();
        
        if ($('#detalles-tbody tr').length === 0) {
            $('#sin-detalles').show();
        }
    });

    // Actualizar totales cuando cambien cantidad, precio o impuesto
    $(document).on('input', '.cantidad-input, .precio-input, .impuesto-input', function() {
        const fila = $(this).closest('tr');
        const cantidad = parseFloat(fila.find('.cantidad-input').val()) || 0;
        const precio = parseFloat(fila.find('.precio-input').val()) || 0;
        const impuesto = parseFloat(fila.find('.impuesto-input').val()) || 0;

        const subtotal = cantidad * precio;
        const montoImpuesto = subtotal * (impuesto / 100);
        const total = subtotal + montoImpuesto;

        fila.find('.total-linea').text('$' + total.toFixed(2));
        actualizarTotal();
    });

    // Actualizar total general
    function actualizarTotal() {
        let totalGeneral = 0;
        $('.total-linea').each(function() {
            const valor = parseFloat($(this).text().replace('$', '')) || 0;
            totalGeneral += valor;
        });
        $('#total-general').text('$' + totalGeneral.toFixed(2));
    }

    // Limpiar modal de material
    function limpiarModalMaterial() {
        $('#buscar-material').val(null).trigger('change');
        $('#cantidad, #precio, #impuesto').val('');
        $('#impuesto').val('0');
        $('#material-info').hide();
        materialSeleccionado = null;
    }

    // Enviar formulario
    $('#movimiento-form').submit(function(e) {
        e.preventDefault();
        
        if ($('#detalles-tbody tr').length === 0) {
            alert('Debe agregar al menos un material al movimiento');
            return;
        }

        const formData = new FormData(this);

        $.ajax({
            url: '<?= base_url('movimientos/update/' . $movimiento['id']) ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#guardar-cambios').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            },
            success: function(response) {
                if (response.success) {
                    mostrarAlerta('success', response.message);
                    setTimeout(function() {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }, 2000);
                } else {
                    mostrarAlerta('error', response.message);
                    if (response.errors) {
                        mostrarErrores(response.errors);
                    }
                }
            },
            error: function() {
                mostrarAlerta('error', 'Error al actualizar el movimiento');
            },
            complete: function() {
                $('#guardar-cambios').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Cambios');
            }
        });
    });

    function mostrarErrores(errores) {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.each(errores, function(campo, mensaje) {
            const input = $(`[name="${campo}"]`);
            input.addClass('is-invalid');
            input.siblings('.invalid-feedback').text(mensaje);
        });
    }

    function mostrarAlerta(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alerta = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass}"></i> ${mensaje}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $('.container-fluid').prepend(alerta);
        
        // Auto-hide después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }

    // Calcular total inicial
    actualizarTotal();
});
</script>
<?= $this->endSection() ?>
