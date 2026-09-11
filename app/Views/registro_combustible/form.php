<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$valorRendimientoActual  = number_format((float)(old('rendimiento')         ?? ($registro['rendimiento']         ?? 0)), 2, '.', '');
$valorRendimientoPromedio= number_format((float)(old('rendimiento_promedio') ?? ($registro['rendimiento_promedio'] ?? 0)), 2, '.', '');
$estadoActual = old('estado') ?? ($registro['estado'] ?? 'APROBADO');
$selectedVid  = old('id_vehiculo') ?? ($registro['id_vehiculo'] ?? '');
$selectedV    = null;
foreach ($vehiculos as $v) { if ($v['id'] == $selectedVid) { $selectedV = $v; break; } }
$requiereKm   = $selectedV && ((string)($selectedV['tipo_consumo'] ?? '') !== '7');
$kmAnteriorInit = old('kilometraje_anterior') ?? ($registro['kilometraje_anterior'] ?? '');
$kmActualInit   = old('kilometraje_actual')   ?? ($registro['kilometraje_actual']   ?? '');
if ($selectedV && !$requiereKm) {
    $kmAnteriorInit = '0';
    $kmActualInit   = '0';
}
$conductores  = $conductores ?? [];
$catalogosCRC = $catalogosCRC ?? [];
$tiposConsumo = $tiposConsumo ?? [];
$nombreConsumoInterno = 'CONSUMO INTERNO';
?>
<style>
/* ── Wizard ─────────────────────────── */
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
/* Vehículo picker */
.vp-display{background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:.75rem;padding:.75rem 1rem;cursor:pointer;transition:border-color .2s;}
.vp-display:hover{border-color:#07b889;}
.vp-display.selected{border-color:#07b889;background:#f0fdf9;}
/* Vehicle card step 2 */
.v-info-card{border-radius:1rem;border:none;background:linear-gradient(135deg,#07b889 0%,#059c73 100%);color:#fff;}
.v-info-stat{background:rgba(255,255,255,.15);border-radius:.75rem;padding:.75rem;text-align:center;}
.v-info-stat .val{font-size:1.4rem;font-weight:700;}
.v-info-stat .lbl{font-size:.7rem;opacity:.85;}
.badge-activo{background:#d1fae5;color:#065f46;}
.badge-inactivo{background:#fee2e2;color:#991b1b;}
.crc-item:hover{background:#f0fdf9;}
.modal-list-item:hover{background:#f0fdf9!important;}
.modal-list-item:active{background:#d1fae5!important;}
/* Mobile: ocultar stats vehículo por defecto */
@media(max-width:575px){
  .veh-stats-row{display:none;}
  .veh-stats-row.show{display:block;}
  .btn-picker-clear{display:none!important;}
}
</style>

<!-- Page header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h4 mb-0"><i class="fas fa-gas-pump me-2 text-success"></i><?= $page_title ?></h1>
    <p class="text-muted small mb-0"><?= isset($registro['id']) ? 'Editar registro' : 'Nuevo registro de combustible' ?></p>
  </div>
  <div class="d-flex gap-2">
    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#infoTipoConsumoModal" title="Ver instructivo de tipos de consumo">
      <i class="fas fa-info-circle me-1"></i>Info
    </button>
    <a href="<?= base_url('registro-combustible') ?>" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i>Volver
    </a>
  </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<?php $validationErrors = session()->getFlashdata('errors'); if (!empty($validationErrors) && is_array($validationErrors)): ?>
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
      <div class="wz-label">Vehículo</div>
    </div>
    <div class="wz-step" id="ind2">
      <div class="wz-bubble">2</div>
      <div class="wz-label">Datos</div>
    </div>
    <div class="wz-step" id="ind3">
      <div class="wz-bubble">3</div>
      <div class="wz-label">Registro</div>
    </div>
    <div class="wz-step" id="ind4">
      <div class="wz-bubble">4</div>
      <div class="wz-label">Finalizar</div>
    </div>
  </div>

  <form method="POST" action="<?= isset($registro['id']) ? base_url('registro-combustible/update/'.$registro['id']) : base_url('registro-combustible/store') ?>" id="wzForm" onsubmit="return deshabilitarSubmit(this)">
    <?= csrf_field() ?>
    <!-- Hidden fields -->
    <input type="hidden" name="id_vehiculo"         id="id_vehiculo"          value="<?= esc($selectedVid) ?>" required>
    <input type="hidden" name="kilometraje_anterior" id="kilometraje_anterior" value="<?= esc($kmAnteriorInit) ?>">
    <input type="hidden" name="rendimiento"          id="rendimiento_input"    value="<?= $valorRendimientoActual ?>">
    <input type="hidden" name="rendimiento_promedio" id="rendimiento_promedio_input" value="<?= $valorRendimientoPromedio ?>">
    <input type="hidden" name="estado"               id="estado_input"         value="<?= esc($estadoActual) ?>">
    <input type="hidden" name="nombreCliente"        id="nombreCliente"        value="<?= esc(old('nombreCliente') ?? ($registro['nombreCliente'] ?? '')) ?>">
    <input type="hidden" name="dni"                  id="dni"                  value="<?= esc(old('dni') ?? ($registro['dni'] ?? '')) ?>">
    <input type="hidden" name="referencia1"          id="referencia1"          value="<?= esc(old('referencia1') ?? ($registro['referencia1'] ?? '')) ?>">
    <input type="hidden" name="externo" id="externo_input" value="<?= $requiereKm ? '0' : '1' ?>">

    <!-- ╔══════════════ STEP 1 — Seleccionar vehículo ══════════════╗ -->
    <div class="wz-panel card shadow-sm active" id="step1">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-1">Selecciona el vehículo</h5>
        <p class="text-muted small mb-4">Toca el campo para buscar y filtrar</p>
        <div class="input-group mb-3">
          <div class="vp-display flex-grow-1 <?= $selectedVid ? 'selected' : '' ?>" id="vehiculoDisplay" onclick="abrirModalVehiculos()">
            <i class="fas fa-car me-2 text-muted"></i>
            <span id="vehiculoDisplayText"><?= $selectedV ? esc(($selectedV['codigo_unidad'] ?? $selectedV['placa']).' — '.$selectedV['placa'].' — '.$selectedV['marca']) : 'Toca para buscar vehículo...' ?></span>
          </div>
          <button type="button" class="btn btn-success d-none d-sm-inline-flex" onclick="abrirModalVehiculos()"><i class="fas fa-search"></i></button>
          <button type="button" class="btn btn-outline-danger btn-picker-clear <?= $selectedVid ? '' : 'd-none' ?>" id="btnLimpiarVehiculo" onclick="limpiarVehiculo()"><i class="fas fa-times"></i></button>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-success px-4" id="btnS1Next" onclick="irStep(2)" <?= $selectedVid ? '' : 'disabled' ?>>
            Siguiente <i class="fas fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 2 — Datos del vehículo ═════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step2">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Datos del vehículo</h5>
        <!-- Info card -->
        <div class="v-info-card card mb-4 p-3" id="vehiculoInfoCard">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div style="width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
              <i class="fas fa-truck"></i>
            </div>
            <div class="flex-grow-1">
              <div class="fw-bold fs-5" id="vInfoNombre">—</div>
            </div>
          </div>
          <button type="button" class="d-sm-none btn btn-sm w-100 mb-2" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3)" onclick="toggleVehStats()" id="vehStatsToggle">
            <i class="fas fa-chevron-down me-1" id="vehStatsIcon"></i><span id="vehStatsLabel">Ver estadísticas</span>
          </button>
          <div id="vInfoStats" class="veh-stats-row">
            <div class="row g-2">
              <div class="col-6 col-sm-3"><div class="v-info-stat"><div class="val" id="vInfoAnio">—</div><div class="lbl">Año</div></div></div>
              <div class="col-6 col-sm-3"><div class="v-info-stat"><div class="val" id="vInfoRend">—</div><div class="lbl">Rend. Actual</div></div></div>
              <div class="col-6 col-sm-3"><div class="v-info-stat"><div class="val" id="vInfoKm">—</div><div class="lbl">Últ. km</div></div></div>
              <div class="col-6 col-sm-3"><div class="v-info-stat"><div class="val" id="vInfoCap">—</div><div class="lbl"><i class="fas fa-gas-pump text-warning me-1"></i>Capacidad</div></div></div>
            </div>
          </div>
        </div>
        <!-- Conductor -->
        <div class="mb-3">
          <label class="form-label fw-semibold"><i class="fas fa-id-card me-1 text-success"></i>Conductor <span class="text-danger">*</span></label>
          <div class="input-group">
            <div class="vp-display flex-grow-1 <?= !empty(old('nombreCliente') ?? ($registro['nombreCliente'] ?? '')) ? 'selected' : '' ?>" id="conductorDisplay" onclick="abrirModalConductores()">
              <i class="fas fa-user me-2 text-muted"></i>
              <span id="conductorDisplayText"><?= !empty(old('nombreCliente') ?? ($registro['nombreCliente'] ?? '')) ? esc(old('nombreCliente') ?? $registro['nombreCliente']) : 'Toca para buscar conductor...' ?></span>
            </div>
            <button type="button" class="btn btn-success d-none d-sm-inline-flex" onclick="abrirModalConductores()"><i class="fas fa-search"></i></button>
            <button type="button" class="btn btn-outline-danger btn-picker-clear <?= empty(old('nombreCliente') ?? ($registro['nombreCliente'] ?? '')) ? 'd-none' : '' ?>" id="btnLimpiarConductor" onclick="limpiarConductor()"><i class="fas fa-times"></i></button>
          </div>
          <div class="form-text text-muted" id="conductorDniHint"></div>
        </div>
        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(1)"><i class="fas fa-arrow-left me-1"></i>Atrás</button>
          <button type="button" class="btn btn-success px-4" onclick="irStep(3)">Siguiente <i class="fas fa-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 3 — Registro ═══════════════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step3">
      <div class="card-body p-4">
        <!-- Fecha (hidden + badge) -->
        <input type="hidden" name="fecha_registro" id="fecha_registro" value="<?= old('fecha_registro') ?? ($registro['fecha_registro'] ?? date('Y-m-d H:i:s')) ?>">
        <input type="hidden" name="medicion"       id="medicion"       value="0">
        <input type="hidden" name="combustible_tanque" id="combustible_tanque" value="<?= old('cantidad_litros') ?? ($registro['cantidad_litros'] ?? '0') ?>">
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="badge bg-light text-dark border px-3 py-2">
            <i class="fas fa-calendar-day me-1 text-success"></i>
            <span id="fechaDisplay"><?= date('d/m/Y H:i', strtotime(old('fecha_registro') ?? ($registro['fecha_registro'] ?? 'now'))) ?></span>
          </span>
          <small class="text-muted">Fecha del registro</small>
        </div>
        <h5 class="fw-bold mb-3">Datos del despacho</h5>
        <div class="row g-3">
          <div class="col-12" id="kilometraje_actual_wrapper" style="<?= $requiereKm ? '' : 'display:none;' ?>">
            <label class="form-label fw-semibold"><i class="fas fa-tachometer-alt me-1 text-success"></i>Kilometraje actual <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
              <input type="number" name="kilometraje_actual" id="kilometraje_actual" class="form-control" step="any" min="0"
                     value="<?= esc($kmActualInit) ?>" <?= $requiereKm ? 'required' : '' ?>
                     oninput="calcularRendimiento();actualizarResumen();">
              <span class="input-group-text">km</span>
            </div>
            <div class="invalid-feedback d-block d-none" id="error-kilometraje-actual">El kilometraje actual debe ser mayor que el anterior.</div>
          </div>
          <div class="col-12 col-sm-6">
            <label class="form-label fw-semibold"><i class="fas fa-gas-pump me-1 text-success"></i>Litros despachados <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
              <input type="number" name="cantidad_litros" id="cantidad_litros" class="form-control" step="any" min="0.01"
                     value="<?= old('cantidad_litros') ?? ($registro['cantidad_litros'] ?? '') ?>" required
                     oninput="actualizarConversion();actualizarCombustibleTanque();calcularRendimiento();actualizarResumen();">
              <span class="input-group-text">L</span>
            </div>
            <div class="invalid-feedback d-block d-none" id="error-cantidad-litros">Los litros despachados no pueden exceder la capacidad del tanque.</div>
          </div>
          <!-- Conversión L → Gal -->
          <div class="col-12 col-sm-6">
            <label class="form-label fw-semibold text-muted"><i class="fas fa-exchange-alt me-1"></i>Equivalencia</label>
            <div class="rounded-3 px-3 py-2 d-flex align-items-center justify-content-around" style="background:#f0fdf9;border:1.5px solid #bbf7e0;min-height:56px;">
              <div class="text-center">
                <div class="fw-bold fs-5 text-success" id="conv-litros-val">0</div>
                <div class="text-muted" style="font-size:.7rem">Litros</div>
              </div>
              <i class="fas fa-arrows-alt-h text-muted"></i>
              <div class="text-center">
                <div class="fw-bold fs-5 text-primary" id="conv-galones-val">0.000</div>
                <div class="text-muted" style="font-size:.7rem">Galones</div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold"><i class="fas fa-gas-pump me-1 text-success"></i>Apertura de Bomba</label>
            <select name="id_lectura" id="id_lectura" class="form-select form-select-lg" required>
              <option value="">Selecciona una apertura...</option>
              <?php foreach ($aperturas_pendientes ?? [] as $apertura): ?>
                <option value="<?= $apertura['id'] ?>" <?= (old('id_lectura') ?? ($registro['id_lectura'] ?? '')) == $apertura['id'] ? 'selected' : '' ?>>
                  <?= esc($apertura['codigo_tanque'] ?? '---') ?> - <?= esc($apertura['tanque'] ?? 'Tanque') ?> - <?= date('d/m/Y H:i', strtotime($apertura['fecha_apertura'])) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12" id="observaciones-wrapper">
            <label class="form-label fw-semibold" id="observaciones-label">
                <i class="fas fa-comment me-1 text-success" id="observaciones-icon"></i>
                Observaciones
                <span id="observaciones-sag-badge" class="badge bg-danger ms-2 d-none">
                    <i class="fas fa-exclamation-triangle me-1"></i>Requerido para SAG
                </span>
            </label>
            <textarea name="observaciones" id="observaciones" class="form-control" rows="3" maxlength="500"
                      placeholder="Ingresa detalles del despacho, condiciones del vehículo u observaciones relevantes..."><?= old('observaciones') ?? ($registro['observaciones'] ?? '') ?></textarea>
            <div id="observaciones-helper" class="form-text text-muted mt-1">
                <i class="fas fa-info-circle me-1"></i>El aprobador SAG leerá este campo para decidir si procede o emite nota de cancelación.
            </div>
            <div id="observaciones-bloqueado-hint" class="alert alert-danger py-2 px-3 mt-2 d-none" style="font-size:.82rem">
                <i class="fas fa-lock me-2"></i><strong>Registro ALERTA:</strong> El campo de observaciones debe justificar la anomalía detectada. El aprobador SAG evaluará este registro para autorizar o cancelar la orden.
            </div>
          </div>
        </div>

        <!-- Rendimiento summary -->
        <div class="card mt-3 border-0 bg-light rounded-3">
          <div class="card-body py-2">
            <div class="d-flex justify-content-around text-center">
              <div><div class="fw-bold fs-5" id="rendimiento-valor"><?= $valorRendimientoActual ?></div><div class="text-muted small">Rendimiento Calculado</div></div>
              <div class="vr"></div>
              <div><div class="fw-bold fs-5" id="rendimiento_promedio"><?= $valorRendimientoPromedio ?></div><div class="text-muted small">Promedio Histórico</div></div>
              <div class="vr"></div>
              <div><div class="fw-bold fs-5" id="gal-consumir-rend">—</div><div class="text-muted small">Galones a consumir s/rendimiento</div></div>
              <div class="vr"></div>
              <div><div class="fw-bold fs-5" id="km-recorridos">—</div><div class="text-muted small">Galones faltante/sobrantes</div></div>
            </div>
          </div>
        </div>

        <div id="rendimiento-alerta" class="alert alert-warning mt-3 d-none">
          <i class="fas fa-exclamation-triangle me-2"></i><span id="alerta-msg"></span>
        </div>
        <div id="rendimiento-override" class="form-check mt-2 d-none">
          <input class="form-check-input" type="checkbox" id="override_rendimiento">
          <label class="form-check-label small" for="override_rendimiento">Confirmo que el rendimiento es correcto y deseo guardar.</label>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(2)"><i class="fas fa-arrow-left me-1"></i>Atrás</button>
          <button type="button" class="btn btn-success px-4" onclick="if(!validarKilometrajeActual())return;if(!validarCapacidadTanque())return;irStep(4);actualizarResumen();">Revisar <i class="fas fa-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 4 — Finalizar ══════════════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step4">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-1"><i class="fas fa-clipboard-check me-2 text-success"></i>Revisa y confirma</h5>
        <p class="text-muted small mb-4">Verifica que toda la información sea correcta antes de guardar.</p>

        <!-- Número de recibo -->
        <div class="rounded-3 border border-success border-opacity-50 overflow-hidden mb-3">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold bg-success bg-opacity-10">
            <i class="fas fa-receipt text-success"></i> Número de recibo a generar
          </div>
          <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.72rem">RECIBO Nº</div>
            <div class="fw-bold fs-4 text-success" id="resNumeroRecibo">—</div>
          </div>
          <div class="px-3 pb-2">
            <div class="form-text text-muted small mb-0" id="mensajeNumeroRecibo">Obteniendo número desde SAG...</div>
          </div>
        </div>

        <!-- Vehículo -->
        <div class="rounded-3 border overflow-hidden mb-3">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold" style="background:#f0fdf9;border-bottom:1px solid #bbf7e0;">
            <i class="fas fa-car text-success"></i> Vehículo
          </div>
          <div class="row g-0 p-3">
            <div class="col-6 col-sm-4 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">PLACA</div>
              <div class="fw-bold fs-5" id="resPlaca">—</div>
            </div>
            <div class="col-6 col-sm-4 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">CÓDIGO UNIDAD</div>
              <div class="fw-semibold" id="resCodigoUnidad">—</div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="text-muted" style="font-size:.72rem">MARCA</div>
              <div class="fw-semibold" id="resMarca">—</div>
            </div>
          </div>
        </div>

        <!-- Conductor -->
        <div class="rounded-3 border overflow-hidden mb-3">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold" style="background:#f0fdf9;border-bottom:1px solid #bbf7e0;">
            <i class="fas fa-user text-success"></i> Conductor
          </div>
          <div class="row g-0 p-3">
            <div class="col-12 col-sm-4 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">NOMBRE</div>
              <div class="fw-bold" id="resConductor">—</div>
            </div>
            <div class="col-12 col-sm-4 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">DNI</div>
              <div class="fw-semibold" id="resDni">—</div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="text-muted" style="font-size:.72rem">CARNET</div>
              <div class="fw-semibold" id="resCarnet">—</div>
            </div>
          </div>
        </div>

        <!-- Despacho -->
        <div class="rounded-3 border overflow-hidden mb-3">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold" style="background:#f0fdf9;border-bottom:1px solid #bbf7e0;">
            <i class="fas fa-gas-pump text-success"></i> Despacho
          </div>
          <div class="row g-0 p-3">
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">FECHA</div>
              <div class="fw-semibold" id="resFecha">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">KM ANTERIOR</div>
              <div class="fw-semibold" id="resKmAnt">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">KM ACTUAL</div>
              <div class="fw-semibold" id="resKmActual">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">RECORRIDO</div>
              <div class="fw-semibold" id="resRecorrido">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">LITROS</div>
              <div class="fw-semibold" id="resLitros">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">GALONES</div>
              <div class="fw-semibold" id="resGalones">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2">
              <div class="text-muted" style="font-size:.72rem">CONSUMO REAL</div>
              <div class="fw-semibold" id="resConsumo">—</div>
            </div>
          </div>
        </div>

        <!-- Rendimiento -->
        <div class="rounded-3 border overflow-hidden mb-3">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold" style="background:#f0fdf9;border-bottom:1px solid #bbf7e0;">
            <i class="fas fa-tachometer-alt text-success"></i> Rendimiento
          </div>
          <div class="row g-0 p-3">
            <div class="col-6 col-sm-3 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">CALCULADO</div>
              <div class="fw-bold fs-5" id="resRendCalc">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">PROMEDIO HISTÓRICO</div>
              <div class="fw-semibold" id="resRendProm">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">DIFERENCIA</div>
              <div class="fw-semibold" id="resRendDiff">—</div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="text-muted" style="font-size:.72rem">ESTADO</div>
              <div id="resEstado"><span class="badge bg-secondary">—</span></div>
            </div>
          </div>
          <div class="row g-0 p-3 pt-0">
            <div class="col-6 col-sm-3 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">GALONES TEÓRICOS</div>
              <div class="fw-semibold" id="resGalTeoricos">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">DIF. GALONES</div>
              <div class="fw-semibold" id="resDiffGal">—</div>
            </div>
            <div class="col-6 col-sm-3 mb-2 mb-sm-0">
              <div class="text-muted" style="font-size:.72rem">ÍNDICE</div>
              <div class="fw-semibold" id="resIndice">—</div>
            </div>
          </div>
        </div>

        <!-- Observaciones -->
        <div class="rounded-3 border overflow-hidden mb-3" id="resObservacionesWrap">
          <div class="px-3 py-2 d-flex align-items-center gap-2 fw-semibold" style="background:#f0fdf9;border-bottom:1px solid #bbf7e0;">
            <i class="fas fa-comment text-success"></i> Observaciones
          </div>
          <div class="p-3">
            <pre class="mb-0 small" id="resObservaciones" style="white-space:pre-wrap;font-family:inherit;background:none;border:none;padding:0">—</pre>
          </div>
        </div>

        <div id="rendimiento-alerta2" class="alert alert-warning d-none">
          <i class="fas fa-exclamation-triangle me-2"></i><span id="alerta-msg2"></span>
        </div>

        <div class="d-flex justify-content-between mt-3">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(3)"><i class="fas fa-arrow-left me-1"></i>Atrás</button>
          <button type="submit" class="btn btn-success px-4" id="btn-guardar" disabled>
            <i class="fas fa-save me-1"></i><?= isset($registro['id']) ? 'Actualizar' : 'Guardar registro' ?>
          </button>
        </div>
      </div>
    </div>

  </form>
</div>

<!-- ══ MODAL VEHÍCULOS ══════════════════════════════════════════════ -->
<div class="modal fade" id="modalVehiculos" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header text-white" style="background:linear-gradient(135deg,#07b889,#059c73)">
        <h5 class="modal-title"><i class="fas fa-car me-2"></i>Seleccionar Vehículo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="p-3 border-bottom">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="filtroVehiculos" class="form-control" placeholder="Placa, marca, modelo...">
            <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('filtroVehiculos').value='';filtrarVehiculos()"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="list-group list-group-flush" id="modalVehiculosList">
          <?php foreach ($vehiculos as $v): ?>
          <button type="button" class="list-group-item list-group-item-action py-3 px-3 modal-list-item"
            data-id="<?= $v['id'] ?>"
            data-placa="<?= esc(strtolower($v['placa'])) ?>"
            data-marca="<?= esc(strtolower($v['marca'])) ?>"
            data-modelo="<?= esc(strtolower($v['modelo'])) ?>"
            data-anio="<?= esc($v['anio'] ?? '') ?>"
            data-estado="<?= esc(strtolower($v['estado'] ?? '')) ?>"
            data-rendimiento="<?= esc($v['rendimiento'] ?? '') ?>"
            data-max-combustible="<?= esc($v['max_combustible'] ?? '') ?>"
            data-codigo-unidad="<?= esc(strtolower($v['codigo_unidad'] ?? '')) ?>"
            data-externo="<?= esc($v['externo'] ?? 0) ?>"
            data-tipo-consumo="<?= esc($v['tipo_consumo'] ?? '') ?>"
            data-display="<?= esc(($v['codigo_unidad'] ?? $v['placa']).' — '.$v['placa'].' — '.$v['marca']) ?>"
            onclick="seleccionarVehiculo(this)">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="fw-bold"><?= esc($v['codigo_unidad'] ?? $v['placa']) ?> — <?= esc($v['placa']) ?></div>
                <div class="text-muted small"><?= esc($v['marca']) ?></div>
              </div>
              <div class="d-flex flex-column align-items-end gap-1 ms-2">
                <span class="badge <?= strtolower($v['estado']??'') === 'activo' ? 'badge-activo' : 'badge-inactivo' ?>"><?= esc($v['estado'] ?? 'N/A') ?></span>
                <span class="badge <?= ($v['externo'] ?? 0) == 1 ? 'bg-warning text-dark' : 'bg-info text-white' ?>"><?= ($v['externo'] ?? 0) == 1 ? 'Externo' : 'Interno' ?></span>
                <small class="text-muted">KPL: <?= esc($v['rendimiento'] ?? '—') ?></small>
              </div>
            </div>
          </button>
          <?php endforeach; ?>
        </div>
        <p id="sinResultados" class="text-center text-muted py-3 d-none">Sin resultados.</p>
      </div>
      <div class="modal-footer">
        <small class="text-muted me-auto" id="contadorVehiculos"></small>
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- ══ MODAL CONDUCTORES ════════════════════════════════════════════ -->
<div class="modal fade" id="modalConductores" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header text-white" style="background:linear-gradient(135deg,#07b889,#059c73)">
        <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Seleccionar Conductor</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="p-3 border-bottom">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="filtroConductores" class="form-control" placeholder="Nombre, DNI, código...">
            <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('filtroConductores').value='';filtrarConductores()"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <?php if (empty($conductores)): ?>
        <p class="text-center text-muted py-4">No hay conductores registrados para esta empresa.</p>
        <?php else: ?>
        <div class="list-group list-group-flush" id="modalConductoresList">
          <?php foreach ($conductores as $c): ?>
          <button type="button" class="list-group-item list-group-item-action py-3 px-3 modal-list-item"
            data-nombre="<?= esc(strtolower($c['nombre'].' '.($c['apellido']??''))) ?>"
            data-dni="<?= esc(strtolower($c['dni']??'')) ?>"
            data-codigo="<?= esc($c['codigo_consecutivo']??'') ?>"
            data-display="<?= esc($c['nombre'].' '.($c['apellido']??'')) ?>"
            data-dni-value="<?= esc($c['dni']??'') ?>"
            data-carnet="<?= esc($c['carnet']??'') ?>"
            onclick="seleccionarConductor(this)">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="fw-bold"><?= esc($c['nombre'].' '.($c['apellido']??'')) ?></div>
                <div class="text-muted small">DNI: <?= esc($c['dni']??'—') ?></div>
                <?php if (!empty($c['carnet'])): ?>
                <div class="text-muted" style="font-size:.7rem">Carnet: <?= esc($c['carnet']) ?></div>
                <?php endif; ?>
              </div>
              <div class="d-flex flex-column align-items-end gap-1 ms-2">
                <span class="badge <?= strtoupper($c['estado']??'')=='ACTIVO' ? 'badge-activo' : 'badge-inactivo' ?>"><?= esc($c['estado']??'') ?></span>
                <?php if (!empty($c['codigo_consecutivo'])): ?>
                <small class="text-muted"><?= esc($c['codigo_consecutivo']) ?></small>
                <?php endif; ?>
              </div>
            </div>
          </button>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <p id="sinResultadosConductores" class="text-center text-muted py-3 d-none">Sin resultados.</p>
      </div>
      <div class="modal-footer">
        <small class="text-muted me-auto" id="contadorConductores"></small>
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
// ══ WIZARD ═══════════════════════════════════════════════════════
function irStep(n) {
    if (n > 1 && !document.getElementById('id_vehiculo').value) {
        document.getElementById('vehiculoDisplay').style.outline = '2px solid #ef4444';
        return;
    }
    document.querySelectorAll('.wz-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('step' + n).classList.add('active');
    for (let i = 1; i <= 4; i++) {
        const ind = document.getElementById('ind' + i);
        ind.classList.remove('active','done');
        if (i < n) ind.classList.add('done');
        else if (i === n) ind.classList.add('active');
    }
    if (n === 4) cargarNumeroRecibo();
    window.scrollTo({top:0, behavior:'smooth'});
}

// ── STATS VEHÍCULO (colapsable en móvil) ─────────────────────────
function toggleVehStats() {
    const el   = document.getElementById('vInfoStats');
    const icon = document.getElementById('vehStatsIcon');
    const lbl  = document.getElementById('vehStatsLabel');
    const open = el.classList.toggle('show');
    icon.className = open ? 'fas fa-chevron-up me-1' : 'fas fa-chevron-down me-1';
    lbl.textContent = open ? 'Ocultar estadísticas' : 'Ver estadísticas';
}

// ── MODAL VEHÍCULO ────────────────────────────────────────────────
function abrirModalVehiculos() {
    new bootstrap.Modal(document.getElementById('modalVehiculos')).show();
}

function seleccionarVehiculo(row) {
    const id = row.dataset.id, display = row.dataset.display;
    const vehInput = document.getElementById('id_vehiculo');
    vehInput.value = id;
    if (row.dataset.maxCombustible) vehInput.dataset.maxCapacidad = row.dataset.maxCombustible;
    const tipoConsumo = (row.dataset.tipoConsumo || '').toString();
    const requiereKm = tipoConsumo !== '7';
    document.getElementById('externo_input').value = requiereKm ? '0' : '1';
    const kmWrapper = document.getElementById('kilometraje_actual_wrapper');
    const kmActualInput = document.getElementById('kilometraje_actual');
    if (!requiereKm) {
        kmActualInput.value = 0;
        kmActualInput.required = false;
        if (kmWrapper) kmWrapper.style.display = 'none';
    } else {
        kmActualInput.required = true;
        if (kmWrapper) kmWrapper.style.display = '';
    }
    document.getElementById('vehiculoDisplayText').textContent = display;
    document.getElementById('vehiculoDisplay').classList.add('selected');
    document.getElementById('vehiculoDisplay').style.outline = '';
    document.getElementById('btnLimpiarVehiculo').classList.remove('d-none');
    document.getElementById('btnS1Next').disabled = false;
    document.getElementById('vInfoAnio').textContent   = row.dataset.anio || '\u2014';
    document.getElementById('vInfoRend').textContent   = row.dataset.rendimiento || '\u2014';
    const cap = row.dataset.maxCombustible;
    document.getElementById('vInfoCap').textContent   = (cap && cap !== 'N/A') ? cap + ' L' : '\u2014';
    const codigoUnidad = row.dataset.codigoUnidad;
    const placa = row.dataset.placa || '';
    const marca = row.dataset.marca || '';
    // Formato: código_unidad - placa - marca (en mayúsculas)
    document.getElementById('vInfoNombre').textContent = codigoUnidad && placa && marca ? 
        (codigoUnidad + ' - ' + placa + ' - ' + marca).toUpperCase() : 
        display.toUpperCase();
    bootstrap.Modal.getInstance(document.getElementById('modalVehiculos')).hide();
    cargarKilometrajeVehiculo();
    actualizarResumen();
    setTimeout(() => irStep(2), 350);
}

function limpiarVehiculo() {
    document.getElementById('id_vehiculo').value = '';
    delete document.getElementById('id_vehiculo').dataset.maxCapacidad;
    document.getElementById('vehiculoDisplayText').textContent = 'Toca para buscar vehículo...';
    document.getElementById('vehiculoDisplay').classList.remove('selected');
    document.getElementById('btnLimpiarVehiculo').classList.add('d-none');
    document.getElementById('btnS1Next').disabled = true;
    document.getElementById('vInfoNombre').textContent = '\u2014';
    document.getElementById('vInfoAnio').textContent = '\u2014';
    document.getElementById('vInfoRend').textContent = '\u2014';
    document.getElementById('vInfoKm').textContent = '\u2014';
    document.getElementById('vInfoCap').textContent = '\u2014';
    document.getElementById('externo_input').value = '1';
    const kmWrapperReset = document.getElementById('kilometraje_actual_wrapper');
    if (kmWrapperReset) kmWrapperReset.style.display = 'none';
    const kmInputReset = document.getElementById('kilometraje_actual');
    if (kmInputReset) {
        kmInputReset.required = false;
        kmInputReset.value = '';
    }
    const kmAntReset = document.getElementById('kilometraje_anterior');
    if (kmAntReset) kmAntReset.value = '';
    calcularRendimiento();
}

function filtrarVehiculos() {
    const q    = document.getElementById('filtroVehiculos').value.toLowerCase().trim();
    const btns = document.querySelectorAll('#modalVehiculosList .modal-list-item');
    let vis = 0;
    btns.forEach(b => {
        const haystack = [b.dataset.placa,b.dataset.marca,b.dataset.modelo,b.dataset.anio,b.dataset.estado,b.dataset.codigoUnidad].join(' ').toLowerCase();
        const show = !q || haystack.includes(q);
        b.style.display = show ? '' : 'none';
        if (show) vis++;
    });
    document.getElementById('sinResultados').classList.toggle('d-none', vis > 0);
    document.getElementById('contadorVehiculos').textContent = vis + ' vehículo' + (vis !== 1 ? 's' : '');
}

// ── MODAL CONDUCTOR ───────────────────────────────────────────────
function abrirModalConductores() {
    new bootstrap.Modal(document.getElementById('modalConductores')).show();
}

function seleccionarConductor(row) {
    const display  = row.dataset.display;
    const dniVal   = row.dataset.dniValue;
    const carnet   = row.dataset.carnet || '';
    document.getElementById('nombreCliente').value              = display;
    document.getElementById('dni').value                        = dniVal;
    document.getElementById('referencia1').value                = carnet;
    document.getElementById('conductorDisplayText').textContent = display;
    document.getElementById('conductorDisplay').classList.add('selected');
    document.getElementById('btnLimpiarConductor').classList.remove('d-none');
    let hint = dniVal ? 'DNI: ' + dniVal : '';
    if (carnet) hint += (hint ? ' · ' : '') + 'Carnet: ' + carnet;
    document.getElementById('conductorDniHint').textContent = hint;
    bootstrap.Modal.getInstance(document.getElementById('modalConductores')).hide();
    calcularRendimiento();
    actualizarResumen();
}

function limpiarConductor() {
    document.getElementById('nombreCliente').value = '';
    document.getElementById('dni').value           = '';
    document.getElementById('referencia1').value   = '';
    document.getElementById('conductorDisplayText').textContent = 'Toca para buscar conductor...';
    document.getElementById('conductorDisplay').classList.remove('selected');
    document.getElementById('btnLimpiarConductor').classList.add('d-none');
    document.getElementById('conductorDniHint').textContent = '';
    calcularRendimiento();
}

function filtrarConductores() {
    const q    = document.getElementById('filtroConductores').value.toLowerCase().trim();
    const btns = document.querySelectorAll('#modalConductoresList .modal-list-item');
    let vis = 0;
    btns.forEach(b => {
        const show = !q || (b.dataset.nombre + ' ' + b.dataset.dni + ' ' + b.dataset.codigo).includes(q);
        b.style.display = show ? '' : 'none';
        if (show) vis++;
    });
    document.getElementById('sinResultadosConductores').classList.toggle('d-none', vis > 0);
    document.getElementById('contadorConductores').textContent = vis + ' conductor' + (vis !== 1 ? 'es' : '');
}

// ── RESUMEN ───────────────────────────────────────────────────────
function actualizarResumen() {
    // Vehículo
    const vDisplay = document.getElementById('vehiculoDisplayText')?.textContent || '';
    const parts    = vDisplay.split(' — ');
    const row = document.querySelector('#modalVehiculosList .modal-list-item[data-id="' + (document.getElementById('id_vehiculo')?.value||'') + '"');
    const codigoUnidad = row?.dataset?.codigoUnidad || '';
    const placa = row?.dataset?.placa || parts[1] || '';
    const marca = row?.dataset?.marca || '';
    
    document.getElementById('resPlaca').textContent   = placa || '—';
    document.getElementById('resCodigoUnidad').textContent = codigoUnidad || '—';
    document.getElementById('resMarca').textContent = marca || '—';

    // Conductor
    const conductor = document.getElementById('conductorDisplayText')?.textContent || '';
    const dni       = document.getElementById('dni')?.value || '';
    const carnet    = document.getElementById('referencia1')?.value || '';
    document.getElementById('resConductor').textContent = conductor !== 'Toca para buscar conductor...' ? conductor : '—';
    document.getElementById('resDni').textContent       = dni    ? 'DNI: '    + dni    : '—';
    document.getElementById('resCarnet').textContent    = carnet ? 'Carnet: ' + carnet : '';

    // Despacho
    const fecha   = document.getElementById('fecha_registro')?.value || '';
    const kmAnt   = parseFormattedNumber(document.getElementById('kilometraje_anterior')?.value);
    const kmAct   = parseFloat(document.getElementById('kilometraje_actual')?.value)   || 0;
    const litros  = parseFloat(document.getElementById('cantidad_litros')?.value)     || 0;
    const recorr  = kmAct - kmAnt;
    const galones = litros > 0 ? (litros * L_TO_GAL).toFixed(3) : '0.000';
    const motivo  = document.getElementById('id_tipo_motivo')?.selectedOptions?.[0]?.text || '';
    document.getElementById('resFecha').textContent    = fecha ? fecha.split('-').reverse().join('/') : '—';
    document.getElementById('resKmAnt').textContent    = kmAnt ? Number(kmAnt).toLocaleString() + ' km' : '—';
    document.getElementById('resKmActual').textContent = kmAct ? Number(kmAct).toLocaleString() + ' km' : '—';
    document.getElementById('resRecorrido').textContent= (recorr > 0) ? Number(recorr).toLocaleString() + ' km' : '—';
    document.getElementById('resLitros').textContent   = litros ? litros.toFixed(2) + ' L' : '—';
    document.getElementById('resGalones').textContent  = litros ? galones + ' gal' : '—';
    document.getElementById('resConsumo').textContent = litros ? litros.toFixed(2) + ' L' : '—';

    // Rendimiento
    const prom  = parseFormattedNumber(document.getElementById('rendimiento_promedio')?.textContent);
    const consumoGalones = litros * L_TO_GAL;
    const rnd   = (recorr > 0 && consumoGalones > 0) ? (recorr / consumoGalones) : 0;
    const galTeoricos = prom > 0 ? (recorr / prom) : 0;
    const diffGal = consumoGalones - galTeoricos;
    const indice = (prom > 0 && rnd > 0) ? ((rnd / prom) * 100) : 0;
    const estado = document.getElementById('estado_input')?.value || 'APROBADO';
    document.getElementById('resRendCalc').textContent = (rnd > 0) ? rnd.toFixed(2) + ' km/gal' : '—';
    document.getElementById('resRendCalc').className   = (rnd > 0 && prom > 0 && rnd < prom) ? 'fw-bold fs-5 text-danger' : 'fw-bold fs-5 text-success';
    document.getElementById('resRendProm').textContent = prom > 0 ? prom.toFixed(2) + ' km/gal' : '—';
    if (prom > 0 && rnd > 0) {
        const diff = ((prom - rnd) / prom * 100).toFixed(1);
        const sign = rnd < prom ? '-' : '+';
        document.getElementById('resRendDiff').textContent = `${sign}${diff}%`;
        document.getElementById('resRendDiff').className   = rnd < prom ? 'fw-semibold text-danger' : 'fw-semibold text-success';
    } else {
        document.getElementById('resRendDiff').textContent = '—';
        document.getElementById('resRendDiff').className = 'fw-semibold';
    }
    document.getElementById('resGalTeoricos').textContent = galTeoricos > 0 ? galTeoricos.toFixed(2) + ' gal' : '—';
    const resDiffGalEl = document.getElementById('resDiffGal');
    if (resDiffGalEl) {
        resDiffGalEl.textContent = (recorr > 0 && litros > 0) ? (diffGal >= 0 ? '+' : '') + diffGal.toFixed(2) + ' gal' : '—';
        resDiffGalEl.className = diffGal < -5 ? 'fw-semibold text-danger' : (diffGal > 5 ? 'fw-semibold text-warning' : 'fw-semibold text-success');
    }
    document.getElementById('resIndice').textContent = indice > 0 ? indice.toFixed(1) + '%' : '—';
    // FB-02: no revelar al operador si hay alerta; el hidden estado_input sigue valiendo BLOQUEADO
    const estHtml = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>APROBADO</span>';
    document.getElementById('resEstado').innerHTML = estHtml;

    // Observaciones
    const obs = document.getElementById('observaciones')?.value?.trim() || '';
    const obsWrap = document.getElementById('resObservacionesWrap');
    const obsEl   = document.getElementById('resObservaciones');
    if (obsWrap && obsEl) {
        if (obs) { obsEl.textContent = obs; obsWrap.classList.remove('d-none'); }
        else { obsEl.textContent = '—'; obsWrap.classList.add('d-none'); }
    }

    // FB-02: ocultar alerta duplicada en resumen
    const alerta2 = document.getElementById('rendimiento-alerta2');
    if (alerta2) alerta2.classList.add('d-none');
}

// ── NÚMERO DE RECIBO DESDE SQL SERVER ─────────────────────────────
function cargarNumeroRecibo() {
    const numEl = document.getElementById('resNumeroRecibo');
    const msgEl = document.getElementById('mensajeNumeroRecibo');
    if (!numEl) return;

    numEl.textContent = '—';
    if (msgEl) msgEl.textContent = 'Obteniendo número desde SAG...';

    fetch('<?= base_url('registro-combustible/siguiente-numero-recibo') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.numero_recibo_formateado) {
            numEl.textContent = data.numero_recibo_formateado;
            if (msgEl) msgEl.textContent = 'Número provisional generado desde SAG.';
        } else {
            if (msgEl) msgEl.textContent = 'No se pudo obtener el número de recibo.';
        }
    })
    .catch(err => {
        console.error('Error al obtener número de recibo:', err);
        if (msgEl) msgEl.textContent = 'Error de conexión con SAG para obtener el número.';
    });
}

// ── CONVERSIÓN LITROS → GALONES ───────────────────────────────────
const L_TO_GAL = 0.264172;
function actualizarConversion() {
    const lit = parseFloat(document.getElementById('cantidad_litros')?.value) || 0;
    const lEl = document.getElementById('conv-litros-val');
    const gEl = document.getElementById('conv-galones-val');
    if (lEl) lEl.textContent = lit > 0 ? lit.toFixed(2) : '0';
    if (gEl) gEl.textContent = lit > 0 ? (lit * L_TO_GAL).toFixed(3) : '0.000';
}

// ── CÁLCULOS ──────────────────────────────────────────────────────
function actualizarCombustibleTanque() {
    const lit = parseFloat(document.getElementById('cantidad_litros')?.value) || 0;
    const el  = document.getElementById('combustible_tanque');
    if (el) el.value = lit.toFixed(2);
}

function validarKilometrajeActual() {
    // Solo validar km si el campo es requerido (tipo_consumo === '6')
    const kmInput = document.getElementById('kilometraje_actual');
    if (!kmInput || kmInput.required === false) return true;
    const kmAnt = parseFormattedNumber(document.getElementById('kilometraje_anterior').value);
    const kmAct = parseFloat(kmInput?.value) || 0;
    const kmError = document.getElementById('error-kilometraje-actual');
    if (kmInput?.value !== '' && kmAct <= kmAnt) {
        kmInput.classList.add('is-invalid');
        if (kmError) {
            kmError.textContent = `El kilometraje actual (${kmAct.toLocaleString()} km) debe ser mayor que el anterior (${kmAnt.toLocaleString()} km).`;
            kmError.classList.remove('d-none');
        }
        kmInput?.focus();
        return false;
    }
    kmInput?.classList.remove('is-invalid');
    if (kmError) kmError.classList.add('d-none');
    return true;
}

function validarCapacidadTanque() {
    const lit = parseFloat(document.getElementById('cantidad_litros')?.value) || 0;
    const maxCap = parseFloat(document.getElementById('id_vehiculo')?.dataset?.maxCapacidad || 0);
    const litInput = document.getElementById('cantidad_litros');
    const litError = document.getElementById('error-cantidad-litros');
    if (maxCap > 0 && lit > maxCap) {
        litInput?.classList.add('is-invalid');
        if (litError) {
            litError.textContent = `Los ${lit.toFixed(2)} L despachados exceden la capacidad del tanque (${maxCap.toFixed(2)} L).`;
            litError.classList.remove('d-none');
        }
        litInput?.focus();
        return false;
    }
    litInput?.classList.remove('is-invalid');
    if (litError) litError.classList.add('d-none');
    return true;
}

function calcularRendimiento() {
    const kmAnt  = parseFormattedNumber(document.getElementById('kilometraje_anterior').value);
    const kmAct  = parseFloat(document.getElementById('kilometraje_actual')?.value)  || 0;
    const lit    = parseFloat(document.getElementById('cantidad_litros')?.value)     || 0;
    const consumoGalones = lit * L_TO_GAL;
    const prom   = parseFormattedNumber(document.getElementById('rendimiento_promedio')?.textContent);
    const recorr = kmAct - kmAnt;
    const rendEl = document.getElementById('rendimiento-valor');
    const galConEl = document.getElementById('gal-consumir-rend');
    const kmEl   = document.getElementById('km-recorridos');
    const alerta = document.getElementById('rendimiento-alerta');
    const alertaMsg = document.getElementById('alerta-msg');
    const overCont = document.getElementById('rendimiento-override');
    const overChk  = document.getElementById('override_rendimiento');
    const estIn  = document.getElementById('estado_input');
    const btnG   = document.getElementById('btn-guardar');
    const maxCap = parseFloat(document.getElementById('id_vehiculo')?.dataset?.maxCapacidad || 0);
    const kmInput = document.getElementById('kilometraje_actual');
    const kmError = document.getElementById('error-kilometraje-actual');
    let msg = '', estado = 'APROBADO';
    let kmErrorMsg = '';

    // Validación: KM actual debe ser mayor que KM anterior (solo si el vehículo requiere kilometraje, i.e. tipo_consumo === '6')
    const kmRequired = document.getElementById('kilometraje_actual')?.required !== false;
    if (kmRequired && kmInput?.value !== '' && kmAct <= kmAnt) {
        kmErrorMsg = `El kilometraje actual (${kmAct.toLocaleString()} km) debe ser mayor que el anterior (${kmAnt.toLocaleString()} km).`;
        kmInput.classList.add('is-invalid');
        if (kmError) { kmError.textContent = kmErrorMsg; kmError.classList.remove('d-none'); }
    } else {
        kmInput?.classList.remove('is-invalid');
        if (kmError) kmError.classList.add('d-none');
    }

    // Validación: litros despachados no pueden exceder capacidad del tanque
    const litInput = document.getElementById('cantidad_litros');
    const litError = document.getElementById('error-cantidad-litros');
    if (maxCap > 0 && lit > maxCap) {
        msg = `Los ${lit.toFixed(2)} L despachados exceden la capacidad del tanque (${maxCap.toFixed(2)} L). Verifique la cantidad o seleccione el vehículo correcto.`;
        estado = 'BLOQUEADO';
        litInput?.classList.add('is-invalid');
        if (litError) { litError.textContent = `Los ${lit.toFixed(2)} L despachados exceden la capacidad del tanque (${maxCap.toFixed(2)} L).`; litError.classList.remove('d-none'); }
    } else {
        litInput?.classList.remove('is-invalid');
        if (litError) litError.classList.add('d-none');
    }

    // Solo evaluar rendimiento si no hay error de capacidad
    if (!msg && recorr > 0 && consumoGalones > 0) {
        const rnd = recorr / consumoGalones;
        if (rendEl) { rendEl.textContent = rnd.toFixed(2); rendEl.className = 'fw-bold fs-5 ' + (prom > 0 && rnd < prom ? 'text-danger' : 'text-success'); }
        const galTeoricos = prom > 0 ? (recorr / prom) : 0;
        const diffGal = galTeoricos - consumoGalones;
        if (galConEl) galConEl.textContent = galTeoricos > 0 ? galTeoricos.toFixed(2) : '—';
        if (kmEl) {
            if (prom > 0 && recorr > 0 && lit > 0) {
                const sign = diffGal >= 0 ? '+' : '';
                const label = diffGal >= 0 ? 'sobran' : 'faltan';
                kmEl.textContent = `${sign}${diffGal.toFixed(2)} gal`;
                kmEl.className = diffGal >= 0 ? 'fw-bold fs-5 text-success' : 'fw-bold fs-5 text-danger';
            } else {
                kmEl.textContent = '—';
                kmEl.className = 'fw-bold fs-5';
            }
        }
        document.getElementById('rendimiento_input').value = rnd.toFixed(2);
        document.getElementById('rendimiento_promedio_input').value = prom.toFixed(2);
        if (rnd < 0) { msg = 'El rendimiento no puede ser negativo.'; estado = 'BLOQUEADO'; }
        // Validación: margen de tolerancia de galones (±5 galones) - se omite si es primer ingreso (km anterior es 0)
        if (!msg && prom > 0 && kmAnt > 0) {
            const galTeoricosMargen = recorr / prom;
            const diffGalMargen = consumoGalones - galTeoricosMargen;
            if (Math.abs(diffGalMargen) > 5) {
                const sign = diffGalMargen > 0 ? 'sobran' : 'faltan';
                msg = `La diferencia de galones (${Math.abs(diffGalMargen).toFixed(2)} gal ${sign}) supera el margen de tolerancia de ±5 galones. Verifique el despacho o el kilometraje.`;
                estado = 'BLOQUEADO';
            } else {
                // Parámetros aceptables - no bloquea pero muestra observación
                msg = 'Parámetros aceptables';
                // No cambiar estado, mantiene APROBADO
            }
        }
        // Solo bloquear por rendimiento bajo si la diferencia de galones está fuera de margen - se omite si es primer ingreso (km anterior es 0)
        if (!msg && prom > 0 && rnd < prom && kmAnt > 0) {
            const pct = ((prom - rnd) / prom * 100).toFixed(1);
            msg = `El rendimiento está un ${pct}% por debajo del promedio (${prom.toFixed(2)} km/gal).`;
            estado = 'BLOQUEADO';
        }
    } else {
        if (rendEl) { rendEl.textContent = '0'; rendEl.className = 'fw-bold fs-5'; }
        if (galConEl) galConEl.textContent = '—';
        if (kmEl) { kmEl.textContent = '—'; kmEl.className = 'fw-bold fs-5'; }
        document.getElementById('rendimiento_input').value = '0';
    }
    // Actualizar hidden medicion desde cantidad real despachada
    const medHidden = document.getElementById('medicion');
    if (medHidden) medHidden.value = lit;

    const bloqueo = (Boolean(msg) && msg !== 'Parámetros aceptables') || Boolean(kmErrorMsg);
    const completo = !!(document.getElementById('id_vehiculo').value &&
                        (!kmRequired || document.getElementById('kilometraje_actual')?.value) &&
                        document.getElementById('cantidad_litros')?.value &&
                        document.getElementById('fecha_registro')?.value);

    // FB-02: ocultar alertas al operador para evitar manipulación de datos
    if (alerta) { alerta.classList.add('d-none'); }
    if (overCont) { overCont.classList.add('d-none'); if (overChk) overChk.checked = false; }
    if (estIn) estIn.value = estado;
    // FB-01: permitir envío siempre que los datos básicos estén completos
    if (btnG) { btnG.disabled = !completo; }

    // Auto-generar observación estructurada para el aprobador SAG
    actualizarObservacionesBloqueado(bloqueo, msg, recorr, lit, kmAnt, kmAct, prom);
}

// Track si las observaciones fueron auto-generadas (para no sobreescribir edición manual)
let _obsAutoGenerado = false;

function actualizarObservacionesBloqueado(bloqueo, motivo, recorr, consumo, kmAnt, kmAct, prom) {
    const obsTA      = document.getElementById('observaciones');
    const obsWrapper = document.getElementById('observaciones-wrapper');
    const obsBadge   = document.getElementById('observaciones-sag-badge');
    const obsHint    = document.getElementById('observaciones-bloqueado-hint');
    const obsIcon    = document.getElementById('observaciones-icon');

    if (!obsTA) return;
    // FB-02: no mostrar UI de alerta ni auto-rellenar observaciones al operador
    return;

    // Generar observación para parámetros aceptables
    if (motivo === 'Parámetros aceptables') {
        const fecha  = document.getElementById('fecha_registro')?.value || new Date().toLocaleDateString('es-NI');
        const consumoGalones = consumo * L_TO_GAL;
        const rnd    = consumoGalones > 0 ? (recorr / consumoGalones) : 0;
        const galTeoricos = prom > 0 ? (recorr / prom) : 0;
        const diffGal = consumoGalones - galTeoricos;

        const textoAuto =
            `[VERIFICACIÓN — Parámetros aceptables]\n` +
            `Rend: ${rnd.toFixed(2)} km/gal | Prom: ${prom.toFixed(2)}\n` +
            `Km: ${Number(kmAnt).toLocaleString()}→${Number(kmAct).toLocaleString()} | Rec: ${Number(recorr).toLocaleString()} km | L: ${Number(consumo).toFixed(2)}\n` +
            `Gal teor: ${galTeoricos.toFixed(2)} | Dif gal: ${diffGal >= 0 ? '+' : ''}${diffGal.toFixed(2)} (dentro de margen ±5)\n` +
            `Fecha: ${fecha}\n` +
            `---\n` +
            `Observaciones:\n`;

        if (!obsTA.value.trim() || _obsAutoGenerado) {
            obsTA.value = textoAuto;
            _obsAutoGenerado = true;
        }
        return;
    }

    if (bloqueo) {
        const fecha  = document.getElementById('fecha_registro')?.value || new Date().toLocaleDateString('es-NI');
        const consumoGalones = consumo * L_TO_GAL;
        const rnd    = consumoGalones > 0 ? (recorr / consumoGalones) : 0;
        const diff   = prom > 0 ? (((prom - rnd) / prom) * 100).toFixed(1) : '—';
        const signDiff = prom > 0 && rnd < prom ? `-${diff}%` : `${diff}%`;
        const galTeoricos = prom > 0 ? (recorr / prom) : 0;
        const diffGal = consumoGalones - galTeoricos;
        const indice = (prom > 0 && rnd > 0) ? ((rnd / prom) * 100) : 0;

        const textoAuto =
            `[ALERTA — Supervisor requerido]\n` +
            `Motivo: ${motivo}\n` +
            `Rend: ${rnd.toFixed(2)} km/gal | Prom: ${prom.toFixed(2)} | Dif: ${signDiff}\n` +
            `Km: ${Number(kmAnt).toLocaleString()}→${Number(kmAct).toLocaleString()} | Rec: ${Number(recorr).toLocaleString()} km | L: ${Number(consumo).toFixed(2)}\n` +
            `Gal teor: ${galTeoricos.toFixed(2)} | Dif gal: ${diffGal >= 0 ? '+' : ''}${diffGal.toFixed(2)} | Idx: ${indice.toFixed(1)}%\n` +
            `Fecha: ${fecha}\n` +
            `---\n` +
            `Observaciones:\n`;

        // Solo auto-rellenar si está vacío o era auto-generado antes
        if (!obsTA.value.trim() || _obsAutoGenerado) {
            obsTA.value = textoAuto;
            _obsAutoGenerado = true;
        }

        obsTA.rows = 7;
        obsTA.classList.add('border-danger', 'border-2');
        obsTA.placeholder = 'Describe la justificación para el aprobador SAG...';
        if (obsBadge) obsBadge.classList.remove('d-none');
        if (obsHint)  obsHint.classList.remove('d-none');
        if (obsIcon)  { obsIcon.className = 'fas fa-exclamation-circle me-1 text-danger'; }
        if (obsWrapper) obsWrapper.classList.add('obs-bloqueado');

    } else {
        // Limpiar auto-generado si el usuario no escribió nada propio
        if (_obsAutoGenerado && obsTA.value.startsWith('[ALERTA')) {
            obsTA.value = '';
            _obsAutoGenerado = false;
        }
        obsTA.rows = 3;
        obsTA.classList.remove('border-danger', 'border-2');
        obsTA.placeholder = 'Ingresa detalles del despacho, condiciones del vehículo u observaciones relevantes...';
        if (obsBadge) obsBadge.classList.add('d-none');
        if (obsHint)  obsHint.classList.add('d-none');
        if (obsIcon)  { obsIcon.className = 'fas fa-comment me-1 text-success'; }
        if (obsWrapper) obsWrapper.classList.remove('obs-bloqueado');
    }

    // Si el usuario edita manualmente, marcar como no-auto para no sobreescribir
    if (!obsTA.dataset.listenerAdded) {
        obsTA.dataset.listenerAdded = 'true';
        obsTA.addEventListener('input', () => { _obsAutoGenerado = false; });
    }
}

// ── UTIL: limpiar número formateado (ej: 783.990,00 → 783990) ─────
function parseFormattedNumber(val) {
    if (val === null || val === undefined || val === '') return 0;
    const s = String(val).trim();
    if (!s) return 0;
    // Detectar formato: si hay coma y punto, determinar cuál es decimal
    const hasComma = s.includes(',');
    const hasDot   = s.includes('.');
    if (hasComma && hasDot) {
        // 1.234,56 → coma es decimal; 1,234.56 → punto es decimal
        const lastComma = s.lastIndexOf(',');
        const lastDot   = s.lastIndexOf('.');
        if (lastComma > lastDot) {
            // coma es decimal: 1.234,56 → 1234.56
            return parseFloat(s.replace(/\./g, '').replace(',', '.'));
        } else {
            // punto es decimal: 1,234.56 → 1234.56
            return parseFloat(s.replace(/,/g, ''));
        }
    } else if (hasComma && !hasDot) {
        // puede ser 1234,56 (decimal) o 1,234 (miles) — asumimos decimal si hay 1-2 dígitos después
        const parts = s.split(',');
        if (parts.length === 2 && parts[1].length <= 2) {
            return parseFloat(s.replace(',', '.'));
        }
        return parseFloat(s.replace(/,/g, ''));
    } else if (hasDot && !hasComma) {
        // puede ser 1234.56 (decimal) o 1.234 (miles)
        const parts = s.split('.');
        if (parts.length === 2 && parts[1].length <= 2) {
            return parseFloat(s);
        }
        return parseFloat(s.replace(/\./g, ''));
    }
    return parseFloat(s) || 0;
}

// ── CARGAR KILOMETRAJE ─────────────────────────────────────────────
function cargarKilometrajeVehiculo() {
    const idV = document.getElementById('id_vehiculo').value;
    if (!idV) return;
    const kmAntIn = document.getElementById('kilometraje_anterior');
    const vehInput = document.getElementById('id_vehiculo');
    const medIn   = document.getElementById('medicion');
    const promEl  = document.getElementById('rendimiento_promedio');
    const vInfoKm = document.getElementById('vInfoKm');
    const row     = document.querySelector(`#modalVehiculosList .modal-list-item[data-id='${idV}']`);
    const maxComb = row ? row.dataset.maxCombustible : '';
    if (vehInput && maxComb) vehInput.dataset.maxCapacidad = maxComb;

    fetch(`<?= base_url('registro-combustible/getKilometrajeVehiculo/') ?>${idV}`, {
        headers: {'X-Requested-With':'XMLHttpRequest'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const kmNum = parseFormattedNumber(data.kilometraje);
            const rendNum = parseFormattedNumber(data.rendimiento);
            // Lógica: requiere km si tipo_consumo !== '7'
            const tipoConsumo = (data.tipo_consumo !== undefined && data.tipo_consumo !== null) ? String(data.tipo_consumo) : (row ? String(row.dataset.tipoConsumo || '') : '');
            const requiereKm = tipoConsumo !== '7';
            document.getElementById('externo_input').value = requiereKm ? '0' : '1';
            const kmWrapper = document.getElementById('kilometraje_actual_wrapper');
            const kmActualInput = document.getElementById('kilometraje_actual');
            if (!requiereKm) {
                kmActualInput.value = 0;
                kmActualInput.required = false;
                if (kmWrapper) kmWrapper.style.display = 'none';
                kmAntIn.value = 0;
            } else {
                kmActualInput.required = true;
                if (kmWrapper) kmWrapper.style.display = '';
                kmAntIn.value = kmNum;
            }
            if (vInfoKm) vInfoKm.textContent = Number(kmNum).toLocaleString();
            const capVal = (maxComb && maxComb !== 'N/A') ? maxComb + ' L' : '\u2014';
            const capEl = document.getElementById('vInfoCap'); if (capEl) capEl.textContent = capVal;
            if (promEl) promEl.textContent = rendNum;
            document.getElementById('rendimiento_promedio_input').value = rendNum;
            if (medIn) {
                medIn.removeAttribute('readonly');
                if (maxComb && maxComb !== 'N/A' && maxComb !== '') {
                    // Almacenar capacidad en el input del vehículo para validación
                    if (vehInput) vehInput.dataset.maxCapacidad = maxComb;
                } else if (vehInput) { delete vehInput.dataset.maxCapacidad; }
            }
            actualizarCombustibleTanque();
            calcularRendimiento();
        }
    })
    .catch(() => { kmAntIn.value = ''; });
}

// ── INIT ───────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const idVInit = document.getElementById('id_vehiculo').value;
    if (idVInit) {
        const row = document.querySelector(`#modalVehiculosList .modal-list-item[data-id='${idVInit}']`);
        if (row) {
            document.getElementById('vInfoAnio').textContent   = row.dataset.anio || '\u2014';
            document.getElementById('vInfoRend').textContent   = row.dataset.rendimiento || '\u2014';
            document.getElementById('vInfoCap').textContent    = (row.dataset.maxCombustible && row.dataset.maxCombustible !== 'N/A') ? row.dataset.maxCombustible + ' L' : '\u2014';
            const codigoUnidad = row.dataset.codigoUnidad;
            const placa = row.dataset.placa || '';
            const marca = row.dataset.marca || '';
            // Formato: código_unidad - placa - marca (en mayúsculas)
            document.getElementById('vInfoNombre').textContent = codigoUnidad && placa && marca ? 
                (codigoUnidad + ' - ' + placa + ' - ' + marca).toUpperCase() : 
                row.dataset.display.toUpperCase();

            // Lógica: requiere km si tipo_consumo !== '7'
            const tipoConsumoInit = (row.dataset.tipoConsumo || '').toString();
            const requiereKmInit = tipoConsumoInit !== '7';
            document.getElementById('externo_input').value = requiereKmInit ? '0' : '1';
            const kmWrapperInit = document.getElementById('kilometraje_actual_wrapper');
            const kmActualInit = document.getElementById('kilometraje_actual');
            if (!requiereKmInit) {
                kmActualInit.value = 0;
                kmActualInit.required = false;
                if (kmWrapperInit) kmWrapperInit.style.display = 'none';
                document.getElementById('kilometraje_anterior').value = 0;
            } else {
                kmActualInit.required = true;
                if (kmWrapperInit) kmWrapperInit.style.display = '';
            }
        }
        cargarKilometrajeVehiculo();
        // Saltar al paso 3 si ya hay km o si no se requiere km (no mostrar paso de km)
        const kmValInit = document.getElementById('kilometraje_actual').value;
        const requiereKmAfter = (document.getElementById('kilometraje_actual').required !== false);
        if (!requiereKmAfter || kmValInit) irStep(3);
        else irStep(2);
    }

    ['kilometraje_actual','cantidad_litros'].forEach(id =>
        document.getElementById(id)?.addEventListener('input', calcularRendimiento));
    document.getElementById('kilometraje_actual')?.addEventListener('input', actualizarResumen);
    document.getElementById('cantidad_litros')?.addEventListener('input', actualizarCombustibleTanque);
    document.getElementById('override_rendimiento')?.addEventListener('change', calcularRendimiento);

    calcularRendimiento();
    actualizarCombustibleTanque();
    actualizarConversion();
    actualizarResumen();

    // Restaurar conductor si viene de validación fallida
    const nombreInit = document.getElementById('nombreCliente').value;
    if (nombreInit) {
        document.getElementById('conductorDisplayText').textContent = nombreInit;
        document.getElementById('conductorDisplay').classList.add('selected');
        document.getElementById('btnLimpiarConductor').classList.remove('d-none');
        const dniInit = document.getElementById('dni').value;
        if (dniInit) document.getElementById('conductorDniHint').textContent = 'DNI: ' + dniInit;
    }

    document.getElementById('filtroVehiculos').addEventListener('input', filtrarVehiculos);
    document.getElementById('modalVehiculos').addEventListener('show.bs.modal', function () {
        document.getElementById('filtroVehiculos').value = '';
        filtrarVehiculos();
        setTimeout(() => document.getElementById('filtroVehiculos').focus(), 300);
    });

    document.getElementById('filtroConductores').addEventListener('input', filtrarConductores);
    document.getElementById('modalConductores').addEventListener('show.bs.modal', function () {
        document.getElementById('filtroConductores').value = '';
        filtrarConductores();
        setTimeout(() => document.getElementById('filtroConductores').focus(), 300);
    });

});

// ── PROTECCIÓN ANTI-DOBLE-SUBMIT ─────────────────────────────────
function deshabilitarSubmit(form) {
    const btn = form.querySelector('button[type="submit"]');
    if (btn && !btn.disabled) {
        btn.disabled = true;
        btn.dataset.originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Guardando...';
    }
    return true;
}
</script>

<!-- Modal instructivo de tipos de consumo y kilometraje -->
<div class="modal fade" id="infoTipoConsumoModal" tabindex="-1" aria-labelledby="infoTipoConsumoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="infoTipoConsumoModalLabel"><i class="fas fa-info-circle me-2"></i>¿Cuándo se pide el kilometraje?</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <h6 class="fw-bold mb-3">Antes de registrar el combustible</h6>
        <ol class="ps-3 mb-3">
          <li class="mb-2">
            Ve al módulo <a href="<?= base_url('vehiculos') ?>">Vehículos</a> y edita cada vehículo.
          </li>
          <li class="mb-2">
            Asigna el <strong>tipo de consumo</strong> correspondiente en cada vehículo.
          </li>
          <li class="mb-2">
            Al registrar combustible, solo los vehículos con tipo de consumo
            <strong>"<?= esc($nombreConsumoInterno) ?>"</strong> mostrarán el campo
            <strong>Kilometraje actual</strong>. Para los demás tipos de consumo el campo no aparecerá.
          </li>
        </ol>
        <p class="mb-0 small text-muted">
          Si un vehículo no solicita kilometraje y crees que debería hacerlo, verifica que tenga asignado el tipo de consumo <strong>"<?= esc($nombreConsumoInterno) ?>"</strong>.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Entendido</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
