<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Reportes de Vehículos
                    </h1>
                    <p class="text-muted mb-0">Análisis y reportes detallados de la flota vehicular</p>
                </div>
                <div>
                    <a href="<?= base_url('vehiculos') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver a Vehículos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-car fa-2x mb-2"></i>
                    <h4 class="mb-1"><?= $estadisticas['total_vehiculos'] ?? 0 ?></h4>
                    <p class="mb-0 small">Total Vehículos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-2x mb-2"></i>
                    <h4 class="mb-1"><?= $estadisticas['asignados'] ?? 0 ?></h4>
                    <p class="mb-0 small">Vehículos Asignados</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <h4 class="mb-1"><?= $estadisticas['documentos_vencer'] ?? 0 ?></h4>
                    <p class="mb-0 small">Documentos por Vencer</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-tools fa-2x mb-2"></i>
                    <h4 class="mb-1"><?= $estadisticas['mantenimiento_vencido'] ?? 0 ?></h4>
                    <p class="mb-0 small">Mantenimiento Vencido</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de estadísticas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-secondary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-file-times fa-2x mb-2"></i>
                    <h4 class="mb-1"><?= $estadisticas['sin_documentacion'] ?? 0 ?></h4>
                    <p class="mb-0 small">Sin Documentación</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tipos de Reportes -->
    <div class="row">
        <!-- Reporte por Estado -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>
                        Vehículos por Estado
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Reporte detallado de todos los vehículos agrupados por su estado actual.</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" onclick="generarReporte('estado')">
                            <i class="fas fa-eye me-1"></i>
                            Ver Reporte
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="exportarReporte('estado')">
                            <i class="fas fa-download me-1"></i>
                            Exportar Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte de Mantenimiento -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Mantenimiento Vencido
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Vehículos que requieren mantenimiento urgente (más de 90 días sin servicio).</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger" onclick="generarReporte('mantenimiento')">
                            <i class="fas fa-eye me-1"></i>
                            Ver Reporte
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="exportarReporte('mantenimiento')">
                            <i class="fas fa-download me-1"></i>
                            Exportar Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte de Kilometraje -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Reporte de Kilometraje
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Análisis del kilometraje actual de todos los vehículos de la flota.</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-info" onclick="generarReporte('kilometraje')">
                            <i class="fas fa-eye me-1"></i>
                            Ver Reporte
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="exportarReporte('kilometraje')">
                            <i class="fas fa-download me-1"></i>
                            Exportar Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte de Documentos -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Documentos por Vencer
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Documentos de vehículos que vencen en los próximos 30 días.</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning" onclick="generarReporte('documentos')">
                            <i class="fas fa-eye me-1"></i>
                            Ver Reporte
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="exportarReporte('documentos')">
                            <i class="fas fa-download me-1"></i>
                            Exportar Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte de Asignaciones -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Asignaciones de Conductores
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Estado actual de las asignaciones de conductores a vehículos.</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="generarReporte('asignaciones')">
                            <i class="fas fa-eye me-1"></i>
                            Ver Reporte
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="exportarReporte('asignaciones')">
                            <i class="fas fa-download me-1"></i>
                            Exportar Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte de Vehículos sin Documentación -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-times me-2"></i>
                        Vehículos sin Documentación
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Vehículos que no tienen ningún documento registrado en el sistema.</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-secondary" onclick="mostrarFiltrosSinDocumentacion()">
                            <i class="fas fa-filter me-1"></i>
                            Ver con Filtros
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="exportarReporte('sin_documentacion')">
                            <i class="fas fa-download me-1"></i>
                            Exportar Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar reportes -->
<div class="modal fade" id="reporteModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reporteModalTitle">
                    <i class="fas fa-chart-bar me-2"></i>
                    Reporte de Vehículos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reporteContent">
                    <!-- Contenido del reporte se carga aquí -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnExportarModal">
                    <i class="fas fa-download me-1"></i>
                    Exportar Excel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para filtros de vehículos sin documentación -->
