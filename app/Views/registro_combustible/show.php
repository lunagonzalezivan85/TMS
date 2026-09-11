<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <?php
        $esVenta = strtoupper($registro['tipo'] ?? 'CONSUMO') === 'VENTA';
        $voucherUrl = $esVenta
            ? base_url('registro-combustible/voucher/venta/' . $registro['id'])
            : base_url('registro-combustible/voucher/consumo/' . $registro['id']);
        $esBloqueado = strtoupper($registro['estado'] ?? '') === 'BLOQUEADO';
        $kmRecorridos = (float)$registro['kilometraje_actual'] - (float)$registro['kilometraje_anterior'];
        $L_TO_GAL = 0.264172;
        $galDesp  = (float)$registro['cantidad_litros'] * $L_TO_GAL;
        $rendCalc = (float)($registro['rendimiento'] ?? 0);
        $rendProm = (float)($registro['rendimiento_promedio'] ?? 0);
        $galTeor  = $rendProm > 0 ? ($kmRecorridos / $rendProm) : 0;
        $diffGal  = $galTeor - $galDesp  ;
    ?>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">
                <i class="fas fa-gas-pump text-primary me-2"></i>Registro de Combustible #<?= esc($registro['id']) ?>
            </h1>
            <p class="text-muted mb-0"><?= date('d/m/Y', strtotime($registro['fecha_registro'])) ?></p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <a href="<?= $voucherUrl ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-print me-1"></i>Voucher
            </a>
            <a href="<?= base_url('registro-combustible/edit/' . $registro['id']) ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i>Editar
            </a>
            <a href="<?= base_url('registro-combustible') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <!-- Información del Registro -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Información del Registro</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Vehículo</label>
                    <div class="fw-bold"><?= esc($registro['placa']) ?> — <?= esc($registro['marca'] . ' ' . $registro['modelo']) ?> (<?= esc($registro['anio']) ?>)</div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="text-muted small">Fecha</label>
                    <div class="fw-bold"><?= date('d/m/Y', strtotime($registro['fecha_registro'])) ?></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="text-muted small">Motivo</label>
                    <div class="fw-bold"><?= esc($registro['motivo']) ?></div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Kilometraje Anterior</label>
                    <div class="fw-bold"><?= number_format($registro['kilometraje_anterior']) ?> km</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Kilometraje Actual</label>
                    <div class="fw-bold text-primary"><?= number_format($registro['kilometraje_actual']) ?> km</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Kilómetros Recorridos</label>
                    <div class="fw-bold"><?= number_format($kmRecorridos) ?> km</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Cantidad Despachada</label>
                    <div class="fw-bold"><?= number_format($registro['cantidad_litros'], 2) ?> L (<?= number_format($galDesp, 2) ?> gal)</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Rendimiento Calculado</label>
                    <div class="fw-bold text-<?= $rendProm > 0 && $rendCalc < $rendProm ? 'danger' : 'success' ?>">
                        <?= $rendCalc > 0 ? number_format($rendCalc, 2) : 'N/A' ?> km/gal
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Promedio Histórico</label>
                    <div class="fw-bold"><?= $rendProm > 0 ? number_format($rendProm, 2) : 'N/A' ?> km/gal</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis de Rendimiento -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2"></i>Análisis de Rendimiento</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="text-muted small">Galones Teóricos</label>
                    <div class="fw-bold"><?= $galTeor > 0 ? number_format($galTeor, 2) : 'N/A' ?> gal</div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="text-muted small">Galones Despachados</label>
                    <div class="fw-bold"><?= number_format($galDesp, 2) ?> gal</div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="text-muted small">Diferencia</label>
                    <div class="fw-bold text-<?= $diffGal >= 0 ? 'success' : 'danger' ?>">
                        <?= ($diffGal >= 0 ? '+' : '') . number_format($diffGal, 2) ?> gal
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="text-muted small">Índice</label>
                    <div class="fw-bold"><?= $rendProm > 0 && $rendCalc > 0 ? number_format(($rendCalc / $rendProm) * 100, 1) : 'N/A' ?>%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Observaciones -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-bold"><i class="fas fa-comment me-2"></i>Observaciones</h6>
        </div>
        <div class="card-body">
            <?php
                $obsTexto = $registro['observaciones'] ?? '';
                $tieneObs = !empty(trim($obsTexto));
            ?>
            <?php if ($tieneObs): ?>
                <?php if ($esBloqueado && (str_starts_with(trim($obsTexto), '[BLOQUEADO') || str_starts_with(trim($obsTexto), '[ALERTA'))): ?>
                    <?php
                        $partes = explode('─────', $obsTexto, 2);
                        $cabecera = trim($partes[0]);
                        $notaOperador = isset($partes[1]) ? trim(ltrim($partes[1], '─')) : '';
                        $notaOperador = preg_replace('/^Observaciones adicionales del operador:\n?/i', '', $notaOperador);
                    ?>
                    <div class="bg-danger bg-opacity-10 p-3 mb-2 rounded">
                        <pre class="mb-0 small" style="white-space:pre-wrap;font-family:inherit;background:none;border:none;padding:0"><?= esc($cabecera) ?></pre>
                    </div>
                    <?php if (!empty(trim($notaOperador))): ?>
                        <div class="p-2 border-top">
                            <strong>Nota del operador:</strong>
                            <p class="mb-0 mt-1"><?= nl2br(esc(trim($notaOperador))) ?></p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="mb-0"><?= nl2br(esc($obsTexto)) ?></p>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-muted fst-italic mb-0">Sin observaciones registradas.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Auditoría -->
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Información de Auditoría</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="text-muted small">Creado por</label>
                    <div class="fw-bold"><?= esc($registro['usuario_crea'] ?? 'N/A') ?></div>
                </div>
                <div class="col-md-4">
                    <label class="text-muted small">Última actualización</label>
                    <div class="fw-bold small">
                        <?= $registro['fecha_actualiza'] ? date('d/m/Y H:i', strtotime($registro['fecha_actualiza'])) : 'N/A' ?>
                    </div>
                </div>
                <?php if ($registro['usuario_edita']): ?>
                <div class="col-md-4">
                    <label class="text-muted small">Editado por</label>
                    <div class="fw-bold"><?= esc($registro['usuario_edita']) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
