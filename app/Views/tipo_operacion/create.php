<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-plus-circle text-primary"></i>
                        <?= $page_title ?>
                    </h1>
                    <p class="text-muted mb-0">Complete el formulario para crear un nuevo tipo de operación</p>
                </div>
                <div>
                    <a href="<?= base_url('tipo-operacion') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit"></i> Información del Tipo de Operación
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form-tipo-operacion" action="<?= base_url('tipo-operacion/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">
                                        <i class="fas fa-tag text-primary"></i>
                                        Descripción <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="descripcion" 
                                           name="descripcion" 
                                           value="<?= old('descripcion') ?>"
                                           placeholder="Ingrese la descripción del tipo de operación"
                                           required>
                                    <div class="form-text">
                                        Ingrese una descripción clara y concisa del tipo de operación (mínimo 3 caracteres)
                                    </div>
                                    <div class="invalid-feedback" id="error-descripcion"></div>
                                    <div class="valid-feedback" id="success-descripcion">
                                        Descripción disponible
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-info-circle text-info"></i>
                                        Estado Inicial
                                    </label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-success">ACTIVO</span>
                                        <small class="text-muted ms-2">El tipo de operación se creará como activo</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user text-info"></i>
                                        Usuario Creador
                                    </label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-info"><?= session()->get('username') ?></span>
                                        <small class="text-muted ms-2">Usuario actual</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-lightbulb"></i> Información Importante
                                    </h6>
                                    <ul class="mb-0">
                                        <li>La descripción debe ser única en el sistema</li>
                                        <li>El tipo de operación se creará en estado ACTIVO</li>
                                        <li>Podrá modificar el estado posteriormente desde la lista</li>
                                        <li>Los campos de auditoría se completarán automáticamente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('tipo-operacion') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="btn-guardar">
                                        <i class="fas fa-save"></i> Guardar Tipo de Operación
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
    const form = document.getElementById('form-tipo-operacion');
    const descripcionInput = document.getElementById('descripcion');
    const btnGuardar = document.getElementById('btn-guardar');
    let timeoutId = null;

    // Validación en tiempo real de la descripción
    descripcionInput.addEventListener('input', function() {
        const descripcion = this.value.trim();
        
        // Limpiar timeout anterior
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        
        // Limpiar estados anteriores
        this.classList.remove('is-valid', 'is-invalid');
        document.getElementById('error-descripcion').textContent = '';
        document.getElementById('success-descripcion').textContent = '';
        
        // Validar longitud mínima
        if (descripcion.length < 3) {
            if (descripcion.length > 0) {
                this.classList.add('is-invalid');
                document.getElementById('error-descripcion').textContent = 'La descripción debe tener al menos 3 caracteres';
            }
            return;
        }
        
        // Validar longitud máxima
        if (descripcion.length > 255) {
            this.classList.add('is-invalid');
            document.getElementById('error-descripcion').textContent = 'La descripción no puede exceder 255 caracteres';
            return;
        }
        
        // Verificar disponibilidad después de 500ms
        timeoutId = setTimeout(() => {
            verificarDescripcion(descripcion);
        }, 500);
    });

    // Función para verificar si la descripción ya existe
    function verificarDescripcion(descripcion) {
        fetch('<?= base_url('tipo-operacion/verificarDescripcion') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `descripcion=${encodeURIComponent(descripcion)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.existe) {
                descripcionInput.classList.add('is-invalid');
                descripcionInput.classList.remove('is-valid');
                document.getElementById('error-descripcion').textContent = data.message;
            } else {
                descripcionInput.classList.add('is-valid');
                descripcionInput.classList.remove('is-invalid');
                document.getElementById('success-descripcion').textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Error al verificar descripción:', error);
        });
    }

    // Validación del formulario antes del envío
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const descripcion = descripcionInput.value.trim();
        let isValid = true;
        
        // Limpiar errores anteriores
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        // Validar descripción
        if (!descripcion) {
            descripcionInput.classList.add('is-invalid');
            document.getElementById('error-descripcion').textContent = 'La descripción es obligatoria';
            isValid = false;
        } else if (descripcion.length < 3) {
            descripcionInput.classList.add('is-invalid');
            document.getElementById('error-descripcion').textContent = 'La descripción debe tener al menos 3 caracteres';
            isValid = false;
        } else if (descripcion.length > 255) {
            descripcionInput.classList.add('is-invalid');
            document.getElementById('error-descripcion').textContent = 'La descripción no puede exceder 255 caracteres';
            isValid = false;
        }
        
        // Si hay errores, enfocar el primer campo con error
        if (!isValid) {
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Deshabilitar botón y mostrar loading
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        
        // Enviar formulario
        this.submit();
    });

    // Auto-hide alerts después de 5 segundos
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Enfocar el primer campo al cargar la página
    descripcionInput.focus();
});
</script>
<?= $this->endSection() ?>
