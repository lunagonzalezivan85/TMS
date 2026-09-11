<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
/* Reset y estilos base para evitar conflictos */
.kanban-container * {
    box-sizing: border-box;
}

/* Estilo moderno para Kanban Board */
.kanban-container {
   
    min-height: 100vh !important;
    padding: 20px 0 !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
}

.kanban-container .kanban-header-section {
    background: white !important;
    border-radius: 12px !important;
    padding: 20px !important;
    margin-bottom: 20px !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
    border: none !important;
}

.kanban-container .quick-filters {
    display: flex !important;
    gap: 15px !important;
    margin-bottom: 20px !important;
    flex-wrap: wrap !important;
}

.kanban-container .filter-btn {
    padding: 8px 16px !important;
    border: 1px solid #e1e5e9 !important;
    background: white !important;
    border-radius: 20px !important;
    font-size: 14px !important;
    color: #5a6c7d !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    text-decoration: none !important;
    display: inline-block !important;
}

.kanban-container .filter-btn:hover, 
.kanban-container .filter-btn.active {
    background: #0052cc !important;
    color: white !important;
    border-color: #0052cc !important;
    text-decoration: none !important;
}

.kanban-container .kanban-board {
    display: flex !important;
    gap: 20px !important;
    overflow-x: auto !important;
    padding: 0 10px 20px !important;
    min-height: 70vh !important;
}

.kanban-container .kanban-column {
    min-width: 280px !important;
    max-width: 320px !important;
    background: #f4f5f7 !important;
    border-radius: 8px !important;
    padding: 0 !important;
    flex-shrink: 0 !important;
    margin: 0 !important;
}

.kanban-container .column-header {
    padding: 16px 20px !important;
    background: white !important;
    border-radius: 8px 8px 0 0 !important;
    border-bottom: 1px solid #dfe1e6 !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin: 0 !important;
}

.kanban-container .column-title {
    font-weight: 600 !important;
    font-size: 14px !important;
    color: #172b4d !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin: 0 !important;
}

.kanban-container .column-count {
    background: #dfe1e6 !important;
    color: #5e6c84 !important;
    padding: 2px 8px !important;
    border-radius: 12px !important;
    font-size: 12px !important;
    font-weight: 500 !important;
}

.kanban-container .kanban-cards {
    padding: 8px !important;
    min-height: 400px !important;
    max-height: 600px !important;
    overflow-y: auto !important;
}

.kanban-container .kanban-card {
    background: white !important;
    border-radius: 8px !important;
    padding: 16px !important;
    margin-bottom: 8px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12) !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    border: 1px solid #dfe1e6 !important;
    position: relative !important;
}

.kanban-container .kanban-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    transform: translateY(-1px) !important;
}

.kanban-container .kanban-card::before {
    content: '' !important;
    position: absolute !important;
    left: 0 !important;
    top: 0 !important;
    bottom: 0 !important;
    width: 4px !important;
    border-radius: 4px 0 0 4px !important;
}

