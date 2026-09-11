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
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('movimientos') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <form id="movimiento-form">
        <div class="row">
            <!-- Información del Movimiento -->
            <div class="col-lg-12">
            <div class="card shadow mb-4">
                    <div class="card-body">
                    <div class="btn-group">
                        <a title="Cancelar" href="<?= base_url('movimientos') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> 
                        </a>
                        <button title="Guardar como Borrador" type="submit" class="btn btn-primary" id="guardar-borrador">
                            <i class="fas fa-save"></i> 
                        </button>
                        <button title="Guardar y Enviar" type="button" class="btn btn-success" id="guardar-pendiente">
                            <i class="fas fa-paper-plane"></i> 
                        </button>
                       
                        </div>
                    </div>
                </div>
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
                                    <label for="tipo_movimiento">Tipo de Movimiento</label>
                                    <?php if (!empty($tipo_preseleccionado)): ?>
                                        <input type="text" class="form-control" 
                                               value="<?= esc($tipos_movimiento[$tipo_preseleccionado] ?? 'Tipo no válido') ?>" 
                                               readonly>
                                        <input type="hidden" id="tipo_movimiento" name="tipo_movimiento" value="<?= esc($tipo_preseleccionado) ?>">
                                    <?php else: ?>
                                        <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                                            <option value="">Seleccione un tipo</option>
                                            <?php foreach ($tipos_movimiento as $key => $tipo): ?>
                                                <option value="<?= $key ?>">
                                                    <?= esc($tipo) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_movimiento">Fecha del Movimiento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha_movimiento" name="fecha_movimiento" 
                                           value="<?= date('Y-m-d') ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="codigo_solicitud">Orden de Trabajo (Referencia)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="codigo_solicitud" name="referencia" 
                                               placeholder="Digite el código de la solicitud...">
                                        <div class="input-group-append" id="boton-buscar-container">
                                            <button type="button" class="btn btn-outline-primary" id="buscar-solicitudes">
                                                <i class="fas fa-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted" id="texto-ayuda-referencia">Digite el código de solicitud o use el botón buscar para ver solicitudes en proceso</small>
                                    <div class="invalid-feedback"></div>
                                    <!-- Información de la solicitud seleccionada -->
                                    <div id="info-solicitud" class="mt-2" style="display: none;">
                                        <div class="alert alert-info py-2">
                                            <strong>Solicitud:</strong> <span id="solicitud-codigo"></span><br>
                                            <strong>Descripción:</strong> <span id="solicitud-descripcion"></span><br>
                                            <strong>Vehículo:</strong> <span id="solicitud-vehiculo"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($tipo_preseleccionado === 'TRANSFERENCIA'): ?>
                        <div class="row" id="origen-destino-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_origen">Ubicación de Origen <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="codigo_origen" name="codigo_origen" 
                                           placeholder="Ej: ALMACEN-A, BODEGA-01" required>
                                    <small class="text-muted">Código de la ubicación de origen</small>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_destino">Ubicación de Destino <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="codigo_destino" name="codigo_destino" 
                                           placeholder="Ej: ALMACEN-B, BODEGA-02" required>
                                    <small class="text-muted">Código de la ubicación de destino</small>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="row" id="origen-destino-row" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_origen">Ubicación de Origen</label>
                                    <input type="text" class="form-control" id="codigo_origen" name="codigo_origen" 
                                           placeholder="Código de origen">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_destino">Ubicación de Destino</label>
                                    <input type="text" class="form-control" id="codigo_destino" name="codigo_destino" 
                                           placeholder="Código de destino">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Detalles del Movimiento -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list"></i> Detalles del Movimiento
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" id="agregar-linea">
                            <i class="fas fa-plus"></i> Agregar Línea
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Instrucciones:</strong> Digite el código del material en la primera columna. 
                            Puede usar el <strong>código consecutivo</strong> o el <strong>código de vinculación</strong>. 
                            Los datos del material se cargarán automáticamente.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="detalles-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="20%">Código Material <span class="text-danger">*</span></th>
                                        <th width="25%">Descripción</th>
                                        <th width="8%">Unidad</th>
                                        <th width="12%">Cantidad <span class="text-danger">*</span></th>
                                        <th width="3%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="detalles-tbody">
                                    <!-- Fila inicial -->
                                    <tr class="detalle-row" data-index="0">
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control codigo-material" 
                                                       name="codigos_material[]" 
                                                       placeholder="Digite código del material"
                                                       data-index="0">
                                                <button type="button" class="btn btn-outline-secondary buscar-material" data-index="0" title="Buscar material">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="materiales[]" class="material-id">
                                            <div class="invalid-feedback"></div>
                                        </td>
                                        <td>
                                            <span class="material-nombre text-muted">-</span>
                                        </td>
                                        <td>
                                            <span class="material-unidad text-muted">-</span>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control cantidad-input" 
                                                   name="cantidades[]" step="0.01" min="0.01" 
                                                   placeholder="0.00" data-index="0">
                                            <input type="hidden" class="precio-input" name="precios[]" data-index="0">
                                            <input type="hidden" class="impuesto-input" name="impuestos[]" value="0" data-index="0">
                                            <span class="total-linea d-none">0</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger eliminar-linea" 
                                                    data-index="0" title="Eliminar línea">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active d-none">
                                        <th colspan="5" class="text-right h6">Total General:</th>
                                        <th id="total-general" class="h5 text-primary">$0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
               

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
                            • <strong>Requisa de Salida:</strong> Salida de materiales para órdenes de trabajo<br>
                            • <strong>Orden de Compras:</strong> Ingreso de materiales por compras<br>
                            • <strong>Ajuste de Materiales:</strong> Corrección de inventario<br>
                            • <strong>Traslados:</strong> Movimiento entre ubicaciones<br><br>
                            
                            <strong>Nota:</strong> Seleccione una orden de trabajo en proceso como referencia para vincular el movimiento.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal para buscar solicitudes -->
