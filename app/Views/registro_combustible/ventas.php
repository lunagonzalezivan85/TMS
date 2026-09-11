<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-money-bill-wave me-2"></i><?= $page_title ?>
            </h1>
            <p class="mb-0 text-muted">Control y seguimiento de ventas de combustible</p>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('registro-combustible/create-venta') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva venta
            </a>
            <a href="<?= base_url('registro-combustible') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver a registros
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>Filtros
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha_desde" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha_desde" name="fecha_desde" class="form-control" value="<?= esc($filtros['fecha_desde'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label for="fecha_hasta" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control" value="<?= esc($filtros['fecha_hasta'] ?? '') ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>Listado de ventas
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($registros)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron ventas</h5>
                    <p class="text-muted mb-0">Registra una nueva venta para verla en este listado.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>DNI</th>
                                <th>Galones</th>
                                <th>Tipo</th>
                                <th>Monto (C$)</th>
                                <th>Monto ($)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros as $registro): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($registro['fecha_registro'])) ?></td>
                                    <td>
                                        <strong><?= esc($registro['nombreCliente'] ?? 'N/A') ?></strong>
                                    </td>
                                    <td><?= esc($registro['dni'] ?? '—') ?></td>
                                    <td>
                                        <span class="badge bg-info"><?= number_format((float) ($registro['cantidad_litros'] ?? 0), 2) ?> gal</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= esc(strtoupper($registro['tipo'] ?? 'VENTA')) ?></span>
                                    </td>
                                    <td>
                                        <?php $montoNio = isset($registro['monto_nio']) ? (float) $registro['monto_nio'] : 0; ?>
                                        <span class="badge bg-success">C$ <?= number_format($montoNio, 2) ?></span>
                                    </td>
                                    <td>
                                        <?php $montoUsd = isset($registro['monto_usd']) ? (float) $registro['monto_usd'] : 0; ?>
                                        <span class="badge bg-secondary">$ <?= number_format($montoUsd, 2) ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('registro-combustible/show/' . $registro['id']) ?>" class="btn btn-outline-info" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('registro-combustible/ventas/' . $registro['id'] . '/montos') ?>" class="btn btn-outline-success" title="Actualizar montos">
                                                <i class="fas fa-dollar-sign"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
