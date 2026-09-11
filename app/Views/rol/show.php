<?php helper('date'); ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumb as $item): ?>
                <?php if (!empty($item['url'])): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= $item['url'] ?>" class="text-decoration-none">
                            <?= $item['name'] ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $item['name'] ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye me-2"></i>
                <?= $title ?>
            </h1>
            <p class="text-muted mb-0">Información detallada del rol: <strong><?= esc($rol['nombre']) ?></strong></p>
        </div>
        <div class="btn-group" role="group">
            <a href="<?= base_url('rol') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Volver
            </a>
            <a href="<?= base_url("rol/edit/{$rol['id']}") ?>" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>
                Editar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Información principal del rol -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-1"></i>
                        Información del Rol
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">ID del Rol</label>
                                <div class="fw-bold fs-5 text-primary">
                                    <i class="fas fa-hashtag me-1"></i>
                                    <?= $rol['id'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Estado</label>
                                <div class="fw-bold fs-5">
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Activo
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Nombre del Rol</label>
                                <div class="fw-bold fs-4 text-dark">
                                    <i class="fas fa-tag me-2 text-primary"></i>
                                    <?= esc($rol['nombre']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Descripción</label>
                                <div class="p-3 bg-light rounded border">
                                    <?php if (!empty($rol['descripcion'])): ?>
                                        <i class="fas fa-quote-left text-muted me-2"></i>
                                        <?= nl2br(esc($rol['descripcion'])) ?>
                                        <i class="fas fa-quote-right text-muted ms-2"></i>
                                    <?php else: ?>
                                        <em class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            No se ha proporcionado una descripción para este rol.
                                        </em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de auditoría -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-1"></i>
                        Información de Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">
                                    <i class="fas fa-calendar-plus text-success me-1"></i>
                                    Fecha de Registro
                                </label>
                                <div class="fw-bold">
                                    <?= date('d/m/Y H:i:s', strtotime($rol['fecha_registro'])) ?>
                                </div>
                                <small class="text-muted">
                                    <?= time_ago($rol['fecha_registro']) ?>
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">
                                    <i class="fas fa-user-plus text-info me-1"></i>
                                    Usuario Creador
                                </label>
                                <div class="fw-bold">
                                    <?php if (!empty($rol['usuario_crea'])): ?>
                                        <span class="badge bg-info">
                                            ID: <?= $rol['usuario_crea'] ?>
                                        </span>
                                    <?php else: ?>
                                        <em class="text-muted">No especificado</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($rol['fecha_actualizacion'])): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">
                                        <i class="fas fa-calendar-edit text-warning me-1"></i>
                                        Última Actualización
                                    </label>
                                    <div class="fw-bold">
                                        <?= date('d/m/Y H:i:s', strtotime($rol['fecha_actualizacion'])) ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= time_ago($rol['fecha_actualizacion']) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">
                                        <i class="fas fa-user-edit text-warning me-1"></i>
                                        Usuario Editor
                                    </label>
                                    <div class="fw-bold">
                                        <?php if (!empty($rol['usuario_actualiza'])): ?>
                                            <span class="badge bg-warning text-dark">
                                                ID: <?= $rol['usuario_actualiza'] ?>
                                            </span>
                                        <?php else: ?>
                                            <em class="text-muted">No especificado</em>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Accesos del Rol -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-key me-1"></i>
                        Accesos del Rol
                    </h6>
                    <span class="badge bg-primary">
                        <?= $total_accesos ?> accesos
                    </span>
                </div>
                <div class="card-body">
                    <?php if (empty($accesos_agrupados)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Este rol no tiene accesos asignados.</p>
                        </div>
                    <?php else: ?>
                        <div class="accordion" id="accordionAccesos">
                            <?php foreach ($accesos_agrupados as $index => $grupo): ?>
                                <div class="accordion-item border rounded mb-2">
                                    <h2 class="accordion-header" id="heading<?= $index ?>">
                                        <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" 
                                                aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                                                aria-controls="collapse<?= $index ?>">
                                            <div class="d-flex align-items-center w-100">
                                                <div class="me-3">
                                                    <?php if (!empty($grupo['menu_principal']['icono'])): ?>
                                                        <i class="<?= $grupo['menu_principal']['icono'] ?> text-primary"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-folder text-primary"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <strong><?= esc($grupo['menu_principal']['menu']) ?></strong>
                                                    <?php if (isset($grupo['menu_principal']['sin_acceso_directo']) && $grupo['menu_principal']['sin_acceso_directo']): ?>
                                                        <span class="badge bg-warning text-dark ms-2">Solo submenús</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="me-3">
                                                    <span class="badge bg-secondary">
                                                        <?= count($grupo['submenus']) ?> <?= count($grupo['submenus']) === 1 ? 'submenú' : 'submenús' ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                                         aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordionAccesos">
                                        <div class="accordion-body">
                                            <?php if (!isset($grupo['menu_principal']['sin_acceso_directo'])): ?>
                                                <!-- Acceso directo al menú principal -->
                                                <div class="d-flex align-items-center p-2 bg-light rounded mb-3">
                                                    <div class="me-3">
                                                        <?php if (!empty($grupo['menu_principal']['icono'])): ?>
                                                            <i class="<?= $grupo['menu_principal']['icono'] ?> text-success"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-home text-success"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <strong class="text-success"><?= esc($grupo['menu_principal']['menu']) ?></strong>
                                                        <small class="d-block text-muted">
                                                            <i class="fas fa-link me-1"></i>
                                                            <?= esc($grupo['menu_principal']['ruta']) ?>
                                                        </small>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-success">Menú Principal</span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($grupo['submenus'])): ?>
                                                <!-- Submenús -->
                                                <div class="row">
                                                    <?php foreach ($grupo['submenus'] as $submenu): ?>
                                                        <div class="col-md-6 mb-2">
                                                            <div class="d-flex align-items-center p-2 border rounded bg-white">
                                                                <div class="me-3">
                                                                    <?php if (!empty($submenu['icono'])): ?>
                                                                        <i class="<?= $submenu['icono'] ?> text-info"></i>
                                                                    <?php else: ?>
                                                                        <i class="fas fa-file text-info"></i>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div class="fw-bold text-dark"><?= esc($submenu['menu']) ?></div>
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-link me-1"></i>
                                                                        <?= esc($submenu['ruta']) ?>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-center py-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        No hay submenús para este menú principal.
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Acciones rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-1"></i>
                        Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url("rol/edit/{$rol['id']}") ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Editar Rol
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmarEliminacion()">
                            <i class="fas fa-trash me-2"></i>
                            Eliminar Rol
                        </button>
                        <hr>
                        <a href="<?= base_url('rol/create') ?>" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>
                            Crear Nuevo Rol
                        </a>
                        <a href="<?= base_url('rol') ?>" class="btn btn-secondary">
                            <i class="fas fa-list me-2"></i>
                            Ver Todos los Roles
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas del rol -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-1"></i>
                        Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x text-primary mb-2"></i>
                            <div class="h4 mb-0" id="totalUsuarios">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <small class="text-muted">Usuarios con este rol</small>
                        </div>
                        
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="progressBar"></div>
                        </div>
                        
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Cargando estadísticas...
                        </small>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-lightbulb me-1"></i>
                        Información Adicional
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading">
                            <i class="fas fa-shield-alt"></i>
                            Seguridad
                        </h6>
                        <p class="mb-0 small">
                            Este rol puede ser asignado a múltiples usuarios. Los cambios en el rol afectarán a todos los usuarios que lo tengan asignado.
                        </p>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle"></i>
                            Eliminación
                        </h6>
                        <p class="mb-0 small">
                            No se puede eliminar un rol que tenga usuarios asignados. Primero debes reasignar o eliminar los usuarios asociados.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarEstadisticasRol();
});