.kanban-container .kanban-card.prioridad-alta::before { background: #ff5630 !important; }
.kanban-container .kanban-card.prioridad-media::before { background: #ffab00 !important; }
.kanban-container .kanban-card.prioridad-normal::before { background: #36b37e !important; }
.kanban-container .kanban-card.prioridad-baja::before { background: #6b778c !important; }

.kanban-container .card-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    margin-bottom: 12px !important;
}

.kanban-container .card-id {
    font-size: 12px !important;
    color: #5e6c84 !important;
    font-weight: 500 !important;
    background: #f4f5f7 !important;
    padding: 2px 6px !important;
    border-radius: 4px !important;
}

.kanban-container .card-priority {
    font-size: 10px !important;
    padding: 2px 6px !important;
    border-radius: 3px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
}

.kanban-container .priority-alta { background: #ffebe6 !important; color: #bf2600 !important; }
.kanban-container .priority-media { background: #fffae6 !important; color: #974f00 !important; }
.kanban-container .priority-normal { background: #e3fcef !important; color: #006644 !important; }
.kanban-container .priority-baja { background: #f4f5f7 !important; color: #5e6c84 !important; }

.kanban-container .card-title {
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #172b4d !important;
    line-height: 1.4 !important;
    margin-bottom: 12px !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
}

.kanban-container .card-meta {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    font-size: 12px !important;
    color: #5e6c84 !important;
}

.kanban-container .card-vehicle {
    display: flex !important;
    align-items: center !important;
    gap: 4px !important;
}

.kanban-container .card-date {
    font-size: 11px !important;
}

.kanban-container .card-assignee {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    margin-top: 8px !important;
    padding-top: 8px !important;
    border-top: 1px solid #f4f5f7 !important;
}

.kanban-container .assignee-avatar {
    width: 24px !important;
    height: 24px !important;
    border-radius: 50% !important;
    background: #0052cc !important;
    color: white !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 10px !important;
    font-weight: 600 !important;
}

.kanban-empty {
    text-align: center;
    padding: 40px 20px;
    color: #8993a4;
}

.kanban-empty i {
    font-size: 32px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.kanban-empty p {
    font-size: 14px;
    margin: 0;
}

.dragging {
    opacity: 0.6;
    transform: rotate(2deg);
}

.drag-over {
    background: #e3f2fd !important;
}

/* Responsive */
@media (max-width: 768px) {
    .kanban-board {
        flex-direction: column;
        gap: 15px;
    }
    
    .kanban-column {
        min-width: 100%;
        max-width: 100%;
    }
    
    .quick-filters {
        justify-content: center;
    }
}

/* Scrollbar personalizado */
.kanban-cards::-webkit-scrollbar {
    width: 6px;
}
.kanban-cards::-webkit-scrollbar-track {
    background: #f4f5f7;
    border-radius: 3px;
}
.kanban-cards::-webkit-scrollbar-thumb {
    background: #dfe1e6;
    border-radius: 3px;
}
.kanban-cards::-webkit-scrollbar-thumb:hover {
    background: #c1c7d0;
}

/* ===== Kanban visual-only (scoped) ===== */
.kb-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 10px;
    margin: 6px 0 10px;
}
.kb-title {
    font-weight: 600;
    color: #172b4d;
    font-size: 14px;
}
.kb-count {
    background: #e9ecef;
    color: #6c757d;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 12px;
}
.kb-card {
    border: 1px solid #e9ecef;
    border-left: 4px solid #6c757d;
    transition: .15s box-shadow;
}
.kb-card:hover{box-shadow:0 6px 18px rgba(0,0,0,.08)}
.kb-badge-alta{background:#ffebe6;color:#b71f00}
.kb-badge-media{background:#fff3cd;color:#7a4d00}
.kb-badge-normal{background:#e3fcef;color:#0a5c2c}
.kb-badge-baja{background:#eef2f7;color:#5e6c84}
.kb-pri-alta{border-left-color:#dc3545}
.kb-pri-media{border-left-color:#fd7e14}
.kb-pri-normal{border-left-color:#28a745}
.kb-pri-baja{border-left-color:#6c757d}

/* Divisor entre columnas */
@media (min-width: 992px) { /* >= lg */
  .kb-col{ position: relative; border-right: 1px solid #e9ecef; }
  .kb-col:not(:last-child)::after{
    content: '';
    position: absolute;
    top: 0;
    right: -8px; /* centra la línea en el gap */
    width: 1px;
    height: 100%;
    background: #e9ecef;
  }
}
@media (max-width: 991.98px) { /* < lg */
  .kb-col{ border-bottom: 1px solid #e9ecef; padding-bottom: 8px; margin-bottom: 8px; }
}

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

 
    <div class="container-fluid" style="max-width: 100% !important; padding: 0 15px !important;">
        <!-- Header Section -->
        <div class="kanban-header-section">
                <div>
                    <h2 class="mb-1" style="color: #172b4d; font-weight: 600;">Órdenes de Trabajo - Kanban Board</h2>
                    <p class="text-muted mb-0">Gestiona el flujo de trabajo de las órdenes de mantenimiento</p>
                </div>
                <div>
                    <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-outline-secondary me-2" style="text-decoration: none;">
                        <i class="fas fa-list me-1"></i>Vista Lista
                    </a>
                    <a href="<?= base_url('ordenes-trabajo/calendario') ?>" class="btn btn-outline-info me-2" style="text-decoration: none;">
                        <i class="fas fa-calendar-alt me-1"></i>Calendario
                    </a>
                    <a href="<?= base_url('ordenes-trabajo/create') ?>" class="btn btn-primary" style="text-decoration: none;">
                        <i class="fas fa-plus me-1"></i>Nueva Orden
                    </a>
                </div>
            </div>

            <!-- Quick Filters -->
           
        </div>

        <!-- Tablero Kanban -->
        <div>
            <?php
            $kb_labels = [
                'PENDIENTE' => 'Por hacer',
                'EN_PROCESO' => 'En progreso',
                'FINALIZADO' => 'Finalizadas',
                'RECHAZADO' => 'Rechazadas'
            ];
            $kb_order = ['PENDIENTE','EN_PROCESO','FINALIZADO','RECHAZADO'];
            ?>
            <div class="row g-3">
                <?php foreach ($kb_order as $estado): ?>
                    <div class="col-12 col-md-6 col-lg-3 kb-col border-1">
                        <div class="kb-header">
                            <span class="kb-title"><?= $kb_labels[$estado] ?></span>
                            <span class="kb-count"><?= $conteos[$estado] ?? 0 ?></span>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <?php if (empty($solicitudes_por_estado[$estado])): ?>
                                <div class="text-muted small">No hay órdenes</div>
                            <?php else: ?>
                                <?php foreach ($solicitudes_por_estado[$estado] as $solicitud): ?>
                                    <?php $pri = strtolower($solicitud['prioridad'] ?? 'normal'); ?>
                                    <a href="<?= base_url('ordenes-trabajo/show/' . $solicitud['id']) ?>" class="text-decoration-none">
                                        <div class="card kb-card kb-pri-<?= $pri ?>">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="small text-muted">#<?= esc($solicitud['codigo_consecutivo'] ?? ('ORD-'.$solicitud['id'])) ?></span>
                                                    <?php if (!empty($solicitud['prioridad']) && $solicitud['prioridad'] !== 'NORMAL'): ?>
                                                        <span class="badge kb-badge-<?= $pri ?>"><?= esc($solicitud['prioridad']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="fw-semibold text-dark mb-1" style="line-height:1.2"><?= esc($solicitud['descripcion']) ?></div>
                                                <div class="d-flex justify-content-between small text-muted">
                                                    <span><i class="fas fa-car me-1"></i><?= esc($solicitud['placa'] ?? 'Sin placa') ?></span>
                                                    <span><i class="far fa-calendar me-1"></i><?= date('Y-m-d', strtotime($solicitud['fecha_solicitud'])) ?></span>
                                                </div>
                                                <?php if (!empty($solicitud['mecanico_asignado'])): ?>
                                                    <div class="small text-muted mt-1"><i class="fas fa-user-cog me-1"></i><?= esc($solicitud['mecanico_asignado']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Vista Kanban solo visual; sin drag & drop ni filtros.
</script>
<?= $this->endSection() ?>
