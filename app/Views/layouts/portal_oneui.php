<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= $title ?? 'Portal' ?> — GMV</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">
    <meta name="base-url" content="<?= base_url() ?>">

    <?= $this->renderSection('head') ?>

    <style>
        :root {
            --bg: #f2f4f7;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --primary: #2563eb;
            --primary-dk: #1d4ed8;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #0891b2;
            --radius: 20px;
            --radius-sm: 14px;
            --shadow: 0 1px 3px rgba(15,23,42,.06), 0 4px 16px rgba(15,23,42,.04);
            --shadow-lg: 0 8px 32px rgba(15,23,42,.1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Topbar ── */
        .oneui-topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 0 1.25rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .oneui-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
            text-decoration: none;
        }

        .oneui-brand .brand-icon {
            width: 32px; height: 32px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 0.85rem;
        }

        .oneui-user {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .oneui-user .avatar {
            width: 30px; height: 30px;
            background: #e0e7ff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-weight: 700;
            font-size: 0.75rem;
        }

        /* ── Bottom nav (One UI style) ── */
        .oneui-bottomnav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--card);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-around;
            padding: 6px 0 calc(6px + env(safe-area-inset-bottom));
            z-index: 100;
        }

        .oneui-bottomnav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 6px 12px;
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
            border-radius: 12px;
            transition: color .15s;
            min-width: 64px;
        }

        .oneui-bottomnav a i { font-size: 1.15rem; }
        .oneui-bottomnav a.active { color: var(--primary); }
        .oneui-bottomnav a:active { transform: scale(.92); }

        /* ── Main content ── */
        .oneui-body {
            min-height: calc(100vh - 56px);
            padding: 1.25rem 1rem calc(80px + env(safe-area-inset-bottom));
            max-width: 720px;
            margin: 0 auto;
        }

        /* ── Bento grid ── */
        .bento {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, 1fr);
        }

        .bento-3 { grid-template-columns: repeat(3, 1fr); }

        .bento-item {
            background: var(--card);
            border-radius: var(--radius);
            padding: 1.25rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: transform .15s, box-shadow .15s;
            text-decoration: none;
            color: var(--text);
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .bento-item:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
        .bento-item:active { transform: scale(.97); }

        .bento-item.span-2 { grid-column: span 2; }
        .bento-item.span-3 { grid-column: span 3; }

        .bento-icon {
            width: 44px; height: 44px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .bento-label { font-weight: 700; font-size: .95rem; }
        .bento-sub { font-size: .78rem; color: var(--muted); }
        .bento-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }

        /* ── Cards ── */
        .oneui-card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 1.25rem;
        }

        /* ── Status pill ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .status-pill.pendientes { background: #fef3c7; color: #92400e; }
        .status-pill.en_proceso { background: #dbeafe; color: #1e40af; }
        .status-pill.aprobadas { background: #cffafe; color: #155e75; }
        .status-pill.finalizada { background: #dcfce7; color: #166534; }
        .status-pill.cancelada { background: #fee2e2; color: #991b1b; }
        .status-pill.asignada { background: #e0e7ff; color: #3730a3; }

        /* ── Buttons ── */
        .btn-oneui {
            border-radius: 14px;
            font-weight: 600;
            padding: .65rem 1.25rem;
            font-size: .9rem;
            border: none;
            transition: transform .1s, box-shadow .15s;
        }
        .btn-oneui:active { transform: scale(.96); }
        .btn-oneui-primary { background: var(--primary); color: #fff; }
        .btn-oneui-primary:hover { background: var(--primary-dk); color: #fff; }
        .btn-oneui-outline { background: transparent; border: 2px solid var(--border); color: var(--text); }
        .btn-oneui-outline:hover { border-color: var(--primary); color: var(--primary); }

        /* ── Form controls ── */
        .form-control, .form-select {
            border-radius: var(--radius-sm);
            border: 2px solid var(--border);
            padding: .65rem .9rem;
            font-size: .9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        /* ── Timeline ── */
        .timeline { position: relative; padding-left: 28px; }
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: var(--border);
            border-radius: 2px;
        }
        .timeline-item { position: relative; padding-bottom: 1.25rem; }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-dot {
            position: absolute;
            left: -24px;
            top: 4px;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--border);
            border: 3px solid var(--card);
        }
        .timeline-dot.active { background: var(--primary); }
        .timeline-dot.done { background: var(--success); }

        /* ── PIN input ── */
        .pin-input {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .pin-digit {
            width: 48px; height: 56px;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            font-family: 'Inter', monospace;
            background: var(--card);
            transition: border-color .15s, box-shadow .15s;
        }
        .pin-digit:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
            outline: none;
        }

        /* ── Section header ── */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .section-header h6 {
            font-weight: 700;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--muted);
            margin: 0;
        }

        /* ── Footer ── */
        .oneui-footer {
            text-align: center;
            padding: .75rem;
            font-size: .7rem;
            color: var(--muted);
            padding-bottom: calc(80px + env(safe-area-inset-bottom));
        }

        @media (max-width: 480px) {
            .bento { gap: 10px; }
            .bento-item { padding: 1rem; }
            .bento-icon { width: 38px; height: 38px; font-size: 1rem; }
            .bento-value { font-size: 1.4rem; }
            .pin-digit { width: 42px; height: 50px; font-size: 1.3rem; }
        }
    </style>
</head>
<body>

<!-- Topbar -->
<header class="oneui-topbar">
    <a href="<?= base_url('portal') ?>" class="oneui-brand">
        <div class="brand-icon"><i class="fas fa-truck"></i></div>
        <span>Portal</span>
    </a>
    <div class="oneui-user">
        <?php if (session()->get('conductor_id')): ?>
        <div class="avatar"><?= strtoupper(substr(session()->get('conductor_nombre') ?? 'C', 0, 1)) ?></div>
        <span class="d-none d-sm-inline"><?= esc(session()->get('conductor_nombre') ?? 'Conductor') ?></span>
        <a href="<?= base_url('portal/logout') ?>" class="btn btn-sm btn-outline-secondary ms-1" title="Salir">
            <i class="fas fa-sign-out-alt"></i>
        </a>
        <?php endif; ?>
    </div>
</header>

<!-- Contenido -->
<main class="oneui-body">
    <?= $this->renderSection('content') ?>
</main>

<!-- Bottom nav -->
<nav class="oneui-bottomnav">
    <?php $uri = uri_string(); ?>
    <a href="<?= base_url('portal') ?>" class="<?= $uri === 'portal' ? 'active' : '' ?>">
        <i class="fas fa-home"></i>
        <span>Inicio</span>
    </a>
    <a href="<?= base_url('portal/solicitud/crear') ?>" class="<?= str_contains($uri, 'crear') ? 'active' : '' ?>">
        <i class="fas fa-plus-circle"></i>
        <span>Nueva</span>
    </a>
    <a href="<?= base_url('portal/solicitud/historial') ?>" class="<?= str_contains($uri, 'historial') || str_contains($uri, 'seguimiento') ? 'active' : '' ?>">
        <i class="fas fa-list-alt"></i>
        <span>Solicitudes</span>
    </a>
</nav>

<footer class="oneui-footer">
    GMV — Sistema de Gestión de Mantenimiento
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
