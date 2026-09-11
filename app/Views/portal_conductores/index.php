<?php $this->extend('layouts/portal'); ?>
<?php $this->section('content'); ?>

<style>
/* ── Touch-friendly tiles ── */
.tile {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    padding: 1.75rem 1rem;
    border-radius: var(--radius);
    text-decoration: none;
    transition: transform .15s, box-shadow .15s;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    min-height: 160px;
}
.tile:active { transform: scale(.97); }
.tile-icon {
    width: 64px; height: 64px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem;
}
.tile-label { font-weight: 700; font-size: 1.05rem; }
.tile-sub { font-size: .8rem; opacity: .7; }

.tile-primary { background: var(--primary); color: #fff; }
.tile-primary .tile-icon { background: rgba(255,255,255,.2); color: #fff; }
.tile-primary:hover { background: var(--primary-dk); color: #fff; }

.tile-light { background: var(--card); color: var(--text); border: 2px solid var(--border); }
.tile-light .tile-icon { background: #e7f0ff; color: var(--primary); }
.tile-light:hover { border-color: var(--primary); color: var(--text); }

.tile-warning { background: #fffbeb; color: #92400e; border: 2px solid #fde68a; }
.tile-warning .tile-icon { background: #fef3c7; color: #d97706; }
.tile-warning:hover { border-color: #d97706; color: #92400e; }

.tile-success { background: #f0fdf4; color: #166534; border: 2px solid #bbf7d0; }
.tile-success .tile-icon { background: #dcfce7; color: #16a34a; }
.tile-success:hover { border-color: #16a34a; color: #166534; }

.tile-info { background: #ecfeff; color: #155e75; border: 2px solid #a5f3fc; }
.tile-info .tile-icon { background: #cffafe; color: #0891b2; }
.tile-info:hover { border-color: #0891b2; color: #155e75; }

/* ── Stat pills ── */
.stat-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .35rem .8rem; border-radius: 999px;
    font-size: .8rem; font-weight: 600;
}

/* ── Recent card ── */
.recent-card {
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: .85rem;
    transition: box-shadow .15s;
}
.recent-card:hover { box-shadow: 0 2px 12px rgba(15,23,42,.06); }

@media (max-width: 575px) {
    .tile { min-height: 130px; padding: 1.25rem .75rem; }
    .tile-icon { width: 52px; height: 52px; font-size: 1.4rem; }
    .tile-label { font-size: .95rem; }
}
</style>

<div class="container-fluid px-3 px-sm-4 py-3 py-sm-4" style="max-width: 920px; margin: 0 auto;">

    <!-- Bienvenida -->
    <div class="text-center mb-4 animate__animated animate__fadeIn">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2"
             style="width:56px;height:56px;background:var(--primary);">
            <i class="fas fa-truck fa-lg text-white"></i>
        </div>
        <h4 class="fw-bold mb-1">Portal de Conductores</h4>
        <p class="text-muted mb-0 small">Bienvenido<?= session()->get('user_id') ? ', ' . esc(session()->get('nombre') ?? session()->get('usuario') ?? 'Conductor') : '' ?></p>
    </div>

    <!-- Tiles principales -->
    <div class="row g-3 mb-4">
        <!-- Nueva Solicitud -->
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="<?= base_url('portal/solicitud/crear') ?>" class="tile tile-primary shadow-sm animate__animated animate__fadeInUp">
                <div class="tile-icon"><i class="fas fa-plus-circle"></i></div>
                <div class="tile-label">Nueva Solicitud</div>
                <div class="tile-sub">Reporta un problema</div>
            </a>
        </div>

        <!-- Mis Solicitudes -->
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="<?= base_url('portal/solicitud/historial') ?>" class="tile tile-light shadow-sm animate__animated animate__fadeInUp" style="animation-delay:.05s">
                <div class="tile-icon"><i class="fas fa-list-alt"></i></div>
                <div class="tile-label">Mis Solicitudes</div>
                <div class="tile-sub"><?= $conteo['total'] ?? 0 ?> en total</div>
            </a>
        </div>

        <!-- Pendientes -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="tile tile-warning shadow-sm animate__animated animate__fadeInUp" style="animation-delay:.1s">
                <div class="tile-icon"><i class="fas fa-clock"></i></div>
                <div class="tile-label"><?= $conteo['pendientes'] ?? 0 ?></div>
                <div class="tile-sub">Pendientes</div>
            </div>
        </div>

        <!-- En Proceso -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="tile tile-info shadow-sm animate__animated animate__fadeInUp" style="animation-delay:.15s">
                <div class="tile-icon"><i class="fas fa-cogs"></i></div>
                <div class="tile-label"><?= $conteo['en_proceso'] ?? 0 ?></div>
                <div class="tile-sub">En Proceso</div>
            </div>
        </div>

        <!-- Finalizadas -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="tile tile-success shadow-sm animate__animated animate__fadeInUp" style="animation-delay:.2s">
                <div class="tile-icon"><i class="fas fa-check-circle"></i></div>
                <div class="tile-label"><?= $conteo['finalizadas'] ?? 0 ?></div>
                <div class="tile-sub">Finalizadas</div>
            </div>
        </div>

        <!-- Salir (solo si hay sesión) -->
        <?php if (session()->get('user_id')): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="<?= base_url('logout') ?>" class="tile tile-light shadow-sm animate__animated animate__fadeInUp" style="animation-delay:.25s">
                <div class="tile-icon" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-sign-out-alt"></i></div>
                <div class="tile-label">Salir</div>
                <div class="tile-sub">Cerrar sesión</div>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php if (empty($recientes)): ?>
    <div class="text-center py-4 animate__animated animate__fadeIn">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
             style="width:64px;height:64px;">
            <i class="fas fa-inbox fa-2x text-muted opacity-50"></i>
        </div>
        <p class="text-muted mb-2">No tienes solicitudes registradas</p>
        <a href="<?= base_url('portal/solicitud/crear') ?>" class="btn btn-primary btn-lg rounded-pill px-4">
            <i class="fas fa-plus me-1"></i> Crear Primera Solicitud
        </a>
    </div>
    <?php endif; ?>

</div>

<?php $this->endSection(); ?>
