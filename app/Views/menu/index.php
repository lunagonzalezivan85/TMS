<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-sitemap"></i> <?= $title ?>
            </h1>
            <p class="mb-0 text-muted">Gestiona la estructura de menús del sistema</p>
        </div>
        <a href="<?= base_url('menu/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Menú
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Menús
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $estadisticas['total_menus'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
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
                                Menús Padre
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $estadisticas['menus_padre'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
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
                                Submenús
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $estadisticas['submenus'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-gray-300"></i>
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
                                Nivel Máximo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $estadisticas['nivel_maximo'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="menuTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tabla-tab" data-bs-toggle="tab" data-bs-target="#tabla" type="button" role="tab">
                        <i class="fas fa-table"></i> Vista de Tabla
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="arbol-tab" data-bs-toggle="tab" data-bs-target="#arbol" type="button" role="tab">
                        <i class="fas fa-sitemap"></i> Vista de Árbol
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="menuTabsContent">
                <!-- Vista de Tabla -->
                <div class="tab-pane fade" id="tabla" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tablaMenus" width="100%" cellspacing="0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Icono</th>
                                    <th>Menú</th>
                                    <th>Ruta</th>
                                    <th>Menú Padre</th>
                                    <th>Nivel</th>
                                    <th>Submenús</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos cargados por AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Vista de Árbol -->
                <div class="tab-pane fade show active" id="arbol" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div id="menuTree">
                                <?= $this->include('menu/tree_view', ['menus' => $menus]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEliminarLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este menú?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-warning"></i>
                    <strong>Advertencia:</strong> Esta acción no se puede deshacer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="confirmarEliminar">
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
    // Inicializar DataTable
    let tabla = $('#tablaMenus').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('menu/getData') ?>',
            type: 'POST'
        },
        columns: [
            { data: 'id', width: '5%' },
            { data: 'icono', width: '8%', orderable: false, searchable: false },
            { data: 'menu', width: '18%' },
            { data: 'ruta', width: '15%' },
            { data: 'padre', width: '18%' },
            { data: 'nivel', width: '8%' },
            { data: 'submenus', width: '10%' },
            { data: 'fecha_registra', width: '15%' },
            { data: 'acciones', width: '14%', orderable: false, searchable: false }
        ],
        order: [[4, 'asc'], [2, 'asc']], // Ordenar por nivel y luego por nombre
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-info btn-sm'
            }
        ]
    });

    // Variable para almacenar el ID del menú a eliminar
    let menuIdEliminar = null;

    // Manejar click en botón eliminar
    $(document).on('click', '.eliminar-menu', function() {
        menuIdEliminar = $(this).data('id');
        $('#modalEliminar').modal('show');
    });

    // Confirmar eliminación
    $('#confirmarEliminar').click(function() {
        if (menuIdEliminar) {
            $.ajax({
                url: '<?= base_url('menu/delete') ?>/' + menuIdEliminar,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    $('#modalEliminar').modal('hide');
                    
                    if (response.success) {
                        showAlert('success', response.message);
                        tabla.ajax.reload();
                        
                        // Recargar vista de árbol si está activa
                        if ($('#arbol-tab').hasClass('active')) {
                            location.reload();
                        }
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function() {
                    $('#modalEliminar').modal('hide');
                    showAlert('error', 'Error al eliminar el menú');
                }
            });
        }
    });

    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('.container-fluid').prepend(alert);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }

    // Manejar cambio de pestañas
    $('#menuTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        if ($(e.target).attr('id') === 'tabla-tab') {
            tabla.columns.adjust().responsive.recalc();
        }
    });
});
</script>
<?= $this->endSection() ?>
