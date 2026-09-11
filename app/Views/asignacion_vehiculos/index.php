<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Asignación de Vehículos</li>
        </ol>
    </nav>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $estadisticas['activas'] ?></h4>
                            <p class="card-text">Asignaciones Activas</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-link fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $estadisticas['vehiculos_disponibles'] ?></h4>
                            <p class="card-text">Vehículos Disponibles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-car fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $estadisticas['conductores_disponibles'] ?></h4>
                            <p class="card-text">Conductores Disponibles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $estadisticas['total'] ?></h4>
                            <p class="card-text">Total Asignaciones</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-history fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-link me-2"></i>
                        Gestión de Asignaciones
                    </h5>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('asignacion-vehiculos/wizard') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nueva Asignación
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filtro_estado" class="form-label">Estado</label>
                    <select class="form-select" id="filtro_estado">
                        <option value="">Todos los estados</option>
                        <option value="ACTIVA">Activas</option>
                        <option value="INACTIVA">Inactivas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro_tipo_unidad" class="form-label">Tipo de Unidad</label>
                    <select class="form-select" id="filtro_tipo_unidad">
                        <option value="">Todos los tipos</option>
                        <?php foreach ($tipos_unidad as $tipo): ?>
                            <option value="<?= $tipo['id'] ?>"><?= esc($tipo['descripcion']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filtro_conductor" class="form-label">Buscar Conductor</label>
                    <input type="text" class="form-control" id="filtro_conductor" placeholder="Nombre o DNI del conductor">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-secondary" id="btnLimpiarFiltros">
                            <i class="fas fa-broom me-1"></i>Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaAsignaciones">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Vehículo</th>
                            <th>Conductor</th>
                            <th>Tipo de Unidad</th>
                            <th>Fecha Asignación</th>
                            <th>Fecha Desasignación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos cargados por AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="mensajeConfirmacion"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmar">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para verificar dependencias de manera asíncrona
function verificarDependencias() {
    return new Promise((resolve, reject) => {
        let intentos = 0;
        const maxIntentos = 50; // 5 segundos máximo
        
        function verificar() {
            if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                resolve(true);
            } else if (intentos < maxIntentos) {
                intentos++;
                setTimeout(verificar, 100); // Esperar 100ms
            } else {
                resolve(false); // Continuar sin DataTables
            }
        }
        verificar();
    });
}

// Función para inicializar la tabla
async function inicializarTabla() {
    const dependenciasDisponibles = await verificarDependencias();
    
    if (!dependenciasDisponibles) {
        console.warn('jQuery o DataTables no están disponibles después de 5 segundos');
        return;
    }
    
    let tabla;
    
    // Verificar si ya existe una instancia de DataTable y destruirla
    if ($.fn.DataTable.isDataTable('#tablaAsignaciones')) {
        $('#tablaAsignaciones').DataTable().destroy();
        console.log('DataTable anterior destruido');
    }
    
    // Inicializar DataTable
    tabla = $('#tablaAsignaciones').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url('asignacion-vehiculos/getData') ?>',
            type: 'GET',
            data: function(d) {
                d.estado = $('#filtro_estado').val();
                d.tipo_unidad = $('#filtro_tipo_unidad').val();
                d.conductor = $('#filtro_conductor').val();
            },
            error: function(xhr, error, thrown) {
                console.error('Error en AJAX DataTables:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error,
                    thrown: thrown
                });
                
                // Mostrar alerta al usuario
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <strong>Error de conexión:</strong> No se pudieron cargar los datos de asignaciones.
                    <br><small>Estado: ${xhr.status} - ${xhr.statusText}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                const container = document.querySelector('.container-fluid');
                container.insertBefore(alertDiv, container.firstChild);
            },
            dataSrc: function(json) {
                console.log('Respuesta del servidor:', json);
                
                if (json.error) {
                    console.error('Error del servidor:', json.error);
                    return [];
                }
                
                return json.data || [];
            }
        },
        columns: [
            { data: 'id', width: '5%' },
            { data: 'vehiculo', width: '20%' },
            { data: 'conductor', width: '15%' },
            { data: 'tipo_unidad', width: '15%' },
            { data: 'fecha_asignacion', width: '12%' },
            { data: 'fecha_desasignacion', width: '12%' },
            { data: 'estado', width: '10%' },
            { data: 'acciones', width: '11%', orderable: false }
        ],
        order: [[0, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
        },
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i>Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i>PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i>Imprimir',
                className: 'btn btn-info btn-sm'
            }
        ]
    });

    // Configurar event listeners
    configurarEventListeners(tabla);
}

// Función para configurar event listeners
function configurarEventListeners(tabla) {
    // Aplicar filtros
    $('#filtro_estado, #filtro_tipo_unidad').on('change', function() {
        tabla.ajax.reload();
    });

    $('#filtro_conductor').on('keyup', function() {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(function() {
            tabla.ajax.reload();
        }, 500);
    });

    // Limpiar filtros
    $('#btnLimpiarFiltros').on('click', function() {
        $('#filtro_estado').val('');
        $('#filtro_tipo_unidad').val('');
        $('#filtro_conductor').val('');
        tabla.ajax.reload();
    });

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Actualizar estadísticas cada 30 segundos
    setInterval(function() {
        actualizarEstadisticas();
    }, 30000);
}

// Función para actualizar estadísticas
function actualizarEstadisticas() {
    fetch('<?= base_url('asignacion-vehiculos/getEstadisticas') ?>')
        .then(response => response.json())
        .then(data => {
            if (!data.error) {
                document.querySelector('.bg-success .card-title').textContent = data.activas;
                document.querySelector('.bg-info .card-title').textContent = data.vehiculos_disponibles;
                document.querySelector('.bg-warning .card-title').textContent = data.conductores_disponibles;
                document.querySelector('.bg-secondary .card-title').textContent = data.total;
            }
        })
        .catch(error => console.error('Error actualizando estadísticas:', error));
}

// Variable para controlar si ya se inicializó
let tablaInicializada = false;

// Función principal de inicialización
async function inicializar() {
    if (tablaInicializada) {
        console.log('Tabla ya inicializada, omitiendo reinicialización');
        return;
    }
    
    await inicializarTabla();
    tablaInicializada = true;
    
    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Cargar estadísticas iniciales
    actualizarEstadisticas();
}

// Inicializar cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    inicializar();
});

// Función global para que el layout pueda llamarla
window.initDataTable = function() {
    if (!tablaInicializada) {
        inicializar();
    }
};

// Función para confirmar desasignación
function confirmarDesasignacion(id, vehiculo, conductor) {
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    document.getElementById('mensajeConfirmacion').innerHTML = 
        `¿Está seguro de desasignar el vehículo <strong>${vehiculo}</strong> del conductor <strong>${conductor}</strong>?`;
    
    document.getElementById('btnConfirmar').onclick = function() {
        window.location.href = `<?= base_url('asignacion-vehiculos/desasignar/') ?>${id}`;
    };
    
    modal.show();
}
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 2px;
}

.dt-buttons {
    margin-bottom: 1rem;
}

.dt-button {
    margin-right: 5px !important;
}
</style>
<?= $this->endSection() ?>
