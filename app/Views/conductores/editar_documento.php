<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $page_title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('conductores/documentos/' . $documento['IdConductor']) ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <form action="<?= base_url('conductores/actualizarDocumento/' . $documento['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_documento">Tipo de Documento</label>
                                    <input type="text" class="form-control" id="tipo_documento" value="<?= esc($documento['tipo_documento']) ?>" readonly>
                                    <small class="form-text text-muted">El tipo de documento no se puede modificar</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero_documento">Número de Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="numero_documento" name="numero_documento" 
                                           value="<?= old('numero_documento', $documento['numero_documento']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_emision">Fecha de Emisión</label>
                                    <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" 
                                           value="<?= old('fecha_emision', $documento['fecha_emision']) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" 
                                           value="<?= old('fecha_vencimiento', $documento['fecha_vencimiento']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= old('observaciones', $documento['observaciones']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="archivo">Archivo Actual</label>
                                    <?php if ($documento['ruta_documento']): ?>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-success mr-2">Archivo cargado</span>
                                            <div class="btn-group">
                                                <a href="<?= base_url('conductores/verDocumento/' . $documento['id']) ?>" 
                                                   class="btn btn-info btn-sm" target="_blank">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="<?= base_url('conductores/descargarDocumento/' . $documento['id']) ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-download"></i> Descargar
                                                </a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Sin archivo</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="archivo">Nuevo Archivo (opcional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="archivo" name="archivo" 
                                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <label class="custom-file-label" for="archivo">Seleccionar archivo...</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Formatos permitidos: PDF, JPG, PNG, DOC, DOCX. Máximo 10MB.
                                        <br><strong>Si selecciona un nuevo archivo, reemplazará el actual.</strong>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado Actual</label>
                                    <div>
                                        <?php
                                        $badgeClass = 'secondary';
                                        switch ($documento['estado']) {
                                            case 'VIGENTE':
                                                $badgeClass = 'success';
                                                break;
                                            case 'POR_VENCER':
                                                $badgeClass = 'warning';
                                                break;
                                            case 'VENCIDO':
                                                $badgeClass = 'danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge badge-<?= $badgeClass ?>"><?= esc($documento['estado']) ?></span>
                                        <small class="form-text text-muted">El estado se actualiza automáticamente según la fecha de vencimiento</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Información de Registro</label>
                                    <div>
                                        <small class="text-muted">
                                            Registrado: <?= date('d/m/Y H:i', strtotime($documento['fechaRegistro'])) ?><br>
                                            <?php if ($documento['fechaUpdate']): ?>
                                                Última actualización: <?= date('d/m/Y H:i', strtotime($documento['fechaUpdate'])) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Documento
                        </button>
                        <a href="<?= base_url('conductores/documentos/' . $documento['IdConductor']) ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Actualizar el nombre del archivo seleccionado
    $('#archivo').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Seleccionar archivo...');
    });
    
    // Validación del formulario
    $('form').on('submit', function(e) {
        var numeroDocumento = $('#numero_documento').val().trim();
        
        if (!numeroDocumento) {
            e.preventDefault();
            alert('El número de documento es obligatorio');
            $('#numero_documento').focus();
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>
