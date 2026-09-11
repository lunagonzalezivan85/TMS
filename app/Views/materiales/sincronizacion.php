<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-sync-alt"></i> <?= $title ?>
        </h1>
        <div>
            <button type="button" class="btn btn-primary btn-sm" onclick="cargarProductos()">
                <i class="fas fa-refresh"></i> Actualizar Lista
            </button>
            <a href="<?= base_url('materiales') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver a Materiales
            </a>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="busqueda">Buscar Productos</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="busqueda" 
                                   placeholder="Código, descripción o descripción corta...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="buscarProductos()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="limite">Productos por página</label>
                        <select class="form-control" id="limite" onchange="cargarProductos()">
                            <option value="25">25</option>
                            <option value="50" selected>50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filtro-estado">Estado</label>
                        <select class="form-control" id="filtro-estado" onchange="cargarProductos()">
                            <option value="todos">Todos</option>
                            <option value="no-sincronizados">No Sincronizados</option>
                            <option value="sincronizados">Sincronizados</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Productos SQL Server</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-productos">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sincronizados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-sincronizados">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-pendientes">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Progreso</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="porcentaje-progreso">0%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             id="barra-progreso" style="width: 0%" 
                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de productos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Productos del ERP SAG
            </h6>
            <div class="dropdown no-arrow">
                <button class="btn btn-success btn-sm" onclick="sincronizarTodos()">
                    <i class="fas fa-sync-alt"></i> Sincronizar Seleccionados
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Loading -->
            <div id="loading" class="text-center py-4" style="display: none;">
                <i class="fas fa-spinner fa-spin fa-2x text-gray-300 mb-3"></i>
                <p class="text-muted">Cargando productos...</p>
            </div>

            <!-- Tabla de productos -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabla-productos">
                    <thead class="thead-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                            </th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Descripción Corta</th>
                            <th>Costo Unitario</th>
                            <th>Existencia</th>
                            <th>Estado</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="productos-tbody">
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Haga clic en "Actualizar Lista" para cargar productos
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div id="info-paginacion" class="text-muted small"></div>
                </div>
                <div class="col-md-6">
                    <nav aria-label="Paginación productos">
                        <ul class="pagination pagination-sm justify-content-end" id="paginacion">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="modalConfirmar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Sincronización</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea sincronizar el producto <strong id="producto-nombre"></strong>?</p>
                <p class="text-info small">Se creará un nuevo material en el sistema con código consecutivo automático.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarSincronizacion()">
                    <i class="fas fa-sync-alt"></i> Sincronizar
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let paginaActual = 0;
let limiteActual = 50;
let productoSeleccionado = null;

$(document).ready(function() {
    cargarProductos();
    
    // Búsqueda en tiempo real
    $('#busqueda').on('keypress', function(e) {
        if (e.which === 13) {
            buscarProductos();
        }
    });
});

function cargarProductos(pagina = 0) {
    const limite = $('#limite').val();
    const busqueda = $('#busqueda').val();
    const offset = pagina * limite;
    
    $('#loading').show();
    $('#tabla-productos tbody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>');
    
    $.get('<?= base_url('materiales/productos-sqlserver') ?>', {
        limite: limite,
        offset: offset,
        busqueda: busqueda
    })
    .done(function(response) {
        if (response.success) {
            mostrarProductos(response.productos);
            actualizarEstadisticas(response);
            actualizarPaginacion(response);
            paginaActual = pagina;
            limiteActual = limite;
        } else {
            mostrarError('Error al cargar productos: ' + response.message);
        }
    })
    .fail(function() {
        mostrarError('Error de conexión al cargar productos');
    })
    .always(function() {
        $('#loading').hide();
    });
}

function mostrarProductos(productos) {
    let html = '';
    
    if (productos.length === 0) {
        html = '<tr><td colspan="8" class="text-center text-muted py-4">No se encontraron productos</td></tr>';
    } else {
        productos.forEach(function(producto) {
            const sincronizado = producto.sincronizado;
            const estadoBadge = sincronizado ? 
                '<span class="badge bg-success">Sincronizado</span>' : 
                '<span class="badge bg-warning">Pendiente</span>';
            
            const checkbox = sincronizado ? '' : 
                `<input type="checkbox" class="producto-check" value="${producto.CODIGO_PRODUCTO}">`;
            
            const acciones = sincronizado ? 
                `<a href="<?= base_url('materiales/show/') ?>${producto.material_id}" class="btn btn-info btn-sm" title="Ver Material">
                    <i class="fas fa-eye"></i>
                </a>` :
                `<button class="btn btn-primary btn-sm" onclick="sincronizarProducto('${producto.CODIGO_PRODUCTO}', '${producto.DESCRIPCION}')" title="Sincronizar">
                    <i class="fas fa-sync-alt"></i>
                </button>`;
            
            html += `
                <tr class="${sincronizado ? 'table-success' : ''}">
                    <td class="text-center">${checkbox}</td>
                    <td><code>${producto.CODIGO_PRODUCTO}</code></td>
                    <td>${producto.DESCRIPCION || '-'}</td>
                    <td>${producto.DESCRIPCION_CORTA || '-'}</td>
                    <td class="text-right">$${parseFloat(producto.COSTO_UNITARIO || 0).toFixed(2)}</td>
                    <td class="text-right">${producto.EXISTENCIA_TOTAL || 0}</td>
                    <td class="text-center">${estadoBadge}</td>
                    <td class="text-center">${acciones}</td>
                </tr>
            `;
        });
    }
    
    $('#productos-tbody').html(html);
}

