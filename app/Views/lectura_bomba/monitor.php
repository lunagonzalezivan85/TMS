<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
    /* Monitor de Bombas - Adaptado a colores del sistema GMV */
    .monitor-dashboard {
        background-color: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 25px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .monitor-header {
        border-bottom: 2px solid var(--border);
        padding-bottom: 15px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .monitor-header h2 {
        color: var(--primary);
        font-size: 24px;
        font-weight: 600;
        margin: 0;
    }

    .monitor-header p {
        color: var(--muted);
        font-size: 14px;
        margin: 5px 0 0 0;
    }

    .system-status {
        display: flex;
        align-items: center;
        background-color: var(--primary-50);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        border: 1px solid var(--primary-50);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        margin-right: 8px;
    }

    .status-dot.offline {
        background-color: #ef4444;
    }

    .monitor-content {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .panel-controls {
        flex: 1;
        min-width: 300px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .panel-graphic {
        flex: 1.2;
        min-width: 350px;
        background-color: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .monitor-card {
        background-color: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .monitor-card-label {
        font-size: 12px;
        color: var(--muted);
        text-transform: uppercase;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .monitor-card-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text);
        font-family: 'Inter', monospace;
    }

    .monitor-card-value span {
        font-size: 16px;
        color: var(--muted);
        margin-left: 5px;
        font-weight: 400;
    }

    .btn-live {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 15px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 12px;
        cursor: pointer;
        text-transform: uppercase;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-live:hover {
        background-color: var(--primary-600);
        transform: translateY(-1px);
    }

    .btn-live.active {
        background-color: #ef4444;
    }

    .live-indicator-box {
        background-color: var(--bg);
        border: 1px dashed var(--border);
        border-radius: 12px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        opacity: 0.4;
        transition: all 0.3s ease;
    }

    .live-indicator-box.active {
        opacity: 1;
        border-color: #ef4444;
        background-color: #fef2f2;
    }

    .live-text {
        color: var(--muted);
        font-weight: 600;
        font-size: 14px;
    }

    .live-indicator-box.active .live-text {
        color: #ef4444;
    }

    .pulse-dot {
        width: 12px;
        height: 12px;
        background-color: #ef4444;
        border-radius: 50%;
        display: inline-block;
    }

    .live-indicator-box.active .pulse-dot {
        animation: pulse 1s infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(0.9); opacity: 0.6; }
        100% { transform: scale(1.2); opacity: 1; box-shadow: 0 0 10px #ef4444; }
    }

    .tank-title {
        align-self: flex-start;
        font-size: 16px;
        color: var(--text);
        font-weight: 600;
        margin-bottom: 20px;
        width: 100%;
        border-bottom: 1px solid var(--border);
        padding-bottom: 8px;
    }

    .tank-wrapper {
        display: flex;
        align-items: center;
        gap: 40px;
        width: 100%;
        justify-content: center;
        margin-top: 10px;
    }

    .tank-outer {
        width: 150px;
        height: 260px;
        border: 4px solid var(--border);
        border-radius: 20px;
        position: relative;
        background-color: var(--bg);
        overflow: hidden;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.1);
    }

    .tank-fluid {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(180deg, var(--primary) 0%, var(--primary-600) 100%);
        border-top: 3px solid var(--primary-50);
        transition: height 0.5s ease-out;
    }

    .tank-percentage {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        font-weight: 800;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        z-index: 10;
    }

    .tank-info-text {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .no-data-alert {
        background-color: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }

    .no-data-alert i {
        font-size: 48px;
        color: #f59e0b;
        margin-bottom: 15px;
    }

    .no-data-alert h4 {
        color: #92400e;
        margin-bottom: 10px;
    }

    .no-data-alert p {
        color: #b45309;
        margin-bottom: 15px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="monitor-dashboard">
        <div class="monitor-header">
            <div>
                <h2><i class="fas fa-gas-pump me-2"></i>Monitor de Bombas</h2>
                <p>Control e Inventario de Tanques en Tiempo Real</p>
            </div>
            <div class="system-status">
                <span class="status-dot"></span>
                <span>Sistema Operativo</span>
            </div>
        </div>

        <?php if (empty($apertura_activa)): ?>
            <div class="no-data-alert">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>No hay aperturas activas</h4>
                <p>No se encontraron aperturas de bomba pendientes de cierre para monitorear.</p>
                <a href="<?= base_url('lectura-bomba/apertura') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Nueva Apertura
                </a>
            </div>
        <?php else: ?>
            <div class="monitor-content">
                <div class="panel-controls">
                    <div class="monitor-card" style="cursor: pointer;" onclick="cargarAperturasPendientes()">
                        <div class="monitor-card-label">Centro de Costo <i class="fas fa-info-circle text-muted" style="font-size: 12px;"></i></div>
                        <div class="monitor-card-value" style="font-size: 20px;">
                            <?= esc($apertura_activa['nombre_centro_costo'] ?? $apertura_activa['id_centro_costo']) ?>
                        </div>
                    </div>

                    <div class="monitor-card">
                        <div class="monitor-card-label">Lectura Inicial</div>
                        <div class="monitor-card-value">
                            <?= number_format($apertura_activa['lectura_inicial_litros'], 2) ?><span>L</span>
                        </div>
                    </div>

                    <div class="monitor-card">
                        <div class="monitor-card-label">Litraje Inicial</div>
                        <div class="monitor-card-value" style="color: var(--primary);">
                            <?= number_format($apertura_activa['litraje_inicial_ltr'] ?? 0, 4) ?><span>L</span>
                        </div>
                    </div>

                    <div class="monitor-card" style="cursor: pointer;" onclick="cargarRegistrosCombustible()">
                        <div class="monitor-card-label">Consumo Real (Registros) <i class="fas fa-list text-muted" style="font-size: 12px;"></i></div>
                        <div class="monitor-card-value" id="litros-consumo" style="color: #ef4444;">
                            <?= number_format($consumo_real, 4) ?><span>Lts</span>
                        </div>
                    </div>

                    <button class="btn-live" id="btn-toggle-live">
                        <i class="fas fa-play me-2"></i>Ver en Vivo
                    </button>

                    <div class="live-indicator-box" id="live-box">
                        <div class="live-text" id="live-status-text">MONITOREO DETENIDO</div>
                        <div class="pulse-dot"></div>
                    </div>
                </div>

                <div class="panel-graphic">
                    <div class="tank-title">Tanque Principal de Almacenamiento</div>

                    <?php
                    $litraje_inicial = (float)($apertura_activa['litraje_inicial_ltr'] ?? 0);
                    $volumen_neto = max(0, $litraje_inicial - $consumo_real);
                    $porcentaje_inicial = $litraje_inicial > 0 ? (($volumen_neto / $litraje_inicial) * 100) : 0;
                    ?>

                    <div class="tank-wrapper">
                        <div class="tank-outer">
                            <div class="tank-percentage" id="txt-porcentaje">
                                <?= number_format($porcentaje_inicial, 0) ?>%
                            </div>
                            <div class="tank-fluid" id="fluid-nivel" style="height: <?= $porcentaje_inicial ?>%;"></div>
                        </div>

                        <div class="tank-info-text">
                            <div>
                                <div class="monitor-card-label">Volumen Neto</div>
                                <div class="monitor-card-value" id="litros-actuales" style="color: var(--primary); font-size: 36px;">
                                    <?= number_format($volumen_neto, 4) ?><span>L</span>
                                </div>
                            </div>
                            <div>
                                <div class="monitor-card-label">Capacidad Máx</div>
                                <div class="monitor-card-value" style="font-size: 18px; color: var(--muted);">
                                    <?= number_format($litraje_inicial, 4) ?> L
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Aperturas Pendientes -->
<div class="modal fade" id="modalAperturas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-gas-pump me-2"></i>Aperturas Pendientes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="aperturas-content">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Registros de Combustible -->
<div class="modal fade" id="modalRegistros" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-list me-2"></i>Registros de Combustible
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="registros-content">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para seleccionar una apertura específica
    function seleccionarApertura(id) {
        window.location.href = '<?= base_url('lectura-bomba/monitor') ?>?apertura_id=' + id;
    }

    // Función para cargar registros de combustible en el modal
    async function cargarRegistrosCombustible() {
        const modal = new bootstrap.Modal(document.getElementById('modalRegistros'));
        const content = document.getElementById('registros-content');
        const aperturaId = <?= (int)($apertura_activa['id'] ?? 0) ?>;

        modal.show();
        content.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `;

        try {
            const response = await fetch(`<?= base_url('lectura-bomba/registros-combustible/') ?>${aperturaId}`);
            const data = await response.json();

            if (data.success && data.registros.length > 0) {
                let html = '<div class="table-responsive"><table class="table table-hover table-sm">';
                html += '<thead><tr><th>Fecha</th><th>Placa</th><th>Unidad</th><th>Marca/Modelo</th><th>Km Anterior</th><th>Km Actual</th><th>Litros</th></tr></thead><tbody>';

                let totalLitros = 0;
                data.registros.forEach(registro => {
                    totalLitros += parseFloat(registro.cantidad_litros);
                    html += `
                        <tr>
                            <td>${new Date(registro.fecha_registro).toLocaleString()}</td>
                            <td>${registro.placa || '-'}</td>
                            <td>${registro.codigo_unidad || '-'}</td>
                            <td>${registro.marca || ''} ${registro.modelo || ''}</td>
                            <td>${registro.kilometraje_anterior || '-'}</td>
                            <td>${registro.kilometraje_actual || '-'}</td>
                            <td>${parseFloat(registro.cantidad_litros).toFixed(4)} L</td>
                        </tr>
                    `;
                });

                html += `
                    <tr class="table-primary fw-bold">
                        <td colspan="6" class="text-end">Total:</td>
                        <td>${totalLitros.toFixed(4)} L</td>
                    </tr>
                `;

                html += '</tbody></table></div>';
                content.innerHTML = html;
            } else {
                content.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle text-info fa-3x mb-3"></i>
                        <p class="text-muted">No hay registros de combustible para esta apertura</p>
                    </div>
                `;
            }
        } catch (error) {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar los registros: ${error.message}
                </div>
            `;
        }
    }

    // Función para cargar aperturas pendientes en el modal
    async function cargarAperturasPendientes() {
        const modal = new bootstrap.Modal(document.getElementById('modalAperturas'));
        const content = document.getElementById('aperturas-content');

        modal.show();
        content.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `;

        try {
            const response = await fetch('<?= base_url('lectura-bomba/aperturas-pendientes') ?>');
            const data = await response.json();

            if (data.success && data.aperturas.length > 0) {
                let html = '<div class="table-responsive"><table class="table table-hover">';
                html += '<thead><tr><th>Centro</th><th>Apertura</th><th>Lectura Inicial</th><th>Litraje Inicial</th><th>Acciones</th></tr></thead><tbody>';

                data.aperturas.forEach(apertura => {
                    const esActual = apertura.id === <?= (int)($apertura_activa['id'] ?? 0) ?>;
                    html += `
                        <tr ${esActual ? 'class="table-primary"' : ''}>
                            <td>${apertura.nombre_centro_costo || apertura.id_centro_costo}</td>
                            <td>${new Date(apertura.fecha_apertura).toLocaleString()}</td>
                            <td>${parseFloat(apertura.lectura_inicial_litros).toFixed(2)} L</td>
                            <td>${parseFloat(apertura.litraje_inicial_ltr || 0).toFixed(4)} L</td>
                            <td>
                                <button onclick="seleccionarApertura(${apertura.id})" class="btn btn-sm btn-primary" ${esActual ? 'disabled' : ''} title="${esActual ? 'Actual' : 'Monitorear'}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="<?= base_url('lectura-bomba/cierre/') ?>${apertura.id}" class="btn btn-sm btn-danger" title="Cerrar">
                                    <i class="fas fa-power-off"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });

                html += '</tbody></table></div>';
                content.innerHTML = html;
            } else {
                content.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <p class="text-muted">No hay aperturas pendientes</p>
                    </div>
                `;
            }
        } catch (error) {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar las aperturas: ${error.message}
                </div>
            `;
        }
    }

<?php if (!empty($apertura_activa)): ?>
    const btnLive = document.getElementById('btn-toggle-live');
    const liveBox = document.getElementById('live-box');
    const liveStatusText = document.getElementById('live-status-text');
    const fluidNivel = document.getElementById('fluid-nivel');
    const txtPorcentaje = document.getElementById('txt-porcentaje');
    const litrosActualesTxt = document.getElementById('litros-actuales');
    const litrosConsumoTxt = document.getElementById('litros-consumo');

    let enVivoActivo = false;
    let intervaloActualizacion = null;

    // Datos iniciales desde PHP (datos reales)
    const capacidadMaxima = <?= (float)($apertura_activa['litraje_inicial_ltr'] ?? 0) ?>;
    const litrosConsumidos = <?= (float)$consumo_real ?>;
    let litrosActuales = Math.max(0, capacidadMaxima - litrosConsumidos);
    const aperturaId = <?= (int)($apertura_activa['id']) ?>;

    // Actualizar datos reales desde el servidor
    async function actualizarDatosReales() {
        try {
            const response = await fetch(`<?= base_url('lectura-bomba/obtener-consumo/' . $apertura_activa['id']) ?>`);
            const data = await response.json();

            if (data.success) {
                litrosConsumidos = data.consumo;
                litrosActuales = Math.max(0, capacidadMaxima - litrosConsumidos);

                let porcentaje = capacidadMaxima > 0 ? ((litrosActuales / capacidadMaxima) * 100).toFixed(0) : 0;

                fluidNivel.style.height = `${porcentaje}%`;
                txtPorcentaje.textContent = `${porcentaje}%`;
                litrosActualesTxt.innerHTML = `${litrosActuales.toFixed(4)}<span>L</span>`;
                litrosConsumoTxt.innerHTML = `${litrosConsumidos.toFixed(4)}<span>Lts</span>`;
            }
        } catch (error) {
            console.error('Error al actualizar datos:', error);
        }
    }

    btnLive.addEventListener('click', () => {
        enVivoActivo = !enVivoActivo;

        if (enVivoActivo) {
            btnLive.innerHTML = '<i class="fas fa-stop me-2"></i>Detener Actualización';
            btnLive.classList.add('active');
            liveBox.classList.add('active');
            liveStatusText.textContent = "ACTUALIZANDO DATOS REALES";

            // Actualizar cada 5 segundos
            intervaloActualizacion = setInterval(actualizarDatosReales, 5000);
            actualizarDatosReales(); // Primera actualización inmediata

        } else {
            btnLive.innerHTML = '<i class="fas fa-play me-2"></i>Actualizar en Vivo';
            btnLive.classList.remove('active');
            liveBox.classList.remove('active');
            liveStatusText.textContent = "ACTUALIZACIÓN DETENIDA";
            clearInterval(intervaloActualizacion);
        }
    });
</script>
<?php endif; ?>

<?= $this->endSection() ?>
