<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0"><i class="fas fa-edit me-2 text-primary"></i><?= esc($title) ?></h4>
                <a href="<?= base_url('solicitudes/show/' . $solicitud['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="<?= base_url('solicitudes/update/' . $solicitud['id']) ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <!-- Tipo de mantenimiento -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipo de mantenimiento</label>
                                <select name="tipo_mantenimiento" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($tiposMantenimiento as $tm): ?>
                                        <option value="<?= $tm['id'] ?>"
                                            <?= (($solicitud['id_tipo_mantenimiento'] ?? '') == $tm['id']
                                                || strtoupper($solicitud['tipo_mantenimiento'] ?? '') === strtoupper($tm['nombre'])) ? 'selected' : '' ?>>
                                            <?= esc(ucwords(strtolower($tm['nombre']))) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Tipo de problema -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipo de problema</label>
                                <select name="id_tipo_problema" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($tiposProblema as $tp): ?>
                                        <option value="<?= $tp['id'] ?>" <?= ($solicitud['id_tipo_problema'] ?? '') == $tp['id'] ? 'selected' : '' ?>><?= esc($tp['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Prioridad -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Prioridad</label>
                                <select name="prioridad" class="form-select" required>
                                    <option value="1" <?= ($solicitud['prioridad'] ?? 2) == 1 ? 'selected' : '' ?>>Baja</option>
                                    <option value="2" <?= ($solicitud['prioridad'] ?? 2) == 2 ? 'selected' : '' ?>>Media</option>
                                    <option value="3" <?= ($solicitud['prioridad'] ?? 2) == 3 ? 'selected' : '' ?>>Alta</option>
                                    <option value="4" <?= ($solicitud['prioridad'] ?? 2) == 4 ? 'selected' : '' ?>>Crítica</option>
                                </select>
                            </div>

                            <!-- Condición de movilidad -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Condición de movilidad</label>
                                <select name="condicion_movilidad" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="OPERATIVO" <?= ($solicitud['condicion_movilidad'] ?? '') === 'OPERATIVO' ? 'selected' : '' ?>>Operativo</option>
                                    <option value="INMOVILIZADO" <?= ($solicitud['condicion_movilidad'] ?? '') === 'INMOVILIZADO' ? 'selected' : '' ?>>Inmovilizado</option>
                                    <option value="ARRASTRE" <?= ($solicitud['condicion_movilidad'] ?? '') === 'ARRASTRE' ? 'selected' : '' ?>>Solo arrastre</option>
                                </select>
                            </div>

                            <!-- Ubicación -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Ubicación actual</label>
                                <input type="text" name="ubicacion" class="form-control" value="<?= esc($solicitud['ubicacion'] ?? '') ?>" placeholder="Ej: Taller central, Ruta 32 km 15">
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="4" required><?= esc($solicitud['descripcion'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= base_url('solicitudes/show/' . $solicitud['id']) ?>" class="btn btn-outline-secondary rounded-pill px-4">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-save me-1"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
