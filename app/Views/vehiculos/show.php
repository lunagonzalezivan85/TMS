<?= $this->extend('layouts/main') ?>
<?php helper('vehiculo'); ?>

<?= $this->section('content') ?>

<?php
$estadoClass = vehiculo_estado_class($vehiculo['estado']);
$estadoLabel = vehiculo_estado_label($vehiculo['estado']);
$estadoIcons = [
    'ACTIVO'        => 'fa-check-circle',
    'INACTIVO'      => 'fa-pause-circle',
    'EN REPARACION' => 'fa-tools',
];
$estadoIcon = $estadoIcons[$vehiculo['estado']] ?? 'fa-circle';

$colorMap = [
    'success'   => ['bg' => '#e8f5e9', 'border' => '#4caf50', 'text' => '#2e7d32'],
    'secondary' => ['bg' => '#f5f5f5', 'border' => '#9e9e9e', 'text' => '#424242'],
    'warning'   => ['bg' => '#fff8e1', 'border' => '#ff9800', 'text' => '#e65100'],
];
$accentColors = $colorMap[$estadoClass] ?? $colorMap['secondary'];
?>

<div class="container-fluid px-4">

    <!-- ── Breadcrumb ─────────────────────────────── -->
    <nav aria-label="breadcrumb" class="mb-2 mt-2">
        <ol class=" breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('vehiculos') ?>">Vehículos</a></li>
            <li class="breadcrumb-item active"><?= esc($vehiculo['placa']) ?></li>
        </ol>
    </nav>

    <!-- ════════════════════════════════════════════════════════════
         HERO BANNER — One UI Style
    ═════════════════════════════════════════════════════════════ -->
    <div class="card shadow-sm mb-3 border-0 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);">
        <div class="card-body p-4 text-white position-relative">
            <div class="row align-items-center position-relative">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                        <span class="badge fs-5 fw-bold px-4 py-2 rounded-pill" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); letter-spacing: 2px;">
                            <i class="fas fa-id-card me-2"></i><?= esc($vehiculo['placa']) ?>
                        </span>
                        <h3 class="mb-0 fw-bold d-inline">
                            <?= esc($vehiculo['marca']) ?> <?= esc($vehiculo['modelo']) ?>
                            <span class="fw-normal opacity-75 fs-5">(<?= esc($vehiculo['anio']) ?>)</span>
                        </h3>
                    </div>

                    <!-- Telemetría de estado — badges de salud -->
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="badge rounded-pill px-3 py-2" style="background: <?= $vehiculo['estado'] === 'ACTIVO' ? 'rgba(76,175,80,0.85)' : ($vehiculo['estado'] === 'EN REPARACION' ? 'rgba(255,152,0,0.85)' : 'rgba(158,158,158,0.85)') ?>;">
                            <i class="fas <?= $estadoIcon ?> me-1"></i><?= $estadoLabel ?>
                        </span>

                        <?php if ($vehiculo['conductor_nombre']): ?>
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.15);">
                            <i class="fas fa-user-tie me-1"></i><?= esc($vehiculo['conductor_nombre']) ?>
                        </span>
                        <?php else: ?>
                        <a href="<?= base_url('vehiculos/edit/' . $vehiculo['id']) ?>" class="badge rounded-pill px-3 py-2 text-decoration-none" style="background: rgba(255,193,7,0.3); border: 1px solid rgba(255,193,7,0.5);">
                            <i class="fas fa-user-plus me-1"></i>Sin Conductor / Asignar
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($vehiculo['empresa_nombre'])): ?>
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.15);">
                            <i class="fas fa-building me-1"></i><?= esc($vehiculo['empresa_nombre']) ?>
                        </span>
                        <?php endif; ?>

                        <?php if (!empty($vehiculo['codigo_unidad'])): ?>
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.15);">
                            <i class="fas fa-hashtag me-1"></i><?= esc($vehiculo['codigo_unidad']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                    <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                        <small class="opacity-75 d-none d-md-inline">Acciones rápidas</small>
                        <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="abrirCommandPaletteVehiculo()">
                            <i class="fas fa-bolt me-1"></i>Ctrl + K
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         BENTO GRID — KPIs + Chart (Data-Dense Bento Canvas)
    ═════════════════════════════════════════════════════════════ -->
    <div class="row g-3 mb-3">
        <!-- Gráfico de Consumo y Rendimiento — 60% del espacio -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-area text-primary me-2"></i>Consumo y Rendimiento
                    </h6>
                    <!-- Filtros temporales — Segmented Toggle -->
                    <div class="btn-group btn-group-sm" role="group" id="chartPeriodoToggle">
                        <button type="button" class="btn btn-outline-secondary" data-periodo="hoy">Hoy</button>
                        <button type="button" class="btn btn-outline-secondary" data-periodo="semana">Semana</button>
                        <button type="button" class="btn btn-outline-secondary active" data-periodo="mes">Mes</button>
                        <button type="button" class="btn btn-outline-secondary" data-periodo="historico">Histórico</button>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div id="chartConsumoRendimiento" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- Bento Lateral — 3 contenedores -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-3 h-100">

                <!-- Bento 1: Kilometraje (Odómetro Digital) -->
                <div class="card shadow-sm border-0 rounded-4 flex-grow-1">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="avatar-sm rounded-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #6f42c1, #5a32a3); width: 36px; height: 36px;">
                                <i class="mdi mdi-counter text-white"></i>
                            </div>
                            <small class="text-muted fw-medium text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Kilometraje</small>
                        </div>
                        <h3 class="fw-bold mb-1" id="bento_km_actual"><?= number_format($stats_rendimiento['km_final_global'] ?? 0, 0) ?></h3>
                        <small class="text-muted">KM Lectura Actual</small>
                        <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Inicial</small>
                                <span class="fw-medium" id="bento_km_inicial"><?= number_format($stats_rendimiento['km_inicial_global'] ?? 0, 0) ?> KM</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Por Tramo</small>
                                <span class="fw-medium" id="bento_km_tramos"><?= number_format($stats_rendimiento['km_recorrido_tramos'] ?? 0, 0) ?> KM</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bento 2: Combustible & Costos -->
                <div class="card shadow-sm border-0 rounded-4 flex-grow-1">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="avatar-sm rounded-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #fd7e14, #dc6504); width: 36px; height: 36px;">
                                <i class="mdi mdi-fuel text-white"></i>
                            </div>
                            <small class="text-muted fw-medium text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Combustible</small>
                        </div>
<?php
$totalGalones = (float)($stats_rendimiento['total_galones_consumidos'] ?? 0);
?>
                        <h3 class="fw-bold mb-1" id="bento_galones"><?= number_format($totalGalones, 1) ?></h3>
                        <small class="text-muted">Galones Consumidos</small>
                        <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Cargas</small>
                                <span class="fw-medium" id="bento_cargas"><?= $stats_rendimiento['total_despachos_registrados'] ?? 0 ?></span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Costo Mes</small>
                                <span class="fw-medium" id="bento_costo">$<?= number_format((float)($stats_combustible_mes['total_monto_usd'] ?? 0), 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bento 3: Rendimiento Teórico vs Real -->
                <div class="card shadow-sm border-0 rounded-4 flex-grow-1">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="avatar-sm rounded-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #20c997, #17a988); width: 36px; height: 36px;">
                                <i class="mdi mdi-target text-white"></i>
                            </div>
                            <small class="text-muted fw-medium text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Rendimiento</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Teórico</small>
                                <span class="fw-bold fs-5" id="bento_rend_teorico"><?= number_format($stats_rendimiento['rendimiento_teorico'] ?? 0, 2) ?></span>
                                <small class="text-muted">KM/Gal</small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Promedio Real</small>
                                <span class="fw-bold fs-5 <?= ($stats_rendimiento['rendimiento_promedio_registrado'] ?? 0) >= ($stats_rendimiento['rendimiento_teorico'] ?? 0) ? 'text-success' : 'text-danger' ?>" id="bento_rend_promedio"><?= number_format($stats_rendimiento['rendimiento_promedio_registrado'] ?? 0, 2) ?></span>
                                <small class="text-muted">KM/Gal</small>
                            </div>
                        </div>
                        <?php
                        $rendTeorico = (float)($stats_rendimiento['rendimiento_teorico'] ?? 0);
                        $rendReal = (float)($stats_rendimiento['rendimiento_promedio_registrado'] ?? 0);
                        $desviacion = $rendTeorico > 0 ? (($rendReal - $rendTeorico) / $rendTeorico) * 100 : 0;
                        $desvClass = $desviacion >= 0 ? 'text-success' : 'text-danger';
                        $desvIcon = $desviacion >= 0 ? '▲' : '▼';
                        ?>
                        <div class="mt-2 pt-2 border-top text-center">
                            <small class="<?= $desvClass ?> fw-medium"><?= $desvIcon ?> <?= number_format(abs($desviacion), 1) ?>% vs teórico</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         PILL SEGMENTED NAVIGATION — Pegado al header
    ═════════════════════════════════════════════════════════════ -->
    <ul class="nav nav-pills nav-pills-custom mb-3" id="vehiculoTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill" id="tab-info" data-bs-toggle="pill" data-bs-target="#pane-info" type="button" role="tab">
                <i class="fas fa-info-circle me-1"></i>Información
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill" id="tab-combustible" data-bs-toggle="pill" data-bs-target="#pane-combustible" type="button" role="tab">
                <i class="fas fa-gas-pump me-1"></i>Combustible
                <?php if (!empty($registros_combustible)): ?>
                <span class="badge bg-light text-dark ms-1"><?= count($registros_combustible) ?></span>
                <?php endif; ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill" id="tab-documentos" data-bs-toggle="pill" data-bs-target="#pane-documentos" type="button" role="tab">
                <i class="fas fa-file-alt me-1"></i>Documentos
                <?php if (!empty($documentos)): ?>
                <span class="badge bg-light text-dark ms-1"><?= count($documentos) ?></span>
                <?php endif; ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill" id="tab-estadisticas" data-bs-toggle="pill" data-bs-target="#pane-estadisticas" type="button" role="tab">
                <i class="fas fa-chart-bar me-1"></i>Estadísticas
            </button>
        </li>
    </ul>

    <div class="tab-content" id="vehiculoTabsContent">

        <!-- ════════════════════════════════════════════════════════════
             TAB 1: INFORMACIÓN
        ═════════════════════════════════════════════════════════════ -->
        <div class="tab-pane fade show active" id="pane-info" role="tabpanel">
            <div class="row g-4">

                <!-- Columna izquierda -->
                <div class="col-xl-8">

                    <!-- Detalles técnicos -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>Datos Técnicos
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-0">
                                <div class="col-md-6 pe-md-3">
                                    <?php
                                    $camposA = [
                                        ['fas fa-hashtag',     'Código',         esc($vehiculo['codigo_consecutivo'])],
                                        ['fas fa-id-card',     'Placa',          '<span class="badge bg-dark">' . esc($vehiculo['placa']) . '</span>'],
                                        ['fas fa-industry',    'Marca',          esc($vehiculo['marca'])],
                                        ['fas fa-car',         'Modelo',          esc($vehiculo['modelo'])],
                                        ['fas fa-calendar-alt','Año',            esc($vehiculo['anio'])],
                                    ];
                                    foreach ($camposA as [$ico, $label, $val]):
                                    ?>
                                    <div class="d-flex align-items-start py-2 border-bottom">
                                        <span class="text-muted me-3" style="width:20px; text-align:center;">
                                            <i class="fas <?= $ico ?> fa-sm"></i>
                                        </span>
                                        <span class="text-muted me-2" style="min-width:110px;"><?= $label ?>:</span>
                                        <span class="fw-medium"><?= $val ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-md-6 ps-md-3 border-md-start">
                                    <?php
                                    $ccDisplay = !empty($centro_costo)
                                        ? '<span class="badge bg-primary me-1">' . esc($centro_costo['CODIGO_CENTRO']) . '</span>'
                                          . '<small class="text-muted">' . esc($centro_costo['DESCRIPCION']) . '</small>'
                                        : (!empty($vehiculo['codigo_centro_costo'])
                                            ? '<span class="badge bg-warning text-dark">' . esc($vehiculo['codigo_centro_costo']) . '</span>'
                                            : '<span class="text-muted fst-italic">No asignado</span>');

                                    $camposB = [
                                        ['fas fa-barcode',   'N° Motor',     !empty($vehiculo['numero_motor'])  ? esc($vehiculo['numero_motor'])  : '<span class="text-muted fst-italic">—</span>'],
                                        ['fas fa-barcode',   'N° Chasis',    !empty($vehiculo['numero_chasis']) ? esc($vehiculo['numero_chasis']) : '<span class="text-muted fst-italic">—</span>'],
                                        ['fas fa-code',      'Código Unidad',!empty($vehiculo['codigo_unidad']) ? esc($vehiculo['codigo_unidad']) : '<span class="text-muted fst-italic">—</span>'],
                                        ['fas fa-building',  'Centro Costo', $ccDisplay],
                                        ['fas fa-gas-pump',  'Rend. Combus.',!empty($vehiculo['rendimiento'])   ? esc($vehiculo['rendimiento']) . ' KM/Gal' : '<span class="text-muted fst-italic">—</span>'],
                                    ];
                                    foreach ($camposB as [$ico, $label, $val]):
                                    ?>
                                    <div class="d-flex align-items-start py-2 border-bottom">
                                        <span class="text-muted me-3" style="width:20px; text-align:center;">
                                            <i class="fas <?= $ico ?> fa-sm"></i>
                                        </span>
                                        <span class="text-muted me-2" style="min-width:110px;"><?= $label ?>:</span>
                                        <span class="fw-medium"><?= $val ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conductor asignado -->
                    <?php if ($vehiculo['conductor_nombre']): ?>
                    <div class="card shadow-sm border-0 border-start border-4 border-success mb-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:52px; height:52px; flex-shrink:0;">
                                <i class="fas fa-user-tie fa-lg text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold"><?= esc($vehiculo['conductor_nombre']) ?></div>
                                <small class="text-muted">
                                    DNI: <?= esc($vehiculo['conductor_dni']) ?>
                                    &nbsp;·&nbsp;
                                    Ingreso: <?= date('d/m/Y', strtotime($vehiculo['conductor_fecha_ingreso'])) ?>
                                </small>
                            </div>
                            <span class="badge bg-success px-3 py-2">Conductor activo</span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning d-flex align-items-center gap-3 border-0 shadow-sm mb-4">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                        <div>
                            <strong>Sin conductor asignado.</strong>
                            Puedes asignar uno desde
                            <a href="<?= base_url('vehiculos/edit/' . $vehiculo['id']) ?>">Editar vehículo</a>.
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Historial de estados -->
                    <?php if (!empty($historial_estados)): ?>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-history text-primary me-2"></i>Historial de Estados
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($historial_estados as $h): ?>
                                <li class="list-group-item d-flex align-items-center gap-3 py-3 px-4">
                                    <div class="flex-shrink-0">
                                        <?= vehiculo_estado_badge($h['estado']) ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium text-dark"><?= esc($h['motivo'] ?: '—') ?></div>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i><?= esc($h['usuario_nombre']) ?>
                                        </small>
                                    </div>
                                    <small class="text-muted text-nowrap">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($h['fecha_inicio'])) ?>
                                    </small>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                </div><!-- /col-xl-8 -->

                <!-- Columna derecha (sidebar) -->
                <div class="col-xl-4">

                    <!-- Acciones -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-bolt text-primary me-2"></i>Acciones
                            </h6>
                        </div>
                        <div class="card-body d-grid gap-2">
                            <a href="<?= base_url('vehiculos/edit/' . $vehiculo['id']) ?>"
                               class="btn btn-primary d-flex align-items-center gap-2">
                                <i class="fas fa-edit"></i><span>Editar vehículo</span>
                            </a>
                            <a href="<?= base_url('vehiculos/documentos/' . $vehiculo['id']) ?>"
                               class="btn btn-outline-info d-flex align-items-center gap-2">
                                <i class="fas fa-file-alt"></i><span>Gestionar documentos</span>
                            </a>
                            <hr class="my-1">
                            <?php if ($vehiculo['estado'] === 'ACTIVO'): ?>
                            <button class="btn btn-outline-warning d-flex align-items-center gap-2 cambiar-estado" data-estado="INACTIVO">
                                <i class="fas fa-pause-circle"></i><span>Inactivar vehículo</span>
                            </button>
                            <button class="btn btn-outline-secondary d-flex align-items-center gap-2 cambiar-estado" data-estado="EN REPARACION">
                                <i class="fas fa-tools"></i><span>Enviar a reparación</span>
                            </button>
                            <?php elseif ($vehiculo['estado'] === 'EN REPARACION'): ?>
                            <button class="btn btn-outline-success d-flex align-items-center gap-2 cambiar-estado" data-estado="ACTIVO">
                                <i class="fas fa-check-circle"></i><span>Marcar como activo</span>
                            </button>
                            <button class="btn btn-outline-warning d-flex align-items-center gap-2 cambiar-estado" data-estado="INACTIVO">
                                <i class="fas fa-pause-circle"></i><span>Inactivar vehículo</span>
                            </button>
                            <?php else: ?>
                            <button class="btn btn-outline-success d-flex align-items-center gap-2 cambiar-estado" data-estado="ACTIVO">
                                <i class="fas fa-check-circle"></i><span>Activar vehículo</span>
                            </button>
                            <?php endif; ?>
                            <hr class="my-1">
                            <?php if (!$solicitud_activa): ?>
                            <button class="btn btn-outline-danger d-flex align-items-center gap-2" id="btnSolicitudMantenimiento">
                                <i class="fas fa-wrench"></i><span>Solicitar mantenimiento</span>
                            </button>
                            <?php else: ?>
                            <a href="<?= base_url('vehiculos/verSolicitudMantenimiento/' . $vehiculo['id']) ?>"
                               class="btn btn-warning d-flex align-items-center gap-2">
                                <i class="fas fa-wrench"></i><span>Ver solicitud activa</span>
                                <span class="badge bg-danger ms-auto">1</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Auditoría -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-info-circle text-secondary me-2"></i>Registro
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between py-2 px-3">
                                    <small class="text-muted">Creado por</small>
                                    <small class="fw-medium"><?= esc($vehiculo['usuario_crea_nombre'] ?? '—') ?></small>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-2 px-3">
                                    <small class="text-muted">Fecha registro</small>
                                    <small class="fw-medium"><?= date('d/m/Y H:i', strtotime($vehiculo['fechaRegistro'])) ?></small>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-2 px-3">
                                    <small class="text-muted">Última edición</small>
                                    <small class="fw-medium"><?= date('d/m/Y H:i', strtotime($vehiculo['fechaUpdate'])) ?></small>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-2 px-3">
                                    <small class="text-muted">Editado por</small>
                                    <small class="fw-medium"><?= esc($vehiculo['usuario_edita_nombre'] ?? '—') ?></small>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div><!-- /col-xl-4 -->
            </div><!-- /row -->
        </div><!-- /pane-info -->

        <!-- ════════════════════════════════════════════════════════════
             TAB 2: REGISTROS DE COMBUSTIBLE
        ═════════════════════════════════════════════════════════════ -->
        <div class="tab-pane fade" id="pane-combustible" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-gas-pump text-success me-2"></i>Registros de Combustible
                        <span class="badge bg-light text-secondary ms-2 d-none" id="badgePeriodoActivo"></span>
                    </h6>
                    <a href="<?= base_url('registro-combustible?vehiculo=' . $vehiculo['id']) ?>" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-list me-1"></i>Ver todos
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($registros_combustible)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th class="text-end">Km Ant.</th>
                                    <th class="text-end">Km Act.</th>
                                    <th class="text-end">Recorrido</th>
                                    <th class="text-end">Galones</th>
                                    <th class="text-end">Rend.</th>
                                    <th class="text-end">Monto USD</th>
                                    <th>Usuario</th>
                                    <th>SAG</th>
                                </tr>
                            </thead>
                            <tbody id="combustible-tbody">
                                <?php foreach ($registros_combustible as $rc): ?>
                                <tr>
                                    <td>
                                        <div><?= date('d/m/Y', strtotime($rc['fecha_registro'])) ?></div>
                                        <small class="text-muted"><?= date('H:i', strtotime($rc['fecha_registro'])) ?></small>
                                    </td>
                                    <td>
                                        <?php $tipo = strtoupper($rc['tipo'] ?? 'CONSUMO'); ?>
                                        <span class="badge bg-<?= $tipo === 'VENTA' ? 'info' : 'success' ?>"><?= $tipo ?></span>
                                    </td>
                                    <?php $kmAnt = (float)($rc['kilometraje_anterior'] ?? 0); $kmAct = (float)($rc['kilometraje_actual'] ?? 0); $recorrido = $kmAct - $kmAnt; $galones = (float)($rc['cantidad_litros'] ?? 0) / 3.78541; $rend = $recorrido > 0 && $galones > 0 ? $recorrido / $galones : 0; ?>
                                    <td class="text-end"><?= number_format($kmAnt, 0) ?></td>
                                    <td class="text-end"><?= number_format($kmAct, 0) ?></td>
                                    <td class="text-end fw-medium"><?= $recorrido > 0 ? number_format($recorrido, 0) : '—' ?></td>
                                    <td class="text-end fw-medium"><?= number_format($galones, 2) ?></td>
                                    <td class="text-end"><?= $rend > 0 ? number_format($rend, 2) . ' KM/Gal' : '—' ?></td>
                                    <td class="text-end"><?= !empty($rc['monto_usd']) ? '$' . number_format((float)$rc['monto_usd'], 2) : '—' ?></td>
                                    <td><small><?= esc($rc['usuario_crea'] ?? '—') ?></small></td>
                                    <td>
                                        <?php if (($rc['enviado'] ?? 0) == 1): ?>
                                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                        <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5 text-muted" id="combustible-empty">
                        <i class="fas fa-gas-pump fa-3x mb-3 opacity-25"></i>
                        <p>No hay registros de combustible para este vehículo.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div><!-- /pane-combustible -->

        <!-- ════════════════════════════════════════════════════════════
             TAB 3: DOCUMENTOS
        ═════════════════════════════════════════════════════════════ -->
        <div class="tab-pane fade" id="pane-documentos" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-file-alt text-info me-2"></i>Documentos del Vehículo
                    </h6>
                    <a href="<?= base_url('vehiculos/documentos/' . $vehiculo['id']) ?>" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-cog me-1"></i>Gestionar
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($documentos)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Documento</th>
                                    <th>Número</th>
                                    <th>Vence</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documentos as $doc):
                                    $fechaVenc = $doc['fecha_vencimiento'] ?? null;
                                    $estadoDoc = 'success';
                                    $diasRest = null;
                                    if (!empty($fechaVenc)) {
                                        $diff = (strtotime($fechaVenc) - time()) / 86400;
                                        $diasRest = (int)$diff;
                                        if ($diasRest < 0) $estadoDoc = 'danger';
                                        elseif ($diasRest <= 30) $estadoDoc = 'warning';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-file-alt text-<?= $estadoDoc ?> me-2"></i>
                                        <?= esc($doc['nombre_tipo_documento'] ?? 'Documento') ?>
                                    </td>
                                    <td><?= esc($doc['numero'] ?? $doc['numero_documento'] ?? '—') ?></td>
                                    <td>
                                        <?php if (!empty($fechaVenc)): ?>
                                            <?= date('d/m/Y', strtotime($fechaVenc)) ?>
                                            <?php if ($diasRest !== null): ?>
                                                <small class="text-<?= $estadoDoc ?>">
                                                    (<?= $diasRest >= 0 ? $diasRest . ' días' : 'vencido' ?>)
                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $estadoDoc ?>">
                                            <?php
                                            echo $estadoDoc === 'danger' ? 'Vencido'
                                                : ($estadoDoc === 'warning' ? 'Por vencer'
                                                : 'Vigente');
                                            ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <?php if (!empty($doc['ruta_archivo'])): ?>
                                        <a href="<?= site_url('vehiculos/verDocumento/' . $doc['id']) ?>" class="btn btn-sm btn-outline-primary" target="_blank" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-file-alt fa-3x mb-3 opacity-25"></i>
                        <p>No hay documentos registrados para este vehículo.</p>
                        <a href="<?= base_url('vehiculos/documentos/' . $vehiculo['id']) ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-upload me-1"></i>Subir primer documento
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div><!-- /pane-documentos -->

        <!-- ════════════════════════════════════════════════════════════
             TAB 4: ESTADÍSTICAS
        ═════════════════════════════════════════════════════════════ -->
        <div class="tab-pane fade" id="pane-estadisticas" role="tabpanel">

            <!-- Rendimiento del vehículo -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i>Métricas de Rendimiento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm rounded-4 mx-auto mb-2 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                                        <i class="mdi mdi-counter fs-5 text-white"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" id="stat_km_inicial"><?= number_format($stats_rendimiento['km_inicial_global'] ?? 0, 0) ?></h4>
                                    <small class="text-muted">KM Inicial</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm rounded-4 mx-auto mb-2 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #198754, #157347);">
                                        <i class="mdi mdi-speedometer fs-5 text-white"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" id="stat_km_actual"><?= number_format($stats_rendimiento['km_final_global'] ?? 0, 0) ?></h4>
                                    <small class="text-muted">KM Actual</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm rounded-4 mx-auto mb-2 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                                        <i class="mdi mdi-road-variant fs-5 text-white"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" id="stat_km_global"><?= number_format($stats_rendimiento['km_recorrido_global'] ?? 0, 0) ?></h4>
                                    <small class="text-muted">KM Recorrido Lineal</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm rounded-4 mx-auto mb-2 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0dcaf0, #0aa8c4);">
                                        <i class="mdi mdi-map-marker-distance fs-5 text-white"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" id="stat_km_tramos"><?= number_format($stats_rendimiento['km_recorrido_tramos'] ?? 0, 0) ?></h4>
                                    <small class="text-muted">KM por Tramo</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
<?php $totalGalonesConsumidos = (float)($stats_rendimiento['total_galones_consumidos'] ?? 0); ?>
                                    <div class="avatar-sm rounded-4 mx-auto mb-2 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #fd7e14, #dc6504);">
                                        <i class="mdi mdi-fuel fs-5 text-white"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" id="stat_consumo_general"><?= number_format($totalGalonesConsumidos, 2) ?></h4>
                                    <small class="text-muted">Consumo General (Gal)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm rounded-4 mx-auto mb-2 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #20c997, #17a988);">
                                        <i class="mdi mdi-target fs-5 text-white"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" id="stat_rend_teorico"><?= number_format($stats_rendimiento['rendimiento_teorico'] ?? 0, 2) ?></h4>
                                    <small class="text-muted">Rendimiento Teórico (KM/Gal)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm rounded-4 mx-auto mb-2 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #d63384, #b02a6f);">
                                        <i class="mdi mdi-chart-line fs-5 text-white"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" id="stat_rend_promedio"><?= number_format($stats_rendimiento['rendimiento_promedio_registrado'] ?? 0, 2) ?></h4>
                                    <small class="text-muted">Rendimiento Promedio (KM/Gal)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($stats_rendimiento['diferencia_descuadre'])): ?>
                    <div class="alert alert-warning border-0 mt-3 mb-0 py-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Descuadre entre KM lineal y por tramos: <strong><?= number_format(abs($stats_rendimiento['diferencia_descuadre']), 0) ?> km</strong>
                        <span class="text-muted ms-2">(<?= $stats_rendimiento['diferencia_descuadre'] > 0 ? 'lineal mayor' : 'tramos mayor' ?>)</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Stats combustible mes actual -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 text-center py-3">
                        <div class="card-body p-2">
                            <i class="fas fa-gas-pump fa-2x text-success mb-2"></i>
