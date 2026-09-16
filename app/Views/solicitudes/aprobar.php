<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <div class="row g-2">
        <div class="col-12">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="fw-bold mb-0"><i class="fas fa-clipboard-check me-2 text-success"></i><?= $title ?></h4>
                <a href="<?= base_url('solicitudes/show/' . $solicitud['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="row g-2">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-2">
                        <div class="card-header bg-white border-0 pt-2 pb-0 px-2">
                            <h6 class="fw-bold mb-0" style="font-size:0.85rem;"><i class="fas fa-info-circle me-2 text-success"></i>Resumen de la solicitud</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Código</small>
                                    <p class="fw-bold mb-0"><?= esc($solicitud['codigo_consecutivo']) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Estado</small>
                                    <p class="mb-0"><span class="badge text-bg-warning rounded-pill"><?= esc($solicitud['estado']) ?></span></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Fecha de solicitud</small>
                                    <p class="fw-semibold mb-0 small"><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Solicitante</small>
                                    <p class="fw-semibold mb-0 small"><?= esc($solicitud['solicitante']) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Vehículo</small>
                                    <p class="fw-semibold mb-0 small"><?= esc(($vehiculo['placa'] ?? 'N/A') . ' - ' . ($vehiculo['marca'] ?? '') . ' ' . ($vehiculo['modelo'] ?? '')) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Prioridad</small>
                                    <?php
                                    $prioridades = [1 => 'Baja', 2 => 'Media', 3 => 'Alta', 4 => 'Crítica'];
                                    $p = (int)($solicitud['prioridad'] ?? 2);
                                    ?>
                                    <p class="mb-0"><span class="badge <?= ['text-bg-success','text-bg-warning','text-bg-orange','text-bg-danger'][$p-1] ?? 'text-bg-secondary' ?> rounded-pill"><?= $prioridades[$p] ?? 'Media' ?></span></p>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">Descripción</small>
                                    <div class="bg-light rounded-3 p-2">
                                        <p class="mb-0 small"><?= nl2br(esc($solicitud['descripcion'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 pt-2 pb-0 px-2">
                            <h6 class="fw-bold mb-0" style="font-size:0.85rem;"><i class="fas fa-check-circle me-2 text-success"></i>Decisión</h6>
                        </div>
                        <div class="card-body p-2">
                            <form action="<?= base_url('solicitudes/guardar-aprobacion/' . $solicitud['id']) ?>" method="POST">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Observaciones de la aprobación</label>
                                    <textarea name="observaciones_aprobacion" class="form-control rounded-3" rows="3" placeholder="Notas o comentarios sobre la decisión (opcional)"></textarea>
                                </div>

                                <div class="d-flex justify-content-between pt-2">
                                    <button type="submit" name="accion" value="rechazar" class="btn btn-outline-danger rounded-pill"
                                        onclick="return confirm('¿Rechazar esta solicitud? La solicitud se cerrará y no podrá asignarse un técnico.')">
                                        <i class="fas fa-times me-2"></i>Rechazar
                                    </button>
                                    <button type="submit" name="accion" value="aprobar" class="btn btn-success rounded-pill"
                                        onclick="return confirm('¿Aprobar esta solicitud? Podrá asignar un técnico a continuación.')">
                                        <i class="fas fa-check me-2"></i>Aprobar solicitud
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 rounded-4 mb-2" style="background:#eff6ff;border:1px solid #bfdbfe !important;">
                        <div class="card-body p-2">
                            <h6 class="fw-bold text-primary mb-1" style="font-size:0.85rem;"><i class="fas fa-lightbulb me-2"></i>Información</h6>
                            <p class="mb-0 small text-dark">
                                Al <strong>aprobar</strong>, la solicitud cambia a estado <span class="badge text-bg-success rounded-pill">APROBADA</span> y podrá asignar un técnico.
                                Al <strong>rechazar</strong>, la solicitud se cierra como <span class="badge text-bg-danger rounded-pill">RECHAZADA</span>.
                            </p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-2">
                            <h6 class="fw-bold mb-2" style="font-size:0.85rem;">Flujo de la solicitud</h6>
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
                                    <p class="fw-semibold mb-0 small">Asignación</p>
                                    <small class="text-muted" style="font-size:0.75rem;">Se asigna un técnico responsable.</small>
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0"><span class="badge rounded-circle bg-secondary p-1">3</span></div>
                                <div class="ms-2">
                                    <p class="fw-semibold mb-0 small">Diagnóstico</p>
                                    <small class="text-muted" style="font-size:0.75rem;">El técnico evalúa el vehículo.</small>
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
