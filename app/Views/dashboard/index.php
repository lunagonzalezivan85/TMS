<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
.welcome-banner {
    background: #0f172a;
    border-radius: 1rem;
    color: white;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 24px rgba(0,0,0,0.15);
}

.welcome-banner h2 {
    color: #4f46e5;
}

.quick-access-card {
    position: relative;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: none;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    min-height: 120px;
}

.quick-access-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.quick-access-card .icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    font-size: 1.5rem;
    color: white;
}

.quick-access-card h5 {
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
    color: #1e293b;
}

.quick-access-card .counter {
    font-size: 1.75rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.5rem;
}

.quick-access-card .badge-wrapper {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
}

.reminder-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 1rem;
    color: white;
    padding: 1.5rem;
}

.stats-chart-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Barra de navegación inferior flotante */
.bottom-nav {
    position: fixed;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border-radius: 50px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    z-index: 1000;
    width: 95%;
    max-width: 400px;
    justify-content: space-between;
}

.bottom-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: #64748b;
    transition: all 0.3s ease;
    min-width: 0;
    flex: 1;
    padding: 0.5rem 0;
}

.bottom-nav-item:hover,
.bottom-nav-item.active {
    color: #3B82F6;
}

.bottom-nav-item i {
    font-size: 1.3rem;
}

.bottom-nav-fab {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    transition: all 0.3s ease;
    cursor: pointer;
    flex-shrink: 0;
    margin: 0 0.25rem;
}

.bottom-nav-fab:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
}

