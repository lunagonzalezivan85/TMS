<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-clipboard-list me-2 text-success"></i><?= $title ?></h3>
        <a href="<?= base_url('solicitudes/create') ?>" class="btn btn-success rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Nueva Solicitud
        </a>
    </div>

    <!-- Panel de filtros colapsable -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3" data-bs-toggle="collapse" data-bs-target="#panelFiltros" style="cursor:pointer;">
            <div class="d-flex align-items-center">
                <i class="fas fa-filter text-success me-2"></i>
                <span class="fw-semibold">Filtros de búsqueda</span>
                <?php
                $filtrosActivos = array_filter([
                    $filtros['estado'],
                    $filtros['fecha_desde'],
                    $filtros['fecha_hasta'],
                    $filtros['id_vehiculo'],
                    $filtros['busqueda']
                ]);
                ?>
                <?php if (!empty($filtrosActivos)): ?>
                    <span class="badge bg-success ms-2 rounded-pill"><?= count($filtrosActivos) ?></span>
                <?php endif; ?>
            </div>
            <i class="fas fa-chevron-down text-muted"></i>
        </div>
        <div class="collapse <?= !empty($filtrosActivos) ? 'show' : '' ?>" id="panelFiltros">
            <div class="card-body pt-0">
                <form id="filtroForm" method="GET" action="<?= base_url('solicitudes') ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="estado" class="form-label small text-muted">Estado</label>
                            <select name="estado" id="estado" class="form-select rounded-3">
                                <option value="">Todos los estados</option>
                                <?php foreach ($estados as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= ($filtros['estado'] == $key) ? 'selected' : '' ?>><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_desde" class="form-label small text-muted">Fecha desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control rounded-3" value="<?= $filtros['fecha_desde'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_hasta" class="form-label small text-muted">Fecha hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control rounded-3" value="<?= $filtros['fecha_hasta'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="id_vehiculo" class="form-label small text-muted">Vehículo</label>
                            <select name="id_vehiculo" id="id_vehiculo" class="form-select rounded-3">
                                <option value="">Todos los vehículos</option>
                                <?php foreach ($vehiculos as $vehiculo): ?>
                                    <option value="<?= $vehiculo['id'] ?>" <?= ($filtros['id_vehiculo'] == $vehiculo['id']) ? 'selected' : '' ?>><?= esc($vehiculo['placa']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label for="search" class="form-label small text-muted">Buscar</label>
                            <div class="input-group rounded-3 overflow-hidden border">
                                <span class="input-group-text border-0 bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" id="search" class="form-control border-0" placeholder="Código, placa, descripción, solicitante..." value="<?= esc($filtros['busqueda']) ?>">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-success flex-fill rounded-3"><i class="fas fa-search me-1"></i> Buscar</button>
                                <a href="<?= base_url('solicitudes') ?>" class="btn btn-outline-secondary rounded-3"><i class="fas fa-eraser"></i></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Listado de solicitudes en cards -->
    <?php if (empty($solicitudes)): ?>
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-inbox fa-4x text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted">No se encontraron solicitudes</h5>
            <p class="text-muted mb-4">Ajusta los filtros o crea una nueva solicitud.</p>
            <a href="<?= base_url('solicitudes/create') ?>" class="btn btn-success rounded-pill px-4">
                <i class="fas fa-plus me-2"></i>Nueva Solicitud
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($solicitudes as $s): ?>
                <?php
                $estado = $s['estado'] ?? 'PENDIENTE';
                $estadoClass = match(strtoupper($estado)) {
                    'PENDIENTE' => 'bg-warning text-dark',
                    'EN_PROCESO' => 'bg-info text-dark',
                    'APROBADA', 'APROBADAS' => 'bg-success',
                    'COMPLETADA', 'FINALIZADA' => 'bg-primary',
                    'CANCELADA' => 'bg-secondary',
                    'RECHAZADA' => 'bg-dark',
                    default => 'bg-light text-dark'
                };
                $estadoLabel = $estados[$estado] ?? $estado;

                $prioridad = (int)($s['prioridad'] ?? 2);
                $prioridadClass = ['success', 'warning', 'orange', 'danger'][$prioridad - 1] ?? 'secondary';
                $prioridadLabels = [1 => 'Baja', 2 => 'Media', 3 => 'Alta', 4 => 'Crítica'];
                $prioridadLabel = $prioridadLabels[$prioridad] ?? 'Media';
                ?>
                <div class="col-lg-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge rounded-pill <?= $estadoClass ?> mb-2"><?= esc($estadoLabel) ?></span>
                                    <h5 class="fw-bold mb-0"><?= esc($s['codigo_consecutivo']) ?></h5>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($s['fecha_solicitud'] ?? $s['fechaSolicitud'] ?? 'now')) ?></small>
                                </div>
                                <span class="badge rounded-pill bg-<?= $prioridadClass ?> bg-opacity-10 text-<?= $prioridadClass ?> border border-<?= $prioridadClass ?> border-opacity-25"><?= $prioridadLabel ?></span>
                            </div>

                            <div class="d-flex align-items-center mb-3 p-2 rounded-3 bg-light">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm" style="width:44px;height:44px;">
                                        <i class="fas fa-car text-success"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <p class="fw-semibold mb-0"><?= esc($s['placa'] ?? 'N/A') ?></p>
                                    <small class="text-muted"><?= esc(($s['marca'] ?? '') . ' ' . ($s['modelo'] ?? '')) ?></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Problema</small>
                                <p class="fw-semibold mb-1"><?= esc($s['tipo_problema'] ?? 'N/A') ?></p>
                                <p class="text-muted small mb-0 line-clamp-2"><?= character_limiter(esc($s['descripcion'] ?? ''), 120) ?></p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i><?= esc($s['nombre_solicitante'] ?? 'N/A') ?>
                                </small>
                                <a href="<?= base_url('solicitudes/show/' . $s['id']) ?>" class="btn btn-sm btn-outline-success rounded-pill">
                                    Ver detalle<i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Paginación simple -->
        <?php if ($totalRecords > $perPage): ?>
            <?php
            $totalPages = (int)ceil($totalRecords / $perPage);
            $query = array_filter($_GET);
            unset($query['page']);
            $baseQuery = http_build_query($query);
            $sep = $baseQuery ? '&' : '';
            ?>
            <nav class="mt-5" aria-label="Paginación de solicitudes">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link rounded-start-4" href="<?= $page > 1 ? base_url('solicitudes?' . $baseQuery . $sep . 'page=' . ($page - 1)) : '#' ?>">Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= base_url('solicitudes?' . $baseQuery . $sep . 'page=' . $i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link rounded-end-4" href="<?= $page < $totalPages ? base_url('solicitudes?' . $baseQuery . $sep . 'page=' . ($page + 1)) : '#' ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    .hover-card { transition: transform .15s ease, box-shadow .15s ease; }
    .hover-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,.08) !important; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bg-orange { background-color: #fd7e14 !important; }
    .text-orange { color: #fd7e14 !important; }
    .border-orange { border-color: #fd7e14 !important; }
</style>
<?= $this->endSection() ?>
