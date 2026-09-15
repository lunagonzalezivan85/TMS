<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0"><i class="fas fa-file-alt me-2 text-success"></i><?= $title ?></h3>
                <a href="<?= base_url('solicitudes') ?>" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-0 pt-4 ps-4">
                            <h5 class="fw-bold"><i class="fas fa-car me-2 text-success"></i>Información del Vehículo</h5>
                        </div>
                        <div class="card-body ps-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <small class="text-muted">Placa</small>
                                    <p class="fw-semibold fs-5"><?= esc($vehiculo['placa'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Marca / Modelo</small>
                                    <p class="fw-semibold"><?= esc(($vehiculo['marca'] ?? '') . ' ' . ($vehiculo['modelo'] ?? '')) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Estado del vehículo</small>
                                    <p class="fw-semibold"><?= vehiculo_estado_badge($vehiculo['estado'] ?? 'ACTIVO') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Kilometraje</small>
                                    <p class="fw-semibold"><?= number_format((int)($vehiculo['kilometraje'] ?? 0)) ?> km</p>
                                </div>
                                <div class="col-md-8">
                                    <small class="text-muted">Conductor asignado</small>
                                    <p class="fw-semibold"><?= esc(($conductor['nombre'] ?? '') . ' ' . ($conductor['apellido'] ?? 'N/A')) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 pt-4 ps-4">
                            <h5 class="fw-bold"><i class="fas fa-clipboard-list me-2 text-success"></i>Detalle de la Solicitud</h5>
                        </div>
                        <div class="card-body ps-4">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">Tipo de mantenimiento</small>
                                    <p class="fw-semibold"><?= esc($solicitud['tipo_mantenimiento'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Tipo de problema</small>
                                    <p class="fw-semibold"><?= esc($tipoProblema['nombre'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Prioridad</small>
                                    <?php
                                    $prioridades = [1 => 'Baja', 2 => 'Media', 3 => 'Alta', 4 => 'Crítica'];
                                    $p = (int)($solicitud['prioridad'] ?? 2);
                                    ?>
                                    <p><span class="badge <?= ['text-bg-success','text-bg-warning','text-bg-orange','text-bg-danger'][$p-1] ?? 'text-bg-secondary' ?> rounded-pill"><?= $prioridades[$p] ?? 'Media' ?></span></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Condición de movilidad</small>
                                    <p class="fw-semibold"><?= esc($solicitud['condicion_movilidad'] ?? 'No especificada') ?></p>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">Ubicación actual</small>
                                    <p class="fw-semibold"><?= esc($solicitud['ubicacion'] ?? 'No especificada') ?></p>
                                </div>
                            </div>

                            <div class="bg-light rounded-4 p-3">
                                <small class="text-muted">Descripción</small>
                                <p class="mb-0"><?= nl2br(esc($solicitud['descripcion'])) ?></p>
                            </div>

                            <?php if (!empty($solicitud['url_foto'])): ?>
                                <div class="mt-4">
                                    <small class="text-muted">Evidencia</small>
                                    <div class="mt-2">
                                        <a href="<?= base_url('public/' . $solicitud['url_foto']) ?>" target="_blank" class="btn btn-outline-success rounded-pill">
                                            <i class="fas fa-image me-2"></i>Ver evidencia adjunta
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h6 class="text-muted mb-2">Estado actual</h6>
                            <span class="badge text-bg-warning fs-6 rounded-pill"><?= esc($solicitud['estado']) ?></span>
                            <hr class="my-3">
                            <small class="text-muted d-block mb-1">Código</small>
                            <p class="fw-bold"><?= esc($solicitud['codigo_consecutivo']) ?></p>
                            <small class="text-muted d-block mb-1">Fecha de solicitud</small>
                            <p class="fw-semibold"><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])) ?></p>
                            <small class="text-muted d-block mb-1">Solicitante</small>
                            <p class="fw-semibold"><?= esc($solicitud['solicitante']) ?></p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Siguientes pasos</h6>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-success p-2">1</span></div>
                                <div class="ms-3">
                                    <p class="fw-semibold mb-0">Aprobación</p>
                                    <small class="text-muted">Un supervisor aprueba o rechaza la solicitud.</small>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-secondary p-2">2</span></div>
                                <div class="ms-3">
                                    <p class="fw-semibold mb-0">Diagnóstico</p>
                                    <small class="text-muted">Se evalúa si el vehículo entra en mantenimiento.</small>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-secondary p-2">3</span></div>
                                <div class="ms-3">
                                    <p class="fw-semibold mb-0">Asignación</p>
                                    <small class="text-muted">Se asigna un mecánico responsable.</small>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-secondary p-2">4</span></div>
                                <div class="ms-3">
                                    <p class="fw-semibold mb-0">Finalización</p>
                                    <small class="text-muted">Reparado, descartado o pendiente de compra.</small>
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
