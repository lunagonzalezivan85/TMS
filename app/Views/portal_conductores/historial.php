<?= $this->extend('layouts/portal_oneui') ?>
<?= $this->section('content') ?>

<?php
$badges = [
    'PENDIENTE'  => 'pendientes',
    'EN_PROCESO' => 'en_proceso',
    'APROBADA'   => 'aprobadas',
    'ASIGNADA'   => 'asignada',
    'FINALIZADA' => 'finalizada',
    'CANCELADA'  => 'cancelada',
];
?>

<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-4 animate__animated animate__fadeIn">
    <div>
        <h5 class="fw-bold mb-0">Mis Solicitudes</h5>
        <p class="text-muted mb-0 small"><?= count($solicitudes) ?> registrada<?= count($solicitudes) !== 1 ? 's' : '' ?></p>
    </div>
    <a href="<?= base_url('portal/solicitud/crear') ?>" class="btn btn-oneui btn-oneui-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Nueva
    </a>
</div>

<?php if (empty($solicitudes)): ?>
    <div class="oneui-card text-center py-5 animate__animated animate__fadeIn">
        <i class="fas fa-inbox fa-3x text-muted opacity-25 mb-3"></i>
        <p class="text-muted mb-3">No tienes solicitudes registradas</p>
        <a href="<?= base_url('portal/solicitud/crear') ?>" class="btn btn-oneui btn-oneui-primary">
            <i class="fas fa-plus me-1"></i> Crear primera solicitud
        </a>
    </div>
<?php else: ?>

    <!-- Filtros por estado -->
    <div class="d-flex gap-2 mb-3 overflow-auto pb-1 animate__animated animate__fadeIn" style="animation-delay:.05s">
        <button class="btn btn-sm rounded-pill px-3 filtro-estado active" data-estado="" style="background:var(--primary);color:#fff;border:none;font-size:.75rem;font-weight:600;">Todas</button>
        <button class="btn btn-sm rounded-pill px-3 filtro-estado" data-estado="PENDIENTE" style="background:#fef3c7;color:#92400e;border:none;font-size:.75rem;font-weight:600;">Pendientes</button>
        <button class="btn btn-sm rounded-pill px-3 filtro-estado" data-estado="EN_PROCESO" style="background:#dbeafe;color:#1e40af;border:none;font-size:.75rem;font-weight:600;">En Proceso</button>
        <button class="btn btn-sm rounded-pill px-3 filtro-estado" data-estado="FINALIZADA" style="background:#dcfce7;color:#166534;border:none;font-size:.75rem;font-weight:600;">Finalizadas</button>
    </div>

    <!-- Lista de solicitudes -->
    <div class="d-flex flex-column gap-2" id="lista-solicitudes">
        <?php foreach ($solicitudes as $i => $s):
            $estado = $s['estado'] ?? 'PENDIENTE';
            $estadoClass = $badges[$estado] ?? 'pendientes';
            $fecha = $s['fecha_solicitud'] ? date('d/m/Y', strtotime($s['fecha_solicitud'])) : '—';
            $hora = $s['fecha_solicitud'] ? date('H:i', strtotime($s['fecha_solicitud'])) : '';
        ?>
        <a href="<?= base_url('portal/solicitud/seguimiento/' . $s['id']) ?>"
           class="oneui-card d-flex align-items-center gap-3 text-decoration-none solicitud-item animate__animated animate__fadeInUp"
           style="animation-delay:<?= (0.1 + $i * 0.04) ?>s;color:var(--text);"
           data-estado="<?= esc($estado) ?>">

            <div class="bento-icon flex-shrink-0" style="width:42px;height:42px;font-size:1rem;background:#f0f4f8;color:var(--muted);border-radius:14px;">
                <i class="fas fa-truck"></i>
            </div>

            <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                    <span class="fw-semibold small"><?= esc($s['placa'] ?? 'N/A') ?></span>
                    <span class="status-pill <?= $estadoClass ?>"><?= esc($estado) ?></span>
                </div>
                <div class="text-muted small text-truncate mb-1"><?= esc(($s['marca'] ?? '') . ' ' . ($s['modelo'] ?? '')) ?></div>
                <div class="small text-truncate" style="color:var(--muted);"><?= esc($s['descripcion'] ?? '') ?></div>
            </div>

            <div class="text-end flex-shrink-0">
                <div class="text-muted" style="font-size:.7rem;"><?= $fecha ?></div>
                <div class="text-muted" style="font-size:.65rem;"><?= $hora ?></div>
                <i class="fas fa-chevron-right text-muted mt-1" style="font-size:.65rem;"></i>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<script>
document.querySelectorAll('.filtro-estado').forEach(btn => {
    btn.addEventListener('click', function() {
        const estado = this.dataset.estado;

        // Actualizar botones activos
        document.querySelectorAll('.filtro-estado').forEach(b => {
            b.classList.remove('active');
            b.style.background = '';
            b.style.color = '';
        });
        this.classList.add('active');
        this.style.background = 'var(--primary)';
        this.style.color = '#fff';

        // Filtrar items
        document.querySelectorAll('.solicitud-item').forEach(item => {
            if (!estado || item.dataset.estado === estado) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