<div class="modal fade" id="filtrosSinDocumentacionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filtros - Vehículos sin Documentación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filtrosSinDocumentacionForm">
                    <div class="mb-3">
                        <label for="filtroEstado" class="form-label">Estado del Vehículo</label>
                        <select class="form-select" id="filtroEstado" name="estado">
                            <option value="TODOS">Todos los estados</option>
                            <option value="ACTIVO">Activo</option>
                            <option value="INACTIVO">Inactivo</option>
                            <option value="EN REPARACION">En Reparación</option>
                            <option value="FUERA DE SERVICIO">Fuera de Servicio</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filtroBusqueda" class="form-label">Búsqueda General</label>
                        <input type="text" class="form-control" id="filtroBusqueda" name="busqueda" 
                               placeholder="Buscar por placa, marca, modelo, año, motor, chasis...">
                        <small class="text-muted">Busca en todos los campos del vehículo</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltrosSinDocumentacion()">
                    <i class="fas fa-search me-1"></i>
                    Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let tipoReporteActual = '';

function generarReporte(tipo) {
    tipoReporteActual = tipo;
    
    // Configurar modal según el tipo
    const titulos = {
        'estado': 'Vehículos por Estado',
        'mantenimiento': 'Mantenimiento Vencido',
        'kilometraje': 'Reporte de Kilometraje',
        'documentos': 'Documentos por Vencer',
        'asignaciones': 'Asignaciones de Conductores',
        'sin_documentacion': 'Vehículos sin Documentación'
    };
    
    document.getElementById('reporteModalTitle').innerHTML = 
        `<i class="fas fa-chart-bar me-2"></i>${titulos[tipo]}`;
    
    // Mostrar loading
    document.getElementById('reporteContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Generando reporte...</p>
        </div>
    `;
    
    // Mostrar modal
    new bootstrap.Modal(document.getElementById('reporteModal')).show();
    
    // Cargar datos
    const endpoints = {
        'estado': '<?= base_url('vehiculos/reporteVehiculosPorEstado') ?>',
        'mantenimiento': '<?= base_url('vehiculos/reporteMantenimientoVencido') ?>',
        'kilometraje': '<?= base_url('vehiculos/reporteKilometraje') ?>',
        'documentos': '<?= base_url('vehiculos/reporteDocumentosPorVencer') ?>',
        'asignaciones': '<?= base_url('vehiculos/reporteAsignaciones') ?>',
        'sin_documentacion': '<?= base_url('vehiculos/reporteVehiculosSinDocumentacion') ?>'
    };
    
    fetch(endpoints[tipo])
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarReporte(tipo, data.data);
            } else {
                mostrarError('Error cargando el reporte');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error de conexión');
        });
}

function mostrarReporte(tipo, datos) {
    let html = '';
    
    if (datos.length === 0) {
        html = `
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-2x mb-2"></i>
                <h5>No hay datos disponibles</h5>
                <p class="mb-0">No se encontraron registros para este reporte.</p>
            </div>
        `;
    } else {
        html = generarTablaReporte(tipo, datos);
    }
    
    document.getElementById('reporteContent').innerHTML = html;
}

function generarTablaReporte(tipo, datos) {
    let headers = '';
    let filas = '';
    
    switch (tipo) {
        case 'estado':
            headers = '<th>Placa</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Estado</th><th>Kilometraje</th><th>Tipo Unidad</th>';
            datos.forEach(item => {
                const estadoBadge = getEstadoBadge(item.estado);
                filas += `
                    <tr>
                        <td><strong>${item.placa}</strong></td>
                        <td>${item.marca}</td>
                        <td>${item.modelo}</td>
                        <td>${item.anio}</td>
                        <td>${estadoBadge}</td>
                        <td>${formatNumber(item.kilometraje)} km</td>
                        <td>${item.tipo_unidad_desc || 'N/A'}</td>
                    </tr>
                `;
            });
            break;
            
        case 'mantenimiento':
            headers = '<th>Placa</th><th>Marca</th><th>Modelo</th><th>Último Mantenimiento</th><th>Días sin Mantenimiento</th>';
            datos.forEach(item => {
                const diasBadge = item.dias_sin_mantenimiento > 180 ? 'bg-danger' : 'bg-warning';
                filas += `
                    <tr>
                        <td><strong>${item.placa}</strong></td>
                        <td>${item.marca}</td>
                        <td>${item.modelo}</td>
                        <td>${item.ultimo_mantenimiento || 'Nunca'}</td>
                        <td><span class="badge ${diasBadge}">${item.dias_sin_mantenimiento} días</span></td>
                    </tr>
                `;
            });
            break;
            
        case 'kilometraje':
            headers = '<th>Placa</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Kilometraje</th><th>Tipo Unidad</th>';
            datos.forEach(item => {
                filas += `
                    <tr>
                        <td><strong>${item.placa}</strong></td>
                        <td>${item.marca}</td>
                        <td>${item.modelo}</td>
                        <td>${item.anio}</td>
                        <td><strong>${formatNumber(item.kilometraje)} km</strong></td>
                        <td>${item.tipo_unidad_desc || 'N/A'}</td>
                    </tr>
                `;
            });
            break;
            
        case 'documentos':
            headers = '<th>Placa</th><th>Vehículo</th><th>Tipo Documento</th><th>Fecha Vencimiento</th><th>Días Restantes</th>';
            datos.forEach(item => {
                const diasBadge = item.dias_restantes <= 7 ? 'bg-danger' : 'bg-warning';
                filas += `
                    <tr>
                        <td><strong>${item.placa}</strong></td>
                        <td>${item.marca} ${item.modelo} (${item.anio})</td>
                        <td>${item.tipo_documento}</td>
                        <td>${formatDate(item.fecha_vencimiento)}</td>
                        <td><span class="badge ${diasBadge}">${item.dias_restantes} días</span></td>
                    </tr>
                `;
            });
            break;
            
        case 'asignaciones':
            headers = '<th>Placa</th><th>Vehículo</th><th>Conductor</th><th>DNI</th><th>Estado</th><th>Fecha Asignación</th>';
            datos.forEach(item => {
                const estadoBadge = item.estado_desc === 'Asignado' ? 'bg-success' : 'bg-secondary';
                filas += `
                    <tr>
                        <td><strong>${item.placa}</strong></td>
                        <td>${item.marca} ${item.modelo} (${item.anio})</td>
                        <td>${item.conductor || 'Sin asignar'}</td>
                        <td>${item.dni || 'N/A'}</td>
                        <td><span class="badge ${estadoBadge}">${item.estado_desc}</span></td>
                        <td>${formatDate(item.fecha_asignacion) || 'N/A'}</td>
                    </tr>
                `;
            });
            break;
            
        case 'sin_documentacion':
            headers = '<th>Placa</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Estado</th><th>Kilometraje</th><th>Tipo Unidad</th><th>Tipo Operación</th>';
            datos.forEach(item => {
                const estadoBadge = getEstadoBadge(item.estado);
                filas += `
                    <tr>
                        <td><strong>${item.placa}</strong></td>
                        <td>${item.marca}</td>
                        <td>${item.modelo}</td>
                        <td>${item.anio}</td>
                        <td>${estadoBadge}</td>
                        <td>${formatNumber(item.kilometraje)} km</td>
                        <td>${item.tipo_unidad_desc || 'N/A'}</td>
                        <td>${item.tipo_operacion_desc || 'N/A'}</td>
                    </tr>
                `;
            });
            break;
    }
    
    return `
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>${headers}</tr>
                </thead>
                <tbody>
                    ${filas}
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <small class="text-muted">Total de registros: ${datos.length}</small>
        </div>
    `;
}

function exportarReporte(tipo) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('vehiculos/exportarReporte') ?>';
    
    const tipoInput = document.createElement('input');
    tipoInput.type = 'hidden';
    tipoInput.name = 'tipo_reporte';
    tipoInput.value = tipo;
    
    const formatoInput = document.createElement('input');
    formatoInput.type = 'hidden';
    formatoInput.name = 'formato';
    formatoInput.value = 'excel';
    
    form.appendChild(tipoInput);
    form.appendChild(formatoInput);
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function mostrarError(mensaje) {
    document.getElementById('reporteContent').innerHTML = `
        <div class="alert alert-danger text-center">
            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
            <h5>Error</h5>
            <p class="mb-0">${mensaje}</p>
        </div>
    `;
}

function getEstadoBadge(estado) {
    const badges = {
        'ACTIVO': '<span class="badge bg-success">Activo</span>',
        'INACTIVO': '<span class="badge bg-secondary">Inactivo</span>',
        'EN REPARACION': '<span class="badge bg-warning">En Reparación</span>',
        'FUERA DE SERVICIO': '<span class="badge bg-danger">Fuera de Servicio</span>'
    };
    return badges[estado] || `<span class="badge bg-secondary">${estado}</span>`;
}

function formatNumber(number) {
    return new Intl.NumberFormat('es-ES').format(number);
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES');
}

// Funciones para vehículos sin documentación
function mostrarFiltrosSinDocumentacion() {
    new bootstrap.Modal(document.getElementById('filtrosSinDocumentacionModal')).show();
}

function aplicarFiltrosSinDocumentacion() {
    const estado = document.getElementById('filtroEstado').value;
    const busqueda = document.getElementById('filtroBusqueda').value;
    
    // Cerrar modal de filtros
    bootstrap.Modal.getInstance(document.getElementById('filtrosSinDocumentacionModal')).hide();
    
    // Generar reporte con filtros
    generarReporteConFiltros('sin_documentacion', { estado, busqueda });
}

function generarReporteConFiltros(tipo, filtros) {
    tipoReporteActual = tipo;
    
    // Configurar modal
    document.getElementById('reporteModalTitle').innerHTML = 
        `<i class="fas fa-chart-bar me-2"></i>Vehículos sin Documentación`;
    
    // Mostrar loading
    document.getElementById('reporteContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Aplicando filtros...</p>
        </div>
    `;
    
    // Mostrar modal
    new bootstrap.Modal(document.getElementById('reporteModal')).show();
    
    // Construir URL con parámetros
    let url = '<?= base_url('vehiculos/reporteVehiculosSinDocumentacion') ?>';
    const params = new URLSearchParams();
    
    if (filtros.estado && filtros.estado !== 'TODOS') {
        params.append('estado', filtros.estado);
    }
    if (filtros.busqueda && filtros.busqueda.trim() !== '') {
        params.append('busqueda', filtros.busqueda.trim());
    }
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    // Cargar datos
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarReporteConFiltros(data.data, data.filtros, data.total);
            } else {
                mostrarError('Error cargando el reporte');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error de conexión');
        });
}

