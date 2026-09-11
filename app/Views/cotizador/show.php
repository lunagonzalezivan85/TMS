<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
/* Section cards */
.sec-card { border:1px solid #e5e7eb; border-radius:12px; background:#fff; overflow:hidden; }
.sec-header { padding:.75rem 1.25rem; font-weight:700; font-size:.95rem; display:flex; align-items:center; gap:.5rem; border-bottom:1px solid #f1f5f9; }
.sec-body { padding:1.25rem; }

/* Data rows */
.det-row { display:flex; justify-content:space-between; padding:.45rem 0; border-bottom:1px dashed #e5e7eb; font-size:.875rem; }
.det-row:last-child { border-bottom:none; }
.det-row .lbl { color:#64748b; }
.det-row .val { font-weight:600; color:#1e293b; }

/* Highlight boxes */
.det-total { background:#f0fdf4; border-radius:8px; padding:.75rem 1rem; margin-top:.5rem; }
.det-total .val { font-size:1.15rem; font-weight:700; color:#059c73; }

/* Metric cards */
.metric-card { border-radius:10px; padding:1rem 1.25rem; text-align:center; }
.metric-card .label { font-size:.7rem; color:#64748b; text-transform:uppercase; letter-spacing:.04em; }
.metric-card .val { font-size:1.4rem; font-weight:700; }
.metric-card .sub { font-size:.72rem; color:#94a3b8; margin-top:.15rem; }

/* Profitability banner */
.profit-banner { border-radius:12px; padding:1.25rem 1.5rem; display:flex; align-items:center; gap:1rem; }
.profit-banner .icon { width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.profit-banner .text h4 { margin:0; font-weight:700; font-size:1.1rem; }
.profit-banner .text p { margin:.15rem 0 0; font-size:.85rem; }
.profit-yes { background:#f0fdf4; border:1px solid #bbf7e0; }
.profit-yes .icon { background:#059c73; color:#fff; }
.profit-yes h4 { color:#059c73; }
.profit-no { background:#fef2f2; border:1px solid #fecaca; }
.profit-no .icon { background:#dc2626; color:#fff; }
.profit-no h4 { color:#dc2626; }

/* Info grid */
.info-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:.75rem; }
.info-item { padding:.5rem 0; }
.info-item .lbl { font-size:.72rem; color:#94a3b8; text-transform:uppercase; letter-spacing:.03em; }
.info-item .val { font-weight:600; color:#1e293b; font-size:.9rem; }

/* Cost breakdown table */
.cost-table { width:100%; border-collapse:collapse; }
.cost-table th { text-align:left; padding:.5rem .75rem; font-size:.75rem; color:#64748b; text-transform:uppercase; border-bottom:2px solid #e5e7eb; }
.cost-table td { padding:.5rem .75rem; font-size:.875rem; border-bottom:1px solid #f1f5f9; }
.cost-table td.num { text-align:right; font-weight:600; }
.cost-table tr.total-row { background:#f8fafc; }
.cost-table tr.total-row td { font-weight:700; border-top:2px solid #e5e7eb; border-bottom:none; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$d = $desglose;
$estColors = ['BORRADOR'=>'secondary','ENVIADA'=>'info','APROBADA'=>'success','RECHAZADA'=>'danger'];
$estLabels = ['BORRADOR'=>'Borrador','ENVIADA'=>'Enviada','APROBADA'=>'Aprobada','RECHAZADA'=>'Rechazada'];
$est = $cotizacion['estado'] ?? 'BORRADOR';

// Métricas de rentabilidad
$precioFinal = (float)($d['precio_final'] ?? $cotizacion['precio_final'] ?? 0);
$costoViaje  = (float)($d['costo_viaje'] ?? $cotizacion['costo_viaje'] ?? 0);
$margenMonto = (float)($d['margen_monto'] ?? $cotizacion['margen_monto'] ?? 0);
$margenPct   = (float)($d['margen_porcentaje'] ?? $cotizacion['margen_porcentaje'] ?? 0);
$margenObj   = (float)($d['margen_objetivo_pct'] ?? 0);
$costoDirecto = (float)($d['costo_directo_fijo'] ?? 0);
$volumen      = (float)($cotizacion['volumen_galones'] ?? 0);
$distancia    = (float)($cotizacion['distancia_km'] ?? 0);
$fleteGalon   = (float)($d['flete_por_galon'] ?? $cotizacion['flete_por_galon'] ?? 0);
$tipoCambio   = (float)($d['tipo_cambio'] ?? 36.6243);

$esRentable = $margenMonto > 0;
$cumpleObjetivo = $margenPct >= $margenObj && $margenObj > 0;
$roi = $costoViaje > 0 ? ($margenMonto / $costoViaje) * 100 : 0;
$costoPorGalon = $volumen > 0 ? $costoViaje / $volumen : 0;
$precioUSD = $tipoCambio > 0 ? $precioFinal / $tipoCambio : 0;
$costoDirectoPct = $precioFinal > 0 ? ($costoDirecto / $precioFinal) * 100 : 0;
$costosRetenidosPct = $precioFinal > 0 ? (($costoViaje - $costoDirecto) / $precioFinal) * 100 : 0;
?>

<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-file-invoice fa-2x text-primary me-3"></i>
            <div>
                <h2 class="fw-bold mb-0"><?= esc($cotizacion['numero_cotizacion']) ?></h2>
                <small class="text-muted"><?= esc($cotizacion['cliente_nombre']) ?> · <span class="badge bg-<?= $estColors[$est] ?? 'secondary' ?>"><?= $estLabels[$est] ?? $est ?></span></small>
            </div>
        </div>
        <a href="<?= base_url('cotizador/historial') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver al historial
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Columna principal -->
        <div class="col-12 col-lg-8">

            <!-- ═══ Rentabilidad ═══ -->
            <div class="profit-banner <?= $esRentable ? 'profit-yes' : 'profit-no' ?> mb-4">
                <div class="icon">
                    <i class="fas <?= $esRentable ? 'fa-check' : 'fa-exclamation-triangle' ?>"></i>
                </div>
                <div class="text">
                    <h4><?= $esRentable ? 'Cotización Rentable' : 'Cotización NO Rentable' ?></h4>
                    <p>
                        Margen de <strong>C$ <?= number_format($margenMonto, 2) ?></strong>
                        (<?= number_format($margenPct, 1) ?>% sobre el precio)
                        · ROI <strong><?= number_format($roi, 1) ?>%</strong>
                        <?php if ($margenObj > 0): ?>
                        · Objetivo era <?= number_format($margenObj, 1) ?>%
                        <?= $cumpleObjetivo ? '<i class="fas fa-check-circle text-success ms-1"></i>' : '<i class="fas fa-times-circle text-danger ms-1"></i>' ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- ═══ Métricas clave ═══ -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="metric-card" style="background:#eff6ff;">
                        <div class="label">Precio Final</div>
                        <div class="val text-primary">C$ <?= number_format($precioFinal, 0) ?></div>
                        <div class="sub">≈ $<?= number_format($precioUSD, 2) ?> USD</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric-card" style="background:#f0fdf4;">
                        <div class="label">Margen</div>
                        <div class="val text-success">C$ <?= number_format($margenMonto, 0) ?></div>
                        <div class="sub"><?= number_format($margenPct, 1) ?>% del precio</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric-card" style="background:#fef3c7;">
                        <div class="label">Costo Total</div>
                        <div class="val" style="color:#b45309;">C$ <?= number_format($costoViaje, 0) ?></div>
                        <div class="sub"><?= number_format($costoPorGalon, 2) ?> C$/gal</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric-card" style="background:#f1f5f9;">
                        <div class="label">Flete/Galón</div>
                        <div class="val" style="color:#475569;">C$ <?= number_format($fleteGalon, 2) ?></div>
                        <div class="sub"><?= number_format($volumen, 0) ?> gal transportados</div>
                    </div>
                </div>
            </div>

            <!-- ═══ 1. Información del Cliente ═══ -->
            <div class="sec-card mb-4">
                <div class="sec-header" style="background:#eff6ff;">
                    <i class="fas fa-user text-primary"></i> 1. Información del Cliente
                </div>
                <div class="sec-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="lbl">Cliente</div>
                            <div class="val"><?= esc($cotizacion['cliente_nombre']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">RUC</div>
                            <div class="val"><?= esc($cotizacion['cliente_ruc'] ?: '—') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Teléfono</div>
                            <div class="val"><?= esc($cotizacion['cliente_telefono'] ?: '—') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Fecha creación</div>
                            <div class="val"><?= $cotizacion['fecha_creacion'] ? date('d/m/Y H:i', strtotime($cotizacion['fecha_creacion'])) : '—' ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ 2. Datos de la Operación ═══ -->
            <div class="sec-card mb-4">
                <div class="sec-header" style="background:#f0fdf4;">
                    <i class="fas fa-truck text-success"></i> 2. Datos de la Operación
                </div>
                <div class="sec-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="lbl">Producto</div>
                            <div class="val"><?= esc($cotizacion['producto_nombre'] ?? $cotizacion['producto_id']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Tipo de unidad</div>
                            <div class="val"><?= esc($cotizacion['tipo_vehiculo'] ?: '—') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Distancia</div>
                            <div class="val"><?= number_format($distancia) ?> km</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Volumen</div>
                            <div class="val"><?= number_format($volumen) ?> gal</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Rendimiento</div>
                            <div class="val"><?= number_format($cotizacion['rendimiento_km_galon'], 2) ?> km/gal</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Galones consumidos</div>
                            <div class="val"><?= number_format($d['galones_consumidos'] ?? 0, 2) ?> gal</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ 3. Desglose de Costos ═══ -->
            <div class="sec-card mb-4">
                <div class="sec-header" style="background:#fef3c7;">
                    <i class="fas fa-calculator" style="color:#b45309;"></i> 3. Desglose de Costos
                </div>
                <div class="sec-body">
                    <table class="cost-table">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th class="text-end">Monto (C$)</th>
                                <th class="text-end">% del precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Combustible</td><td class="num"><?= number_format($d['costo_combustible'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['costo_combustible'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr><td>Depreciación</td><td class="num"><?= number_format($d['depreciacion'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['depreciacion'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr><td>Mantenimiento</td><td class="num"><?= number_format($d['mantenimiento'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['mantenimiento'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr><td>Llantas</td><td class="num"><?= number_format($d['llantas'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['llantas'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr><td>Viático</td><td class="num"><?= number_format($d['viatico'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['viatico'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr class="total-row"><td>Costo Directo Fijo</td><td class="num"><?= number_format($costoDirecto, 2) ?></td><td class="num"><?= number_format($costoDirectoPct, 1) ?>%</td></tr>
                            <tr><td>Chofer (<?= number_format($d['chofer_pct_efectivo'] ?? 0, 1) ?>%)</td><td class="num"><?= number_format($d['costo_chofer'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['costo_chofer'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr><td>Administración</td><td class="num"><?= number_format($d['costo_admin'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['costo_admin'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr><td>Municipalidad</td><td class="num"><?= number_format($d['costo_municipalidad'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['costo_municipalidad'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr><td>DGI</td><td class="num"><?= number_format($d['costo_dgi'] ?? 0, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? ($d['costo_dgi'] ?? 0) / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                            <tr class="total-row"><td>Costo Total del Viaje</td><td class="num"><?= number_format($costoViaje, 2) ?></td><td class="num"><?= number_format($precioFinal > 0 ? $costoViaje / $precioFinal * 100 : 0, 1) ?>%</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ═══ 4. Análisis de Pricing ═══ -->
            <div class="sec-card mb-4">
                <div class="sec-header" style="background:#f5f3ff;">
                    <i class="fas fa-chart-line" style="color:#7c3aed;"></i> 4. Análisis de Pricing
                </div>
                <div class="sec-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="det-row"><span class="lbl">Precio sugerido (automático)</span><span class="val">C$ <?= number_format($d['precio_sugerido'] ?? 0, 2) ?></span></div>
                            <div class="det-row"><span class="lbl">Precio final aplicado</span><span class="val">C$ <?= number_format($precioFinal, 2) ?></span></div>
                            <div class="det-row"><span class="lbl">Diferencia vs sugerido</span><span class="val"><?= $precioFinal > ($d['precio_sugerido'] ?? 0) ? '+' : '' ?>C$ <?= number_format($precioFinal - ($d['precio_sugerido'] ?? 0), 2) ?></span></div>
                            <div class="det-row"><span class="lbl">¿Precio manual?</span><span class="val"><?= ($d['tiene_precio_manual'] ?? false) ? 'Sí' : 'No (sugerido)' ?></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="det-row"><span class="lbl">Margen objetivo</span><span class="val"><?= number_format($margenObj, 1) ?>%</span></div>
                            <div class="det-row"><span class="lbl">Margen real</span><span class="val" style="color:<?= $esRentable ? '#059c73' : '#dc2626' ?>;"><?= number_format($margenPct, 1) ?>%</span></div>
                            <div class="det-row"><span class="lbl">ROI sobre costo</span><span class="val"><?= number_format($roi, 1) ?>%</span></div>
                            <div class="det-row"><span class="lbl">Flete por galón</span><span class="val">C$ <?= number_format($fleteGalon, 4) ?></span></div>
                            <div class="det-total">
                                <div class="det-row"><span class="lbl">PRECIO FINAL</span><span class="val">C$ <?= number_format($precioFinal, 2) ?></span></div>
                                <div class="det-row"><span class="lbl">≈ USD</span><span class="val">$ <?= number_format($precioUSD, 2) ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Columna lateral -->
        <div class="col-12 col-lg-4">
            <!-- Acciones -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-cog me-2 text-primary"></i>Acciones</h5>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('cotizador/imprimir/'.$cotizacion['id']) ?>" target="_blank" class="btn btn-success">
                        <i class="fas fa-print me-1"></i> Imprimir cotización
                    </a>
                    <?php if ($est === 'BORRADOR'): ?>
                    <a href="<?= base_url('cotizador/edit/'.$cotizacion['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Editar cotización
                    </a>
                    <?php endif; ?>
                    <button class="btn btn-outline-danger btnEliminar" data-id="<?= $cotizacion['id'] ?>">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                </div>
            </div>

            <!-- Auditoría -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-history me-2 text-muted"></i>Auditoría</h5>
                <div class="det-row"><span class="lbl">Creado por</span><span class="val"><?= esc($cotizacion['usuario_crea'] ?? '—') ?></span></div>
                <div class="det-row"><span class="lbl">Fecha creación</span><span class="val"><?= $cotizacion['fecha_creacion'] ? date('d/m/Y H:i', strtotime($cotizacion['fecha_creacion'])) : '—' ?></span></div>
                <div class="det-row"><span class="lbl">Últ. actualización</span><span class="val"><?= $cotizacion['fecha_actualiza'] ? date('d/m/Y H:i', strtotime($cotizacion['fecha_actualiza'])) : '—' ?></span></div>
                <div class="det-row"><span class="lbl">Estado</span><span class="val"><span class="badge bg-<?= $estColors[$est] ?? 'secondary' ?>"><?= $estLabels[$est] ?? $est ?></span></span></div>
            </div>

            <!-- Resumen ejecutivo -->
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-clipboard-list me-2 text-success"></i>Resumen Ejecutivo</h5>
                <div class="det-row"><span class="lbl">Cliente</span><span class="val text-end"><?= esc($cotizacion['cliente_nombre']) ?></span></div>
                <div class="det-row"><span class="lbl">Producto</span><span class="val text-end"><?= esc($cotizacion['producto_nombre'] ?? '—') ?></span></div>
                <div class="det-row"><span class="lbl">Volumen</span><span class="val text-end"><?= number_format($volumen) ?> gal</span></div>
                <div class="det-row"><span class="lbl">Distancia</span><span class="val text-end"><?= number_format($distancia) ?> km</span></div>
                <hr class="my-2">
                <div class="det-row"><span class="lbl">Costo total</span><span class="val text-end">C$ <?= number_format($costoViaje, 2) ?></span></div>
                <div class="det-row"><span class="lbl">Precio final</span><span class="val text-end">C$ <?= number_format($precioFinal, 2) ?></span></div>
                <div class="det-row"><span class="lbl">Margen</span><span class="val text-end" style="color:<?= $esRentable ? '#059c73' : '#dc2626' ?>;">C$ <?= number_format($margenMonto, 2) ?> (<?= number_format($margenPct, 1) ?>%)</span></div>
                <div class="det-total">
                    <div class="det-row"><span class="lbl">RENTABILIDAD</span><span class="val" style="color:<?= $esRentable ? '#059c73' : '#dc2626' ?>;"><?= $esRentable ? 'RENTABLE' : 'NO RENTABLE' ?></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.querySelector('.btnEliminar')?.addEventListener('click', async function() {
    if (!confirm('¿Eliminar esta cotización?')) return;
    const id = this.dataset.id;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const csrfHeader = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    try {
        const resp = fetch('<?= base_url("cotizador/delete/") ?>' + id, {
            method: 'DELETE',
            headers: { [csrfHeader]: csrfToken },
        });
        const result = await (await resp).json();
        if (result.success) {
            window.location.href = '<?= base_url("cotizador/historial") ?>';
        } else {
            alert('Error: ' + (result.message || 'No se pudo eliminar'));
        }
    } catch(err) {
        alert('Error: ' + err.message);
    }
});
</script>
<?= $this->endSection() ?>
