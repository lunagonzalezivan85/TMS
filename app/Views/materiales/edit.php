<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit"></i> <?= $title ?>
        </h1>
        <div>
            <a href="<?= base_url('materiales/show/' . $material['id']) ?>" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> Ver Detalles
            </a>
            <a href="<?= base_url('materiales') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Formulario principal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box"></i> Información del Material
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= base_url('materiales/update/' . $material['id']) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">
                        
                        <div class="form-group">
                            <label for="nombre">Nombre del Material <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   id="nombre" name="nombre" value="<?= old('nombre', $material['nombre']) ?>" 
                                   placeholder="Ej: Aceite Motor 5W-30, Filtro de Aceite, etc." required>
                            <?php if (isset($errors['nombre'])): ?>
                                <div class="invalid-feedback"><?= $errors['nombre'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unidad_medida">Unidad de Medida <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= isset($errors['unidad_medida']) ? 'is-invalid' : '' ?>" 
                                           id="unidad_medida" name="unidad_medida" value="<?= old('unidad_medida', $material['unidad_medida']) ?>" 
                                           placeholder="Ej: LITRO, UNIDAD, METRO, etc." required
                                           list="unidades_comunes">
                                    <datalist id="unidades_comunes">
                                        <option value="UNIDAD">
                                        <option value="LITRO">
                                        <option value="METRO">
                                        <option value="KILOGRAMO">
                                        <option value="JUEGO">
                                        <option value="CAJA">
                                        <option value="GALON">
                                        <option value="PIEZA">
                                        <?php if (!empty($unidadesMedida)): ?>
                                            <?php foreach ($unidadesMedida as $unidad): ?>
                                                <option value="<?= esc($unidad) ?>">
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </datalist>
                                    <?php if (isset($errors['unidad_medida'])): ?>
                                        <div class="invalid-feedback"><?= $errors['unidad_medida'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo_unitario">Costo Unitario <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" class="form-control <?= isset($errors['costo_unitario']) ? 'is-invalid' : '' ?>" 
                                               id="costo_unitario" name="costo_unitario" value="<?= old('costo_unitario', $material['costo_unitario']) ?>" 
                                               min="0" step="0.01" placeholder="0.00" required>
                                        <?php if (isset($errors['costo_unitario'])): ?>
                                            <div class="invalid-feedback"><?= $errors['costo_unitario'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Información del material -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Información Actual
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Código Consecutivo</h6>
                    <p class="text-muted mb-3">
                        <code><?= esc($material['codigo_consecutivo']) ?></code>
                    </p>

                    <h6 class="text-primary">Fecha de Registro</h6>
                    <p class="text-muted mb-3">
                        <?= date('d/m/Y H:i', strtotime($material['fechaRegistro'])) ?>
                    </p>

                    <h6 class="text-primary">Creado Por</h6>
                    <p class="text-muted mb-3">
                        <?= esc($material['nombre_creador'] ?? 'N/A') ?>
                    </p>

                    <?php if (!empty($material['fechaUpdate'])): ?>
                        <h6 class="text-primary">Última Actualización</h6>
                        <p class="text-muted mb-3">
                            <?= date('d/m/Y H:i', strtotime($material['fechaUpdate'])) ?>
                            <?php if (!empty($material['nombre_editor'])): ?>
                                <br><small>por <?= esc($material['nombre_editor']) ?></small>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Panel de ayuda -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Advertencias
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <strong>Cambio de Costo:</strong> Al actualizar el costo unitario, esto afectará los cálculos de futuros trabajos, pero no modificará los registros históricos.
                    </p>
                    
                    <p class="text-muted small mb-3">
                        <strong>Unidad de Medida:</strong> Cambiar la unidad de medida puede causar confusión en registros existentes. Considere crear un nuevo material si el cambio es significativo.
                    </p>

                    <p class="text-muted small">
                        <strong>Nombre del Material:</strong> Use nombres descriptivos y mantenga consistencia con materiales similares.
                    </p>
                </div>
            </div>

            <!-- Historial de uso -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-history"></i> Uso del Material
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Para ver el historial de uso de este material en trabajos de mantenimiento, visite la sección de detalles.
                    </p>
                    <a href="<?= base_url('materiales/show/' . $material['id']) ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Ver Detalles
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Mostrar mensaje de éxito si existe
    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '<?= session()->getFlashdata('success') ?>',
            confirmButtonColor: '#10b981',
            timer: 3000,
            timerProgressBar: true
        });
    <?php endif; ?>
    
    // Mostrar mensaje de error si existe
    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#ef4444'
        });
    <?php endif; ?>
    
    // Validación en tiempo real
            $(this).closest('.input-group').siblings('.invalid-feedback').remove();
        }
    });

    // Formatear precio mientras se escribe
    $('#costo_unitario').on('input', function() {
        let valor = $(this).val();
        if (valor && !isNaN(valor)) {
            // Limitar a 2 decimales
            if (valor.includes('.')) {
                const partes = valor.split('.');
                if (partes[1] && partes[1].length > 2) {
                    $(this).val(partes[0] + '.' + partes[1].substring(0, 2));
                }
            }
        }
    });

    // Confirmación antes de guardar cambios importantes
    $('form').on('submit', function(e) {
        const costoOriginal = <?= $material['costo_unitario'] ?>;
        const costoNuevo = parseFloat($('#costo_unitario').val());
        
        if (Math.abs(costoNuevo - costoOriginal) > (costoOriginal * 0.5)) {
            if (!confirm('El costo ha cambiado significativamente. ¿Está seguro de continuar?')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
