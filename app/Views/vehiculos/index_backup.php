<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold"><?= $page_title ?></h1>
            <p class="mb-0 text-muted">Gestiona los vehículos de tu flota en tiempo real</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('vehiculos/reportes') ?>" class="btn btn-light shadow-sm border">
                <i class="fas fa-chart-bar me-2 text-info"></i>Reportes
            </a>
            <?= buttonIfAllowed('vehiculos/create', '<i class="fas fa-plus me-2"></i>Agregar Vehículo', 'btn btn-primary shadow-sm px-4') ?>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeInUp" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px);">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="filtro_estado" class="form-label small fw-bold text-muted">ESTADO</label>
                    <div class="input-group overflow-hidden rounded-pill border shadow-sm">
                        <span class="input-group-text bg-white border-0"><i class="fas fa-filter text-muted"></i></span>
                        <select class="form-select border-0 ps-0" id="filtro_estado">
                            <option value="">Todos los estados</option>
                            <option value="ACTIVO">Activo</option>
                            <option value="INACTIVO">Inactivo</option>
                            <option value="EN REPARACION">En Reparación</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-9">
                    <label for="filtro_buscar" class="form-label small fw-bold text-muted">BUSCAR VEHÍCULO</label>
                    <div class="input-group overflow-hidden rounded-pill border shadow-sm">
                        <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-0 py-2" id="filtro_buscar" placeholder="Placa, marca, modelo, código...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="card border-0 shadow-sm h-100 py-2 overflow-hidden" style="border-left: 4px solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Vehículos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat_total">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat_activos">-</div>
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
                                En Reparación
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat_reparacion">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
                                Sin Conductor
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat_sin_conductor">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de vehículos -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Vehículos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="vehiculosTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Placa</th>
                            <th>Vehículo</th>
                            <th>Kilometraje</th>
                            <th>Conductor</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargan vía AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="dataTables_info" style="padding-top: 8px;">
                    Cargando...
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado del Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body">
                    <input type="hidden" id="vehiculo_id" name="id">
                    <input type="hidden" id="nuevo_estado" name="estado">
                    
                    <div class="mb-3">
                        <label for="motivo_cambio" class="form-label">Motivo del cambio</label>
                        <textarea class="form-control" id="motivo_cambio" name="motivo" rows="3" 
                                  placeholder="Describe el motivo del cambio de estado"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="mensaje_cambio_estado"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Cambio</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos de vehículos
    cargarVehiculos();
    
    // Cargar estadísticas
    cargarEstadisticas();
    
    // Event listeners para filtros
    const filtroEstado = document.getElementById('filtro_estado');
    const filtroBuscar = document.getElementById('filtro_buscar');
    
    if (filtroEstado) {
        filtroEstado.addEventListener('change', cargarVehiculos);
    }
    if (filtroBuscar) {
        filtroBuscar.addEventListener('input', debounce(cargarVehiculos, 500));
    }
    
});

// Función para cargar vehículos
function cargarVehiculos() {
    const tbody = document.querySelector('#vehiculosTable tbody');
    if (!tbody) return;
    
    // Mostrar loading con Skeletons
    let skeletons = '';
    for(let i=0; i<5; i++) {
        skeletons += `
            <tr class="animate__animated animate__pulse animate__infinite">
                <td><div class="bg-light rounded w-50" style="height:20px"></div></td>
                <td><div class="bg-light rounded w-75" style="height:20px"></div></td>
                <td><div class="bg-light rounded w-100" style="height:20px"></div></td>
                <td><div class="bg-light rounded w-50" style="height:20px"></div></td>
                <td><div class="bg-light rounded w-100" style="height:20px"></div></td>
                <td><div class="bg-light rounded w-75" style="height:20px"></div></td>
                <td><div class="bg-light rounded w-100" style="height:20px"></div></td>
            </tr>
        `;
    }
    tbody.innerHTML = skeletons;
    
    // Obtener valores de filtros
    const filtros = {
        draw: 1,
        start: 0,
        length: 100,
        'search[value]': document.getElementById('filtro_buscar')?.value || '',
        estado: document.getElementById('filtro_estado')?.value || '',
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    };
    
    // Hacer petición AJAX
    fetch('<?= base_url('vehiculos/getData') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams(filtros)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error: ' + data.error + '</td></tr>';
            return;
        }
        
        if (!data.data || data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No se encontraron vehículos</td></tr>';
            return;
        }
        
        // Renderizar datos con animación
        let html = '';
        data.data.forEach((vehiculo, idx) => {
            html += `
                <tr class="animate__animated animate__fadeInUp" style="animation-delay: ${idx * 0.05}s">
                    <td class="fw-bold text-primary small">${vehiculo.codigo}</td>
                    <td><span class="badge bg-light text-dark border shadow-sm">${vehiculo.placa}</span></td>
                    <td>
                        <div class="fw-semibold">${vehiculo.vehiculo}</div>
                    </td>
                    <td><i class="fas fa-tachometer-alt text-muted me-1"></i> ${vehiculo.kilometraje}</td>
                    <td class="small text-muted">${vehiculo.conductor}</td>
                    <td>${vehiculo.estado}</td>
                    <td>${vehiculo.acciones}</td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        // Actualizar contador
        const info = document.querySelector('.dataTables_info');
        if (info) {
            info.textContent = `Mostrando ${data.data.length} de ${data.recordsTotal} vehículos`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error de conexión</td></tr>';
    });
}

// Función debounce para optimizar búsquedas
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Función para cargar estadísticas
function cargarEstadisticas() {
    fetch('<?= base_url('vehiculos/getEstadisticas') ?>', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const statTotal = document.getElementById('stat_total');
        const statActivos = document.getElementById('stat_activos');
        const statReparacion = document.getElementById('stat_reparacion');
        const statSinConductor = document.getElementById('stat_sin_conductor');
        
        if (statTotal) statTotal.textContent = data.total || 0;
        if (statActivos) statActivos.textContent = data.por_estado?.ACTIVO || 0;
        if (statReparacion) statReparacion.textContent = data.por_estado?.['EN REPARACION'] || 0;
        if (statSinConductor) statSinConductor.textContent = data.sin_conductor || 0;
    })
    .catch(error => {
        console.log('Error cargando estadísticas:', error);
    });
}

// Función para mostrar alertas
function mostrarAlerta(tipo, mensaje) {
    const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
    const icon = tipo === 'success' ? 'check-circle' : 'exclamation-triangle';
    
    const alerta = document.createElement('div');
    alerta.className = `alert ${alertClass} alert-dismissible fade show`;
    alerta.setAttribute('role', 'alert');
    alerta.innerHTML = `
        <i class="fas fa-${icon} me-2"></i>${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    if (container) {
        container.insertBefore(alerta, container.firstChild);
    }
    
    // Auto-hide después de 5 segundos
    setTimeout(function() {
        if (alerta.parentNode) {
            alerta.style.opacity = '0';
            setTimeout(() => {
                if (alerta.parentNode) {
                    alerta.parentNode.removeChild(alerta);
                }
            }, 300);
        }
    }, 5000);
}
</script>
<?= $this->endSection() ?>
