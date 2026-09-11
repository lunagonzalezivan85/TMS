<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-shield me-2"></i>Gestión de Roles
            </h1>
            <p class="text-muted mb-0">Administra los roles y permisos del sistema</p>
        </div>
        <a href="<?= base_url('admin/roles/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Rol
        </a>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla de roles -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Lista de Roles
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="rolesTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Permisos</th>
                            <th>Estado</th>
                            <th>Usuarios</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargan via AJAX -->
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
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este rol?</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                <div id="roleInfo" class="alert alert-info" style="display: none;">
                    <!-- Información del rol a eliminar -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-2"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver permisos -->
<div class="modal fade" id="permisosModal" tabindex="-1" aria-labelledby="permisosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permisosModalLabel">
                    <i class="fas fa-key me-2"></i>Permisos del Rol
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="permisosContent">
                    <!-- Contenido de permisos cargado via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    const table = $('#rolesTable').DataTable({
        ajax: {
            url: '<?= base_url('admin/roles/ajax') ?>',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'descripcion' },
            { data: 'permisos', orderable: false, searchable: false },
            { data: 'estado' },
            { data: 'usuarios_count' },
            { data: 'acciones', orderable: false, searchable: false }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        responsive: true,
        order: [[0, 'desc']],
        pageLength: 25
    });

    // Variable para almacenar el ID del rol a eliminar
    let roleIdToDelete = null;

    // Función para eliminar rol
    window.eliminarRol = function(id, nombre) {
        roleIdToDelete = id;
        $('#roleInfo').html(`<strong>Rol:</strong> ${nombre}`).show();
        $('#deleteModal').modal('show');
    };

    // Confirmar eliminación
    $('#confirmDelete').click(function() {
        if (roleIdToDelete) {
            $.ajax({
                url: '<?= base_url('admin/roles') ?>/' + roleIdToDelete,
                type: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        table.ajax.reload();
                        showAlert('success', response.success);
                    } else {
                        showAlert('error', response.error);
                    }
                },
                error: function(xhr) {
                    $('#deleteModal').modal('hide');
                    const response = xhr.responseJSON;
                    showAlert('error', response ? response.error : 'Error al eliminar el rol');
                }
            });
        }
    });

    // Función para ver permisos
    window.verPermisos = function(id, nombre) {
        $('#permisosModalLabel').html(`<i class="fas fa-key me-2"></i>Permisos del Rol: ${nombre}`);
        $('#permisosContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
        $('#permisosModal').modal('show');
        
        $.ajax({
            url: '<?= base_url('admin/roles') ?>/' + id + '/permisos',
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    let content = '<div class="row">';
                    
                    if (response.permisos && response.permisos.length > 0) {
                        response.permisos.forEach(function(permiso) {
                            content += `
                                <div class="col-md-6 mb-2">
                                    <div class="card bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title mb-1">
                                                <i class="fas fa-shield-alt text-primary me-1"></i>
                                                ${permiso.modulo}
                                            </h6>
                                            <small class="text-muted">${permiso.accion}</small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        content += '<div class="col-12"><p class="text-muted text-center">No hay permisos asignados</p></div>';
                    }
                    
                    content += '</div>';
                    $('#permisosContent').html(content);
                } else {
                    $('#permisosContent').html('<div class="alert alert-danger">Error al cargar los permisos</div>');
                }
            },
            error: function() {
                $('#permisosContent').html('<div class="alert alert-danger">Error al cargar los permisos</div>');
            }
        });
    };

    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
        
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.container-fluid').prepend(alert);
        
        // Auto-hide después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
