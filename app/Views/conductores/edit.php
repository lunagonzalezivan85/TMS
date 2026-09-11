<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
     <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body py-3">
                    <h3 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit"></i> Datos del Conductor
                        
                        <a href="<?= base_url('conductores') ?>" class="btn btn-secondary shadow-sm float-end">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i><span class="d-none d-lg-inline"> Volver a Lista</span>
                        </a>
                    </h3>
                </div>
            </div>
        </div>
     </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-edit text-primary"></i> <?= $page_title ?>
        </h1>
        <div>
            
        </div>
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
                        <i class="fas fa-user-edit"></i> Editar Información del Conductor
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('conductores/update/' . $conductor['id']) ?>" method="POST" id="formConductor">
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
                                           value="<?= old('nombre', $conductor['nombre']) ?>" required minlength="2" maxlength="100">
                                    <div class="invalid-feedback">Por favor ingrese el nombre del conductor (2-100 caracteres)</div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" 
                                           value="<?= old('apellido', $conductor['apellido']) ?>" required minlength="2" maxlength="100">
                                    <div class="invalid-feedback">Por favor ingrese el apellido del conductor (2-100 caracteres)</div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="dni" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="dni" name="dni" 
                                           value="<?= old('dni', $conductor['dni']) ?>" required minlength="6" maxlength="20" 
                                           pattern="[a-zA-Z0-9]+" title="Solo se permiten letras y números">
                                    <div class="invalid-feedback">Por favor ingrese un documento válido (6-20 caracteres alfanuméricos)</div>
                                    <small class="form-text text-muted">Se verificará que no exista otro conductor con este documento</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="fechaIngreso" class="form-label">Fecha de Ingreso <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" 
                                           value="<?= old('fechaIngreso', $conductor['fechaIngreso'] ?? date('Y-m-d')) ?>" required>
                                    <div class="invalid-feedback">Por favor ingrese una fecha de ingreso válida</div>
                                </div>
                            </div>

                            <!-- Información de Licencia -->
                            <div class="col-md-6">


                                <!-- Información del Sistema -->
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Información del Sistema</h6>
                                    <p class="mb-1"><strong>Código:</strong> <?= $conductor['codigo_consecutivo'] ?></p>
                                    <p class="mb-1"><strong>Estado Actual:</strong> 
                                        <span class="badge <?= $conductor['estado'] == 'ACTIVO' ? 'badge-success' : 'badge-secondary' ?>">
                                            <?= $conductor['estado'] ?>
                                        </span>
                                    </p>
                                    <p class="mb-0"><strong>Empresa:</strong> <?= session()->get('empresa_nombre') ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="<?= base_url('conductores/show/' . $conductor['id']) ?>" class="btn btn-info me-2">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                        <a href="<?= base_url('conductores') ?>" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                                        <i class="fas fa-save"></i> Actualizar Conductor
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
    const conductorId = <?= $conductor['id'] ?>;

    // Contador de caracteres para observaciones
    const observaciones = document.getElementById('observaciones');
    const contador = document.getElementById('contador-observaciones');
    
    observaciones.addEventListener('input', function() {
        contador.textContent = this.value.length;
    });

    // Validación en tiempo real del documento
    const documentoInput = document.getElementById('documento_identidad');
    let timeoutDocumento;

    documentoInput.addEventListener('input', function() {
        clearTimeout(timeoutDocumento);
        const documento = this.value.trim();
        
        if (documento.length >= 6) {
            timeoutDocumento = setTimeout(() => {
                verificarDocumento(documento, conductorId);
            }, 500);
        }
    });

    // Validación en tiempo real de la licencia
    const licenciaInput = document.getElementById('numero_licencia');
    let timeoutLicencia;

    licenciaInput.addEventListener('input', function() {
        clearTimeout(timeoutLicencia);
        const licencia = this.value.trim();
        
        if (licencia.length >= 6) {
            timeoutLicencia = setTimeout(() => {
                verificarLicencia(licencia, conductorId);
            }, 500);
        }
    });

    // Validación de fecha de nacimiento
    const fechaNacimiento = document.getElementById('fecha_nacimiento');
    fechaNacimiento.addEventListener('change', function() {
        validarEdad(this.value);
    });

    // Validación de fecha de vencimiento de licencia
    const fechaVencimiento = document.getElementById('fecha_vencimiento_licencia');
    fechaVencimiento.addEventListener('change', function() {
        validarFechaVencimiento(this.value);
    });

    // Función para verificar documento
    function verificarDocumento(documento, excludeId) {
        fetch('<?= base_url('conductores/verificarDocumento') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `documento=${encodeURIComponent(documento)}&exclude_id=${excludeId}`
        })
        .then(response => response.json())
        .then(data => {
            const input = document.getElementById('documento_identidad');
            const feedback = input.nextElementSibling;
            
            if (data.existe) {
                input.classList.add('is-invalid');
                feedback.textContent = 'Ya existe otro conductor con este documento de identidad';
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                feedback.textContent = '';
            }
        })
        .catch(error => {
            console.error('Error al verificar documento:', error);
        });
    }

    // Función para verificar licencia
    function verificarLicencia(licencia, excludeId) {
        fetch('<?= base_url('conductores/verificarLicencia') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `licencia=${encodeURIComponent(licencia)}&exclude_id=${excludeId}`
        })
        .then(response => response.json())
        .then(data => {
            const input = document.getElementById('numero_licencia');
            const feedback = input.nextElementSibling;
            
            if (data.existe) {
                input.classList.add('is-invalid');
                feedback.textContent = 'Ya existe otro conductor con este número de licencia';
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                feedback.textContent = '';
            }
        })
        .catch(error => {
            console.error('Error al verificar licencia:', error);
        });
    }

    // Función para validar edad
    function validarEdad(fechaNacimiento) {
        const input = document.getElementById('fecha_nacimiento');
        const feedback = input.nextElementSibling;
        
        if (fechaNacimiento) {
            const hoy = new Date();
            const nacimiento = new Date(fechaNacimiento);
            const edad = hoy.getFullYear() - nacimiento.getFullYear();
            const mesActual = hoy.getMonth();
            const mesNacimiento = nacimiento.getMonth();
            
            let edadReal = edad;
            if (mesActual < mesNacimiento || (mesActual === mesNacimiento && hoy.getDate() < nacimiento.getDate())) {
                edadReal--;
            }
            
            if (edadReal < 18) {
                input.classList.add('is-invalid');
                feedback.textContent = 'El conductor debe ser mayor de 18 años';
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                feedback.textContent = '';
            }
        }
    }

    // Función para validar fecha de vencimiento
    function validarFechaVencimiento(fechaVencimiento) {
        const input = document.getElementById('fecha_vencimiento_licencia');
        const feedback = input.nextElementSibling;
        
        if (fechaVencimiento) {
            const hoy = new Date();
            const vencimiento = new Date(fechaVencimiento);
            
            if (vencimiento < hoy) {
                input.classList.add('is-invalid');
                feedback.textContent = 'La fecha de vencimiento no puede ser anterior a hoy';
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                feedback.textContent = '';
            }
        }
    }

    // Validación del formulario antes del envío
    form.addEventListener('submit', function(e) {
        let valido = true;
        const campos = form.querySelectorAll('input[required], select[required]');
        
        campos.forEach(campo => {
            if (!campo.value.trim()) {
                campo.classList.add('is-invalid');
                valido = false;
            }
        });

        // Verificar si hay errores de validación
        const camposInvalidos = form.querySelectorAll('.is-invalid');
        if (camposInvalidos.length > 0) {
            valido = false;
        }

        if (!valido) {
            e.preventDefault();
            mostrarAlerta('danger', 'Por favor, corrija los errores en el formulario');
        } else {
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
        }
    });

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        const alertaHtml = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.container-fluid');
        const header = container.querySelector('.d-sm-flex');
        header.insertAdjacentHTML('afterend', alertaHtml);
        
        // Scroll hacia arriba
        window.scrollTo(0, 0);
    }

    // Inicializar contador
    contador.textContent = observaciones.value.length;

    // Validar campos iniciales si tienen valores
    if (documentoInput.value.trim().length >= 6) {
        verificarDocumento(documentoInput.value.trim(), conductorId);
    }
    
    if (licenciaInput.value.trim().length >= 6) {
        verificarLicencia(licenciaInput.value.trim(), conductorId);
    }
    
    if (fechaNacimiento.value) {
        validarEdad(fechaNacimiento.value);
    }
    
    if (fechaVencimiento.value) {
        validarFechaVencimiento(fechaVencimiento.value);
    }
});
</script>
<?= $this->endSection() ?>
