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
.chk-card{border:1px solid #e2e8f0;border-radius:.5rem;padding:.5rem .75rem;cursor:pointer;transition:all .15s;font-size:.85rem;}
.chk-card:hover{border-color:#0d6efd;background:#f0f7ff;}
.chk-card input{accent-color:#0d6efd;}
.chk-card.checked{border-color:#0d6efd;background:#e7f1ff;}
.mat-row td{vertical-align:middle;}
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
                <div class="wz-label">Recepción</div>
            </div>
            <div class="wz-step" id="ind2">
                <div class="wz-bubble">2</div>
                <div class="wz-label">Diagnóstico</div>
            </div>
            <div class="wz-step" id="ind3">
                <div class="wz-bubble">3</div>
                <div class="wz-label">Trabajo</div>
            </div>
            <div class="wz-step" id="ind4">
                <div class="wz-bubble">4</div>
                <div class="wz-label">Resultado</div>
            </div>
        </div>

        <form id="formRealizarTrabajo" method="POST" action="<?= base_url('ordenes-trabajo/guardar-trabajo/' . $orden['id']) ?>">
            <?= csrf_field() ?>

            <!-- ═══════════════ STEP 1: Recepción del Vehículo ═══════════════ -->
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

                <!-- Datos de recepción -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-clipboard-check text-primary me-2"></i>Recepción del Vehículo
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Fecha de ingreso</label>
                                <input type="date" class="form-control" name="fecha_ingreso" value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Hora</label>
                                <input type="time" class="form-control" name="hora_ingreso" value="<?= date('H:i') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Kilometraje al ingreso</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="kilometraje_ingreso" value="<?= (int)($orden['kilometraje'] ?? 0) ?>" min="0">
                                    <span class="input-group-text">km</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Nivel de combustible</label>
                                <select class="form-select" name="nivel_combustible">
                                    <option value="">Seleccionar...</option>
                                    <option value="RESERVA">Reserva</option>
                                    <option value="1/4">1/4</option>
                                    <option value="1/2">1/2</option>
                                    <option value="3/4">3/4</option>
                                    <option value="LLENO">Lleno</option>
                                </select>
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

            <!-- ═══════════════ STEP 2: Diagnóstico ═══════════════ -->
            <div class="wz-panel" id="panel2">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-stethoscope text-danger me-2"></i>Diagnóstico Inicial — Sistemas Afectados
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <?php
                            $sistemas = ['Motor','Transmisión','Frenos','Suspensión','Dirección','Sistema eléctrico','Neumáticos','Carrocería','Aire acondicionado','Sistema de escape','Refrigeración','Combustible'];
                            foreach ($sistemas as $s): ?>
                            <div class="col-6 col-md-4">
                                <label class="chk-card d-flex align-items-center gap-2">
                                    <input type="checkbox" class="form-check-input m-0" name="diagnostico[]" value="<?= esc($s) ?>">
                                    <?= esc($s) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                            <div class="col-6 col-md-4">
                                <label class="chk-card d-flex align-items-center gap-2">
                                    <input type="checkbox" class="form-check-input m-0" id="chk_diag_otro">
                                    Otro
                                </label>
                            </div>
                        </div>
                        <div class="mb-3" id="diag_otro_wrap" style="display:none;">
                            <input type="text" class="form-control" name="diagnostico_otro" placeholder="Especifica otro sistema afectado...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-medium">Observaciones del diagnóstico</label>
                            <textarea class="form-control" name="observaciones" rows="3" placeholder="Hallazgos del diagnóstico, ruidos, códigos de error, etc..."></textarea>
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

            <!-- ═══════════════ STEP 3: Trabajo y Materiales ═══════════════ -->
            <div class="wz-panel" id="panel3">
                <!-- Trabajos realizados (checklist) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-tools text-primary me-2"></i>Trabajos Realizados
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <?php
                            $trabajos = ['Cambio de aceite y filtro','Cambio de filtro de aire','Cambio de filtro de combustible','Ajuste de frenos','Cambio de pastillas/zapatas','Reparación de motor','Reparación eléctrica','Cambio de neumáticos','Alineación y balanceo','Reparación de suspensión'];
                            foreach ($trabajos as $t): ?>
                            <div class="col-6">
                                <label class="chk-card d-flex align-items-center gap-2">
                                    <input type="checkbox" class="form-check-input m-0" name="trabajos_check[]" value="<?= esc($t) ?>">
                                    <?= esc($t) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                            <div class="col-6">
                                <label class="chk-card d-flex align-items-center gap-2">
                                    <input type="checkbox" class="form-check-input m-0" id="chk_trab_otro">
                                    Otro
                                </label>
                            </div>
                        </div>
                        <div class="mb-3" id="trab_otro_wrap" style="display:none;">
                            <input type="text" class="form-control" name="trabajos_otro" placeholder="Especifica otro trabajo realizado...">
                        </div>

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

                        <div class="mb-0">
                            <label class="form-label fw-medium">Detalle del trabajo realizado <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="trabajo_realizado" rows="4" placeholder="Describe detalladamente el trabajo realizado..." required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Materiales / Repuestos -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-boxes text-warning me-2"></i>Materiales / Repuestos Utilizados
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarMaterial()">
                            <i class="fas fa-plus me-1"></i>Agregar
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0" id="tablaMateriales">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:45%">Material</th>
                                        <th style="width:15%">Cantidad</th>
                                        <th style="width:20%">Costo Unit.</th>
                                        <th style="width:15%" class="text-end">Subtotal</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="materialesBody">
                                    <tr class="mat-empty"><td colspan="5" class="text-center text-muted py-3">Sin materiales registrados</td></tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-semibold">Total materiales:</td>
                                        <td class="text-end fw-bold" id="totalMateriales">$0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" onclick="goToStep(2)">
                        <i class="fas fa-arrow-left me-1"></i> Atrás
                    </button>
                    <button type="button" class="btn btn-primary px-4" onclick="goToStep(4)">
                        Continuar <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ═══════════════ STEP 4: Resultado Final ═══════════════ -->
            <div class="wz-panel" id="panel4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-flag-checkered text-success me-2"></i>Resultado Final
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <?php
                            $resultados = [
                                'REPARADO' => 'Reparado — Vehículo operativo',
                                'REPARACION_PARCIAL' => 'Reparación parcial — Requiere repuestos',
                                'NO_REPARADO' => 'No reparado — Derivar a taller externo',
                                'PENDIENTE' => 'Pendiente — Esperando aprobación'
                            ];
                            foreach ($resultados as $val => $label): ?>
                            <div class="col-6">
                                <label class="chk-card d-flex align-items-center gap-2">
                                    <input type="radio" class="form-check-input m-0" name="resultado_final" value="<?= $val ?>">
                                    <?= esc($label) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Kilometraje a la salida</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="kilometraje_actual" id="kilometraje_actual" value="<?= (int)($orden['kilometraje'] ?? 0) ?>" min="0">
                                    <span class="input-group-text">km</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Horas trabajadas</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="horas_trabajo" placeholder="0" min="0" step="0.5">
                                    <span class="input-group-text">hrs</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Estado del Vehículo Post-Trabajo</label>
                                <select class="form-select" name="estado_vehiculo_post">
                                    <option value="">Seleccionar estado</option>
                                    <option value="OPERATIVO">Operativo</option>
                                    <option value="REQUIERE_REVISION">Requiere Revisión</option>
                                    <option value="FUERA_DE_SERVICIO">Fuera de Servicio</option>
                                    <option value="PENDIENTE_REPUESTOS">Pendiente de Repuestos</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Observaciones finales / recomendaciones</label>
                            <textarea class="form-control" name="observaciones_finales" rows="2" placeholder="Recomendaciones para el conductor, próximos mantenimientos..."></textarea>
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

                <!-- Resumen -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-check-circle text-success me-2"></i>Revisar y Guardar
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="resumenContent" class="border rounded p-3 bg-light mb-3">
                            <p class="text-muted text-center mb-0 py-3">Revisa los datos ingresados en los pasos anteriores.</p>
                        </div>

                        <div class="form-check mb-0">
                            <input type="checkbox" class="form-check-input" id="confirmar_guardar" required>
                            <label class="form-check-label" for="confirmar_guardar">
                                <strong>Confirmo que los datos son correctos</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" onclick="goToStep(3)">
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
const materialesCatalogo = <?= json_encode(array_map(fn($m) => ['id' => $m['id'], 'nombre' => $m['nombre'], 'unidad' => $m['unidad_medida'] ?? '', 'costo' => $m['costo_unitario'] ?? 0], $materiales ?? [])) ?>;
let matIndex = 0;

function goToStep(step) {
    // Validar paso 3 antes de avanzar al 4
    if (currentStep === 3 && step === 4) {
        const trabajo = document.querySelector('textarea[name="trabajo_realizado"]').value.trim();
        if (!trabajo || trabajo.length < 10) {
            alert('La descripción del trabajo debe tener al menos 10 caracteres');
            return;
        }
    }

    document.querySelectorAll('.wz-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel' + step).classList.add('active');

    for (let i = 1; i <= 4; i++) {
        const ind = document.getElementById('ind' + i);
        ind.classList.remove('active', 'done');
        if (i < step) ind.classList.add('done');
        else if (i === step) ind.classList.add('active');
    }

    currentStep = step;
    if (step === 4) generarResumen();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===== Materiales =====
function agregarMaterial() {
    const tbody = document.getElementById('materialesBody');
    const empty = tbody.querySelector('.mat-empty');
    if (empty) empty.remove();

    const opciones = materialesCatalogo.map(m =>
        `<option value="${m.id}" data-costo="${m.costo}" data-unidad="${m.unidad}">${m.nombre}</option>`
    ).join('');

    const tr = document.createElement('tr');
    tr.className = 'mat-row';
    tr.innerHTML = `
        <td>
            <select class="form-select form-select-sm mat-select" name="materiales[${matIndex}][id_material]" onchange="onMaterialChange(this)" required>
                <option value="">Seleccionar material...</option>
                ${opciones}
            </select>
        </td>
        <td><input type="number" class="form-control form-control-sm mat-cant" name="materiales[${matIndex}][cantidad]" value="1" min="1" onchange="calcTotal()"></td>
        <td><input type="number" class="form-control form-control-sm mat-costo" name="materiales[${matIndex}][costo_unitario]" value="0" min="0" step="0.01" onchange="calcTotal()"></td>
        <td class="text-end mat-sub fw-medium">$0.00</td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarMaterial(this)"><i class="fas fa-times"></i></button></td>
    `;
    tbody.appendChild(tr);
    matIndex++;
}

function onMaterialChange(sel) {
    const opt = sel.options[sel.selectedIndex];
    const row = sel.closest('tr');
    if (opt && opt.dataset.costo) {
        row.querySelector('.mat-costo').value = opt.dataset.costo;
    }
    calcTotal();
}

function eliminarMaterial(btn) {
    btn.closest('tr').remove();
    const tbody = document.getElementById('materialesBody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = '<tr class="mat-empty"><td colspan="5" class="text-center text-muted py-3">Sin materiales registrados</td></tr>';
    }
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('#materialesBody .mat-row').forEach(row => {
        const cant = parseFloat(row.querySelector('.mat-cant').value) || 0;
        const costo = parseFloat(row.querySelector('.mat-costo').value) || 0;
        const sub = cant * costo;
        row.querySelector('.mat-sub').textContent = '$' + sub.toFixed(2);
        total += sub;
    });
    document.getElementById('totalMateriales').textContent = '$' + total.toFixed(2);
}

// ===== Checkboxes "Otro" =====
document.getElementById('chk_diag_otro').addEventListener('change', function() {
    document.getElementById('diag_otro_wrap').style.display = this.checked ? 'block' : 'none';
});
document.getElementById('chk_trab_otro').addEventListener('change', function() {
    document.getElementById('trab_otro_wrap').style.display = this.checked ? 'block' : 'none';
});

// Estilo visual para checkboxes seleccionados
document.querySelectorAll('.chk-card input').forEach(input => {
    input.addEventListener('change', function() {
        this.closest('.chk-card').classList.toggle('checked', this.checked);
    });
});

// ===== Resumen =====
function generarResumen() {
    const getChecked = name => [...document.querySelectorAll(`input[name="${name}"]:checked`)].map(i => i.value);
    const diag = getChecked('diagnostico[]');
    const diagOtro = document.querySelector('input[name="diagnostico_otro"]')?.value;
    if (diagOtro) diag.push('Otro: ' + diagOtro);

    const trab = getChecked('trabajos_check[]');
    const trabOtro = document.querySelector('input[name="trabajos_otro"]')?.value;
    if (trabOtro) trab.push('Otro: ' + trabOtro);

    const trabajo = document.querySelector('textarea[name="trabajo_realizado"]').value;
    const kmIng = document.querySelector('input[name="kilometraje_ingreso"]').value;
    const kmSal = document.getElementById('kilometraje_actual').value;
    const horas = document.querySelector('input[name="horas_trabajo"]').value || '—';
    const obs = document.querySelector('textarea[name="observaciones"]').value || '—';
    const obsFin = document.querySelector('textarea[name="observaciones_finales"]').value || '—';
    const estado = document.querySelector('select[name="estado_vehiculo_post"]').value || 'Sin cambio';
    const resultado = document.querySelector('input[name="resultado_final"]:checked')?.value || '—';
    const completado = document.getElementById('trabajo_completado').checked;
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]').value;
    const fechaFin = document.getElementById('fecha_fin').value || '—';
    const combustible = document.querySelector('select[name="nivel_combustible"]').value || '—';
    const totalMat = document.getElementById('totalMateriales').textContent;

    document.getElementById('resumenContent').innerHTML = `
        <div class="row g-2">
            <div class="col-md-4"><small class="text-muted">Km ingreso</small><div><strong>${parseInt(kmIng).toLocaleString()} km</strong></div></div>
            <div class="col-md-4"><small class="text-muted">Combustible</small><div><strong>${combustible}</strong></div></div>
            <div class="col-md-4"><small class="text-muted">Fecha inicio</small><div><strong>${fechaInicio}</strong></div></div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12"><small class="text-muted">Sistemas diagnosticados</small><div>${diag.length ? diag.join(', ') : '—'}</div></div>
            <div class="col-12"><small class="text-muted">Obs. diagnóstico</small><div>${obs}</div></div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12"><small class="text-muted">Trabajos realizados</small><div>${trab.length ? trab.join(', ') : '—'}</div></div>
            <div class="col-12"><small class="text-muted">Detalle</small><div>${trabajo}</div></div>
            <div class="col-12"><small class="text-muted">Materiales</small><div><strong>${totalMat}</strong></div></div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-md-4"><small class="text-muted">Resultado</small><div><strong>${resultado}</strong></div></div>
            <div class="col-md-4"><small class="text-muted">Km salida</small><div><strong>${parseInt(kmSal).toLocaleString()} km</strong></div></div>
            <div class="col-md-4"><small class="text-muted">Horas</small><div><strong>${horas} hrs</strong></div></div>
            <div class="col-md-6"><small class="text-muted">Estado post-trabajo</small><div><strong>${estado}</strong></div></div>
            <div class="col-md-6"><small class="text-muted">¿Completado?</small><div><strong>${completado ? 'Sí' : 'No'}</strong></div></div>
            <div class="col-12"><small class="text-muted">Obs. finales</small><div>${obsFin}</div></div>
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
