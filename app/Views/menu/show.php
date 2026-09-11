<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Detalles del Menú: <?= esc($menu['menu']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="<?= esc($menu['icono']) ?> fa-fw me-2"></i>
            <?= esc($menu['menu']) ?>
        </h1>
        <div class="btn-group" role="group">
            <a href="<?= base_url('menu') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Lista
            </a>
            <a href="<?= base_url('menu/edit/' . $menu['id']) ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <?php if (empty($submenus)): ?>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Breadcrumb -->
    <?php if (!empty($breadcrumb)): ?>
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-3 rounded">
                <li class="breadcrumb-item">
                    <i class="fas fa-home"></i> Raíz
                </li>
                <?php foreach ($breadcrumb as $item): ?>
                    <li class="breadcrumb-item">
                        <i class="<?= esc($item['icono']) ?> me-1"></i>
                        <?= esc($item['menu']) ?>
                    </li>
                <?php endforeach; ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="<?= esc($menu['icono']) ?> me-1"></i>
                    <?= esc($menu['menu']) ?>
                </li>
            </ol>
        </nav>
    <?php endif; ?>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Información del Menú
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-hashtag me-1"></i>
                                    ID del Menú
                                </label>
                                <div class="info-value">
                                    <span class="badge bg-primary fs-6">#<?= $menu['id'] ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Nivel en Jerarquía
                                </label>
                                <div class="info-value">
                                    <span class="badge bg-info fs-6">Nivel <?= $menu['nivel'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-tag me-1"></i>
                                    Nombre del Menú
                                </label>
                                <div class="info-value">
                                    <h5 class="mb-0"><?= esc($menu['menu']) ?></h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-icons me-1"></i>
                                    Icono Asignado
                                </label>
                                <div class="info-value">
                                    <div class="d-flex align-items-center">
                                        <i class="<?= esc($menu['icono']) ?> fa-2x me-3 text-primary"></i>
                                        <div>
                                            <strong><?= esc($nombre_icono ?? $icon_name ?? '') ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <code><?= esc($menu['icono']) ?></code>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-sitemap me-1"></i>
                                    Menú Superior
                                </label>
                                <div class="info-value">
                                    <?php if ($menu['id_superior']): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="<?= esc($menu_superior['icono']) ?> me-2"></i>
                                            <a href="<?= base_url('menu/show/' . $menu_superior['id']) ?>" 
                                               class="text-decoration-none">
                                                <?= esc($menu_superior['menu']) ?>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">
                                            <i class="fas fa-home me-1"></i>
                                            Menú Principal (Sin Superior)
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-code-branch me-1"></i>
                                    Submenús
                                </label>
                                <div class="info-value">
                                    <span class="badge bg-<?= empty($submenus) ? 'secondary' : 'success' ?> fs-6">
                                        <?= count($submenus) ?> submenú(s)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submenús -->
            <?php if (!empty($submenus)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-code-branch me-2"></i>
                            Submenús (<?= count($submenus) ?>)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($submenus as $submenu): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="submenu-item p-3 border rounded bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="<?= esc($submenu['icono']) ?> fa-lg me-3 text-primary"></i>
                                                <div>
                                                    <h6 class="mb-1"><?= esc($submenu['menu']) ?></h6>
                                                    <small class="text-muted">Nivel <?= $submenu['nivel'] ?></small>
                                                </div>
                                            </div>
                                            <a href="<?= base_url('menu/show/' . $submenu['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">
                                <i class="fas fa-layer-group me-1"></i>
                                Nivel Actual
                            </span>
                            <span class="badge bg-info"><?= $menu['nivel'] ?></span>
                        </div>
                    </div>
                    
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">
                                <i class="fas fa-code-branch me-1"></i>
                                Submenús Directos
                            </span>
                            <span class="badge bg-success"><?= count($submenus) ?></span>
                        </div>
                    </div>
                    
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">
                                <i class="fas fa-sitemap me-1"></i>
                                Tiene Superior
                            </span>
                            <span class="badge bg-<?= $menu['id_superior'] ? 'primary' : 'secondary' ?>">
                                <?= $menu['id_superior'] ? 'Sí' : 'No' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auditoría -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>
                        Información de Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="audit-item mb-3">
                        <label class="form-label text-muted small">
                            <i class="fas fa-user-plus me-1"></i>
                            Creado por
                        </label>
                        <div class="audit-value">
                            <strong>Usuario #<?= $menu['usuario_crea'] ?></strong>
                        </div>
                    </div>
                    
                    <div class="audit-item mb-3">
                        <label class="form-label text-muted small">
                            <i class="fas fa-calendar-plus me-1"></i>
                            Fecha de Creación
                        </label>
                        <div class="audit-value">
                            <?= date('d/m/Y H:i', strtotime($menu['fecha_registra'])) ?>
                        </div>
                    </div>
                    
                    <?php if ($menu['usuario_edita']): ?>
                        <div class="audit-item mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-user-edit me-1"></i>
                                Última edición por
                            </label>
                            <div class="audit-value">
                                <strong>Usuario #<?= $menu['usuario_edita'] ?></strong>
                            </div>
                        </div>
                        
                        <div class="audit-item mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-calendar-edit me-1"></i>
                                Fecha de Última Edición
                            </label>
                            <div class="audit-value">
                                <?= date('d/m/Y H:i', strtotime($menu['fecha_actualiza'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('menu/create?parent=' . $menu['id']) ?>" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Crear Submenú
                        </a>
                        
                        <a href="<?= base_url('menu/edit/' . $menu['id']) ?>" 
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar Menú
                        </a>
                        
                        <?php if (!empty($breadcrumb)): ?>
                            <a href="<?= base_url('menu/show/' . $menu_superior['id']) ?>" 
                               class="btn btn-info btn-sm">
                                <i class="fas fa-level-up-alt"></i> Ver Superior
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('menu') ?>" 
                           class="btn btn-secondary btn-sm">
                            <i class="fas fa-list"></i> Ver Todos los Menús
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<?php if (empty($submenus)): ?>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                        <h5>¿Estás seguro de eliminar este menú?</h5>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Menú a eliminar:</strong>
                        <br>
                        <i class="<?= esc($menu['icono']) ?> me-2"></i>
                        <?= esc($menu['menu']) ?>
                    </div>
                    
                    <p class="text-muted">
                        Esta acción no se puede deshacer. El menú será eliminado permanentemente del sistema.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash"></i> Eliminar Menú
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.info-item, .stat-item, .audit-item {
    border-bottom: 1px solid #e3e6f0;
    padding-bottom: 10px;
}

.info-item:last-child, .stat-item:last-child, .audit-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-value {
    margin-top: 5px;
}

.audit-value {
    font-size: 14px;
    color: #5a5c69;
}

.submenu-item {
    transition: all 0.3s ease;
}

.submenu-item:hover {
    background-color: #f8f9fc !important;
    border-color: #4e73df !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-weight: bold;
    color: #6c757d;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
</style>

<script>
$(document).ready(function() {
    // Manejar eliminación del menú
    $('#confirmDelete').on('click', function() {
        const menuId = <?= $menu['id'] ?>;
        
        $.ajax({
            url: '<?= base_url('menu/delete') ?>/' + menuId,
            type: 'DELETE',
            dataType: 'json',
            beforeSend: function() {
                $('#confirmDelete').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Eliminando...');
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    showAlert('success', response.message);
                    setTimeout(() => {
                        window.location.href = '<?= base_url('menu') ?>';
                    }, 2000);
                } else {
                    showAlert('error', response.message);
                    $('#confirmDelete').prop('disabled', false)
                        .html('<i class="fas fa-trash"></i> Eliminar Menú');
                }
            },
            error: function() {
                showAlert('error', 'Error al eliminar el menú');
                $('#confirmDelete').prop('disabled', false)
                    .html('<i class="fas fa-trash"></i> Eliminar Menú');
            }
        });
    });
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass} me-2"></i>${message}
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

<?= $this->endSection() ?>
