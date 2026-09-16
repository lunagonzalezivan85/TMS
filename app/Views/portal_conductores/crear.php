<?php $this->extend('layouts/portal_oneui'); ?>
<?php $this->section('content'); ?>

<div class="animate__animated animate__fadeIn">

    <!-- Encabezado -->
    <div class="text-center mb-4">
        <div class="bento-icon mx-auto mb-2" style="width:56px;height:56px;background:var(--primary);color:#fff;border-radius:18px;font-size:1.4rem;">
            <i class="fas fa-tools"></i>
        </div>
        <h5 class="fw-bold mb-1">Nueva Solicitud</h5>
        <p class="text-muted mb-0 small">Completa los pasos para enviar tu solicitud</p>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Indicador de pasos -->
    <div class="wizard-steps mb-3">
        <div class="step-item active" id="step-indicator-1">
            <div class="step-circle">1</div>
            <div class="step-label">ID</div>
        </div>
        <div class="step-line" id="line-1-2"></div>
        <div class="step-item" id="step-indicator-2">
            <div class="step-circle">2</div>
            <div class="step-label">Vehículo</div>
        </div>
        <div class="step-line" id="line-2-3"></div>
        <div class="step-item" id="step-indicator-3">
            <div class="step-circle">3</div>
            <div class="step-label">Problema</div>
        </div>
        <div class="step-line" id="line-3-4"></div>
        <div class="step-item" id="step-indicator-4">
            <div class="step-circle">4</div>
            <div class="step-label">Desc.</div>
        </div>
        <div class="step-line" id="line-4-5"></div>
        <div class="step-item" id="step-indicator-5">
            <div class="step-circle">5</div>
            <div class="step-label">Foto</div>
        </div>
    </div>

    <!-- Formulario -->
    <?= form_open_multipart('portal/solicitud/store', ['id' => 'wizard-form']) ?>
    <input type="hidden" name="id_vehiculo" id="id_vehiculo" value="<?= old('id_vehiculo') ?>">
    <input type="hidden" name="id_tipo_problema" id="id_tipo_problema" value="<?= old('id_tipo_problema') ?>">

    <!-- ===================== PASO 1: Identificación del Solicitante ===================== -->
    <div class="oneui-card mb-3" id="step-1">
        <div class="d-flex align-items-center gap-2 mb-1">
            <div class="bento-icon" style="width:36px;height:36px;font-size:.9rem;background:#e0e7ff;color:var(--primary);border-radius:12px;">
                <i class="fas fa-id-card"></i>
            </div>
            <h6 class="fw-semibold mb-0">Identificación del solicitante</h6>
        </div>
        <p class="text-muted small mb-3 ms-1">Ingresa tu número de carnet o cédula de identidad</p>

        <div class="input-group mb-2">
            <span class="input-group-text bg-white" style="border-radius:14px 0 0 14px;border:2px solid var(--border);border-right:none;"><i class="fas fa-id-badge text-primary"></i></span>
            <input type="text" name="carnet_solicitante" id="carnet-input" class="form-control form-control-lg"
                   placeholder="Ej: 001-123456-0001X"
                   value="<?= old('carnet_solicitante', session()->get('conductor_carnet') ?? '') ?>"
                   autocomplete="off" inputmode="text"
                   style="border-radius:0 14px 14px 0;border-left:none;">
        </div>
        <div class="form-text mb-1">Número de carnet o cédula de identidad del conductor.</div>
        <div class="invalid-feedback d-block d-none" id="error-carnet">Ingresa tu número de carnet o identificación.</div>

        <div class="text-end mt-3">
            <button type="button" class="btn btn-oneui btn-oneui-primary w-100" onclick="irPaso(2)">
                Siguiente <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- ===================== PASO 2: Buscar Vehículo ===================== -->
    <div class="oneui-card mb-3 d-none" id="step-2">
        <div class="d-flex align-items-center gap-2 mb-1">
            <div class="bento-icon" style="width:36px;height:36px;font-size:.9rem;background:#dbeafe;color:#2563eb;border-radius:12px;">
                <i class="fas fa-car"></i>
            </div>
            <h6 class="fw-semibold mb-0">Buscar vehículo</h6>
        </div>
        <p class="text-muted small mb-3 ms-1">Ingresa la placa, código consecutivo o número de motor</p>

        <div class="d-flex gap-2 mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white" style="border-radius:14px 0 0 14px;border:2px solid var(--border);border-right:none;"><i class="fas fa-search text-primary"></i></span>
                <input type="text" id="buscar-input" class="form-control form-control-lg"
                       placeholder="Placa, código o motor"
                       autocomplete="off" autocapitalize="characters"
                       style="border-radius:0;border-left:none;border-right:none;">
            </div>
            <button class="btn btn-oneui btn-oneui-primary flex-shrink-0" type="button" id="btn-buscar" style="min-width:52px;border-radius:14px;">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Skeleton Loader -->
        <div id="buscar-skeleton" class="d-none animate__animated animate__pulse animate__infinite">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                <div class="rounded-circle bg-secondary bg-opacity-10 p-4"></div>
                <div class="flex-grow-1">
                    <div class="bg-secondary bg-opacity-10 rounded-pill w-50 mb-2" style="height: 15px;"></div>
                    <div class="bg-secondary bg-opacity-10 rounded-pill w-75" style="height: 10px;"></div>
                </div>
            </div>
        </div>

        <!-- Resultado de búsqueda -->
        <div id="buscar-resultado" class="d-none mb-2 animate__animated animate__fadeIn">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="border:2px solid var(--primary);background:#eff6ff;">
                <div class="rounded-circle p-2 flex-shrink-0" style="background:rgba(37,99,235,.15);">
                    <i class="fas fa-truck text-primary"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold" id="res-placa"></div>
                    <div class="text-muted small text-truncate" id="res-detalle"></div>
                </div>
                <i class="fas fa-check-circle text-primary fa-lg flex-shrink-0 animate__animated animate__bounceIn"></i>
            </div>
        </div>

        <!-- Error búsqueda -->
        <div id="buscar-error" class="alert alert-warning d-none py-2 small rounded-3">
            <i class="fas fa-exclamation-triangle me-2"></i><span id="buscar-error-msg"></span>
        </div>

        <div class="invalid-feedback d-block d-none" id="error-vehiculo">Debes buscar y seleccionar un vehículo.</div>

        <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn btn-oneui btn-oneui-outline flex-fill" onclick="irPaso(1)">
                <i class="fas fa-arrow-left me-1"></i> Anterior
            </button>
            <button type="button" class="btn btn-oneui btn-oneui-primary flex-fill" id="btn-paso3" onclick="irPaso(3)" disabled>
                Siguiente <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- ===================== PASO 3: Tipo de Problema ===================== -->
    <div class="oneui-card mb-3 d-none" id="step-3">
        <!-- Resumen vehículo -->
        <div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-3" style="background:#eff6ff;">
            <i class="fas fa-car text-primary flex-shrink-0"></i>
            <span class="small flex-grow-1 text-truncate"><strong id="resumen-placa"></strong> <span class="text-muted" id="resumen-detalle"></span></span>
            <button type="button" class="btn btn-sm btn-link p-0 text-primary flex-shrink-0" onclick="irPaso(2)">
                <i class="fas fa-edit"></i>
            </button>
        </div>

        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bento-icon" style="width:36px;height:36px;font-size:.9rem;background:#fef3c7;color:#d97706;border-radius:12px;">
                <i class="fas fa-wrench"></i>
            </div>
            <h6 class="fw-semibold mb-0">Tipo de problema</h6>
        </div>

        <?php if (empty($tiposProblema)): ?>
            <div class="alert alert-warning rounded-3"><i class="fas fa-exclamation-triangle me-2"></i>No hay tipos de problema configurados.</div>
        <?php else: ?>
            <?php foreach ($tiposProblema as $categoria => $tipos): ?>
                <?php if (count($tiposProblema) > 1): ?>
                    <p class="text-muted small mb-1 mt-2"><?= esc($categoria) ?></p>
                <?php endif; ?>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <?php foreach ($tipos as $tipo): ?>
                        <button type="button"
                                class="btn btn-outline-secondary btn-tipo-problema"
                                data-id="<?= $tipo['id'] ?>"
                                onclick="seleccionarTipo(this, <?= $tipo['id'] ?>)">
                            <?= esc($tipo['nombre']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div class="invalid-feedback d-block d-none" id="error-tipo">Selecciona un tipo de problema.</div>
        <?php endif; ?>

        <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn btn-oneui btn-oneui-outline flex-fill" onclick="irPaso(2)">
                <i class="fas fa-arrow-left me-1"></i> Anterior
            </button>
            <button type="button" class="btn btn-oneui btn-oneui-primary flex-fill" onclick="irPaso(4)">
                Siguiente <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- ===================== PASO 4: Descripción ===================== -->
    <div class="oneui-card mb-3 d-none" id="step-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bento-icon" style="width:36px;height:36px;font-size:.9rem;background:#cffafe;color:#0891b2;border-radius:12px;">
                <i class="fas fa-comment-alt"></i>
            </div>
            <h6 class="fw-semibold mb-0">Describe el problema</h6>
        </div>
        <textarea name="descripcion" id="descripcion" rows="5"
                  class="form-control"
                  placeholder="Describe brevemente el problema que presenta el vehículo..."
                  minlength="10"><?= old('descripcion') ?></textarea>
        <div class="form-text">Mínimo 10 caracteres.</div>
        <div class="invalid-feedback d-block d-none" id="error-descripcion">La descripción es obligatoria (mínimo 10 caracteres).</div>

        <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn btn-oneui btn-oneui-outline flex-fill" onclick="irPaso(3)">
                <i class="fas fa-arrow-left me-1"></i> Anterior
            </button>
            <button type="button" class="btn btn-oneui btn-oneui-primary flex-fill" onclick="irPaso(5)">
                Siguiente <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- ===================== PASO 5: Archivo ===================== -->
    <div class="oneui-card mb-3 d-none" id="step-5">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bento-icon" style="width:36px;height:36px;font-size:.9rem;background:#dcfce7;color:#16a34a;border-radius:12px;">
                <i class="fas fa-paperclip"></i>
            </div>
            <h6 class="fw-semibold mb-0">Adjuntar foto <span class="text-muted fw-normal small">(opcional)</span></h6>
        </div>

        <div id="drop-zone" class="text-center p-5 mb-3 rounded-3"
             style="cursor:pointer;border:2px dashed var(--border);"
             onclick="document.getElementById('foto').click()">
            <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-2"></i>
            <p class="mb-0 text-muted">Haz clic o arrastra una imagen aquí</p>
            <small class="text-muted">JPG, PNG — máx. 5 MB</small>
        </div>
        <input type="file" name="foto" id="foto" class="d-none" accept="image/jpg,image/jpeg,image/png"
               onchange="mostrarArchivo(this)">
        <div id="archivo-preview" class="d-none alert alert-success py-2 rounded-3">
            <i class="fas fa-check-circle me-2"></i><span id="archivo-nombre"></span>
            <button type="button" class="btn-close float-end" onclick="quitarArchivo()"></button>
        </div>

        <div class="d-flex gap-2 mb-2">
            <button type="button" class="btn btn-oneui btn-oneui-outline flex-fill" onclick="irPaso(4)">
                <i class="fas fa-arrow-left me-1"></i> Anterior
            </button>
            <button type="button" class="btn btn-oneui btn-oneui-outline flex-fill" onclick="enviarFormulario()">
                Omitir y Enviar
            </button>
        </div>
        <button type="submit" class="btn btn-oneui w-100" id="btn-enviar" style="background:var(--success);color:#fff;">
            <i class="fas fa-paper-plane me-1"></i> Enviar Solicitud
        </button>
    </div>

    <?= form_close() ?>

</div>

<!-- Estilos del wizard -->
<style>
/* ── Stepper ── */
.wizard-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    overflow: hidden;
}
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
}
.step-circle {
    width: 32px; height: 32px; border-radius: 50%;
    background: #dee2e6; color: #6c757d;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.82rem; transition: all .3s;
}
.step-item.active .step-circle { background: #0d6efd; color: #fff; }
.step-item.done .step-circle   { background: #198754; color: #fff; }
.step-label {
    font-size: 0.62rem; margin-top: 3px; color: #6c757d;
    white-space: nowrap; max-width: 44px; text-align: center;
    overflow: hidden; text-overflow: ellipsis;
}
.step-item.active .step-label { color: #0d6efd; font-weight: 600; }
.step-item.done .step-label   { color: #198754; }
.step-line {
    flex: 1; height: 2px; background: #dee2e6;
    margin: 0 3px; margin-bottom: 18px; transition: background .3s;
    min-width: 16px;
}
.step-line.done { background: #198754; }

/* ── Tipo problema pills ── */
.btn-tipo-problema { border-radius: 999px; font-size: 0.82rem; padding: .35rem .85rem; border: 2px solid var(--border); }
.btn-tipo-problema.active { background: var(--primary); color: #fff; border-color: var(--primary); }

/* ── Drop zone ── */
#drop-zone { transition: background .2s, border-color .2s; }
#drop-zone:hover { background: #f8f9fa; border-color: var(--primary) !important; }

/* ── Reduce drop zone padding on mobile ── */
@media (max-width: 575px) {
    #drop-zone { padding: 2rem 1rem !important; }
    .wizard-steps { padding: 0; }
    .step-circle { width: 28px; height: 28px; font-size: 0.75rem; }
    .step-label { font-size: 0.58rem; max-width: 38px; }
    .step-line { min-width: 10px; margin: 0 2px; margin-bottom: 16px; }
}
</style>

<?php $this->endSection(); ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('public/assets/js/modules/Portal.js') ?>"></script>

<?php $this->endSection(); ?>
