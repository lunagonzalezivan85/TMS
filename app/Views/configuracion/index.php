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
                <i class="fas fa-cogs me-2"></i>
                <?= $title ?>
            </h1>
            <p class="text-muted mb-0">Administra la configuración del sistema y usuarios</p>
        </div>
    </div>

    <!-- Opciones de Configuración -->
    <div class="row">
        <!-- Gestión de Usuarios -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Gestión de Usuarios
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-users me-2"></i>
                                Usuarios
                            </div>
                            <div class="text-muted small mt-2">
                                Crear y administrar usuarios del sistema
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?= base_url('configuracion/registro-usuario') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i>
                            Registrar Usuario
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm ms-2" onclick="mostrarUsuarios()">
                            <i class="fas fa-list me-1"></i>
                            Ver Usuarios
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Roles -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Gestión de Roles
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-user-tag me-2"></i>
                                Roles
                            </div>
                            <div class="text-muted small mt-2">
                                Administrar roles y permisos
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?= base_url('rol/create') ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Crear Rol
                        </a>
                        <a href="<?= base_url('rol') ?>" class="btn btn-outline-success btn-sm ms-2">
                            <i class="fas fa-list me-1"></i>
                            Ver Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Accesos -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Gestión de Accesos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-key me-2"></i>
                                Accesos
                            </div>
                            <div class="text-muted small mt-2">
                                Configurar permisos de acceso
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?= base_url('acceso/create') ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Crear Acceso
                        </a>
                        <a href="<?= base_url('acceso') ?>" class="btn btn-outline-info btn-sm ms-2">
                            <i class="fas fa-list me-1"></i>
                            Ver Accesos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas del Sistema -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area me-1"></i>
                        Estadísticas de Usuarios
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row" id="estadisticasUsuarios">
                        <div class="col-12 text-center">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Cargando estadísticas...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-1"></i>
                        Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Versión del Sistema
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                GMV v1.0.0
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Framework
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                CodeIgniter 4
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Base de Datos
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                MySQL
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Servidor Web
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Apache/XAMPP
                            </div>
                        </div>
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
    cargarEstadisticasUsuarios();
});

function cargarEstadisticasUsuarios() {
    fetch('<?= base_url('configuracion/get-estadisticas-usuarios') ?>', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarEstadisticas(data.data);
        } else {
            mostrarErrorEstadisticas();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarErrorEstadisticas();
    });
}

function mostrarEstadisticas(stats) {
    const container = document.getElementById('estadisticasUsuarios');
    
    let rolesHtml = '';
    if (stats.usuarios_por_rol && stats.usuarios_por_rol.length > 0) {
        rolesHtml = stats.usuarios_por_rol.map(rol => 
            `<div class="col-12 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">${rol.rol}</span>
                    <span class="badge bg-primary">${rol.total}</span>
                </div>
            </div>`
        ).join('');
    }
    
    container.innerHTML = `
        <div class="col-sm-4 mb-3">
            <div class="text-center">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Usuarios Activos
                </div>
                <div class="h4 mb-0 font-weight-bold text-gray-800">
                    ${stats.usuarios_activos || 0}
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-3">
            <div class="text-center">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Nuevos (30 días)
                </div>
                <div class="h4 mb-0 font-weight-bold text-gray-800">
                    ${stats.usuarios_recientes || 0}
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-3">
            <div class="text-center">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    Roles Diferentes
                </div>
                <div class="h4 mb-0 font-weight-bold text-gray-800">
                    ${stats.usuarios_por_rol ? stats.usuarios_por_rol.length : 0}
                </div>
            </div>
        </div>
        ${rolesHtml ? `<div class="col-12"><hr><h6 class="text-muted mb-3">Distribución por Roles:</h6>${rolesHtml}</div>` : ''}
    `;
}

function mostrarErrorEstadisticas() {
    const container = document.getElementById('estadisticasUsuarios');
    container.innerHTML = `
        <div class="col-12 text-center">
            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
            <p class="text-muted mt-2">Error al cargar estadísticas</p>
        </div>
    `;
}

function mostrarUsuarios() {
    // Crear modal para mostrar usuarios
    const modalHtml = `
        <div class="modal fade" id="usuariosModal" tabindex="-1" aria-labelledby="usuariosModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="usuariosModalLabel">
                            <i class="fas fa-users me-2"></i>Usuarios de la Empresa
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="usuariosContent">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2">Cargando usuarios...</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Agregar modal al DOM si no existe
    if (!document.getElementById('usuariosModal')) {
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('usuariosModal'));
    modal.show();
    
    // Cargar usuarios via AJAX
    fetch('<?= base_url('configuracion/get-usuarios-empresa') ?>', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const content = document.getElementById('usuariosContent');
        if (data.success && data.data.length > 0) {
            let usuariosHtml = `
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            data.data.forEach(usuario => {
                const estado = usuario.estado == 1 ? 
                    '<span class="badge bg-success">Activo</span>' : 
                    '<span class="badge bg-danger">Inactivo</span>';
                    
                const fecha = new Date(usuario.fecha_registro).toLocaleDateString('es-ES');
                
                usuariosHtml += `
                    <tr>
                        <td>${usuario.nombre}</td>
                        <td>${usuario.email}</td>
                        <td><span class="badge bg-primary">${usuario.rol_nombre}</span></td>
                        <td>${estado}</td>
                        <td>${fecha}</td>
                    </tr>
                `;
            });
            
            usuariosHtml += `
                        </tbody>
                    </table>
                </div>
            `;
            
            content.innerHTML = usuariosHtml;
        } else {
            content.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No se encontraron usuarios</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('usuariosContent').innerHTML = `
            <div class="text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p class="text-muted">Error al cargar usuarios</p>
            </div>
        `;
    });
}
</script>
<?= $this->endSection() ?>
