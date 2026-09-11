<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$aperturaPendiente = session()->getFlashdata('apertura_pendiente');
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
</style>

<!-- Page header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h4 mb-0"><i class="fas fa-gas-pump me-2 text-success"></i>Apertura de Bomba</h1>
    <p class="text-muted small mb-0">Registro de lectura inicial del contador</p>
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

<!-- Apertura Pendiente -->
<?php if ($aperturaPendiente): ?>
<div class="alert alert-warning border-2 mb-4">
    <div class="d-flex align-items-start gap-3">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-2">Apertura Pendiente de Cierre</h6>
            <p class="mb-2">Existe una apertura sin cerrar para este centro de costo:</p>
            <ul class="mb-3">
                <li><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($aperturaPendiente['fecha_apertura'])) ?></li>
                <li><strong>Lectura Inicial:</strong> <?= number_format($aperturaPendiente['lectura_inicial_litros'], 2) ?> L</li>
                <li><strong>Usuario:</strong> <?= esc($aperturaPendiente['usuario_apertura']) ?></li>
            </ul>
            <a href="<?= base_url('lectura-bomba/cierre/' . $aperturaPendiente['id']) ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-lock me-1"></i>Cerrar Turno Anterior
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="wz-wrap">
  <!-- Step indicators -->
  <div class="wz-steps">
    <div class="wz-step active" id="ind1">
      <div class="wz-bubble">1</div>
      <div class="wz-label">Centro</div>
    </div>
    <div class="wz-step" id="ind2">
      <div class="wz-bubble">2</div>
      <div class="wz-label">Lectura</div>
    </div>
    <div class="wz-step" id="ind3">
      <div class="wz-bubble">3</div>
      <div class="wz-label">Foto</div>
    </div>
    <div class="wz-step" id="ind4">
      <div class="wz-bubble">4</div>
      <div class="wz-label">Finalizar</div>
    </div>
  </div>

  <form method="POST" action="<?= base_url('lectura-bomba/guardar-apertura') ?>" id="wzForm" enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <!-- Hidden fields -->
    <input type="hidden" name="id_centro_costo" id="id_centro_costo_hidden" value="">
    <input type="hidden" name="lectura_inicial_galones" id="lectura_inicial_galones" value="">

    <!-- ╔══════════════ STEP 1 — Centro de Costo ══════════════╗ -->
    <div class="wz-panel card shadow-sm active" id="step1">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-1">Seleccione Bomba</h5>
        <p class="text-muted small mb-4">Elige la bomba para la lectura</p>
        
        <div class="mb-4">
            <label class="form-label">Centro de Costo <span class="text-danger">*</span></label>
            <select class="form-select form-select-lg" id="id_centro_costo" required onchange="verificarApertura(this.value); validarStep1()">
                <option value="">Seleccione...</option>
                <?php foreach ($catalogo_opciones as $id => $nombre): ?>
                <option value="<?= esc($id) ?>" <?= old('id_centro_costo') == $id ? 'selected' : '' ?>>
                    <?= esc($nombre) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-success px-4" id="btnS1Next" onclick="irStep(2)" disabled>
            Siguiente <i class="fas fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 2 — Lectura ═════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step2">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Lectura del Contador</h5>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Lectura Inicial (Litros) <span class="text-danger">*</span></label>
                <input type="text" step="0.01" class="form-control form-control-lg" id="lectura_inicial_litros" name="lectura_inicial_litros"
                       value="<?= old('lectura_inicial_litros') ?>" required placeholder="0.00" oninput="calcularGalonesDesdeLitros(); validarStep2()">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Lectura Inicial (Galones) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control form-control-lg" id="lectura_inicial_galones_display"
                       value="<?= old('lectura_inicial_galones') ?>" required placeholder="0.00" oninput="calcularLitrosDesdeGalones(); validarStep2()">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Litraje Inicial (Litros)</label>
                <input type="number" step="0.0001" class="form-control form-control-lg" id="litraje_inicial_ltr" name="litraje_inicial_ltr"
                       value="<?= old('litraje_inicial_ltr') ?>" placeholder="0.0000" oninput="calcularLitrajeGalonesDesdeLitros()">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Litraje Inicial (Galones)</label>
                <input type="number" step="0.0001" class="form-control form-control-lg" id="litraje_inicial_gal" name="litraje_inicial_gal"
                       value="<?= old('litraje_inicial_gal') ?>" placeholder="0.0000" oninput="calcularLitrajeLitrosDesdeGalones()">
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(1)">
            <i class="fas fa-arrow-left me-1"></i>Anterior
          </button>
          <button type="button" class="btn btn-success px-4" id="btnS2Next" onclick="irStep(3)" disabled>
            Siguiente <i class="fas fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 3 — Foto y Estado ═════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step3">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Foto y Estado</h5>
        
        <div class="mb-4">
            <label class="form-label">Foto del Contador <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required onchange="validarStep3()">
            <small class="text-muted">Formato: JPG, PNG. Máximo 5MB.</small>
        </div>
        
        <div class="mb-4">
            <label class="form-label">Estado <span class="text-danger">*</span></label>
            <select class="form-select form-select-lg" id="estado" name="estado" required onchange="toggleObservaciones(this.value); validarStep3()">
                <option value="">Seleccione...</option>
                <option value="normal" <?= old('estado') == 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="anomalia" <?= old('estado') == 'anomalia' ? 'selected' : '' ?>>Anomalía</option>
            </select>
        </div>
        
        <div class="mb-4" id="obsContainer" style="display:none;">
            <label class="form-label">Observaciones <span class="text-danger">*</span></label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                      placeholder="Ingrese detalles de la anomalía..." required oninput="validarStep3()"><?= old('observaciones') ?></textarea>
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(2)">
            <i class="fas fa-arrow-left me-1"></i>Anterior
          </button>
          <button type="button" class="btn btn-success px-4" id="btnS3Next" onclick="irStep(4)" disabled>
            Siguiente <i class="fas fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ╔══════════════ STEP 4 — Finalizar ═════════════════╗ -->
    <div class="wz-panel card shadow-sm" id="step4">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Resumen</h5>
        
        <div class="bg-light p-3 rounded mb-4">
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Centro de Costo:</small>
                    <div class="fw-bold" id="resumenCentro">—</div>
                </div>
                <div class="col-6">
                    <small class="text-muted">Lectura:</small>
                    <div class="fw-bold" id="resumenLectura">—</div>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted">Conversión (galones):</small>
                    <div class="fw-bold" id="resumenGalones">—</div>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted">Estado:</small>
                    <div class="fw-bold" id="resumenEstado">—</div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" onclick="irStep(3)">
            <i class="fas fa-arrow-left me-1"></i>Anterior
          </button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-1"></i>Guardar Apertura
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
const L_TO_GAL = 3.785;
let currentStep = 1;

