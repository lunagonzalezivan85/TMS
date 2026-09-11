<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
.rc-card { border:1px solid #e5e7eb; border-radius:12px; background:#fff; transition:box-shadow .15s; overflow:hidden; }
.rc-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); }
.rc-card .rc-left { width:5px; min-height:100%; flex-shrink:0; }
.rc-card.consumo .rc-left { background:linear-gradient(180deg,#07b889,#059c73); }
.rc-card.venta    .rc-left { background:linear-gradient(180deg,#6366f1,#4f46e5); }
.rc-card.bloqueado .rc-left { background:#ef4444; }
.stat-pill { background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; padding:4px 10px; font-size:.78rem; }
.stat-pill .label { color:#9ca3af; font-size:.68rem; display:block; }
.stat-pill .val   { font-weight:600; color:#111827; }
#filtrosBody { transition:.2s; }

/* Dropdown z-index fix - eliminar overflow del table-responsive */
.card-body.table-responsive {
    overflow: visible !important;
}

/* Responsive: Cards en móvil, tabla en desktop */
@media (min-width: 768px) {
    .tabla-desktop { display: block; }
    .cards-movil { display: none !important; }
}
@media (max-width: 767px) {
    .tabla-desktop { display: none !important; }
    .cards-movil { display: flex !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$registrosConsumo = $registros;

// Separar registros por tipo de vehículo (interno/externo)
$registrosInternos = array_filter($registros, fn($r) => ($r['externo'] ?? 0) != 1);
$registrosExternos = array_filter($registros, fn($r) => ($r['externo'] ?? 0) == 1);

$fechaHoy = date('Y-m-d');
$hayFiltros = !empty($filtros['vehiculo'])
           || (!empty($filtros['fecha_desde']) && $filtros['fecha_desde'] !== $fechaHoy)
           || (!empty($filtros['fecha_hasta']) && $filtros['fecha_hasta'] !== $fechaHoy)
           || !empty($filtros['usuario']);

$voucherId = session()->getFlashdata('voucher_id');
?>

<input type="hidden" id="voucherIdFlash" value="<?= $voucherId ? (int)$voucherId : '' ?>">
<input type="hidden" id="voucherUrlBase" value="<?= base_url('registro-combustible/voucher/consumo/') ?>">

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0"><i class="fas fa-gas-pump me-2 text-success"></i><?= $page_title ?></h4>
            <small class="text-muted"><?= count($registros) ?> registros encontrados</small>
        </div>
        <div class="d-flex gap-2">
            <?php if (!empty($filtros['lectura_id'])): ?>
                <a href="<?= base_url('registro-combustible') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Limpiar filtro
                </a>
            <?php endif; ?>
            <a href="<?= base_url('registro-combustible/estadisticas') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-chart-bar me-1"></i><span class="d-none d-sm-inline">Estadísticas</span>
            </a>
            <a href="<?= base_url('registro-combustible/create') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i>Nuevo
            </a>
        </div>
    </div>

    <!-- ALERTA TOTAL LITROS -->
    <?php if (isset($filtros['lectura_id']) && !empty($filtros['lectura_id'])): ?>
        <div class="alert alert-info alert-dismissible fade show py-2">
            <i class="fas fa-info-circle me-2"></i>
            Registros de combustible para lectura #<?= $filtros['lectura_id'] ?> -
            <strong>Total: <?= number_format($total_litros ?? 0, 2) ?> litros</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ALERTAS -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show py-2">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show py-2">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- MÉTRICAS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;flex-shrink:0">
                        <i class="fas fa-list text-success"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0 lh-1" id="stat_total">—</div>
                        <small class="text-muted">Total registros</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;flex-shrink:0">
                        <i class="fas fa-tint text-primary"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0 lh-1" id="stat_litros">—</div>
                        <small class="text-muted" id="stat_litros_small">—</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;flex-shrink:0">
                        <i class="fas fa-truck text-primary"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0 lh-1" id="stat_vehiculos">—</div>
                        <small class="text-muted">Vehículos</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;flex-shrink:0">
                        <i class="fas fa-sync-alt text-warning"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0 lh-1" id="stat_sag_pendiente">—</div>
                        <small class="text-muted">Pendientes SAG</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTROS (colapsable) -->
    <div class="card mb-3 border-0 shadow-sm">
        <div id="filtrosHeader" class="card-header bg-white d-flex justify-content-between align-items-center py-2" style="cursor:pointer">
            <span class="fw-semibold text-muted small">
                <i class="fas fa-filter me-1"></i>Filtros
                <?php if ($hayFiltros): ?>
                    <span class="badge bg-success ms-1">Activos</span>
                <?php endif; ?>
            </span>
            <i class="fas fa-chevron-down text-muted" id="filtrosIcon" style="transition:.2s;<?= $hayFiltros ? '' : 'transform:rotate(-90deg)' ?>"></i>
        </div>
        <div id="filtrosBody" <?= $hayFiltros ? '' : 'style="display:none"' ?>>
            <div class="card-body pt-2 pb-3">
                <form method="GET" action="<?= base_url('registro-combustible') ?>">
                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label small mb-1">Vehículo</label>
                            <select name="vehiculo" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <?php foreach ($vehiculos as $v): ?>
                                    <option value="<?= $v['id'] ?>" <?= $filtros['vehiculo'] == $v['id'] ? 'selected' : '' ?>>
                                        <?= esc($v['codigo_unidad'] ?? $v['placa']) ?> — <?= esc($v['placa']) ?> — <?= esc($v['marca']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6 col-sm-3 col-md-2">
                            <label class="form-label small mb-1">Desde</label>
                            <input type="date" name="fecha_desde" class="form-control form-control-sm" value="<?= $filtros['fecha_desde'] ?? '' ?>">
                        </div>
                        <div class="col-6 col-sm-3 col-md-2">
                            <label class="form-label small mb-1">Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="<?= $filtros['fecha_hasta'] ?? '' ?>">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label small mb-1">Usuario</label>
                            <select name="usuario" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <?php foreach ($usuarios as $u): ?>
                                    <option value="<?= $u['usuario'] ?>" <?= $filtros['usuario'] == $u['usuario'] ? 'selected' : '' ?>>
                                        <?= esc($u['usuario']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-success btn-sm flex-grow-1">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="<?= base_url('registro-combustible') ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?= view('registro_combustible/_tabs_tipo_consumo', [
        'registros' => $registros,
        'catalogosCRC' => $catalogosCRC ?? [],
    ]) ?>

    <div class="d-none">
    <!-- LEGACY TABS INTERNO / EXTERNO -->
    <ul class="nav nav-tabs mb-2" id="rcTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="tab-interno" data-bs-toggle="tab" data-bs-target="#pane-interno" type="button" role="tab">
                <i class="fas fa-warehouse me-1 text-info"></i>Internos
                <span class="badge bg-info text-white ms-1"><?= count($registrosInternos) ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="tab-externo" data-bs-toggle="tab" data-bs-target="#pane-externo" type="button" role="tab">
                <i class="fas fa-truck-moving me-1 text-warning"></i>Externos
                <span class="badge bg-warning text-dark ms-1"><?= count($registrosExternos) ?></span>
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- ══ TAB: INTERNO ══ -->
        <div class="tab-pane fade show active" id="pane-interno" role="tabpanel">
            <div class="bg-light rounded-bottom">
                <!-- Vista Tabla Desktop -->
                <div class="tabla-desktop">
                    <div class="card border shadow-sm">
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped table-hover mb-0 table-sm">
                                <thead>
                                    <tr>
                                        <th>Vehículo</th>
                                        <th>Conductor</th>
                                        <th>Fecha</th>
                                        <th>Consumo</th>
                                        <th>Km Recorridos</th>
                                        <th>Rendimiento</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($registrosInternos)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="fas fa-gas-pump fa-2x mb-2 opacity-25"></i>
                                                <p class="mb-0">No hay registros de vehículos internos.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($registrosInternos as $r):
                                            $kmAnterior = (float)($r['kilometraje_anterior'] ?? 0);
                                            $kmActual   = (float)($r['kilometraje_actual']   ?? 0);
                                            $litros     = (float)($r['cantidad_litros']      ?? 0);
                                            $kmRec      = $kmActual - $kmAnterior;
                                            $rend       = ($kmRec > 0 && $litros > 0) ? round($kmRec / $litros, 2) : 0;
                                            $estado     = strtoupper($r['estado'] ?? 'APROBADO');
                                            $bloqueado  = $estado === 'BLOQUEADO';
                                            $enviado    = ($r['enviado'] ?? 0) == 1;
                                            $rendColor  = $rend >= 10 ? 'success' : ($rend >= 7 ? 'warning' : 'danger');
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold"><?= esc($r['codigo_unidad'] ?? $r['placa'] ?? 'N/A') ?> — <?= esc($r['placa'] ?? 'N/A') ?></div>
                                                <small class="text-muted"><?= esc($r['marca'] ?? '') ?></small>
                                            </td>
                                            <td><?= esc($r['nombreCliente'] ?? '—') ?></td>
                                            <td><?= date('d/m/Y', strtotime($r['fecha_registro'])) ?></td>
                                            <td>
                                                <div class="fw-bold"><?= number_format($litros / 3.78541, 1) ?> gal</div>
                                                <small class="text-muted"><?= number_format($litros, 2) ?> L</small>
                                            </td>
                                            <td><?= number_format($kmRec) ?> km</td>
                                            <td>
                                                <?php if ($rend > 0): ?>
                                                    <span class="badge bg-<?= $rendColor ?>"><?= $rend ?> km/L</span>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge <?= $bloqueado ? 'bg-danger' : 'bg-success' ?>">
                                                    <?= $bloqueado ? 'Bloqueado' : 'Aprobado' ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown" data-bs-boundary="window" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                        <?php if ($enviado): ?>
                                                            <li><span class="dropdown-item text-success"><i class="fas fa-check-circle me-2"></i>Enviado al SAG</span></li>
                                                        <?php else: ?>
                                                            <li>
                                                                <button class="dropdown-item" data-action="reenviar-sag" data-id="<?= $r['id'] ?>">
                                                                    <i class="fas fa-sync-alt me-2 text-warning"></i>Reenviar SAG
                                                                </button>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('registro-combustible/show/'.$r['id']) ?>">
                                                                <i class="fas fa-eye me-2 text-info"></i>Ver detalle
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('registro-combustible/edit/'.$r['id']) ?>">
                                                                <i class="fas fa-edit me-2 text-primary"></i>Editar
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('registro-combustible/voucher/consumo/'.$r['id']) ?>">
                                                                <i class="fas fa-file-alt me-2 text-secondary"></i>Voucher
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" data-action="eliminar" data-id="<?= $r['id'] ?>">
                                                                <i class="fas fa-trash me-2"></i>Eliminar
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Vista Cards Móvil -->
                <div class="cards-movil d-flex flex-column gap-2">
                    <?php if (empty($registrosInternos)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-gas-pump fa-3x mb-3 opacity-25"></i>
                            <p>No hay registros de vehículos internos.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($registrosInternos as $r) {
                            $kmAnterior = (float)($r['kilometraje_anterior'] ?? 0);
                            $kmActual   = (float)($r['kilometraje_actual']   ?? 0);
                            $litros     = (float)($r['cantidad_litros']      ?? 0);
                            $kmRec      = $kmActual - $kmAnterior;
                            $rend       = ($kmRec > 0 && $litros > 0) ? round($kmRec / $litros, 2) : 0;
                            $estado     = strtoupper($r['estado'] ?? 'APROBADO');
                            $bloqueado  = $estado === 'BLOQUEADO';
                            $enviado    = ($r['enviado'] ?? 0) == 1;
                            $rendColor  = $rend >= 10 ? 'success' : ($rend >= 7 ? 'warning' : 'danger');
                        ?>
                        <div class="rc-card d-flex <?= $bloqueado ? 'bloqueado' : 'consumo' ?>">
                            <div class="rc-left"></div>
                            <div class="p-3 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <div class="fw-bold"><?= esc($r['placa'] ?? 'N/A') ?></div>
                                            <small class="text-muted"><?= esc(($r['marca'] ?? '').' '.($r['modelo'] ?? '')) ?></small>
                                        </div>
                                        <div class="vr d-none d-sm-block"></div>
                                        <div>
                                            <div class="fw-semibold small"><?= esc($r['nombreCliente'] ?? '—') ?></div>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($r['fecha_registro'])) ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($enviado): ?>
                                            <span class="sag-ok text-success fw-semibold" style="font-size:.75rem"><i class="fas fa-check-circle me-1"></i>SAG</span>
                                        <?php else: ?>
                                            <button class="btn btn-warning btn-sm" data-action="reenviar-sag" data-id="<?= $r['id'] ?>" title="Reenviar al SAG">
                                                <i class="fas fa-sync-alt me-1"></i>SAG
                                            </button>
                                        <?php endif; ?>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" aria-expanded="false" style="width:32px;padding:0;line-height:30px;border-radius:6px">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li><a class="dropdown-item" href="<?= base_url('registro-combustible/show/'.$r['id']) ?>">
                                                    <i class="fas fa-eye me-2 text-info"></i>Ver detalle</a></li>
                                                <li><a class="dropdown-item" href="<?= base_url('registro-combustible/edit/'.$r['id']) ?>">
                                                    <i class="fas fa-edit me-2 text-primary"></i>Editar</a></li>
                                                <li><a class="dropdown-item" href="<?= base_url('registro-combustible/voucher/consumo/'.$r['id']) ?>">
                                                    <i class="fas fa-file-alt me-2 text-secondary"></i>Voucher</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item text-danger" data-action="eliminar" data-id="<?= $r['id'] ?>">
                                                    <i class="fas fa-trash me-2"></i>Eliminar</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <div class="stat-pill"><span class="label">Litros</span><span class="val"><?= number_format($litros, 2) ?> L</span></div>
                                    <div class="stat-pill"><span class="label">Km recorridos</span><span class="val"><?= number_format($kmRec) ?> km</span></div>
                                    <?php if ($rend > 0): ?>
                                    <div class="stat-pill"><span class="label">Rendimiento</span><span class="val text-<?= $rendColor ?>"><?= $rend ?> km/L</span></div>
                                    <?php endif; ?>
                                    <div class="stat-pill"><span class="label">Estado</span><span class="val <?= $bloqueado ? 'text-danger' : 'text-success' ?>"><?= $bloqueado ? 'Bloqueado' : 'Aprobado' ?></span></div>
                                    <div class="stat-pill"><span class="label">Usuario</span><span class="val"><?= esc($r['usuario_crea'] ?? $r['usuario_edita'] ?? '—') ?></span></div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ══ TAB: EXTERNO ══ -->
        <div class="tab-pane fade" id="pane-externo" role="tabpanel">
            <div class="bg-light rounded-bottom">
                <!-- Vista Tabla Desktop -->
                <div class="tabla-desktop">
                    <div class="card border shadow-sm">
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped table-hover mb-0 table-sm">
                                <thead>
                                    <tr>
                                        <th>Vehículo</th>
                                        <th>Conductor</th>
                                        <th>Fecha</th>
                                        <th>Consumo</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($registrosExternos)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fas fa-gas-pump fa-2x mb-2 opacity-25"></i>
                                                <p class="mb-0">No hay registros de vehículos externos.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($registrosExternos as $r):
                                            $litros     = (float)($r['cantidad_litros'] ?? 0);
                                            $estado     = strtoupper($r['estado'] ?? 'APROBADO');
                                            $bloqueado  = $estado === 'BLOQUEADO';
                                            $enviado    = ($r['enviado'] ?? 0) == 1;
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold"><?= esc($r['codigo_unidad'] ?? $r['placa'] ?? 'N/A') ?> — <?= esc($r['placa'] ?? 'N/A') ?></div>
                                                <small class="text-muted"><?= esc($r['marca'] ?? '') ?></small>
                                            </td>
                                            <td><?= esc($r['nombreCliente'] ?? '—') ?></td>
                                            <td><?= date('d/m/Y', strtotime($r['fecha_registro'])) ?></td>
                                            <td>
                                                <div class="fw-bold"><?= number_format($litros / 3.78541, 1) ?> gal</div>
                                                <small class="text-muted"><?= number_format($litros, 2) ?> L</small>
                                            </td>
                                            <td>
                                                <span class="badge <?= $bloqueado ? 'bg-danger' : 'bg-success' ?>">
                                                    <?= $bloqueado ? 'Bloqueado' : 'Aprobado' ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown" data-bs-boundary="window" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                        <?php if ($enviado): ?>
                                                            <li><span class="dropdown-item text-success"><i class="fas fa-check-circle me-2"></i>Enviado al SAG</span></li>
                                                        <?php else: ?>
                                                            <li>
                                                                <button class="dropdown-item" data-action="reenviar-sag" data-id="<?= $r['id'] ?>">
                                                                    <i class="fas fa-sync-alt me-2 text-warning"></i>Reenviar SAG
                                                                </button>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('registro-combustible/show/'.$r['id']) ?>">
                                                                <i class="fas fa-eye me-2 text-info"></i>Ver detalle
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('registro-combustible/edit/'.$r['id']) ?>">
                                                                <i class="fas fa-edit me-2 text-primary"></i>Editar
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('registro-combustible/voucher/consumo/'.$r['id']) ?>">
                                                                <i class="fas fa-file-alt me-2 text-secondary"></i>Voucher
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" data-action="eliminar" data-id="<?= $r['id'] ?>">
                                                                <i class="fas fa-trash me-2"></i>Eliminar
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Vista Cards Móvil -->
                <div class="cards-movil d-flex flex-column gap-2">
                    <?php if (empty($registrosExternos)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-gas-pump fa-3x mb-3 opacity-25"></i>
                            <p>No hay registros de vehículos externos.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($registrosExternos as $r) {
                            $litros     = (float)($r['cantidad_litros'] ?? 0);
                            $estado     = strtoupper($r['estado'] ?? 'APROBADO');
                            $bloqueado  = $estado === 'BLOQUEADO';
                            $enviado    = ($r['enviado'] ?? 0) == 1;
                        ?>
                        <div class="rc-card d-flex <?= $bloqueado ? 'bloqueado' : 'consumo' ?>">
                            <div class="rc-left"></div>
                            <div class="p-3 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <div class="fw-bold"><?= esc($r['placa'] ?? 'N/A') ?></div>
                                            <small class="text-muted"><?= esc(($r['marca'] ?? '').' '.($r['modelo'] ?? '')) ?></small>
                                        </div>
                                        <div class="vr d-none d-sm-block"></div>
                                        <div>
                                            <div class="fw-semibold small"><?= esc($r['nombreCliente'] ?? '—') ?></div>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($r['fecha_registro'])) ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($enviado): ?>
                                            <span class="sag-ok text-success fw-semibold" style="font-size:.75rem"><i class="fas fa-check-circle me-1"></i>SAG</span>
                                        <?php else: ?>
                                            <button class="btn btn-warning btn-sm" data-action="reenviar-sag" data-id="<?= $r['id'] ?>" title="Reenviar al SAG">
                                                <i class="fas fa-sync-alt me-1"></i>SAG
                                            </button>
                                        <?php endif; ?>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" aria-expanded="false" style="width:32px;padding:0;line-height:30px;border-radius:6px">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li><a class="dropdown-item" href="<?= base_url('registro-combustible/show/'.$r['id']) ?>">
                                                    <i class="fas fa-eye me-2 text-info"></i>Ver detalle</a></li>
                                                <li><a class="dropdown-item" href="<?= base_url('registro-combustible/edit/'.$r['id']) ?>">
                                                    <i class="fas fa-edit me-2 text-primary"></i>Editar</a></li>
                                                <li><a class="dropdown-item" href="<?= base_url('registro-combustible/voucher/consumo/'.$r['id']) ?>">
                                                    <i class="fas fa-file-alt me-2 text-secondary"></i>Voucher</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item text-danger" data-action="eliminar" data-id="<?= $r['id'] ?>">
                                                    <i class="fas fa-trash me-2"></i>Eliminar</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <div class="stat-pill"><span class="label">Litros</span><span class="val"><?= number_format($litros, 2) ?> L</span></div>
                                    <div class="stat-pill"><span class="label">Estado</span><span class="val <?= $bloqueado ? 'text-danger' : 'text-success' ?>"><?= $bloqueado ? 'Bloqueado' : 'Aprobado' ?></span></div>
                                    <div class="stat-pill"><span class="label">Usuario</span><span class="val"><?= esc($r['usuario_crea'] ?? $r['usuario_edita'] ?? '—') ?></span></div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">¿Eliminar registro?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-2 small text-muted">Esta acción no se puede deshacer.</div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal imprimir voucher -->
<div class="modal fade" id="voucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title"><i class="fas fa-print me-2"></i>Imprimir voucher</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 small">
                El registro se guardó exitosamente. ¿Desea imprimir el voucher ahora?
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">No, gracias</button>
                <a href="#" id="btnImprimirVoucher" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-print me-1"></i>Sí, imprimir</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('public/assets/js/modules/RegistroCombustible.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        RegistroCombustibleIndex.init('<?= base_url() ?>');

        const voucherId = document.getElementById('voucherIdFlash')?.value;
        if (voucherId) {
            const voucherUrl = document.getElementById('voucherUrlBase')?.value + voucherId;
            const btn = document.getElementById('btnImprimirVoucher');
            if (btn) btn.href = voucherUrl;
            const voucherModal = new bootstrap.Modal(document.getElementById('voucherModal'));
            voucherModal.show();
        }
    });
</script>
<?= $this->endSection() ?>
