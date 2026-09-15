<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
    .reporte-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        border-radius: 24px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .reporte-hero::after {
        content: ''; position: absolute; top: -20%; right: -5%; width: 260px; height: 260px;
        background: rgba(79, 70, 229, 0.15); border-radius: 50%;
    }
    .reporte-hero h1, .reporte-hero p { position: relative; z-index: 1; }
    .bento-stat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.25rem;
        display: flex; align-items: center; gap: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        transition: transform 0.15s;
    }
    .bento-stat:hover { transform: translateY(-3px); }
    .bento-icon {
        width: 48px; height: 48px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        background: #f8fafc; color: #334155;
        border: 1px solid #e2e8f0;
    }
    .bento-value { font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1; }
    .bento-label { font-size: 0.8rem; color: #64748b; }
    .reporte-card {
        border: 2px solid #e2e8f0; border-radius: 18px; padding: 1rem;
        cursor: pointer; text-align: center; background: #fff;
        transition: all 0.15s; height: 100%;
    }
    .reporte-card:hover, .reporte-card.selected {
        border-color: #4f46e5; background: #eef2ff;
        transform: translateY(-2px); box-shadow: 0 10px 20px rgba(79,70,229,0.08);
    }
    .reporte-card i { font-size: 1.5rem; color: #4f46e5; margin-bottom: 0.5rem; }
    .reporte-card h6 { font-size: 0.85rem; font-weight: 600; margin-bottom: 0.2rem; }
    .reporte-card small { font-size: 0.75rem; color: #64748b; }
    .filter-bar {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
        padding: 1rem 1.25rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .btn-generar { background: #4f46e5; color: #fff; border-radius: 12px; }
    .btn-generar:disabled { background: #cbd5e1; }
    .resultado-card { border-radius: 18px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .table-reporte thead th { background: #f8fafc; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.3px; }
    .table-reporte tbody td { font-size: 0.9rem; vertical-align: middle; }
    .resumen-bento {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 18px;
        padding: 1.25rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .resumen-item {
        display: flex; flex-direction: column; align-items: flex-start;
        padding: 1rem; border-radius: 14px; background: #f8fafc;
        border: 1px solid #e2e8f0; height: 100%;
    }
    .resumen-item .number { font-size: 1.6rem; font-weight: 700; color: #0f172a; line-height: 1; }
    .resumen-item .label { font-size: 0.8rem; color: #64748b; margin-top: 0.35rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="reporte-hero">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-chart-line me-2"></i>Reportes de Vehículos</h1>
                <p class="mb-0 opacity-75">Análisis y reportes unificados de la flota vehicular.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="opacity-75"><?= date('d M Y, l') ?></span>
            </div>
        </div>
    </div>

    <!-- Bento stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="bento-stat">
                <div class="bento-icon"><i class="fas fa-car"></i></div>
                <div><div class="bento-value"><?= $estadisticas['total_vehiculos'] ?? 0 ?></div><div class="bento-label">Total Vehículos</div></div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="bento-stat">
                <div class="bento-icon"><i class="fas fa-user-check"></i></div>
                <div><div class="bento-value"><?= $estadisticas['asignados'] ?? 0 ?></div><div class="bento-label">Asignados</div></div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="bento-stat">
                <div class="bento-icon"><i class="fas fa-file-alt"></i></div>
                <div><div class="bento-value"><?= $estadisticas['documentos_vencer'] ?? 0 ?></div><div class="bento-label">Documentos por vencer</div></div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="bento-stat">
                <div class="bento-icon"><i class="fas fa-exclamation-circle"></i></div>
                <div><div class="bento-value"><?= $estadisticas['sin_documentacion'] ?? 0 ?></div><div class="bento-label">Sin documentación</div></div>
            </div>
        </div>
    </div>

    <!-- Selector de reportes -->
    <h6 class="fw-bold text-muted mb-3 text-uppercase small">Selecciona un reporte</h6>
    <div class="row g-3 mb-4" id="reportesGrid">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="reporte-card" data-value="estado" onclick="seleccionarReporte('estado')">
                <i class="fas fa-tasks"></i>
                <h6>Por Estado</h6>
                <small>Flota agrupada</small>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="reporte-card" data-value="mantenimiento" onclick="seleccionarReporte('mantenimiento')">
                <i class="fas fa-wrench"></i>
                <h6>Mantenimiento vencido</h6>
                <small>Más de 90 días</small>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="reporte-card" data-value="kilometraje" onclick="seleccionarReporte('kilometraje')">
                <i class="fas fa-tachometer-alt"></i>
                <h6>Kilometraje</h6>
                <small>Análisis de KM</small>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="reporte-card" data-value="documentos" onclick="seleccionarReporte('documentos')">
                <i class="fas fa-calendar-alt"></i>
                <h6>Documentos por vencer</h6>
                <small>Próximos 30 días</small>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="reporte-card" data-value="asignaciones" onclick="seleccionarReporte('asignaciones')">
                <i class="fas fa-users"></i>
                <h6>Asignaciones</h6>
                <small>Conductores</small>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="reporte-card" data-value="sin_documentacion" onclick="seleccionarReporte('sin_documentacion')">
                <i class="fas fa-folder-open"></i>
                <h6>Sin documentación</h6>
                <small>Sin archivos</small>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filter-bar mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Estado</label>
                <select class="form-select" id="filtroEstado">
                    <option value="TODOS">Todos</option>
                    <option value="ACTIVO">Activo</option>
                    <option value="INACTIVO">Inactivo</option>
                    <option value="EN REPARACION">En Reparación</option>
                    <option value="FUERA DE SERVICIO">Fuera de Servicio</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-semibold text-muted">Búsqueda general</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" id="filtroBusqueda" placeholder="Placa, marca, modelo, año...">
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-generar flex-fill" onclick="generarReporte()" id="btnGenerar" disabled>
                        <i class="fas fa-search me-2"></i>Generar
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="limpiarResultados()" title="Limpiar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados: resumen arriba de tabla -->
    <div class="row" id="areaResultados" style="display: none;">
        <div class="col-12">
            <div class="card resultado-card">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="fw-bold mb-0" id="tituloReporte">Resultados del Reporte</h5>
                        <small class="text-muted" id="descripcionReporte"></small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill" onclick="exportarReporte()">
                            <i class="fas fa-file-excel me-1"></i>Exportar Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filtrosAplicados" class="mb-3" style="display: none;">
                        <div class="alert alert-light border rounded-4 mb-0">
                            <strong><i class="fas fa-filter me-1"></i>Filtros aplicados:</strong>
                            <span id="filtrosTexto"></span>
                        </div>
                    </div>

                    <!-- Resumen del reporte arriba de la tabla -->
                    <div class="resumen-bento mb-4" id="areaEstadisticas" style="display: none;">
                        <h6 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2"></i>Resumen del reporte</h6>
                        <div class="row g-3" id="estadisticasRapidas"></div>
                    </div>

                    <div id="loadingReporte" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="text-muted">Generando reporte...</p>
                    </div>

                    <div id="tablaResultados" class="table-responsive"></div>

                    <div id="sinResultados" class="text-center py-5" style="display: none;">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron resultados</h5>
                        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let tipoReporteActual = '';
let datosActuales = [];

const configReportes = {
    'estado': {
        titulo: 'Vehículos por Estado',
        descripcion: 'Lista completa de vehículos agrupados por su estado actual',
        endpoint: '<?= base_url('vehiculos/reporteVehiculosPorEstado') ?>',
        headers: ['Placa', 'Marca', 'Modelo', 'Año', 'Estado', 'Kilometraje', 'Tipo Unidad'],
        campos: ['placa', 'marca', 'modelo', 'anio', 'estado', 'kilometraje', 'tipo_unidad_desc']
    },
    'mantenimiento': {
        titulo: 'Mantenimiento Vencido',
        descripcion: 'Vehículos que requieren mantenimiento urgente (más de 90 días sin servicio)',
        endpoint: '<?= base_url('vehiculos/reporteMantenimientoVencido') ?>',
        headers: ['Placa', 'Marca', 'Modelo', 'Último Mantenimiento', 'Días sin Mantenimiento'],
        campos: ['placa', 'marca', 'modelo', 'ultimo_mantenimiento', 'dias_sin_mantenimiento']
    },
    'kilometraje': {
        titulo: 'Reporte de Kilometraje',
        descripcion: 'Análisis del kilometraje actual de todos los vehículos de la flota',
        endpoint: '<?= base_url('vehiculos/reporteKilometraje') ?>',
        headers: ['Placa', 'Marca', 'Modelo', 'Año', 'Kilometraje', 'Tipo Unidad'],
        campos: ['placa', 'marca', 'modelo', 'anio', 'kilometraje', 'tipo_unidad_desc']
    },
    'documentos': {
        titulo: 'Documentos por Vencer',
        descripcion: 'Documentos de vehículos que vencen en los próximos 30 días',
        endpoint: '<?= base_url('vehiculos/reporteDocumentosPorVencer') ?>',
        headers: ['Placa', 'Vehículo', 'Tipo Documento', 'Fecha Vencimiento', 'Días Restantes'],
        campos: ['placa', 'vehiculo_info', 'tipo_documento', 'fecha_vencimiento', 'dias_restantes']
    },
    'asignaciones': {
        titulo: 'Asignaciones de Conductores',
        descripcion: 'Estado actual de las asignaciones de conductores a vehículos',
        endpoint: '<?= base_url('vehiculos/reporteAsignaciones') ?>',
        headers: ['Placa', 'Vehículo', 'Conductor', 'DNI', 'Estado', 'Fecha Asignación'],
        campos: ['placa', 'vehiculo_info', 'conductor', 'dni', 'estado_desc', 'fecha_asignacion']
    },
    'sin_documentacion': {
        titulo: 'Vehículos sin Documentación',
        descripcion: 'Vehículos que no tienen ningún documento registrado en el sistema',
        endpoint: '<?= base_url('vehiculos/reporteVehiculosSinDocumentacion') ?>',
        headers: ['Placa', 'Marca', 'Modelo', 'Año', 'Estado', 'Kilometraje', 'Tipo Unidad', 'Tipo Operación'],
        campos: ['placa', 'marca', 'modelo', 'anio', 'estado', 'kilometraje', 'tipo_unidad_desc', 'tipo_operacion_desc']
    }
};

function seleccionarReporte(tipo) {
    tipoReporteActual = tipo;
    document.querySelectorAll('.reporte-card').forEach(card => card.classList.remove('selected'));
    document.querySelector('.reporte-card[data-value="' + tipo + '"]').classList.add('selected');
    document.getElementById('btnGenerar').disabled = false;

    const config = configReportes[tipo];
    document.getElementById('tituloReporte').textContent = config.titulo;
    document.getElementById('descripcionReporte').textContent = config.descripcion;
}

function generarReporte() {
    if (!tipoReporteActual) return;

    const config = configReportes[tipoReporteActual];
    const estado = document.getElementById('filtroEstado').value;
    const busqueda = document.getElementById('filtroBusqueda').value.trim();

    document.getElementById('areaResultados').style.display = 'block';
    document.getElementById('loadingReporte').style.display = 'block';
    document.getElementById('tablaResultados').innerHTML = '';
    document.getElementById('sinResultados').style.display = 'none';
    document.getElementById('areaEstadisticas').style.display = 'none';

    let url = config.endpoint;
    const params = new URLSearchParams();
    if (estado && estado !== 'TODOS') params.append('estado', estado);
    if (busqueda) params.append('busqueda', busqueda);
    if (params.toString()) url += '?' + params.toString();

    mostrarFiltrosAplicados(estado, busqueda);

    fetch(url, {
        method: 'GET',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => { if (!response.ok) throw new Error('HTTP error! status: ' + response.status); return response.json(); })
    .then(data => {
        document.getElementById('loadingReporte').style.display = 'none';
        if (data.success && data.data && data.data.length > 0) {
            datosActuales = data.data;
            mostrarEstadisticasRapidas(data.data);
            mostrarTablaResultados(data.data, config);
        } else {
            document.getElementById('sinResultados').style.display = 'block';
            document.getElementById('areaEstadisticas').style.display = 'none';
            datosActuales = [];
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loadingReporte').style.display = 'none';
        mostrarError('Error cargando el reporte');
    });
}

function mostrarFiltrosAplicados(estado, busqueda) {
    let filtros = [];
    if (estado && estado !== 'TODOS') filtros.push('<span class="badge bg-primary me-1">Estado: ' + estado + '</span>');
    if (busqueda) filtros.push('<span class="badge bg-info me-1">Búsqueda: "' + busqueda + '"</span>');

    const filtrosDiv = document.getElementById('filtrosAplicados');
    if (filtros.length > 0) {
        document.getElementById('filtrosTexto').innerHTML = filtros.join(' ');
        filtrosDiv.style.display = 'block';
    } else {
        filtrosDiv.style.display = 'none';
    }
}

function mostrarTablaResultados(datos, config) {
    let html = '<table class="table table-hover align-middle table-reporte"><thead class="table-light"><tr>';
    config.headers.forEach(header => html += '<th>' + header + '</th>');
    html += '</tr></thead><tbody>';

    datos.forEach(item => {
        html += '<tr>';
        config.campos.forEach(campo => {
            let valor = item[campo] || 'N/A';
            if (campo === 'estado') valor = getEstadoBadge(valor);
            else if (campo === 'kilometraje') valor = formatNumber(valor) + ' km';
            else if (campo.includes('fecha')) valor = formatDate(valor);
            else if (campo === 'dias_sin_mantenimiento') valor = '<span class="badge ' + (valor > 180 ? 'bg-danger' : 'bg-warning') + '">' + valor + ' días</span>';
            else if (campo === 'dias_restantes') valor = '<span class="badge ' + (valor <= 7 ? 'bg-danger' : 'bg-warning') + '">' + valor + ' días</span>';
            else if (campo === 'estado_desc') valor = '<span class="badge ' + (valor === 'Asignado' ? 'bg-success' : 'bg-secondary') + '">' + valor + '</span>';
            else if (campo === 'vehiculo_info') valor = item.marca + ' ' + item.modelo + ' (' + item.anio + ')';
            else if (campo === 'placa') valor = '<strong>' + valor + '</strong>';
            html += '<td>' + valor + '</td>';
        });
        html += '</tr>';
    });

    html += '</tbody></table><div class="mt-3"><small class="text-muted"><i class="fas fa-info-circle me-1"></i>Total de registros encontrados: <strong>' + datos.length + '</strong></small></div>';
    document.getElementById('tablaResultados').innerHTML = html;
}

function mostrarEstadisticasRapidas(datos) {
    if (!datos.length) {
        document.getElementById('areaEstadisticas').style.display = 'none';
        return;
    }

    let estadisticasHtml = '';
    estadisticasHtml += '<div class="col-md-6 col-lg-3"><div class="resumen-item"><span class="number">' + datos.length + '</span><span class="label">Total Registros</span></div></div>';

    if (datos[0].hasOwnProperty('estado')) {
        const conteoEstados = {};
        datos.forEach(item => { const estado = item.estado || 'Sin Estado'; conteoEstados[estado] = (conteoEstados[estado] || 0) + 1; });
        Object.entries(conteoEstados).forEach(([estado, cantidad]) => {
            const porcentaje = ((cantidad / datos.length) * 100).toFixed(1);
            estadisticasHtml += '<div class="col-md-6 col-lg-3"><div class="resumen-item"><span class="number">' + cantidad + '</span><span class="label">' + estado + ' (' + porcentaje + '%)</span></div></div>';
        });
    }

    if (tipoReporteActual === 'kilometraje') {
        const kilometrajes = datos.map(item => parseInt(item.kilometraje) || 0);
        const maxKm = Math.max(...kilometrajes);
        const avgKm = Math.round(kilometrajes.reduce((a, b) => a + b, 0) / kilometrajes.length);
        estadisticasHtml += '<div class="col-md-6 col-lg-3"><div class="resumen-item"><span class="number">' + formatNumber(maxKm) + '</span><span class="label">Máximo KM</span></div></div>';
        estadisticasHtml += '<div class="col-md-6 col-lg-3"><div class="resumen-item"><span class="number">' + formatNumber(avgKm) + '</span><span class="label">Promedio KM</span></div></div>';
    }

    if (tipoReporteActual === 'documentos') {
        const criticos = datos.filter(item => item.dias_restantes <= 7).length;
        const advertencia = datos.filter(item => item.dias_restantes > 7 && item.dias_restantes <= 15).length;
        estadisticasHtml += '<div class="col-md-6 col-lg-3"><div class="resumen-item"><span class="number">' + criticos + '</span><span class="label">Críticos (≤7 días)</span></div></div>';
        estadisticasHtml += '<div class="col-md-6 col-lg-3"><div class="resumen-item"><span class="number">' + advertencia + '</span><span class="label">Advertencia (8-15 días)</span></div></div>';
    }

    document.getElementById('estadisticasRapidas').innerHTML = estadisticasHtml;
    document.getElementById('areaEstadisticas').style.display = 'block';
}

function exportarReporte() {
    if (!tipoReporteActual || !datosActuales.length) { alert('No hay datos para exportar'); return; }
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('vehiculos/exportarReporte') ?>';
    const tipoInput = document.createElement('input');
    tipoInput.type = 'hidden'; tipoInput.name = 'tipo_reporte'; tipoInput.value = tipoReporteActual;
    const formatoInput = document.createElement('input');
    formatoInput.type = 'hidden'; formatoInput.name = 'formato'; formatoInput.value = 'excel';
    form.appendChild(tipoInput); form.appendChild(formatoInput);
    document.body.appendChild(form); form.submit(); document.body.removeChild(form);
}

function limpiarResultados() {
    document.getElementById('areaResultados').style.display = 'none';
    document.getElementById('filtroEstado').value = 'TODOS';
    document.getElementById('filtroBusqueda').value = '';
    document.querySelectorAll('.reporte-card').forEach(card => card.classList.remove('selected'));
    tipoReporteActual = '';
    document.getElementById('btnGenerar').disabled = true;
    datosActuales = [];
}

function mostrarError(mensaje) {
    document.getElementById('tablaResultados').innerHTML = '<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><h5>Error</h5><p class="mb-0">' + mensaje + '</p></div>';
}

function getEstadoBadge(estado) {
    const badges = {
        'ACTIVO': '<span class="badge bg-success rounded-pill">Activo</span>',
        'INACTIVO': '<span class="badge bg-secondary rounded-pill">Inactivo</span>',
        'EN REPARACION': '<span class="badge bg-warning rounded-pill">En Reparación</span>',
        'FUERA DE SERVICIO': '<span class="badge bg-danger rounded-pill">Fuera de Servicio</span>'
    };
    return badges[estado] || '<span class="badge bg-secondary rounded-pill">' + estado + '</span>';
}

function formatNumber(number) { return new Intl.NumberFormat('es-ES').format(number); }
function formatDate(dateString) { if (!dateString) return 'N/A'; return new Date(dateString).toLocaleDateString('es-ES'); }

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('filtroBusqueda').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && tipoReporteActual) generarReporte();
    });
});
</script>
<?= $this->endSection() ?>