<?php
$totalGalonesMes = (float)($stats_combustible_mes['total_litros'] ?? 0) / 3.78541;
?>
                            <h4 class="fw-bold mb-0" id="stat_mes_galones"><?= number_format($totalGalonesMes, 1) ?></h4>
                            <small class="text-muted" id="stat_mes_galones_label">Galones (mes)</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 text-center py-3">
                        <div class="card-body p-2">
                            <i class="fas fa-database fa-2x text-info mb-2"></i>
                            <h4 class="fw-bold mb-0" id="stat_mes_cargas"><?= (int)($stats_combustible_mes['total_registros'] ?? 0) ?></h4>
                            <small class="text-muted" id="stat_mes_cargas_label">Cargas (mes)</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 text-center py-3">
                        <div class="card-body p-2">
                            <i class="fas fa-dollar-sign fa-2x text-warning mb-2"></i>
                            <h4 class="fw-bold mb-0" id="stat_mes_costo">$<?= number_format((float)($stats_combustible_mes['total_monto_usd'] ?? 0), 2) ?></h4>
                            <small class="text-muted" id="stat_mes_costo_label">Costo (mes)</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 text-center py-3">
                        <div class="card-body p-2">
                            <i class="fas fa-road fa-2x text-primary mb-2"></i>
                            <h4 class="fw-bold mb-0" id="stat_mes_km"><?= number_format((float)($stats_combustible_mes['total_kilometros'] ?? 0), 0) ?></h4>
                            <small class="text-muted" id="stat_mes_km_label">Km (mes)</small>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /pane-estadisticas -->

    </div><!-- /tab-content -->
