<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Realizar Orden de Trabajo #<?= $orden['codigo_consecutivo'] ?? $orden['id'] ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
.wz-wrap{max-width:820px;margin:0 auto;}
.wz-steps{display:flex;gap:0;margin-bottom:2rem;}
.wz-step{flex:1;text-align:center;position:relative;}
.wz-step:not(:last-child)::after{content:'';position:absolute;top:20px;left:50%;width:100%;height:2px;background:#e2e8f0;z-index:0;}
.wz-step.done::after,.wz-step.active::after{background:#0d6efd;}
.wz-bubble{width:40px;height:40px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;position:relative;z-index:1;background:#e2e8f0;color:#64748b;transition:all .3s;}
.wz-step.active .wz-bubble{background:#0d6efd;color:#fff;box-shadow:0 0 0 4px #cfe2ff;}
.wz-step.done .wz-bubble{background:#198754;color:#fff;}
.wz-label{font-size:.75rem;margin-top:.35rem;color:#64748b;font-weight:500;}
.wz-step.active .wz-label{color:#0d6efd;font-weight:700;}
.wz-panel{display:none;}.wz-panel.active{display:block;}
.v-info-card{border-radius:1rem;border:none;background:linear-gradient(135deg,#0d6efd 0%,#0a58ca 100%);color:#fff;}
.v-info-stat{background:rgba(255,255,255,.15);border-radius:.75rem;padding:.75rem;text-align:center;}
.v-info-stat .val{font-size:1.3rem;font-weight:700;}
.v-info-stat .lbl{font-size:.7rem;opacity:.85;}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-0">
                <i class="fas fa-tools me-2 text-primary"></i>Realizar Orden de Trabajo #<?= $orden['codigo_consecutivo'] ?? $orden['id'] ?>
            </h1>
            <p class="text-muted small mb-0"><?= esc($orden['descripcion'] ?? '') ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('ordenes-trabajo/show/' . $orden['id']) ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
            <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
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
                <div class="wz-label">Trabajo</div>
            </div>
            <div class="wz-step" id="ind3">
                <div class="wz-bubble">3</div>
                <div class="wz-label">Finalizar</div>
            </div>
        </div>

        <form id="formRealizarTrabajo" method="POST" action="<?= base_url('ordenes-trabajo/guardar-trabajo/' . $orden['id']) ?>">
            <?= csrf_field() ?>

            <!-- ═══════════════ STEP 1: Vehículo ═══════════════ -->
            <div class="wz-panel active" id="panel1">
                <!-- Card de info del vehículo -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="v-info-card p-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-truck-moving fa-2x me-3"></i>
                                <div>
                                    <h4 class="mb-0 fw-bold"><?= esc($orden['placa']) ?></h4>
                                    <small style="opacity:.85"><?= esc($orden['marca']) ?> <?= esc($orden['modelo']) ?> <?= esc($orden['anio'] ?? '') ?></small>
                                </div>
                                <span class="badge bg-light text-dark ms-auto px-3 py-2">EN PROCESO</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <div class="v-info-stat">
                                        <div class="val"><?= number_format((int)($orden['kilometraje'] ?? 0)) ?></div>
                                        <div class="lbl">Kilometraje</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="v-info-stat">
                                        <div class="val"><?= esc($orden['numero_motor'] ?? '—') ?></div>
                                        <div class="lbl">N° Motor</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="v-info-stat">
                                        <div class="val"><?= esc($orden['estado_vehiculo'] ?? '—') ?></div>
                                        <div class="lbl">Estado</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="v-info-stat">
                                        <div class="val"><?= esc($orden['nombre_asignado'] ?? '—') ?></div>
                                        <div class="lbl">Asignado a</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimos 3 registros de combustible -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-gas-pump text-success me-2"></i>Últimos Registros de Combustible
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($ultimos_combustible)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th class="text-end">Km Ant.</th>
                                        <th class="text-end">Km Act.</th>
                                        <th class="text-end">Recorrido</th>
                                        <th class="text-end">Litros</th>
                                        <th class="text-end">Rend.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimos_combustible as $rc): ?>
                                    <?php
                                        $kmAnt = (float)($rc['kilometraje_anterior'] ?? 0);
                                        $kmAct = (float)($rc['kilometraje_actual'] ?? 0);
                                        $recorrido = $kmAct - $kmAnt;
                                        $litros = (float)($rc['cantidad_litros'] ?? 0);
                                        $rend = ($recorrido > 0 && $litros > 0) ? $recorrido / $litros : 0;
                                        $tipo = strtoupper($rc['tipo'] ?? 'CONSUMO');
                                    ?>
                                    <tr>
                                        <td><div><?= date('d/m/Y', strtotime($rc['fecha_registro'])) ?></div>
                                            <small class="text-muted"><?= date('H:i', strtotime($rc['fecha_registro'])) ?></small></td>
                                        <td><span class="badge bg-<?= $tipo === 'VENTA' ? 'info' : 'success' ?>"><?= $tipo ?></span></td>
                                        <td class="text-end"><?= number_format($kmAnt, 0) ?></td>
                                        <td class="text-end"><?= number_format($kmAct, 0) ?></td>
                                        <td class="text-end fw-medium"><?= $recorrido > 0 ? number_format($recorrido, 0) : '—' ?></td>
                                        <td class="text-end"><?= number_format($litros, 2) ?></td>
                                        <td class="text-end"><?= $rend > 0 ? number_format($rend, 2) . ' km/L' : '—' ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-gas-pump fa-2x mb-2 opacity-25"></i>
                            <p class="mb-0">No hay registros de combustible para este vehículo.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary px-4" onclick="goToStep(2)">
                        Continuar <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ═══════════════ STEP 2: Trabajo ═══════════════ -->
            <div class="wz-panel" id="panel2">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-clipboard-list text-primary me-2"></i>Registro de Trabajo
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Técnico <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_tecnico" required>
                                    <option value="<?= session()->get('user_id') ?>" selected><?= session()->get('nombre') ?></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="fecha_inicio" value="<?= date('Y-m-d\TH:i') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Fecha Fin</label>
                                <input type="datetime-local" class="form-control" name="fecha_fin" id="fecha_fin">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Trabajo Realizado <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="trabajo_realizado" rows="4" placeholder="Describe detalladamente el trabajo realizado..." required></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">
                                    Kilometraje Actual
                                    <i class="fas fa-edit text-muted ms-1" title="Editable"></i>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="kilometraje_actual" id="kilometraje_actual"
                                           value="<?= (int)($orden['kilometraje'] ?? 0) ?>" min="0">
                                    <span class="input-group-text">km</span>
                                </div>
                                <small class="text-muted">Kilometraje actual del vehículo, editable si es necesario.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Horas de Trabajo</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="horas_trabajo" placeholder="0" min="0" step="0.5">
                                    <span class="input-group-text">hrs</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="2" placeholder="Observaciones, recomendaciones o notas..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Estado del Vehículo Post-Trabajo</label>
                            <select class="form-select" name="estado_vehiculo_post">
                                <option value="">Seleccionar estado</option>
                                <option value="OPERATIVO">Operativo</option>
                                <option value="REQUIERE_REVISION">Requiere Revisión</option>
                                <option value="FUERA_DE_SERVICIO">Fuera de Servicio</option>
                                <option value="PENDIENTE_REPUESTOS">Pendiente de Repuestos</option>
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="trabajo_completado" name="trabajo_completado" value="1">
                            <label class="form-check-label" for="trabajo_completado">
                                <strong>Marcar trabajo como completado</strong>
                                <small class="text-muted d-block">La orden cambiará a FINALIZADA automáticamente</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" onclick="goToStep(1)">
                        <i class="fas fa-arrow-left me-1"></i> Atrás
                    </button>
                    <button type="button" class="btn btn-primary px-4" onclick="goToStep(3)">
                        Continuar <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ═══════════════ STEP 3: Finalizar ═══════════════ -->
            <div class="wz-panel" id="panel3">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-check-circle text-success me-2"></i>Revisar y Guardar
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-light">
                                    <small class="text-muted d-block">Vehículo</small>
                                    <strong><?= esc($orden['placa']) ?> — <?= esc($orden['marca']) ?> <?= esc($orden['modelo']) ?></strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-light">
                                    <small class="text-muted d-block">Orden</small>
                                    <strong>#<?= $orden['codigo_consecutivo'] ?? $orden['id'] ?></strong>
                                    <span class="badge bg-primary ms-2">EN PROCESO</span>
                                </div>
                            </div>
                        </div>

                        <div id="resumenContent" class="border rounded p-3 bg-light mb-3">
                            <p class="text-muted text-center mb-0 py-3">Revisa los datos ingresados en el paso anterior.</p>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="confirmar_guardar" required>
                            <label class="form-check-label" for="confirmar_guardar">
                                <strong>Confirmo que los datos son correctos</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" onclick="goToStep(2)">
                        <i class="fas fa-arrow-left me-1"></i> Atrás
                    </button>
                    <button type="submit" class="btn btn-success px-4" id="btnGuardar">
                        <i class="fas fa-save me-1"></i> Guardar Trabajo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentStep = 1;

function goToStep(step) {
    // Validar paso 2 antes de avanzar
    if (currentStep === 2 && step === 3) {
        const trabajo = document.querySelector('textarea[name="trabajo_realizado"]').value.trim();
        if (!trabajo || trabajo.length < 10) {
            alert('La descripción del trabajo debe tener al menos 10 caracteres');
            return;
        }
    }

    // Ocultar panel actual
    document.querySelectorAll('.wz-panel').forEach(p => p.classList.remove('active'));
    // Mostrar nuevo panel
    document.getElementById('panel' + step).classList.add('active');

    // Actualizar indicadores
    for (let i = 1; i <= 3; i++) {
        const ind = document.getElementById('ind' + i);
        ind.classList.remove('active', 'done');
        if (i < step) ind.classList.add('done');
        else if (i === step) ind.classList.add('active');
    }

    currentStep = step;

    // Si va a paso 3, generar resumen
    if (step === 3) generarResumen();

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function generarResumen() {
    const trabajo = document.querySelector('textarea[name="trabajo_realizado"]').value;
    const km = document.getElementById('kilometraje_actual').value;
    const horas = document.querySelector('input[name="horas_trabajo"]').value || '—';
    const obs = document.querySelector('textarea[name="observaciones"]').value || '—';
    const estado = document.querySelector('select[name="estado_vehiculo_post"]').value || 'Sin cambio';
    const completado = document.getElementById('trabajo_completado').checked;
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]').value;
    const fechaFin = document.getElementById('fecha_fin').value || '—';

    document.getElementById('resumenContent').innerHTML = `
        <div class="row g-2">
            <div class="col-md-6"><small class="text-muted">Fecha Inicio</small><div><strong>${fechaInicio}</strong></div></div>
            <div class="col-md-6"><small class="text-muted">Fecha Fin</small><div><strong>${fechaFin}</strong></div></div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-md-6"><small class="text-muted">Kilometraje</small><div><strong>${parseInt(km).toLocaleString()} km</strong></div></div>
            <div class="col-md-6"><small class="text-muted">Horas de trabajo</small><div><strong>${horas} hrs</strong></div></div>
            <div class="col-md-6"><small class="text-muted">Estado post-trabajo</small><div><strong>${estado}</strong></div></div>
            <div class="col-md-6"><small class="text-muted">¿Completado?</small><div><strong>${completado ? 'Sí' : 'No'}</strong></div></div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12"><small class="text-muted">Trabajo realizado</small><div>${trabajo}</div></div>
            <div class="col-12"><small class="text-muted">Observaciones</small><div>${obs}</div></div>
        </div>
    `;
}

// Auto-completar fecha fin al marcar completado
document.getElementById('trabajo_completado').addEventListener('change', function() {
    if (this.checked) {
        const fin = document.getElementById('fecha_fin');
        if (!fin.value) fin.value = new Date().toISOString().slice(0, 16);
    }
});

// Validar confirmación antes de enviar
document.getElementById('formRealizarTrabajo').addEventListener('submit', function(e) {
    if (!document.getElementById('confirmar_guardar').checked) {
        e.preventDefault();
        alert('Debes confirmar que los datos son correctos');
        return false;
    }
    document.getElementById('btnGuardar').disabled = true;
    document.getElementById('btnGuardar').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';
});
</script>
<?= $this->endSection() ?>
