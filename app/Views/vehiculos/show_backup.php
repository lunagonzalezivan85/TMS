<?= $this->extend('layouts/main') ?>
<?php helper('vehiculo'); ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $page_title ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('vehiculos') ?>">Vehículos</a></li>
                    <li class="breadcrumb-item active"><?= $vehiculo['placa'] ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('vehiculos') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <a href="<?= base_url('vehiculos/edit/' . $vehiculo['id']) ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Información principal del vehículo -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-car me-2"></i>Información del Vehículo
                    </h6>
                    <span class="badge bg-<?= vehiculo_estado_class($vehiculo['estado']) ?> fs-6">
                        <?= vehiculo_estado_label($vehiculo['estado']) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold text-muted" width="40%">Código:</td>
                                    <td><?= esc($vehiculo['codigo_consecutivo']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Placa:</td>
                                    <td><span class="badge bg-dark fs-6"><?= esc($vehiculo['placa']) ?></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Marca:</td>
                                    <td><?= esc($vehiculo['marca']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Modelo:</td>
                                    <td><?= esc($vehiculo['modelo']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Año:</td>
                                    <td><?= esc($vehiculo['anio']) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold text-muted" width="40%">Kilometraje:</td>
                                    <td><?= number_format($vehiculo['kilometraje'] ?? 0) ?> km</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Estado:</td>
                                    <td>
                                        <span class="badge bg-<?= vehiculo_estado_class($vehiculo['estado']) ?>">
                                            <?= vehiculo_estado_label($vehiculo['estado']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php if ($vehiculo['motivo_inactividad']): ?>
                                <tr>
                                    <td class="fw-bold text-muted">Motivo:</td>
                                    <td><?= esc($vehiculo['motivo_inactividad']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="fw-bold text-muted">Tipo de Unidad:</td>
                                    <td>
                                        <?php if (!empty($vehiculo['tipo_unidad_descripcion'])): ?>
                                            <span class="badge bg-info"><?= esc($vehiculo['tipo_unidad_descripcion']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No asignado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Tipo de Operación:</td>
                                    <td>
                                        <?php if (!empty($vehiculo['tipo_operacion_descripcion'])): ?>
                                            <span class="badge bg-warning text-dark"><?= esc($vehiculo['tipo_operacion_descripcion']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No asignado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Centro de Costo:</td>
                                    <td>
                                        <?php if (!empty($centro_costo)): ?>
                                            <div class="d-flex flex-column">
                                                <span class="badge bg-primary mb-1" style="width: fit-content;">
                                                    <?= esc($centro_costo['CODIGO_CENTRO']) ?>
                                                </span>
                                                <small class="text-muted">
                                                    <?= esc($centro_costo['DESCRIPCION']) ?>
                                                </small>
                                            </div>
                                        <?php elseif (!empty($vehiculo['codigo_centro_costo'])): ?>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-warning text-dark me-2">
                                                    <?= esc($vehiculo['codigo_centro_costo']) ?>
                                                </span>
                                                <small class="text-muted">(Información no disponible)</small>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">No asignado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Empresa:</td>
                                    <td><?= esc($vehiculo['empresa_nombre']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if ($vehiculo['conductor_nombre']): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-user me-2"></i>Conductor Asignado
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nombre:</strong> <?= esc($vehiculo['conductor_nombre']) ?><br>
                                        <strong>DNI:</strong> <?= esc($vehiculo['conductor_dni']) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Fecha de Ingreso:</strong> 
                                        <?= date('d/m/Y', strtotime($vehiculo['conductor_fecha_ingreso'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Sin conductor asignado</strong> - Este vehículo no tiene conductor asignado actualmente.
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial de Estados -->
            <?php if (!empty($historial_estados)): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Historial de Estados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Motivo</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial_estados as $historial): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($historial['fecha_inicio'])) ?></td>
                                    <td><?= vehiculo_estado_badge($historial['estado']) ?></td>
                                    <td><?= $historial['motivo'] ?: '-' ?></td>
                                    <td><?= $historial['usuario_nombre'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Panel lateral con acciones y información adicional -->
        <div class="col-xl-4 col-lg-5">
            <!-- Acciones rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('vehiculos/edit/' . $vehiculo['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar Vehículo
                        </a>
                        
                        <a href="<?= base_url('vehiculos/documentos/' . $vehiculo['id']) ?>" class="btn btn-info">
                            <i class="fas fa-file-alt me-2"></i>Gestionar Documentos
                        </a>

                        <?php if ($vehiculo['estado'] == 'ACTIVO'): ?>
                        <button class="btn btn-warning cambiar-estado" 
                                data-id="<?= $vehiculo['id'] ?>" 
                                data-estado="INACTIVO">
                            <i class="fas fa-pause me-2"></i>Inactivar Vehículo
                        </button>
                        <?php else: ?>
                        <button class="btn btn-success cambiar-estado" 
                                data-id="<?= $vehiculo['id'] ?>" 
                                data-estado="ACTIVO">
                            <i class="fas fa-play me-2"></i>Activar Vehículo
                        </button>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Información de auditoría -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información de Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-bold text-muted" width="50%">Creado por:</td>
                            <td><?= $vehiculo['usuario_crea_nombre'] ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Fecha de registro:</td>
                            <td><?= date('d/m/Y H:i', strtotime($vehiculo['fechaRegistro'])) ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Última modificación:</td>
                            <td><?= date('d/m/Y H:i', strtotime($vehiculo['fechaUpdate'])) ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Modificado por:</td>
                            <td><?= $vehiculo['usuario_edita_nombre'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Estadísticas del vehículo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0" id="total_solicitudes">-</h4>
                                <small class="text-muted">Solicitudes de Mantenimiento</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0" id="mantenimientos_completados">-</h4>
                            <small class="text-muted">Mantenimientos Completados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado del Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body">
                    <input type="hidden" id="vehiculo_id" name="id" value="<?= $vehiculo['id'] ?>">
                    <input type="hidden" id="nuevo_estado" name="estado">
                    
                    <div class="mb-3">
                        <label for="motivo_cambio" class="form-label">Motivo del cambio</label>
                        <textarea class="form-control" id="motivo_cambio" name="motivo" rows="3" 
                                  placeholder="Describe el motivo del cambio de estado"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="mensaje_cambio_estado"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Cambio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para solicitar mantenimiento -->
<div class="modal fade" id="modalSolicitudMantenimiento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-wrench me-2"></i>Solicitar Mantenimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSolicitudMantenimiento">
                <div class="modal-body">
                    <input type="hidden" id="vehiculo_id_mantenimiento" name="id_vehiculo" value="<?= $vehiculo['id'] ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_mantenimiento" class="form-label">Tipo de Mantenimiento</label>
                                <select class="form-select" id="tipo_mantenimiento" name="tipo_mantenimiento" required>
                                    <option value="PREVENTIVO">Preventivo</option>
                                    <option value="CORRECTIVO" selected>Correctivo</option>
                                    <option value="EMERGENCIA">Emergencia</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-select" id="prioridad" name="prioridad" required>
                                    <option value="BAJA">Baja</option>
                                    <option value="MEDIA" selected>Media</option>
                                    <option value="ALTA">Alta</option>
                                    <option value="CRITICA">Crítica</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion_mantenimiento" class="form-label">Descripción del Problema</label>
                        <textarea class="form-control" id="descripcion_mantenimiento" name="descripcion" rows="4" 
                                  placeholder="Describe detalladamente el problema o mantenimiento requerido..." required></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Información:</strong> Una vez creada la solicitud, será revisada por el equipo de mantenimiento. 
                        Recibirás notificaciones sobre el estado de tu solicitud.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('public/assets/js/modules/Vehiculos.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    VehiculosShow.init(<?= $vehiculo['id'] ?>, '<?= base_url() ?>');
});
</script>
<?= $this->endSection() ?>