function calcularGalones() {
    const litros = parseFloat(document.getElementById('lectura_inicial_litros').value) || 0;
    const galones = litros * L_TO_GAL;
    document.getElementById('lectura_inicial_galones').value = galones.toFixed(2);
    document.getElementById('lectura_inicial_galones_display').value = galones.toFixed(2);
}

function toggleObservaciones(estado) {
    const obsContainer = document.getElementById('obsContainer');
    const obsTextarea = document.getElementById('observaciones');
    
    if (estado === 'anomalia') {
        obsContainer.style.display = 'block';
        obsTextarea.setAttribute('required', 'required');
    } else {
        obsContainer.style.display = 'none';
        obsTextarea.removeAttribute('required');
    }
    validarStep3();
}

function validarStep1() {
    const centro = document.getElementById('id_centro_costo').value;
    const centroTexto = document.getElementById('id_centro_costo').options[document.getElementById('id_centro_costo').selectedIndex].text;
    document.getElementById('btnS1Next').disabled = !centro;
    
    if (centro) {
        document.getElementById('id_centro_costo_hidden').value = centro;
        document.getElementById('resumenCentro').textContent = centroTexto;
    }
}

function calcularGalonesDesdeLitros() {
    const litros = parseFloat(document.getElementById('lectura_inicial_litros').value) || 0;
    if (litros > 0) {
        const galones = litros / 3.785;
        document.getElementById('lectura_inicial_galones_display').value = galones.toFixed(2);
        document.getElementById('lectura_inicial_galones').value = galones.toFixed(2);
    } else {
        document.getElementById('lectura_inicial_galones_display').value = '';
        document.getElementById('lectura_inicial_galones').value = '';
    }
}