@media (max-width: 768px) {
    .welcome-banner {
        padding: 1.5rem;
    }
    
    .bottom-nav {
        bottom: 1rem;
        padding: 0.5rem 0.75rem;
        gap: 0.5rem;
        width: 95%;
        max-width: 380px;
    }
    
    .bottom-nav-item i {
        font-size: 1.2rem;
    }
    
    .bottom-nav-fab {
        width: 48px;
        height: 48px;
        font-size: 1.1rem;
        margin: 0 0.5rem;
    }
}
    
    .quick-access-card {
        min-width: calc(50% - 0.75rem);
        padding: 1rem 0.75rem;
    }
    
    .quick-access-card .icon-wrapper {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .quick-access-card h5 {
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .quick-access-card {
        min-width: calc(50% - 0.5rem);
        padding: 0.75rem 0.5rem;
    }
    
    .quick-access-card .icon-wrapper {
        width: 35px;
        height: 35px;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .quick-access-card h5 {
        font-size: 0.75rem;
    }
    
    .quick-access-card .badge-wrapper {
        top: 0.25rem;
        right: 0.25rem;
    }
    
    .quick-access-card .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.4rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">Dashboard</li>
<?= $this->endSection() ?>

<?= $this->section('page-header') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Mensaje de Bienvenida -->
<div class="welcome-banner animate__animated animate__fadeIn">
    <div class="d-flex align-items-center justify-content-between">
        <div class="flex-grow-1">
            <h2 class="mb-2 fw-bold">¡Bienvenido al TMS!</h2>
            <p class="mb-0 text-white opacity-75">Sistema de Gestión de Transporte y Mantenimiento Vehicular</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-md-block">
                <div class="fs-6 fw-bold text-white" id="currentDate"></div>
                <div class="text-white opacity-75 small" id="currentTime"></div>
            </div>
            <div class="d-flex align-items-center gap-2 bg-white rounded-pill px-3 py-2 shadow-sm">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                    <?= strtoupper(substr(session('nombre') ?? 'U', 0, 1)) ?>
                </div>
                <div class="d-none d-md-block">
                    <div class="fw-bold text-dark small"><?= session('nombre') ?? 'Usuario' ?></div>
                    <div class="text-muted" style="font-size: 0.7rem;"><?= session('rol') ?? 'Usuario' ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accesos Rápidos -->
<div class="mb-4">
    <h5 class="fw-bold mb-3 text-dark">
        <i class="fas fa-bolt text-warning me-2"></i>Accesos Rápidos
    </h5>
    <div class="d-flex flex-wrap gap-2 gap-md-3">
        <a href="<?= base_url('vehiculos') ?>" class="quick-access-card flex-fill flex-md-grow-0 flex-md-shrink-0">
            <div class="badge-wrapper">
                <span class="badge bg-primary rounded-pill">Activo</span>
            </div>
            <div class="icon-wrapper bg-primary">
                <i class="fas fa-car"></i>
            </div>
            <h5>Vehículos</h5>
            <div class="counter"><?= $stats['total_vehicles'] ?? 0 ?></div>
        </a>
        <a href="<?= base_url('solicitudes') ?>" class="quick-access-card flex-fill flex-md-grow-0 flex-md-shrink-0">
            <div class="badge-wrapper">
                <span class="badge bg-success rounded-pill">Activo</span>
            </div>
            <div class="icon-wrapper bg-success">
                <i class="fas fa-tools"></i>
            </div>
            <h5>Mantenimiento</h5>
            <div class="counter"><?= $stats['total_maintenance'] ?? 0 ?></div>
        </a>
        <a href="<?= base_url('registro-combustible') ?>" class="quick-access-card flex-fill flex-md-grow-0 flex-md-shrink-0">
            <div class="badge-wrapper">
                <span class="badge bg-warning rounded-pill">Activo</span>
            </div>
            <div class="icon-wrapper bg-warning">
                <i class="fas fa-gas-pump"></i>
            </div>
            <h5>Combustible</h5>
            <div class="counter"><?= $combustible_stats['total_registros'] ?? 0 ?></div>
        </a>
        <a href="<?= base_url('conductores') ?>" class="quick-access-card flex-fill flex-md-grow-0 flex-md-shrink-0">
            <div class="badge-wrapper">
                <span class="badge bg-info rounded-pill">Activo</span>
            </div>
            <div class="icon-wrapper bg-info">
                <i class="fas fa-user-tie"></i>
            </div>
            <h5>Conductores</h5>
            <div class="counter"><?= $stats['total_conductores'] ?? 0 ?></div>
        </a>
        <a href="#" class="quick-access-card flex-fill flex-md-grow-0 flex-md-shrink-0">
            <div class="badge-wrapper">
                <span class="badge bg-danger rounded-pill">Alertas</span>
            </div>
            <div class="icon-wrapper bg-danger">
                <i class="fas fa-bell"></i>
            </div>
            <h5>Alertas</h5>
            <div class="counter"><?= $stats['alerts'] ?? 0 ?></div>
        </a>
    </div>
</div>

<!-- Columnas: 8 (Gráficos) y 4 (Recordatorio) -->
<div class="row">
    <!-- Columna de 8 - Estadísticas reales de combustible -->
    <div class="col-lg-8 mb-4">
        <div class="card stats-chart-card animate__animated animate__fadeInUp">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-gas-pump text-primary me-2"></i>Resumen de Combustible
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded-3 p-3 text-center h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Total Litros</div>
                            <div class="fs-4 fw-bold text-primary"><?= number_format($combustible_stats['total_litros'] ?? 0, 2) ?></div>
                            <div class="text-muted small">litros</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded-3 p-3 text-center h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Total Galones</div>
                            <div class="fs-4 fw-bold text-success"><?= number_format($combustible_stats['total_galones'] ?? 0, 2) ?></div>
                            <div class="text-muted small">galones</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded-3 p-3 text-center h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Promedio Litros</div>
                            <div class="fs-4 fw-bold text-warning"><?= number_format($combustible_stats['promedio_litros'] ?? 0, 2) ?></div>
                            <div class="text-muted small">por registro</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded-3 p-3 text-center h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Total Kilómetros</div>
                            <div class="fs-4 fw-bold text-info"><?= number_format($combustible_stats['total_kilometros'] ?? 0, 0) ?></div>
                            <div class="text-muted small">km recorridos</div>
                        </div>
                    </div>
                </div>
                <hr class="my-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-file-alt text-muted"></i>
                            <span class="text-muted small">Registros totales:</span>
                            <strong><?= number_format($combustible_stats['total_registros'] ?? 0) ?></strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-car text-muted"></i>
                            <span class="text-muted small">Vehículos con consumo:</span>
                            <strong><?= number_format($combustible_stats['total_vehiculos'] ?? 0) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna de 4 - Card de Recordatorio -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="fas fa-lightbulb text-warning fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Recordatorio</h5>
                        <p class="text-muted small mb-0">Documentos por vencer</p>
                    </div>
                </div>
                <p class="text-secondary mb-3">
                    No olvides revisar los documentos de conductores que vencen en los próximos 30 días.
                </p>
                <?php if (!empty($documentos_por_vencer)): ?>
                <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        <strong><?= count($documentos_por_vencer) ?> documentos por vencer</strong>
                    </div>
                </div>
                <a href="<?= base_url('conductores') ?>" class="btn btn-warning w-100">
                    <i class="fas fa-arrow-right me-1"></i>Ver Detalles
                </a>
                <?php else: ?>
                <div class="alert alert-success bg-success bg-opacity-10 border-0 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>¡Todo en orden!</strong>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Barra de Navegación Inferior Flotante -->
<nav class="bottom-nav d-flex d-md-none">
    <a href="<?= base_url('dashboard') ?>" class="bottom-nav-item active" title="Home">
        <i class="fas fa-home"></i>
    </a>
    <a href="<?= base_url('vehiculos') ?>" class="bottom-nav-item" title="Vehículos">
        <i class="fas fa-car"></i>
    </a>
    <button class="bottom-nav-fab" onclick="window.location.href='<?= base_url('registro-combustible/create') ?>'" title="Nuevo Registro">
        <i class="fas fa-plus"></i>
    </button>
    <a href="<?= base_url('solicitudes') ?>" class="bottom-nav-item" title="Mantenimiento">
        <i class="fas fa-tools"></i>
    </a>
    <a href="<?= base_url('conductores') ?>" class="bottom-nav-item" title="Conductores">
        <i class="fas fa-user"></i>
    </a>
    <!-- Botón de cierre de bomba en móvil -->
    <a id="bomba-mobile-nav" href="<?= base_url('lectura-bomba/cierre') ?>" class="bottom-nav-item" style="color: #ef4444; display: none;" title="Cerrar Bomba">
        <i class="fas fa-power-off"></i>
    </a>
</nav>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Fecha y hora actual
function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').textContent = now.toLocaleDateString('es-ES', options);
    document.getElementById('currentTime').textContent = now.toLocaleTimeString('es-ES');
}
updateDateTime();
setInterval(updateDateTime, 1000);
</script>
<?= $this->endSection() ?>
