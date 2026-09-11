<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumb as $item): ?>
                <?php if (empty($item['url'])): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($item['name']) ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="<?= esc($item['url']) ?>"><?= esc($item['name']) ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-key text-primary me-2"></i>
            Gestión de Accesos
        </h1>
        <a href="<?= base_url('acceso/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Nuevo Acceso
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4" id="estadisticas-container">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Accesos</h5>
                            <h2 class="mb-0" id="total-accesos">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-key fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Activos</h5>
                            <h2 class="mb-0" id="accesos-activos">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Inactivos</h5>
                            <h2 class="mb-0" id="accesos-inactivos">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Roles con Acceso</h5>
                            <h2 class="mb-0" id="roles-con-acceso">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Accesos -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Lista de Accesos
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accesos-table" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Rol</th>
                            <th>Menú</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargan via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este acceso?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let table;
    let accesoAEliminar = null;

    // Inicializar DataTable
    function inicializarDataTable() {
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            table = $('#accesos-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?= base_url('acceso/getData') ?>',
                    type: 'POST',
                    error: function(xhr, error, code) {
                        console.error('Error en DataTable:', error);
                        mostrarAlerta('Error al cargar los datos', 'error');
                    }
                },
                columns: [
                    { data: 'id', width: '60px' },
                    { data: 'rol_nombre' },
                    { data: 'menu_nombre' },
                    { data: 'estado', orderable: false },
                    { data: 'fecha_registro' },
                    { data: 'acciones', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
                pageLength: 25,
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                drawCallback: function() {
                    // Re-inicializar tooltips después de cada draw
                    if (typeof bootstrap !== 'undefined') {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                        tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }
                }
            });
        } else {
            console.error('DataTables no está disponible');
            mostrarAlerta('Error: DataTables no está cargado', 'error');
        }
    }

    // Cargar estadísticas
    function cargarEstadisticas() {
        fetch('<?= base_url('acceso/getEstadisticas') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-accesos').textContent = data.data.total_accesos || 0;
                document.getElementById('accesos-activos').textContent = data.data.accesos_activos || 0;
                document.getElementById('accesos-inactivos').textContent = data.data.accesos_inactivos || 0;
                document.getElementById('roles-con-acceso').textContent = data.data.roles_con_acceso || 0;
            } else {
                console.error('Error al cargar estadísticas:', data.message);
                // Mostrar 0 en caso de error
                document.getElementById('total-accesos').textContent = '0';
                document.getElementById('accesos-activos').textContent = '0';
                document.getElementById('accesos-inactivos').textContent = '0';
                document.getElementById('roles-con-acceso').textContent = '0';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Mostrar 0 en caso de error
            document.getElementById('total-accesos').textContent = '0';
            document.getElementById('accesos-activos').textContent = '0';
            document.getElementById('accesos-inactivos').textContent = '0';
            document.getElementById('roles-con-acceso').textContent = '0';
        });
    }

    // Cambiar estado del acceso
    window.cambiarEstadoAcceso = function(id) {
        if (!confirm('¿Está seguro que desea cambiar el estado de este acceso?')) {
            return;
        }

        fetch(`<?= base_url('acceso/cambiarEstado') ?>/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                if (table) {
                    table.ajax.reload(null, false);
                }
                cargarEstadisticas();
            } else {
                mostrarAlerta(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al cambiar el estado del acceso', 'error');
        });
    };

    // Eliminar acceso
    window.eliminarAcceso = function(id) {
        accesoAEliminar = id;
        const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
        modal.show();
    };

    // Confirmar eliminación
    document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
        if (!accesoAEliminar) return;

        fetch(`<?= base_url('acceso/delete') ?>/${accesoAEliminar}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                if (table) {
                    table.ajax.reload(null, false);
                }
                cargarEstadisticas();
            } else {
                mostrarAlerta(data.message, 'error');
            }
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminar'));
            modal.hide();
            accesoAEliminar = null;
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al eliminar el acceso', 'error');
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminar'));
            modal.hide();
            accesoAEliminar = null;
        });
    });

    // Función para mostrar alertas
    function mostrarAlerta(mensaje, tipo) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass} me-2"></i>
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Insertar alerta al inicio del container
        const container = document.querySelector('.container-fluid');
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }

    // Verificar si jQuery y DataTables están disponibles
    function verificarDependencias() {
        let intentos = 0;
        const maxIntentos = 50; // 5 segundos máximo
        
        const verificar = () => {
            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                inicializarDataTable();
                cargarEstadisticas();
                return;
            }
            
            intentos++;
            if (intentos < maxIntentos) {
                setTimeout(verificar, 100);
            } else {
                console.error('jQuery o DataTables no se cargaron correctamente');
                mostrarAlerta('Error: No se pudieron cargar las dependencias necesarias', 'error');
                // Cargar al menos las estadísticas
                cargarEstadisticas();
            }
        };
        
        verificar();
    }

    // Inicializar
    verificarDependencias();
});
</script>
<?= $this->endSection() ?>
