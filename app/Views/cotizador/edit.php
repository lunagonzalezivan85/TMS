<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
.calc-row { display:flex; justify-content:space-between; padding:.35rem 0; border-bottom:1px dashed #e5e7eb; font-size:.85rem; }
.calc-row:last-child { border-bottom:none; }
.calc-row .lbl { color:#64748b; }
.calc-row .val { font-weight:600; color:#1e293b; }
.calc-total { background:#f0fdf4; border-radius:8px; padding:.5rem .75rem; margin-top:.5rem; }
.calc-total .val { font-size:1.1rem; font-weight:700; color:#059c73; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$esBorrador  = ($cotizacion['estado'] ?? '') === 'BORRADOR';
$esAprobada  = ($cotizacion['estado'] ?? '') === 'APROBADA';
$esRechazada = ($cotizacion['estado'] ?? '') === 'RECHAZADA';
$bloqueado   = !$esBorrador; // Solo borrador es editable
$estColors = ['BORRADOR'=>'secondary','ENVIADA'=>'info','APROBADA'=>'success','RECHAZADA'=>'danger'];
$estLabels = ['BORRADOR'=>'Borrador','ENVIADA'=>'Enviada','APROBADA'=>'Aprobada','RECHAZADA'=>'Rechazada'];
?>

<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-edit fa-2x text-primary me-3"></i>
            <div>
                <h2 class="fw-bold mb-0">Editar Cotización</h2>
                <small class="text-muted"><?= esc($cotizacion['numero_cotizacion']) ?> — <?= esc($cotizacion['cliente_nombre']) ?>
                    <span class="badge bg-<?= $estColors[$cotizacion['estado'] ?? 'BORRADOR'] ?? 'secondary' ?> ms-2">
                        <?= $estLabels[$cotizacion['estado'] ?? 'BORRADOR'] ?? 'Borrador' ?>
                    </span>
                </small>
            </div>
        </div>
        <a href="<?= base_url('cotizador/historial') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-1"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach(session()->getFlashdata('errors') as $err): ?>
            <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($bloqueado): ?>
    <div class="alert alert-info">
        <i class="fas fa-lock me-2"></i> Esta cotización está <strong><?= $estLabels[$cotizacion['estado']] ?></strong> y no puede modificarse.
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm p-4">
                <form action="<?= base_url('cotizador/update/'.$cotizacion['id']) ?>" method="POST" id="formCotizador">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Producto</label>
                            <select name="producto_id" id="producto_id" class="form-select" <?= $bloqueado ? 'disabled' : '' ?>>
                                <?php foreach($productos as $id => $p): ?>
                                <option value="<?= $id ?>" data-cost="<?= $p['costPerGallon'] ?>" <?= ($cotizacion['producto_id'] ?? '') === $id ? 'selected' : '' ?>>
                                    <?= esc($p['name']) ?> — C$ <?= number_format($p['costPerGallon'], 2) ?>/gal
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($bloqueado): ?>
                            <input type="hidden" name="producto_id" value="<?= esc($cotizacion['producto_id'] ?? 'diesel') ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nombre del cliente <span class="text-danger">*</span></label>
                            <input type="text" name="cliente_nombre" id="cliente_nombre" class="form-control" required value="<?= esc($cotizacion['cliente_nombre']) ?>" <?= $bloqueado ? 'readonly' : '' ?>>
                            <?php if ($bloqueado): ?>
                            <input type="hidden" name="cliente_nombre" value="<?= esc($cotizacion['cliente_nombre']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">RUC</label>
                            <input type="text" name="cliente_ruc" id="cliente_ruc" class="form-control" value="<?= esc($cotizacion['cliente_ruc'] ?? '') ?>" <?= $bloqueado ? 'readonly' : '' ?>>
                            <?php if ($bloqueado): ?>
                            <input type="hidden" name="cliente_ruc" value="<?= esc($cotizacion['cliente_ruc'] ?? '') ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <input type="text" name="cliente_telefono" id="cliente_telefono" class="form-control" value="<?= esc($cotizacion['cliente_telefono'] ?? '') ?>" <?= $bloqueado ? 'readonly' : '' ?>>
                            <?php if ($bloqueado): ?>
                            <input type="hidden" name="cliente_telefono" value="<?= esc($cotizacion['cliente_telefono'] ?? '') ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipo de unidad</label>
                            <select name="tipo_vehiculo" id="tipo_vehiculo" class="form-select" <?= $bloqueado ? 'disabled' : '' ?>>
                                <option value="">Seleccionar...</option>
                                <?php foreach($tiposUnidad as $id => $nombre): ?>
                                <option value="<?= esc($nombre) ?>" <?= ($cotizacion['tipo_vehiculo'] ?? '') === $nombre ? 'selected' : '' ?>><?= esc($nombre) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($bloqueado): ?>
                            <input type="hidden" name="tipo_vehiculo" value="<?= esc($cotizacion['tipo_vehiculo'] ?? '') ?>">
                            <?php endif; ?>
                        </div>

                        <hr class="mt-3 mb-2">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Volumen (galones) <span class="text-danger">*</span></label>
                            <input type="number" name="volumen_galones" id="volumen_galones" class="form-control" step="any" min="0.01" value="<?= $cotizacion['volumen_galones'] ?>" required <?= $bloqueado ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Distancia (km) <span class="text-danger">*</span></label>
                            <input type="number" name="distancia_km" id="distancia_km" class="form-control" step="any" min="0.01" value="<?= $cotizacion['distancia_km'] ?>" required <?= $bloqueado ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Rendimiento (km/gal) <span class="text-danger">*</span></label>
                            <input type="number" name="rendimiento_km_galon" id="rendimiento_km_galon" class="form-control" step="any" min="0.01" value="<?= $cotizacion['rendimiento_km_galon'] ?>" required <?= $bloqueado ? 'readonly' : '' ?>>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Margen objetivo (%)</label>
                            <input type="number" name="margen_porcentaje" id="margen_porcentaje" class="form-control" step="any" min="0" value="<?= $desglose['margen_objetivo_pct'] ?? 15 ?>" <?= $bloqueado ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Precio propuesto (C$)</label>
                            <input type="number" name="precio_propuesto" id="precio_propuesto" class="form-control" step="any" min="0" value="<?= $cotizacion['precio_final'] > 0 ? $cotizacion['precio_final'] : '' ?>" <?= $bloqueado ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Chofer (%)</label>
                            <input type="number" name="chofer_porcentaje" id="chofer_porcentaje" class="form-control" step="any" min="0" value="<?= $desglose['chofer_porcentaje'] ?? 12 ?>" <?= $bloqueado ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Viático (C$)</label>
                            <input type="number" name="viatico" id="viatico" class="form-control" step="any" min="0" value="<?= $desglose['viatico'] ?? 330 ?>" <?= $bloqueado ? 'readonly' : '' ?>>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Admin (%)</label>
                            <input type="number" name="admin_porcentaje" id="admin_porcentaje" class="form-control" step="any" min="0" value="<?= $desglose['admin_porcentaje'] ?? 8 ?>" <?= $bloqueado ? 'readonly' : '' ?>>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2 flex-wrap">
                                <?php if ($esBorrador): ?>
                                <button type="button" class="btn btn-outline-primary" id="btnCalcular">
                                    <i class="fas fa-calculator me-1"></i> Calcular
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar cambios
                                </button>
                                <?php endif; ?>
                                <a href="<?= base_url('cotizador/show/'.$cotizacion['id']) ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-eye me-1"></i> Ver detalle
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <!-- Resultado del cálculo -->
            <div class="card border-0 shadow-sm p-4" id="resultadoCard" style="display:none;">
                <h5 class="fw-bold mb-3"><i class="fas fa-chart-line me-2 text-success"></i>Resultado del cálculo</h5>
                <div id="resultadoBody"></div>
            </div>

            <!-- Acciones de aprobación -->
            <?php if ($esBorrador): ?>
            <div class="card border-0 shadow-sm p-4 mt-3">
                <h5 class="fw-bold mb-3"><i class="fas fa-gavel me-2 text-warning"></i>Revisión</h5>
                <p class="text-muted small">¿Esta cotización está lista para ser aprobada?</p>
                <div class="d-flex gap-2">
                    <form action="<?= base_url('cotizador/aprobar/'.$cotizacion['id']) ?>" method="POST" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-1"></i> Aprobar
                        </button>
                    </form>
                    <form action="<?= base_url('cotizador/rechazar/'.$cotizacion['id']) ?>" method="POST" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-times-circle me-1"></i> Rechazar
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function() {
    const baseUrl = '<?= base_url() ?>';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const csrfHeader = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const EXCHANGE_RATE = <?= $factores['exchangeRate'] ?? 36.6243 ?>;

    function fmtCordobas(val) {
        return new Intl.NumberFormat('es-NI', { style:'currency', currency:'NIO', maximumFractionDigits:2 }).format(Number(val||0));
    }
    function fmtUSD(val) {
        return new Intl.NumberFormat('en-US', { style:'currency', currency:'USD', maximumFractionDigits:2 }).format(Number(val||0));
    }

    function getFormData() {
        return {
            producto_id: document.getElementById('producto_id')?.value || 'diesel',
            volumen_galones: parseFloat(document.getElementById('volumen_galones')?.value) || 0,
            distancia_km: parseFloat(document.getElementById('distancia_km')?.value) || 0,
            rendimiento_km_galon: parseFloat(document.getElementById('rendimiento_km_galon')?.value) || 0,
            margen_porcentaje: parseFloat(document.getElementById('margen_porcentaje')?.value) || 0,
            precio_propuesto: parseFloat(document.getElementById('precio_propuesto')?.value) || 0,
            chofer_porcentaje: parseFloat(document.getElementById('chofer_porcentaje')?.value) || 0,
            viatico: parseFloat(document.getElementById('viatico')?.value) || 0,
            admin_porcentaje: parseFloat(document.getElementById('admin_porcentaje')?.value) || 0,
        };
    }

    function renderResultado(calc) {
        const card = document.getElementById('resultadoCard');
        const body = document.getElementById('resultadoBody');
        card.style.display = 'block';
        body.innerHTML = `
            <div class="calc-row"><span class="lbl">Galones consumidos</span><span class="val">${calc.galones_consumidos.toFixed(2)} gal</span></div>
            <div class="calc-row"><span class="lbl">Costo combustible</span><span class="val">${fmtCordobas(calc.costo_combustible)}</span></div>
            <div class="calc-row"><span class="lbl">Depreciación</span><span class="val">${fmtCordobas(calc.depreciacion)}</span></div>
            <div class="calc-row"><span class="lbl">Mantenimiento</span><span class="val">${fmtCordobas(calc.mantenimiento)}</span></div>
            <div class="calc-row"><span class="lbl">Llantas</span><span class="val">${fmtCordobas(calc.llantas)}</span></div>
            <div class="calc-row"><span class="lbl">Viático</span><span class="val">${fmtCordobas(calc.viatico)}</span></div>
            <div class="calc-row"><span class="lbl">Costo directo fijo</span><span class="val">${fmtCordobas(calc.costo_directo_fijo)}</span></div>
            <div class="calc-row"><span class="lbl">Costo chofer (${calc.chofer_pct_efectivo.toFixed(1)}%)</span><span class="val">${fmtCordobas(calc.costo_chofer)}</span></div>
            <div class="calc-row"><span class="lbl">Costo admin</span><span class="val">${fmtCordobas(calc.costo_admin)}</span></div>
            <div class="calc-row"><span class="lbl">Municipalidad</span><span class="val">${fmtCordobas(calc.costo_municipalidad)}</span></div>
            <div class="calc-row"><span class="lbl">DGI</span><span class="val">${fmtCordobas(calc.costo_dgi)}</span></div>
            <div class="calc-row"><span class="lbl">Costo total viaje</span><span class="val">${fmtCordobas(calc.costo_viaje)}</span></div>
            <hr class="my-2">
            <div class="calc-row"><span class="lbl">Precio sugerido</span><span class="val">${fmtCordobas(calc.precio_sugerido)}</span></div>
            <div class="calc-row"><span class="lbl">Margen real</span><span class="val">${calc.margen_porcentaje.toFixed(1)}% (${fmtCordobas(calc.margen_monto)})</span></div>
            <div class="calc-row"><span class="lbl">Flete por galón</span><span class="val">C$ ${calc.flete_por_galon.toFixed(4)}</span></div>
            <div class="calc-total">
                <div class="calc-row"><span class="lbl">PRECIO FINAL</span><span class="val">${fmtCordobas(calc.precio_final)}</span></div>
                <div class="calc-row"><span class="lbl">≈ USD</span><span class="val">${fmtUSD(calc.precio_final / EXCHANGE_RATE)}</span></div>
            </div>
        `;
    }

    document.getElementById('btnCalcular')?.addEventListener('click', async function() {
        const data = getFormData();
        try {
            const resp = await fetch(`${baseUrl}cotizador/calcular`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeader]: csrfToken,
                },
                body: JSON.stringify(data),
            });
            const result = await resp.json();
            if (result.success) {
                renderResultado(result.calc);
            } else {
                alert('Error: ' + (result.error || 'No se pudo calcular'));
            }
        } catch(err) {
            alert('Error de conexión: ' + err.message);
        }
    });
})();
</script>
<?= $this->endSection() ?>