<div class="modal fade" id="modalBuscarSolicitudes" tabindex="-1" role="dialog" aria-labelledby="modalBuscarSolicitudesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarSolicitudesLabel">
                    <i class="fas fa-search"></i> Buscar Solicitudes en Proceso
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="filtro-solicitudes" 
                               placeholder="Buscar por código, descripción o vehículo...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filtro-estado">
                            <option value="">Todos los estados</option>
                            <option value="EN_PROCESO" selected>En Proceso</option>
                            <option value="PENDIENTES">Pendiente</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" id="aplicar-filtros">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tabla-solicitudes">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Vehículo</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se carga dinámicamente -->
                        </tbody>
                    </table>
                </div>
                
                <div id="loading-solicitudes" class="text-center py-4" style="display: none;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Cargando solicitudes...</p>
                </div>
                
                <div id="no-solicitudes" class="text-center py-4" style="display: none;">
                    <i class="fas fa-inbox fa-2x text-muted"></i>
                    <p class="mt-2 text-muted">No se encontraron solicitudes</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para buscar materiales -->
<div class="modal fade" id="modalBuscarMateriales" tabindex="-1" role="dialog" aria-labelledby="modalBuscarMaterialesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarMaterialesLabel">
                    <i class="fas fa-boxes"></i> Buscar Materiales
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="filtro-materiales" placeholder="Buscar por código o nombre...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tabla-materiales">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Unidad</th>
                                <th>Costo Unit.</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se carga dinámicamente -->
                        </tbody>
                    </table>
                </div>
                <div id="loading-materiales" class="text-center py-4" style="display: none;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Cargando materiales...</p>
                </div>
                <div id="no-materiales" class="text-center py-4" style="display: none;">
                    <i class="fas fa-box-open fa-2x text-muted"></i>
                    <p class="mt-2 text-muted">No se encontraron materiales</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let detalleIndex = 1; // Empezamos en 1 porque ya hay una fila inicial
    let materialTargetIndex = null;

    // Funcionalidad para búsqueda de solicitudes
    
    // Abrir modal de búsqueda
    $('#buscar-solicitudes').click(function() {
        $('#modalBuscarSolicitudes').modal('show');
        cargarSolicitudes();
    });
    
    // Buscar solicitud por código cuando se pierde el foco
    $('#codigo_solicitud').blur(function() {
        const codigo = $(this).val().trim();
        if (codigo !== '') {
            buscarSolicitudPorCodigo(codigo);
        } else {
            limpiarInfoSolicitud();
        }
    });

    // Abrir modal de materiales
    $(document).on('click', '.buscar-material', function() {
        materialTargetIndex = $(this).data('index');
        $('#modalBuscarMateriales').modal('show');
        cargarMateriales();
    });

    // Búsqueda en modal de materiales
    $('#filtro-materiales').on('input', function() {
        cargarMateriales();
    });

    function cargarMateriales() {
        const termino = $('#filtro-materiales').val();
        $('#loading-materiales').show();
        $('#tabla-materiales tbody').empty();
        $('#no-materiales').hide();

        $.ajax({
            url: '<?= base_url('movimientos/buscarMateriales') ?>',
            type: 'GET',
            data: { q: termino },
            dataType: 'json',
            success: function(response) {
                $('#loading-materiales').hide();

                if (response.results && response.results.length > 0) {
                    const materiales = response.results.slice(0, 10);
                    let rows = '';
                    materiales.forEach(function(mat) {
                        rows += `
                            <tr>
                                <td>${mat.codigo}</td>
                                <td>${mat.nombre}</td>
                                <td>${mat.unidad_medida || '-'}</td>
                                <td>${mat.costo_unitario ?? 0}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary seleccionar-material"
                                        data-id="${mat.id}"
                                        data-codigo="${mat.codigo}"
                                        data-nombre="${mat.nombre}"
                                        data-unidad="${mat.unidad_medida || ''}"
                                        data-costo="${mat.costo_unitario ?? 0}"
                                    >
                                        <i class="fas fa-check"></i> Seleccionar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#tabla-materiales tbody').html(rows);
                } else {
                    $('#no-materiales').show();
                }
            },
            error: function() {
                $('#loading-materiales').hide();
                $('#no-materiales').show();
                alert('Error al cargar materiales');
            }
        });
    }

    // Seleccionar material desde modal
    $(document).on('click', '.seleccionar-material', function() {
        if (materialTargetIndex === null) return;

        const fila = $(`#detalles-tbody tr[data-index="${materialTargetIndex}"]`);
        fila.find('.codigo-material').val($(this).data('codigo'));
        fila.find('.material-id').val($(this).data('id'));
        fila.find('.material-nombre').text($(this).data('nombre'));
        fila.find('.material-unidad').text($(this).data('unidad'));
        fila.find('.precio-input').val($(this).data('costo'));

        const cantidadInput = fila.find('.cantidad-input');
        if (cantidadInput.val() === '' || cantidadInput.val() === '0') {
            cantidadInput.val('1');
        }

        fila.find('.codigo-material').removeClass('is-invalid');
        fila.find('.invalid-feedback').text('');

        calcularTotalLinea(fila);
        actualizarTotal();

        $('#modalBuscarMateriales').modal('hide');
        materialTargetIndex = null;
    });
    
    // Aplicar filtros en el modal
    $('#aplicar-filtros').click(function() {
        cargarSolicitudes();
    });
    
    // Buscar al presionar Enter en el filtro
    $('#filtro-solicitudes').keypress(function(e) {
        if (e.which === 13) {
            cargarSolicitudes();
        }
    });
    
    // Función para cargar solicitudes en el modal
    function cargarSolicitudes() {
        const filtro = $('#filtro-solicitudes').val();
        const estado = $('#filtro-estado').val();
        
        $('#loading-solicitudes').show();
        $('#tabla-solicitudes tbody').empty();
        $('#no-solicitudes').hide();
        
        $.ajax({
            url: '<?= base_url('movimientos/buscarOrdenesEnProceso') ?>',
            type: 'GET',
            data: {
                q: filtro,
                estado: estado,
                modal: true
            },
            dataType: 'json',
            success: function(response) {
                $('#loading-solicitudes').hide();
                
                if (response.results && response.results.length > 0) {
                    let tbody = '';
                    response.results.forEach(function(solicitud) {
                        tbody += `
                            <tr>
                                <td>${solicitud.codigo}</td>
                                <td>${solicitud.descripcion || '-'}</td>
                                <td>${solicitud.vehiculo || '-'}</td>
                                <td><span class="badge badge-info">${solicitud.estado || 'EN_PROCESO'}</span></td>
                                <td>${solicitud.fecha || '-'}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary seleccionar-solicitud" 
                                            data-codigo="${solicitud.codigo}"
                                            data-descripcion="${solicitud.descripcion || ''}"
                                            data-vehiculo="${solicitud.vehiculo || ''}">
                                        <i class="fas fa-check"></i> Seleccionar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#tabla-solicitudes tbody').html(tbody);
                } else {
                    $('#no-solicitudes').show();
                }
            },
            error: function() {
                $('#loading-solicitudes').hide();
                $('#no-solicitudes').show();
                alert('Error al cargar las solicitudes');
            }
        });
    }
    
    // Seleccionar solicitud del modal
    $(document).on('click', '.seleccionar-solicitud', function() {
        const codigo = $(this).data('codigo');
        const descripcion = $(this).data('descripcion');
        const vehiculo = $(this).data('vehiculo');
        
        $('#codigo_solicitud').val(codigo);
        mostrarInfoSolicitud(codigo, descripcion, vehiculo);
        $('#modalBuscarSolicitudes').modal('hide');
    });
    
    // Función para buscar solicitud por código
    function buscarSolicitudPorCodigo(codigo) {
        $.ajax({
            url: '<?= base_url('movimientos/buscarOrdenesEnProceso') ?>',
            type: 'GET',
            data: { q: codigo, exact: true },
            dataType: 'json',
            success: function(response) {
                if (response.results && response.results.length > 0) {
                    const solicitud = response.results[0];
                    mostrarInfoSolicitud(solicitud.codigo, solicitud.descripcion, solicitud.vehiculo);
                } else {
                    limpiarInfoSolicitud();
                    $('#codigo_solicitud').addClass('is-invalid');
                    $('#codigo_solicitud').siblings('.invalid-feedback').text('Solicitud no encontrada');
                }
            },
            error: function() {
                limpiarInfoSolicitud();
                $('#codigo_solicitud').addClass('is-invalid');
                $('#codigo_solicitud').siblings('.invalid-feedback').text('Error al buscar la solicitud');
            }
        });
    }
    
    // Función para mostrar información de la solicitud
    function mostrarInfoSolicitud(codigo, descripcion, vehiculo) {
        $('#solicitud-codigo').text(codigo);
        $('#solicitud-descripcion').text(descripcion || 'Sin descripción');
        $('#solicitud-vehiculo').text(vehiculo || 'Sin vehículo asignado');
        $('#info-solicitud').show();
        $('#codigo_solicitud').removeClass('is-invalid');
        $('#codigo_solicitud').siblings('.invalid-feedback').text('');
    }
    
    // Función para limpiar información de la solicitud
    function limpiarInfoSolicitud() {
        $('#info-solicitud').hide();
        $('#codigo_solicitud').removeClass('is-invalid');
        $('#codigo_solicitud').siblings('.invalid-feedback').text('');
    }

    // Mostrar/ocultar campos origen-destino según tipo de movimiento (solo para modo manual)
    <?php if (empty($tipo_preseleccionado)): ?>
    function toggleOrigenDestino() {
        const tipo = $('#tipo_movimiento').val();
        if (tipo === 'TRANSFERENCIA') {
            $('#origen-destino-row').show();
            $('#codigo_origen, #codigo_destino').attr('required', true);
        } else {
            $('#origen-destino-row').hide();
            $('#codigo_origen, #codigo_destino').attr('required', false).val('');
        }
    }
    
    // Mostrar/ocultar botón de búsqueda según tipo de movimiento
    function toggleBotonBuscar() {
        const tipo = $('#tipo_movimiento').val();
        if (tipo === 'ENTRADA') {
            $('#boton-buscar-container').hide();
            $('#codigo_solicitud').attr('placeholder', 'Referencia opcional para entrada de inventario...');
            $('#texto-ayuda-referencia').text('Referencia opcional para identificar la entrada de inventario');
        } else {
            $('#boton-buscar-container').show();
            $('#codigo_solicitud').attr('placeholder', 'Digite el código de la solicitud...');
            $('#texto-ayuda-referencia').text('Digite el código de solicitud o use el botón buscar para ver solicitudes en proceso');
        }
    }
    
    $('#tipo_movimiento').change(function() {
        toggleOrigenDestino();
        toggleBotonBuscar();
    });
    
    // Ejecutar al cargar la página
    toggleOrigenDestino();
    toggleBotonBuscar();
    <?php else: ?>
    // Para tipos preseleccionados, ocultar botón si es ENTRADA
    <?php if ($tipo_preseleccionado === 'ENTRADA'): ?>
    $('#boton-buscar-container').hide();
    $('#codigo_solicitud').attr('placeholder', 'Referencia opcional para entrada de inventario...');
    $('#texto-ayuda-referencia').text('Referencia opcional para identificar la entrada de inventario');
    <?php endif; ?>
    <?php endif; ?>

    // Agregar nueva línea
    $('#agregar-linea').click(function() {
        agregarNuevaLinea();
    });

    // Función para agregar nueva línea
    function agregarNuevaLinea() {
        const nuevaFila = `
            <tr class="detalle-row" data-index="${detalleIndex}">
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control codigo-material" 
                               name="codigos_material[]" 
                               placeholder="Digite código del material"
                               data-index="${detalleIndex}">
                        <button type="button" class="btn btn-outline-secondary buscar-material" data-index="${detalleIndex}" title="Buscar material">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <input type="hidden" name="materiales[]" class="material-id">
                    <div class="invalid-feedback"></div>
                </td>
                <td>
                    <span class="material-nombre text-muted">-</span>
                </td>
                <td>
                    <span class="material-unidad text-muted">-</span>
                </td>
                <td>
                    <input type="number" class="form-control cantidad-input" 
                           name="cantidades[]" step="0.01" min="0.01" 
                           placeholder="0.00" data-index="${detalleIndex}">
                    <input type="hidden" class="precio-input" name="precios[]" data-index="${detalleIndex}">
                    <input type="hidden" class="impuesto-input" name="impuestos[]" value="0" data-index="${detalleIndex}">
                    <span class="total-linea d-none">0</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger eliminar-linea" 
                            data-index="${detalleIndex}" title="Eliminar línea">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#detalles-tbody').append(nuevaFila);
        detalleIndex++;
    }

    // Buscar material por código cuando se pierde el foco del input
    $(document).on('blur', '.codigo-material', function() {
        const input = $(this);
        const codigo = input.val().trim();
        const fila = input.closest('tr');
        const index = input.data('index');
        
        console.log('Buscando material con código:', codigo);
        
        if (codigo === '') {
            limpiarFilaMaterial(fila);
            return;
        }
        
        // Mostrar indicador de carga
        fila.find('.material-nombre').html('<i class="fas fa-spinner fa-spin"></i> Buscando...');
        
        $.ajax({
            url: '<?= base_url('movimientos/buscarMaterialPorCodigo') ?>',
            type: 'POST',
            data: { codigo: codigo },
            dataType: 'json',
            beforeSend: function(xhr) {
                console.log('Enviando request a:', '<?= base_url('movimientos/buscarMaterialPorCodigo') ?>');
                console.log('Datos enviados:', { codigo: codigo });
            },
            success: function(response) {
                console.log('Respuesta recibida:', response);
                
                if (response.success) {
                    const material = response.material;
                    
                    // Verificar si el material ya existe en otra fila
                    let materialExiste = false;
                    $('.material-id').each(function() {
                        if ($(this).val() == material.id && $(this).closest('tr').data('index') != index) {
                            materialExiste = true;
                            return false;
                        }
                    });
                    
                    if (materialExiste) {
                        alert('Este material ya ha sido agregado en otra línea');
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text('Material duplicado');
                        limpiarFilaMaterial(fila);
                        return;
                    }
                    
                    // Llenar datos del material
                    fila.find('.material-id').val(material.id);
                    fila.find('.material-nombre').text(material.nombre);
                    fila.find('.material-unidad').text(material.unidad_medida);
                    fila.find('.precio-input').val(material.costo_unitario);
                    
                    // Establecer cantidad por defecto si está vacía
                    const cantidadInput = fila.find('.cantidad-input');
                    if (cantidadInput.val() === '' || cantidadInput.val() === '0') {
                        cantidadInput.val('1');
                    }
                    
                    // Limpiar errores
                    input.removeClass('is-invalid');
                    input.siblings('.invalid-feedback').text('');
                    
                    // Calcular total de la línea con los nuevos valores
                    calcularTotalLinea(fila);
                    actualizarTotal();
                    
                    // Enfocar cantidad para que el usuario pueda modificarla si lo desea
                    cantidadInput.focus().select();
                    
                } else {
                    // Material no encontrado
                    console.log('Material no encontrado:', response.message);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(response.message);
                    limpiarFilaMaterial(fila);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error en AJAX:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').text('Error al buscar el material');
                limpiarFilaMaterial(fila);
            }
        });
    });

    // Función para limpiar datos del material en una fila
    function limpiarFilaMaterial(fila) {
        fila.find('.material-id').val('');
        fila.find('.material-nombre').text('-');
        fila.find('.material-unidad').text('-');
        fila.find('.precio-input').val('');
        fila.find('.cantidad-input').val('');
        fila.find('.total-linea').text('$0.00');
        actualizarTotal();
    }

    // Eliminar línea
    $(document).on('click', '.eliminar-linea', function() {
        const fila = $(this).closest('tr');
        
        // No permitir eliminar si es la única fila
        if ($('#detalles-tbody tr').length <= 1) {
            alert('Debe mantener al menos una línea');
            return;
        }
        
        fila.remove();
        actualizarTotal();
    });

    // Actualizar totales cuando cambien cantidad, precio o impuesto
    $(document).on('input', '.cantidad-input, .precio-input, .impuesto-input', function() {
        const fila = $(this).closest('tr');
        calcularTotalLinea(fila);
        actualizarTotal();
    });

    // Función para calcular total de línea
    function calcularTotalLinea(fila) {
        const cantidad = parseFloat(fila.find('.cantidad-input').val()) || 0;
        const precio = parseFloat(fila.find('.precio-input').val()) || 0;
        const impuesto = parseFloat(fila.find('.impuesto-input').val()) || 0;

        const subtotal = cantidad * precio;
        const montoImpuesto = subtotal * (impuesto / 100);
        const total = subtotal + montoImpuesto;

        fila.find('.total-linea').text('$' + total.toFixed(2));
    }

    // Actualizar total general
    function actualizarTotal() {
        let totalGeneral = 0;
        $('.total-linea').each(function() {
            const valor = parseFloat($(this).text().replace('$', '')) || 0;
            totalGeneral += valor;
        });
        $('#total-general').text('$' + totalGeneral.toFixed(2));
    }

    // Enviar formulario
    $('#movimiento-form').submit(function(e) {
        e.preventDefault();
        guardarMovimiento(0); // Borrador
    });

    $('#guardar-pendiente').click(function() {
        guardarMovimiento(1); // Pendiente
    });

    function guardarMovimiento(estado) {
        // Validar que hay al menos una línea con material
        let tieneDetalles = false;
        $('.material-id').each(function() {
            if ($(this).val() !== '') {
                tieneDetalles = true;
                return false;
            }
        });
        
        if (!tieneDetalles) {
            alert('Debe agregar al menos un material al movimiento');
            return;
        }

        const formData = new FormData($('#movimiento-form')[0]);
        formData.append('estado', estado);

        $.ajax({
            url: '<?= base_url('movimientos/store') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#guardar-borrador, #guardar-pendiente').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else {
                    alert(response.message);
                    if (response.errors) {
                        mostrarErrores(response.errors);
                    }
                }
            },
            error: function() {
                alert('Error al guardar el movimiento');
            },
            complete: function() {
                $('#guardar-borrador, #guardar-pendiente').prop('disabled', false);
            }
        });
    }

    function mostrarErrores(errores) {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.each(errores, function(campo, mensaje) {
            const input = $(`[name="${campo}"]`);
            input.addClass('is-invalid');
            input.siblings('.invalid-feedback').text(mensaje);
        });
    }
});
</script>
<?= $this->endSection() ?>