function mostrarReporteConFiltros(datos, filtros, total) {
    let html = '';
    
    // Mostrar información de filtros aplicados
    let filtrosInfo = '';
    if (filtros.estado && filtros.estado !== 'TODOS') {
        filtrosInfo += `<span class="badge bg-primary me-2">Estado: ${filtros.estado}</span>`;
    }
    if (filtros.busqueda) {
        filtrosInfo += `<span class="badge bg-info me-2">Búsqueda: "${filtros.busqueda}"</span>`;
    }
    
    if (filtrosInfo) {
        html += `
            <div class="alert alert-info">
                <strong>Filtros aplicados:</strong><br>
                ${filtrosInfo}
            </div>
        `;
    }
    
    if (datos.length === 0) {
        html += `
            <div class="alert alert-warning text-center">
                <i class="fas fa-search fa-2x mb-2"></i>
                <h5>No se encontraron resultados</h5>
                <p class="mb-0">No hay vehículos sin documentación que coincidan con los filtros aplicados.</p>
            </div>
        `;
    } else {
        html += generarTablaReporte('sin_documentacion', datos);
    }
    
    document.getElementById('reporteContent').innerHTML = html;
}

// Event listener para el botón de exportar del modal
document.getElementById('btnExportarModal').addEventListener('click', function() {
    if (tipoReporteActual) {
        exportarReporte(tipoReporteActual);
    }
});
</script>

<?= $this->endSection() ?>
