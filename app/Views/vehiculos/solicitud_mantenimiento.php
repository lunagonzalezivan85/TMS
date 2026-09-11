<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('vehiculos') ?>">Vehículos</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>"><?= $vehiculo['placa'] ?></a></li>
            <li class="breadcrumb-item active">Solicitud de Mantenimiento</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-wrench text-warning me-2"></i>
                        Solicitud de Mantenimiento
                    </h2>
                    <p class="text-muted mb-0">Vehículo: <strong><?= $vehiculo['placa'] ?> - <?= $vehiculo['marca'] ?> <?= $vehiculo['modelo'] ?></strong></p>
                </div>
                <div>
                    <a href="<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Vehículo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información de la Solicitud -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Detalles de la Solicitud
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Estado Actual</h6>
                            <?php
                            $estadoClass = [
                                'PENDIENTE' => 'warning',
                                'APROBADA' => 'info',
                                'EN_PROCESO' => 'primary',
                                'COMPLETADA' => 'success',
                                'CANCELADA' => 'danger'
                            ];
                            $class = $estadoClass[$solicitud['estado']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $class ?> fs-6 px-3 py-2">
                                <?= ucfirst(strtolower(str_replace('_', ' ', $solicitud['estado']))) ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Fecha de Solicitud</h6>
                            <p class="mb-0">
                                <i class="fas fa-calendar me-2"></i>
                                <?= date('d/m/Y H:i', strtotime($solicitud['fechaRegistro'])) ?>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Tipo de Mantenimiento</h6>
                            <p class="mb-0">
                                <i class="fas fa-tools me-2"></i>
                                <?= ucfirst(strtolower($solicitud['tipoMantenimiento'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Prioridad</h6>
                            <?php
                            $prioridadClass = [
                                'BAJA' => 'success',
                                'MEDIA' => 'warning',
                                'ALTA' => 'danger',
                                'CRITICA' => 'dark'
                            ];
                            $prioClass = $prioridadClass[$solicitud['prioridad']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $prioClass ?>">
                                <?= ucfirst(strtolower($solicitud['prioridad'])) ?>
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Descripción del Problema</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0"><?= nl2br(esc($solicitud['descripcion'])) ?></p>
                        </div>
                    </div>

                    <?php if (isset($solicitud['observaciones']) && !empty($solicitud['observaciones'])): ?>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Observaciones del Técnico</h6>
                        <div class="bg-info bg-opacity-10 p-3 rounded border-start border-info border-4">
                            <p class="mb-0"><?= nl2br(esc($solicitud['observaciones'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Estado y Acciones -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Acciones
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($solicitud['estado'] == 'PENDIENTE'): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Pendiente de Revisión</strong><br>
                            Su solicitud está siendo evaluada por el equipo de mantenimiento.
                        </div>
                    <?php elseif ($solicitud['estado'] == 'APROBADA'): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-thumbs-up me-2"></i>
                            <strong>Solicitud Aprobada</strong><br>
                            El mantenimiento será programado pronto.
                        </div>
                    <?php elseif ($solicitud['estado'] == 'EN_PROCESO'): ?>
                        <div class="alert alert-primary">
                            <i class="fas fa-wrench me-2"></i>
                            <strong>En Proceso</strong><br>
                            El mantenimiento está siendo realizado.
                        </div>
                    <?php elseif ($solicitud['estado'] == 'COMPLETADA'): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Completado</strong><br>
                            El mantenimiento ha sido finalizado exitosamente.
                        </div>
                    <?php elseif ($solicitud['estado'] == 'CANCELADA'): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Cancelado</strong><br>
                            La solicitud ha sido cancelada.
                        </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2">
                        <a href="<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>" class="btn btn-outline-primary">
                            <i class="fas fa-car me-2"></i>Ver Vehículo
                        </a>
                        
                        <?php if (in_array($solicitud['estado'], ['PENDIENTE', 'APROBADA'])): ?>
                        <button class="btn btn-outline-danger" onclick="confirmarCancelacion()">
                            <i class="fas fa-times me-2"></i>Cancelar Solicitud
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Información del Vehículo -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-car me-2"></i>
                        Información del Vehículo
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Placa</small>
                        <p class="mb-0 fw-bold"><?= $vehiculo['placa'] ?></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Marca y Modelo</small>
                        <p class="mb-0"><?= $vehiculo['marca'] ?> <?= $vehiculo['modelo'] ?></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Año</small>
                        <p class="mb-0"><?= $vehiculo['anio'] ?></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Estado Actual</small>
                        <p class="mb-0">
                            <span class="badge bg-<?= $vehiculo['estado'] == 'ACTIVO' ? 'success' : ($vehiculo['estado'] == 'INACTIVO' ? 'secondary' : 'warning') ?>">
                                <?= $vehiculo['estado'] ?>
                            </span>
                        </p>
                    </div>
                    <?php if (!empty($vehiculo['conductor_nombre'])): ?>
                    <div class="mb-0">
                        <small class="text-muted">Conductor Asignado</small>
                        <p class="mb-0"><?= $vehiculo['conductor_nombre'] ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmarCancelacion() {
    if (confirm('¿Está seguro que desea cancelar esta solicitud de mantenimiento?')) {
        // Aquí se podría implementar la funcionalidad de cancelación
        alert('Funcionalidad de cancelación pendiente de implementar');
    }
}
</script>
<?= $this->endSection() ?>
