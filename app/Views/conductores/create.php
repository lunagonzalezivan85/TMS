<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-plus text-primary"></i> <?= $page_title ?>
        </h1>
        <a href="<?= base_url('conductores') ?>" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver a Lista
        </a>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit"></i> Información del Conductor
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('conductores/store') ?>" method="POST" id="formConductor">
                        <?= csrf_field() ?>
                        <div class="row">
                            <!-- Información Personal -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user"></i> Información Personal
                                </h5>
                                
                                <div class="form-group mb-3">
                                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= old('nombre') ?>" required minlength="2" maxlength="100">
                                    <div class="invalid-feedback">Por favor ingrese el nombre del conductor (2-100 caracteres)</div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" 
                                           value="<?= old('apellido') ?>" required minlength="2" maxlength="100">
                                    <div class="invalid-feedback">Por favor ingrese el apellido del conductor (2-100 caracteres)</div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="dni" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="dni" name="dni" 
                                           value="<?= old('dni') ?>" required minlength="6" maxlength="20" 
                                           pattern="[a-zA-Z0-9]+" title="Solo se permiten letras y números">
                                    <div class="invalid-feedback">Por favor ingrese un documento válido (6-20 caracteres alfanuméricos)</div>
                                    <small class="form-text text-muted">Se verificará que no exista otro conductor con este documento</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="fechaIngreso" class="form-label">Fecha de Ingreso <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" 
                                           value="<?= old('fechaIngreso', date('Y-m-d')) ?>" required>
                                    <div class="invalid-feedback">Por favor ingrese una fecha de ingreso válida</div>
                                </div>


                            </div>

                            <!-- Información del Sistema -->
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Información del Sistema</h6>
                                    <p class="mb-1"><strong>Código:</strong> Se generará automáticamente</p>
                                    <p class="mb-1"><strong>Estado:</strong> Se creará como ACTIVO</p>
                                    <p class="mb-0"><strong>Empresa:</strong> <?= session()->get('empresa_nombre') ?></p>
                                </div>

                                <!-- Información del Sistema -->
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Información del Sistema</h6>
                                    <p class="mb-1"><strong>Código:</strong> Se generará automáticamente</p>
                                    <p class="mb-1"><strong>Estado:</strong> Se creará como ACTIVO</p>
                                    <p class="mb-0"><strong>Empresa:</strong> <?= session()->get('empresa_nombre') ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('conductores') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                                        <i class="fas fa-save"></i> Registrar Conductor
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formConductor');
    const btnGuardar = document.getElementById('btnGuardar');

    // Validación de formulario
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (form.checkValidity() === false) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // Deshabilitar botón para evitar doble envío
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

            // Enviar formulario
            this.submit();
        });
    }

    // Validar DNI único
    const dniInput = document.getElementById('dni');
    if (dniInput) {
        dniInput.addEventListener('blur', function() {
            const dni = this.value.trim();
            if (dni.length >= 6) {
                verificarDni(dni);
            }
        });
    }

    // Función para verificar DNI único
    function verificarDni(dni) {
        fetch('<?= base_url('conductores/verificarDni') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.content || ''
            },
            body: `dni=${encodeURIComponent(dni)}`
        })
        .then(response => response.json())
        .then(data => {
            const input = document.getElementById('dni');
            const feedback = input.nextElementSibling;
            
            if (data.existe) {
                input.setCustomValidity('DNI ya registrado');
                input.classList.add('is-invalid');
                feedback.textContent = 'Ya existe un conductor con este DNI';
            } else {
                input.setCustomValidity('');
                input.classList.remove('is-invalid');
                feedback.textContent = '';
            }
        })
        .catch(error => {
            console.error('Error al verificar DNI:', error);
        });
    }
});
</script>
<?= $this->endSection() ?>
