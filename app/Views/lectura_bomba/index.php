<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 600;
}
.status-badge.open {
    background: #fef3c7;
    color: #92400e;
}
.status-badge.closed {
    background: #d1fae5;
    color: #065f46;
}
.status-badge.anomaly {
    background: #fee2e2;
    color: #991b1b;
}
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-0"><i class="fas fa-gas-pump me-2 text-success"></i>Lecturas de Bomba</h1>
            <p class="text-muted small mb-0">
                <?= $es_admin ? 'Todas las aperturas y cierres' : 'Mis aperturas y cierres' ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('lectura-bomba/monitor') ?>" class="btn btn-info btn-sm">
                <i class="fas fa-tv me-1"></i>Monitor
            </a>
            <a href="<?= base_url('lectura-bomba/apertura') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Nueva Apertura
            </a>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('info')): ?>
    <div class="alert alert-info alert-dismissible fade show">
        <i class="fas fa-info-circle me-2"></i><?= session()->getFlashdata('info') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="<?= base_url('lectura-bomba') ?>">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="centro_costo" class="form-label">Centro de Costo</label>
                        <select class="form-select" id="centro_costo" name="centro_costo">
                            <option value="">Todos</option>
                            <?php foreach ($catalogo_opciones as $id => $nombre): ?>
                            <option value="<?= esc($id) ?>" <?= $filtro_centro == $id ? 'selected' : '' ?>>
                                <?= esc($nombre) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filtrar
                        </button>
                    </div>
                    <?php if ($filtro_centro): ?>
                    <div class="col-md-2">
                        <a href="<?= base_url('lectura-bomba') ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>Limpiar
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista única -->
    <?php if (!empty($historial)): ?>
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Registros (<?= count($historial) ?>)</h6>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Centro</th>
                            <th>Apertura</th>
                            <th>Cierre</th>
                            <th>Lectura Inicial</th>
                            <th>Litraje Inicial</th>
                            <th>Lectura Final</th>
                            <th>Litraje Final</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $reg): ?>
                        <tr>
                            <td class="text-truncate" style="max-width: 120px;" title="<?= esc($reg['nombre_centro_costo'] ?? $reg['id_centro_costo']) ?>">
                                <span class="fw-bold text-primary"><?= esc($reg['nombre_centro_costo'] ?? $reg['id_centro_costo']) ?></span>
                            </td>
                            <td>
                                <div><?= date('d/m/Y', strtotime($reg['fecha_apertura'])) ?></div>
                                <small class="text-muted"><?= date('H:i', strtotime($reg['fecha_apertura'])) ?></small>
                            </td>
                            <td>
                                <?php if ($reg['fecha_cierre']): ?>
                                    <div><?= date('d/m/Y', strtotime($reg['fecha_cierre'])) ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($reg['fecha_cierre'])) ?></small>
                                <?php else: ?>
                                    <span class="status-badge open">
                                        <i class="fas fa-unlock"></i>Pendiente
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?= number_format($reg['lectura_inicial_litros'], 2) ?> L</div>
                                <small class="text-muted"><?= number_format($reg['lectura_inicial_galones'], 2) ?> gal</small>
                            </td>
                            <td>
                                <?php if ($reg['litraje_inicial_ltr']): ?>
                                    <div class="fw-bold"><?= number_format($reg['litraje_inicial_ltr'], 4) ?> L</div>
                                    <small class="text-muted"><?= number_format($reg['litraje_inicial_gal'] ?? 0, 4) ?> gal</small>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($reg['lectura_final_litros']): ?>
                                    <div class="fw-bold"><?= number_format($reg['lectura_final_litros'], 2) ?> L</div>
                                    <small class="text-muted"><?= number_format($reg['lectura_final_galones'], 2) ?> gal</small>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($reg['litraje_final_ltr']): ?>
                                    <div class="fw-bold"><?= number_format($reg['litraje_final_ltr'], 4) ?> L</div>
                                    <small class="text-muted"><?= number_format($reg['litraje_final_gal'] ?? 0, 4) ?> gal</small>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($reg['estado'] === 'anomalia'): ?>
                                    <span class="status-badge anomaly">
                                        <i class="fas fa-exclamation-triangle"></i>Anomalía
                                    </span>
                                <?php else: ?>
                                    <?php if ($reg['fecha_cierre']): ?>
                                        <span class="status-badge closed">
                                            <i class="fas fa-check"></i>Cerrado
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge open">
                                            <i class="fas fa-unlock"></i>Abierto
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$reg['fecha_cierre']): ?>
                                    <a href="<?= base_url('lectura-bomba/cierre/' . $reg['id']) ?>" class="btn btn-danger btn-sm" title="Cerrar">
                                        <i class="fas fa-power-off"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('lectura-bomba/detalle/' . $reg['id']) ?>" class="btn btn-outline-primary btn-sm" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('registro-combustible?lectura_id=' . $reg['id']) ?>" class="btn btn-success btn-sm" title="Ver registros de combustible">
                                    <i class="fas fa-gas-pump"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php elseif ($filtro_centro): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No hay registros</h5>
            <p class="text-muted">No se encontraron registros para el centro de costo seleccionado.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Sin registros</h5>
            <p class="text-muted">
                <?= $es_admin ? 'No hay registros de lecturas de bomba.' : 'No tienes registros de lecturas de bomba.' ?>
            </p>
            <a href="<?= base_url('lectura-bomba/apertura') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Crear Primera Apertura
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
