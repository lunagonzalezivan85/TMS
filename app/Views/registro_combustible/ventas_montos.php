<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-dollar-sign me-2"></i><?= $page_title ?>
            </h1>
            <p class="mb-0 text-muted">Actualiza los montos registrados para la venta seleccionada.</p>
        </div>
        <a href="<?= base_url('registro-combustible/ventas') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver a ventas
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-receipt me-2"></i>Información de la venta
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Fecha</span>
                            <strong><?= date('d/m/Y', strtotime($registro['fecha_registro'])) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Cliente</span>
                            <strong><?= esc($registro['nombreCliente'] ?? 'N/A') ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">DNI</span>
                            <strong><?= esc($registro['dni'] ?? '—') ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Galones vendidos</span>
                            <strong><?= number_format((float) ($registro['cantidad_litros'] ?? 0), 2) ?> gal</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-pen me-2"></i>Actualizar montos
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= base_url('registro-combustible/ventas/' . $registro['id'] . '/montos') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="monto_nio" class="form-label">
                                <i class="fas fa-money-bill me-1"></i>Monto en córdobas (C$)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">C$</span>
                                <input type="number" step="0.01" min="0" id="monto_nio" name="monto_nio" class="form-control"
                                       value="<?= esc(old('monto_nio') ?? ($registro['monto_nio'] ?? 0)) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="monto_usd" class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Monto en dólares ($)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" id="monto_usd" name="monto_usd" class="form-control"
                                       value="<?= esc(old('monto_usd') ?? ($registro['monto_usd'] ?? 0)) ?>" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('registro-combustible/ventas') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
