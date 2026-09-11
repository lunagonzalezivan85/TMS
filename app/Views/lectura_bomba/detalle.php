<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$r = $reporte;
$cerrado = !empty($r['fecha_cierre']);
$mermas = floatval($r['diferencia_mermas_ltr'] ?? 0);
$diffContador = $r['diferencia_contador_vs_vales_ltr'] !== null ? floatval($r['diferencia_contador_vs_vales_ltr']) : null;
$stockTeorico = floatval($r['stock_teorico_tanque_ltr'] ?? 0);
$despachado = floatval($r['total_despachado_ltr'] ?? 0);
$ingresos = floatval($r['ingresos_tanque_ltr'] ?? 0);
$consumoFisico = $r['consumo_fisico_turno_ltr'] !== null ? floatval($r['consumo_fisico_turno_ltr']) : null;
?>
<style>
/* ── Estilo Reporte Profesional ─────────────────────────── */
.reporte-container {
    max-width: 920px;
    margin: 0 auto;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.reporte-header {
    background-color: #0f172a;
    color: #ffffff;
    padding: 2.5rem 2rem;
    border-bottom: 4px solid #dc2626;
}

.reporte-logo-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
}

.reporte-subtitle {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #94a3b8;
    font-weight: 700;
}

.reporte-meta-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    padding: 2rem;
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.reporte-meta-item {
    display: flex;
    flex-direction: column;
}

.reporte-meta-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 0.25rem;
    letter-spacing: 0.5px;
}

.reporte-meta-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
}

.reporte-seccion-titulo {
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #0f172a;
    letter-spacing: 1px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.reporte-tabla {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
}

.reporte-tabla th {
    background-color: #f1f5f9;
    color: #334155;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    letter-spacing: 0.5px;
}

.reporte-tabla td {
    padding: 0.85rem 1rem;
    border: 1px solid #e2e8f0;
    font-size: 0.9rem;
    color: #334155;
}

.reporte-tabla tr.destacado {
    background-color: #f8fafc;
    font-weight: 700;
}

.reporte-tabla tr.destacado td {
    color: #0f172a;
}

.reporte-tabla tr.subtotal td {
    background-color: #eff6ff;
    font-weight: 600;
    color: #1e40af;
}

.reporte-tabla tr.merma-pos td {
    background-color: #fef2f2;
}

.reporte-tabla tr.merma-neg td {
    background-color: #f0fdf4;
}

.reporte-kpi-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    padding: 2rem;
    border-bottom: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
    .reporte-kpi-row { grid-template-columns: repeat(2, 1fr); }
}

.reporte-kpi-card {
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    padding: 1.25rem;
    text-align: center;
    background-color: #ffffff;
}

.reporte-kpi-card.alert {
    border-left: 4px solid #dc2626;
}

.reporte-kpi-card.info {
    border-left: 4px solid #2563eb;
}

.reporte-kpi-card.success {
    border-left: 4px solid #16a34a;
}

.reporte-kpi-card.warning {
    border-left: 4px solid #d97706;
}

.reporte-kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-top: 0.25rem;
}

.reporte-memo {
    background-color: #fffbeb;
    border: 1px solid #fef3c7;
    border-left: 4px solid #d97706;
    padding: 1.5rem;
    border-radius: 0.375rem;
    margin-bottom: 1.5rem;
}

.reporte-firmas {
    display: none;
    grid-template-columns: repeat(2, 1fr);
    gap: 4rem;
    padding: 4rem 2rem 2.5rem 2rem;
    text-align: center;
}

