<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <div class="row g-2">
        <div class="col-12">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0"><i class="fas fa-file-alt me-2 text-success"></i><?= $title ?></h4>
                <div class="d-flex gap-2">
                    <?php if (in_array(strtolower((string)session('rol_name')), ['administrador', 'supervisor']) && in_array($solicitud['estado'], ['PENDIENTE', 'APROBADA'])): ?>
                        <a href="<?= base_url('solicitudes/asignar/' . $solicitud['id']) ?>" class="btn btn-sm btn-success rounded-pill">
                            <i class="fas fa-user-cog me-2"></i>Asignar técnico
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('solicitudes/reporte/' . $solicitud['id']) ?>" onclick="window.open(this.href, 'reporte', 'width=900,height=700,scrollbars=yes'); return false;" class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class="fas fa-print me-2"></i>Reporte
                    </a>
                    <a href="<?= base_url('solicitudes') ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-2">
                        <div class="card-header bg-white border-0 pt-2 pb-0 px-2">
                            <h6 class="fw-bold mb-0" style="font-size:0.85rem;"><i class="fas fa-car me-2 text-success"></i>Información del Vehículo</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Placa</small>
                                    <p class="fw-semibold fs-5 mb-0"><?= esc($vehiculo['placa'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Marca / Modelo</small>
                                    <p class="fw-semibold mb-0"><?= esc(($vehiculo['marca'] ?? '') . ' ' . ($vehiculo['modelo'] ?? '')) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Estado del vehículo</small>
                                    <p class="mb-0"><?= vehiculo_estado_badge($vehiculo['estado'] ?? 'ACTIVO') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Kilometraje</small>
                                    <p class="fw-semibold mb-0"><?= number_format((int)($vehiculo['kilometraje'] ?? 0)) ?> km</p>
                                </div>
                                <div class="col-md-8">
                                    <small class="text-muted">Conductor asignado</small>
                                    <p class="fw-semibold mb-0"><?= esc(($conductor['nombre'] ?? '') . ' ' . ($conductor['apellido'] ?? 'N/A')) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 pt-2 pb-0 px-2">
                            <h6 class="fw-bold mb-0" style="font-size:0.85rem;"><i class="fas fa-clipboard-list me-2 text-success"></i>Detalle de la Solicitud</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <small class="text-muted">Tipo de mantenimiento</small>
                                    <p class="fw-semibold mb-0"><?= esc($solicitud['tipo_mantenimiento'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Tipo de problema</small>
                                    <p class="fw-semibold mb-0"><?= esc($tipoProblema['nombre'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Prioridad</small>
                                    <?php
                                    $prioridades = [1 => 'Baja', 2 => 'Media', 3 => 'Alta', 4 => 'Crítica'];
                                    $p = (int)($solicitud['prioridad'] ?? 2);
                                    ?>
                                    <p class="mb-0"><span class="badge <?= ['text-bg-success','text-bg-warning','text-bg-orange','text-bg-danger'][$p-1] ?? 'text-bg-secondary' ?> rounded-pill"><?= $prioridades[$p] ?? 'Media' ?></span></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Condición de movilidad</small>
                                    <p class="fw-semibold mb-0"><?= esc($solicitud['condicion_movilidad'] ?? 'No especificada') ?></p>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">Ubicación actual</small>
                                    <p class="fw-semibold mb-0"><?= esc($solicitud['ubicacion'] ?? 'No especificada') ?></p>
                                </div>
                            </div>

                            <div class="bg-light rounded-3 p-2">
                                <small class="text-muted">Descripción</small>
                                <p class="mb-0 small"><?= nl2br(esc($solicitud['descripcion'])) ?></p>
                            </div>

                            <?php if (!empty($solicitud['url_foto'])): ?>
                                <div class="mt-3">
                                    <small class="text-muted">Evidencia</small>
                                    <div class="mt-1">
                                        <a href="<?= base_url('public/' . $solicitud['url_foto']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill">
                                            <i class="fas fa-image me-2"></i>Ver evidencia adjunta
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <?php if (!empty($sugerencia)): ?>
                    <div class="card border-0 rounded-4 mb-2" style="background:#eff6ff;border:1px solid #bfdbfe !important;">
                        <div class="card-body p-2 d-flex align-items-start">
                            <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#dbeafe;">
                                <i class="fas fa-lightbulb text-primary"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-primary mb-1" style="font-size:0.85rem;">Sugerencia de acción</h6>
                                <p class="mb-0 small text-dark"><?= esc($sugerencia) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="card border-0 shadow-sm rounded-4 mb-2">
                        <div class="card-body p-2">
                            <h6 class="text-muted mb-1" style="font-size:0.85rem;">Estado actual</h6>
                            <span class="badge text-bg-warning rounded-pill"><?= esc($solicitud['estado']) ?></span>
                            <hr class="my-2">
                            <small class="text-muted d-block mb-0">Código</small>
                            <p class="fw-bold mb-1"><?= esc($solicitud['codigo_consecutivo']) ?></p>
                            <small class="text-muted d-block mb-0">Fecha de solicitud</small>
                            <p class="fw-semibold mb-1 small"><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])) ?></p>
                            <small class="text-muted d-block mb-0">Solicitante</small>
                            <p class="fw-semibold mb-0 small"><?= esc($solicitud['solicitante']) ?></p>

                            <?php if (!empty($tecnico)): ?>
                                <hr class="my-2">
                                <small class="text-muted d-block mb-0">Técnico asignado</small>
                                <p class="fw-semibold mb-0 small">
                                    <i class="fas fa-user-cog text-success me-1"></i>
                                    <?= esc(($tecnico['nombre'] ?? '') . ' ' . ($tecnico['apellido'] ?? '')) ?>
                                </p>
                                <?php if (!empty($solicitud['fecha_asignacion'])): ?>
                                    <small class="text-muted d-block mb-0">Fecha de asignación</small>
                                    <p class="fw-semibold mb-0 small"><?= date('d/m/Y H:i', strtotime($solicitud['fecha_asignacion'])) ?></p>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php
                            $estadosFinales = ['COMPLETADA', 'FINALIZADA', 'CANCELADA', 'RECHAZADA'];
                            $esFinalizada = in_array(strtoupper($solicitud['estado']), $estadosFinales);
                            ?>
                            <?php if (!$esFinalizada && !empty($solicitud['fecha_asignacion'])): ?>
                                <?php
                                $fechaAsignacion = new DateTime($solicitud['fecha_asignacion']);
                                $hoy = new DateTime();
                                $dias = $fechaAsignacion->diff($hoy)->days;
                                $claseDias = $dias <= 3 ? 'text-bg-success' : ($dias <= 7 ? 'text-bg-warning' : 'text-bg-danger');
                                ?>
                                <div class="mt-2 p-2 rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-muted d-block" style="font-size:0.75rem;">Días transcurridos</small>
                                        <span class="badge <?= $claseDias ?> rounded-pill fs-6"><?= $dias ?> día<?= $dias != 1 ? 's' : '' ?></span>
                                    </div>
                                    <i class="fas fa-clock text-muted" style="font-size:1.5rem;"></i>
                                </div>
                            <?php elseif ($esFinalizada && !empty($solicitud['fecha_asignacion'])): ?>
                                <?php
                                $fechaAsignacion = new DateTime($solicitud['fecha_asignacion']);
                                $fechaCierre = new DateTime($solicitud['fecha_cierre'] ?? 'now');
                                $dias = $fechaAsignacion->diff($fechaCierre)->days;
                                ?>
                                <div class="mt-2 p-2 rounded-3 bg-light">
                                    <small class="text-muted d-block" style="font-size:0.75rem;">Días totales (asignación → cierre)</small>
                                    <span class="badge text-bg-secondary rounded-pill"><?= $dias ?> día<?= $dias != 1 ? 's' : '' ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-2">
                            <h6 class="fw-bold mb-2" style="font-size:0.85rem;">Siguientes pasos</h6>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-success p-1">1</span></div>
                                <div class="ms-2">
                                    <p class="fw-semibold mb-0 small">Aprobación</p>
                                    <small class="text-muted" style="font-size:0.75rem;">Un supervisor aprueba o rechaza.</small>
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-secondary p-1">2</span></div>
                                <div class="ms-2">
                                    <p class="fw-semibold mb-0 small">Diagnóstico</p>
                                    <small class="text-muted" style="font-size:0.75rem;">Se evalúa el vehículo.</small>
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-secondary p-1">3</span></div>
                                <div class="ms-2">
                                    <p class="fw-semibold mb-0 small">Asignación</p>
                                    <small class="text-muted" style="font-size:0.75rem;">Se asigna un mecánico.</small>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-secondary p-1">4</span></div>
                                <div class="ms-2">
                                    <p class="fw-semibold mb-0 small">Finalización</p>
                                    <small class="text-muted" style="font-size:0.75rem;">Reparado, descartado o pendiente.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
