<?php if (!isset($solicitud) || !is_array($solicitud) || !isset($solicitud['id'])): ?>
    <div class="alert alert-warning">No hay datos de solicitud disponibles</div>
<?php else: ?>
<div class="kanban-card prioridad-<?= strtolower($solicitud['prioridad'] ?? 'normal') ?>" 
     data-solicitud-id="<?= $solicitud['id'] ?>"
     draggable="true">
    
    <!-- Card Header -->
    <div class="card-header">
        <div class="card-id">
            <i class="fas fa-hashtag"></i>
            <?= esc($solicitud['codigo_consecutivo'] ?? 'ORD-' . $solicitud['id']) ?>
        </div>
        <?php if (isset($solicitud['prioridad']) && $solicitud['prioridad'] !== 'NORMAL'): ?>
            <div class="card-priority priority-<?= strtolower($solicitud['prioridad']) ?>">
                <i class="fas fa-arrow-up"></i>
                <?= esc($solicitud['prioridad']) ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Card Title -->
    <div class="card-title">
        <?= esc($solicitud['descripcion']) ?>
    </div>
    
    <!-- Card Meta -->
    <div class="card-meta">
        <div class="card-vehicle">
            <i class="fas fa-car"></i>
            <span><?= esc($solicitud['placa'] ?? 'Sin asignar') ?></span>
        </div>
        <div class="card-date">
            <?= date('M d', strtotime($solicitud['fecha_solicitud'])) ?>
        </div>
    </div>
    
    <!-- Card Assignee -->
    <?php if (isset($solicitud['mecanico_asignado']) && $solicitud['mecanico_asignado']): ?>
        <div class="card-assignee">
            <div class="assignee-avatar">
                <?= strtoupper(substr($solicitud['mecanico_asignado'], 0, 2)) ?>
            </div>
            <span style="font-size: 12px; color: #5e6c84;">
                <?= esc($solicitud['mecanico_asignado']) ?>
            </span>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>
