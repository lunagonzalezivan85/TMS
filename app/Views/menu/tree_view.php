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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="alert alert-info mb-0 py-2 flex-grow-1 me-2">
            <i class="fas fa-info-circle"></i>
            <strong>Arrastra y suelta</strong> los elementos para reordenarlos. Usa el ícono <i class="fas fa-grip-vertical text-muted"></i> para mover. Click en <i class="fas fa-chevron-down text-muted"></i> para colapsar.
        </div>
        <button type="button" class="btn btn-success btn-sm" id="btnGuardarOrden">
            <i class="fas fa-save"></i> Guardar Orden
        </button>
    </div>

    <div class="menu-tree" id="menuTreeContainer">
        <?php
        $orden_global = 0;
        function renderMenuItems($menus, &$orden_global) {
            $html = '<div class="sortable-list" data-parent-id="">';
            foreach ($menus as $menu):
                $orden_global++;
                $hasChildren = !empty($menu['children']);
                $html .= '<div class="menu-item level-' . $menu['nivel'] . ($hasChildren ? ' has-children' : '') . '" data-id="' . $menu['id'] . '" data-orden="' . $orden_global . '">';
                $html .= '<div class="menu-content">';
                $html .= '<div class="menu-info">';
                $html .= '<i class="fas fa-grip-vertical drag-handle text-muted me-2"></i>';
                if ($hasChildren):
                    $html .= '<i class="fas fa-chevron-down toggle-children text-muted me-2" style="cursor:pointer; font-size:12px;"></i>';
                endif;
                $html .= '<span class="menu-icon"><i class="' . esc($menu['icono']) . '"></i></span>';
                $html .= '<span class="menu-name">' . esc($menu['menu']) . '</span>';
                $html .= '<span class="badge bg-secondary ms-2">Nivel ' . $menu['nivel'] . '</span>';
                $html .= '</div>';
                $html .= '<div class="menu-actions">';
                $html .= '<a href="' . base_url('menu/show/' . $menu['id']) . '" class="btn btn-sm btn-outline-info" title="Ver detalles"><i class="fas fa-eye"></i></a>';
                $html .= '<a href="' . base_url('menu/edit/' . $menu['id']) . '" class="btn btn-sm btn-outline-warning" title="Editar"><i class="fas fa-edit"></i></a>';
                if (!$hasChildren):
                    $html .= '<button type="button" class="btn btn-sm btn-outline-danger eliminar-menu-tree" data-id="' . $menu['id'] . '" title="Eliminar"><i class="fas fa-trash"></i></button>';
                else:
                    $html .= '<button type="button" class="btn btn-sm btn-outline-secondary" disabled title="No se puede eliminar: tiene submenús"><i class="fas fa-ban"></i></button>';
                endif;
                $html .= '</div>';
                $html .= '</div>';
                if ($hasChildren):
                    $html .= '<div class="submenu-container">';
                    $html .= renderMenuItems($menu['children'], $orden_global);
                    $html .= '</div>';
                endif;
                $html .= '</div>';
            endforeach;
            $html .= '</div>';
            return $html;
        }
        echo renderMenuItems($menus, $orden_global);
        ?>
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

.menu-item.level-1 { border-left-color: #4e73df; margin-left: 0; }
.menu-item.level-2 { border-left-color: #1cc88a; margin-left: 20px; }
.menu-item.level-3 { border-left-color: #36b9cc; margin-left: 40px; }
.menu-item.level-4 { border-left-color: #f6c23e; margin-left: 60px; }
.menu-item.level-5 { border-left-color: #e74a3b; margin-left: 80px; }

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

.drag-handle {
    cursor: grab;
    font-size: 14px;
}

.drag-handle:active {
    cursor: grabbing;
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
    transition: max-height 0.3s ease, opacity 0.3s ease;
    overflow: hidden;
}

.menu-item.collapsed > .submenu-container {
    max-height: 0 !important;
    opacity: 0;
    margin-top: 0;
    padding-top: 0;
    padding-bottom: 0;
}

.toggle-children {
    transition: transform 0.2s ease;
}

.menu-item.collapsed > .menu-content .toggle-children {
    transform: rotate(-90deg);
}

.sortable-list {
    min-height: 20px;
}

.sortable-ghost {
    opacity: 0.4;
    background: #c8ebfb;
}

.sortable-chosen {
    box-shadow: 0 0 0 2px #4e73df;
}

.sortable-drag {
    opacity: 0.9;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

#btnGuardarOrden {
    white-space: nowrap;
}

#btnGuardarOrden.saving {
    pointer-events: none;
    opacity: 0.7;
}

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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Sortable en cada lista
    $('.sortable-list').each(function() {
        Sortable.create(this, {
            group: 'menu-tree',
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onMove: function(evt) {
                return evt.related && evt.related.parentNode.classList.contains('sortable-list');
            }
        });
    });

    // Colapsar/expandir nivel 1
    $(document).on('click', '.toggle-children', function(e) {
        e.stopPropagation();
        $(this).closest('.menu-item').toggleClass('collapsed');
    });

    // Colapsar todo por defecto (solo nivel 1 que tiene hijos)
    $('.menu-item.level-1.has-children').addClass('collapsed');

    // Guardar orden
    $('#btnGuardarOrden').on('click', function() {
        var items = [];
        var orden = 0;

        $('.sortable-list').each(function() {
            var parentId = $(this).data('parent-id') || '';
            $(this).children('.menu-item').each(function() {
                orden++;
                items.push({
                    id: $(this).data('id'),
                    orden: orden,
                    parent: parentId
                });
            });
        });

        if (items.length === 0) return;

        var btn = $(this);
        btn.addClass('saving').html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
            url: '<?= base_url('menu/reorder') ?>',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ items: items }),
            success: function(response) {
                btn.removeClass('saving').html('<i class="fas fa-save"></i> Guardar Orden');
                if (response.success) {
                    showAlert('success', response.message);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                btn.removeClass('saving').html('<i class="fas fa-save"></i> Guardar Orden');
                showAlert('error', 'Error al guardar el orden');
            }
        });
    });

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

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

        const alert = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
            '<i class="fas ' + iconClass + '"></i> ' + message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>';

        $('.container-fluid').prepend(alert);
        setTimeout(function() { $('.alert').fadeOut(); }, 5000);
    }
});
</script>
