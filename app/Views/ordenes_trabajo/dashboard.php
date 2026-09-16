<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
.kpi-card{border:none;border-radius:.75rem;transition:transform .2s,box-shadow .2s;}
.kpi-card:hover{transform:translateY(-2px);box-shadow:0 .5rem 1.5rem rgba(0,0,0,.08)!important;}
.kpi-icon{width:48px;height:48px;border-radius:.5rem;display:flex;align-items:center;justify-content:center;font-size:1.25rem;}
.ot-sidebar-item{border-radius:.5rem;margin-bottom:.25rem;transition:background .15s;}
.ot-sidebar-item:hover{background:#f1f5f9;}
.ot-sidebar-item.active{background:#eff6ff;border-left:3px solid #0d6efd;}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-0 fw-bold">
                <i class="fas fa-tools me-2 text-primary"></i>Órdenes de Trabajo
            </h1>
            <p class="text-muted small mb-0">Gestión y seguimiento de órdenes de mantenimiento</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('ordenes-trabajo/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nueva Orden
            </a>
            <a href="<?= base_url('ordenes-trabajo/calendario') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-calendar-alt me-1"></i> Calendario
            </a>
            <a href="<?= base_url('ordenes-trabajo/kanban') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-columns me-1"></i> Kanban
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <?php
        $totalTodas = 0;
        if (isset($conteoEstados) && is_array($conteoEstados)) {
            foreach ($conteoEstados as $estado => $count) {
                $totalTodas += is_array($count) ? ($count['total'] ?? 0) : $count;
            }
        }
        $kpis = [
            ['label' => 'Total', 'count' => $totalTodas, 'icon' => 'fa-list', 'color' => 'primary', 'bg' => '#eff6ff', 'estado' => 'todas'],
            ['label' => 'Pendientes', 'count' => $conteoEstados['PENDIENTE'] ?? 0, 'icon' => 'fa-clock', 'color' => 'warning', 'bg' => '#fffbeb', 'estado' => 'PENDIENTE'],
            ['label' => 'Aprobadas', 'count' => $conteoEstados['APROBADA'] ?? 0, 'icon' => 'fa-check-circle', 'color' => 'success', 'bg' => '#f0fdf4', 'estado' => 'APROBADA'],
            ['label' => 'En Proceso', 'count' => $conteoEstados['EN_PROCESO'] ?? 0, 'icon' => 'fa-cogs', 'color' => 'info', 'bg' => '#ecfeff', 'estado' => 'EN_PROCESO'],
            ['label' => 'Finalizadas', 'count' => $conteoEstados['FINALIZADA'] ?? 0, 'icon' => 'fa-flag-checkered', 'color' => 'secondary', 'bg' => '#f8fafc', 'estado' => 'FINALIZADA'],
        ];
        foreach ($kpis as $kpi):
        ?>
        <div class="col-6 col-md-4 col-xl-2">
            <a href="<?= base_url('ordenes-trabajo') ?>?estado=<?= $kpi['estado'] ?>" class="text-decoration-none">
                <div class="card kpi-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="kpi-icon me-3" style="background:<?= $kpi['bg'] ?>;color:var(--bs-<?= $kpi['color'] ?>);">
                            <i class="fas <?= $kpi['icon'] ?>"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold lh-1 text-<?= $kpi['color'] ?>"><?= $kpi['count'] ?></div>
                            <small class="text-muted"><?= $kpi['label'] ?></small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Layout principal -->
    <div class="row g-3">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size:.7rem;letter-spacing:.05em;">
                        <i class="fas fa-filter me-1"></i>Filtrar por Estado
                    </h6>
                    <div class="list-group list-group-flush">
                        <?php
                        $estados = [
                            'todas' => ['label' => 'Todas', 'icon' => 'fa-list', 'color' => 'secondary'],
                            'PLANIFICADA' => ['label' => 'Planificadas', 'icon' => 'fa-calendar-alt', 'color' => 'info'],
                            'PENDIENTE' => ['label' => 'Pendientes', 'icon' => 'fa-clock', 'color' => 'warning'],
                            'APROBADA' => ['label' => 'Aprobadas', 'icon' => 'fa-check-circle', 'color' => 'success'],
                            'EN_PROCESO' => ['label' => 'En Proceso', 'icon' => 'fa-cogs', 'color' => 'primary'],
                            'FINALIZADA' => ['label' => 'Finalizadas', 'icon' => 'fa-flag-checkered', 'color' => 'secondary'],
                        ];
                        $estadoActivoUrl = $_GET['estado'] ?? 'todas';
                        foreach ($estados as $key => $info):
                            $count = $key === 'todas' ? $totalTodas : ($conteoEstados[$key] ?? 0);
                        ?>
                        <a href="<?= base_url('ordenes-trabajo') ?>?estado=<?= $key ?>"
                           class="list-group-item list-group-item-action border-0 px-2 py-2 ot-sidebar-item <?= $estadoActivoUrl === $key ? 'active' : '' ?>"
                           data-estado="<?= $key ?>">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>
                                    <i class="fas <?= $info['icon'] ?> text-<?= $info['color'] ?> me-2"></i>
                                    <small class="fw-medium"><?= $info['label'] ?></small>
                                </span>
                                <span class="badge rounded-pill bg-light text-dark"><?= $count ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size:.7rem;letter-spacing:.05em;">
                        <i class="fas fa-bolt me-1"></i>Accesos Rápidos
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('ordenes-trabajo/create') ?>" class="btn btn-outline-primary btn-sm text-start">
                            <i class="fas fa-plus me-2"></i>Nueva Orden
                        </a>
                        <a href="<?= base_url('ordenes-trabajo/mis-ordenes') ?>" class="btn btn-outline-primary btn-sm text-start">
                            <i class="fas fa-user-cog me-2"></i>Mis Órdenes
                        </a>
                        <a href="<?= base_url('ordenes-trabajo/consulta') ?>" class="btn btn-outline-secondary btn-sm text-start">
                            <i class="fas fa-search me-2"></i>Búsqueda Avanzada
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla principal -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-list text-primary me-2"></i><span id="titulo-lista">Órdenes Recientes</span>
                    </h6>
                    <div class="d-flex gap-2">
                        <div class="input-group input-group-sm" style="width:200px;">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Buscar...">
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" onclick="refreshOrders()" title="Actualizar">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" title="Ordenar">
                                <i class="fas fa-sort"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="sortOrders('fecha_desc');return false;"><i class="fas fa-clock me-2"></i>Más recientes</a></li>
                                <li><a class="dropdown-item" href="#" onclick="sortOrders('fecha_asc');return false;"><i class="fas fa-history me-2"></i>Más antiguas</a></li>
                                <li><a class="dropdown-item" href="#" onclick="sortOrders('prioridad');return false;"><i class="fas fa-exclamation me-2"></i>Por prioridad</a></li>
                                <li><a class="dropdown-item" href="#" onclick="sortOrders('estado');return false;"><i class="fas fa-tag me-2"></i>Por estado</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="lista-ordenes">
                        <?php if (!empty($solicitudesRecientes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="tablaOrdenes">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" width="70">Código</th>
                                        <th>Vehículo</th>
                                        <th>Descripción</th>
                                        <th width="110">Estado</th>
                                        <th width="90">Prioridad</th>
                                        <th width="120">Fecha</th>
                                        <th width="80" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ordenes as $orden): ?>
                                    <?php
                                    $estadoTexto = [
                                        'PENDIENTE' => 'Pendiente', 'APROBADA' => 'Aprobada',
                                        'EN_PROCESO' => 'En Proceso', 'FINALIZADA' => 'Finalizada',
                                        'PLANIFICADA' => 'Planificada'
                                    ];
                                    $badgeClass = [
                                        'PENDIENTE' => 'bg-warning text-dark', 'APROBADA' => 'bg-success',
                                        'EN_PROCESO' => 'bg-primary', 'FINALIZADA' => 'bg-secondary',
                                        'PLANIFICADA' => 'bg-info'
                                    ];
                                    $prioridadTexto = ['1' => 'Baja', '2' => 'Media', '3' => 'Alta', '4' => 'Crítica'];
                                    $prioridadClass = ['1' => 'bg-light text-dark', '2' => 'bg-warning text-dark', '3' => 'bg-danger', '4' => 'bg-dark'];
                                    ?>
                                    <tr class="orden-row" data-estado="<?= esc($orden['estado']) ?>">
                                        <td class="ps-3">
                                            <span class="fw-bold text-primary"><?= $orden['codigo_consecutivo'] ?? ('#' . $orden['id']) ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;">
                                                    <i class="fas fa-car text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium"><?= esc($orden['placa'] ?? 'N/A') ?></div>
                                                    <small class="text-muted"><?= esc($orden['marca'] ?? '') ?> <?= esc($orden['modelo'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width:220px;" title="<?= esc($orden['descripcion']) ?>">
                                                <?= strlen($orden['descripcion']) > 55 ? substr(esc($orden['descripcion']), 0, 55) . '…' : esc($orden['descripcion']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill <?= $badgeClass[$orden['estado']] ?? 'bg-light text-dark' ?>">
                                                <?= $estadoTexto[$orden['estado']] ?? esc($orden['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill <?= $prioridadClass[$orden['prioridad']] ?? 'bg-secondary' ?>">
                                                <?= $prioridadTexto[$orden['prioridad']] ?? 'N/A' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="d-block"><?= date('d/m/Y', strtotime($orden['fecha_solicitud'])) ?></small>
                                            <small class="text-muted"><?= date('H:i', strtotime($orden['fecha_solicitud'])) ?></small>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('ordenes-trabajo/show/' . $orden['id']) ?>"
                                               class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                <i class="fas fa-inbox fa-2x text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-1">No hay órdenes de trabajo</h5>
                            <p class="text-muted small mb-3">Comienza creando tu primera orden de trabajo</p>
                            <a href="<?= base_url('ordenes-trabajo/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Crear Primera Orden
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!empty($solicitudesRecientes) && count($ordenes) >= 20): ?>
                <div class="card-footer bg-white border-top py-2 text-center">
                    <small class="text-muted">Mostrando las 20 órdenes más recientes ·
                        <a href="<?= base_url('ordenes-trabajo/consulta') ?>" class="text-decoration-none">Ver todas</a>
                    </small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const estadoActivo = urlParams.get('estado') || 'todas';
    updateTitulo(estadoActivo);

    // Búsqueda en vivo
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#tablaOrdenes tbody tr').filter(function() {
            return $(this).text().toLowerCase().indexOf(value) > -1;
        }).show();
        $('#tablaOrdenes tbody tr').not(function() {
            return $(this).text().toLowerCase().indexOf(value) > -1;
        }).hide();
    });
});

function updateTitulo(estado) {
    const titulos = {
        'todas': 'Todas las Órdenes',
        'PLANIFICADA': 'Órdenes Planificadas',
        'PENDIENTE': 'Órdenes Pendientes',
        'APROBADA': 'Órdenes Aprobadas',
        'EN_PROCESO': 'Órdenes en Proceso',
        'FINALIZADA': 'Órdenes Finalizadas'
    };
    $('#titulo-lista').text(titulos[estado] || 'Órdenes de Trabajo');
}

function refreshOrders() {
    location.reload();
}

function sortOrders(tipo) {
    const tbody = $('#tablaOrdenes tbody');
    const rows = tbody.find('tr').toArray();
    rows.sort(function(a, b) {
        switch(tipo) {
            case 'fecha_desc':
                return new Date($(b).find('td:eq(5)').text()) - new Date($(a).find('td:eq(5)').text());
            case 'fecha_asc':
                return new Date($(a).find('td:eq(5)').text()) - new Date($(b).find('td:eq(5)').text());
            case 'prioridad':
                const orden = {'Crítica': 4, 'Alta': 3, 'Media': 2, 'Baja': 1};
                return (orden[$(b).find('td:eq(4) .badge').text()] || 0) - (orden[$(a).find('td:eq(4) .badge').text()] || 0);
            case 'estado':
                return $(a).find('td:eq(3) .badge').text().localeCompare($(b).find('td:eq(3) .badge').text());
            default:
                return 0;
        }
    });
    tbody.empty().append(rows);
}
</script>
<?= $this->endSection() ?>
