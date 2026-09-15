<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-3 px-3">
                    <h4 class="fw-bold mb-0"><i class="fas fa-user-cog me-2 text-success"></i><?= $title ?></h4>
                </div>
                <div class="card-body p-3">
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <small class="text-muted">Código</small>
                                <p class="fw-semibold mb-0"><?= esc($solicitud['codigo_consecutivo']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Estado</small>
                                <p class="mb-0"><span class="badge text-bg-warning rounded-pill"><?= esc($solicitud['estado']) ?></span></p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Descripción</small>
                                <p class="mb-0 small"><?= character_limiter(esc($solicitud['descripcion']), 180) ?></p>
                            </div>
                        </div>
                    </div>

                    <form action="<?= base_url('solicitudes/guardar-asignacion/' . $solicitud['id']) ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="id_tecnico" class="form-label fw-semibold">Técnico responsable</label>
                            <select name="id_tecnico" id="id_tecnico" class="form-select rounded-3" required>
                                <option value="">Seleccione un técnico</option>
                                <?php foreach ($tecnicos as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= esc(($t['nombre'] ?? '') . ' ' . ($t['apellido'] ?? '')) ?> (<?= esc($t['usuario'] ?? '') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($tecnicos)): ?>
                                <div class="form-text text-danger">No hay técnicos activos registrados. Cree un usuario con rol Técnico o Mecánico.</div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_asignacion" class="form-label fw-semibold">Fecha de asignación</label>
                            <input type="datetime-local" name="fecha_asignacion" id="fecha_asignacion" class="form-control rounded-3" value="<?= date('Y-m-d\TH:i') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="observaciones_asignacion" class="form-label fw-semibold">Observaciones de la asignación</label>
                            <textarea name="observaciones_asignacion" id="observaciones_asignacion" class="form-control rounded-3" rows="3" placeholder="Notas u observaciones para el técnico"></textarea>
                        </div>

                        <div class="d-flex justify-content-between pt-2">
                            <a href="<?= base_url('solicitudes/show/' . $solicitud['id']) ?>" class="btn btn-outline-secondary rounded-pill">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill" <?= empty($tecnicos) ? 'disabled' : '' ?>>
                                <i class="fas fa-check me-2"></i>Confirmar asignación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
