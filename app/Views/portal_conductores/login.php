<?= $this->extend('layouts/portal_oneui') ?>
<?= $this->section('content') ?>

<style>
.pin-wrap { max-width: 360px; margin: 0 auto; }
.pin-header { text-align: center; margin-bottom: 2rem; }
.pin-header .icon {
    width: 72px; height: 72px;
    background: var(--primary);
    border-radius: 20px;
    display: inline-flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.75rem;
    margin-bottom: 1rem;
}
.pin-header h4 { font-weight: 800; }
.pin-header p { color: var(--muted); font-size: .85rem; }
</style>

<div class="pin-wrap animate__animated animate__fadeIn">
    <div class="pin-header">
        <div class="icon"><i class="fas fa-truck"></i></div>
        <h4>Portal de Conductores</h4>
        <p>Ingresa tu PIN de 6 dígitos para acceder</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger rounded-3 mb-3" style="font-size:.85rem;">
        <i class="fas fa-exclamation-circle me-1"></i><?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('portal/login') ?>" id="pinForm">
        <?= csrf_field() ?>
        <div class="pin-input mb-4">
            <?php for ($i = 0; $i < 6; $i++): ?>
            <input type="text" class="pin-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="<?= $i ?>" autocomplete="off">
            <?php endfor; ?>
        </div>
        <input type="hidden" name="pin" id="pinValue">
        <button type="submit" class="btn btn-oneui btn-oneui-primary w-100" id="btnLogin" disabled>
            <i class="fas fa-sign-in-alt me-1"></i> Acceder
        </button>
    </form>

    <div class="text-center mt-4">
        <small class="text-muted">¿No tienes PIN? Contacta a tu supervisor</small>
    </div>
</div>

<script>
const digits = document.querySelectorAll('.pin-digit');
const pinValue = document.getElementById('pinValue');
const btnLogin = document.getElementById('btnLogin');

digits.forEach((input, i) => {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value && i < 5) digits[i + 1].focus();
        updatePin();
    });
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value && i > 0) {
            digits[i - 1].focus();
            digits[i - 1].value = '';
            updatePin();
        }
    });
    input.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
        paste.split('').forEach((ch, j) => { if (digits[j]) digits[j].value = ch; });
        if (paste.length > 0) digits[Math.min(paste.length, 5)].focus();
        updatePin();
    });
});

function updatePin() {
    const pin = [...digits].map(d => d.value).join('');
    pinValue.value = pin;
    btnLogin.disabled = pin.length !== 6;
}

digits[0].focus();
</script>

<?= $this->endSection() ?>
