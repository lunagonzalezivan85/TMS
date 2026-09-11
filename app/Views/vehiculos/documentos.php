<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$estadoVehiculo = strtolower($vehiculo['estado'] ?? '');
$badgeClass = $estadoVehiculo === 'activo' ? 'success' : ($estadoVehiculo === 'inactivo' ? 'secondary' : 'warning');
$totalDocs = count($documentos);
$vigentes = 0; $porVencer = 0; $vencidos = 0;
foreach ($documentos as $d) {
    $fv = $d['fecha_vencimiento'] ?? null;
    if (empty($fv)) continue;
    $dias = (strtotime($fv) - time()) / 86400;
    if ($dias < 0) $vencidos++;
    elseif ($dias <= 30) $porVencer++;
    else $vigentes++;
}
?>
<style>
.bento-stat { border-radius: 1rem; border: none; background: linear-gradient(135deg, var(--bg-from), var(--bg-to)); color: #fff; }
.bento-stat .val { font-size: 1.75rem; font-weight: 700; line-height: 1; }
.bento-stat .lbl { font-size: .72rem; opacity: .85; text-transform: uppercase; letter-spacing: .5px; }
</style>

<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('vehiculos') ?>">Vehículos</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>"><?= esc($vehiculo['placa']) ?></a></li>
            <li class="breadcrumb-item active">Documentos</li>
        </ol>
    </nav>

    <!-- ══ HERO BANNER ══ -->
    <div class="card shadow-sm mb-3 border-0 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);">
        <div class="card-body p-4 text-white">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                        <span class="badge fs-5 fw-bold px-4 py-2 rounded-pill" style="background: rgba(255,255,255,.2); backdrop-filter: blur(10px); letter-spacing: 2px;">
                            <i class="fas fa-id-card me-2"></i><?= esc($vehiculo['placa']) ?>
                        </span>
                        <h3 class="mb-0 fw-bold d-inline">
                            <?= esc($vehiculo['marca']) ?> <?= esc($vehiculo['modelo']) ?>
                            <span class="fw-normal opacity-75 fs-5">(<?= esc($vehiculo['anio']) ?>)</span>
                        </h3>
                    </div>
                    <div class="d-flex gap-4 mt-2 flex-wrap">
                        <div><i class="fas fa-road me-1 opacity-75"></i> <span class="fw-medium"><?= number_format($vehiculo['kilometraje'] ?? 0) ?> km</span></div>
                        <div><i class="fas fa-tag me-1 opacity-75"></i> <span class="fw-medium"><?= esc($vehiculo['codigo_consecutivo'] ?? '—') ?></span></div>
                        <div><span class="badge bg-<?= $badgeClass ?>"><?= esc($vehiculo['estado']) ?></span></div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button type="button" class="btn btn-light fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#modalSubirDocumento">
                        <i class="fas fa-upload me-2"></i>Subir Documento
                    </button>
                    <a href="<?= base_url('vehiculos/show/' . $vehiculo['id']) ?>" class="btn btn-outline-light ms-1">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ ALERTAS DE VENCIMIENTO ══ -->
    <?php if (!empty($alertasDocumentos)): ?>
    <div class="mb-3">
        <?php foreach ($alertasDocumentos as $alerta):
            $esVencido = $alerta['vencido'];
            $color = $esVencido ? '#dc3545' : '#ffc107';
            $bgColor = $esVencido ? 'rgba(220,53,69,.08)' : 'rgba(255,193,7,.08)';
            $icono = $esVencido ? 'fas fa-times-circle' : 'fas fa-exclamation-triangle';
            $texto = $esVencido
                ? 'VENCIDO hace ' . $alerta['dias_restantes'] . ' día(s)'
                : 'Vence en ' . $alerta['dias_restantes'] . ' día(s)';
        ?>
        <div class="card border-0 rounded-3 mb-2" style="background: <?= $bgColor ?>; border-left: 4px solid <?= $color ?> !important;">
            <div class="card-body p-2 px-3 d-flex align-items-center gap-2">
                <i class="<?= $icono ?> me-1" style="color: <?= $color ?>; font-size: 1.1rem;"></i>
                <span class="fw-semibold" style="color: <?= $color ?>; font-size: .85rem;">
                    <?= esc($alerta['nombre']) ?>
                </span>
                <span class="badge rounded-pill" style="background: <?= $color ?>; font-size: .7rem;">
                    <?= $texto ?>
                </span>
                <span class="text-muted ms-auto" style="font-size: .75rem;">
                    <i class="fas fa-calendar me-1"></i><?= date('d/m/Y', strtotime($alerta['fecha_vencimiento'])) ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ══ BENTO STATS ══ -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="bento-stat card h-100 shadow-sm" style="--bg-from:#1a237e;--bg-to:#3949ab;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:.75rem;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <div class="val"><?= $totalDocs ?></div>
                        <div class="lbl">Total Documentos</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="bento-stat card h-100 shadow-sm" style="--bg-from:#28a745;--bg-to:#1e7e34;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:.75rem;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="val"><?= $vigentes ?></div>
                        <div class="lbl">Vigentes</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="bento-stat card h-100 shadow-sm" style="--bg-from:#ffc107;--bg-to:#d39e00;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:.75rem;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#fff;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="val"><?= $porVencer ?></div>
                        <div class="lbl">Por Vencer</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="bento-stat card h-100 shadow-sm" style="--bg-from:#dc3545;--bg-to:#a71d2a;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:.75rem;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="val"><?= $vencidos ?></div>
                        <div class="lbl">Vencidos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ DOCUMENTS GRID ══ -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="fas fa-folder-open me-2 text-primary"></i>Documentos del Vehículo</h5>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary active" id="btnVistaTarjetas" title="Vista tarjetas">
                <i class="mdi mdi-view-grid-outline"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnVistaTabla" title="Vista tabla">
                <i class="mdi mdi-table"></i>
            </button>
        </div>
    </div>

    <div id="contenedorVistaDocumentos">
        <?= view('vehiculos/partials/documentos_cards', ['documentos' => $documentos, 'vehiculo' => $vehiculo]) ?>
    </div>
</div>

<!-- ══ MODAL SUBIR DOCUMENTO ══ -->
<div class="modal fade" id="modalSubirDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow" style="overflow:hidden;">
            <div class="modal-header text-white border-0 p-3" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
                <h5 class="modal-title fw-bold"><i class="fas fa-upload me-2"></i>Subir Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSubirDocumento" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="id_vehiculo" value="<?= $vehiculo['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-file-signature me-1 text-primary"></i>Tipo de Documento <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" id="tipo_documento_nombre" placeholder="Seleccionar tipo de documento" readonly required>
                            <input type="hidden" id="tipo_documento" name="tipo_documento">
                            <button class="btn btn-outline-primary" type="button" id="btnBuscarTipoDocumento">
                                <i class="fas fa-search me-1"></i>Buscar
                            </button>
                        </div>
                        <div class="invalid-feedback" id="error_tipo_documento">Debe seleccionar un tipo de documento</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-paperclip me-1 text-primary"></i>Archivo <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-lg" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        <div class="form-text">Formatos: PDF, JPG, PNG, DOC, DOCX · Máx 5MB</div>
                        <div class="invalid-feedback" id="error_archivo"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold"><i class="fas fa-hashtag me-1 text-primary"></i>Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" maxlength="100" placeholder="Nº del documento">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold"><i class="fas fa-calendar-alt me-1 text-primary"></i>Vencimiento</label>
                            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-semibold"><i class="fas fa-bell me-1 text-primary"></i>Notificación previa</label>
                        <div class="input-group" style="max-width:200px;">
                            <input type="number" min="0" class="form-control" id="notificacion" name="notificacion" placeholder="7">
                            <span class="input-group-text">días</span>
                        </div>
                        <div class="form-text">Días antes del vencimiento para alertar</div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-semibold"><i class="fas fa-comment me-1 text-primary"></i>Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" id="btnSubir">
                        <i class="fas fa-upload me-1"></i>Subir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══ MODAL BUSCAR TIPO DOCUMENTO ══ -->
<div class="modal fade" id="modalBuscarTipoDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow" style="overflow:hidden;">
            <div class="modal-header text-white border-0 p-3" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
                <h5 class="modal-title fw-bold"><i class="fas fa-search me-2"></i>Buscar Tipo de Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="input-group input-group-lg mb-3">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="buscarDocumentoInput" placeholder="Escriba para buscar...">
                    <button class="btn btn-primary" type="button" id="btnBuscarDocumento">
                        <i class="fas fa-search me-1"></i>Buscar
                    </button>
                </div>
                <div class="table-responsive" style="max-height:350px;overflow-y:auto;">
                    <table class="table table-hover">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th width="90">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="resultadosBusqueda"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- ══ MODAL VISUALIZAR ══ -->
<div class="modal fade" id="modalVisualizarDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow" style="overflow:hidden;">
            <div class="modal-header text-white border-0 p-3" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
                <h5 class="modal-title fw-bold"><i class="fas fa-eye me-2"></i>Visualizar: <span id="nombreDocumentoVer"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: 65vh;">
                <div id="contenidoDocumento" class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="btnDescargarModal" class="btn btn-success rounded-pill px-4" target="_blank">
                    <i class="fas fa-download me-1"></i>Descargar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ══ MODAL ELIMINAR ══ -->
<div class="modal fade" id="modalEliminarDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow" style="overflow:hidden;">
            <div class="modal-header text-white border-0 p-3" style="background:linear-gradient(135deg,#dc3545,#a71d2a);">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3 opacity-25"></i>
                <p>¿Eliminar el documento <strong id="nombreDocumentoEliminar"></strong>?</p>
                <p class="text-danger small"><i class="fas fa-exclamation-triangle me-1"></i>Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0 p-3 justify-content-center">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger rounded-pill px-4" id="btnConfirmarEliminar">
                    <i class="fas fa-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let documentoIdEliminar = null;

    const tiposDocumentoData = <?= json_encode($tiposDocumento ?? []) ?>;

    function buscarTiposDocumento(termino = '') {
        let html = '';
        let filtrados = tiposDocumentoData;
        if (termino) {
            const t = termino.toLowerCase();
            filtrados = tiposDocumentoData.filter(function(item) {
                return (item.nombre && item.nombre.toLowerCase().includes(t)) ||
                       (item.codigo && item.codigo.toLowerCase().includes(t)) ||
                       (item.descripcion && item.descripcion.toLowerCase().includes(t));
            });
        }
        if (filtrados.length > 0) {
            filtrados.forEach(function(item) {
                html += `
                    <tr>
                        <td>${item.codigo || ''}</td>
                        <td>${item.nombre}</td>
                        <td>${item.descripcion || ''}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary btn-seleccionar-documento rounded-pill px-3"
                                data-id="${item.id}"
                                data-nombre="${item.nombre}">
                                <i class="fas fa-check me-1"></i>Seleccionar
                            </button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="4" class="text-center text-muted py-3">No se encontraron resultados</td></tr>';
        }
        $('#resultadosBusqueda').html(html);
    }

    const modalBuscar = new bootstrap.Modal(document.getElementById('modalBuscarTipoDocumento'), { backdrop: true, keyboard: true });

    $('#btnBuscarTipoDocumento').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const modalSubir = bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumento'));
        modalBuscar.show();
        buscarTiposDocumento();
        $('#modalBuscarTipoDocumento').on('hidden.bs.modal', function () {
            if (modalSubir) modalSubir.show();
        });
    });

    const modalSubir = new bootstrap.Modal(document.getElementById('modalSubirDocumento'), { backdrop: 'static', keyboard: false });

    $('#btnBuscarDocumento').on('click', function() {
        buscarTiposDocumento($('#buscarDocumentoInput').val());
    });

    $('#buscarDocumentoInput').on('keyup', function(e) {
        if (e.key === 'Enter') buscarTiposDocumento($(this).val());
    });

    $(document).on('click', '.btn-seleccionar-documento', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        document.getElementById('tipo_documento').value = id;
        document.getElementById('tipo_documento_nombre').value = nombre;
        $('#error_tipo_documento').hide();
        bootstrap.Modal.getInstance(document.getElementById('modalBuscarTipoDocumento')).hide();
        setTimeout(() => document.getElementById('tipo_documento_nombre').focus(), 100);
    });

    let modalInicializado = false;
    $('#modalSubirDocumento').on('show.bs.modal', function() {
        if (!modalInicializado) {
            $('#tipo_documento').val('');
            $('#tipo_documento_nombre').val('');
            modalInicializado = true;
        }
    });

    $('.ver-documento').on('click', function() {
        const documentoId = $(this).data('id');
        const nombreDocumento = $(this).data('nombre');
        const tipoArchivo = ($(this).data('tipo') || '').toLowerCase();

        $('#nombreDocumentoVer').text(nombreDocumento);
        $('#btnDescargarModal').attr('href', '<?= base_url('vehiculos/descargarDocumento/') ?>' + documentoId);

        $('#contenidoDocumento').html('<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-primary"></div></div>');
        $('#modalVisualizarDocumento').modal('show');

        if (tipoArchivo === 'pdf') {
            const pdfUrl = '<?= base_url('vehiculos/verDocumento/') ?>' + documentoId;
            $('#contenidoDocumento').html(`<iframe src="${pdfUrl}" class="w-100 h-100" style="border:none;" title="${nombreDocumento}"></iframe>`);
        } else if (['jpg','jpeg','png','gif'].includes(tipoArchivo)) {
            const imgUrl = '<?= base_url('vehiculos/verDocumento/') ?>' + documentoId;
            $('#contenidoDocumento').html(`<div class="d-flex justify-content-center align-items-center h-100 p-3"><img src="${imgUrl}" class="img-fluid" style="max-height:100%;max-width:100%;object-fit:contain;" alt="${nombreDocumento}"></div>`);
        } else {
            $('#contenidoDocumento').html(`<div class="d-flex flex-column justify-content-center align-items-center h-100 text-center p-4"><i class="fas fa-file-alt fa-4x text-muted mb-3"></i><h5>Archivo no visualizable</h5><a href="<?= base_url('vehiculos/descargarDocumento/') ?>${documentoId}" class="btn btn-primary mt-2"><i class="fas fa-download me-2"></i>Descargar</a></div>`);
        }
    });

    $('#formSubirDocumento').on('submit', function(e) {
        e.preventDefault();
        $('.is-invalid').removeClass('is-invalid');

        const archivo = $('#archivo')[0].files[0];
        if (!archivo) { $('#archivo').addClass('is-invalid'); $('#error_archivo').text('Debe seleccionar un archivo'); return; }
        if (archivo.size > 5 * 1024 * 1024) { $('#archivo').addClass('is-invalid'); $('#error_archivo').text('Máximo 5MB'); return; }

        const tiposPermitidos = ['application/pdf','image/jpeg','image/jpg','image/png','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!tiposPermitidos.includes(archivo.type)) { $('#archivo').addClass('is-invalid'); $('#error_archivo').text('Formato no permitido'); return; }

        $('#btnSubir').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Subiendo...');

        const formData = new FormData(this);
        $.ajax({
            url: '<?= base_url('vehiculos/subirDocumento') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    mostrarAlerta('success', response.message);
                    $('#modalSubirDocumento').modal('hide');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta('error', response.message || 'Error al subir');
                    $('#btnSubir').prop('disabled', false).html('<i class="fas fa-upload me-1"></i>Subir');
                }
            },
            error: function(xhr) {
                mostrarAlerta('error', xhr.responseJSON?.message || 'Error de conexión');
                $('#btnSubir').prop('disabled', false).html('<i class="fas fa-upload me-1"></i>Subir');
            }
        });
    });

    $('#btnVistaTarjetas').addClass('active');
    $('#btnVistaTarjetas').on('click', function() { if (!$(this).hasClass('active')) cargarVista('cards'); });
    $('#btnVistaTabla').on('click', function() { if (!$(this).hasClass('active')) cargarVista('table'); });

    function cargarVista(vista) {
        const contenedor = $('#contenedorVistaDocumentos');
        contenedor.html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Cargando...</p></div>');
        $.ajax({
            url: '<?= base_url('vehiculos/documentos/' . $vehiculo['id']) ?>',
            type: 'GET',
            data: { vista: vista },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    contenedor.html(response.html);
                    if (vista === 'table') { $('#btnVistaTabla').addClass('active'); $('#btnVistaTarjetas').removeClass('active'); }
                    else { $('#btnVistaTarjetas').addClass('active'); $('#btnVistaTabla').removeClass('active'); }
                }
            },
            error: function() { mostrarAlerta('error', 'Error al cargar la vista'); }
        });
    }

    $('#btnConfirmarEliminar').on('click', function() {
        if (!documentoIdEliminar) return;
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Eliminando...');
        $.ajax({
            url: '<?= base_url('vehiculos/eliminarDocumento') ?>',
            type: 'POST',
            data: { id: documentoIdEliminar },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    mostrarAlerta('success', response.message);
                    $('#modalEliminarDocumento').modal('hide');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta('error', response.message || 'Error al eliminar');
                }
                $('#btnConfirmarEliminar').prop('disabled', false).html('<i class="fas fa-trash me-1"></i>Eliminar');
            },
            error: function(xhr) {
                mostrarAlerta('error', xhr.responseJSON?.message || 'Error de conexión');
                $('#btnConfirmarEliminar').prop('disabled', false).html('<i class="fas fa-trash me-1"></i>Eliminar');
            }
        });
    });

    $('#modalSubirDocumento').on('hidden.bs.modal', function() {
        $('#formSubirDocumento')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('#btnSubir').prop('disabled', false).html('<i class="fas fa-upload me-1"></i>Subir');
    });

    function mostrarAlerta(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const icon = tipo === 'success' ? 'check-circle' : 'exclamation-triangle';
        const alerta = `<div class="alert ${alertClass} alert-dismissible fade show rounded-3 shadow-sm" role="alert"><i class="fas fa-${icon} me-2"></i>${mensaje}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
        $('.container-fluid').prepend(alerta);
        $('html, body').animate({ scrollTop: 0 }, 300);
        if (tipo === 'error') setTimeout(() => $('.alert').fadeOut(), 5000);
    }
});
</script>
<?= $this->endSection() ?>
