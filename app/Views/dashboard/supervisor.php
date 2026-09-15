<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
    .dash-prompt {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
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
    .prompt-input-box {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 16px;
        padding: 0.85rem 1.25rem;
        color: white;
        cursor: text;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: background 0.15s;
        position: relative;
        z-index: 1;
    }
    .prompt-input-box:hover { background: rgba(255,255,255,0.22); }
    .prompt-input-box input { background: transparent; border: none; outline: none; color: white; flex: 1; font-size: 1rem; }
    .prompt-input-box input::placeholder { color: rgba(255,255,255,0.75); }
    .prompt-input-box i { color: rgba(255,255,255,0.8); }
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
    .cmd-item:hover, .cmd-item.active { background: #eef2ff; }
    .cmd-item i { width: 24px; text-align: center; color: #4f46e5; }
    .kbd-hint { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #94a3b8; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.15rem 0.4rem; }
    .summary-stat {
        display: flex; flex-direction: column; align-items: flex-start;
        padding: 1rem; border-radius: 14px; background: #f8fafc;
        border: 1px solid #e2e8f0; height: 100%;
    }
    .summary-stat .number { font-size: 1.7rem; font-weight: 700; color: #0f172a; line-height: 1; }
    .summary-stat .label { font-size: 0.8rem; color: #64748b; margin-top: 0.35rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="dash-prompt">
        <div class="row align-items-end">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <h2 class="fw-bold mb-2">Hola, Supervisor 👋</h2>
                <p class="mb-0">¿Qué deseas supervisar hoy?</p>
            </div>
        </div>
        <div class="prompt-input-box mt-3" onclick="focusPrompt()">
            <i class="fas fa-sparkles"></i>
            <input type="text" id="promptInput" placeholder="Escribe una acción o presiona Ctrl + K..." autocomplete="off">
            <span class="kbd-hint" style="border-color:rgba(255,255,255,0.4);color:rgba(255,255,255,0.8);">Enter</span>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="<?= base_url('solicitudes') ?>" class="chip text-decoration-none text-dark">
            <i class="fas fa-clipboard-list text-warning"></i>
            <span>Solicitudes</span>
            <span class="chip-count bg-warning text-dark"><?= $stats['total_maintenance'] ?></span>
        </a>
        <a href="<?= base_url('solicitudes') ?>" class="chip text-decoration-none text-dark">
            <i class="fas fa-exclamation-circle text-primary"></i>
            <span>Pendientes</span>
            <span class="chip-count bg-primary text-white"><?= $stats['pending_maintenance'] ?></span>
        </a>
        <a href="<?= base_url('solicitudes') ?>" class="chip text-decoration-none text-dark">
            <i class="fas fa-wrench text-success"></i>
            <span>En mantenimiento</span>
            <span class="chip-count bg-success text-white"><?= $stats['vehicles_maintenance'] ?></span>
        </a>
        <a href="<?= base_url('vehiculos') ?>" class="chip text-decoration-none text-dark">
            <i class="fas fa-car text-info"></i>
            <span>Vehículos</span>
            <span class="chip-count bg-info text-white"><?= $stats['total_vehicles'] ?></span>
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Resumen del día</h5>
                <span class="text-muted small"><?= date('d/m/Y') ?></span>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="summary-stat">
                        <span class="number text-warning"><?= $stats['total_maintenance'] ?></span>
                        <span class="label">Solicitudes totales</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="summary-stat">
                        <span class="number text-primary"><?= $stats['pending_maintenance'] ?></span>
                        <span class="label">Por asignar</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="summary-stat">
                        <span class="number text-success"><?= $stats['vehicles_maintenance'] ?></span>
                        <span class="label">Vehículos en taller</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="summary-stat">
                        <span class="number text-info"><?= $stats['total_vehicles'] ?></span>
                        <span class="label">Vehículos activos</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex flex-wrap gap-2">
                <a href="<?= base_url('solicitudes/create') ?>" class="btn btn-primary rounded-pill"><i class="fas fa-plus-circle me-2"></i>Nueva solicitud</a>
                <a href="<?= base_url('solicitudes') ?>" class="btn btn-outline-primary rounded-pill"><i class="fas fa-user-cog me-2"></i>Asignar técnicos</a>
                <a href="<?= base_url('vehiculos') ?>" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-car me-2"></i>Revisar vehículos</a>
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
function focusPrompt() { document.getElementById('promptInput').focus(); }
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
    if (document.getElementById('cmdPaletteOverlay').style.display !== 'none') {
        if (e.key === 'Escape') cerrarCommandPalette();
        else if (e.key === 'ArrowDown') { e.preventDefault(); selectedIndex = Math.min(selectedIndex + 1, document.querySelectorAll('.cmd-item').length - 1); updateActive(); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); selectedIndex = Math.max(selectedIndex - 1, 0); updateActive(); }
        else if (e.key === 'Enter' && selectedIndex >= 0) { const items = document.querySelectorAll('.cmd-item'); if (items[selectedIndex]) window.location.href = items[selectedIndex].dataset.url; }
    }
});
document.getElementById('promptInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); abrirCommandPalette(); }
});
document.getElementById('cmdInput').addEventListener('input', function() { selectedIndex = -1; renderItems(this.value); });
function updateActive() { document.querySelectorAll('.cmd-item').forEach((el, i) => el.classList.toggle('active', i === selectedIndex)); }
</script>
<?= $this->endSection() ?>
