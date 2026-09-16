<?= $this->extend('layouts/portal_oneui') ?>
<?= $this->section('content') ?>

<?php
$s = $solicitud;
$estado = strtoupper($s['estado'] ?? 'PENDIENTE');
$estadoClass = strtolower(str_replace(' ', '_', $estado));

// Timeline steps
$pasos = [
    'PENDIENTE'   => ['label' => 'Solicitud enviada', 'icon' => 'fa-paper-plane'],
    'APROBADA'    => ['label' => 'Aprobada', 'icon' => 'fa-check'],
    'ASIGNADA'    => ['label' => 'Técnico asignado', 'icon' => 'fa-user-cog'],
    'EN_PROCESO'  => ['label' => 'En reparación', 'icon' => 'fa-cogs'],
    'FINALIZADA'  => ['label' => 'Finalizada', 'icon' => 'fa-flag-checkered'],
];

$ordenEstados = ['PENDIENTE', 'APROBADA', 'ASIGNADA', 'EN_PROCESO', 'FINALIZADA'];
$estadoActualIdx = array_search($estado, $ordenEstados);
if ($estadoActualIdx === false) $estadoActualIdx = 0;
if ($estado === 'CANCELADA') $estadoActualIdx = -1;

$fecha = $s['fecha_solicitud'] ? date('d/m/Y H:i', strtotime($s['fecha_solicitud'])) : '—';
?>

<!-- Header -->
<div class="d-flex align-items-center gap-3 mb-4 animate__animated animate__fadeIn">
    <a href="<?= base_url('portal/solicitud/historial') ?>" class="bento-icon" style="width:36px;height:36px;font-size:.85rem;background:var(--card);color:var(--muted);border-radius:12px;border:1px solid var(--border);text-decoration:none;">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0">Solicitud #<?= $s['id'] ?></h5>
        <p class="text-muted mb-0 small"><?= $fecha ?></p>
    </div>
    <span class="status-pill <?= $estadoClass ?>"><?= esc($estado) ?></span>
</div>

<!-- Vehículo -->
<div class="oneui-card mb-3 animate__animated animate__fadeInUp" style="animation-delay:.05s">
    <div class="d-flex align-items-center gap-3">
        <div class="bento-icon" style="width:48px;height:48px;font-size:1.2rem;background:#dbeafe;color:#2563eb;border-radius:16px;">
            <i class="fas fa-truck"></i>
        </div>
        <div>
            <div class="fw-bold"><?= esc($s['placa'] ?? 'N/A') ?></div>
            <div class="text-muted small"><?= esc(($s['marca'] ?? '') . ' ' . ($s['modelo'] ?? '') . ' ' . ($s['anio'] ?? '')) ?></div>
        </div>
    </div>
</div>

<!-- Timeline de estados -->
<div class="oneui-card mb-3 animate__animated animate__fadeInUp" style="animation-delay:.1s">
    <h6 class="fw-bold mb-3" style="font-size:.85rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);">Progreso</h6>
    <div class="timeline">
        <?php foreach ($pasos as $key => $paso):
            $idx = array_search($key, $ordenEstados);
            $isDone = $idx < $estadoActualIdx;
            $isActive = $idx === $estadoActualIdx;
            $dotClass = $isDone ? 'done' : ($isActive ? 'active' : '');
        ?>
        <div class="timeline-item">
            <div class="timeline-dot <?= $dotClass ?>"></div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas <?= $paso['icon'] ?> <?= $isDone || $isActive ? 'text-primary' : 'text-muted' ?>" style="font-size:.8rem;width:16px;"></i>
                <span class="<?= $isActive ? 'fw-bold' : ($isDone ? 'fw-semibold' : 'text-muted') ?>" style="font-size:.85rem;">
                    <?= $paso['label'] ?>
                </span>
                <?php if ($isActive): ?>
                <span class="badge bg-primary ms-auto" style="font-size:.65rem;">Actual</span>
                <?php elseif ($isDone): ?>
                <i class="fas fa-check-circle text-success ms-auto" style="font-size:.8rem;"></i>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Detalles del problema -->
