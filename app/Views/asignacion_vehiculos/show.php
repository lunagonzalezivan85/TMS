<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('asignacion-vehiculos') ?>">Asignación de Vehículos</a></li>
            <li class="breadcrumb-item active">Detalles de Asignación</li>
        </ol>
    </nav>

    <!-- Información Principal -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información de la Asignación #<?= $asignacion['id'] ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Estado -->
                        <div class="col-md-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Estado de la Asignación</h6>
                                <?php if ($asignacion['estado'] === 'ACTIVA'): ?>
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>ACTIVA
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-times-circle me-1"></i>INACTIVA
                                    </span>
                                <?php endif; ?>
                            </div>
                            <hr>
                        </div>

                        <!-- Información del Vehículo -->
                        <div class="col-md-6">
                            <h6><i class="fas fa-car me-2 text-primary"></i>Información del Vehículo</h6>
                            <div class="mb-3">
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Placa:</strong></div>
                                    <div class="col-7"><?= esc($asignacion['placa']) ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Marca:</strong></div>
                                    <div class="col-7"><?= esc($asignacion['marca']) ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Modelo:</strong></div>
                                    <div class="col-7"><?= esc($asignacion['modelo']) ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Año:</strong></div>
                                    <div class="col-7"><?= esc($asignacion['anio']) ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Kilometraje:</strong></div>
                                    <div class="col-7"><?= number_format($asignacion['kilometraje']) ?> km</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Tipo de Unidad:</strong></div>
                                    <div class="col-7">
                                        <span class="badge bg-info"><?= esc($asignacion['tipo_unidad_descripcion'] ?? 'No asignado') ?></span>
                                    </div>
                                </div>
                                <?php if ($asignacion['tipo_operacion_descripcion']): ?>
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Tipo de Operación:</strong></div>
                                    <div class="col-7">
                                        <span class="badge bg-warning text-dark"><?= esc($asignacion['tipo_operacion_descripcion']) ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Información del Conductor -->
                        <div class="col-md-6">
                            <h6><i class="fas fa-user me-2 text-success"></i>Información del Conductor</h6>
                            <div class="mb-3">
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Nombre:</strong></div>
                                    <div class="col-7"><?= esc($asignacion['conductor_nombre']) ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5"><strong>DNI:</strong></div>
                                    <div class="col-7"><?= esc($asignacion['conductor_dni']) ?></div>
                                </div>
                                <?php if ($asignacion['conductor_telefono']): ?>
                                <div class="row mb-2">
                                    <div class="col-5"><strong>Teléfono:</strong></div>
                                    <div class="col-7"><?= esc($asignacion['conductor_telefono']) ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Fechas y Motivos -->
                        <div class="col-md-12">
                            <hr>
                            <h6><i class="fas fa-calendar me-2 text-warning"></i>Fechas y Motivos</h6>
                            
                            <div class="row mb-3">
                                <!-- Información de Asignación -->
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0"><i class="fas fa-play me-2"></i>Asignación</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($asignacion['fecha_asignacion'])) ?></p>
                                            <p><strong>Motivo:</strong></p>
                                            <div class="bg-light p-2 rounded">
                                                <?= nl2br(esc($asignacion['observaciones'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información de Desasignación -->
                                <div class="col-md-6">
                                    <?php if ($asignacion['estado'] === 'INACTIVA' && $asignacion['fecha_desasignacion']): ?>
                                        <div class="card border-danger">
                                            <div class="card-header bg-danger text-white">
                                                <h6 class="mb-0"><i class="fas fa-stop me-2"></i>Desasignación</h6>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($asignacion['fecha_desasignacion'])) ?></p>
                                                <p><strong>Motivo:</strong></p>
                                                <div class="bg-light p-2 rounded">
                                                    <?= nl2br(esc($asignacion['motivo_desasignacion'])) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif ($asignacion['estado'] === 'ACTIVA'): ?>
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Estado Actual</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-check-circle text-success fa-3x mb-2"></i>
                                                <p class="mb-0"><strong>Asignación Activa</strong></p>
                                                <small class="text-muted">El vehículo está actualmente asignado</small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="card border-secondary">
                                            <div class="card-header bg-secondary text-white">
                                                <h6 class="mb-0"><i class="fas fa-question me-2"></i>Sin Información</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i>
                                                <p class="mb-0">No hay información de desasignación</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Acciones -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Acciones
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if ($asignacion['estado'] === 'ACTIVA'): ?>
                            <a href="<?= base_url('asignacion-vehiculos/desasignar/' . $asignacion['id']) ?>" 
                               class="btn btn-warning">
                                <i class="fas fa-unlink me-2"></i>Desasignar Vehículo
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('vehiculos/show/' . $asignacion['id_vehiculo']) ?>" 
                           class="btn btn-info">
                            <i class="fas fa-car me-2"></i>Ver Vehículo
                        </a>
                        
                        <a href="<?= base_url('conductores/show/' . $asignacion['id_conductor']) ?>" 
                           class="btn btn-success">
                            <i class="fas fa-user me-2"></i>Ver Conductor
                        </a>
                        
                        <hr>
                        
                        <a href="<?= base_url('asignacion-vehiculos') ?>" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información de Auditoría -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Creado por:</small><br>
                        <strong><?= esc($asignacion['usuario_crea_nombre'] ?? 'Sistema') ?></strong><br>
                        <small><?= date('d/m/Y H:i', strtotime($asignacion['fecha_registro'])) ?></small>
                    </div>
                    
                    <?php if ($asignacion['fecha_actualizacion'] !== $asignacion['fecha_registro']): ?>
                    <div class="mb-3">
                        <small class="text-muted">Última modificación:</small><br>
                        <strong><?= esc($asignacion['usuario_edita_nombre'] ?? 'Sistema') ?></strong><br>
                        <small><?= date('d/m/Y H:i', strtotime($asignacion['fecha_actualizacion'])) ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Estadísticas de Tiempo -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Tiempo de Asignación
                    </h6>
                </div>
                <div class="card-body">
                    <?php
                    $fechaAsignacion = new DateTime($asignacion['fecha_asignacion']);
                    
                    // Determinar fecha final (desasignación o actual)
                    if ($asignacion['estado'] === 'INACTIVA' && $asignacion['fecha_desasignacion']) {
                        $fechaFinal = new DateTime($asignacion['fecha_desasignacion']);
                        $estadoTexto = 'estuvo asignado';
                        $colorBadge = 'bg-secondary';
                    } else {
                        $fechaFinal = new DateTime();
                        $estadoTexto = 'lleva asignado';
                        $colorBadge = 'bg-success';
                    }
                    
                    // Calcular diferencia
                    $diferencia = $fechaAsignacion->diff($fechaFinal);
                    
                    // Formatear tiempo transcurrido
                    $tiempoTexto = '';
                    if ($diferencia->y > 0) {
                        $tiempoTexto .= $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '') . ', ';
                    }
                    if ($diferencia->m > 0) {
                        $tiempoTexto .= $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '') . ', ';
                    }
                    if ($diferencia->d > 0) {
                        $tiempoTexto .= $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '') . ', ';
                    }
                    if ($diferencia->h > 0) {
                        $tiempoTexto .= $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '') . ', ';
                    }
                    if ($diferencia->i > 0) {
                        $tiempoTexto .= $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '');
                    }
                    
                    // Limpiar comas finales
                    $tiempoTexto = rtrim($tiempoTexto, ', ');
                    
                    // Si es muy poco tiempo, mostrar "menos de un minuto"
                    if (empty($tiempoTexto)) {
                        $tiempoTexto = 'menos de un minuto';
                    }
                    
                    // Calcular total de días para mostrar como número principal
                    $totalDias = $diferencia->days;
                    ?>
                    
                    <div class="text-center mb-3">
                        <h2 class="text-primary mb-1"><?= $totalDias ?></h2>
                        <p class="mb-2">
                            <span class="badge <?= $colorBadge ?> fs-6">
                                <?= $totalDias == 1 ? 'Día' : 'Días' ?> <?= $estadoTexto ?>
                            </span>
                        </p>
                        <hr>
                        <div class="text-muted">
                            <strong>Tiempo detallado:</strong><br>
                            <span class="fs-6"><?= $tiempoTexto ?></span>
                        </div>
                    </div>
                    
                    <!-- Fechas de referencia -->
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted">Inicio</small><br>
                            <strong><?= $fechaAsignacion->format('d/m/Y') ?></strong><br>
                            <small><?= $fechaAsignacion->format('H:i') ?></small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">
                                <?= $asignacion['estado'] === 'ACTIVA' ? 'Actual' : 'Fin' ?>
                            </small><br>
                            <strong><?= $fechaFinal->format('d/m/Y') ?></strong><br>
                            <small><?= $fechaFinal->format('H:i') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge.fs-6 {
    font-size: 0.9rem !important;
    padding: 0.5rem 0.75rem;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.card-header h6 {
    color: #495057;
}
</style>
<?= $this->endSection() ?>