</div>

<!-- ── Botón flotante de filtro ────────────────── -->
<button class="btn btn-primary shadow-sm position-fixed end-0 rounded-circle"
        id="btnFiltroCombustible"
        style="width:56px; height:56px; z-index:1050; bottom:80px; margin-right:24px;"
        title="Filtrar por período">
    <i class="fas fa-filter fa-lg"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge bg-danger d-none" id="filtroActivoBadge">!</span>
</button>

<!-- ── Modal: Filtrar Combustible ─────────────────── -->
<div class="modal fade" id="modalFiltroCombustible" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-filter text-primary me-2"></i>Filtrar por Período
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formFiltroCombustible">
                <div class="modal-body pt-2">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha Desde</label>
                            <input type="date" class="form-control" name="fecha_desde" id="filtro_fecha_desde">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha Hasta</label>
                            <input type="date" class="form-control" name="fecha_hasta" id="filtro_fecha_hasta">
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-secondary btn-sm filtro-rapido" data-dias="7">Últimos 7 días</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm filtro-rapido" data-dias="30">Últimos 30 días</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm filtro-rapido" data-dias="90">Últimos 3 meses</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm filtro-rapido" data-dias="180">Últimos 6 meses</button>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="btnQuitarFiltro">Quitar filtro</button>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-search me-1"></i>Aplicar Filtro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Modal: Cambiar Estado ─────────────────────── -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-exchange-alt text-primary me-2"></i>Cambiar Estado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body pt-2">
                    <input type="hidden" id="vehiculo_id" name="id" value="<?= $vehiculo['id'] ?>">
                    <input type="hidden" id="nuevo_estado" name="estado">

                    <div class="alert alert-light border mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        <span id="mensaje_cambio_estado"></span>
                    </div>

                    <div class="mb-0">
                        <label for="motivo_cambio" class="form-label fw-medium">
                            Motivo <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <textarea class="form-control" id="motivo_cambio" name="motivo" rows="3"
                                  placeholder="Describe el motivo del cambio de estado…"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-check me-1"></i>Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Modal: Solicitar Mantenimiento ────────────── -->