<div class="oneui-card mb-3 animate__animated animate__fadeInUp" style="animation-delay:.15s">
    <h6 class="fw-bold mb-3" style="font-size:.85rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);">Detalles</h6>

    <?php if ($tipoProblema): ?>
    <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3" style="background:#fef3c7;">
        <i class="fas fa-wrench" style="color:#d97706;"></i>
        <span class="small fw-semibold"><?= esc($tipoProblema['nombre']) ?></span>
    </div>
    <?php endif; ?>

    <div class="mb-3">
        <label class="text-muted small fw-bold text-uppercase" style="font-size:.7rem;">Descripción</label>
        <p class="mb-0 small"><?= nl2br(esc($s['descripcion'] ?? '')) ?></p>
    </div>

    <?php if (!empty($s['url_foto'])): ?>
    <div class="mb-0">
        <label class="text-muted small fw-bold text-uppercase" style="font-size:.7rem;">Foto adjunta</label>
        <div class="mt-1">
            <img src="<?= base_url($s['url_foto']) ?>" class="img-fluid rounded-3" style="max-height:200px;object-fit:cover;" alt="Foto del problema">
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Trabajo realizado (si existe) -->
<?php if ($registroTrabajo): ?>
<div class="oneui-card mb-3 animate__animated animate__fadeInUp" style="animation-delay:.2s">
    <h6 class="fw-bold mb-3" style="font-size:.85rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);">Trabajo Realizado</h6>

    <div class="row g-2 mb-3">
        <?php if (!empty($registroTrabajo['trabajo_realizado'])): ?>
        <div class="col-12">
            <label class="text-muted small fw-bold text-uppercase" style="font-size:.7rem;">Descripción del trabajo</label>
            <p class="mb-0 small"><?= nl2br(esc($registroTrabajo['trabajo_realizado'])) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($registroTrabajo['diagnostico_sistemas'])): ?>
        <div class="col-12">
            <label class="text-muted small fw-bold text-uppercase" style="font-size:.7rem;">Sistemas diagnosticados</label>
            <div class="d-flex flex-wrap gap-1 mt-1">
                <?php foreach (json_decode($registroTrabajo['diagnostico_sistemas'], true) ?? [] as $sist): ?>
                <span class="badge rounded-pill" style="background:#e0e7ff;color:#3730a3;font-size:.7rem;"><?= esc($sist) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($registroTrabajo['trabajos_checklist'])): ?>
        <div class="col-12">
            <label class="text-muted small fw-bold text-uppercase" style="font-size:.7rem;">Trabajos realizados</label>
            <div class="d-flex flex-wrap gap-1 mt-1">
                <?php foreach (json_decode($registroTrabajo['trabajos_checklist'], true) ?? [] as $t): ?>
                <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;font-size:.7rem;"><?= esc($t) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($registroTrabajo['resultado_final'])): ?>
        <div class="col-12">
            <label class="text-muted small fw-bold text-uppercase" style="font-size:.7rem;">Resultado</label>
            <div>
                <span class="status-pill <?= strtolower($registroTrabajo['resultado_final']) ?>"><?= esc(str_replace('_', ' ', $registroTrabajo['resultado_final'])) ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($registroTrabajo['observaciones_finales'])): ?>
        <div class="col-12">
            <label class="text-muted small fw-bold text-uppercase" style="font-size:.7rem;">Observaciones finales</label>
            <p class="mb-0 small"><?= nl2br(esc($registroTrabajo['observaciones_finales'])) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Materiales utilizados -->
<?php if (!empty($materiales)): ?>
<div class="oneui-card mb-3 animate__animated animate__fadeInUp" style="animation-delay:.25s">
    <h6 class="fw-bold mb-3" style="font-size:.85rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);">Materiales Utilizados</h6>
    <div class="table-responsive">
        <table class="table table-sm mb-0" style="font-size:.82rem;">
            <thead>
                <tr>
                    <th>Material</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-end">Costo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($materiales as $m): ?>
                <tr>
                    <td><?= esc($m['material_nombre'] ?? 'Material #' . $m['id_material']) ?></td>
                    <td class="text-center"><?= $m['cantidad'] ?> <?= esc($m['unidad_medida'] ?? '') ?></td>
                    <td class="text-end">$<?= number_format($m['costo_total'] ?? 0, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="2" class="text-end">Total:</td>
                    <td class="text-end">$<?= number_format(array_sum(array_column($materiales, 'costo_total')), 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Acciones -->
<div class="d-flex gap-2 animate__animated animate__fadeInUp" style="animation-delay:.3s">
    <a href="<?= base_url('portal/solicitud/historial') ?>" class="btn btn-oneui btn-oneui-outline flex-fill">
        <i class="fas fa-list me-1"></i> Ver todas
    </a>
    <a href="<?= base_url('portal/solicitud/crear') ?>" class="btn btn-oneui btn-oneui-primary flex-fill">
        <i class="fas fa-plus me-1"></i> Nueva solicitud
    </a>
</div>

<?= $this->endSection() ?>
