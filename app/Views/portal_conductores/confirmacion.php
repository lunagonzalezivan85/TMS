<?php $this->extend('layouts/portal'); ?>
<?php $this->section('content'); ?>

<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0 text-center">
        <div class="card-body p-5">
            <div class="mb-4">
                <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center"
                     style="width:80px;height:80px;">
                    <i class="fas fa-check fa-2x text-white"></i>
                </div>
            </div>
            <h4 class="fw-bold text-success mb-1">¡Solicitud enviada!</h4>
            <p class="text-muted mb-4">Tu solicitud de mantenimiento fue registrada correctamente.</p>

            <div class="bg-light rounded-3 p-3 mb-4 text-start">
                <div class="row g-2">
                    <div class="col-5 text-muted small">N° Solicitud</div>
                    <div class="col-7 fw-bold">#<?= esc($solicitud['id']) ?></div>

                    <div class="col-5 text-muted small">Vehículo</div>
                    <div class="col-7 fw-semibold">
                        <?= esc($solicitud['placa'] ?? 'N/A') ?>
                        <span class="text-muted fw-normal"><?= esc(($solicitud['marca'] ?? '') . ' ' . ($solicitud['modelo'] ?? '')) ?></span>
                    </div>

                    <div class="col-5 text-muted small">Estado</div>
                    <div class="col-7">
                        <span class="badge bg-warning text-dark">PENDIENTE</span>
                    </div>

                    <div class="col-5 text-muted small">Fecha</div>
                    <div class="col-7 small"><?= esc($solicitud['fecha_solicitud'] ?? date('Y-m-d H:i:s')) ?></div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-center">
                <a href="<?= base_url('portal/solicitud/crear') ?>" class="btn btn-primary px-4">
                    <i class="fas fa-plus me-1"></i> Nueva Solicitud
                </a>
                <a href="<?= base_url('portal/solicitud/historial') ?>" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-list me-1"></i> Mis Solicitudes
                </a>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
