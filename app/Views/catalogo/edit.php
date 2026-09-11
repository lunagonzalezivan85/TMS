<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('catalogo') ?>">Catálogo</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('catalogo/show/' . $catalogo['id']) ?>"><?= esc($catalogo['codigo']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-edit me-2"></i><?= $title ?>
                </h1>
                <div class="btn-group">
                    <a href="<?= base_url('catalogo/show/' . $catalogo['id']) ?>" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>Ver Detalle
                    </a>
                    <a href="<?= base_url('catalogo') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Información del Catálogo
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('catalogo/update/' . $catalogo['id']) ?>" method="POST" id="formCatalogo">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">
                                        Código <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?= session()->getFlashdata('errors')['codigo'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="codigo" 
                                           name="codigo" 
                                           value="<?= old('codigo', $catalogo['codigo']) ?>" 
                                           required 
                                           maxlength="50">
                                    <div class="invalid-feedback" id="error_codigo">
                                        <?= session()->getFlashdata('errors')['codigo'] ?? '' ?>
                                    </div>
                                    <div class="form-text">Código único identificador</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">
                                        Estado <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select <?= session()->getFlashdata('errors')['estado'] ?? false ? 'is-invalid' : '' ?>" 
                                            id="estado" 
                                            name="estado" 
                                            required>
                                        <option value="">Seleccionar estado</option>
                                        <option value="1" <?= old('estado', $catalogo['estado']) == '1' ? 'selected' : '' ?>>ACTIVO</option>
                                        <option value="0" <?= old('estado', $catalogo['estado']) == '0' ? 'selected' : '' ?>>INACTIVO</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['estado'] ?? '' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= session()->getFlashdata('errors')['nombre'] ?? false ? 'is-invalid' : '' ?>" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?= old('nombre', $catalogo['nombre']) ?>" 
                                   required 
                                   maxlength="255">
                            <div class="invalid-feedback">
                                <?= session()->getFlashdata('errors')['nombre'] ?? '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control <?= session()->getFlashdata('errors')['descripcion'] ?? false ? 'is-invalid' : '' ?>" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3" 
                                      maxlength="500"><?= old('descripcion', $catalogo['descripcion']) ?></textarea>
                            <div class="invalid-feedback">
                                <?= session()->getFlashdata('errors')['descripcion'] ?? '' ?>
                            </div>
                            <div class="form-text">Descripción opcional del catálogo</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_superior" class="form-label">Catálogo Superior</label>
                                    <select class="form-select <?= session()->getFlashdata('errors')['id_superior'] ?? false ? 'is-invalid' : '' ?>" 
                                            id="id_superior" 
                                            name="id_superior">
                                        <option value="">Sin catálogo superior (Raíz)</option>
                                        <?php foreach ($catalogos_padre as $padre): ?>
                                            <option value="<?= $padre['id'] ?>" 
                                                    <?= old('id_superior', $catalogo['id_superior']) == $padre['id'] ? 'selected' : '' ?>>
                                                <?= str_repeat('└─ ', ($padre['nivel'] ?? 1) - 1) ?>
                                                <?= esc($padre['codigo'] . ' - ' . $padre['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['id_superior'] ?? '' ?>
                                    </div>
                                    <div class="form-text">Selecciona un catálogo padre si es subcatálogo</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="referencia" class="form-label">Referencia</label>
                                    <input type="text" 
                                           class="form-control <?= session()->getFlashdata('errors')['referencia'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="referencia" 
                                           name="referencia" 
                                           value="<?= old('referencia', $catalogo['referencia']) ?>" 
                                           maxlength="100">
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['referencia'] ?? '' ?>
                                    </div>
                                    <div class="form-text">Referencia externa opcional</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="referencia2" class="form-label">Referencia 2</label>
                                    <input type="text" 
                                           class="form-control <?= session()->getFlashdata('errors')['referencia2'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="referencia2" 
                                           name="referencia2" 
                                           value="<?= old('referencia2', $catalogo['referencia2']) ?>" 
                                           maxlength="100">
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['referencia2'] ?? '' ?>
                                    </div>
                                    <div class="form-text">Segunda referencia opcional</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edicion" class="form-label">Edición</label>
                                    <input type="number" 
                                           class="form-control <?= session()->getFlashdata('errors')['edicion'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="edicion" 
                                           name="edicion" 
                                           value="<?= old('edicion', $catalogo['edicion']) ?>" 
                                           min="0">
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['edicion'] ?? '' ?>
                                    </div>
                                    <div class="form-text">Número de edición opcional</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('catalogo/show/' . $catalogo['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnGuardar">
                                <i class="fas fa-save me-2"></i>Actualizar Catálogo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de información -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Catálogo
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td><?= $catalogo['id'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nivel Actual:</strong></td>
                            <td>
                                <span class="badge bg-secondary">Nivel <?= $catalogo['nivel'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Creado por:</strong></td>
                            <td><?= esc($catalogo['usuario_creador'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha creación:</strong></td>
                            <td>
                                <?php if ($catalogo['fecha_registro']): ?>
                                    <?= date('d/m/Y H:i', strtotime($catalogo['fecha_registro'])) ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($catalogo['usuario_actualizador']): ?>
                        <tr>
                            <td><strong>Actualizado por:</strong></td>
                            <td><?= esc($catalogo['usuario_actualizador']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Última actualización:</strong></td>
                            <td>
                                <?php if ($catalogo['fecha_actualiza']): ?>
                                    <?= date('d/m/Y H:i', strtotime($catalogo['fecha_actualiza'])) ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>

                    <hr>

                    <h6><i class="fas fa-lightbulb me-1"></i>Consejos</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>El código debe ser único en el sistema</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>El nivel se recalcula automáticamente</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Los campos marcados con (*) son obligatorios</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const catalogoId = <?= $catalogo['id'] ?>;

    // Verificar código único
    $('#codigo').on('blur', function() {
        const codigo = $(this).val().trim();
        if (codigo) {
            verificarCodigo(codigo);
        }
    });

    // Función para verificar si el código existe
    function verificarCodigo(codigo) {
        $.ajax({
            url: '<?= base_url('catalogo/verificarCodigo') ?>',
            type: 'POST',
            data: { 
                codigo: codigo,
                exclude_id: catalogoId
            },
            dataType: 'json',
            success: function(response) {
                if (response.exists) {
                    $('#codigo').addClass('is-invalid');
                    $('#error_codigo').text('Este código ya existe. Por favor, use uno diferente.');
                } else {
                    $('#codigo').removeClass('is-invalid');
                    $('#error_codigo').text('');
                }
            }
        });
    }

    // Validación del formulario
    $('#formCatalogo').on('submit', function(e) {
        let valid = true;

        // Validar código
        const codigo = $('#codigo').val().trim();
        if (!codigo) {
            $('#codigo').addClass('is-invalid');
            $('#error_codigo').text('El código es requerido');
            valid = false;
        }

        // Validar nombre
        const nombre = $('#nombre').val().trim();
        if (!nombre) {
            $('#nombre').addClass('is-invalid');
            valid = false;
        }

        // Validar estado
        const estado = $('#estado').val();
        if (!estado) {
            $('#estado').addClass('is-invalid');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 300);
        } else {
            // Deshabilitar botón para evitar doble envío
            $('#btnGuardar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...');
        }
    });

    // Limpiar errores al escribir
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
<?= $this->endSection() ?>
