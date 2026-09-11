<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Reporte de Combustible</li>
                    </ol>
                </div>
                <h4 class="page-title">Reporte de Combustible</h4>
            </div>
        </div>
    </div>

    <!-- Filtro de rango de fechas -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <form method="GET" action="<?= base_url('reportes/combustible') ?>" class="row g-2 align-items-end" id="formFiltro">
                        <div class="col-auto">
                            <label class="form-label mb-1 text-muted small">Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" value="<?= esc($fecha_inicio ?? '') ?>">
                        </div>
                        <div class="col-auto">
                            <label class="form-label mb-1 text-muted small">Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" value="<?= esc($fecha_fin ?? '') ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-filter-variant me-1"></i>Filtrar
                            </button>
                            <a href="<?= base_url('reportes/combustible') ?>" class="btn btn-outline-secondary">
                                <i class="mdi mdi-refresh me-1"></i>Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Globales -->
    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar-md rounded-4 mx-auto mb-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                        <i class="mdi mdi-gas-station fs-3 text-white"></i>
                    </div>
                    <h3 class="mb-1" id="kpiGalones"><?= number_format($kpis['total_galones_consumidos'] ?? 0, 2) ?></h3>
                    <p class="text-muted mb-0">Total Galones Consumidos</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar-md rounded-4 mx-auto mb-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0dcaf0, #0aa8c4);">
                        <i class="mdi mdi-fuel fs-3 text-white"></i>
                    </div>
                    <h3 class="mb-1" id="kpiDespachos"><?= number_format($kpis['total_despachos'] ?? 0, 0) ?></h3>
                    <p class="text-muted mb-0">Total Despachos</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar-md rounded-4 mx-auto mb-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #198754, #157347);">
                        <i class="mdi mdi-pump fs-3 text-white"></i>
                    </div>
                    <h3 class="mb-1" id="kpiPromedio"><?= number_format($kpis['promedio_galones_por_despacho'] ?? 0, 2) ?></h3>
                    <p class="text-muted mb-0">Promedio Galones por Despacho</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar-md rounded-4 mx-auto mb-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #ffc107, #d39e00);">
                        <i class="mdi mdi-truck-fast fs-3 text-white"></i>
                    </div>
                    <h3 class="mb-1" id="kpiVehiculos"><?= $kpis['total_vehiculos_unicos'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Vehículos Únicos Atendidos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs para las tablas -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-tabs-custom border-0 px-3 pt-3" id="tabReportes" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-medium" data-bs-toggle="tab" data-bs-target="#tab-top10" type="button" role="tab">
                                <i class="mdi mdi-trophy-award me-1"></i>Top 10 Consumo
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" data-bs-toggle="tab" data-bs-target="#tab-rendimiento" type="button" role="tab">
                                <i class="mdi mdi-speedometer me-1"></i>Rendimiento
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" data-bs-toggle="tab" data-bs-target="#tab-comparativo" type="button" role="tab">
                                <i class="mdi mdi-chart-line-variant me-1"></i>Comparativo Teórica vs Promedio
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" data-bs-toggle="tab" data-bs-target="#tab-turnos" type="button" role="tab">
                                <i class="mdi mdi-gauge me-1"></i>Consumo por Turno
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <!-- Tab: Top 10 -->
                        <div class="tab-pane fade show active" id="tab-top10" role="tabpanel">
                            <div class="d-flex justify-content-end mb-2">
                                <a href="#" class="btn btn-sm btn-outline-success btn-export" data-seccion="top10">
                                    <i class="mdi mdi-file-excel me-1"></i>Exportar Excel
                                </a>
                            </div>
                            <div id="content-top10">
                                <div class="skeleton-table">
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Rendimiento -->
                        <div class="tab-pane fade" id="tab-rendimiento" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success rounded-pill px-3 py-2">KM/Gal</span>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-success btn-export" data-seccion="rendimiento">
                                    <i class="mdi mdi-file-excel me-1"></i>Exportar Excel
                                </a>
                            </div>
                            <div id="content-rendimiento">
                                <div class="skeleton-table">
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Comparativo -->
                        <div class="tab-pane fade" id="tab-comparativo" role="tabpanel">
                            <div class="d-flex justify-content-end mb-2">
                                <a href="#" class="btn btn-sm btn-outline-success btn-export" data-seccion="comparativo">
                                    <i class="mdi mdi-file-excel me-1"></i>Exportar Excel
                                </a>
                            </div>
                            <div id="content-comparativo">
                                <div class="skeleton-table">
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Turnos -->
                        <div class="tab-pane fade" id="tab-turnos" role="tabpanel">
                            <div class="d-flex justify-content-end mb-2">
                                <a href="#" class="btn btn-sm btn-outline-success btn-export" data-seccion="turnos">
                                    <i class="mdi mdi-file-excel me-1"></i>Exportar Excel
                                </a>
                            </div>
                            <div id="content-turnos">
                                <div class="skeleton-table">
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                    <div class="skeleton-row skeleton-line"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.skeleton-table { padding: 1rem 0; }
.skeleton-row {
    height: 2.5rem;
    margin-bottom: 0.75rem;
    border-radius: 0.375rem;
}
.skeleton-line {
    background: linear-gradient(90deg, #e9ecef 25%, #f1f3f5 50%, #e9ecef 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
.nav-tabs-custom .nav-link {
    border: none;
    color: #6c757d;
}
.nav-tabs-custom .nav-link.active {
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
    background: transparent;
}
</style>

<!-- Modal Detalle Rendimiento -->
<div class="modal fade" id="modalRendimientoDetalle" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-chart-line me-2"></i><span id="modalRendTitulo">Detalle de Rendimiento</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="chartRendimientoDetalle" style="min-height: 280px;"></div>
                <hr class="my-3">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover table-sm table-centered mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Fecha</th>
                                <th class="text-end">KM Inicial</th>
                                <th class="text-end">KM Final</th>
                                <th class="text-end">KM Recorrido</th>
                                <th class="text-end">Galones</th>
                                <th class="text-end" id="thRendUnidad">Rend. KM/Gal</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyRendimientoDetalle"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js"></script>
<script>
const BASE_URL = '<?= base_url() ?>';
const FECHA_INICIO = '<?= esc($fecha_inicio ?? '') ?>';
const FECHA_FIN = '<?= esc($fecha_fin ?? '') ?>';

function buildQuery() {
    const params = new URLSearchParams();
    if (FECHA_INICIO) params.set('fecha_inicio', FECHA_INICIO);
    if (FECHA_FIN) params.set('fecha_fin', FECHA_FIN);
    return params.toString() ? '?' + params.toString() : '';
}

const skeletonHtml = `
    <div class="skeleton-table">
        <div class="skeleton-row skeleton-line"></div>
        <div class="skeleton-row skeleton-line"></div>
        <div class="skeleton-row skeleton-line"></div>
        <div class="skeleton-row skeleton-line"></div>
        <div class="skeleton-row skeleton-line"></div>
    </div>`;

const emptyHtml = `
    <div class="text-center py-4 text-muted">
        <i class="mdi mdi-database-off-outline fs-1 d-block mb-2 opacity-25"></i>
        No hay datos disponibles
    </div>`;

const loaded = {};
const cachedData = {};

function loadTab(seccion) {
    if (loaded[seccion]) return;
    loaded[seccion] = true;
    const container = document.getElementById('content-' + seccion);
    container.innerHTML = skeletonHtml;

    fetch(`${BASE_URL}reportes/ajax-${seccion}${buildQuery()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        const data = res.data || [];
        cachedData[seccion] = data;
        if (!data.length) {
            container.innerHTML = emptyHtml;
            return;
        }
        container.innerHTML = renderTable(seccion, data);
    })
    .catch(() => { container.innerHTML = emptyHtml; });
}

function renderTable(seccion, data) {
    let html = '<div class="table-responsive"><table class="table table-hover table-centered mb-0">';
    if (seccion === 'top10') {
        html += `<thead class="table-light"><tr><th>#</th><th>Código</th><th>Placa</th><th>Marca/Modelo</th><th class="text-end">Galones</th><th class="text-end">Despachos</th></tr></thead><tbody>`;
        data.forEach((v, i) => {
            html += `<tr><td><span class="badge ${i < 3 ? 'bg-warning' : 'bg-light text-dark'} rounded-pill">${i+1}</span></td>
                <td>${esc(v.codigo_unidad)}</td><td>${esc(v.placa)}</td>
                <td>${esc((v.marca||'')+' '+(v.modelo||''))}</td>
                <td class="text-end fw-medium">${fmt(v.total_galones_consumidos)}</td>
                <td class="text-end">${v.total_despachos}</td></tr>`;
        });
    } else if (seccion === 'rendimiento') {
        const unidadLabel = 'KM/Gal';
        const unidadField = 'rendimiento_km_gln';
        const volLabel = 'Galones';
        const volField = 'total_galones_consumidos';
        const thresholdOk = 10;
        const thresholdWarn = 7;
        html += `<thead class="table-light"><tr><th>Código</th><th>Placa</th><th class="text-end">KM</th><th class="text-end">${volLabel}</th><th class="text-end">Rend. ${unidadLabel}</th></tr></thead><tbody>`;
        data.forEach(r => {
            const rendVal = r[unidadField];
            const badge = rendVal !== null
                ? `<span class="badge ${rendVal>=thresholdOk?'bg-success':rendVal>=thresholdWarn?'bg-warning':'bg-danger'}">${fmt(rendVal)} ${unidadLabel}</span>`
                : '<span class="badge bg-secondary">N/A</span>';
            html += `<tr style="cursor:pointer" onclick="loadRendimientoDetalle(${r.id_vehiculo}, '${esc(r.placa)}')"><td>${esc(r.codigo_unidad)}</td><td>${esc(r.placa)}</td>
                <td class="text-end">${fmtInt(r.km_totales_recorridos)}</td>
                <td class="text-end">${fmt(r[volField])}</td>
                <td class="text-end">${badge}</td></tr>`;
        });
    } else if (seccion === 'comparativo') {
        const unidadLabel = 'KM/Gal';
        const unidadField = 'rendimiento_promedio_kmgln';
        html += `<thead class="table-light"><tr><th>Código</th><th>Placa</th><th>Marca/Modelo</th><th class="text-end">Teórica ${unidadLabel}</th><th class="text-end">Promedio ${unidadLabel}</th><th class="text-end">Dif.</th><th class="text-end">% Eficiencia</th><th class="text-end">Despachos</th></tr></thead><tbody>`;
        data.forEach(c => {
            const ef = c.porcentaje_eficiencia;
            const efClass = ef >= 100 ? 'bg-success' : ef >= 80 ? 'bg-warning' : 'bg-danger';
            const diff = c.diferencia_promedio_vs_teorico;
            const diffClass = diff >= 0 ? 'text-success' : 'text-danger';
            html += `<tr><td>${esc(c.codigo_unidad)}</td><td>${esc(c.placa)}</td>
                <td>${esc((c.marca||'')+' '+(c.modelo||''))}</td>
                <td class="text-end">${fmt(c.rendimiento_teorico)}</td>
                <td class="text-end fw-medium">${fmt(c[unidadField])}</td>
                <td class="text-end ${diffClass} fw-medium">${diff>0?'+':''}${fmt(diff)}</td>
                <td class="text-end"><span class="badge ${efClass}">${fmt(ef,1)}%</span></td>
                <td class="text-end">${c.total_despachos}</td></tr>`;
        });
    } else if (seccion === 'turnos') {
        html += `<thead class="table-light"><tr><th>Turno</th><th>Bomba</th><th>Usuario</th><th>Apertura</th><th>Cierre</th><th class="text-end">Lec. Inicial</th><th class="text-end">Lec. Final</th><th class="text-end">Teórico</th><th class="text-end">Despachado</th><th class="text-end">Diferencia</th></tr></thead><tbody>`;
        data.forEach(t => {
            const diff = t.diferencia;
            const diffClass = Math.abs(diff) < 1 ? 'text-success' : Math.abs(diff) < 5 ? 'text-warning' : 'text-danger';
            const usuario = t.usuario_cierre || t.usuario_apertura || '—';
            html += `<tr><td>#${t.id_lectura_bomba}</td>
                <td>${esc(t.nombre_bomba || '—')}</td>
                <td><small>${esc(usuario)}</small></td>
                <td>${t.fecha_apertura ? fmtDate(t.fecha_apertura) : 'N/A'}</td>
                <td>${t.fecha_cierre ? fmtDate(t.fecha_cierre) : '<span class="badge bg-info">Abierto</span>'}</td>
                <td class="text-end">${fmt(t.lectura_inicial_galones)}</td>
                <td class="text-end">${fmt(t.lectura_final_galones)}</td>
                <td class="text-end fw-medium">${fmt(t.teorico)}</td>
                <td class="text-end">${fmt(t.despachado)}</td>
                <td class="text-end ${diffClass} fw-bold">${diff>0?'+':''}${fmt(diff)}</td></tr>`;
        });
    }
    html += '</tbody></table></div>';
    return html;
}

function esc(v) { return v != null ? String(v).replace(/[<>&"]/g, c => ({'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;'}[c])) : ''; }
function fmt(v, d=2) { return v != null ? Number(v).toLocaleString('en-US', {minimumFractionDigits:d, maximumFractionDigits:d}) : '0'; }
function fmtInt(v) { return v != null ? Number(v).toLocaleString('en-US') : '0'; }
function fmtDate(d) { const dt = new Date(d); return dt.toLocaleString('es-ES', {day:'2-digit',month:'2-digit',year:'numeric',hour:'2-digit',minute:'2-digit'}); }

let rendimientoChart = null;

function loadRendimientoDetalle(idVehiculo, placa) {
    const modal = new bootstrap.Modal(document.getElementById('modalRendimientoDetalle'));
    document.getElementById('modalRendTitulo').textContent = 'Detalle de Rendimiento — ' + placa;
    document.getElementById('thRendUnidad').textContent = 'Rend. KM/Gal';
    document.getElementById('tbodyRendimientoDetalle').innerHTML = '<tr><td colspan="7" class="text-center py-3 text-muted">Cargando...</td></tr>';
    document.getElementById('chartRendimientoDetalle').innerHTML = '';
    if (rendimientoChart) { rendimientoChart.destroy(); rendimientoChart = null; }
    modal.show();

    fetch(`${BASE_URL}reportes/ajax-rendimiento-detalle/${idVehiculo}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        const data = res.data || [];
        if (!data.length) {
            document.getElementById('tbodyRendimientoDetalle').innerHTML = '<tr><td colspan="7" class="text-center py-3 text-muted">Sin registros</td></tr>';
            return;
        }

        const unidadLabel = 'KM/Gal';
        const unidadField = 'rendimiento_km_gln';

        // Tabla
        let html = '';
        data.forEach(d => {
            const rendVal = d[unidadField];
            const rendBadge = rendVal !== null
                ? `<span class="badge ${rendVal>=10?'bg-success':rendVal>=7?'bg-warning':'bg-danger'}">${fmt(rendVal)}</span>`
                : '<span class="badge bg-secondary">N/A</span>';
            html += `<tr>
                <td><small>${fmtDate(d.fecha_registro)}</small></td>
                <td class="text-end">${fmtInt(d.kilometraje_anterior)}</td>
                <td class="text-end">${fmtInt(d.kilometraje_actual)}</td>
                <td class="text-end fw-medium">${fmtInt(d.km_recorridos)}</td>
                <td class="text-end">${fmt(d.cantidad_galones)}</td>
                <td class="text-end">${rendBadge}</td>
            </tr>`;
        });
        document.getElementById('tbodyRendimientoDetalle').innerHTML = html;

        // Gráfico de línea
        const elChart = document.getElementById('chartRendimientoDetalle');
        if (elChart && typeof ApexCharts !== 'undefined') {
            rendimientoChart = new ApexCharts(elChart, {
                series: [{
                    name: 'Rendimiento ' + unidadLabel,
                    data: data.map(d => parseFloat(d[unidadField]) || 0)
                }],
                chart: {
                    type: 'line',
                    height: 280,
                    fontFamily: 'inherit',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#0d6efd'],
                stroke: { width: 3, curve: 'smooth' },
                markers: { size: 5, hover: { size: 7 } },
                xaxis: {
                    categories: data.map(d => {
                        const dt = new Date(d.fecha_registro);
                        return dt.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                    }),
                    labels: { rotate: -45, style: { fontSize: '11px' } }
                },
                yaxis: {
                    title: { text: unidadLabel },
                    labels: { formatter: v => fmt(v, 1) }
                },
                tooltip: {
                    y: { formatter: v => fmt(v, 2) + ' ' + unidadLabel }
                },
                grid: { borderColor: '#e9ecef', strokeDashArray: 4 }
            });
            rendimientoChart.render();
        }
    })
    .catch(() => {
        document.getElementById('tbodyRendimientoDetalle').innerHTML = '<tr><td colspan="7" class="text-center py-3 text-danger">Error al cargar</td></tr>';
    });
}

// Cargar primera tab al iniciar
document.addEventListener('DOMContentLoaded', function() {
    loadTab('top10');

    // Lazy load al cambiar de tab
    document.querySelectorAll('#tabReportes button[data-bs-toggle="tab"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('data-bs-target').replace('#tab-', '');
            loadTab(target);
        });
    });


    // Exportar Excel
    document.querySelectorAll('.btn-export').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const seccion = this.dataset.seccion;
            window.location.href = `${BASE_URL}reportes/exportar-excel?seccion=${seccion}${buildQuery() ? '&' + buildQuery().slice(1) : ''}`;
        });
    });
});
</script>
<?= $this->endSection() ?>
