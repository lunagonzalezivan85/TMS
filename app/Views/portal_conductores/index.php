<?= $this->extend('layouts/portal_oneui') ?>
<?= $this->section('content') ?>

<?php
$conductorNombre = session()->get('conductor_nombre') ?? 'Conductor';
$conductorId = session()->get('conductor_id');
?>

<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-4 animate__animated animate__fadeIn">
    <div>
        <h5 class="fw-bold mb-0">Hola, <?= esc(explode(' ', $conductorNombre)[0]) ?></h5>
        <p class="text-muted mb-0 small">¿Qué necesitas hoy?</p>
    </div>
    <div class="avatar" style="width:40px;height:40px;background:#e0e7ff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:700;">
        <?= strtoupper(substr($conductorNombre, 0, 1)) ?>
    </div>
</div>

<!-- Bento Grid -->
<div class="bento mb-4">

    <!-- Nueva Solicitud — hero card -->
    <a href="<?= base_url('portal/solicitud/crear') ?>" class="bento-item span-2 animate__animated animate__fadeInUp" style="background:var(--primary);color:#fff;border:none;">
        <div class="d-flex align-items-center gap-3">
            <div class="bento-icon" style="background:rgba(255,255,255,.2);color:#fff;">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <div class="bento-label" style="color:#fff;">Nueva Solicitud</div>
                <div class="bento-sub" style="color:rgba(255,255,255,.75);">Reporta un problema con tu vehículo</div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-end mt-2">
            <i class="fas fa-arrow-right" style="opacity:.6;"></i>
        </div>
    </a>

    <!-- Stats row -->
    <div class="bento-item animate__animated animate__fadeInUp" style="animation-delay:.05s">
        <div class="bento-icon" style="background:#fef3c7;color:#d97706;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="bento-value"><?= $conteo['pendientes'] ?? 0 ?></div>
        <div class="bento-sub">Pendientes</div>
    </div>

    <div class="bento-item animate__animated animate__fadeInUp" style="animation-delay:.1s">
        <div class="bento-icon" style="background:#dbeafe;color:#2563eb;">
            <i class="fas fa-cogs"></i>
        </div>
        <div class="bento-value"><?= $conteo['en_proceso'] ?? 0 ?></div>
        <div class="bento-sub">En Proceso</div>
    </div>

    <div class="bento-item animate__animated animate__fadeInUp" style="animation-delay:.15s">
        <div class="bento-icon" style="background:#dcfce7;color:#16a34a;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="bento-value"><?= $conteo['finalizadas'] ?? 0 ?></div>
        <div class="bento-sub">Finalizadas</div>
    </div>

    <a href="<?= base_url('portal/solicitud/historial') ?>" class="bento-item animate__animated animate__fadeInUp" style="animation-delay:.2s">
        <div class="bento-icon" style="background:#e0e7ff;color:#3730a3;">
            <i class="fas fa-list-alt"></i>
        </div>
        <div class="bento-value"><?= $conteo['total'] ?? 0 ?></div>
        <div class="bento-sub">Total Solicitudes</div>
    </a>

</div>

<!-- Solicitudes recientes -->
<?php if (!empty($recientes)): ?>
<div class="section-header animate__animated animate__fadeIn" style="animation-delay:.25s">
    <h6>Recientes</h6>
    <a href="<?= base_url('portal/solicitud/historial') ?>" class="text-decoration-none small fw-semibold" style="color:var(--primary);">Ver todas</a>
</div>

<div class="d-flex flex-column gap-2 mb-4">
    <?php foreach ($recientes as $i => $s):
        $estado = $s['estado'] ?? 'PENDIENTE';
        $estadoClass = strtolower(str_replace(' ', '_', $estado));
        $fecha = $s['fecha_solicitud'] ? date('d/m/Y', strtotime($s['fecha_solicitud'])) : '—';
    ?>
    <a href="<?= base_url('portal/solicitud/seguimiento/' . $s['id']) ?>" class="oneui-card d-flex align-items-center gap-3 text-decoration-none animate__animated animate__fadeInUp" style="animation-delay:<?= (0.3 + $i * 0.05) ?>s;color:var(--text);">
        <div class="bento-icon" style="width:40px;height:40px;font-size:1rem;background:#f0f4f8;color:var(--muted);border-radius:12px;">
            <i class="fas fa-truck"></i>
        </div>
        <div class="flex-grow-1 overflow-hidden">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="fw-semibold small"><?= esc($s['placa'] ?? 'N/A') ?></span>
                <span class="status-pill <?= $estadoClass ?>"><?= esc($estado) ?></span>
            </div>
            <div class="text-muted small text-truncate"><?= esc($s['descripcion'] ?? '') ?></div>
        </div>
        <i class="fas fa-chevron-right text-muted" style="font-size:.7rem;"></i>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Acciones rápidas -->
<div class="section-header animate__animated animate__fadeIn" style="animation-delay:.35s">
    <h6>Acciones</h6>
</div>

<div class="bento bento-3 mb-4">
    <a href="<?= base_url('portal/solicitud/crear') ?>" class="bento-item animate__animated animate__fadeInUp" style="animation-delay:.4s">
        <div class="bento-icon" style="background:#dbeafe;color:#2563eb;">
            <i class="fas fa-wrench"></i>
        </div>
        <div class="bento-label" style="font-size:.8rem;">Solicitar</div>
        <div class="bento-sub">Mantenimiento</div>
    </a>

    <a href="<?= base_url('portal/solicitud/historial') ?>" class="bento-item animate__animated animate__fadeInUp" style="animation-delay:.45s">
        <div class="bento-icon" style="background:#cffafe;color:#0891b2;">
            <i class="fas fa-search"></i>
        </div>
        <div class="bento-label" style="font-size:.8rem;">Seguimiento</div>
        <div class="bento-sub">Estado actual</div>
    </a>

    <a href="<?= base_url('portal/logout') ?>" class="bento-item animate__animated animate__fadeInUp" style="animation-delay:.5s">
        <div class="bento-icon" style="background:#fee2e2;color:#dc2626;">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <div class="bento-label" style="font-size:.8rem;">Salir</div>
        <div class="bento-sub">Cerrar sesión</div>
    </a>
</div>

<?= $this->endSection() ?>
