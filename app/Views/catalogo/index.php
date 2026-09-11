<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Catálogo Principal</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header con estadísticas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-list-alt me-2"></i>Catálogo Principal
                </h1>
                <a href="<?= base_url('catalogo/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Catálogo
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['total'] ?></h4>
                            <p class="mb-0">Total Principales</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['activos'] ?></h4>
                            <p class="mb-0">Activos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['inactivos'] ?></h4>
                            <p class="mb-0">Inactivos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['subcatalogos'] ?></h4>
                            <p class="mb-0">Subcatálogos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-sitemap fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('catalogo') ?>">
                <div class="row">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Buscar por código, nombre, descripción o referencia..."
                                   value="<?= esc($search) ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <?php if (!empty($search)): ?>
                                <a href="<?= base_url('catalogo') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Limpiar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cards de catálogos -->
    <?php if (empty($catalogos)): ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No se encontraron catálogos principales</h5>
            <p class="text-muted">
                <?php if (!empty($search)): ?>
                    No hay resultados para "<?= esc($search) ?>"
                <?php else: ?>
                    Aún no has creado ningún catálogo principal
                <?php endif; ?>
            </p>
            <a href="<?= base_url('catalogo/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Crear Primer Catálogo
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($catalogos as $catalogo): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-folder-open text-primary me-2"></i>
                                <strong><?= esc($catalogo['codigo']) ?></strong>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input estado-switch" 
                                       type="checkbox" 
                                       data-id="<?= $catalogo['id'] ?>"
                                       <?= $catalogo['estado'] == '1' ? 'checked' : '' ?>
                                       title="Cambiar estado">
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-truncate" title="<?= esc($catalogo['nombre']) ?>">
                                <?= esc($catalogo['nombre']) ?>
                            </h5>
                            
                            <?php if ($catalogo['descripcion']): ?>
                                <p class="card-text text-muted small">
                                    <?= esc(substr($catalogo['descripcion'], 0, 100)) ?>
                                    <?= strlen($catalogo['descripcion']) > 100 ? '...' : '' ?>
                                </p>
                            <?php else: ?>
                                <p class="card-text text-muted small fst-italic">Sin descripción</p>
                            <?php endif; ?>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Estado:</small>
                                    <span class="badge bg-<?= $catalogo['estado'] == '1' ? 'success' : 'danger' ?>">
                                        <?= $catalogo['estado'] == '1' ? 'ACTIVO' : 'INACTIVO' ?>
                                    </span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Nivel:</small>
                                    <span class="badge bg-secondary">
                                        Nivel <?= $catalogo['nivel'] ?? 1 ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($catalogo['referencia'] || $catalogo['referencia2']): ?>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Referencias:</small>
                                    <?php if ($catalogo['referencia']): ?>
                                        <small class="d-block"><strong>Ref 1:</strong> <?= esc($catalogo['referencia']) ?></small>
                                    <?php endif; ?>
                                    <?php if ($catalogo['referencia2']): ?>
                                        <small class="d-block"><strong>Ref 2:</strong> <?= esc($catalogo['referencia2']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($catalogo['edicion']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Edición: <strong><?= $catalogo['edicion'] ?></strong></small>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <?php if ($catalogo['fecha_registro']): ?>
                                        Creado: <?= date('d/m/Y', strtotime($catalogo['fecha_registro'])) ?>
                                    <?php endif; ?>
                                </small>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('catalogo/show/' . $catalogo['id']) ?>" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('catalogo/edit/' . $catalogo['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger eliminar-catalogo" 
                                            data-id="<?= $catalogo['id'] ?>" 
                                            data-nombre="<?= esc($catalogo['nombre']) ?>"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Paginación -->
        <?php if ($pager): ?>
            <div class="d-flex justify-content-center mt-4">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Cambiar estado
    document.querySelectorAll('.estado-switch').forEach(function(switchElement) {
        switchElement.addEventListener('change', function() {
            const id = this.dataset.id;
            const estado = this.checked ? 'ACTIVO' : 'INACTIVO';
            
            fetch('<?= base_url('catalogo/cambiarEstado') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `id=${id}&estado=${estado}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#ef4444'
                    });
                    // Revertir el switch
                    this.checked = estado !== 'ACTIVO';
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor',
                    confirmButtonColor: '#ef4444'
                });
                // Revertir el switch
                this.checked = estado !== 'ACTIVO';
            });
        });
    });

    // Eliminar catálogo
    document.querySelectorAll('.eliminar-catalogo').forEach(function(button) {
        button.addEventListener('click', function() {
            const catalogoId = this.dataset.id;
            const nombreCatalogo = this.dataset.nombre;
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el catálogo "${nombreCatalogo}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarCatalogo(catalogoId);
                }
            });
        });
    });

    // Función para eliminar catálogo
    function eliminarCatalogo(catalogoId) {
        // Mostrar loading
        Swal.fire({
            title: 'Eliminando...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch(`<?= base_url('catalogo/delete/') ?>${catalogoId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Eliminado!',
                    text: data.message,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor',
                confirmButtonColor: '#ef4444'
            });
        });
    }

});
</script>
<?= $this->endSection() ?>