function actualizarEstadisticas(response) {
    const total = response.total;
    const sincronizados = response.productos.filter(p => p.sincronizado).length;
    const pendientes = response.productos.filter(p => !p.sincronizado).length;
    const porcentaje = total > 0 ? Math.round((sincronizados / total) * 100) : 0;
    
    $('#total-productos').text(total.toLocaleString());
    $('#total-sincronizados').text(sincronizados.toLocaleString());
    $('#total-pendientes').text(pendientes.toLocaleString());
    $('#porcentaje-progreso').text(porcentaje + '%');
    $('#barra-progreso').css('width', porcentaje + '%').attr('aria-valuenow', porcentaje);
}

function actualizarPaginacion(response) {
    const totalPaginas = Math.ceil(response.total / response.limite);
    const paginaActual = Math.floor(response.offset / response.limite);
    
    let html = '';
    
    // Información
    const inicio = response.offset + 1;
    const fin = Math.min(response.offset + response.limite, response.total);
    $('#info-paginacion').text(`Mostrando ${inicio} a ${fin} de ${response.total} productos`);
    
    // Botones de paginación
    if (totalPaginas > 1) {
        // Anterior
        html += `<li class="page-item ${paginaActual === 0 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="cargarProductos(${paginaActual - 1})">Anterior</a>
        </li>`;
        
        // Páginas
        const inicio = Math.max(0, paginaActual - 2);
        const fin = Math.min(totalPaginas, inicio + 5);
        
        for (let i = inicio; i < fin; i++) {
            html += `<li class="page-item ${i === paginaActual ? 'active' : ''}">
                <a class="page-link" href="#" onclick="cargarProductos(${i})">${i + 1}</a>
            </li>`;
        }
        
        // Siguiente
        html += `<li class="page-item ${paginaActual >= totalPaginas - 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="cargarProductos(${paginaActual + 1})">Siguiente</a>
        </li>`;
    }
    
    $('#paginacion').html(html);
}

function buscarProductos() {
    cargarProductos(0);
}

function sincronizarProducto(codigo, descripcion) {
    Swal.fire({
        title: 'Confirmar Sincronización',
        html: `¿Está seguro de que desea sincronizar el producto <strong>${descripcion}</strong>?<br><small class="text-muted">Se creará un nuevo material en el sistema con código consecutivo automático.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-sync-alt"></i> Sincronizar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.post('<?= base_url('materiales/sincronizar-producto') ?>', {
                codigo_producto: codigo
            })
            .then(response => {
                if (!response.success) {
                    throw new Error(response.message);
                }
                return response;
            })
            .catch(error => {
                Swal.showValidationMessage(`Error: ${error.message || 'Error de conexión'}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: '¡Producto Sincronizado!',
                html: `Código asignado: <strong>${result.value.codigo_consecutivo}</strong>`,
                confirmButtonColor: '#10b981',
                timer: 3000,
                timerProgressBar: true
            });
            cargarProductos(paginaActual);
        }
    });
}

function toggleSelectAll() {
    const checked = $('#select-all').is(':checked');
    $('.producto-check').prop('checked', checked);
}

