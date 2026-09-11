<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
.card-supervisor { transition: transform .15s, box-shadow .15s; border-left: 4px solid #dc3545; }
.card-supervisor:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.12); }
.stat-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .3px; color: #6c757d; }
.stat-value { font-size: .92rem; font-weight: 600; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-shield me-2"></i><?= $page_title ?>
            </h1>
            <p class="mb-0 text-muted">Registros con anomalías pendientes de aprobación</p>
        </div>
        <div class="d-flex gap-2">
            <div id="estado-notif" class="d-flex align-items-center gap-1 px-2 py-1 rounded small" style="border:1px solid #dee2e6;background:#f8f9fa">
                <span class="text-muted">Notificaciones:</span>
                <span id="badge-notif" class="badge bg-secondary">Verificando...</span>
            </div>
            <button id="btn-activar-sonido" class="btn btn-warning btn-sm" onclick="activarSonido()">
                <i class="fas fa-volume-mute me-1"></i><span id="txt-sonido">Activar alertas de sonido</span>
            </button>
            <a href="<?= base_url('registro-combustible') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Volver a Registros
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (empty($registros)): ?>
    <div class="card shadow-sm text-center py-5">
        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
        <h5 class="text-muted">No hay registros bloqueados</h5>
        <p class="text-muted small mb-0">Todos los registros están aprobados o han sido revisados.</p>
    </div>
    <?php else: ?>

    <!-- VERSIÓN DESKTOP: tabla -->
    <div class="d-none d-md-block card shadow-sm">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-lock me-2"></i>Registros Bloqueados
            </h6>
            <span class="badge bg-danger"><?= count($registros) ?> pendiente(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="small">ID</th>
                            <th class="small">Fecha</th>
                            <th class="small">Vehículo</th>
                            <th class="small">Conductor</th>
                            <th class="small text-end">Litros</th>
                            <th class="small text-end">KM Anterior</th>
                            <th class="small text-end">KM Actual</th>
                            <th class="small text-end">Rendimiento</th>
                            <th class="small text-center">Estado</th>
                            <th class="small text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $r):
                            $recorrido = ((float)($r['kilometraje_actual'] ?? 0)) - ((float)($r['kilometraje_anterior'] ?? 0));
                            $difRend   = ((float)($r['rendimiento_promedio'] ?? 0)) - ((float)($r['rendimiento'] ?? 0));
                        ?>
                        <tr>
                            <td class="small fw-semibold">#<?= $r['id'] ?></td>
                            <td class="small"><?= date('d/m/Y H:i', strtotime($r['fecha_registro'])) ?></td>
                            <td class="small">
                                <div class="fw-semibold"><?= esc($r['placa'] ?? '—') ?></div>
                                <div class="text-muted" style="font-size:.7rem"><?= esc($r['marca'] ?? '') ?> <?= esc($r['modelo'] ?? '') ?></div>
                            </td>
                            <td class="small">
                                <?= esc($r['nombreCliente'] ?: '—') ?>
                                <?php if ($r['dni']): ?>
                                <div class="text-muted" style="font-size:.7rem">DNI: <?= esc($r['dni']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="small text-end fw-semibold"><?= number_format((float)($r['cantidad_litros'] ?? 0), 2) ?> L</td>
                            <td class="small text-end"><?= number_format((float)($r['kilometraje_anterior'] ?? 0), 2) ?></td>
                            <td class="small text-end"><?= number_format((float)($r['kilometraje_actual'] ?? 0), 2) ?></td>
                            <td class="small text-end">
                                <?= number_format((float)($r['rendimiento'] ?? 0), 2) ?>
                                <div class="text-muted" style="font-size:.7rem">Prom: <?= number_format((float)($r['rendimiento_promedio'] ?? 0), 2) ?></div>
                            </td>
                            <td class="small text-center">
                                <span class="badge bg-danger"><i class="fas fa-lock me-1"></i>BLOQUEADO</span>
                            </td>
                            <td class="small text-center">
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetalle<?= $r['id'] ?>">
                                    <i class="fas fa-eye me-1"></i>Ver detalle
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- VERSIÓN MÓVIL: cards -->
    <div class="d-md-none row g-3">
        <?php foreach ($registros as $r):
            $recorrido = ((float)($r['kilometraje_actual'] ?? 0)) - ((float)($r['kilometraje_anterior'] ?? 0));
            $difRend   = ((float)($r['rendimiento_promedio'] ?? 0)) - ((float)($r['rendimiento'] ?? 0));
        ?>
        <div class="col-12">
            <div class="card card-supervisor shadow-sm h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-danger"><i class="fas fa-lock me-1"></i>BLOQUEADO</span>
                        <span class="text-muted small">#<?= $r['id'] ?></span>
                    </div>
                    <h6 class="fw-bold mb-1">
                        <i class="fas fa-car me-1 text-primary"></i><?= esc($r['placa'] ?? 'Sin placa') ?>
                    </h6>
                    <p class="text-muted small mb-2"><?= esc($r['marca'] ?? '') ?> <?= esc($r['modelo'] ?? '') ?></p>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="stat-label">Litros</div>
                            <div class="stat-value text-primary"><?= number_format((float)($r['cantidad_litros'] ?? 0), 2) ?> L</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-label">Recorrido</div>
                            <div class="stat-value"><?= number_format($recorrido, 2) ?> km</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-label">Rendimiento</div>
                            <div class="stat-value <?= $difRend > 5 ? 'text-danger' : 'text-warning' ?>"><?= number_format((float)($r['rendimiento'] ?? 0), 2) ?></div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalDetalle<?= $r['id'] ?>">
                            <i class="fas fa-eye me-1"></i>Ver detalle completo
                        </button>
                        <div class="d-flex gap-2">
                            <form method="POST" action="<?= base_url('registro-combustible/aprobar/' . $r['id']) ?>" class="flex-fill" onsubmit="return confirm('¿Aprobar y enviar a SAG el registro #<?= $r['id'] ?>?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-check me-1"></i>Aprobar
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-danger btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#modalRechazar<?= $r['id'] ?>">
                                <i class="fas fa-times me-1"></i>Rechazar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white small text-muted">
                    <i class="far fa-calendar me-1"></i><?= date('d/m/Y H:i', strtotime($r['fecha_registro'])) ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- MODALES compartidos (desktop + móvil) -->
    <?php foreach ($registros as $r):
        $recorrido = ((float)($r['kilometraje_actual'] ?? 0)) - ((float)($r['kilometraje_anterior'] ?? 0));
        $difRend   = ((float)($r['rendimiento_promedio'] ?? 0)) - ((float)($r['rendimiento'] ?? 0));
    ?>
    <!-- Modal Detalle Completo -->
    <div class="modal fade" id="modalDetalle<?= $r['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title mb-0"><i class="fas fa-file-invoice text-primary me-2"></i>Detalle del Registro #<?= $r['id'] ?></h5>
                        <span class="badge bg-danger"><i class="fas fa-lock me-1"></i>BLOQUEADO — Requiere validación</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-car me-2"></i>Vehículo</h6>
                            <div class="mb-2"><span class="text-muted small">Placa:</span> <strong class="ms-1"><?= esc($r['placa'] ?? '—') ?></strong></div>
                            <div class="mb-2"><span class="text-muted small">Marca / Modelo:</span> <strong class="ms-1"><?= esc($r['marca'] ?? '—') ?> <?= esc($r['modelo'] ?? '') ?></strong></div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-user me-2"></i>Conductor</h6>
                            <div class="mb-2"><span class="text-muted small">Nombre:</span> <strong class="ms-1"><?= esc($r['nombreCliente'] ?: '—') ?></strong></div>
                            <div class="mb-2"><span class="text-muted small">DNI:</span> <strong class="ms-1"><?= esc($r['dni'] ?: '—') ?></strong></div>
                        </div>
                        <div class="col-12"><hr class="my-1"></div>
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-gas-pump me-2"></i>Despacho</h6>
                            <div class="mb-2"><span class="text-muted small">Fecha:</span> <strong class="ms-1"><?= date('d/m/Y H:i', strtotime($r['fecha_registro'])) ?></strong></div>
                            <div class="mb-2"><span class="text-muted small">Litros:</span> <strong class="ms-1"><?= number_format((float)($r['cantidad_litros'] ?? 0), 2) ?> L</strong></div>
                            <div class="mb-2"><span class="text-muted small">Tipo:</span> <strong class="ms-1"><?= esc($r['tipo'] ?? 'CONSUMO') ?></strong></div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-tachometer-alt me-2"></i>Kilometraje</h6>
                            <div class="mb-2"><span class="text-muted small">Anterior:</span> <strong class="ms-1"><?= number_format((float)($r['kilometraje_anterior'] ?? 0), 2) ?> km</strong></div>
                            <div class="mb-2"><span class="text-muted small">Actual:</span> <strong class="ms-1"><?= number_format((float)($r['kilometraje_actual'] ?? 0), 2) ?> km</strong></div>
                            <div class="mb-2"><span class="text-muted small">Recorrido:</span> <strong class="ms-1"><?= number_format($recorrido, 2) ?> km</strong></div>
                        </div>
                        <div class="col-12"><hr class="my-1"></div>
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-chart-line me-2"></i>Rendimiento</h6>
                            <div class="mb-2"><span class="text-muted small">Calculado:</span> <strong class="ms-1 <?= $difRend > 5 ? 'text-danger' : 'text-warning' ?>"><?= number_format((float)($r['rendimiento'] ?? 0), 2) ?> km/gal</strong></div>
                            <div class="mb-2"><span class="text-muted small">Promedio histórico:</span> <strong class="ms-1"><?= number_format((float)($r['rendimiento_promedio'] ?? 0), 2) ?> km/gal</strong></div>
                            <div class="mb-2"><span class="text-muted small">Diferencia:</span> <strong class="ms-1 <?= $difRend > 5 ? 'text-danger' : 'text-warning' ?>"><?= number_format($difRend, 2) ?> km/gal</strong></div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-sticky-note me-2"></i>Observaciones del Operador</h6>
                            <div class="p-2 bg-light rounded border">
                                <?= nl2br(esc($r['observaciones'] ?: 'Sin observaciones')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <form method="POST" action="<?= base_url('registro-combustible/aprobar/' . $r['id']) ?>" class="d-inline" onsubmit="return confirm('¿Aprobar y enviar a SAG el registro #<?= $r['id'] ?>?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Aprobar</button>
                    </form>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalRechazar<?= $r['id'] ?>">
                        <i class="fas fa-times me-1"></i>Rechazar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Rechazar -->
    <div class="modal fade" id="modalRechazar<?= $r['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="<?= base_url('registro-combustible/rechazar/' . $r['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-times-circle text-danger me-2"></i>Rechazar Registro #<?= $r['id'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Al rechazar, el operador deberá crear una nueva orden. Indique el motivo.
                        </div>
                        <div class="mb-3">
                            <label for="motivo_rechazo_<?= $r['id'] ?>" class="form-label fw-semibold">Motivo de rechazo <span class="text-danger">*</span></label>
                            <textarea name="motivo_rechazo" id="motivo_rechazo_<?= $r['id'] ?>" class="form-control" rows="3" maxlength="500" required placeholder="Ej: No cuadra con el sistema, kilometraje sospechoso, litros exceden capacidad..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-times me-1"></i>Confirmar Rechazo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
(function() {
    'use strict';
    const POLL_INTERVAL = 60_000; // 1 minuto
    const endpoint = '<?= base_url('registro-combustible/getBloqueadosCount') ?>';
    const SOUND_URL  = '<?= base_url('public/assets/audio/notificacion.mp3') ?>';
    const SUPERVISOR_URL = '<?= base_url('registro-combustible/supervisor') ?>';

    let sonidoActivado = false;
    let audioCtx = null;
    let audioBuffer = null;

    function actualizarEstadoNotif() {
        const badge = document.getElementById('badge-notif');
        if (!badge) return;
        if (!('Notification' in window)) {
            badge.className = 'badge bg-dark';
            badge.textContent = 'No soportado';
            return;
        }
        if (Notification.permission === 'granted') {
            badge.className = 'badge bg-success';
            badge.textContent = 'Permitidas';
        } else if (Notification.permission === 'denied') {
            badge.className = 'badge bg-danger';
            badge.textContent = 'Bloqueadas';
        } else {
            badge.className = 'badge bg-warning text-dark';
            badge.textContent = 'Pendiente';
        }
    }

    window.activarSonido = async function() {
        try {
            // 1. Solicitar permiso de notificación del navegador
            if ('Notification' in window) {
                const perm = await Notification.requestPermission();
                actualizarEstadoNotif();
                if (perm !== 'granted') {
                    alert('Las notificaciones del navegador están bloqueadas o denegadas.\n\nEn Windows, vaya a Configuración → Sistema → Notificaciones y asegúrese de que su navegador (Chrome/Edge) permita notificaciones.\n\nSin notificaciones, seguirá viendo los toasts en pantalla.');
                }
            }

            // 2. Cargar audio
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            audioCtx = new AudioCtx();
            const res = await fetch(SOUND_URL);
            const arrayBuffer = await res.arrayBuffer();
            audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);

            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            // Prueba de sonido a volumen completo
            const source = audioCtx.createBufferSource();
            source.buffer = audioBuffer;
            source.connect(audioCtx.destination);
            source.start(0);

            sonidoActivado = true;

            const btn = document.getElementById('btn-activar-sonido');
            const txt = document.getElementById('txt-sonido');
            if (btn) { btn.classList.replace('btn-warning', 'btn-success'); btn.disabled = true; }
            if (txt) txt.textContent = 'Alertas activadas';

            // Notificación de prueba inmediata
            if ('Notification' in window && Notification.permission === 'granted') {
                const testNotif = new Notification('GMV — Supervisor', {
                    body: 'Notificaciones activadas correctamente. Toque esta alerta para abrir la bandeja del supervisor.',
                    icon: '<?= base_url('favicon.ico') ?>'
                });
                testNotif.onclick = function() {
                    window.location.href = SUPERVISOR_URL;
                    testNotif.close();
                };
            }
        } catch (e) {
            console.error('[Supervisor] Error activando sonido:', e);
            alert('No se pudo cargar el audio. Verifique que el archivo exista en: ' + SOUND_URL);
        }
    };

    function playAlert() {
        if (!sonidoActivado || !audioCtx || !audioBuffer) return;
        try {
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            const source = audioCtx.createBufferSource();
            source.buffer = audioBuffer;
            source.connect(audioCtx.destination);
            source.start(0);
        } catch (e) {}
    }

    function showToast(count) {
        const container = document.getElementById('toast-container');
        if (!container) return;
        container.innerHTML = '';
        const toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-bg-warning border-0 show mb-2';
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-exclamation-triangle me-2"></i><strong>Hay ${count} orden${count > 1 ? 'es' : ''} pendiente${count > 1 ? 's' : ''} de validación</strong>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>`;
        container.appendChild(toastEl);
        setTimeout(() => { if (toastEl) toastEl.remove(); }, 6000);
    }

    function sendBrowserNotification(count) {
        if ('Notification' in window && Notification.permission === 'granted') {
            const notif = new Notification('GMV — Supervisor', {
                body: `Hay ${count} orden${count > 1 ? 'es' : ''} pendiente${count > 1 ? 's' : ''} de validación`,
                icon: '<?= base_url('favicon.ico') ?>'
            });
            notif.onclick = function() {
                window.location.href = SUPERVISOR_URL;
                window.focus();
                notif.close();
            };
        }
    }

    async function checkBloqueados() {
        try {
            const res = await fetch(endpoint, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            const count = data.count ?? 0;

            if (count > 0) {
                playAlert();
                showToast(count);
                sendBrowserNotification(count);
            } else {
                const container = document.getElementById('toast-container');
                if (container) container.innerHTML = '';
            }
        } catch (e) { /* silenciar errores de red */ }
    }

    setTimeout(checkBloqueados, 3000);
    setInterval(checkBloqueados, POLL_INTERVAL);

    actualizarEstadoNotif();
})();
</script>

<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1060;"></div>

<?= $this->endSection() ?>
