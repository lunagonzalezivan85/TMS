<?php $this->extend('layouts/portal'); ?>
<?php $this->section('content'); ?>

<?php
$badges = [
    'PENDIENTES' => 'warning text-dark',
    'EN_PROCESO' => 'primary',
    'APROBADAS'  => 'info text-dark',
    'FINALIZADA' => 'success',
    'CANCELADA'  => 'danger',
];
?>

<div class="container-fluid px-3 px-sm-4 py-3 py-sm-4" style="max-width: 900px; margin: 0 auto;">

    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between mb-3 gap-2 animate__animated animate__fadeIn">
        <div>
            <h5 class="fw-bold mb-0"><i class="fas fa-list-alt me-2 text-primary"></i>Mis Solicitudes</h5>
            <p class="text-muted mb-0 small"><?= count($solicitudes) ?> solicitud<?= count($solicitudes) !== 1 ? 'es' : '' ?> registrada<?= count($solicitudes) !== 1 ? 's' : '' ?></p>
        </div>
        <a href="<?= base_url('portal/solicitud/crear') ?>" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3">
            <i class="fas fa-plus me-1"></i> <span class="d-none d-sm-inline">Nueva </span>Solicitud
        </a>
    </div>

    <?php if (empty($solicitudes)): ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="fas fa-inbox fa-3x text-secondary mb-3"></i>
                <h6 class="text-muted">No tienes solicitudes registradas aún.</h6>
                <a href="<?= base_url('portal/solicitud/crear') ?>" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-1"></i> Crear primera solicitud
                </a>
            </div>
        </div>
    <?php else: ?>

        <!-- Toggle de vistas -->
        <div class="d-flex justify-content-end mb-3 animate__animated animate__fadeIn" style="animation-delay: 0.1s">
            <div class="btn-group btn-group-sm shadow-sm rounded-pill overflow-hidden" role="group" id="view-toggle">
                <button type="button" class="btn btn-primary active border-0" data-view="list" title="Vista lista">
                    <i class="fas fa-list"></i>
                </button>
                <button type="button" class="btn btn-outline-primary border-0" data-view="card" title="Vista tarjetas">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" class="btn btn-outline-primary border-0" data-view="table" title="Vista tabla">
                    <i class="fas fa-table"></i>
                </button>
            </div>
        </div>

        <!-- ══════════════ VISTA LIST (default) ══════════════ -->
        <div id="view-list" class="animate__animated">
            <div class="d-flex flex-column gap-2">
                <?php $idx = 0; foreach ($solicitudes as $s):
                    $estado = $s['estado'] ?? 'PENDIENTES';
                    $badge  = $badges[$estado] ?? 'secondary';
                    $desc   = $s['descripcion'] ?? '';
                    $fecha  = $s['fecha_solicitud'] ? date('d/m/Y', strtotime($s['fecha_solicitud'])) : '—';
                ?>
                <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: <?= ($idx * 0.05) ?>s">
                    <div class="card-body p-3">
<?php $idx++; ?>
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:42px;height:42px;">
                                <i class="fas fa-truck text-primary"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                    <span class="fw-semibold"><?= esc($s['placa'] ?? 'N/A') ?></span>
                                    <span class="badge bg-<?= $badge ?> flex-shrink-0"><?= esc($estado) ?></span>
                                </div>
                                <div class="text-muted small text-truncate mb-1"><?= esc(($s['marca'] ?? '') . ' ' . ($s['modelo'] ?? '')) ?></div>
                                <div class="small text-truncate text-secondary"><?= esc($desc) ?></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <span class="text-muted" style="font-size:.75rem"><i class="fas fa-calendar-alt me-1"></i><?= $fecha ?></span>
                            <span class="text-muted" style="font-size:.75rem">#<?= esc($s['id']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ══════════════ VISTA CARD ══════════════ -->
        <div id="view-card" class="d-none">
            <div class="row g-3">
                <?php foreach ($solicitudes as $s):
                    $estado = $s['estado'] ?? 'PENDIENTES';
                    $badge  = $badges[$estado] ?? 'secondary';
                    $fecha  = $s['fecha_solicitud'] ? date('d/m/Y', strtotime($s['fecha_solicitud'])) : '—';
                ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                     style="width:38px;height:38px;">
                                    <i class="fas fa-truck text-primary small"></i>
                                </div>
                                <span class="badge bg-<?= $badge ?>"><?= esc($estado) ?></span>
                            </div>
                            <div class="fw-semibold mb-0"><?= esc($s['placa'] ?? 'N/A') ?></div>
                            <div class="text-muted small mb-2"><?= esc(($s['marca'] ?? '') . ' ' . ($s['modelo'] ?? '')) ?></div>
                            <p class="small text-secondary mb-0" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                <?= esc($s['descripcion'] ?? '') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top py-2 px-3 d-flex justify-content-between">
                            <span class="text-muted" style="font-size:.72rem"><i class="fas fa-calendar-alt me-1"></i><?= $fecha ?></span>
                            <span class="text-muted" style="font-size:.72rem">#<?= esc($s['id']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ══════════════ VISTA TABLE ══════════════ -->
        <div id="view-table" class="d-none">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Vehículo</th>
                                <th class="d-none d-md-table-cell">Descripción</th>
                                <th>Estado</th>
                                <th class="d-none d-sm-table-cell">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($solicitudes as $s):
                                $estado = $s['estado'] ?? 'PENDIENTES';
                                $badge  = $badges[$estado] ?? 'secondary';
                                $fecha  = $s['fecha_solicitud'] ? date('d/m/Y', strtotime($s['fecha_solicitud'])) : '—';
                            ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted small"><?= esc($s['id']) ?></td>
                                <td>
                                    <div class="fw-semibold small"><?= esc($s['placa'] ?? 'N/A') ?></div>
                                    <div class="text-muted" style="font-size:.72rem"><?= esc(($s['marca'] ?? '') . ' ' . ($s['modelo'] ?? '')) ?></div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="text-truncate small" style="max-width:260px;"><?= esc($s['descripcion'] ?? '') ?></div>
                                </td>
                                <td><span class="badge bg-<?= $badge ?>"><?= esc($estado) ?></span></td>
                                <td class="small text-muted d-none d-sm-table-cell"><?= $fecha ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const views   = ['list', 'card', 'table'];
    const stored  = localStorage.getItem('historial_view') || 'list';

    function activarVista(v) {
        views.forEach(function (name) {
            const el = document.getElementById('view-' + name);
            if (el) {
                if (name === v) {
                    el.classList.remove('d-none');
                    el.classList.add('animate__fadeIn');
                } else {
                    el.classList.add('d-none');
                }
            }
        });
        document.querySelectorAll('#view-toggle button').forEach(function (btn) {
            const isActive = btn.dataset.view === v;
            btn.classList.toggle('btn-primary', isActive);
            btn.classList.toggle('btn-outline-primary', !isActive);
            btn.classList.toggle('active', isActive);
        });
        localStorage.setItem('historial_view', v);
    }

    activarVista(stored);

    document.querySelectorAll('#view-toggle button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            activarVista(btn.dataset.view);
        });
    });
});
</script>

<?php $this->endSection(); ?>
