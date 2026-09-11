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
                <i class="fas fa-users-cog me-2"></i>
                <?= $title ?>
            </h1>
            <p class="text-muted mb-0">Gestiona los roles y permisos del sistema</p>
        </div>
        <a href="<?= base_url('rol/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Nuevo Rol
        </a>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Roles
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalRoles">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users-cog fa-2x text-gray-300"></i>
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
                                Con Usuarios
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="rolesConUsuarios">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Sin Usuarios
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="rolesSinUsuarios">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
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
                                Estado Sistema
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span class="badge bg-success">Activo</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de roles -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-1"></i>
                Lista de Roles
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Acciones:</div>
                    <a class="dropdown-item" href="#" onclick="recargarTabla()">
                        <i class="fas fa-sync-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Actualizar
                    </a>
                    <a class="dropdown-item" href="<?= base_url('rol/create') ?>">
                        <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                        Nuevo Rol
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="rolesTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="25%">Nombre</th>
                            <th width="35%">Descripción</th>
                            <th width="15%">Fecha Registro</th>
                            <th width="15%">Acciones</th>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    initializeDataTable();
    
    // Cargar estadísticas
    cargarEstadisticas();
});

let rolesTable;

async function initializeDataTable() {
    // Verificar si DataTables está disponible
    let attempts = 0;
    const maxAttempts = 50;
    
    while (typeof DataTable === 'undefined' && attempts < maxAttempts) {
        await new Promise(resolve => setTimeout(resolve, 100));
        attempts++;
    }
    
    if (typeof DataTable === 'undefined') {
        console.error('DataTables no está disponible después de 5 segundos');
        mostrarMensajeError('Error al cargar la tabla. Por favor, recarga la página.');
        return;
    }
    
    try {
        rolesTable = new DataTable('#rolesTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('rol/getData') ?>',
                type: 'POST',
                error: function(xhr, error, thrown) {
                    console.error('Error en AJAX:', error);
                    mostrarMensajeError('Error al cargar los datos de la tabla');
                }
            },
            columns: [
                { data: 'id', className: 'text-center' },
                { data: 'nombre' },
                { data: 'descripcion' },
                { data: 'fecha_registro', className: 'text-center' },
                { data: 'acciones', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[0, 'desc']],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            drawCallback: function() {
                // Reinicializar tooltips después de cada redibujado
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });
    } catch (error) {
        console.error('Error al inicializar DataTable:', error);
        mostrarMensajeError('Error al inicializar la tabla');
    }
}

function cargarEstadisticas() {
    fetch('<?= base_url('rol/getEstadisticas') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('totalRoles').textContent = data.data.total_roles;
            document.getElementById('rolesConUsuarios').textContent = data.data.roles_con_usuarios;
            document.getElementById('rolesSinUsuarios').textContent = data.data.roles_sin_usuarios;
        } else {
            console.error('Error al cargar estadísticas:', data.message);
            // Mostrar valores por defecto
            document.getElementById('totalRoles').textContent = '0';
            document.getElementById('rolesConUsuarios').textContent = '0';
            document.getElementById('rolesSinUsuarios').textContent = '0';
        }
    })
    .catch(error => {
        console.error('Error en petición de estadísticas:', error);
        // Mostrar valores por defecto
        document.getElementById('totalRoles').textContent = '0';
        document.getElementById('rolesConUsuarios').textContent = '0';
        document.getElementById('rolesSinUsuarios').textContent = '0';
    });
}

function recargarTabla() {
    if (rolesTable) {
        rolesTable.ajax.reload();
        cargarEstadisticas();
        mostrarMensaje('success', 'Tabla actualizada correctamente');
    }
}

function eliminarRol(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este rol?\n\nEsta acción no se puede deshacer.')) {
        return;
    }

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
            recargarTabla();
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

function mostrarMensajeError(mensaje) {
    mostrarMensaje('error', mensaje);
}
</script>
<?= $this->endSection() ?>
