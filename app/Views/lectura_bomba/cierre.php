<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
/* ── Wizard ─────────────────────────── */
.wz-wrap{max-width:680px;margin:0 auto;}
.wz-steps{display:flex;gap:0;margin-bottom:2rem;}
.wz-step{flex:1;text-align:center;position:relative;}
.wz-step:not(:last-child)::after{content:'';position:absolute;top:20px;left:50%;width:100%;height:2px;background:#e2e8f0;z-index:0;}
.wz-step.done::after,.wz-step.active::after{background:#dc2626;}
.wz-bubble{width:40px;height:40px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;position:relative;z-index:1;background:#e2e8f0;color:#64748b;transition:all .3s;}
.wz-step.active .wz-bubble{background:#dc2626;color:#fff;box-shadow:0 0 0 4px #fee2e2;}
.wz-step.done .wz-bubble{background:#b91c1c;color:#fff;}
.wz-label{font-size:.75rem;margin-top:.35rem;color:#64748b;font-weight:500;}
.wz-step.active .wz-label{color:#dc2626;font-weight:700;}
.wz-panel{display:none;}.wz-panel.active{display:block;}
</style>

<!-- Page header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h4 mb-0"><i class="fas fa-lock me-2 text-danger"></i>Cierre de Bomba</h1>
    <p class="text-muted small mb-0">Registro de lectura final del contador</p>
  </div>
  <a href="<?= base_url('lectura-bomba') ?>" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-arrow-left me-1"></i>Volver
  </a>
</div>

<!-- Alertas -->
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Información de Apertura -->
<div class="card mb-4 bg-light">
    <div class="card-body p-3">
        <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Información de Apertura</h6>
        <div class="row">
            <div class="col-md-4 mb-2">
                <small class="text-muted">Centro de Costo:</small>
                <div class="fw-bold"><?= esc($apertura['nombre_centro_costo'] ?? $apertura['id_centro_costo'] ?? $apertura['codigo_centro_costo']) ?></div>
            </div>
            <div class="col-md-4 mb-2">
                <small class="text-muted">Fecha Apertura:</small>
                <div class="fw-bold"><?= date('d/m/Y H:i', strtotime($apertura['fecha_apertura'])) ?></div>
            </div>
            <div class="col-md-4 mb-2">
                <small class="text-muted">Lectura Inicial:</small>
                <div class="fw-bold"><?= number_format($apertura['lectura_inicial_litros'], 2) ?> L</div>
            </div>
        </div>
    </div>
</div>

<div class="wz-wrap">
  <!-- Step indicators -->
  <div class="wz-steps">
    <div class="wz-step active" id="ind1">
      <div class="wz-bubble">1</div>
      <div class="wz-label">Lectura</div>
    </div>
    <div class="wz-step" id="ind2">
      <div class="wz-bubble">2</div>
      <div class="wz-label">Ingresos</div>
    </div>
    <div class="wz-step" id="ind3">
      <div class="wz-bubble">3</div>
      <div class="wz-label">Notas</div>
    </div>
    <div class="wz-step" id="ind4">
      <div class="wz-bubble">4</div>
      <div class="wz-label">Resumen</div>
    </div>
  </div>

  <form method="POST" action="<?= base_url('lectura-bomba/guardar-cierre') ?>" id="wzForm">
    <?= csrf_field() ?>
    <input type="hidden" name="apertura_id" value="<?= $apertura['id'] ?>">
    <input type="hidden" name="lectura_final_galones" id="lectura_final_galones">
    <input type="hidden" name="salida_despachada" id="salida_despachada_hidden" value="<?= sprintf('%.2f', $salida_despachada ?? 0) ?>">

    <!-- ╔══════════════ STEP 1 — Lectura de Cierre ══════════════╗ -->
    <div class="wz-panel card shadow-sm active" id="step1">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-1">Lectura de Cierre</h5>
        <p class="text-muted small mb-4">Ingrese la lectura final del contador</p>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Lectura Final (Litros) <span class="text-danger">*</span></label>
                <input type="text" inputmode="decimal" class="form-control form-control-lg" id="lectura_final_litros" name="lectura_final_litros"
                       required placeholder="0.0000" oninput="soloNumeros(this); calcularGalones(); actualizarResumen()">
                <small class="text-muted">Lectura inicial: <?= number_format($apertura['lectura_inicial_litros'], 2) ?> L</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Lectura Final (Galones) <span class="text-danger">*</span></label>
                <input type="text" inputmode="decimal" class="form-control form-control-lg" id="lectura_final_galones_display"
                       required placeholder="0.0000" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Litraje Final (Litros)</label>
                <input type="text" inputmode="decimal" class="form-control form-control-lg" id="litraje_final_ltr" name="litraje_final_ltr"
                       placeholder="0.0000" oninput="soloNumeros(this); calcularLitrajeFinalGalonesDesdeLitros()">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Litraje Final (Galones)</label>
                <input type="text" inputmode="decimal" class="form-control form-control-lg" id="litraje_final_gal" name="litraje_final_gal"
                       placeholder="0.0000" oninput="soloNumeros(this); calcularLitrajeFinalLitrosDesdeGalones()">
            </div>
        </div>
        
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-danger px-4" id="btnS1Next" onclick="irStep(2)">
            Siguiente <i class="fas fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 2 — Ingresos y Salidas ═════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step2">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Ingresos y Salidas</h5>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ingreso al Tanque (Litros)</label>
                <input type="text" inputmode="decimal" class="form-control form-control-lg" id="ingreso_tanque" name="ingreso_tanque"
                       placeholder="0.0000" value="0" oninput="soloNumeros(this); validarStep2()">
                <small class="text-muted">Cantidad agregada al tanque (0 si no hubo)</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Salida Despachada (Litros)</label>
                <input type="text" inputmode="decimal" class="form-control form-control-lg" id="salida_despachada_display"
                       placeholder="0.00" readonly value="<?= number_format($salida_despachada ?? 0, 2) ?>">
                <small class="text-muted">Calculada de registros del día</small>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(1)">
            <i class="fas fa-arrow-left me-1"></i>Anterior
          </button>
          <button type="button" class="btn btn-danger px-4" id="btnS2Next" onclick="irStep(3)">
            Siguiente <i class="fas fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 3 — Observaciones ═════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step3">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Observaciones</h5>
        
        <div class="mb-4">
            <label class="form-label">Observaciones (Opcional)</label>
            <textarea class="form-control" id="observaciones_cierre" name="observaciones_cierre" rows="4" 
                      placeholder="Notas adicionales del cierre..."><?= old('observaciones_cierre') ?></textarea>
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(2)">
            <i class="fas fa-arrow-left me-1"></i>Anterior
          </button>
          <button type="button" class="btn btn-danger px-4" onclick="irStep(4)">
            Siguiente <i class="fas fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 4 — Resumen ═════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step4">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Resumen del Cierre</h5>
        
        <div class="bg-light p-3 rounded mb-4">
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Lectura Inicial:</small>
                    <div class="fw-bold"><?= number_format($apertura['lectura_inicial_litros'], 2) ?> L</div>
                </div>
                <div class="col-6">
                    <small class="text-muted">Lectura Final:</small>
                    <div class="fw-bold" id="resumenLecturaFinal">—</div>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted">Consumo del Turno:</small>
                    <div class="fw-bold text-primary" id="resumenConsumo">—</div>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted">Ingreso al Tanque:</small>
                    <div class="fw-bold" id="resumenIngreso">—</div>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted">Salida Despachada:</small>
                    <div class="fw-bold" id="resumenSalida">—</div>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted">Usuario:</small>
                    <div class="fw-bold"><?= session()->get('nombre') ?? session()->get('usuario') ?></div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(3)">
            <i class="fas fa-arrow-left me-1"></i>Anterior
          </button>
          <button type="submit" class="btn btn-danger px-4">
            <i class="fas fa-save me-1"></i>Guardar Cierre
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
const L_TO_GAL = 3.785;
const LECTURA_INICIAL = parseFloat('<?= $apertura['lectura_inicial_litros'] ?? 0 ?>') || 0;
let currentStep = 1;

console.log('CIERRE DEBUG - LECTURA_INICIAL:', LECTURA_INICIAL, 'tipo:', typeof LECTURA_INICIAL);

function soloNumeros(el) {
    // Permitir solo numeros y un punto decimal
    let v = el.value.replace(/[^0-9.]/g, '');
    // Solo un punto decimal
    const parts = v.split('.');
    if (parts.length > 2) {
        v = parts[0] + '.' + parts.slice(1).join('');
    }
    if (el.value !== v) el.value = v;
}

function parseNum(val) {
    if (typeof val !== 'string') val = String(val);
    val = val.trim().replace(/,/g, '.');
    // Si hay multiples puntos, dejar solo el primero como decimal
    const parts = val.split('.');
    if (parts.length > 2) {
        val = parts[0] + '.' + parts.slice(1).join('');
    }
    const n = parseFloat(val);
    return isNaN(n) ? 0 : n;
}

function calcularGalones() {
    const litros = parseNum(document.getElementById('lectura_final_litros').value);
    const galones = litros / L_TO_GAL;
    document.getElementById('lectura_final_galones').value = galones.toFixed(4);
    document.getElementById('lectura_final_galones_display').value = galones.toFixed(4);
}

function actualizarResumen() {
    const litros = parseNum(document.getElementById('lectura_final_litros').value);
    if (litros > 0) {
        document.getElementById('resumenLecturaFinal').textContent = litros.toFixed(2) + ' L';
        const consumo = litros - LECTURA_INICIAL;
        document.getElementById('resumenConsumo').textContent = consumo.toFixed(2) + ' L';
    }
}

function validarStep1() {
    const litros = parseNum(document.getElementById('lectura_final_litros').value);
    const isValid = litros > LECTURA_INICIAL;
    console.log('validarStep1 - litros:', litros, 'LECTURA_INICIAL:', LECTURA_INICIAL, 'isValid:', isValid);
    if (!isValid) {
        alert('La lectura final debe ser mayor a la lectura inicial (' + LECTURA_INICIAL.toFixed(2) + ' L)');
    }
    return isValid;
}

function validarStep2() {
    const ingreso = parseNum(document.getElementById('ingreso_tanque').value);
    const isValid = ingreso >= 0;
    document.getElementById('resumenIngreso').textContent = ingreso.toFixed(2) + ' L';
    document.getElementById('resumenSalida').textContent = document.getElementById('salida_despachada_display').value + ' L';
    return isValid;
}

function calcularLitrajeFinalGalonesDesdeLitros() {
    const litros = parseNum(document.getElementById('litraje_final_ltr').value);
    if (litros > 0) {
        const galones = litros / L_TO_GAL;
        document.getElementById('litraje_final_gal').value = galones.toFixed(4);
    } else {
        document.getElementById('litraje_final_gal').value = '';
    }
}

function calcularLitrajeFinalLitrosDesdeGalones() {
    const galones = parseNum(document.getElementById('litraje_final_gal').value);
    if (galones > 0) {
        const litros = galones * L_TO_GAL;
        document.getElementById('litraje_final_ltr').value = litros.toFixed(4);
    } else {
        document.getElementById('litraje_final_ltr').value = '';
    }
}

function irStep(step) {
    // Validar antes de avanzar
    if (step > currentStep) {
        if (currentStep === 1 && !validarStep1()) return;
        if (currentStep === 2 && !validarStep2()) return;
    }
    
    // Ocultar todos los paneles
    document.querySelectorAll('.wz-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.wz-step').forEach(s => s.classList.remove('active', 'done'));
    
    // Mostrar panel actual
    document.getElementById('step' + step).classList.add('active');
    
    // Actualizar indicadores
    for (let i = 1; i <= 4; i++) {
        const ind = document.getElementById('ind' + i);
        if (i < step) {
            ind.classList.add('done');
        } else if (i === step) {
            ind.classList.add('active');
        }
    }
    
    currentStep = step;
}

document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const lecturaFinal = parseNum(document.getElementById('lectura_final_litros').value);
            const ingresoTanque = parseNum(document.getElementById('ingreso_tanque').value);
            
            if (lecturaFinal <= LECTURA_INICIAL) {
                e.preventDefault();
                alert('La lectura de cierre debe ser mayor a la lectura inicial (' + LECTURA_INICIAL.toFixed(2) + ' L)');
                return;
            }
            
            if (ingresoTanque < 0) {
                e.preventDefault();
                alert('El ingreso al tanque debe ser un valor positivo');
                return;
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