<div class="modal fade" id="modalSolicitudMantenimiento" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-wrench text-danger me-2"></i>Solicitar Mantenimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSolicitudMantenimiento">
                <div class="modal-body pt-2">
                    <input type="hidden" name="id_vehiculo" value="<?= $vehiculo['id'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipo de Mantenimiento</label>
                            <select class="form-select" name="tipo_mantenimiento" required>
                                <?php foreach ($tiposMantenimiento ?? [] as $tm): ?>
                                    <option value="<?= esc(strtoupper($tm['nombre'])) ?>" <?= strtoupper($tm['nombre']) === 'CORRECTIVO' ? 'selected' : '' ?>>
                                        <?= esc(ucwords(strtolower($tm['nombre']))) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Prioridad</label>
                            <select class="form-select" name="prioridad" required>
                                <option value="BAJA">Baja</option>
                                <option value="MEDIA" selected>Media</option>
                                <option value="ALTA">Alta</option>
                                <option value="CRITICA">Crítica</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Descripción del Problema</label>
                            <textarea class="form-control" name="descripcion" rows="4" required
                                      placeholder="Describe detalladamente el problema o mantenimiento requerido…"></textarea>
                        </div>
                    </div>

                    <div class="alert alert-warning d-flex gap-2 align-items-start mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
                        <small>La solicitud será revisada por el equipo de mantenimiento. Recibirás notificaciones sobre su estado.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-paper-plane me-1"></i>Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Command palette de acciones del vehículo -->
