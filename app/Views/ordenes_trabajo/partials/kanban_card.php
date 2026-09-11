<?php if (!isset($solicitud) || !is_array($solicitud) || !isset($solicitud['id'])): ?>
    <div class="alert alert-warning">No hay datos de solicitud disponibles</div>
<?php else: ?>
<div class="kanban-card prioridad-<?= strtolower($solicitud['prioridad'] ?? 'normal') ?>" 
     data-solicitud-id="<?= $solicitud['id'] ?>"
     draggable="true">
    
    <div class="card-codigo">
        <?= esc($solicitud['codigo_consecutivo'] ?? 'S-' . $solicitud['id']) ?>
        <?php if (isset($solicitud['prioridad']) && $solicitud['prioridad'] !== 'NORMAL'): ?>
            <span class="prioridad-badge prioridad-<?= strtolower($solicitud['prioridad']) ?>">
                <?= esc($solicitud['prioridad']) ?>
            </span>
        <?php endif; ?>
    </div>
    
    <div class="card-descripcion">
        <?= esc($solicitud['descripcion']) ?>
    </div>
    
    <div class="card-info">
        <div class="card-vehiculo">
            <i class="fas fa-car"></i>
            <span><?= esc($solicitud['placa'] ?? 'Sin asignar') ?></span>
        </div>
        <div class="card-fecha">
            <?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?>
        </div>
    </div>
    
    <?php if (isset($solicitud['mecanico_asignado']) && $solicitud['mecanico_asignado']): ?>
        <div class="card-info mt-2">
            <div class="card-vehiculo">
                <i class="fas fa-user-cog"></i>
                <span><?= esc($solicitud['mecanico_asignado']) ?></span>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="card-actions mt-2" style="display: none;">
        <button class="btn btn-sm btn-outline-primary" onclick="verDetalle(<?= $solicitud['id'] ?>)">
            <i class="fas fa-eye"></i>
        </button>
        <button class="btn btn-sm btn-outline-success" onclick="editarOrden(<?= $solicitud['id'] ?>)">
            <i class="fas fa-edit"></i>
        </button>
    </div>
</div>
<?php endif; ?>
