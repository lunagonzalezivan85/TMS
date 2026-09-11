<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
    $registro = $registro ?? [];
    $registro['tipo'] = 'VENTA';
    $registro['id_vehiculo'] = $registro['id_vehiculo'] ?? 1;
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-gas-pump me-2"></i><?= $page_title ?>
            </h1>
            <p class="mb-0 text-muted">
                <?= isset($registro['id']) ? 'Modificar información de la venta' : 'Registrar venta de combustible' ?>
            </p>
        </div>
        <a href="<?= base_url('registro-combustible') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas a-exclamation-circle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Información de la Venta
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= isset($registro['id']) ? base_url('registro-combustible/update/' . $registro['id']) : base_url('registro-combustible/store') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="tipo" value="VENTA">
                        <input type="hidden" name="id_vehiculo" value="<?= esc(old('id_vehiculo') ?? ($registro['id_vehiculo'] ?? 1)) ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_registro" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Fecha de Venta <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_registro" id="fecha_registro" class="form-control" 
                                       value="<?= old('fecha_registro') ?? $registro['fecha_registro'] ?? date('Y-m-d') ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nombreCliente" class="form-label">
                                    <i class="fas fa-user me-1"></i>Nombre del Cliente <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombreCliente" id="nombreCliente" class="form-control"
                                       value="<?= esc(old('nombreCliente') ?? ($registro['nombreCliente'] ?? '')) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dni" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>DNI <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="dni" id="dni" class="form-control"
                                       value="<?= esc(old('dni') ?? ($registro['dni'] ?? '')) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cantidad_litros" class="form-label">
                                    <i class="fas fa-gas-pump me-1"></i>Galones vendidos <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="cantidad_litros" id="cantidad_litros"
                                           class="form-control" step="any" min="0.01"
                                           value="<?= old('cantidad_litros') ?? $registro['cantidad_litros'] ?? '' ?>" required>
                                    <span class="input-group-text">gal</span>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="observaciones" class="form-label">
                                    <i class="fas fa-comment me-1"></i>Observaciones
                                </label>
                                <textarea name="observaciones" id="observaciones" class="form-control" rows="3"
                                          placeholder="Observaciones adicionales (opcional)"><?= old('observaciones') ?? $registro['observaciones'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('registro-combustible') ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                <?= isset($registro['id']) ? 'Actualizar' : 'Guardar venta' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Resumen</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Cliente:</strong> <span id="cliente-info">—</span></p>
                    <p class="mb-1"><strong>DNI:</strong> <span id="dni-info">—</span></p>
                    <p class="mb-1"><strong>Galones:</strong> <span id="litros-info">0.00</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function actualizarResumenVenta() {
    const cliente = document.getElementById('nombreCliente').value || '—';
    const dni = document.getElementById('dni').value || '—';
    const galones = parseFloat(document.getElementById('cantidad_litros').value) || 0;

    document.getElementById('cliente-info').textContent = cliente;
    document.getElementById('dni-info').textContent = dni;
    document.getElementById('litros-info').textContent = galones.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    actualizarResumenVenta();

    ['nombreCliente', 'dni', 'cantidad_litros'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', actualizarResumenVenta);
        }
    });
});
</script>

<?= $this->endSection() ?>
