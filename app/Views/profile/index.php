<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-circle me-2"></i><?= $title ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <?php if (!$isOwnProfile): ?>
                        <li class="breadcrumb-item"><a href="<?= base_url('usuarios') ?>">Usuarios</a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active"><?= $isOwnProfile ? 'Mi Perfil' : 'Perfil' ?></li>
                </ol>
            </nav>
        </div>
        <?php if ($canEdit): ?>
            <a href="<?= base_url('profile/edit' . ($isOwnProfile ? '' : '/' . $user['id'])) ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Editar Perfil
            </a>
        <?php endif; ?>
    </div>

    <!-- Mensajes de alerta -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Información Personal -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h5>
                </div>
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="mb-4">
                        <div class="avatar-circle mx-auto mb-3" style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user text-white" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-1"><?= esc($user['nombre']) ?></h4>
                        <p class="text-muted mb-0">@<?= esc($user['usuario']) ?></p>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <span class="badge <?= $user['estado'] === 'ACTIVO' ? 'bg-success' : 'bg-danger' ?> fs-6">
                            <i class="fas <?= $user['estado'] === 'ACTIVO' ? 'fa-check-circle' : 'fa-times-circle' ?> me-1"></i>
                            <?= $user['estado'] ?>
                        </span>
                    </div>

                    <!-- Información básica -->
                    <div class="text-start">
                        <div class="row mb-2">
                            <div class="col-4 text-muted">
                                <i class="fas fa-envelope me-1"></i>Email:
                            </div>
                            <div class="col-8">
                                <a href="mailto:<?= esc($user['correo']) ?>" class="text-decoration-none">
                                    <?= esc($user['correo']) ?>
                                </a>
                            </div>
                        </div>
                        
                        <?php if (!empty($user['telefono'])): ?>
                        <div class="row mb-2">
                            <div class="col-4 text-muted">
                                <i class="fas fa-phone me-1"></i>Teléfono:
                            </div>
                            <div class="col-8">
                                <a href="tel:<?= esc($user['telefono']) ?>" class="text-decoration-none">
                                    <?= esc($user['telefono']) ?>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="row mb-2">
                            <div class="col-4 text-muted">
                                <i class="fas fa-calendar-plus me-1"></i>Registro:
                            </div>
                            <div class="col-8">
                                <?= date('d/m/Y H:i', strtotime($user['fechaRegistro'])) ?>
                            </div>
                        </div>

                        <?php if ($user['fechaUpdate'] && $user['fechaUpdate'] !== $user['fechaRegistro']): ?>
                        <div class="row">
                            <div class="col-4 text-muted">
                                <i class="fas fa-edit me-1"></i>Actualizado:
                            </div>
                            <div class="col-8">
                                <?= date('d/m/Y H:i', strtotime($user['fechaUpdate'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Empresarial y Rol -->
        <div class="col-lg-8 mb-4">
            <div class="row h-100">
                <!-- Información de la Empresa -->
                <div class="col-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-building me-2"></i>Información de la Empresa
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted mb-1">
                                            <i class="fas fa-building me-1"></i>Empresa
                                        </label>
                                        <p class="h5 mb-0"><?= esc($user['empresa_nombre']) ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted mb-1">
                                            <i class="fas fa-id-card me-1"></i>RUC
                                        </label>
                                        <p class="h5 mb-0"><?= esc($user['empresa_ruc']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Rol -->
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user-tag me-2"></i>Rol y Permisos
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted mb-1">
                                            <i class="fas fa-crown me-1"></i>Rol
                                        </label>
                                        <p class="h5 mb-0">
                                            <span class="badge bg-primary fs-6">
                                                <?= esc($user['rol_nombre']) ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted mb-1">
                                            <i class="fas fa-info-circle me-1"></i>Descripción
                                        </label>
                                        <p class="mb-0"><?= esc($user['rol_descripcion'] ?? 'Sin descripción') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas adicionales (si es necesario) -->
    <?php if ($isOwnProfile): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Actividad Reciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">
                                    <i class="fas fa-calendar-check"></i>
                                </h4>
                                <p class="text-muted mb-0">Último acceso</p>
                                <small class="text-muted">Hoy</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-success mb-1">
                                    <i class="fas fa-tasks"></i>
                                </h4>
                                <p class="text-muted mb-0">Tareas completadas</p>
                                <small class="text-muted">En desarrollo</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-warning mb-1">
                                    <i class="fas fa-clock"></i>
                                </h4>
                                <p class="text-muted mb-0">Tiempo activo</p>
                                <small class="text-muted">En desarrollo</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info mb-1">
                                <i class="fas fa-star"></i>
                            </h4>
                            <p class="text-muted mb-0">Puntuación</p>
                            <small class="text-muted">En desarrollo</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.avatar-circle {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.badge {
    font-size: 0.9em;
}

.border-end:last-child {
    border-right: none !important;
}
</style>
<?= $this->endsection() ?>