.reporte-linea-firma {
    border-top: 1px dotted #94a3b8;
    margin-top: 3rem;
    padding-top: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.status-badge.normal {
    background-color: #dcfce7;
    color: #15803d;
}

.status-badge.anomalia {
    background-color: #fee2e2;
    color: #b91c1c;
}

.share-btn {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: white;
    border: none;
    padding: 0.6rem 1.25rem;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.share-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

/* ── Tabla despachos ── */
.tabla-despachos {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
.tabla-despachos th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.72rem;
    padding: 0.6rem 0.75rem;
    border-bottom: 2px solid #e2e8f0;
    letter-spacing: 0.5px;
}
.tabla-despachos td {
    padding: 0.6rem 0.75rem;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}
.tabla-despachos tr:hover td {
    background: #f8fafc;
}

/* ── Estilos de Impresión (A4 Reporte) ───────────────────── */
@media print {
    body * { visibility: hidden; }
    #sidebar-wrapper, .navbar, .btn, .share-btn, .no-print { display: none !important; }
    .reporte-container, .reporte-container * { visibility: visible; }
    .reporte-container {
        position: absolute; left: 0; top: 0;
        width: 100% !important; max-width: 100% !important;
        border: none !important; box-shadow: none !important;
        margin: 0 !important; padding: 0 !important;
    }
    .reporte-header {
        background-color: #0f172a !important; color: #ffffff !important;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .reporte-meta-grid {
        background-color: #f8fafc !important;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .reporte-tabla th {
        background-color: #f1f5f9 !important;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .reporte-memo {
        background-color: #fffbeb !important;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .reporte-firmas { display: grid !important; }
}
</style>

<!-- Page Header (Oculto en Impresión) -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
  <div>
    <h1 class="h4 mb-0"><i class="fas fa-file-contract me-2 text-secondary"></i>Reporte de Conciliación</h1>
    <p class="text-muted small mb-0">Control físico vs despachos — Documento #LB-<?= str_pad($r['id_medicion'], 6, '0', STR_PAD_LEFT) ?></p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= base_url('lectura-bomba/reporte') ?>" class="btn btn-sm btn-outline-primary">
      <i class="fas fa-calendar-alt me-1"></i>Reporte por Fechas
    </a>
    <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
      <i class="fas fa-print me-1"></i>Imprimir
    </button>
    <a href="<?= base_url('lectura-bomba') ?>" class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-arrow-left me-1"></i>Volver
    </a>
  </div>
</div>

<!-- Contenedor del Reporte -->
<div class="reporte-container mb-5">
    
    <!-- Encabezado -->
    <div class="reporte-header">
        <div class="reporte-logo-title">
            <div>
                <div class="reporte-subtitle">Consorcio GMV</div>
                <h3 class="fw-bold mb-0" style="letter-spacing: -0.5px;">
                    <?= $cerrado ? 'REPORTE DE CIERRE Y CONCILIACIÓN' : 'REPORTE DE APERTURA (TURNO ACTIVO)' ?>
                </h3>
            </div>
            <div class="text-end">
                <span class="status-badge <?= esc($r['estado']) ?>">
                    Contador: <?= esc($r['estado']) ?>
                </span>
                <div class="small text-muted mt-2 text-white-50">DOCUMENTO #LB-<?= str_pad($r['id_medicion'], 6, '0', STR_PAD_LEFT) ?></div>
            </div>
        </div>
        <div class="row text-white-50 small">
            <div class="col-6"><strong>EMISOR:</strong> Sistema Automatizado GMV-TMS</div>
            <div class="col-6 text-end"><strong>FECHA DE IMPRESIÓN:</strong> <?= date('d/m/Y H:i') ?></div>
        </div>
    </div>

    <!-- Metadatos -->
    <div class="reporte-meta-grid">
        <div class="reporte-meta-item">
            <span class="reporte-meta-label">Bomba / Centro de Costo</span>
            <span class="reporte-meta-value"><?= esc($r['nombre_centro_costo'] ?? $r['id_centro_costo']) ?></span>
        </div>
        <div class="reporte-meta-item">
            <span class="reporte-meta-label">Operador Responsable</span>
            <span class="reporte-meta-value"><?= esc($r['usuario_apertura']) ?></span>
        </div>
        <div class="reporte-meta-item mt-2">
            <span class="reporte-meta-label">Inicio del Turno (Apertura)</span>
            <span class="reporte-meta-value"><i class="far fa-calendar-alt me-1 text-muted"></i> <?= date('d/m/Y H:i', strtotime($r['fecha_apertura'])) ?></span>
        </div>
        <div class="reporte-meta-item mt-2">
            <span class="reporte-meta-label">Fin del Turno (Cierre)</span>
            <span class="reporte-meta-value">
                <?php if ($cerrado): ?>
                    <i class="far fa-calendar-check me-1 text-muted"></i> <?= date('d/m/Y H:i', strtotime($r['fecha_cierre'])) ?>
                <?php else: ?>
                    <span class="text-danger fw-bold"><i class="fas fa-spinner fa-spin me-1"></i> TURNO ACTIVO / ABIERTO</span>
                <?php endif; ?>
            </span>
        </div>
    </div>

    <!-- KPIs -->
    <div class="reporte-kpi-row bg-white">
        <div class="reporte-kpi-card info">
            <div class="reporte-meta-label text-primary">Despachado (Vales)</div>
            <div class="reporte-kpi-value"><?= number_format($despachado, 2) ?> <span class="fs-6 text-muted">L</span></div>
            <small class="text-muted"><?= intval($r['cantidad_despachos']) ?> despacho(s)</small>
        </div>
        <div class="reporte-kpi-card success">
            <div class="reporte-meta-label text-success">Ingresos al Tanque</div>
            <div class="reporte-kpi-value">+<?= number_format($ingresos, 2) ?> <span class="fs-6 text-muted">L</span></div>
            <small class="text-muted">Recargas externas</small>
        </div>
        <div class="reporte-kpi-card <?= $consumoFisico !== null ? 'warning' : 'info' ?>">
            <div class="reporte-meta-label <?= $consumoFisico !== null ? 'text-warning' : 'text-primary' ?>">Consumo Físico Contador</div>
            <div class="reporte-kpi-value">
                <?= $consumoFisico !== null ? number_format($consumoFisico, 2) : '—' ?>
                <span class="fs-6 text-muted">L</span>
            </div>
            <small class="text-muted"><?= $consumoFisico !== null ? 'Diferencia de lecturas' : 'Pendiente de cierre' ?></small>
        </div>
        <div class="reporte-kpi-card <?= abs($mermas) > 0.5 ? 'alert' : 'success' ?>">
            <div class="reporte-meta-label <?= abs($mermas) > 0.5 ? 'text-danger' : 'text-success' ?>">Mermas / Diferencia</div>
            <div class="reporte-kpi-value <?= abs($mermas) > 0.5 ? 'text-danger' : 'text-success' ?>">
                <?= ($mermas > 0 ? '+' : '') . number_format($mermas, 2) ?> <span class="fs-6 text-muted">L</span>
            </div>
            <small class="text-muted">Físico vs Teórico</small>
        </div>
    </div>

    <!-- ══════════ Tabla de Balance de Combustible ══════════ -->
    <div class="p-4 bg-white border-bottom">
        <h5 class="reporte-seccion-titulo"><i class="fas fa-balance-scale me-2 text-secondary"></i>Balance de Combustible — Conciliación</h5>
        <div class="table-responsive">
            <table class="reporte-tabla">
                <thead>
                    <tr>
                        <th style="width: 45%;">Concepto / Indicador</th>
                        <th class="text-end" style="width: 20%;">Litros</th>
                        <th class="text-end" style="width: 20%;">Galones</th>
                        <th class="text-center" style="width: 15%;">Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 1. Estado Inicial -->
                    <tr>
                        <td><strong>1. Lectura Inicial del Contador</strong><br><small class="text-muted">Apertura del turno</small></td>
                        <td class="text-end fw-bold"><?= number_format($r['lectura_inicial_litros'], 2) ?> L</td>
                        <td class="text-end text-muted"><?= number_format($r['lectura_inicial_galones'], 2) ?> gal</td>
                        <td class="text-center"><span class="badge bg-secondary">Inicial</span></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 2rem;"><i class="fas fa-ruler-vertical me-1 text-muted"></i> Varillado Inicial del Tanque</td>
                        <td class="text-end"><?= number_format($r['varillado_inicial_tanque_ltr'] ?? 0, 2) ?> L</td>
                        <td class="text-end text-muted"><?= number_format($r['varillado_inicial_tanque_gal'] ?? 0, 2) ?> gal</td>
                        <td class="text-center"><span class="badge bg-info text-dark">Varillado</span></td>
                    </tr>

                    <!-- 2. Ingresos -->
                    <tr>
                        <td><strong>2. Ingresos / Abastecimiento al Tanque</strong><br><small class="text-muted">Recargas externas de combustible</small></td>
                        <td class="text-end text-success fw-bold">+<?= number_format($ingresos, 2) ?> L</td>
                        <td class="text-end text-muted"><?= number_format($ingresos * 0.264172, 2) ?> gal</td>
                        <td class="text-center"><span class="badge bg-success">Entrada</span></td>
                    </tr>

                    <!-- 3. Despachos -->
                    <tr>
                        <td><strong>3. Total Despachado (Vales)</strong><br><small class="text-muted"><?= intval($r['cantidad_despachos']) ?> registro(s) de combustible</small></td>
                        <td class="text-end text-danger fw-bold">-<?= number_format($despachado, 2) ?> L</td>
                        <td class="text-end text-muted"><?= number_format($despachado * 0.264172, 2) ?> gal</td>
                        <td class="text-center"><span class="badge bg-danger">Salida</span></td>
                    </tr>

                    <!-- 4. Lectura Final -->
                    <?php if ($cerrado): ?>
                    <tr>
                        <td><strong>4. Lectura Final del Contador</strong><br><small class="text-muted">Cierre del turno</small></td>
                        <td class="text-end fw-bold"><?= number_format($r['lectura_final_litros'], 2) ?> L</td>
                        <td class="text-end text-muted"><?= number_format($r['lectura_final_galones'], 2) ?> gal</td>
                        <td class="text-center"><span class="badge bg-secondary">Final</span></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 2rem;"><i class="fas fa-ruler-vertical me-1 text-muted"></i> Varillado Final del Tanque</td>
                        <td class="text-end"><?= number_format($r['varillado_final_medido_ltr'] ?? 0, 2) ?> L</td>
                        <td class="text-end text-muted"><?= number_format($r['varillado_final_medido_gal'] ?? 0, 2) ?> gal</td>
                        <td class="text-center"><span class="badge bg-info text-dark">Varillado</span></td>
                    </tr>
                    <tr class="destacado">
                        <td>Consumo Físico del Turno<br><small class="text-muted">Lectura final − Lectura inicial (contador)</small></td>
                        <td class="text-end text-primary"><?= number_format($consumoFisico, 2) ?> L</td>
                        <td class="text-end text-primary"><?= number_format($consumoFisico * 0.264172, 2) ?> gal</td>
                        <td class="text-center"><span class="badge bg-primary">Consumo</span></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-3 text-muted">
                            <i class="fas fa-info-circle me-1"></i> La lectura final y consumo se calcularán al cerrar el turno.
                        </td>
                    </tr>
                    <?php endif; ?>

                    <!-- 5. Stock Teórico -->
                    <tr class="subtotal">
                        <td><strong>5. Stock Teórico del Tanque</strong><br><small class="text-muted">Varillado inicial + Ingresos − Despachado</small></td>
                        <td class="text-end"><?= number_format($stockTeorico, 2) ?> L</td>
                        <td class="text-end"><?= number_format($stockTeorico * 0.264172, 2) ?> gal</td>
                        <td class="text-center"><span class="badge bg-primary">Teórico</span></td>
                    </tr>

                    <!-- 6. Diferencia / Mermas -->
                    <?php if ($cerrado): ?>
                    <tr class="<?= $mermas > 0 ? 'merma-pos' : 'merma-neg' ?>">
                        <td><strong>6. Diferencia / Mermas</strong><br><small class="text-muted"><?= $mermas > 0 ? 'Faltante' : ($mermas < 0 ? 'Sobrante' : 'Sin diferencia') ?> — Varillado final vs Stock teórico</small></td>
                        <td class="text-end fw-bold <?= $mermas > 0 ? 'text-danger' : 'text-success' ?>"><?= ($mermas > 0 ? '+' : '') . number_format($mermas, 2) ?> L</td>
                        <td class="text-end text-muted"><?= number_format(abs($mermas) * 0.264172, 2) ?> gal</td>
                        <td class="text-center">
                            <span class="badge bg-<?= abs($mermas) > 0.5 ? 'danger' : 'success' ?>"><?= abs($mermas) > 0.5 ? 'Alerta' : 'OK' ?></span>
                        </td>
                    </tr>
                    <?php if ($diffContador !== null): ?>
                    <tr class="<?= $diffContador > 0 ? 'merma-pos' : 'merma-neg' ?>">
                        <td style="padding-left: 2rem;"><i class="fas fa-exchange-alt me-1 text-muted"></i> Diferencia Contador vs Vales<br><small class="text-muted">Consumo físico del contador − Total despachado en vales</small></td>
                        <td class="text-end fw-bold <?= $diffContador > 0 ? 'text-danger' : 'text-success' ?>"><?= ($diffContador > 0 ? '+' : '') . number_format($diffContador, 2) ?> L</td>
                        <td class="text-end text-muted"><?= number_format(abs($diffContador) * 0.264172, 2) ?> gal</td>
                        <td class="text-center">
                            <span class="badge bg-<?= abs($diffContador) > 0.5 ? 'warning' : 'success' ?>"><?= abs($diffContador) > 0.5 ? 'Revisar' : 'OK' ?></span>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══════════ Tabla de Despachos Detallados ══════════ -->
    <?php if (!empty($despachos)): ?>
    <div class="p-4 bg-white border-bottom">
        <h5 class="reporte-seccion-titulo"><i class="fas fa-list me-2 text-secondary"></i>Despachos Detallados (<?= count($despachos) ?>)</h5>
        <div class="table-responsive">
            <table class="tabla-despachos">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Vehículo</th>
                        <th>Placa</th>
                        <th class="text-end">Litros</th>
                        <th class="text-end">Kilometraje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($despachos as $i => $d): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td><?= esc($d['fecha_registro']) ?></td>
                        <td><?= esc(($d['marca'] ?? '') . ' ' . ($d['modelo'] ?? '')) ?></td>
                        <td><strong><?= esc($d['placa'] ?? 'N/A') ?></strong></td>
                        <td class="text-end fw-bold"><?= number_format($d['cantidad_litros'], 2) ?> L</td>
                        <td class="text-end text-muted"><?= esc($d['kilometraje_actual'] ?? '—') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="border-top: 2px solid #e2e8f0;">
                        <td colspan="4" class="text-end fw-bold text-muted">TOTAL DESPACHADO:</td>
                        <td class="text-end fw-bold text-danger"><?= number_format($despachado, 2) ?> L</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="p-4 bg-white border-bottom text-center">
        <p class="text-muted small mb-0"><i class="fas fa-inbox me-1"></i> No hay despachos registrados en este turno.</p>
    </div>
    <?php endif; ?>

    <!-- ══════════ Observaciones ══════════ -->
    <div class="p-4 bg-white border-bottom">
        <?php if (!empty($r['observaciones']) || !empty($r['observaciones_cierre'])): ?>
            <h5 class="reporte-seccion-titulo"><i class="fas fa-exclamation-circle me-2 text-secondary"></i>Observaciones y Notas de Turno</h5>
            <?php if (!empty($r['observaciones'])): ?>
            <div class="reporte-memo">
                <div class="fw-bold text-warning-emphasis mb-1"><i class="fas fa-sign-in-alt me-1"></i> NOTAS DE APERTURA:</div>
                <div class="small text-secondary"><?= nl2br(esc($r['observaciones'])) ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($r['observaciones_cierre'])): ?>
            <div class="reporte-memo" style="border-left-color: #2563eb; background-color: #f0f7ff; border-color: #e0f2fe;">
                <div class="fw-bold text-primary mb-1"><i class="fas fa-sign-out-alt me-1"></i> NOTAS DE CIERRE:</div>
                <div class="small text-secondary"><?= nl2br(esc($r['observaciones_cierre'])) ?></div>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <h5 class="reporte-seccion-titulo"><i class="fas fa-check-circle me-2 text-success"></i>Observaciones</h5>
            <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1 text-muted"></i> No se registraron anomalías ni observaciones durante este turno.</p>
        <?php endif; ?>
    </div>

    <!-- Firmas (solo impresión) -->
    <div class="reporte-firmas bg-white">
        <div>
            <div class="reporte-linea-firma">
                Firma del Operador Responsable<br>
                <small class="text-muted fw-normal" style="font-size: 0.7rem;"><?= esc($r['usuario_apertura']) ?></small>
            </div>
        </div>
        <div>
            <div class="reporte-linea-firma">
                Firma de Supervisor / Autorización<br>
                <small class="text-muted fw-normal" style="font-size: 0.7rem;">Control Interno Bomba</small>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="p-3 bg-light d-flex justify-content-between align-items-center no-print border-top">
        <div class="small text-muted">
            <i class="fas fa-shield-alt me-1 text-muted"></i> Reporte generado por: <strong><?= esc($r['usuario_apertura']) ?></strong>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" onclick="descargarPDF()">
                <i class="fas fa-file-pdf me-1"></i>Descargar PDF
            </button>
            <button class="share-btn" onclick="compartirPDF()">
                <i class="fas fa-share-alt me-1"></i>Compartir
            </button>
        </div>
    </div>
</div>

<script>
function generarPDF(callback) {
    const { jsPDF } = window.jspdf;
    const elemento = document.querySelector('.reporte-container');

    Swal.fire({
        title: 'Generando PDF...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    html2canvas(elemento, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        windowWidth: elemento.scrollWidth
    }).then(function(canvas) {
        Swal.close();

        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        const imgWidth = pageWidth;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        let heightLeft = imgHeight;
        let position = 0;

        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft > 0) {
            position -= pageHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        const fileName = 'Reporte_LB-<?= str_pad($r['id_medicion'], 6, '0', STR_PAD_LEFT) ?>.pdf';

        if (callback) {
            callback(pdf, fileName);
        } else {
            pdf.save(fileName);
        }
    }).catch(function(err) {
        Swal.close();
        console.error('Error generando PDF:', err);
        Swal.fire('Error', 'No se pudo generar el PDF', 'error');
    });
}

function descargarPDF() {
    generarPDF(function(pdf, fileName) {
        pdf.save(fileName);
    });
}

function compartirPDF() {
    generarPDF(function(pdf, fileName) {
        const blob = pdf.output('blob');
        const file = new File([blob], fileName, { type: 'application/pdf' });

        if (navigator.canShare && navigator.canShare({ files: [file] })) {
            navigator.share({
                title: 'Reporte LB-<?= str_pad($r['id_medicion'], 6, '0', STR_PAD_LEFT) ?>',
                text: 'Reporte de Conciliación de Combustible',
                files: [file]
            }).catch(console.error);
        } else {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            a.click();
            URL.revokeObjectURL(url);
            Swal.fire({
                icon: 'info',
                title: 'PDF descargado',
                text: 'Tu dispositivo no soporta compartir directamente. El PDF se descargó para que lo envíes manualmente.',
                timer: 4000
            });
        }
    });
}
</script>

<?= $this->endSection() ?>
