<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Portal Conductores' ?> — GMV</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- Seguridad CSRF -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">
    <meta name="base-url" content="<?= base_url() ?>">

    <?= $this->renderSection('head') ?>

    <style>
        :root {
            --primary:    #2563eb;
            --primary-dk: #1d4ed8;
            --bg:         #f0f4f8;
            --card:       #ffffff;
            --text:       #0f172a;
            --muted:      #64748b;
            --border:     #e2e8f0;
            --radius:     16px;
            --shadow:     0 8px 32px rgba(15,23,42,.08);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* ── Topbar ── */
        .portal-topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(15,23,42,.06);
        }

        .portal-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--primary);
            text-decoration: none;
        }

        .portal-brand .brand-icon {
            width: 34px; height: 34px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 0.9rem;
        }

        .portal-user {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: var(--muted);
        }

        .portal-user .avatar {
            width: 32px; height: 32px;
            background: #e0e7ff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* ── Nav pills ── */
        .portal-nav {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            display: flex;
            gap: 4px;
            overflow-x: auto;
        }

        .portal-nav a {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 12px 14px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
            transition: color .2s, border-color .2s;
        }

        .portal-nav a:hover { color: var(--primary); }
        .portal-nav a.active { color: var(--primary); border-bottom-color: var(--primary); }

        /* ── Main content ── */
        .portal-body {
            min-height: calc(100vh - 108px);
            padding: 2rem 1rem;
        }

        /* ── Cards ── */
        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        /* ── Buttons ── */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dk); border-color: var(--primary-dk); }

        /* ── Footer ── */
        .portal-footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.75rem;
            color: var(--muted);
            border-top: 1px solid var(--border);
            background: var(--card);
        }
    </style>
</head>
<body>

<!-- Topbar -->
<header class="portal-topbar">
    <a href="<?= base_url('portal') ?>" class="portal-brand">
        <div class="brand-icon"><i class="fas fa-truck"></i></div>
        <span>Portal Conductores</span>
    </a>
    <div class="portal-user">
        <?php if (session()->get('user_id')): ?>
        <div class="avatar">
            <?= strtoupper(substr(session()->get('nombre') ?? session()->get('usuario') ?? 'U', 0, 1)) ?>
        </div>
        <span class="d-none d-sm-inline"><?= esc(session()->get('nombre') ?? session()->get('usuario') ?? 'Usuario') ?></span>
        <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-secondary ms-2" title="Cerrar sesión">
            <i class="fas fa-sign-out-alt"></i>
        </a>
        <?php else: ?>
        <a href="<?= base_url('login') ?>" class="btn btn-sm btn-outline-primary ms-2" title="Iniciar sesión">
            <i class="fas fa-sign-in-alt me-1"></i> <span class="d-none d-sm-inline">Iniciar sesión</span>
        </a>
        <?php endif; ?>
    </div>
</header>

<!-- Nav -->
<nav class="portal-nav">
    <?php
        $currentUri = uri_string();
    ?>
    <a href="<?= base_url('portal') ?>"
       class="<?= $currentUri === 'portal' ? 'active' : '' ?>">
        <i class="fas fa-home"></i> Inicio
    </a>
    <a href="<?= base_url('portal/solicitud/crear') ?>"
       class="<?= str_contains($currentUri, 'crear') ? 'active' : '' ?>">
        <i class="fas fa-plus-circle"></i> Nueva Solicitud
    </a>
    <a href="<?= base_url('portal/solicitud/historial') ?>"
       class="<?= str_contains($currentUri, 'historial') ? 'active' : '' ?>">
        <i class="fas fa-list-alt"></i> Mis Solicitudes
    </a>
</nav>

<!-- Contenido -->
<main class="portal-body">
    <?= $this->renderSection('content') ?>
</main>

<!-- Footer -->
<footer class="portal-footer">
    GMV &mdash; Sistema de Gestión de Mantenimiento de Vehículos
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<!-- Custom Core -->
<script src="<?= base_url('public/assets/js/core/HttpClient.js') ?>"></script>
<script src="<?= base_url('public/assets/js/core/UI.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
