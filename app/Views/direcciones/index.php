<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $page_title ?></h1>
            <p class="mb-0 text-muted">Gestiona las direcciones del sistema</p>
        </div>
        <div>
            <a href="<?= base_url('direcciones/mapa') ?>" class="btn btn-info me-2">
                <i class="fas fa-map me-2"></i>Ver Mapa
            </a>
            <a href="<?= base_url('direcciones/sincronizar') ?>" class="btn btn-warning me-2">
                <i class="fas fa-sync-alt me-2"></i>Sincronizar
            </a>
            <a href="<?= base_url('direcciones/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva Dirección
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Direcciones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['total'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Con Coordenadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['con_coordenadas'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sin Coordenadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['sin_coordenadas'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros de Búsqueda</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('direcciones') ?>">
                <div class="row">
                    <div class="col-md-6">
                        <label for="buscar" class="form-label">Buscar</label>
                        <input type="text" name="buscar" id="buscar" class="form-control" 
                               placeholder="Buscar por dirección, ciudad o código..." 
                               value="<?= $filtros['buscar'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" name="ciudad" id="ciudad" class="form-control" 
                               placeholder="Filtrar por ciudad..." 
                               value="<?= $filtros['ciudad'] ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="<?= base_url('direcciones') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de direcciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Direcciones</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Código Integración</th>
                            <th>Coordenadas</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($direcciones)): ?>
                            <?php foreach ($direcciones as $direccion): ?>
                                <tr>
                                    <td><?= $direccion['id'] ?></td>
                                    <td>
                                        <strong class="text-primary"><?= esc($direccion['nombre'] ?? 'Sin nombre') ?></strong>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;" title="<?= esc($direccion['direccion']) ?>">
                                            <?= esc($direccion['direccion']) ?>
                                        </div>
                                    </td>
                                    <td><?= esc($direccion['ciudad'] ?? '-') ?></td>
                                    <td><?= esc($direccion['codigo_integracion'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($direccion['latitud'] && $direccion['longitud']): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <?= number_format($direccion['latitud'], 6) ?>, <?= number_format($direccion['longitud'], 6) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Sin coordenadas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($direccion['fecha_registro'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('direcciones/show/' . $direccion['id']) ?>" 
                                               class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('direcciones/edit/' . $direccion['id']) ?>" 
                                               class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmarEliminacion(<?= $direccion['id'] ?>)" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No se encontraron direcciones</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar esta dirección? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(id) {
    const form = document.getElementById('deleteForm');
    form.action = '<?= base_url('direcciones/delete/') ?>' + id;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// DataTable initialization
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[ 0, "desc" ]],
        "pageLength": 25
    });
});
</script>

<?= $this->endSection() ?>
