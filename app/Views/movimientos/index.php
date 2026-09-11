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
                    <li class="breadcrumb-item active">Movimientos</li>
                </ol>
            </nav>
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMovimientos" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-plus"></i> Crear Movimiento
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMovimientos">
                <a class="dropdown-item" href="<?= base_url('movimientos/create?tipo=SALIDA') ?>">
                    <i class="fas fa-arrow-up text-warning"></i> Crear Requisa de Salida
                </a>
                <!--<a class="dropdown-item" href="<?= base_url('movimientos/create?tipo=ENTRADA') ?>">
                    <i class="fas fa-arrow-down text-success"></i> Ingresar Orden de Compras
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= base_url('movimientos/create?tipo=AJUSTE_POSITIVO') ?>">
                    <i class="fas fa-plus-circle text-info"></i> Realizar Ajuste de Materiales
                </a>
                <a class="dropdown-item" href="<?= base_url('movimientos/create?tipo=TRANSFERENCIA') ?>">
                    <i class="fas fa-exchange-alt text-primary"></i> Realizar Traslados
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= base_url('movimientos/create') ?>">
                    <i class="fas fa-cog text-secondary"></i> Crear Movimiento Personalizado
                </a>-->
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4" id="estadisticas-container">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Movimientos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-movimientos">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
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
                                Total Entradas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-entradas">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
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
                                Total Salidas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-salidas">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
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
                                Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendientes">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <form id="filtros-form">
                <div class="row">
                    <div class="col-md-3">
                        <label for="tipo_movimiento">Tipo de Movimiento</label>
                        <select class="form-control" id="tipo_movimiento" name="tipo_movimiento">
                            <option value="">Todos los tipos</option>
                            <?php foreach ($tipos_movimiento as $key => $tipo): ?>
                                <option value="<?= $key ?>"><?= esc($tipo) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="">Todos los estados</option>
                            <?php foreach ($estados as $key => $estado): ?>
                                <option value="<?= $key ?>"><?= esc($estado) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_desde">Fecha Desde</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_hasta">Fecha Hasta</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" id="aplicar-filtros">
                            <i class="fas fa-search"></i> Aplicar Filtros
                        </button>
                        <button type="button" class="btn btn-secondary" id="limpiar-filtros">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Lista de Movimientos
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="movimientosTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Items</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargan vía AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar el movimiento <strong id="codigo-eliminar"></strong>?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmar-eliminar">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let table;
    let movimientoIdEliminar = null;

    // Inicializar DataTable
    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#movimientosTable')) {
            $('#movimientosTable').DataTable().destroy();
        }

        table = $('#movimientosTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('movimientos/getData') ?>',
                type: 'POST',
                data: function(d) {
                    d.tipo_movimiento = $('#tipo_movimiento').val();
                    d.estado = $('#estado').val();
                    d.fecha_desde = $('#fecha_desde').val();
                    d.fecha_hasta = $('#fecha_hasta').val();
                }
            },
            columns: [
                { data: 'codigo' },
                { data: 'tipo_movimiento' },
                { data: 'fecha_movimiento' },
                { data: 'referencia' },
                { data: 'total_items', className: 'text-center' },
                { data: 'monto', className: 'text-right' },
                { data: 'estado', className: 'text-center' },
                { data: 'acciones', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[2, 'desc']], // Ordenar por fecha descendente
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });
    }

    // Aplicar filtros
    $('#aplicar-filtros').click(function() {
        table.ajax.reload();
        cargarEstadisticas();
    });

    // Limpiar filtros
    $('#limpiar-filtros').click(function() {
        $('#filtros-form')[0].reset();
        table.ajax.reload();
        cargarEstadisticas();
    });

    // Eliminar movimiento
    $(document).on('click', '.eliminar-movimiento', function() {
        movimientoIdEliminar = $(this).data('id');
        const codigo = $(this).data('codigo');
        $('#codigo-eliminar').text(codigo);
        $('#eliminarModal').modal('show');
    });

    // Confirmar eliminación
    $('#confirmar-eliminar').click(function() {
        if (movimientoIdEliminar) {
            $.ajax({
                url: '<?= base_url('movimientos/delete') ?>/' + movimientoIdEliminar,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    $('#eliminarModal').modal('hide');
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        table.ajax.reload();
                        cargarEstadisticas();
                    } else {
                        mostrarAlerta('error', response.message);
                    }
                },
                error: function() {
                    $('#eliminarModal').modal('hide');
                    mostrarAlerta('error', 'Error al eliminar el movimiento');
                }
            });
        }
    });

    // Cargar estadísticas
    function cargarEstadisticas() {
        const filtros = {
            fecha_desde: $('#fecha_desde').val(),
            fecha_hasta: $('#fecha_hasta').val()
        };

        $.ajax({
            url: '<?= base_url('movimientos/getEstadisticas') ?>',
            type: 'GET',
            data: filtros,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#total-movimientos').text(data.total_movimientos || 0);
                    $('#total-entradas').text('$' + (parseFloat(data.total_entradas || 0).toLocaleString()));
                    $('#total-salidas').text('$' + (parseFloat(data.total_salidas || 0).toLocaleString()));
                    $('#pendientes').text(data.pendientes || 0);
                }
            },
            error: function() {
                console.error('Error al cargar estadísticas');
            }
        });
    }

    // Mostrar alertas
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

    // Inicializar
    initDataTable();
    cargarEstadisticas();
});
</script>
<?= $this->endSection() ?>