function calcularLitrosDesdeGalones() {
    const galones = parseFloat(document.getElementById('lectura_inicial_galones_display').value) || 0;
    if (galones > 0) {
        const litros = galones * 3.785;
        document.getElementById('lectura_inicial_litros').value = litros.toFixed(2);
        document.getElementById('lectura_inicial_galones').value = galones.toFixed(2);
    } else {
        document.getElementById('lectura_inicial_litros').value = '';
        document.getElementById('lectura_inicial_galones').value = '';
    }
}

function calcularLitrajeGalonesDesdeLitros() {
    const litros = parseFloat(document.getElementById('litraje_inicial_ltr').value) || 0;
    if (litros > 0) {
        const galones = litros / 3.785;
        document.getElementById('litraje_inicial_gal').value = galones.toFixed(4);
    } else {
        document.getElementById('litraje_inicial_gal').value = '';
    }
}

function calcularLitrajeLitrosDesdeGalones() {
    const galones = parseFloat(document.getElementById('litraje_inicial_gal').value) || 0;
    if (galones > 0) {
        const litros = galones * 3.785;
        document.getElementById('litraje_inicial_ltr').value = litros.toFixed(4);
    } else {
        document.getElementById('litraje_inicial_ltr').value = '';
    }
}

function validarStep2() {
    const litros = parseFloat(document.getElementById('lectura_inicial_litros').value) || 0;
    const galones = parseFloat(document.getElementById('lectura_inicial_galones_display').value) || 0;
    document.getElementById('btnS2Next').disabled = litros <= 0 && galones <= 0;
    
    if (litros > 0) {
        document.getElementById('resumenLectura').textContent = litros.toFixed(2) + ' L';
        document.getElementById('resumenGalones').textContent = galones.toFixed(2) + ' gal';
    }
}

function validarStep3() {
    const foto = document.getElementById('foto').files.length > 0;
    const estado = document.getElementById('estado').value;
    const obs = document.getElementById('observaciones').value;
    
    let valido = foto && estado;
    if (estado === 'anomalia') {
        valido = valido && obs;
    }
    
    document.getElementById('btnS3Next').disabled = !valido;
    
    if (estado) {
        document.getElementById('resumenEstado').textContent = estado === 'normal' ? 'Normal' : 'Anomalía';
    }
}

function irStep(step) {
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

function verificarApertura(codigoCentroCosto) {
    if (!codigoCentroCosto) return;
    
    fetch('<?= base_url('lectura-bomba/verificar-apertura') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'codigo_centro_costo=' + encodeURIComponent(codigoCentroCosto)
    })
    .then(response => response.json())
    .then(data => {
        if (data.has_pendiente) {
            if (confirm('Existe una apertura pendiente de cierre para este centro de costo. ¿Desea cerrarla antes de abrir una nueva?')) {
                window.location.href = '<?= base_url('lectura-bomba/cierre/') ?>' + data.apertura.id;
            } else {
                document.getElementById('codigo_centro_costo').value = '';
                validarStep1();
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?= $this->endSection() ?>
