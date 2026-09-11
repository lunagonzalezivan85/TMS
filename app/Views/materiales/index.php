<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box"></i> <?= $title ?>
        </h1>
        <div>
            <a href="<?= base_url('materiales/sincronizacion') ?>" class="btn btn-info btn-sm">
                <i class="fas fa-sync-alt"></i> Sincronización
            </a>
            <a href="<?= base_url('materiales/exportar') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> Exportar CSV
            </a>
            <a href="<?= base_url('materiales/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Material
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Materiales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($estadisticas['total_materiales']) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Costo Promedio</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($estadisticas['costo_promedio'], 2) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Unidades Diferentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['unidades_diferentes'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ruler fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Costo Máximo</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($estadisticas['costo_maximo'], 2) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros de Búsqueda</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('materiales') ?>">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" name="busqueda" 
                                   placeholder="Buscar por nombre, código o unidad de medida..." 
                                   value="<?= esc($busqueda) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <?php if ($busqueda): ?>
                            <a href="<?= base_url('materiales') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de materiales -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Lista de Materiales
                <?php if ($busqueda): ?>
                    <small class="text-muted">(Filtrado por: "<?= esc($busqueda) ?>")</small>
                <?php endif; ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($materiales)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-box fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">
                        <?= $busqueda ? 'No se encontraron materiales con los criterios de búsqueda.' : 'No hay materiales registrados.' ?>
                    </p>
                    <a href="<?= base_url('materiales/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer Material
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Unidad</th>
                                <th>Costo Unitario</th>
                                <th>Fecha Registro</th>
                                <th>Creado Por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materiales as $material): ?>
                                <tr>
                                    <td>
                                        <code><?= esc($material['codigo_consecutivo']) ?></code>
                                    </td>
                                    <td>
                                        <strong><?= esc($material['nombre']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary"><?= esc($material['unidad_medida']) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-success font-weight-bold">$<?= number_format($material['costo_unitario'], 2) ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($material['fechaRegistro'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small><?= esc($material['nombre_creador'] ?? 'N/A') ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('materiales/show/' . $material['id']) ?>" 
                                               class="btn btn-info btn-sm" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('materiales/edit/' . $material['id']) ?>" 
                                               class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="confirmarEliminacion(<?= $material['id'] ?>, '<?= esc($material['nombre']) ?>')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <?php if ($totalPaginas > 1): ?>
                    <nav aria-label="Paginación de materiales">
                        <ul class="pagination justify-content-center">
                            <?php if ($paginaActual > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('materiales?page=' . ($paginaActual - 1) . ($busqueda ? '&busqueda=' . urlencode($busqueda) : '')) ?>">
                                        Anterior
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $paginaActual - 2); $i <= min($totalPaginas, $paginaActual + 2); $i++): ?>
                                <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= base_url('materiales?page=' . $i . ($busqueda ? '&busqueda=' . urlencode($busqueda) : '')) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($paginaActual < $totalPaginas): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('materiales?page=' . ($paginaActual + 1) . ($busqueda ? '&busqueda=' . urlencode($busqueda) : '')) ?>">
                                        Siguiente
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el material <strong id="nombreMaterial"></strong>?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="formEliminar" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmarEliminacion(id, nombre) {
    Swal.fire({
        title: 'Confirmar Eliminación',
        html: `¿Estás seguro de que deseas eliminar el material <strong>${nombre}</strong>?<br><small class="text-danger">Esta acción no se puede deshacer.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash"></i> Eliminar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch('<?= base_url('materiales/delete/') ?>' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    '_method': 'DELETE'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`Error: ${error.message}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: '¡Material Eliminado!',
                text: `El material "${nombre}" ha sido eliminado correctamente.`,
                confirmButtonColor: '#10b981',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                window.location.reload();
            });
        }
    });
}

$(document).ready(function() {
    // Auto-submit del formulario de búsqueda con delay
    let timeoutId;
    $('input[name="busqueda"]').on('input', function() {
        clearTimeout(timeoutId);
        const form = $(this).closest('form');
        timeoutId = setTimeout(function() {
            form.submit();
        }, 500);
    });
});
</script>
<?= $this->endSection() ?>
