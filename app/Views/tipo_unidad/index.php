<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-tags text-primary"></i>
                        <?= $page_title ?>
                    </h1>
                    <p class="text-muted mb-0">Administra los tipos de unidad del sistema</p>
                </div>
                <div>
                    <a href="<?= base_url('tipo-unidad/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Tipo de Unidad
                    </a>
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
                                Total Tipos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-tipos">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
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
                                Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tipos-activos">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
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
                                Inactivos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tipos-inactivos">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                                Última Actualización
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <?= date('d/m/Y H:i') ?>
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
            <div class="row">
                <div class="col-md-4">
                    <label for="filtro-estado" class="form-label">Estado</label>
                    <select class="form-select" id="filtro-estado">
                        <option value="">Todos los estados</option>
                        <option value="ACTIVO">Activo</option>
                        <option value="INACTIVO">Inactivo</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="filtro-busqueda" class="form-label">Búsqueda</label>
                    <input type="text" class="form-control" id="filtro-busqueda" 
                           placeholder="Buscar por descripción...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-secondary w-100" id="btn-limpiar-filtros">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de datos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> Lista de Tipos de Unidad
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabla-tipos" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Fecha Actualización</th>
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

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modal-eliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este tipo de unidad?</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                <div class="alert alert-info">
                    <strong>Tipo de Unidad:</strong> <span id="tipo-eliminar-nombre"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="btn-confirmar-eliminar">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let tabla;
    let tipoIdEliminar = null;

    // Función para verificar si las dependencias están disponibles
    function verificarDependencias() {
        return new Promise((resolve, reject) => {
            let intentos = 0;
            const maxIntentos = 50; // 5 segundos máximo
            
            function verificar() {
                if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                    resolve(true);
                } else if (intentos < maxIntentos) {
                    intentos++;
                    setTimeout(verificar, 100); // Esperar 100ms antes del siguiente intento
                } else {
                    console.warn('jQuery o DataTables no están disponibles después de 5 segundos');
                    resolve(false); // Continuar sin DataTables
                }
            }
            
            verificar();
        });
    }

    // Inicializar DataTable
    async function inicializarTabla() {
        const tablaElement = document.getElementById('tabla-tipos');
        if (!tablaElement) {
            console.error('Elemento tabla-tipos no encontrado');
            return;
        }
        
        // Verificar dependencias antes de inicializar DataTable
        const dependenciasDisponibles = await verificarDependencias();
        if (!dependenciasDisponibles) {
            console.warn('DataTables no disponible, tabla funcionará sin paginación avanzada');
            return;
        }

        tabla = $('#tabla-tipos').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('tipo-unidad/getData') ?>',
                type: 'POST',
                data: function(d) {
                    const filtroEstado = document.getElementById('filtro-estado');
                    const filtroBusqueda = document.getElementById('filtro-busqueda');
                    d.estado = filtroEstado ? filtroEstado.value : '';
                    d.busqueda = filtroBusqueda ? filtroBusqueda.value : '';
                }
            },
            columns: [
                { data: 'id', title: 'ID', width: '80px' },
                { data: 'descripcion', title: 'Descripción' },
                { 
                    data: 'estado', 
                    title: 'Estado',
                    width: '120px',
                    render: function(data, type, row) {
                        const badgeClass = data === 'ACTIVO' ? 'bg-success' : 'bg-secondary';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                { 
                    data: 'fechaRegistra', 
                    title: 'Fecha Registro',
                    width: '150px',
                    render: function(data) {
                        return data ? new Date(data).toLocaleDateString('es-ES') : '';
                    }
                },
                { 
                    data: 'fechaActualiza', 
                    title: 'Fecha Actualización',
                    width: '150px',
                    render: function(data) {
                        return data ? new Date(data).toLocaleDateString('es-ES') : '';
                    }
                },
                { 
                    data: 'acciones', 
                    title: 'Acciones',
                    orderable: false,
                    searchable: false,
                    width: '200px'
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });
    }

    // Cargar estadísticas
    function cargarEstadisticas() {
        fetch('<?= base_url('tipo-unidad/getEstadisticas') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const totalElement = document.getElementById('total-tipos');
                const activosElement = document.getElementById('tipos-activos');
                const inactivosElement = document.getElementById('tipos-inactivos');
                
                if (totalElement) totalElement.textContent = data.data.total;
                if (activosElement) activosElement.textContent = data.data.activos;
                if (inactivosElement) inactivosElement.textContent = data.data.inactivos;
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas:', error);
            const totalElement = document.getElementById('total-tipos');
            const activosElement = document.getElementById('tipos-activos');
            const inactivosElement = document.getElementById('tipos-inactivos');
            
            if (totalElement) totalElement.textContent = '0';
            if (activosElement) activosElement.textContent = '0';
            if (inactivosElement) inactivosElement.textContent = '0';
        });
    }

    // Configurar event listeners
    function setupEventListeners() {
        // Event listeners para filtros
        const filtroEstado = document.getElementById('filtro-estado');
        const filtroBusqueda = document.getElementById('filtro-busqueda');
        const btnLimpiar = document.getElementById('btn-limpiar-filtros');
        const btnConfirmarEliminar = document.getElementById('btn-confirmar-eliminar');

        if (filtroEstado) {
            filtroEstado.addEventListener('change', function() {
                if (tabla) tabla.ajax.reload();
            });
        }

        if (filtroBusqueda) {
            filtroBusqueda.addEventListener('keyup', function() {
                if (tabla) tabla.ajax.reload();
            });
        }

        // Limpiar filtros
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function() {
                if (filtroEstado) filtroEstado.value = '';
                if (filtroBusqueda) filtroBusqueda.value = '';
                if (tabla) tabla.ajax.reload();
            });
        }

        // Confirmar eliminación
        if (btnConfirmarEliminar) {
            btnConfirmarEliminar.addEventListener('click', function() {
                if (tipoIdEliminar) {
                    fetch(`<?= base_url('tipo-unidad/delete') ?>/${tipoIdEliminar}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modal-eliminar'));
                        if (modal) modal.hide();
                        
                        if (data.success) {
                            mostrarAlerta('success', data.message);
                            if (tabla) tabla.ajax.reload();
                            cargarEstadisticas();
                        } else {
                            mostrarAlerta('error', data.message);
                        }
                        tipoIdEliminar = null;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modal-eliminar'));
                        if (modal) modal.hide();
                        mostrarAlerta('error', 'Error al eliminar el tipo de unidad');
                        tipoIdEliminar = null;
                    });
                }
            });
        }
    }

    // Event delegation para botones dinámicos
    function setupDynamicEventListeners() {
        document.addEventListener('click', function(e) {
            // Cambiar estado
            if (e.target.closest('.btn-cambiar-estado')) {
                const btn = e.target.closest('.btn-cambiar-estado');
                const id = btn.getAttribute('data-id');
                const nuevoEstado = btn.getAttribute('data-estado');

                if (confirm(`¿Está seguro que desea cambiar el estado a ${nuevoEstado}?`)) {
                    fetch('<?= base_url('tipo-unidad/cambiarEstado') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: `id=${id}&estado=${nuevoEstado}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            mostrarAlerta('success', data.message);
                            if (tabla) tabla.ajax.reload();
                            cargarEstadisticas();
                        } else {
                            mostrarAlerta('error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mostrarAlerta('error', 'Error al cambiar el estado');
                    });
                }
            }

            // Eliminar
            if (e.target.closest('.btn-eliminar')) {
                const btn = e.target.closest('.btn-eliminar');
                tipoIdEliminar = btn.getAttribute('data-id');
                const descripcion = btn.getAttribute('data-descripcion');
                
                const nombreElement = document.getElementById('tipo-eliminar-nombre');
                if (nombreElement) {
                    nombreElement.textContent = descripcion;
                }
                
                const modal = new bootstrap.Modal(document.getElementById('modal-eliminar'));
                modal.show();
            }
        });
    }

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const icon = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alerta = document.createElement('div');
        alerta.className = `alert ${alertClass} alert-dismissible fade show`;
        alerta.setAttribute('role', 'alert');
        alerta.innerHTML = `
            <i class="fas ${icon}"></i> ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertBefore(alerta, container.firstChild);
        }
        
        // Auto-hide después de 5 segundos
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.remove();
            }
        }, 5000);
    }

    // Inicializar todo
    async function inicializar() {
        setupEventListeners();
        setupDynamicEventListeners();
        await inicializarTabla(); // Esperar a que se inicialice la tabla
        cargarEstadisticas();
    }
    
    // Ejecutar inicialización
    inicializar();
});
</script>
<?= $this->endSection() ?>
