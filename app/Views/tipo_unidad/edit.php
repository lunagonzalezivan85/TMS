<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit text-warning"></i>
                        <?= $page_title ?>
                    </h1>
                    <p class="text-muted mb-0">Modifica la información del tipo de unidad</p>
                </div>
                <div>
                    <a href="<?= base_url('tipo-unidad') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit"></i> Información del Tipo de Unidad
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form-editar-tipo" novalidate>
                        <input type="hidden" id="tipo-id" value="<?= $tipoUnidad['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">
                                        <i class="fas fa-tag text-primary"></i>
                                        Descripción <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="descripcion" 
                                           name="descripcion" 
                                           value="<?= esc($tipoUnidad['descripcion']) ?>"
                                           placeholder="Ej: Kilogramo, Litro, Metro, etc."
                                           maxlength="255"
                                           required>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Ingrese una descripción clara y única para el tipo de unidad
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">
                                        <i class="fas fa-toggle-on text-success"></i>
                                        Estado <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="">Seleccionar estado</option>
                                        <option value="ACTIVO" <?= $tipoUnidad['estado'] === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                                        <option value="INACTIVO" <?= $tipoUnidad['estado'] === 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de auditoría -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-info-circle text-info"></i>
                                            Información de Auditoría
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small class="text-muted">ID:</small><br>
                                                <strong><?= $tipoUnidad['id'] ?></strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Fecha Registro:</small><br>
                                                <strong><?= date('d/m/Y H:i', strtotime($tipoUnidad['fechaRegistra'])) ?></strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Fecha Actualización:</small><br>
                                                <strong><?= date('d/m/Y H:i', strtotime($tipoUnidad['fechaActualiza'])) ?></strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Estado Actual:</small><br>
                                                <span class="badge <?= $tipoUnidad['estado'] === 'ACTIVO' ? 'bg-success' : 'bg-secondary' ?>">
                                                    <?= $tipoUnidad['estado'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Importante:</h6>
                                    <ul class="mb-0">
                                        <li>Los cambios se aplicarán inmediatamente en todo el sistema</li>
                                        <li>Si cambia a "Inactivo", el tipo de unidad no estará disponible para nuevos registros</li>
                                        <li>Asegúrese de que la descripción sea única y descriptiva</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('tipo-unidad') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-warning" id="btn-actualizar">
                                        <i class="fas fa-save"></i> Actualizar Tipo de Unidad
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-editar-tipo');
    const btnActualizar = document.getElementById('btn-actualizar');
    const descripcionInput = document.getElementById('descripcion');
    const tipoId = document.getElementById('tipo-id').value;
    
    // Validación en tiempo real para descripción
    if (descripcionInput) {
        let timeoutId;
        let descripcionOriginal = descripcionInput.value.trim();
        
        descripcionInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const descripcion = this.value.trim();
            
            // Solo verificar si cambió de la original
            if (descripcion.length >= 3 && descripcion !== descripcionOriginal) {
                timeoutId = setTimeout(() => {
                    verificarDescripcion(descripcion);
                }, 500);
            } else if (descripcion === descripcionOriginal) {
                mostrarExito(this, 'Descripción actual');
            } else {
                limpiarValidacion(this);
            }
        });
    }

    // Envío del formulario
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validarFormulario()) {
                enviarFormulario();
            }
        });
    }

    // Función para verificar descripción única
    function verificarDescripcion(descripcion) {
        fetch('<?= base_url('tipo-unidad/verificarDescripcion') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `descripcion=${encodeURIComponent(descripcion)}&exclude_id=${tipoId}`
        })
        .then(response => response.json())
        .then(data => {
            const input = document.getElementById('descripcion');
            if (data.exists) {
                mostrarError(input, 'Esta descripción ya existe');
            } else {
                mostrarExito(input, 'Descripción disponible');
            }
        })
        .catch(error => {
            console.error('Error al verificar descripción:', error);
        });
    }

    // Función para validar formulario
    function validarFormulario() {
        let esValido = true;
        
        // Validar descripción
        const descripcion = document.getElementById('descripcion');
        if (descripcion) {
            const valor = descripcion.value.trim();
            if (!valor) {
                mostrarError(descripcion, 'La descripción es obligatoria');
                esValido = false;
            } else if (valor.length < 3) {
                mostrarError(descripcion, 'La descripción debe tener al menos 3 caracteres');
                esValido = false;
            } else if (valor.length > 255) {
                mostrarError(descripcion, 'La descripción no puede exceder 255 caracteres');
                esValido = false;
            } else if (descripcion.classList.contains('is-invalid')) {
                esValido = false; // Ya tiene error de duplicado
            } else {
                mostrarExito(descripcion);
            }
        }

        // Validar estado
        const estado = document.getElementById('estado');
        if (estado) {
            if (!estado.value) {
                mostrarError(estado, 'Debe seleccionar un estado');
                esValido = false;
            } else {
                mostrarExito(estado);
            }
        }

        return esValido;
    }

    // Función para enviar formulario
    function enviarFormulario() {
        const formData = new FormData(form);
        
        // Deshabilitar botón
        btnActualizar.disabled = true;
        btnActualizar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';

        fetch(`<?= base_url('tipo-unidad/update') ?>/${tipoId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                
                // Redirigir después de un breve delay
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '<?= base_url('tipo-unidad') ?>';
                    }
                }, 1500);
            } else {
                mostrarAlerta('error', data.message);
                
                // Mostrar errores específicos
                if (data.errors) {
                    Object.keys(data.errors).forEach(campo => {
                        const input = document.getElementById(campo);
                        if (input) {
                            mostrarError(input, data.errors[campo]);
                        }
                    });
                }
                
                // Rehabilitar botón
                btnActualizar.disabled = false;
                btnActualizar.innerHTML = '<i class="fas fa-save"></i> Actualizar Tipo de Unidad';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('error', 'Error al procesar la solicitud');
            
            // Rehabilitar botón
            btnActualizar.disabled = false;
            btnActualizar.innerHTML = '<i class="fas fa-save"></i> Actualizar Tipo de Unidad';
        });
    }

    // Funciones de utilidad para validación visual
    function mostrarError(input, mensaje) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = mensaje;
        }
    }

    function mostrarExito(input, mensaje = '') {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }

    function limpiarValidacion(input) {
        input.classList.remove('is-valid', 'is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const icon = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alerta = document.createElement('div');
        alerta.className = `alert ${alertClass} alert-dismissible fade show`;
        alerta.setAttribute('role', 'alert');
        alerta.innerHTML = `
            <i class="fas ${icon}"></i> ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertBefore(alerta, container.firstChild);
            
            // Scroll hacia arriba para mostrar la alerta
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Auto-hide después de 5 segundos
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.remove();
            }
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
