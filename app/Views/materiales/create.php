<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus"></i> <?= $title ?>
        </h1>
        <a href="<?= base_url('materiales') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
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
                    <form method="POST" action="<?= base_url('materiales/store') ?>">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="nombre">Nombre del Material <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   id="nombre" name="nombre" value="<?= old('nombre') ?>" 
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
                                           id="unidad_medida" name="unidad_medida" value="<?= old('unidad_medida') ?>" 
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
                                               id="costo_unitario" name="costo_unitario" value="<?= old('costo_unitario') ?>" 
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
                                <i class="fas fa-save"></i> Guardar Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Panel de ayuda -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Información
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Código Consecutivo</h6>
                    <p class="text-muted small mb-3">
                        El código se generará automáticamente con el formato: <code>MAT-YYYY-NNNNNN</code>
                    </p>

                    <h6 class="text-primary">Unidades de Medida Comunes</h6>
                    <ul class="text-muted small mb-3">
                        <li><strong>UNIDAD:</strong> Para piezas individuales</li>
                        <li><strong>LITRO:</strong> Para líquidos</li>
                        <li><strong>KILOGRAMO:</strong> Para materiales por peso</li>
                        <li><strong>METRO:</strong> Para materiales por longitud</li>
                        <li><strong>JUEGO:</strong> Para conjuntos de piezas</li>
                        <li><strong>CAJA:</strong> Para materiales empaquetados</li>
                    </ul>

                    <h6 class="text-primary">Consejos</h6>
                    <ul class="text-muted small">
                        <li>Use nombres descriptivos y específicos</li>
                        <li>Incluya marca o especificaciones si es relevante</li>
                        <li>Mantenga consistencia en las unidades de medida</li>
                        <li>Actualice los costos regularmente</li>
                    </ul>
                </div>
            </div>

            <!-- Materiales recientes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-clock"></i> Materiales Recientes
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Los materiales creados recientemente aparecerán aquí para referencia.
                    </p>
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
    $('form').on('submit', function(e) {
        let valid = true;
        let errores = [];
        
        // Validar campos requeridos
        $(this).find('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                const label = $('label[for="' + $(this).attr('id') + '"]').text().replace('*', '').trim();
                errores.push(`${label} es requerido`);
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Validar costo unitario
        const costo = parseFloat($('#costo_unitario').val());
        if (isNaN(costo) || costo < 0) {
            $('#costo_unitario').addClass('is-invalid');
            errores.push('El costo unitario debe ser un número válido mayor o igual a 0');
            valid = false;
        }
        
        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Formulario Incompleto',
                html: `<div class="text-start"><ul class="mb-0">${errores.map(error => `<li>${error}</li>`).join('')}</ul></div>`,
                confirmButtonColor: '#f59e0b'
            });
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
});
</script>
<?= $this->endSection() ?>
