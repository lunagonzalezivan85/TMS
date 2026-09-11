<?php if (empty($menus)): ?>
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle fa-3x mb-3"></i>
        <h5>No hay menús registrados</h5>
        <p>Comienza creando tu primer menú para estructurar el sistema.</p>
        <a href="<?= base_url('menu/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Primer Menú
        </a>
    </div>
<?php else: ?>
    <div class="menu-tree">
        <?php foreach ($menus as $menu): ?>
            <div class="menu-item level-<?= $menu['nivel'] ?>" data-id="<?= $menu['id'] ?>">
                <div class="menu-content">
                    <div class="menu-info">
                        <span class="menu-icon">
                            <i class="<?= esc($menu['icono']) ?>"></i>
                        </span>
                        <span class="menu-name">
                            <?= esc($menu['menu']) ?>
                        </span>
                        <span class="badge bg-secondary ms-2">
                            Nivel <?= $menu['nivel'] ?>
                        </span>
                    </div>
                    <div class="menu-actions">
                        <a href="<?= base_url('menu/show/' . $menu['id']) ?>" 
                           class="btn btn-sm btn-outline-info" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?= base_url('menu/edit/' . $menu['id']) ?>" 
                           class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if (empty($menu['children'])): ?>
                            <button type="button" class="btn btn-sm btn-outline-danger eliminar-menu-tree" 
                                    data-id="<?= $menu['id'] ?>" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                    disabled title="No se puede eliminar: tiene submenús">
                                <i class="fas fa-ban"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($menu['children'])): ?>
                    <div class="submenu-container">
                        <?= view('menu/tree_view', ['menus' => $menu['children']]) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.menu-tree {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.menu-item {
    margin-bottom: 8px;
    border-left: 3px solid #e3e6f0;
    transition: all 0.3s ease;
}

.menu-item.level-1 {
    border-left-color: #4e73df;
    margin-left: 0;
}

.menu-item.level-2 {
    border-left-color: #1cc88a;
    margin-left: 20px;
}

.menu-item.level-3 {
    border-left-color: #36b9cc;
    margin-left: 40px;
}

.menu-item.level-4 {
    border-left-color: #f6c23e;
    margin-left: 60px;
}

.menu-item.level-5 {
    border-left-color: #e74a3b;
    margin-left: 80px;
}

.menu-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: #fff;
    border: 1px solid #e3e6f0;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.menu-content:hover {
    background: #f8f9fc;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-1px);
}

.menu-info {
    display: flex;
    align-items: center;
    flex-grow: 1;
}

.menu-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fc;
    border-radius: 50%;
    margin-right: 12px;
    font-size: 14px;
    color: #5a5c69;
}

.menu-name {
    font-weight: 600;
    color: #5a5c69;
    font-size: 14px;
}

.menu-actions {
    display: flex;
    gap: 4px;
}

.submenu-container {
    margin-top: 8px;
    padding-left: 15px;
    border-left: 2px dashed #e3e6f0;
}

/* Animaciones */
.menu-item {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .menu-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .menu-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .menu-item.level-2,
    .menu-item.level-3,
    .menu-item.level-4,
    .menu-item.level-5 {
        margin-left: 10px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Manejar eliminación desde vista de árbol
    $(document).on('click', '.eliminar-menu-tree', function() {
        const menuId = $(this).data('id');
        
        if (confirm('¿Estás seguro de que deseas eliminar este menú?')) {
            $.ajax({
                url: '<?= base_url('menu/delete') ?>/' + menuId,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function() {
                    showAlert('error', 'Error al eliminar el menú');
                }
            });
        }
    });
    
    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('.container-fluid').prepend(alert);
        
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