<div class="cmd-palette-overlay" id="cmdPaletteVehiculo" onclick="cerrarCommandPaletteVehiculo(event)" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.45);backdrop-filter:blur(4px);z-index:1055;align-items:flex-start;justify-content:center;padding-top:10vh;">
    <div class="cmd-palette" onclick="event.stopPropagation()" style="width:100%;max-width:560px;background:#fff;border-radius:18px;box-shadow:0 24px 60px rgba(0,0,0,0.25);overflow:hidden;">
        <div class="cmd-palette-header" style="display:flex;align-items:center;gap:0.75rem;padding:1rem 1.25rem;border-bottom:1px solid #e2e8f0;">
            <i class="fas fa-search text-muted"></i>
            <input type="text" id="cmdInputVehiculo" placeholder="Buscar acción..." autocomplete="off" style="border:none;outline:none;flex:1;font-size:1rem;">
            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.75rem;color:#94a3b8;border:1px solid #e2e8f0;border-radius:6px;padding:0.15rem 0.4rem;">ESC</span>
        </div>
        <div class="cmd-palette-list" id="cmdListVehiculo" style="max-height:320px;overflow-y:auto;"></div>
    </div>
</div>

<?= $this->endSection() ?>

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
#chartPeriodoToggle .btn.active {
    background: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js"></script>
