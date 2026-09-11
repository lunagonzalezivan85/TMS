<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — TMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        /* ── COLUMNA IZQUIERDA ────────────────────────────── */
        .login-brand {
            width: 50%;
            background: linear-gradient(145deg, #07b889 0%, #059c73 55%, #047a5a 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            top: -120px;
            left: -120px;
        }

        .login-brand::after {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            bottom: -80px;
            right: -80px;
        }

        .brand-icon {
            width: 90px;
            height: 90px;
            background: rgba(255,255,255,.18);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            color: #fff;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(4px);
        }

        .brand-title {
            font-size: 4.5rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -2px;
            line-height: 1;
            margin-bottom: .75rem;
        }

        .brand-subtitle {
            font-size: 1.15rem;
            color: rgba(255,255,255,.85);
            font-weight: 400;
            text-align: center;
            max-width: 320px;
        }

        .brand-features {
            margin-top: 3rem;
            list-style: none;
            padding: 0;
            width: 100%;
            max-width: 320px;
        }

        .brand-features li {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: rgba(255,255,255,.9);
            font-size: .92rem;
            padding: .6rem 0;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .brand-features li:last-child { border-bottom: 0; }

        .brand-features li i {
            background: rgba(255,255,255,.18);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .85rem;
        }

        .brand-badge {
            position: absolute;
            bottom: 2rem;
            font-size: .78rem;
            color: rgba(255,255,255,.55);
        }

        /* ── COLUMNA DERECHA ──────────────────────────────── */
        .login-form-col {
            width: 50%;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            overflow-y: auto;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .login-header h2 {
            font-size: 1.85rem;
            font-weight: 700;
            color: #0f172a;
        }

        .login-header p {
            color: #64748b;
            font-size: .95rem;
        }

        .form-label {
            font-weight: 600;
            font-size: .88rem;
            color: #334155;
        }

        .form-control {
            border-radius: .75rem;
            border: 1.5px solid #e2e8f0;
            padding: .75rem 1rem;
            font-size: .95rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: #07b889;
            box-shadow: 0 0 0 3px rgba(7,184,137,.15);
        }

        .input-group .form-control { border-right: 0; }

        .btn-toggle-password {
            border: 1.5px solid #e2e8f0;
            border-left: 0;
            border-radius: 0 .75rem .75rem 0;
            background: #fff;
            color: #94a3b8;
            padding: 0 1rem;
            transition: color .2s;
        }

        .btn-toggle-password:hover { color: #07b889; }

        .btn-login {
            background: linear-gradient(135deg, #07b889, #059c73);
            border: 0;
            border-radius: .875rem;
            font-weight: 600;
            font-size: 1rem;
            padding: .85rem;
            letter-spacing: .01em;
            transition: opacity .2s, transform .15s;
        }

        .btn-login:hover { opacity: .92; transform: translateY(-1px); }

        .btn-login:disabled { opacity: .7; }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #cbd5e1;
            font-size: .8rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── RESPONSIVE ───────────────────────────────────── */
        @media (max-width: 768px) {
            body { flex-direction: column; overflow: auto; height: auto; }
            .login-brand { width: 100%; min-height: 220px; padding: 2rem; }
            .brand-title { font-size: 3rem; }
            .brand-features { display: none; }
            .login-form-col { width: 100%; padding: 2rem 1.25rem; }
        }
    </style>
</head>
<body>

    <!-- Columna izquierda — Branding TMS -->
    <div class="login-brand">
        <div class="brand-icon"><i class="fas fa-truck-moving"></i></div>
        <div class="brand-title">TMS</div>
        <p class="brand-subtitle">Transport Management System — Gestión integral de tu flota vehicular</p>

        <ul class="brand-features">
            <li><i class="fas fa-car"></i> Control de flota y vehículos</li>
            <li><i class="fas fa-tools"></i> Órdenes de trabajo y mantenimiento</li>
            <li><i class="fas fa-id-card"></i> Gestión de conductores</li>
            <li><i class="fas fa-chart-bar"></i> Reportes y estadísticas</li>
            <li><i class="fas fa-shield-alt"></i> Acceso seguro por empresa</li>
        </ul>

        <span class="brand-badge">&copy; <?= date('Y') ?> Softlutionic</span>
    </div>

    <!-- Columna derecha — Formulario -->
    <div class="login-form-col">
        <div class="login-box">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
