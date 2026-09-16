<?= $this->extend('layouts/portal_oneui') ?>
<?= $this->section('content') ?>

<div class="text-center py-4 animate__animated animate__fadeIn">
    <div class="bento-icon mx-auto mb-3" style="width:80px;height:80px;background:var(--success);color:#fff;border-radius:24px;font-size:2rem;">
        <i class="fas fa-check"></i>
    </div>
    <h4 class="fw-bold mb-1">¡Solicitud enviada!</h4>
    <p class="text-muted mb-4">Tu solicitud de mantenimiento fue registrada correctamente.</p>
</div>

<div class="oneui-card mb-4 animate__animated animate__fadeInUp" style="animation-delay:.1s">
    <div class="row g-3">
        <div class="col-5 text-muted small">N° Solicitud</div>
        <div class="col-7 fw-bold">#<?= esc($solicitud['id']) ?></div>

        <div class="col-5 text-muted small">Vehículo</div>
        <div class="col-7 fw-semibold">
            <?= esc($solicitud['placa'] ?? 'N/A') ?>
            <span class="text-muted fw-normal"><?= esc(($solicitud['marca'] ?? '') . ' ' . ($solicitud['modelo'] ?? '')) ?></span>
        </div>

        <div class="col-5 text-muted small">Estado</div>
        <div class="col-7"><span class="status-pill pendientes">Pendiente</span></div>

        <div class="col-5 text-muted small">Fecha</div>
        <div class="col-7 small"><?= esc($solicitud['fecha_solicitud'] ?? date('Y-m-d H:i:s')) ?></div>
    </div>
</div>

<div class="d-flex gap-2 animate__animated animate__fadeInUp" style="animation-delay:.2s">
    <a href="<?= base_url('portal/solicitud/seguimiento/' . $solicitud['id']) ?>" class="btn btn-oneui btn-oneui-primary flex-fill">
        <i class="fas fa-search me-1"></i> Ver seguimiento
    </a>
    <a href="<?= base_url('portal') ?>" class="btn btn-oneui btn-oneui-outline flex-fill">
        <i class="fas fa-home me-1"></i> Inicio
    </a>
</div>

<?= $this->endSection() ?>
