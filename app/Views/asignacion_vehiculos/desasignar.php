<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('asignacion-vehiculos') ?>">Asignación de Vehículos</a></li>
            <li class="breadcrumb-item active">Desasignar Vehículo</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-unlink me-2"></i>
                        Desasignar Vehículo
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Errores de validación:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Información de la Asignación Actual -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Información de la Asignación</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Vehículo:</strong> <?= esc($asignacion['placa']) ?> - <?= esc($asignacion['marca']) ?> <?= esc($asignacion['modelo']) ?><br>
                                <strong>Conductor:</strong> <?= esc($asignacion['conductor_nombre']) ?><br>
                                <strong>DNI:</strong> <?= esc($asignacion['conductor_dni']) ?>
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha de Asignación:</strong> <?= date('d/m/Y', strtotime($asignacion['fecha_asignacion'])) ?><br>
                                <strong>Tipo de Unidad:</strong> <?= esc($asignacion['tipo_unidad_descripcion'] ?? 'No asignado') ?><br>
                                <strong>Estado:</strong> <span class="badge bg-success">ACTIVA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Advertencia -->
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Importante</h6>
                        <p class="mb-0">
                            Al desasignar este vehículo, se realizarán los siguientes cambios automáticamente:
                        </p>
                        <ul class="mt-2 mb-0">
                            <li>El vehículo cambiará su estado a <strong>DISPONIBLE</strong></li>
                            <li>El conductor quedará sin vehículo asignado</li>
                            <li>La asignación cambiará su estado a <strong>INACTIVA</strong></li>
                            <li>Se registrará la fecha y motivo de desasignación</li>
                        </ul>
                    </div>

                    <!-- Formulario de Desasignación -->
                    <form action="<?= base_url('asignacion-vehiculos/desasignar/' . $asignacion['id']) ?>" 
                          method="post" id="formDesasignar">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="motivo_desasignacion" class="form-label">
                                <i class="fas fa-comment me-1"></i>Motivo de la Desasignación *
                            </label>
                            <textarea class="form-control <?= session()->getFlashdata('errors')['motivo_desasignacion'] ?? false ? 'is-invalid' : '' ?>" 
                                      id="motivo_desasignacion" 
                                      name="motivo_desasignacion" 
                                      rows="4" 
                                      placeholder="Describe el motivo por el cual se desasigna este vehículo..." 
                                      required><?= old('motivo_desasignacion') ?></textarea>
                            <div class="form-text" id="contadorCaracteres">0/500 caracteres (mínimo 10)</div>
                            <?php if (session()->getFlashdata('errors')['motivo_desasignacion'] ?? false): ?>
                                <div class="invalid-feedback">
                                    <?= session()->getFlashdata('errors')['motivo_desasignacion'] ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Confirmación -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmarDesasignacion" required>
                                <label class="form-check-label" for="confirmarDesasignacion">
                                    <strong>Confirmo que deseo desasignar este vehículo y entiendo las consecuencias de esta acción</strong>
                                </label>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="<?= base_url('asignacion-vehiculos/show/' . $asignacion['id']) ?>" 
                               class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <a href="<?= base_url('asignacion-vehiculos') ?>" 
                               class="btn btn-outline-secondary me-2">
                                <i class="fas fa-list me-2"></i>Ir al Listado
                            </a>
                            <button type="submit" class="btn btn-warning" id="btnDesasignar" disabled>
                                <i class="fas fa-unlink me-2"></i>Desasignar Vehículo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formDesasignar');
    const motivoTextarea = document.getElementById('motivo_desasignacion');
    const contadorCaracteres = document.getElementById('contadorCaracteres');
    const confirmarCheckbox = document.getElementById('confirmarDesasignacion');
    const btnDesasignar = document.getElementById('btnDesasignar');

    // Contador de caracteres
    motivoTextarea.addEventListener('input', function() {
        const length = this.value.length;
        contadorCaracteres.textContent = `${length}/500 caracteres (mínimo 10)`;
        
        if (length < 10) {
            contadorCaracteres.className = 'form-text text-danger';
        } else if (length > 500) {
            contadorCaracteres.className = 'form-text text-danger';
            this.value = this.value.substring(0, 500);
        } else {
            contadorCaracteres.className = 'form-text text-success';
        }
        
        validarFormulario();
    });

    // Validar checkbox de confirmación
    confirmarCheckbox.addEventListener('change', function() {
        validarFormulario();
    });

    // Función para validar el formulario
    function validarFormulario() {
        const motivoValido = motivoTextarea.value.length >= 10 && motivoTextarea.value.length <= 500;
        const confirmado = confirmarCheckbox.checked;
        
        btnDesasignar.disabled = !(motivoValido && confirmado);
    }

    // Validación del formulario al enviar
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const motivo = motivoTextarea.value.trim();
        
        if (motivo.length < 10) {
            alert('El motivo debe tener al menos 10 caracteres');
            motivoTextarea.focus();
            return;
        }
        
        if (motivo.length > 500) {
            alert('El motivo no puede exceder los 500 caracteres');
            motivoTextarea.focus();
            return;
        }
        
        if (!confirmarCheckbox.checked) {
            alert('Debe confirmar que desea realizar la desasignación');
            confirmarCheckbox.focus();
            return;
        }

        // Confirmación final
        if (confirm('¿Está completamente seguro de desasignar este vehículo? Esta acción no se puede deshacer.')) {
            // Mostrar loading
            btnDesasignar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
            btnDesasignar.disabled = true;
            
            // Enviar formulario
            this.submit();
        }
    });

    // Validación inicial
    validarFormulario();
});
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.alert {
    border-left: 4px solid;
}

.alert-info {
    border-left-color: #0dcaf0;
}

.alert-warning {
    border-left-color: #ffc107;
}

.form-check-input:checked {
    background-color: #ffc107;
    border-color: #ffc107;
}
</style>
<?= $this->endSection() ?>
