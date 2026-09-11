<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumb as $item): ?>
                <?php if (!empty($item['url'])): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= $item['url'] ?>" class="text-decoration-none">
                            <?= $item['name'] ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $item['name'] ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-<?= $action === 'create' ? 'plus' : 'edit' ?> me-2"></i>
                <?= $title ?>
            </h1>
            <p class="text-muted mb-0">
                <?= $action === 'create' ? 'Crea un nuevo rol en el sistema' : 'Modifica la información del rol' ?>
            </p>
        </div>
        <a href="<?= base_url('rol') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Volver
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Formulario principal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-1"></i>
                        Información del Rol
                    </h6>
                </div>
                <div class="card-body">
                    <form id="rolForm" novalidate>
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" id="rolId" value="<?= $rol['id'] ?>">
                        <?php endif; ?>

                        <!-- Nombre del rol -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-tag text-primary me-1"></i>
                                Nombre del Rol <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   placeholder="Ej: Administrador, Mecánico, Conductor"
                                   value="<?= isset($rol) ? esc($rol['nombre']) : '' ?>"
                                   maxlength="50"
                                   required>
                            <div class="invalid-feedback" id="nombre-error"></div>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Nombre único que identifica el rol (máximo 50 caracteres)
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="form-label">
                                <i class="fas fa-align-left text-primary me-1"></i>
                                Descripción
                            </label>
                            <textarea class="form-control" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="4"
                                      placeholder="Describe las responsabilidades y permisos de este rol..."
                                      maxlength="200"><?= isset($rol) ? esc($rol['descripcion']) : '' ?></textarea>
                            <div class="invalid-feedback" id="descripcion-error"></div>
                            <div class="form-text d-flex justify-content-between">
                                <span>
                                    <i class="fas fa-info-circle text-info me-1"></i>
                                    Descripción opcional del rol y sus responsabilidades
                                </span>
                                <span id="descripcionContador" class="text-muted">0/200</span>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('rol') ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                <?= $action === 'create' ? 'Crear Rol' : 'Actualizar Rol' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Información adicional -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-lightbulb me-1"></i>
                        Información Importante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle"></i>
                            Consejos para Roles
                        </h6>
                        <ul class="mb-0 small">
                            <li>Usa nombres descriptivos y únicos</li>
                            <li>La descripción ayuda a otros administradores</li>
                            <li>Los roles se pueden asignar a múltiples usuarios</li>
                            <li>No se pueden eliminar roles con usuarios asignados</li>
                        </ul>
                    </div>

                    <?php if ($action === 'edit'): ?>
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle"></i>
                                Editando Rol
                            </h6>
                            <p class="mb-2 small">
                                <strong>ID:</strong> <?= $rol['id'] ?><br>
                                <strong>Creado:</strong> <?= date('d/m/Y H:i', strtotime($rol['fecha_registro'])) ?>
                            </p>
                            <p class="mb-0 small">
                                Los cambios afectarán a todos los usuarios con este rol asignado.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ejemplos de roles -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-examples me-1"></i>
                        Ejemplos de Roles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-3">
                            <strong class="text-primary">Administrador</strong>
                            <p class="mb-1 text-muted">Acceso completo al sistema, gestión de usuarios y configuración.</p>
                        </div>
                        <div class="mb-3">
                            <strong class="text-success">Mecánico</strong>
                            <p class="mb-1 text-muted">Gestión de mantenimientos, vehículos y materiales.</p>
                        </div>
                        <div class="mb-0">
                            <strong class="text-info">Conductor</strong>
                            <p class="mb-0 text-muted">Acceso limitado para reportar mantenimientos y ver vehículos asignados.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('rolForm');
    const nombreInput = document.getElementById('nombre');
    const descripcionInput = document.getElementById('descripcion');
    const submitBtn = document.getElementById('submitBtn');
    const action = '<?= $action ?>';
    const rolId = document.getElementById('rolId')?.value;

    // Contador de caracteres para descripción
    actualizarContadorDescripcion();
    descripcionInput.addEventListener('input', actualizarContadorDescripcion);

    // Validación en tiempo real del nombre
    let timeoutId;
    nombreInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            validarNombreUnico();
        }, 500);
    });

    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        enviarFormulario();
    });

    function actualizarContadorDescripcion() {
        const contador = document.getElementById('descripcionContador');
        const longitud = descripcionInput.value.length;
        contador.textContent = `${longitud}/200`;
        
        if (longitud > 180) {
            contador.classList.add('text-warning');
        } else {
            contador.classList.remove('text-warning');
        }
    }

    async function validarNombreUnico() {
        const nombre = nombreInput.value.trim();
        
        if (nombre.length < 2) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('nombre', nombre);
            if (rolId) {
                formData.append('id', rolId);
            }

            const response = await fetch('<?= base_url('rol/verificarNombre') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();
            
            if (!data.disponible) {
                mostrarErrorCampo('nombre', 'Este nombre de rol ya existe');
                nombreInput.classList.add('is-invalid');
            } else {
                limpiarErrorCampo('nombre');
                nombreInput.classList.remove('is-invalid');
                nombreInput.classList.add('is-valid');
            }
        } catch (error) {
            console.error('Error al validar nombre:', error);
        }
    }

    async function enviarFormulario() {
        // Limpiar errores previos
        limpiarErrores();
        
        // Validar campos requeridos
        if (!validarFormulario()) {
            return;
        }

        // Deshabilitar botón y mostrar loading
        submitBtn.disabled = true;
        const textoOriginal = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Procesando...';

        try {
            const formData = new FormData(form);
            const url = action === 'create' 
                ? '<?= base_url('rol/store') ?>' 
                : `<?= base_url('rol/update') ?>/${rolId}`;
            
            const method = action === 'create' ? 'POST' : 'PUT';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                mostrarMensaje('success', data.message);
                
                // Redirigir después de un breve delay
                setTimeout(() => {
                    window.location.href = data.redirect || '<?= base_url('rol') ?>';
                }, 1500);
            } else {
                if (data.errors) {
                    mostrarErroresValidacion(data.errors);
                } else {
                    mostrarMensaje('error', data.message);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarMensaje('error', 'Error de conexión. Inténtalo de nuevo.');
        } finally {
            // Restaurar botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = textoOriginal;
        }
    }

    function validarFormulario() {
        let valido = true;
        
        // Validar nombre
        const nombre = nombreInput.value.trim();
        if (nombre.length < 2) {
            mostrarErrorCampo('nombre', 'El nombre debe tener al menos 2 caracteres');
            valido = false;
        } else if (nombre.length > 50) {
            mostrarErrorCampo('nombre', 'El nombre no puede exceder 50 caracteres');
            valido = false;
        }

        // Validar descripción (opcional pero con límite)
        const descripcion = descripcionInput.value.trim();
        if (descripcion.length > 200) {
            mostrarErrorCampo('descripcion', 'La descripción no puede exceder 200 caracteres');
            valido = false;
        }

        return valido;
    }

    function mostrarErroresValidacion(errores) {
        Object.keys(errores).forEach(campo => {
            mostrarErrorCampo(campo, errores[campo]);
        });
    }

    function mostrarErrorCampo(campo, mensaje) {
        const input = document.getElementById(campo);
        const errorDiv = document.getElementById(`${campo}-error`);
        
        if (input && errorDiv) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            errorDiv.textContent = mensaje;
        }
    }

    function limpiarErrorCampo(campo) {
        const input = document.getElementById(campo);
        const errorDiv = document.getElementById(`${campo}-error`);
        
        if (input && errorDiv) {
            input.classList.remove('is-invalid');
            errorDiv.textContent = '';
        }
    }

    function limpiarErrores() {
        const campos = ['nombre', 'descripcion'];
        campos.forEach(campo => {
            limpiarErrorCampo(campo);
        });
    }

    function mostrarMensaje(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            <i class="fas ${iconClass} me-2"></i>${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