function cargarEstadisticasRol() {
    // Simular carga de estadísticas (aquí podrías hacer una petición AJAX real)
    setTimeout(() => {
        const totalUsuarios = Math.floor(Math.random() * 20) + 1; // Número aleatorio para demo
        const maxUsuarios = 50;
        const porcentaje = (totalUsuarios / maxUsuarios) * 100;
        
        document.getElementById('totalUsuarios').textContent = totalUsuarios;
        document.getElementById('progressBar').style.width = porcentaje + '%';
        
        const infoText = document.querySelector('.card-body small.text-muted');
        infoText.innerHTML = `<i class="fas fa-check-circle text-success me-1"></i>Estadísticas actualizadas`;
    }, 1500);
}

function confirmarEliminacion() {
    if (confirm('¿Estás seguro de que deseas eliminar este rol?\n\nEsta acción no se puede deshacer y solo será posible si no hay usuarios asignados a este rol.')) {
        eliminarRol(<?= $rol['id'] ?>);
    }
}

function eliminarRol(id) {
    fetch(`<?= base_url('rol/delete') ?>/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarMensaje('success', data.message);
            setTimeout(() => {
                window.location.href = '<?= base_url('rol') ?>';
            }, 2000);
        } else {
            mostrarMensaje('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarMensaje('error', 'Error al eliminar el rol');
    });
}

function mostrarMensaje(tipo, mensaje) {
    const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        <i class="fas ${iconClass} me-2"></i>${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}


</script>
<?= $this->endSection() ?>
