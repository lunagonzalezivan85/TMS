<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users text-primary"></i> <?= $page_title ?>
        </h1>
        <div class="btn-group">
            <a href="<?= base_url('conductores/sincronizar') ?>" class="btn btn-outline-primary shadow-sm">
                <i class="fas fa-sync-alt"></i> Sincronizar con ERP
            </a>
            <a href="<?= base_url('conductores/create') ?>" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Registrar Conductor
            </a>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Conductores
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-conductores">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Conductores Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="conductores-activos">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Con Vehículo Asignado
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="conductores-con-vehiculo">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
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
                                Licencias por Vencer
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="licencias-por-vencer">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="filtro-estado">Estado</label>
                        <select class="form-control" id="filtro-estado">
                            <option value="">Todos los estados</option>
                            <option value="ACTIVO">Activo</option>
                            <option value="INACTIVO">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="filtro-buscar">Buscar</label>
                        <input type="text" class="form-control" id="filtro-buscar" placeholder="Nombre, apellido o DNI">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-primary" id="aplicar-filtros">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-secondary" id="limpiar-filtros">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Conductores -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> Lista de Conductores
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="conductoresTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombres Completos</th>
                            <th>Documento</th>
                            <th>Fecha Ingreso</th>
                            <th>Vehículos</th>
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

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado del Conductor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCambiarEstado">
                    <input type="hidden" id="conductor-id" name="id">
                    <input type="hidden" id="nuevo-estado" name="estado">
                    
                    <div class="form-group">
                        <label for="motivo-cambio">Motivo del cambio:</label>
                        <textarea class="form-control" id="motivo-cambio" name="motivo" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmar-cambio-estado">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este conductor?</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                <input type="hidden" id="eliminar-conductor-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmar-eliminacion">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Hacer la función accesible globalmente
