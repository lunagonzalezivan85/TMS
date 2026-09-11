<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Detalle del Catálogo - <?= esc($catalogo['codigo']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('catalogo') ?>">Catálogo</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($catalogo['codigo']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-list-alt me-2"></i>
                    <?= esc($catalogo['codigo']) ?> - <?= esc($catalogo['nombre']) ?>
                </h1>
                <div class="btn-group">
                    <a href="<?= base_url('catalogo/edit/' . $catalogo['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="<?= base_url('catalogo') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado y badges -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-<?= $catalogo['estado'] == '1' ? 'success' : 'danger' ?> fs-6">
                    <i class="fas fa-<?= $catalogo['estado'] == '1' ? 'check-circle' : 'times-circle' ?> me-1"></i>
                    <?= $catalogo['estado'] == '1' ? 'ACTIVO' : 'INACTIVO' ?>
                </span>
                <span class="badge bg-secondary fs-6">
                    <i class="fas fa-layer-group me-1"></i>
                    Nivel <?= $catalogo['nivel'] ?>
                </span>
                <?php if ($catalogo['nombre_superior']): ?>
                    <span class="badge bg-info fs-6">
                        <i class="fas fa-sitemap me-1"></i>
                        Subcatálogo de: <?= esc($catalogo['nombre_superior']) ?>
                    </span>
                <?php else: ?>
                    <span class="badge bg-primary fs-6">
                        <i class="fas fa-home me-1"></i>
                        Catálogo Principal
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información principal -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Código:</td>
                                    <td><?= esc($catalogo['codigo']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nombre:</td>
                                    <td><?= esc($catalogo['nombre']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td>
                                        <span class="badge bg-<?= $catalogo['estado'] == 'ACTIVO' ? 'success' : 'danger' ?>">
                                            <?= $catalogo['estado'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nivel:</td>
                                    <td>
                                        <span class="badge bg-secondary">Nivel <?= $catalogo['nivel'] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Catálogo Superior:</td>
                                    <td>
                                        <?php if ($catalogo['nombre_superior'] && $catalogo['id_superior']): ?>
                                            <a href="<?= base_url('catalogo/show/' . $catalogo['id_superior']) ?>" 
                                               class="text-decoration-none">
                                                <i class="fas fa-link me-1"></i>
                                                <?= esc($catalogo['nombre_superior']) ?>
                                                <i class="fas fa-external-link-alt ms-1 small"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">
                                                <i class="fas fa-home me-1"></i>
                                                Catálogo raíz
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Referencia:</td>
                                    <td><?= esc($catalogo['referencia'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Referencia 2:</td>
                                    <td><?= esc($catalogo['referencia2'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Edición:</td>
                                    <td><?= $catalogo['edicion'] ? esc($catalogo['edicion']) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">ID:</td>
                                    <td><code><?= $catalogo['id'] ?></code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Empresa ID:</td>
                                    <td><code><?= $catalogo['idempresa'] ?></code></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if ($catalogo['descripcion']): ?>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold">
                                    <i class="fas fa-align-left me-1"></i>Descripción:
                                </h6>
                                <p class="text-muted mb-0"><?= nl2br(esc($catalogo['descripcion'])) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Subcatálogos -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>Subcatálogos 
                        <?php if (!empty($subcatalogos)): ?>
                            <span class="badge bg-primary ms-2"><?= count($subcatalogos) ?></span>
                        <?php endif; ?>
                    </h5>
                </div>
                <?php if (!empty($subcatalogos)): ?>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subcatalogos as $sub): ?>
                                        <tr class="subcatalogo-row" data-id="<?= $sub['id'] ?>" style="cursor: pointer;" title="Clic para ver detalle">
                                            <td>
                                                <strong class="text-primary"><?= esc($sub['codigo']) ?></strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-folder me-2 text-muted"></i>
                                                    <?= esc($sub['nombre']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= esc(substr($sub['descripcion'] ?? '', 0, 50)) ?>
                                                    <?= strlen($sub['descripcion'] ?? '') > 50 ? '...' : '' ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $sub['estado'] == 'ACTIVO' ? 'success' : 'danger' ?>">
                                                    <i class="fas fa-<?= $sub['estado'] == 'ACTIVO' ? 'check' : 'times' ?> me-1"></i>
                                                    <?= $sub['estado'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('catalogo/show/' . $sub['id']) ?>" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Ver detalle"
                                                       onclick="event.stopPropagation();">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('catalogo/edit/' . $sub['id']) ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Editar"
                                                       onclick="event.stopPropagation();">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No hay subcatálogos</h6>
                        <p class="text-muted small mb-3">Este catálogo no tiene subcatálogos asociados.</p>
                        <a href="<?= base_url('catalogo/create') ?>?id_superior=<?= $catalogo['id'] ?>" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Crear Primer Subcatálogo
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="col-lg-4">
            <!-- Información de auditoría -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Información de Auditoría
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="fw-bold">Creado por:</td>
                            <td><?= esc($catalogo['usuario_creador'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Fecha creación:</td>
                            <td>
                                <?php if ($catalogo['fecha_registro']): ?>
                                    <small>
                                        <?= date('d/m/Y H:i', strtotime($catalogo['fecha_registro'])) ?>
                                    </small>
                                <?php else: ?>
                                    <small class="text-muted">N/A</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($catalogo['usuario_actualizador']): ?>
                            <tr>
                                <td class="fw-bold">Actualizado por:</td>
                                <td><?= esc($catalogo['usuario_actualizador']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Última actualización:</td>
                                <td>
                                    <?php if ($catalogo['fecha_actualiza']): ?>
                                        <small>
                                            <?= date('d/m/Y H:i', strtotime($catalogo['fecha_actualiza'])) ?>
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">N/A</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('catalogo/edit/' . $catalogo['id']) ?>" 
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar Catálogo
                        </a>
                        
                        <a href="<?= base_url('catalogo/create') ?>?id_superior=<?= $catalogo['id'] ?>" 
                           class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Crear Subcatálogo
                        </a>
                        
                        <button class="btn btn-<?= $catalogo['estado'] == 'ACTIVO' ? 'warning' : 'success' ?> cambiar-estado"
                                data-id="<?= $catalogo['id'] ?>"
                                data-estado="<?= $catalogo['estado'] == 'ACTIVO' ? 'INACTIVO' : 'ACTIVO' ?>">
                            <i class="fas fa-<?= $catalogo['estado'] == 'ACTIVO' ? 'pause' : 'play' ?> me-2"></i>
                            <?= $catalogo['estado'] == 'ACTIVO' ? 'Desactivar' : 'Activar' ?>
                        </button>
                        
                        <hr>
                        
                        <button class="btn btn-outline-danger eliminar-catalogo"
                                data-id="<?= $catalogo['id'] ?>"
                                data-nombre="<?= esc($catalogo['nombre']) ?>">
                            <i class="fas fa-trash me-2"></i>Eliminar Catálogo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    .subcatalogo-row:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .subcatalogo-row {
        transition: all 0.2s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Navegación por clic en filas de subcatálogos
    document.querySelectorAll('.subcatalogo-row').forEach(function(row) {
        row.addEventListener('click', function() {
            const catalogoId = this.dataset.id;
            window.location.href = `<?= base_url('catalogo/show/') ?>${catalogoId}`;
        });
    });

    // Cambiar estado
    document.querySelectorAll('.cambiar-estado').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nuevoEstado = this.dataset.estado;
            const accion = nuevoEstado === 'ACTIVO' ? 'activar' : 'desactivar';
            
            Swal.fire({
                title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} catálogo?`,
                text: `¿Estás seguro que deseas ${accion} este catálogo?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: nuevoEstado === 'ACTIVO' ? '#10b981' : '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: `Sí, ${accion}`,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    cambiarEstado(id, nuevoEstado);
                }
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

    // Función para cambiar estado
    function cambiarEstado(id, nuevoEstado) {
        Swal.fire({
            title: 'Procesando...',
            text: 'Cambiando estado del catálogo',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('<?= base_url('catalogo/cambiarEstado') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `id=${id}&estado=${nuevoEstado}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
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

    // Función para eliminar catálogo
    function eliminarCatalogo(catalogoId) {
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
                    window.location.href = '<?= base_url('catalogo') ?>';
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
