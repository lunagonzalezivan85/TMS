<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
    .dash-prompt {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border-radius: 24px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .dash-prompt::after {
        content: ''; position: absolute; top: -40%; right: -10%; width: 300px; height: 300px;
        background: rgba(255,255,255,0.1); border-radius: 50%;
    }
    .dash-prompt h2 { position: relative; z-index: 1; }
    .dash-prompt p { position: relative; z-index: 1; opacity: 0.9; }
    .chip {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: #fff; border: 1px solid #e2e8f0; border-radius: 9999px;
        padding: 0.65rem 1rem; font-weight: 500; transition: all 0.15s ease;
    }
    .chip:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(0,0,0,0.05); }
    .chip i { font-size: 1.1rem; }
    .chip-count {
        min-width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 50%; font-size: 0.75rem; font-weight: 700;
    }
    .cmd-palette-overlay {
        position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px); z-index: 1055; display: none;
        align-items: flex-start; justify-content: center; padding-top: 10vh;
    }
    .cmd-palette {
        width: 100%; max-width: 560px; background: #fff;
        border-radius: 18px; box-shadow: 0 24px 60px rgba(0,0,0,0.25); overflow: hidden;
    }
    .cmd-palette-header { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; }
    .cmd-palette-header input { border: none; outline: none; flex: 1; font-size: 1rem; }
    .cmd-palette-list { max-height: 320px; overflow-y: auto; }
    .cmd-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.85rem 1.25rem; cursor: pointer; border-bottom: 1px solid #f1f5f9; transition: background 0.1s;
    }
    .cmd-item:hover, .cmd-item.active { background: #fffbeb; }
    .cmd-item i { width: 24px; text-align: center; color: #d97706; }
    .kbd-hint { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #94a3b8; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.15rem 0.4rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="dash-prompt">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">Hola, Técnico 👋</h2>
                <p class="mb-0">Consulta tus trabajos asignados y registra avances. Usa <span class="kbd-hint"><i class="fas fa-command"></i> Ctrl K</span>.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <button class="btn btn-light rounded-pill px-4" onclick="abrirCommandPalette()">
                    <i class="fas fa-bolt me-2 text-warning"></i>Acciones rápidas
                </button>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="<?= base_url('solicitudes') ?>" class="chip text-decoration-none text-dark">
            <i class="fas fa-wrench text-warning"></i>
            <span>Asignados a mí</span>
            <span class="chip-count bg-warning text-white"><?= $stats['assigned_to_me'] ?></span>
        </a>
        <a href="<?= base_url('solicitudes') ?>" class="chip text-decoration-none text-dark">
            <i class="fas fa-clipboard-list text-primary"></i>
            <span>Solicitudes abiertas</span>
            <span class="chip-count bg-primary text-white"><?= $stats['pending_maintenance'] ?></span>
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-list-check me-2 text-warning"></i>Mis trabajos</h6>
                    <p class="text-muted mb-3">Solicitudes asignadas pendientes: <strong><?= $stats['assigned_to_me'] ?></strong></p>
                    <a href="<?= base_url('solicitudes') ?>" class="btn btn-warning rounded-pill text-white"><i class="fas fa-eye me-2"></i>Ver asignaciones</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-clock me-2 text-warning"></i>Próximos pasos</h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i>Revisar solicitudes asignadas</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i>Diagnosticar fallas</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i>Actualizar estado del trabajo</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cmd-palette-overlay" id="cmdPaletteOverlay" onclick="cerrarCommandPalette(event)">
    <div class="cmd-palette" onclick="event.stopPropagation()">
        <div class="cmd-palette-header">
            <i class="fas fa-search text-muted"></i>
            <input type="text" id="cmdInput" placeholder="Buscar acción..." autocomplete="off">
            <span class="kbd-hint">ESC</span>
        </div>
        <div class="cmd-palette-list" id="cmdList"></div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const comandos = <?= json_encode($comandos) ?>;
let selectedIndex = -1;

function abrirCommandPalette() {
    document.getElementById('cmdPaletteOverlay').style.display = 'flex';
    document.getElementById('cmdInput').value = '';
    document.getElementById('cmdInput').focus();
    selectedIndex = -1;
    renderItems();
}
function cerrarCommandPalette() { document.getElementById('cmdPaletteOverlay').style.display = 'none'; }
function renderItems(filter = '') {
    const list = document.getElementById('cmdList');
    list.innerHTML = '';
    comandos.filter(c => c.label.toLowerCase().includes(filter.toLowerCase())).forEach((cmd, i) => {
        const div = document.createElement('div');
        div.className = 'cmd-item' + (i === selectedIndex ? ' active' : '');
        div.dataset.url = cmd.url;
        div.innerHTML = `<i class="fas ${cmd.icon}"></i><span>${cmd.label}</span>`;
        div.onclick = () => window.location.href = cmd.url;
        list.appendChild(div);
    });
}
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'k') { e.preventDefault(); abrirCommandPalette(); return; }
    if (document.getElementById('cmdPaletteOverlay').style.display === 'none') return;
    if (e.key === 'Escape') cerrarCommandPalette();
    else if (e.key === 'ArrowDown') { e.preventDefault(); selectedIndex = Math.min(selectedIndex + 1, document.querySelectorAll('.cmd-item').length - 1); updateActive(); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); selectedIndex = Math.max(selectedIndex - 1, 0); updateActive(); }
    else if (e.key === 'Enter' && selectedIndex >= 0) { const items = document.querySelectorAll('.cmd-item'); if (items[selectedIndex]) window.location.href = items[selectedIndex].dataset.url; }
});
document.getElementById('cmdInput').addEventListener('input', function() { selectedIndex = -1; renderItems(this.value); });
function updateActive() { document.querySelectorAll('.cmd-item').forEach((el, i) => el.classList.toggle('active', i === selectedIndex)); }
</script>
<?= $this->endSection() ?>
