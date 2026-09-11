<?= $this->extend('layouts/login') ?>

<?= $this->section('content') ?>

<div class="login-header mb-4">
    <h2>Bienvenido de nuevo</h2>
    <p>Ingresa tus credenciales para acceder al sistema</p>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= session()->getFlashdata('error') ?></span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-check-circle"></i>
        <span><?= session()->getFlashdata('success') ?></span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= base_url('login') ?>" method="post" id="loginForm" novalidate>
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="usuario" class="form-label">
            <i class="fas fa-user me-1" style="color:#07b889"></i> Usuario
        </label>
        <input type="text"
               class="form-control <?= (isset($validation) && $validation->hasError('usuario')) ? 'is-invalid' : '' ?>"
               id="usuario" name="usuario"
               placeholder="Tu nombre de usuario"
               required
               value="<?= old('usuario') ?>">
        <?php if (isset($validation) && $validation->hasError('usuario')): ?>
            <div class="invalid-feedback">
                <i class="fas fa-exclamation-circle me-1"></i><?= $validation->getError('usuario') ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <label for="clave" class="form-label">
            <i class="fas fa-lock me-1" style="color:#07b889"></i> Contraseña
        </label>
        <div class="input-group">
            <input type="password"
                   class="form-control <?= (isset($validation) && $validation->hasError('clave')) ? 'is-invalid' : '' ?>"
                   id="clave" name="clave"
                   placeholder="Tu contraseña"
                   required>
            <button class="btn-toggle-password" type="button" id="togglePassword" title="Mostrar/ocultar contraseña">
                <i class="fas fa-eye" id="toggleIcon"></i>
            </button>
        </div>
        <?php if (isset($validation) && $validation->hasError('clave')): ?>
            <div class="text-danger small mt-1">
                <i class="fas fa-exclamation-circle me-1"></i><?= $validation->getError('clave') ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check mb-0">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label text-secondary" for="remember" style="font-size:.9rem">Recordarme</label>
        </div>
        <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none" style="font-size:.9rem; color:#07b889">
            ¿Olvidaste tu contraseña?
        </a>
    </div>

    <button type="submit" class="btn btn-login btn-primary w-100 text-white" id="btnLogin">
        <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
    </button>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // ── Toggle contraseña ────────────────────────────────
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('clave');
        const icon  = document.getElementById('toggleIcon');
        const visible = input.type === 'text';
        input.type = visible ? 'password' : 'text';
        icon.classList.toggle('fa-eye',       visible);
        icon.classList.toggle('fa-eye-slash', !visible);
    });

    // ── Loading state al enviar ──────────────────────────
    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('btnLogin');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Ingresando...';
        btn.disabled  = true;
        setTimeout(() => { btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión'; btn.disabled = false; }, 4000);
    });

    // ── Auto-cerrar alertas en 5 s ───────────────────────
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => bootstrap.Alert.getOrCreateInstance(el).close());
    }, 5000);
</script>
<?= $this->endSection() ?>
