<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.nav-pills-custom {
    gap: 0.5rem;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 0.75rem;
}
.nav-pills-custom .nav-link {
    border: 1px solid #dee2e6;
    color: #6c757d;
    padding: 0.4rem 1rem;
    transition: all 0.2s;
}
.nav-pills-custom .nav-link.active {
    background: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}
.nav-pills-custom .nav-link:not(.active):hover {
    background: #f8f9fa;
    color: #0d6efd;
}
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
}
@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
.skeleton-row {
    height: 38px;
    border-radius: 4px;
    margin-bottom: 8px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">

    <!-- ── Breadcrumb ─────────────────────────────── -->
    <nav aria-label="breadcrumb" class="mb-2 mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('reportes/combustible') ?>">Reportes</a></li>
            <li class="breadcrumb-item active">Vehículos</li>
        </ol>
    </nav>

    <!-- ════════════════════════════════════════════════════════════
         HERO BANNER — One UI Style
    ═════════════════════════════════════════════════════════════ -->
    <div class="card shadow-sm mb-3 border-0 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);">
        <div class="card-body p-4 text-white position-relative">
            <div class="row align-items-center position-relative">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                        <span class="badge fs-5 fw-bold px-4 py-2 rounded-pill" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); letter-spacing: 2px;">
                            <i class="fas fa-chart-bar me-2"></i>Reporte de Flota
                        </span>
                        <h3 class="mb-0 fw-bold d-inline">Vehículos & Rendimiento</h3>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(76,175,80,0.85);">
                            <i class="fas fa-check-circle me-1"></i><?= (int)($kpis['total_activos'] ?? 0) ?> Activos
                        </span>
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(158,158,158,0.85);">
                            <i class="fas fa-pause-circle me-1"></i><?= (int)($kpis['total_inactivos'] ?? 0) ?> Inactivos
                        </span>
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,152,0,0.85);">
                            <i class="fas fa-tools me-1"></i><?= (int)($kpis['total_reparacion'] ?? 0) ?> En Reparación
                        </span>
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.15);">
                            <i class="fas fa-truck me-1"></i><?= (int)($kpis['total_vehiculos'] ?? 0) ?> Total Vehículos
                        </span>
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="d-flex gap-2 justify-content-lg-end flex-wrap">
                        <a href="<?= base_url('reportes/combustible') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="fas fa-gas-pump me-1"></i>Reporte Combustible
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         BENTO GRID — KPIs Globales
    ═════════════════════════════════════════════════════════════ -->
    <div class="row g-3 mb-3">
        <!-- Bento 1: Total Vehículos -->
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="avatar-sm rounded-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0d6efd, #0a58ca); width: 36px; height: 36px;">
                            <i class="fas fa-truck text-white"></i>
                        </div>
                        <small class="text-muted fw-medium text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Flota</small>
                    </div>
                    <h3 class="fw-bold mb-0"><?= (int)($kpis['total_vehiculos'] ?? 0) ?></h3>
                    <small class="text-muted">Total Vehículos</small>
                </div>
            </div>
        </div>

        <!-- Bento 2: KM Recorridos -->
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="avatar-sm rounded-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #6f42c1, #5a32a3); width: 36px; height: 36px;">
                            <i class="fas fa-road text-white"></i>
                        </div>
                        <small class="text-muted fw-medium text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Kilometraje</small>
                    </div>
                    <h3 class="fw-bold mb-0"><?= number_format((float)($kpis_combustible['promedio_km_por_vehiculo'] ?? 0), 0) ?></h3>
                    <small class="text-muted">Promedio KM por Vehículo</small>
                </div>
            </div>
        </div>

        <!-- Bento 3: Total Galones -->
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="avatar-sm rounded-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #fd7e14, #dc6504); width: 36px; height: 36px;">
                            <i class="fas fa-gas-pump text-white"></i>
                        </div>
                        <small class="text-muted fw-medium text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Combustible</small>
                    </div>
                    <h3 class="fw-bold mb-0"><?= number_format((float)($kpis_combustible['total_galones'] ?? 0), 1) ?></h3>
                    <small class="text-muted">Galones Consumidos</small>
                </div>
            </div>
        </div>

        <!-- Bento 4: Total Despachos -->
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="avatar-sm rounded-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #20c997, #17a988); width: 36px; height: 36px;">
                            <i class="fas fa-database text-white"></i>
                        </div>
                        <small class="text-muted fw-medium text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Registros</small>
                    </div>
                    <h3 class="fw-bold mb-0"><?= (int)($kpis_combustible['total_despachos'] ?? 0) ?></h3>
                    <small class="text-muted">Total Despachos</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         PILL SEGMENTED NAVIGATION
    ═════════════════════════════════════════════════════════════ -->
    <ul class="nav nav-pills nav-pills-custom mb-3" id="reporteVehiculosTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill" id="tab-resumen" data-bs-toggle="pill" data-bs-target="#pane-resumen" type="button" role="tab">
                <i class="fas fa-tachometer-alt me-1"></i>Resumen Flota
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill" id="tab-ranking" data-bs-toggle="pill" data-bs-target="#pane-ranking" type="button" role="tab">
                <i class="fas fa-trophy me-1"></i>Ranking Rendimiento
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill" id="tab-kilometraje" data-bs-toggle="pill" data-bs-target="#pane-kilometraje" type="button" role="tab">
                <i class="fas fa-road me-1"></i>Kilometraje
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reporteVehiculosContent">

        <!-- ════════════════════════════════════════════════════════════
             TAB 1: RESUMEN DE FLOTA
        ═════════════════════════════════════════════════════════════ -->
        <div class="tab-pane fade show active" id="pane-resumen" role="tabpanel">
            <div class="row g-3 mb-3">
                <!-- Donut de estados -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-pie text-primary me-2"></i>Distribución por Estado</h6>
                        </div>
                        <div class="card-body p-2">
                            <div id="chartEstados" style="min-height: 280px;"></div>
                        </div>
                    </div>
                </div>
                <!-- RadialBar de cumplimiento -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-semibold"><i class="fas fa-bullseye text-success me-2"></i>Cumplimiento Rendimiento</h6>
                        </div>
                        <div class="card-body p-2">
                            <div id="chartCumplimiento" style="min-height: 250px;"></div>
                            <div class="d-flex gap-2 justify-content-center mt-2">
                                <button class="btn btn-sm btn-outline-success rounded-pill px-3" id="btnCumplen">
                                    <i class="fas fa-check-circle me-1"></i>Cumplen (<span id="cntCumplen">—</span>)
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3" id="btnNoCumplen">
                                    <i class="fas fa-times-circle me-1"></i>No Cumplen (<span id="cntNoCumplen">—</span>)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Top KM -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-semibold"><i class="fas fa-route text-info me-2"></i>Top 10 KM Recorridos</h6>
                        </div>
                        <div class="card-body p-2">
                            <div id="chartTopKm" style="min-height: 280px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla resumen -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="fas fa-table text-primary me-2"></i>Resumen por Vehículo</h6>
                    <button class="btn btn-outline-success btn-sm rounded-pill px-3" id="btnExportResumen">
                        <i class="fas fa-file-excel me-1"></i>Exportar
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="content-resumen">
                        <div class="p-3">
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════════════════════════════
             TAB 2: RANKING DE RENDIMIENTO
        ═════════════════════════════════════════════════════════════ -->
        <div class="tab-pane fade" id="pane-ranking" role="tabpanel">
            <div class="row g-3 mb-3">
                <!-- Gráfico barras real vs teórico -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-bar text-primary me-2"></i>Top 10 — Historial de Kilometraje</h6>
                        </div>
                        <div class="card-body p-2">
                            <div id="chartRanking" style="min-height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="fas fa-trophy text-warning me-2"></i>Ranking de Rendimiento</h6>
                    <button class="btn btn-outline-success btn-sm rounded-pill px-3" id="btnExportRanking">
                        <i class="fas fa-file-excel me-1"></i>Exportar
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="content-ranking">
                        <div class="p-3">
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════════════════════════════
             TAB 3: KILOMETRAJE
        ═════════════════════════════════════════════════════════════ -->
        <div class="tab-pane fade" id="pane-kilometraje" role="tabpanel">
            <div class="row g-3 mb-3">
                <!-- Gráfico barras lineal vs tramos -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-bar text-info me-2"></i>Top 10 — KM Lineal vs KM por Tramos</h6>
                        </div>
                        <div class="card-body p-2">
                            <div id="chartKilometraje" style="min-height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="fas fa-road text-primary me-2"></i>Detalle de Kilometraje</h6>
                    <button class="btn btn-outline-success btn-sm rounded-pill px-3" id="btnExportKilometraje">
                        <i class="fas fa-file-excel me-1"></i>Exportar
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="content-kilometraje">
                        <div class="p-3">
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                            <div class="skeleton skeleton-row"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /tab-content -->
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const baseUrl = '<?= base_url() ?>';
    const fmtInt = v => Number(v).toLocaleString('en-US', {maximumFractionDigits: 0});
    const fmtDec = v => Number(v).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const fmtCompact = v => {
        const n = Number(v);
        if (n >= 1e6) return (n / 1e6).toFixed(1).replace(/\.0$/, '') + 'M';
        if (n >= 1e3) return (n / 1e3).toFixed(1).replace(/\.0$/, '') + 'k';
        return String(n);
    };

    let charts = {};
    let loaded = { resumen: false, ranking: false, kilometraje: false };
    let resumenData = null;

    // ── Modal de cumplimiento ───────────────────────
    function mostrarCumplimiento(data, tipo) {
        const lista = tipo === 'cumplen' ? (data.cumplen || []) : (data.no_cumplen || []);
        const titulo = tipo === 'cumplen' ? 'Vehículos que SÍ Cumplen' : 'Vehículos que NO Cumplen';
        const color = tipo === 'cumplen' ? 'success' : 'danger';
        const icono = tipo === 'cumplen' ? 'check-circle' : 'times-circle';

        const csvHeaders = ['Código', 'Placa', 'Marca', 'Modelo', 'Rend. Teórico', 'Rend. Promedio', 'Evaluaciones', 'Diferencia'];
        const csvRows = lista.map(v => {
            const diff = parseFloat(v.rendimiento_promedio) - parseFloat(v.rendimiento_teorico);
            return [v.codigo_unidad || '', v.placa || '', v.marca || '', v.modelo || '', v.rendimiento_teorico || 0, v.rendimiento_promedio || 0, v.total_evaluaciones || 0, diff.toFixed(2)];
        });

        let html = '<div class="modal fade" id="modalCumplimiento" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">'
            + '<div class="modal-header bg-' + color + ' text-white"><h5 class="modal-title"><i class="fas fa-' + icono + ' me-2"></i>' + titulo + '</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>'
            + '<div class="modal-body" style="max-height: 60vh; overflow-y: auto;">';

        if (lista.length === 0) {
            html += '<div class="text-center py-4 text-muted"><i class="fas fa-info-circle fa-2x mb-2"></i><p>No hay vehículos en esta categoría.</p></div>';
        } else {
            html += '<div class="table-responsive"><table class="table table-hover table-sm mb-0">'
                + '<thead class="table-light"><tr><th>Código</th><th>Placa</th><th>Marca/Modelo</th><th class="text-end">Rend. Teórico</th><th class="text-end">Rend. Promedio</th><th class="text-end">Evaluaciones</th><th class="text-end">Diferencia</th></tr></thead><tbody>';
            lista.forEach(v => {
                const diff = parseFloat(v.rendimiento_promedio) - parseFloat(v.rendimiento_teorico);
                const diffCls = diff >= 0 ? 'text-success' : 'text-danger';
                html += '<tr style="cursor:pointer" onclick="window.location.href=\'' + baseUrl + 'vehiculos/show/' + v.id + '\'">'
                    + '<td><span class="badge bg-primary">' + (v.codigo_unidad || '—') + '</span></td>'
                    + '<td><span class="badge bg-dark">' + (v.placa || '—') + '</span></td>'
                    + '<td><small>' + (v.marca || '') + ' ' + (v.modelo || '') + '</small></td>'
                    + '<td class="text-end">' + fmtDec(v.rendimiento_teorico) + '</td>'
                    + '<td class="text-end fw-bold">' + fmtDec(v.rendimiento_promedio) + '</td>'
                    + '<td class="text-end">' + (v.total_evaluaciones || 0) + '</td>'
                    + '<td class="text-end ' + diffCls + '">' + (diff >= 0 ? '+' : '') + fmtDec(diff) + '</td>'
                    + '</tr>';
            });
            html += '</tbody></table></div>';
        }

        html += '</div><div class="modal-footer">'
            + '<button type="button" class="btn btn-outline-success btn-sm" id="btnExportCumplimiento"><i class="fas fa-file-excel me-1"></i>Exportar Excel</button>'
            + '<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button></div></div></div></div>';

        const existing = document.getElementById('modalCumplimiento');
        if (existing) existing.remove();
        document.body.insertAdjacentHTML('beforeend', html);
        const modal = new bootstrap.Modal(document.getElementById('modalCumplimiento'));
        modal.show();

        // Exportar Excel del modal
        document.getElementById('btnExportCumplimiento')?.addEventListener('click', function() {
            if (lista.length === 0) return;
            const csv = [csvHeaders, ...csvRows].map(row => row.map(c => '"' + String(c).replace(/"/g, '""') + '"').join(',')).join('\n');
            const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'cumplimiento_' + tipo + '.csv';
            link.click();
        });
    }

    // ── Helpers ─────────────────────────────────────
    function renderSkeleton(containerId) {
        const el = document.getElementById(containerId);
        if (!el) return;
        el.innerHTML = '<div class="p-3"><div class="skeleton skeleton-row"></div><div class="skeleton skeleton-row"></div><div class="skeleton skeleton-row"></div><div class="skeleton skeleton-row"></div><div class="skeleton skeleton-row"></div></div>';
    }

    function estadoBadge(estado) {
        const map = { 'ACTIVO': 'success', 'INACTIVO': 'secondary', 'EN REPARACION': 'warning' };
        const cls = map[estado] || 'secondary';
        return '<span class="badge bg-' + cls + '">' + estado + '</span>';
    }

    // ── TAB 1: Resumen Flota ────────────────────────
    function loadResumen() {
        if (loaded.resumen) return;
        loaded.resumen = true;
        renderSkeleton('content-resumen');

        fetch(baseUrl + 'reportes/ajax-resumen-flota')
        .then(r => r.json())
        .then(data => {
            // Gráfico Donut de estados
            const estadosData = data.estados.map(e => ({
                label: e.estado,
                val: parseInt(e.cantidad)
            }));
            const estadoColors = { 'ACTIVO': '#4caf50', 'INACTIVO': '#9e9e9e', 'EN REPARACION': '#ff9800' };

            const elDonut = document.querySelector('#chartEstados');
            if (elDonut && typeof ApexCharts !== 'undefined') {
                charts.estados = new ApexCharts(elDonut, {
                    series: estadosData.map(e => e.val),
                    chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
                    labels: estadosData.map(e => e.label),
                    colors: estadosData.map(e => estadoColors[e.label] || '#999'),
                    legend: { position: 'bottom' },
                    dataLabels: { enabled: true, formatter: val => Math.round(val) },
                    plotOptions: { pie: { donut: { size: '65%' } } }
                });
                charts.estados.render();
            }

            // RadialBar de cumplimiento
            const cum = data.cumplimiento;
            const pctCumple = cum && cum.total_evaluados > 0
                ? Math.round((cum.cumplen / cum.total_evaluados) * 100)
                : 0;

            const cntC = document.getElementById('cntCumplen');
            const cntNC = document.getElementById('cntNoCumplen');
            if (cntC) cntC.textContent = cum ? cum.cumplen : 0;
            if (cntNC) cntNC.textContent = cum ? cum.no_cumplen : 0;

            const elRadial = document.querySelector('#chartCumplimiento');
            if (elRadial && typeof ApexCharts !== 'undefined') {
                const numCumplen = parseInt(cum ? cum.cumplen : 0) || 0;
                const numNoCumplen = parseInt(cum ? cum.no_cumplen : 0) || 0;
                console.log('Cumplimiento data:', { numCumplen, numNoCumplen, raw: cum });
                charts.cumplimiento = new ApexCharts(elRadial, {
                    series: [numCumplen, numNoCumplen],
                    chart: {
                        type: 'pie',
                        height: 250,
                        fontFamily: 'inherit',
                        events: {
                            dataPointSelection: function(e, chart, opts) {
                                const idx = opts.dataPointIndex;
                                mostrarCumplimiento(data, idx === 0 ? 'cumplen' : 'no_cumplen');
                            }
                        }
                    },
                    labels: ['Cumplen', 'No Cumplen'],
                    colors: ['#4caf50', '#f44336'],
                    legend: { position: 'bottom', fontSize: '13px', labels: { colors: '#6c757d' } },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val, opts) {
                            return opts.w.config.series[opts.seriesIndex];
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val, opts) {
                                const total = opts.config.series.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return val + ' (' + pct + '%)';
                            }
                        }
                    }
                });
                charts.cumplimiento.render();
            }

            // Botones de cumplimiento
            document.getElementById('btnCumplen')?.addEventListener('click', () => mostrarCumplimiento(data, 'cumplen'));
            document.getElementById('btnNoCumplen')?.addEventListener('click', () => mostrarCumplimiento(data, 'no_cumplen'));

            // Top 10 KM
            const topKm = data.vehiculos
                .map(v => ({ label: v.placa || v.codigo_unidad, val: parseInt(v.km_actual || 0) - parseInt(v.km_inicial || 0) }))
                .sort((a, b) => b.val - a.val)
                .slice(0, 10);

            const elTopKm = document.querySelector('#chartTopKm');
            if (elTopKm && typeof ApexCharts !== 'undefined') {
                charts.topKm = new ApexCharts(elTopKm, {
                    series: [{ name: 'KM Recorridos', data: topKm.map(v => v.val) }],
                    chart: { type: 'bar', height: 280, fontFamily: 'inherit', toolbar: { show: false } },
                    colors: ['#0d6efd'],
                    plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: false } },
                    labels: topKm.map(v => v.label),
                    xaxis: { labels: { formatter: v => fmtCompact(v) } },
                    tooltip: { y: { formatter: v => fmtInt(v) + ' KM' } }
                });
                charts.topKm.render();
            }

            // Tabla resumen
            let html = '<div class="table-responsive"><table class="table table-hover table-sm mb-0">';
            html += '<thead class="table-light"><tr><th>Unidad</th><th>Placa</th><th>Marca/Modelo</th><th>Estado</th><th class="text-end">KM Actual</th><th class="text-end">Galones</th><th class="text-end">Cargas</th><th class="text-end">Rend. Teor.</th><th class="text-end">Rend. Prom.</th></tr></thead><tbody>';
            data.vehiculos.forEach(v => {
                html += '<tr style="cursor:pointer" onclick="window.location.href=\'' + baseUrl + 'vehiculos/show/' + v.id + '\'">'
                    + '<td>' + (v.codigo_unidad || '—') + '</td>'
                    + '<td><span class="badge bg-dark">' + (v.placa || '—') + '</span></td>'
                    + '<td><small>' + (v.marca || '') + ' ' + (v.modelo || '') + '</small></td>'
                    + '<td>' + estadoBadge(v.estado) + '</td>'
                    + '<td class="text-end">' + fmtInt(v.km_actual || 0) + '</td>'
                    + '<td class="text-end">' + fmtDec(v.total_galones || 0) + '</td>'
                    + '<td class="text-end">' + (v.total_cargas || 0) + '</td>'
                    + '<td class="text-end">' + fmtDec(v.rendimiento_teorico || 0) + '</td>'
                    + '<td class="text-end fw-medium">' + (v.rendimiento_promedio ? fmtDec(v.rendimiento_promedio) : '—') + '</td>'
                    + '</tr>';
            });
            html += '</tbody></table></div>';
            document.getElementById('content-resumen').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('content-resumen').innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle fa-2x mb-2"></i><p>Error al cargar datos</p></div>';
            console.error(err);
        });
    }

    // ── TAB 2: Ranking Rendimiento ──────────────────
    function loadRanking() {
        if (loaded.ranking) return;
        loaded.ranking = true;
        renderSkeleton('content-ranking');

        fetch(baseUrl + 'reportes/ajax-ranking-rendimiento')
        .then(r => r.json())
        .then(data => {
            const rows = data.data || [];

            // Gráfico barras apiladas horizontales: Top 10 por KM Recorrido
            const top10 = rows
                .map(r => ({ ...r, sortVal: parseInt(r.km_recorridos) || 0 }))
                .sort((a, b) => b.sortVal - a.sortVal)
                .slice(0, 10);

            const elChart = document.querySelector('#chartRanking');
            if (elChart && typeof ApexCharts !== 'undefined') {
                charts.ranking = new ApexCharts(elChart, {
                    series: [
                        { name: 'KM Inicial', data: top10.map(r => parseInt(r.km_inicial) || 0) },
                        { name: 'KM Recorrido', data: top10.map(r => parseInt(r.km_recorridos) || 0) },
                        { name: 'KM Actual', data: top10.map(r => parseInt(r.km_final) || 0) },
                        { name: 'KM Prom. Recorrido', data: top10.map(r => parseInt(r.km_promedio_recorrido) || 0) }
                    ],
                    chart: {
                        type: 'bar',
                        height: 400,
                        fontFamily: 'inherit',
                        toolbar: { show: false },
                        stacked: true,
                        horizontal: true
                    },
                    colors: ['#adb5bd', '#0d6efd', '#198754', '#fd7e14'],
                    plotOptions: { bar: { borderRadius: 4, barHeight: '60%' } },
                    stroke: { width: 1, colors: ['#fff'] },
                    labels: top10.map(r => ((r.placa || '') + ' / ' + (r.codigo_unidad || '—')).trim()),
                    xaxis: {
                        labels: {
                            formatter: function(v) { return fmtCompact(v); }
                        }
                    },
                    yaxis: { labels: { style: { fontSize: '12px', fontWeight: 600 } } },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        x: {
                            formatter: function(val, opts) {
                                const idx = opts.dataPointIndex;
                                const r = top10[idx];
                                if (!r) return val || '';
                                return (r.placa || '') + ' / ' + (r.codigo_unidad || '—');
                            }
                        },
                        y: {
                            formatter: function(val, opts) {
                                return fmtInt(val) + ' KM';
                            }
                        }
                    },
                    legend: { position: 'top', fontSize: '12px' },
                    fill: { opacity: [0.5, 0.9, 0.85, 0.8] },
                    grid: { borderColor: '#e9ecef', strokeDashArray: 4 }
                });
                charts.ranking.render();
            }

            // Tabla
            let html = '<div class="table-responsive"><table class="table table-hover table-sm mb-0">';
            html += '<thead class="table-light"><tr><th>#</th><th>Código</th><th>Placa</th><th>Marca/Modelo</th><th class="text-end">KM Inicial</th><th class="text-end">KM Recorrido</th><th class="text-end">KM Actual</th><th class="text-end">Rend. Teor.</th><th class="text-end">Rend. Prom.</th><th class="text-end">Galones</th><th class="text-end">Despachos</th><th class="text-end">Desviación</th><th class="text-center">Estado</th></tr></thead><tbody>';
            rows.forEach((r, i) => {
                const teor = parseFloat(r.rendimiento_teorico) || 0;
                const prom = parseFloat(r.rendimiento_promedio) || 0;
                const fuera = parseInt(r.fuera_rango) === 1;
                const desviacion = teor > 0 ? ((prom - teor) / teor) * 100 : 0;
                const desvCls = desviacion >= 0 ? 'text-success' : 'text-danger';
                const desvSign = desviacion >= 0 ? '+' : '';
                const estadoBadge = fuera
                    ? '<span class="badge bg-danger" title="Rendimiento fuera de rango ±5"><i class="fas fa-exclamation-triangle"></i> Fuera</span>'
                    : '<span class="badge bg-success" title="Dentro de rango ±5"><i class="fas fa-check"></i> OK</span>';
                const rowCls = fuera ? 'table-warning' : '';
                html += '<tr class="' + rowCls + '" style="cursor:pointer" onclick="window.location.href=\'' + baseUrl + 'vehiculos/show/' + r.id + '\'">'
                    + '<td>' + (i + 1) + '</td>'
                    + '<td><span class="badge bg-primary">' + (r.codigo_unidad || '—') + '</span></td>'
                    + '<td><span class="badge bg-dark">' + (r.placa || '—') + '</span></td>'
                    + '<td><small>' + (r.marca || '') + ' ' + (r.modelo || '') + '</small></td>'
                    + '<td class="text-end">' + fmtInt(r.km_inicial) + '</td>'
                    + '<td class="text-end fw-bold text-primary">' + fmtInt(r.km_recorridos) + '</td>'
                    + '<td class="text-end fw-bold">' + fmtInt(r.km_final) + '</td>'
                    + '<td class="text-end">' + fmtDec(r.rendimiento_teorico) + '</td>'
                    + '<td class="text-end fw-medium ' + (fuera ? 'text-danger' : '') + '">' + (r.rendimiento_promedio ? fmtDec(r.rendimiento_promedio) : '—') + '</td>'
                    + '<td class="text-end">' + fmtDec(r.total_galones) + '</td>'
                    + '<td class="text-end">' + (r.total_despachos || 0) + '</td>'
                    + '<td class="text-end fw-bold ' + desvCls + '">' + desvSign + fmtDec(desviacion) + '%</td>'
                    + '<td class="text-center">' + estadoBadge + '</td>'
                    + '</tr>';
            });
            html += '</tbody></table></div>';
            document.getElementById('content-ranking').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('content-ranking').innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle fa-2x mb-2"></i><p>Error al cargar datos</p></div>';
            console.error(err);
        });
    }

    // ── TAB 3: Kilometraje ──────────────────────────
    function loadKilometraje() {
        if (loaded.kilometraje) return;
        loaded.kilometraje = true;
        renderSkeleton('content-kilometraje');

        fetch(baseUrl + 'reportes/ajax-kilometraje')
        .then(r => r.json())
        .then(data => {
            const rows = data.data || [];

            // Gráfico barras apiladas horizontales: Top 10 por KM Lineal
            const top10km = rows
                .map(r => ({ ...r, sortVal: parseInt(r.km_lineal) || 0 }))
                .sort((a, b) => b.sortVal - a.sortVal)
                .slice(0, 10);

            const elChart = document.querySelector('#chartKilometraje');
            if (elChart && typeof ApexCharts !== 'undefined') {
                charts.kilometraje = new ApexCharts(elChart, {
                    series: [
                        { name: 'KM Lineal', data: top10km.map(r => parseInt(r.km_lineal) || 0) },
                        { name: 'KM por Tramos', data: top10km.map(r => parseInt(r.km_tramos) || 0) }
                    ],
                    chart: {
                        type: 'bar',
                        height: 400,
                        fontFamily: 'inherit',
                        toolbar: { show: false },
                        stacked: true,
                        horizontal: true
                    },
                    colors: ['#0d6efd', '#fd7e14'],
                    plotOptions: { bar: { borderRadius: 4, barHeight: '60%' } },
                    stroke: { width: 1, colors: ['#fff'] },
                    labels: top10km.map(r => ((r.placa || '') + ' / ' + (r.codigo_unidad || '—')).trim()),
                    xaxis: {
                        labels: {
                            formatter: function(v) { return fmtCompact(v); }
                        }
                    },
                    yaxis: { labels: { style: { fontSize: '12px', fontWeight: 600 } } },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        x: {
                            formatter: function(val, opts) {
                                const idx = opts.dataPointIndex;
                                const r = top10km[idx];
                                if (!r) return val || '';
                                return (r.placa || '') + ' / ' + (r.codigo_unidad || '—');
                            }
                        },
                        y: {
                            formatter: function(val, opts) {
                                return fmtInt(val) + ' KM';
                            }
                        }
                    },
                    legend: { position: 'top', fontSize: '12px' },
                    fill: { opacity: [0.9, 0.8] },
                    grid: { borderColor: '#e9ecef', strokeDashArray: 4 }
                });
                charts.kilometraje.render();
            }

            // Tabla
            let html = '<div class="table-responsive"><table class="table table-hover table-sm mb-0">';
            html += '<thead class="table-light"><tr><th>Placa</th><th>Marca/Modelo</th><th class="text-end">KM Inicial</th><th class="text-end">KM Final</th><th class="text-end">KM Lineal</th><th class="text-end">KM Tramos</th><th class="text-end">Descuadre</th><th class="text-end">Registros</th></tr></thead><tbody>';
            rows.forEach(r => {
                const desc = parseInt(r.diferencia_descuadre) || 0;
                const descCls = desc !== 0 ? 'text-warning fw-bold' : 'text-muted';
                html += '<tr style="cursor:pointer" onclick="window.location.href=\'' + baseUrl + 'vehiculos/show/' + r.id + '\'">'
                    + '<td><span class="badge bg-dark">' + (r.placa || '—') + '</span></td>'
                    + '<td><small>' + (r.marca || '') + ' ' + (r.modelo || '') + '</small></td>'
                    + '<td class="text-end">' + fmtInt(r.km_inicial) + '</td>'
                    + '<td class="text-end">' + fmtInt(r.km_final) + '</td>'
                    + '<td class="text-end">' + fmtInt(r.km_lineal) + '</td>'
                    + '<td class="text-end">' + fmtInt(r.km_tramos) + '</td>'
                    + '<td class="text-end ' + descCls + '">' + (desc !== 0 ? fmtInt(Math.abs(desc)) + ' km' : '—') + '</td>'
                    + '<td class="text-end">' + (r.total_registros || 0) + '</td>'
                    + '</tr>';
            });
            html += '</tbody></table></div>';
            document.getElementById('content-kilometraje').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('content-kilometraje').innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle fa-2x mb-2"></i><p>Error al cargar datos</p></div>';
            console.error(err);
        });
    }

    // ── Tab switching ───────────────────────────────
    loadResumen();

    document.getElementById('tab-ranking').addEventListener('shown.bs.tab', loadRanking);
    document.getElementById('tab-kilometraje').addEventListener('shown.bs.tab', loadKilometraje);

    // ── Export buttons ──────────────────────────────
    document.getElementById('btnExportResumen')?.addEventListener('click', () => {
        window.location = baseUrl + 'reportes/exportar-excel-vehiculos?seccion=resumen';
    });
    document.getElementById('btnExportRanking')?.addEventListener('click', () => {
        window.location = baseUrl + 'reportes/exportar-excel-vehiculos?seccion=ranking';
    });
    document.getElementById('btnExportKilometraje')?.addEventListener('click', () => {
        window.location = baseUrl + 'reportes/exportar-excel-vehiculos?seccion=kilometraje';
    });
});
</script>
<?= $this->endSection() ?>