<script src="<?= base_url('public/assets/js/modules/Vehiculos.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    VehiculosShow.init(<?= $vehiculo['id'] ?>, '<?= base_url() ?>');

    const vehiculoId = <?= $vehiculo['id'] ?>;
    const baseUrl = '<?= base_url() ?>';
    const csrfName = '<?= csrf_token() ?>';
    const csrfHash = '<?= csrf_hash() ?>';
    let filtroModal = null;
    let periodoActivo = false;

    // ── Chart Data ──────────────────────────────────
    const chartData = {
        labels: <?= json_encode(array_map(function($r) { return date('d/m/Y', strtotime($r['fecha_registro'])); }, array_slice(array_reverse($registros_combustible), 0, 30))) ?>,
        galones: <?= json_encode(array_map(function($r) { return round((float)($r['cantidad_litros'] ?? 0) / 3.78541, 2); }, array_slice(array_reverse($registros_combustible), 0, 30))) ?>,
        rendimiento: <?= json_encode(array_map(function($r) { $kmA = (float)($r['kilometraje_actual'] ?? 0); $kmB = (float)($r['kilometraje_anterior'] ?? 0); $g = (float)($r['cantidad_litros'] ?? 0) / 3.78541; return ($g > 0 && $kmA > $kmB) ? round(($kmA - $kmB) / $g, 2) : 0; }, array_slice(array_reverse($registros_combustible), 0, 30))) ?>,
        rendTeorico: <?= json_encode((float)($stats_rendimiento['rendimiento_teorico'] ?? 0)) ?>,
    };

    // ── ApexCharts Init ─────────────────────────────
    let chart = null;
    function initChart() {
        const el = document.querySelector('#chartConsumoRendimiento');
        if (!el || typeof ApexCharts === 'undefined') return;

        const options = {
            series: [
                {
                    name: 'Galones Consumidos',
                    type: 'column',
                    data: chartData.galones
                },
                {
                    name: 'Rendimiento Real (KM/Gal)',
                    type: 'line',
                    data: chartData.rendimiento
                },
                {
                    name: 'Rendimiento Teórico',
                    type: 'line',
                    data: chartData.galones.map(() => chartData.rendTeorico),
                    dashArray: 5
                }
            ],
            chart: {
                height: 320,
                type: 'line',
                toolbar: { show: false },
                fontFamily: 'inherit',
                animations: { enabled: true, speed: 400 }
            },
            colors: ['#fd7e14', '#0d6efd', '#20c997'],
            stroke: { width: [0, 3, 2], curve: 'smooth' },
            plotOptions: {
                bar: { columnWidth: '50%', borderRadius: 4 }
            },
            fill: {
                type: ['solid', 'solid', 'solid'],
                opacity: [0.85, 1, 1]
            },
            labels: chartData.labels,
            markers: { size: [0, 4, 0] },
            xaxis: {
                tickAmount: Math.min(chartData.labels.length, 10),
                labels: { rotate: -45, style: { fontSize: '11px' } }
            },
            yaxis: [
                {
                    title: { text: 'Galones', style: { fontSize: '12px', fontWeight: 600 } },
                    labels: { formatter: v => Number(v).toLocaleString('en-US', {maximumFractionDigits: 1}) }
                },
                {
                    opposite: true,
                    title: { text: 'KM/Gal', style: { fontSize: '12px', fontWeight: 600 } },
                    labels: { formatter: v => Number(v).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }
                }
            ],
            legend: { position: 'top', fontSize: '12px' },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(val, opts) {
                        if (opts.seriesIndex === 0) return val + ' Gal';
                        return val + ' KM/Gal';
                    }
                }
            },
            grid: { borderColor: '#e9ecef', strokeDashArray: 4 }
        };

        chart = new ApexCharts(el, options);
        chart.render();
    }

    initChart();

    // Retry si ApexCharts aún no cargó del CDN
    if (!chart) {
        let retries = 0;
        const retryInterval = setInterval(function() {
            retries++;
            if (typeof ApexCharts !== 'undefined' && !chart) {
                initChart();
            }
            if (chart || retries > 10) clearInterval(retryInterval);
        }, 300);
    }

    // ── Periodo Toggle ──────────────────────────────
    document.querySelectorAll('#chartPeriodoToggle button').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#chartPeriodoToggle button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const periodo = this.dataset.periodo;
            let desde = '', hasta = new Date().toISOString().split('T')[0];
            if (periodo === 'hoy') desde = hasta;
            else if (periodo === 'semana') { const d = new Date(); d.setDate(d.getDate() - 7); desde = d.toISOString().split('T')[0]; }
            else if (periodo === 'mes') { desde = new Date().toISOString().slice(0,8) + '01'; }
            else if (periodo === 'historico') desde = '';
            aplicarFiltro(desde, hasta);
        });
    });

    // Inicializar modal
    const modalEl = document.getElementById('modalFiltroCombustible');
    if (modalEl) filtroModal = new bootstrap.Modal(modalEl);

    // Botón flotante abre modal
    const btnFiltro = document.getElementById('btnFiltroCombustible');
    if (btnFiltro) {
        btnFiltro.addEventListener('click', function() { filtroModal.show(); });
    }

    // Filtros rápidos
    document.querySelectorAll('.filtro-rapido').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const dias = parseInt(this.dataset.dias);
            const hasta = new Date();
            const desde = new Date();
            desde.setDate(hasta.getDate() - dias);
            document.getElementById('filtro_fecha_desde').value = desde.toISOString().split('T')[0];
            document.getElementById('filtro_fecha_hasta').value = hasta.toISOString().split('T')[0];
        });
    });

    // Quitar filtro
    const btnQuitar = document.getElementById('btnQuitarFiltro');
    if (btnQuitar) {
        btnQuitar.addEventListener('click', function() {
            document.getElementById('filtro_fecha_desde').value = '';
            document.getElementById('filtro_fecha_hasta').value = '';
            aplicarFiltro('', '');
            filtroModal.hide();
        });
    }

    // Submit form
    const formFiltro = document.getElementById('formFiltroCombustible');
    if (formFiltro) {
        formFiltro.addEventListener('submit', function(e) {
            e.preventDefault();
            const desde = document.getElementById('filtro_fecha_desde').value;
            const hasta = document.getElementById('filtro_fecha_hasta').value;
            aplicarFiltro(desde, hasta);
            filtroModal.hide();
        });
    }

    function aplicarFiltro(desde, hasta) {
        const body = {};
        body[csrfName] = csrfHash;
        if (desde) body.fecha_desde = desde;
        if (hasta) body.fecha_hasta = hasta;

        fetch(baseUrl + 'vehiculos/filtrarCombustible/' + vehiculoId, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            body: JSON.stringify(body)
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;

            // Actualizar tabla de combustible
            const tbody = document.getElementById('combustible-tbody');
            if (tbody) tbody.innerHTML = data.html_registros;

            // Mostrar/ocultar empty state
            const emptyEl = document.getElementById('combustible-empty');
            if (emptyEl) emptyEl.style.display = data.count > 0 ? 'none' : '';

            // Actualizar Bento KPIs
            setText('bento_galones', data.stats.total_galones);
            setText('bento_cargas', data.stats.total_registros);
            setText('bento_costo', '$' + data.stats.total_monto_usd);
            setText('bento_km_actual', data.stats.total_kilometros);

            // Actualizar KPIs de rendimiento (tab estadísticas)
            setText('stat_km_inicial', data.rendimiento.km_inicial_global ?? 0);
            setText('stat_km_actual', data.rendimiento.km_final_global ?? 0);
            setText('stat_km_global', data.rendimiento.km_recorrido_global ?? 0);
            setText('stat_km_tramos', data.rendimiento.km_recorrido_tramos ?? 0);
            setText('stat_consumo_general', data.rendimiento.total_galones_consumidos ?? 0);
            setText('stat_rend_teorico', data.rendimiento.rendimiento_teorico ?? 0);
            setText('stat_rend_promedio', data.rendimiento.rendimiento_promedio_registrado ?? 0);

            // Actualizar stats del periodo
            setText('stat_mes_galones', data.stats.total_galones);
            setText('stat_mes_cargas', data.stats.total_registros);
            setText('stat_mes_costo', '$' + data.stats.total_monto_usd);
            setText('stat_mes_km', data.stats.total_kilometros);

            // Actualizar stats 6 meses
            setText('stat_6m_galones', data.stats.total_galones);
            setText('stat_6m_cargas', data.stats.total_registros);
            setText('stat_6m_costo', '$' + data.stats.total_monto_usd);

            // Actualizar chart si hay datos
            if (chart && data.chart_data) {
                chart.updateOptions({
                    series: [
                        { name: 'Galones Consumidos', type: 'column', data: data.chart_data.galones },
                        { name: 'Rendimiento Real (KM/Gal)', type: 'line', data: data.chart_data.rendimiento },
                        { name: 'Rendimiento Teórico', type: 'line', data: data.chart_data.galones.map(() => data.chart_data.rend_teorico), dashArray: 5 }
                    ],
                    labels: data.chart_data.labels
                });
            }

            // Labels dinámicos
            const tieneFiltro = desde || hasta;
            if (tieneFiltro) {
                periodoActivo = true;
                document.getElementById('filtroActivoBadge').classList.remove('d-none');
                const badge = document.getElementById('badgePeriodoActivo');
                if (badge) {
                    badge.classList.remove('d-none');
                    badge.textContent = desde + ' a ' + hasta;
                }
                setText('stat_mes_galones_label', 'Galones (período)');
                setText('stat_mes_cargas_label', 'Cargas (período)');
                setText('stat_mes_costo_label', 'Costo (período)');
                setText('stat_mes_km_label', 'Km (período)');
                setText('stat_6m_label', 'Total Galones (período)');
                setText('stat_6m_cargas_label', 'Total Cargas (período)');
                setText('stat_6m_costo_label', 'Total Costo USD (período)');
            } else {
                periodoActivo = false;
                document.getElementById('filtroActivoBadge').classList.add('d-none');
                const badge = document.getElementById('badgePeriodoActivo');
                if (badge) badge.classList.add('d-none');
                setText('stat_mes_galones_label', 'Galones (mes)');
                setText('stat_mes_cargas_label', 'Cargas (mes)');
                setText('stat_mes_costo_label', 'Costo (mes)');
                setText('stat_mes_km_label', 'Km (mes)');
                setText('stat_6m_label', 'Total Galones (6 meses)');
                setText('stat_6m_cargas_label', 'Total Cargas (6 meses)');
                setText('stat_6m_costo_label', 'Total Costo USD (6 meses)');
            }
        })
        .catch(err => console.error('Error filtrando:', err));
    }

    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    // Command palette de acciones del vehículo
    const comandosVehiculo = [
        { label: 'Volver al listado', url: '<?= base_url('vehiculos') ?>', icon: 'fa-arrow-left' },
        { label: 'Editar vehículo', url: '<?= base_url('vehiculos/edit/' . $vehiculo['id']) ?>', icon: 'fa-edit' },
        { label: 'Documentos del vehículo', url: '<?= base_url('vehiculos/documentos/' . $vehiculo['id']) ?>', icon: 'fa-file-alt' },
        { label: 'Reportar solicitud de mantenimiento', url: '<?= base_url('solicitudes/create') ?>', icon: 'fa-wrench' },
        { label: 'Ver reportes de vehículos', url: '<?= base_url('vehiculos/reportes') ?>', icon: 'fa-chart-line' }
    ];
    let selectedCmdIndex = -1;

    function abrirCommandPaletteVehiculo() {
        document.getElementById('cmdPaletteVehiculo').style.display = 'flex';
        document.getElementById('cmdInputVehiculo').value = '';
        document.getElementById('cmdInputVehiculo').focus();
        selectedCmdIndex = -1;
        renderComandosVehiculo();
    }

    function cerrarCommandPaletteVehiculo() {
        document.getElementById('cmdPaletteVehiculo').style.display = 'none';
    }

    function renderComandosVehiculo(filter = '') {
        const list = document.getElementById('cmdListVehiculo');
        list.innerHTML = '';
        const filtered = comandosVehiculo.filter(c => c.label.toLowerCase().includes(filter.toLowerCase()));
        filtered.forEach((cmd, i) => {
            const div = document.createElement('div');
            div.className = 'cmd-item-vehiculo' + (i === selectedCmdIndex ? ' active' : '');
            div.dataset.url = cmd.url;
            div.style.cssText = 'display:flex;align-items:center;gap:0.75rem;padding:0.85rem 1.25rem;cursor:pointer;border-bottom:1px solid #f1f5f9;transition:background 0.1s;';
            div.innerHTML = '<i class="fas ' + cmd.icon + '" style="width:24px;text-align:center;color:#4f46e5;"></i><span>' + cmd.label + '</span>';
            div.onclick = function() { window.location.href = cmd.url; };
            div.onmouseenter = function() { selectedCmdIndex = i; updateActiveCmd(); };
            list.appendChild(div);
        });
    }

    function updateActiveCmd() {
        document.querySelectorAll('.cmd-item-vehiculo').forEach((el, i) => {
            el.style.background = i === selectedCmdIndex ? '#eef2ff' : '';
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            abrirCommandPaletteVehiculo();
            return;
        }
        if (document.getElementById('cmdPaletteVehiculo').style.display === 'none') return;

        const items = document.querySelectorAll('.cmd-item-vehiculo');
        if (e.key === 'Escape') cerrarCommandPaletteVehiculo();
        else if (e.key === 'ArrowDown') { e.preventDefault(); selectedCmdIndex = Math.min(selectedCmdIndex + 1, items.length - 1); updateActiveCmd(); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); selectedCmdIndex = Math.max(selectedCmdIndex - 1, 0); updateActiveCmd(); }
        else if (e.key === 'Enter' && selectedCmdIndex >= 0 && items[selectedCmdIndex]) window.location.href = items[selectedCmdIndex].dataset.url;
    });

    const cmdInputVehiculo = document.getElementById('cmdInputVehiculo');
    if (cmdInputVehiculo) {
        cmdInputVehiculo.addEventListener('input', function() {
            selectedCmdIndex = -1;
            renderComandosVehiculo(this.value);
        });
    }
});
</script>
<?= $this->endSection() ?>
