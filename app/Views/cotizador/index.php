<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
/* ── Wizard ─────────────────────────────────────────── */
.wz-wrap{max-width:680px;margin:0 auto;}
.wz-steps{display:flex;gap:0;margin-bottom:2rem;}
.wz-step{flex:1;text-align:center;position:relative;}
.wz-step:not(:last-child)::after{content:'';position:absolute;top:20px;left:50%;width:100%;height:2px;background:#e2e8f0;z-index:0;}
.wz-step.done::after,.wz-step.active::after{background:#07b889;}
.wz-bubble{width:40px;height:40px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;position:relative;z-index:1;background:#e2e8f0;color:#64748b;transition:all .3s;}
.wz-step.active .wz-bubble{background:#07b889;color:#fff;box-shadow:0 0 0 4px #d1fae5;}
.wz-step.done .wz-bubble{background:#059c73;color:#fff;}
.wz-label{font-size:.75rem;margin-top:.35rem;color:#64748b;font-weight:500;}
.wz-step.active .wz-label{color:#07b889;font-weight:700;}
.wz-panel{display:none;}.wz-panel.active{display:block;}
.calc-row { display:flex; justify-content:space-between; padding:.35rem 0; border-bottom:1px dashed #e2e8f0; font-size:.85rem; }
.calc-row:last-child { border-bottom:none; }
.calc-row .lbl { color:#64748b; }
.calc-row .val { font-weight:600; color:#1e293b; }
.calc-total { background:#f0fdf4; border-radius:8px; padding:.5rem .75rem; margin-top:.5rem; }
.calc-total .val { font-size:1.1rem; font-weight:700; color:#059c73; }
.cot-kpi { border-radius:10px; padding:1rem 1.25rem; }
.cot-kpi .label { font-size:.72rem; color:#64748b; text-transform:uppercase; letter-spacing:.04em; }
.cot-kpi .val { font-size:1.5rem; font-weight:700; color:#1e293b; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$validationErrors = session()->getFlashdata('errors');
if (!empty($validationErrors) && !is_array($validationErrors)) $validationErrors = null;
?>

<!-- Page header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h4 mb-0"><i class="fas fa-calculator me-2 text-success"></i>Cotizador de Flete</h1>
    <p class="text-muted small mb-0">Calcule el precio sugerido de flete para sus clientes</p>
  </div>
  <a href="<?= base_url('cotizador/historial') ?>" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-history me-1"></i>Historial
  </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (!empty($validationErrors) && is_array($validationErrors)): ?>
<div class="alert alert-warning alert-dismissible fade show">
  <strong><i class="fas fa-exclamation-triangle me-2"></i>Errores de validación:</strong>
  <ul class="mb-0 mt-2">
    <?php foreach ($validationErrors as $field => $msg): ?>
    <li><strong><?= esc($field) ?>:</strong> <?= esc(is_array($msg) ? implode(', ', $msg) : $msg) ?></li>
    <?php endforeach; ?>
  </ul>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="wz-wrap">
  <!-- Step indicators -->
  <div class="wz-steps">
    <div class="wz-step active" id="ind1">
      <div class="wz-bubble">1</div>
      <div class="wz-label">Cliente</div>
    </div>
    <div class="wz-step" id="ind2">
      <div class="wz-bubble">2</div>
      <div class="wz-label">Operación</div>
    </div>
    <div class="wz-step" id="ind3">
      <div class="wz-bubble">3</div>
      <div class="wz-label">Costos</div>
    </div>
    <div class="wz-step" id="ind4">
      <div class="wz-bubble">4</div>
      <div class="wz-label">Finalizar</div>
    </div>
  </div>

  <form method="POST" action="<?= base_url('cotizador/store') ?>" id="wzForm" onsubmit="return deshabilitarSubmit(this)">
    <?= csrf_field() ?>
    <input type="hidden" name="estado" value="BORRADOR">

    <!-- ╔══════════════ STEP 1 — Cliente ═══════════════════════╗ -->
    <div class="wz-panel card shadow-sm active" id="step1">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-1">Datos del cliente</h5>
        <p class="text-muted small mb-4">Información del cliente para la cotización</p>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold"><i class="fas fa-user me-1 text-success"></i>Nombre del cliente <span class="text-danger">*</span></label>
            <input type="text" name="cliente_nombre" id="cliente_nombre" class="form-control form-control-lg" required minlength="2"
                   value="<?= old('cliente_nombre') ?? '' ?>" placeholder="Nombre o razón social">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="fas fa-id-card me-1 text-success"></i>RUC</label>
            <input type="text" name="cliente_ruc" id="cliente_ruc" class="form-control"
                   value="<?= old('cliente_ruc') ?? '' ?>" placeholder="RUC / Cédula">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="fas fa-phone me-1 text-success"></i>Teléfono</label>
            <input type="text" name="cliente_telefono" id="cliente_telefono" class="form-control"
                   value="<?= old('cliente_telefono') ?? '' ?>" placeholder="Número de contacto">
          </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
          <button type="button" class="btn btn-success px-4" id="btnS1Next" onclick="validarS1();irStep(2)">
            Siguiente <i class="fas fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 2 — Operación ════════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step2">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-1">Datos de la operación</h5>
        <p class="text-muted small mb-4">Producto, tipo de unidad y parámetros del viaje</p>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="fas fa-gas-pump me-1 text-success"></i>Producto <span class="text-danger">*</span></label>
            <select name="producto_id" id="producto_id" class="form-select form-select-lg">
              <?php foreach($productos as $id => $p): ?>
              <option value="<?= $id ?>" data-cost="<?= $p['costPerGallon'] ?>"><?= esc($p['name']) ?> — C$ <?= number_format($p['costPerGallon'], 2) ?>/gal</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="fas fa-truck me-1 text-success"></i>Tipo de unidad</label>
            <select name="tipo_vehiculo" id="tipo_vehiculo" class="form-select form-select-lg">
              <option value="">Seleccionar...</option>
              <?php foreach($tiposUnidad as $id => $nombre): ?>
              <option value="<?= esc($nombre) ?>"><?= esc($nombre) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold"><i class="fas fa-road me-1 text-success"></i>Distancia (km) <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
              <input type="number" name="distancia_km" id="distancia_km" class="form-control" step="any" min="0.01" value="262" required oninput="actualizarPreview()">
              <span class="input-group-text">km</span>
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold"><i class="fas fa-droplet me-1 text-success"></i>Volumen (galones) <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
              <input type="number" name="volumen_galones" id="volumen_galones" class="form-control" step="any" min="0.01" value="3000" required oninput="actualizarPreview()">
              <span class="input-group-text">gal</span>
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold"><i class="fas fa-tachometer-alt me-1 text-success"></i>Rendimiento (km/gal) <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
              <input type="number" name="rendimiento_km_galon" id="rendimiento_km_galon" class="form-control" step="any" min="0.01" value="10" required oninput="actualizarPreview()">
              <span class="input-group-text">km/gal</span>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(1)"><i class="fas fa-arrow-left me-1"></i>Atrás</button>
          <button type="button" class="btn btn-success px-4" onclick="irStep(3)">Siguiente <i class="fas fa-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 3 — Costos ═══════════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step3">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-1">Parámetros de costos</h5>
        <p class="text-muted small mb-4">Margen, chofer, viático y administración</p>
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label fw-semibold"><i class="fas fa-percentage me-1 text-success"></i>Margen objetivo (%)</label>
            <input type="number" name="margen_porcentaje" id="margen_porcentaje" class="form-control" step="any" min="0" value="15" oninput="actualizarPreview()">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold"><i class="fas fa-money-bill me-1 text-success"></i>Precio total propuesto (C$)</label>
            <input type="number" name="precio_propuesto" id="precio_propuesto" class="form-control" step="any" min="0" placeholder="0 = sugerido (producto + flete)" oninput="actualizarPreview()">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold"><i class="fas fa-user-tie me-1 text-success"></i>Chofer (%)</label>
            <input type="number" name="chofer_porcentaje" id="chofer_porcentaje" class="form-control" step="any" min="0" value="12" oninput="actualizarPreview()">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold"><i class="fas fa-wallet me-1 text-success"></i>Viático (C$)</label>
            <input type="number" name="viatico" id="viatico" class="form-control" step="any" min="0" value="330" oninput="actualizarPreview()">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold"><i class="fas fa-building me-1 text-success"></i>Admin (%)</label>
            <input type="number" name="admin_porcentaje" id="admin_porcentaje" class="form-control" step="any" min="0" value="8" oninput="actualizarPreview()">
          </div>
        </div>

        <!-- Preview rápido -->
        <div class="card mt-3 border-0 bg-light rounded-3" id="calcPreview" style="display:none;">
          <div class="card-body py-2">
            <div class="d-flex justify-content-around text-center">
              <div><div class="fw-bold fs-5 text-success" id="prevPrecio">—</div><div class="text-muted small">Precio Estimado</div></div>
              <div class="vr"></div>
              <div><div class="fw-bold fs-5" id="prevGalones">—</div><div class="text-muted small">Gal. Consumidos</div></div>
              <div class="vr"></div>
              <div><div class="fw-bold fs-5" id="prevFlete">—</div><div class="text-muted small">Flete/Galón</div></div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(2)"><i class="fas fa-arrow-left me-1"></i>Atrás</button>
          <button type="button" class="btn btn-success px-4" onclick="calcularYResumen();irStep(4)">Revisar <i class="fas fa-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 4 — Finalizar ════════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step4">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-1"><i class="fas fa-clipboard-check me-2 text-success"></i>Revisa y confirma</h5>
        <p class="text-muted small mb-4">Verifica que toda la información sea correcta antes de guardar.</p>

        <!-- Cliente -->
        <div class="rounded-3 border overflow-hidden mb-3">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold" style="background:#f0fdf9;border-bottom:1px solid #bbf7e0;">
            <i class="fas fa-user text-success"></i> Cliente
          </div>
          <div class="row g-0 p-3">
            <div class="col-12 col-sm-6 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">NOMBRE</div>
              <div class="fw-bold" id="resCliente">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">RUC</div>
              <div class="fw-semibold" id="resRuc">—</div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="text-muted" style="font-size:.72rem">TELÉFONO</div>
              <div class="fw-semibold" id="resTel">—</div>
            </div>
          </div>
        </div>

        <!-- Operación -->
        <div class="rounded-3 border overflow-hidden mb-3">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold" style="background:#f0fdf9;border-bottom:1px solid #bbf7e0;">
            <i class="fas fa-truck text-success"></i> Operación
          </div>
          <div class="row g-0 p-3">
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">PRODUCTO</div>
              <div class="fw-semibold" id="resProducto">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">TIPO UNIDAD</div>
              <div class="fw-semibold" id="resTipoUnidad">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">DISTANCIA</div>
              <div class="fw-semibold" id="resDistancia">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">VOLUMEN</div>
              <div class="fw-semibold" id="resVolumen">—</div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="text-muted" style="font-size:.72rem">RENDIMIENTO</div>
              <div class="fw-semibold" id="resRendimiento">—</div>
            </div>
          </div>
        </div>

        <!-- Desglose de costos -->
        <div class="rounded-3 border overflow-hidden mb-3">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold" style="background:#f0fdf9;border-bottom:1px solid #bbf7e0;">
            <i class="fas fa-calculator text-success"></i> Desglose de Costos
          </div>
          <div class="p-3" id="resDesglose">
            <p class="text-muted text-center mb-0">Presiona "Revisar" para ver el desglose</p>
          </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(3)"><i class="fas fa-arrow-left me-1"></i>Atrás</button>
          <button type="submit" class="btn btn-success px-4">
            <i class="fas fa-save me-1"></i> Guardar cotización
          </button>
        </div>
      </div>
    </div>
  </form>
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

    // ══ WIZARD ═══════════════════════════════════════════════════════
    window.irStep = function(n) {
        document.querySelectorAll('.wz-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('step' + n).classList.add('active');
        for (let i = 1; i <= 4; i++) {
            const ind = document.getElementById('ind' + i);
            ind.classList.remove('active','done');
            if (i < n) ind.classList.add('done');
            else if (i === n) ind.classList.add('active');
        }
        window.scrollTo({top:0, behavior:'smooth'});
    };

    window.validarS1 = function() {
        const nombre = document.getElementById('cliente_nombre');
        if (!nombre.value || nombre.value.trim().length < 2) {
            nombre.classList.add('is-invalid');
            nombre.focus();
            return;
        }
        nombre.classList.remove('is-invalid');
    };

    window.deshabilitarSubmit = function(form) {
        const btn = form.querySelector('button[type="submit"]');
        if (btn && !btn.disabled) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';
            setTimeout(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save me-1"></i> Guardar cotización'; }, 5000);
            return true;
        }
        return false;
    };

    function getFormData() {
        return {
            producto_id: document.getElementById('producto_id')?.value || 'diesel',
            cliente_nombre: document.getElementById('cliente_nombre')?.value || '',
            cliente_ruc: document.getElementById('cliente_ruc')?.value || '',
            cliente_telefono: document.getElementById('cliente_telefono')?.value || '',
            tipo_vehiculo: document.getElementById('tipo_vehiculo')?.value || '',
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

    function renderDesglose(calc) {
        const el = document.getElementById('resDesglose');
        el.innerHTML = `
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
            <div class="calc-row"><span class="lbl">Costo total flete</span><span class="val">${fmtCordobas(calc.costo_viaje)}</span></div>
            <hr class="my-2">
            <div class="calc-row"><span class="lbl">Flete sugerido</span><span class="val">${fmtCordobas(calc.flete_sugerido)}</span></div>
            <div class="calc-row"><span class="lbl">Margen real (flete)</span><span class="val">${calc.margen_porcentaje.toFixed(1)}% (${fmtCordobas(calc.margen_monto)})</span></div>
            <div class="calc-row"><span class="lbl">Flete por galón</span><span class="val">C$ ${calc.flete_por_galon.toFixed(4)}</span></div>
            <hr class="my-2">
            <div class="calc-row"><span class="lbl">Subtotal producto (${calc.volumen_galones.toFixed(0)} gal × C$${calc.producto_precio_galon.toFixed(2)})</span><span class="val">${fmtCordobas(calc.subtotal_producto)}</span></div>
            <div class="calc-row"><span class="lbl">Flete</span><span class="val">${fmtCordobas(calc.flete_final)}</span></div>
            <div class="calc-total">
                <div class="calc-row"><span class="lbl">PRECIO TOTAL</span><span class="val">${fmtCordobas(calc.precio_final)}</span></div>
                <div class="calc-row"><span class="lbl">≈ USD</span><span class="val">${fmtUSD(calc.precio_final / EXCHANGE_RATE)}</span></div>
            </div>
        `;
    }

    window.actualizarPreview = function() {
        const preview = document.getElementById('calcPreview');
        const volumen = parseFloat(document.getElementById('volumen_galones')?.value) || 0;
        const distancia = parseFloat(document.getElementById('distancia_km')?.value) || 0;
        const rendimiento = parseFloat(document.getElementById('rendimiento_km_galon')?.value) || 0;
        if (distancia > 0 && rendimiento > 0 && volumen > 0) {
            preview.style.display = 'block';
            const galones = distancia / rendimiento;
            document.getElementById('prevGalones').textContent = galones.toFixed(2) + ' gal';
            const costoComb = galones * 149.3879;
            const fleteEst = volumen > 0 ? costoComb / volumen : 0;
            document.getElementById('prevFlete').textContent = 'C$ ' + fleteEst.toFixed(2);
            document.getElementById('prevPrecio').textContent = fmtCordobas(costoComb);
        }
    };

    window.calcularYResumen = async function() {
        // Llenar resumen del step 4
        document.getElementById('resCliente').textContent = document.getElementById('cliente_nombre')?.value || '—';
        document.getElementById('resRuc').textContent = document.getElementById('cliente_ruc')?.value || '—';
        document.getElementById('resTel').textContent = document.getElementById('cliente_telefono')?.value || '—';

        const prodSelect = document.getElementById('producto_id');
        document.getElementById('resProducto').textContent = prodSelect?.selectedOptions?.[0]?.text || '—';
        document.getElementById('resTipoUnidad').textContent = document.getElementById('tipo_vehiculo')?.value || '—';
        document.getElementById('resDistancia').textContent = (parseFloat(document.getElementById('distancia_km')?.value) || 0).toLocaleString('es-NI') + ' km';
        document.getElementById('resVolumen').textContent = (parseFloat(document.getElementById('volumen_galones')?.value) || 0).toLocaleString('es-NI') + ' gal';
        document.getElementById('resRendimiento').textContent = (parseFloat(document.getElementById('rendimiento_km_galon')?.value) || 0).toFixed(2) + ' km/gal';

        // Calcular vía AJAX
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
                renderDesglose(result.calc);
            }
        } catch(err) {
            document.getElementById('resDesglose').innerHTML = '<p class="text-danger">Error al calcular: ' + err.message + '</p>';
        }
    };
})();
</script>
<?= $this->endSection() ?>
