<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $page_title ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tipos de Motivo de Combustible</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('tipo-motivo-combustible/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Tipo
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
                                Total de Tipos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['total'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
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
                                Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['activos'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Inactivos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['inactivos'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de tipos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Lista de Tipos de Motivo de Combustible
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Creado por</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tipos as $tipo): ?>
                        <tr>
                            <td><?= $tipo['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-gas-pump text-primary me-2"></i>
                                    <?= esc($tipo['descripcion']) ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                $estadoClass = $tipo['estado'] == 'ACTIVO' ? 'success' : 'secondary';
                                $estadoIcon = $tipo['estado'] == 'ACTIVO' ? 'check-circle' : 'times-circle';
                                ?>
                                <span class="badge bg-<?= $estadoClass ?>">
                                    <i class="fas fa-<?= $estadoIcon ?> me-1"></i><?= $tipo['estado'] ?>
                                </span>
                            </td>
                            <td><?= esc($tipo['usuario_crea_nombre'] ?? 'N/A') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($tipo['fecha_registro'])) ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('tipo-motivo-combustible/show/' . $tipo['id']) ?>" 
                                       class="btn btn-info btn-sm" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('tipo-motivo-combustible/edit/' . $tipo['id']) ?>" 
                                       class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($tipo['estado'] == 'ACTIVO'): ?>
                                    <button class="btn btn-secondary btn-sm cambiar-estado" 
                                            data-id="<?= $tipo['id'] ?>" 
                                            data-estado="INACTIVO"
                                            title="Inactivar">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                    <?php else: ?>
                                    <button class="btn btn-success btn-sm cambiar-estado" 
                                            data-id="<?= $tipo['id'] ?>" 
                                            data-estado="ACTIVO"
                                            title="Activar">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm eliminar-tipo" 
                                            data-id="<?= $tipo['id'] ?>" 
                                            data-descripcion="<?= esc($tipo['descripcion']) ?>"
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
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body">
                    <input type="hidden" id="tipo_id" name="id">
                    <input type="hidden" id="nuevo_estado" name="estado">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="mensaje_cambio_estado"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Tipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEliminar">
                <div class="modal-body">
                    <input type="hidden" id="eliminar_id" name="id">
                    
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ¿Está seguro que desea eliminar el tipo "<span id="eliminar_descripcion"></span>"?
                        <br><br>
                        <strong>Esta acción no se puede deshacer.</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#dataTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });

    // Cambiar estado
    $('.cambiar-estado').on('click', function() {
        const id = $(this).data('id');
        const estado = $(this).data('estado');
        
        $('#tipo_id').val(id);
        $('#nuevo_estado').val(estado);
        
        const mensaje = estado === 'ACTIVO' 
            ? 'El tipo será marcado como ACTIVO y estará disponible para su uso.'
            : 'El tipo será marcado como INACTIVO y no estará disponible para su uso.';
        
        $('#mensaje_cambio_estado').text(mensaje);
        new bootstrap.Modal(document.getElementById('modalCambiarEstado')).show();
    });

    // Procesar cambio de estado
    $('#formCambiarEstado').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('tipo-motivo-combustible/cambiarEstado') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
                    mostrarAlerta('success', response.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta('error', response.message);
                }
            },
            error: function() {
                mostrarAlerta('error', 'Error de conexión');
            }
        });
    });

    // Eliminar tipo
    $('.eliminar-tipo').on('click', function() {
        const id = $(this).data('id');
        const descripcion = $(this).data('descripcion');
        
        $('#eliminar_id').val(id);
        $('#eliminar_descripcion').text(descripcion);
        
        new bootstrap.Modal(document.getElementById('modalEliminar')).show();
    });

    // Procesar eliminación
    $('#formEliminar').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#eliminar_id').val();
        
        $.ajax({
            url: '<?= base_url('tipo-motivo-combustible/delete/') ?>' + id,
            type: 'DELETE',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
                    mostrarAlerta('success', response.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta('error', response.message);
                }
            },
            error: function() {
                mostrarAlerta('error', 'Error de conexión');
            }
        });
    });

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const icon = tipo === 'success' ? 'check-circle' : 'exclamation-triangle';
        
        const alerta = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${icon} me-2"></i>${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.container-fluid').prepend(alerta);
        $('html, body').animate({ scrollTop: 0 }, 300);
        
        setTimeout(() => $('.alert').fadeOut(), 5000);
    }
});
</script>
<?= $this->endSection() ?>
