<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
    @media print {
        body { background: white; }
        .no-print { display: none !important; }
        .container-fluid { padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                <h4 class="fw-bold mb-0"><i class="fas fa-file-alt me-2 text-success"></i><?= $title ?></h4>
                <div>
                    <a href="<?= base_url('solicitudes/show/' . $solicitud['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill me-2">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <button onclick="window.print()" class="btn btn-sm btn-success rounded-pill">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold mb-1"><?= esc($empresa['nombre'] ?? 'Transportes GMV S.A.C.') ?></h5>
                        <p class="text-muted mb-0 small">Reporte de Solicitud de Mantenimiento</p>
                        <h4 class="fw-bold mt-2 text-success"><?= esc($solicitud['codigo_consecutivo']) ?></h4>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <small class="text-muted">Fecha de solicitud</small>
                            <p class="fw-semibold mb-0"><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Solicitante</small>
                            <p class="fw-semibold mb-0"><?= esc($solicitud['solicitante']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Estado</small>
                            <p class="fw-semibold mb-0"><?= esc($solicitud['estado']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Prioridad</small>
                            <?php
                            $prioridades = [1 => 'Baja', 2 => 'Media', 3 => 'Alta', 4 => 'Crítica'];
                            $p = (int)($solicitud['prioridad'] ?? 2);
                            ?>
                            <p class="fw-semibold mb-0"><?= $prioridades[$p] ?? 'Media' ?></p>
                        </div>
                    </div>

                    <h6 class="fw-bold border-bottom pb-2 mb-3">Información del Vehículo</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <small class="text-muted">Placa</small>
                            <p class="fw-semibold mb-0"><?= esc($vehiculo['placa'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Marca / Modelo</small>
                            <p class="fw-semibold mb-0"><?= esc(($vehiculo['marca'] ?? '') . ' ' . ($vehiculo['modelo'] ?? '')) ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Año</small>
                            <p class="fw-semibold mb-0"><?= esc($vehiculo['anio'] ?? 'N/A') ?></p>
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

                    <h6 class="fw-bold border-bottom pb-2 mb-3">Detalle de la Avería</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <small class="text-muted">Tipo de mantenimiento</small>
                            <p class="fw-semibold mb-0"><?= esc($solicitud['tipo_mantenimiento'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tipo de problema</small>
                            <p class="fw-semibold mb-0"><?= esc($tipoProblema['nombre'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Condición de movilidad</small>
                            <p class="fw-semibold mb-0"><?= esc($solicitud['condicion_movilidad'] ?? 'No especificada') ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Ubicación</small>
                            <p class="fw-semibold mb-0"><?= esc($solicitud['ubicacion'] ?? 'No especificada') ?></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-2">Descripción</h6>
                        <p class="mb-0"><?= nl2br(esc($solicitud['descripcion'])) ?></p>
                    </div>

                    <?php if (!empty($sugerencia)): ?>
                    <div class="mb-4 p-3 rounded-3" style="background:#eff6ff;border:1px solid #bfdbfe;">
                        <h6 class="fw-bold text-primary mb-2" style="font-size:0.9rem;"><i class="fas fa-lightbulb me-2"></i>Sugerencia de acción</h6>
                        <p class="mb-0 small"><?= esc($sugerencia) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($tecnico)): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-2">Técnico asignado</h6>
                        <p class="mb-0 fw-semibold"><?= esc(($tecnico['nombre'] ?? '') . ' ' . ($tecnico['apellido'] ?? '')) ?> (<?= esc($tecnico['usuario'] ?? '') ?>)</p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($solicitud['url_foto'])): ?>
                    <div class="mb-4 text-center">
                        <h6 class="fw-bold border-bottom pb-2 mb-3 text-start">Evidencia adjunta</h6>
                        <img src="<?= base_url('public/' . $solicitud['url_foto']) ?>" class="img-fluid rounded-4" style="max-height:400px;" alt="Evidencia">
                    </div>
                    <?php endif; ?>

                    <div class="row mt-5 pt-4 border-top">
                        <div class="col-6 text-center">
                            <p class="mb-5" style="border-bottom:1px solid #000;display:inline-block;width:80%;"></p>
                            <p class="fw-semibold small mb-0">Firma del supervisor</p>
                        </div>
                        <div class="col-6 text-center">
                            <p class="mb-5" style="border-bottom:1px solid #000;display:inline-block;width:80%;"></p>
                            <p class="fw-semibold small mb-0">Firma del técnico</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