window.initDataTable = function() {
    // Definir base_url para uso en JavaScript
    const base_url = '<?= base_url() ?>';
    
    let conductoresTable;
    
    // Verificar si la tabla ya está inicializada
    if ($.fn.DataTable.isDataTable('#conductoresTable')) {
        // Si ya está inicializada, destruirla primero
        $('#conductoresTable').DataTable().destroy();
    }
    
    // Inicializar DataTable
    conductoresTable = new DataTable('#conductoresTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('conductores/getData') ?>',
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: function(d) {
                    d.estado = document.getElementById('filtro-estado').value;
                    d.search = document.getElementById('filtro-buscar').value;
                }
            },
            columns: [
                { 
                    data: 'codigo_consecutivo',
                    title: 'Código',
                    className: 'text-center'
                },
                { 
                    data: null,
                    title: 'Nombre Completo',
                    render: function(data, type, row) {
                        return `${row.nombre || ''} ${row.apellido || ''}`.trim() || '-';
                    }
                },
                { 
                    data: 'dni',
                    title: 'DNI',
                    className: 'text-center'
                },
                { 
                    data: 'fechaIngreso',
                    title: 'Fecha Ingreso',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (!data || data === '0000-00-00') return '-';
                        const date = new Date(data);
                        return date.toLocaleDateString('es-ES');
                    }
                },
                { 
                    data: 'vehiculos_asignados',
                    title: 'Vehículos',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return data || '0';
                    }
                },
                { 
                    data: 'estado',
                    title: 'Estado',
                    className: 'text-center',
                    render: function(data, type, row) {
                        const estado = data || 'INACTIVO';
                        const clase = estado === 'ACTIVO' ? 'badge-success' : 'badge-secondary';
                        return `<span class="badge ${clase}">${estado}</span>`;
                    }
                },
                {
                    data: 'id',
                    title: 'Acciones',
                    className: 'text-center',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const estadoBtn = row.estado === 'ACTIVO'
                            ? `<li><button class="dropdown-item text-secondary cambiar-estado" data-id="${data}" data-estado="INACTIVO"><i class="fas fa-pause me-2"></i>Desactivar</button></li>`
                            : `<li><button class="dropdown-item text-success cambiar-estado" data-id="${data}" data-estado="ACTIVO"><i class="fas fa-play me-2"></i>Activar</button></li>`;
                        return `
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a href="${base_url}conductores/show/${data}" class="dropdown-item"><i class="fas fa-eye me-2 text-info"></i>Ver detalles</a></li>
                                    <li><a href="${base_url}conductores/edit/${data}" class="dropdown-item"><i class="fas fa-edit me-2 text-primary"></i>Editar</a></li>
                                    <li><a href="${base_url}conductores/documentos/${data}" class="dropdown-item"><i class="fas fa-file-alt me-2 text-warning"></i>Documentos</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    ${estadoBtn}
                                    <li><button class="dropdown-item text-danger eliminar-conductor" data-id="${data}"><i class="fas fa-trash me-2"></i>Eliminar</button></li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 25
        });

    // Cargar estadísticas
    function cargarEstadisticas() {
        fetch('<?= base_url('conductores/getEstadisticas') ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-conductores').textContent = data.total_conductores || 0;
            document.getElementById('conductores-activos').textContent = data.conductores_activos || 0;
            document.getElementById('conductores-con-vehiculo').textContent = data.conductores_con_vehiculo || 0;
            document.getElementById('licencias-por-vencer').textContent = data.licencias_por_vencer || 0;
        })
        .catch(error => {
            console.error('Error al cargar estadísticas:', error);
            document.getElementById('total-conductores').textContent = '0';
            document.getElementById('conductores-activos').textContent = '0';
            document.getElementById('conductores-con-vehiculo').textContent = '0';
            document.getElementById('licencias-por-vencer').textContent = '0';
        });
    }

    // Aplicar filtros
    document.getElementById('aplicar-filtros').addEventListener('click', function() {
        conductoresTable.ajax.reload();
    });

    // Limpiar filtros
    document.getElementById('limpiar-filtros').addEventListener('click', function() {
        document.getElementById('filtro-estado').value = '';
        document.getElementById('filtro-tipo-licencia').value = '';
        conductoresTable.ajax.reload();
    });

    // Cambiar estado
    document.addEventListener('click', function(e) {
        if (e.target.closest('.cambiar-estado')) {
            e.preventDefault();
            const btn = e.target.closest('.cambiar-estado');
            const id = btn.dataset.id;
            const estado = btn.dataset.estado;
            
            document.getElementById('conductor-id').value = id;
            document.getElementById('nuevo-estado').value = estado;
            document.getElementById('motivo-cambio').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('modalCambiarEstado'));
            modal.show();
        }
    });

    // Confirmar cambio de estado
    document.getElementById('confirmar-cambio-estado').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('formCambiarEstado'));
        
        fetch('<?= base_url('conductores/cambiarEstado') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                conductoresTable.ajax.reload();
                cargarEstadisticas();
                bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
            } else {
                mostrarAlerta('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al cambiar el estado');
        });
    });

    // Eliminar conductor
    document.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-conductor')) {
            e.preventDefault();
            const btn = e.target.closest('.eliminar-conductor');
            const id = btn.dataset.id;
            
            document.getElementById('eliminar-conductor-id').value = id;
            
            const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modal.show();
        }
    });

    // Confirmar eliminación
    document.getElementById('confirmar-eliminacion').addEventListener('click', function() {
        const id = document.getElementById('eliminar-conductor-id').value;
        
        fetch(`<?= base_url('conductores/delete') ?>/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-HTTP-Method-Override': 'DELETE'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                conductoresTable.ajax.reload();
                cargarEstadisticas();
                bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
            } else {
                mostrarAlerta('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al eliminar el conductor');
        });
    });

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        const alertaHtml = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.container-fluid');
        const firstCard = container.querySelector('.card');
        firstCard.insertAdjacentHTML('beforebegin', alertaHtml);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            const alerta = container.querySelector('.alert');
            if (alerta) {
                alerta.remove();
            }
        }, 5000);
    }

    // Inicializar estadísticas
    cargarEstadisticas();
}; // Fin de window.initDataTable

// Nota: La inicialización se maneja automáticamente desde el layout principal (main.php)
// para evitar doble inicialización de DataTables
</script>
<?= $this->endSection() ?>