function sincronizarTodos() {
    const seleccionados = $('.producto-check:checked').map(function() {
        return this.value;
    }).get();
    
    if (seleccionados.length === 0) {
        mostrarError('Seleccione al menos un producto para sincronizar');
        return;
    }
    
    // Confirmar con SweetAlert2
    Swal.fire({
        title: 'Confirmar Sincronización Masiva',
        text: `¿Está seguro de sincronizar ${seleccionados.length} productos?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, sincronizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (!result.isConfirmed) return;
        
        ejecutarSincronizacionMasiva(seleccionados);
    });
}

function ejecutarSincronizacionMasiva(seleccionados) {
    
    // Deshabilitar botón y mostrar progreso
    const botonSincronizar = $('button:contains("Sincronizar Seleccionados")');
    const textoOriginal = botonSincronizar.html();
    botonSincronizar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sincronizando...');
    
    // Realizar sincronización masiva
    $.post('<?= base_url('materiales/sincronizar-masivo') ?>', {
        codigos_productos: seleccionados
    })
    .done(function(response) {
        if (response.success) {
            mostrarResultadosSincronizacionSwal(response);
            cargarProductos(paginaActual); // Recargar lista
            $('#select-all').prop('checked', false); // Desmarcar select all
        } else {
            mostrarError('Error en sincronización masiva: ' + response.message);
        }
    })
    .fail(function() {
        mostrarError('Error de conexión durante la sincronización masiva');
    })
    .always(function() {
        // Rehabilitar botón
        botonSincronizar.prop('disabled', false).html(textoOriginal);
    });
}

function mostrarResultadosSincronizacionSwal(response) {
    const stats = response.estadisticas;
    const resultados = response.resultados;
    
    let htmlContent = `
        <div class="text-start">
            <div class="mb-3">
                <h6 class="text-primary mb-2"><i class="fas fa-chart-bar"></i> Resumen de Sincronización</h6>
                <div class="row text-center">
                    <div class="col-3">
                        <div class="border rounded p-2">
                            <div class="h5 mb-0 text-primary">${stats.total_procesados}</div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="border rounded p-2 bg-success bg-opacity-10">
                            <div class="h5 mb-0 text-success">${stats.exitosos}</div>
                            <small class="text-muted">Exitosos</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="border rounded p-2 bg-warning bg-opacity-10">
                            <div class="h5 mb-0 text-warning">${stats.ya_sincronizados}</div>
                            <small class="text-muted">Ya existían</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="border rounded p-2 bg-danger bg-opacity-10">
                            <div class="h5 mb-0 text-danger">${stats.errores}</div>
                            <small class="text-muted">Errores</small>
                        </div>
                    </div>
                </div>
            </div>`;
    
    // Mostrar productos exitosos
    if (resultados.exitosos.length > 0) {
        htmlContent += `
            <div class="mb-3">
                <h6 class="text-success mb-2"><i class="fas fa-check-circle"></i> Productos Sincronizados (${resultados.exitosos.length})</h6>
                <div class="bg-success bg-opacity-10 p-2 rounded" style="max-height: 150px; overflow-y: auto;">`;
        
        resultados.exitosos.slice(0, 15).forEach(function(exitoso) {
            htmlContent += `<div class="small"><code class="text-success">${exitoso.codigo_consecutivo}</code> - ${exitoso.nombre}</div>`;
        });
        
        if (resultados.exitosos.length > 15) {
            htmlContent += `<div class="small text-muted"><em>... y ${resultados.exitosos.length - 15} más</em></div>`;
        }
        
        htmlContent += `</div></div>`;
    }
    
    // Mostrar errores si los hay
    if (resultados.errores.length > 0) {
        htmlContent += `
            <div class="mb-3">
                <h6 class="text-danger mb-2"><i class="fas fa-exclamation-triangle"></i> Productos con Errores (${resultados.errores.length})</h6>
                <div class="bg-danger bg-opacity-10 p-2 rounded" style="max-height: 120px; overflow-y: auto;">`;
        
        resultados.errores.forEach(function(error) {
            htmlContent += `<div class="small"><code class="text-danger">${error.codigo}</code>: ${error.mensaje}</div>`;
        });
        
        htmlContent += `</div></div>`;
    }
    
    htmlContent += `</div>`;
    
    // Determinar el ícono y color según los resultados
    let icon = 'success';
    let title = '¡Sincronización Completada!';
    
    if (stats.errores > 0 && stats.exitosos === 0) {
        icon = 'error';
        title = 'Sincronización con Errores';
    } else if (stats.errores > 0) {
        icon = 'warning';
        title = 'Sincronización Parcialmente Exitosa';
    }
    
    Swal.fire({
        icon: icon,
        title: title,
        html: htmlContent,
        width: '600px',
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#2563eb',
        customClass: {
            popup: 'text-start'
        }
    });
}

function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: mensaje,
        confirmButtonColor: '#10b981',
        timer: 3000,
        timerProgressBar: true
    });
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje,
        confirmButtonColor: '#ef4444'
    });
}

function mostrarInfo(mensaje) {
    Swal.fire({
        icon: 'info',
        title: 'Información',
        text: mensaje,
        confirmButtonColor: '#2563eb'
    });
}
</script>
<?= $this->endSection() ?>
