<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    <?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url() ?>">Inicio</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('conductores') ?>">Conductores</a>
                        </li>
                        <li class="breadcrumb-item active"><?= $page_title ?></li>
                    </ol>
                </div>
                <h4 class="page-title"><?= $page_title ?></h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="header-title">Documentos del Conductor</h4>
                            <p class="text-muted">Gestión de documentos de <?= esc($conductor['nombre']) ?> <?= esc($conductor['apellido']) ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarDocumento">
                                <i class="mdi mdi-plus-circle-outline me-1"></i> Agregar Documento
                            </button>
                        </div>
                        <div class="btn-group" role="group" aria-label="Vista de documentos">
                            <input type="radio" class="btn-check" name="vistaDocumentos" id="vistaTarjetas" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="vistaTarjetas" title="Vista de tarjetas">
                                <i class="mdi mdi-view-grid-outline"></i>
                            </label>
                            
                            <input type="radio" class="btn-check" name="vistaDocumentos" id="vistaTabla" autocomplete="off">
                            <label class="btn btn-outline-primary" for="vistaTabla" title="Vista de tabla">
                                <i class="mdi mdi-table"></i>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Contenedor para las vistas -->
                    <div id="contenedorVista">
                        <?php if (isset($_GET['vista']) && $_GET['vista'] === 'tabla'): ?>
                            <?= view('conductores/partials/documentos_table', ['documentos' => $documentos]) ?>
                        <?php else: ?>
                            <?= view('conductores/partials/documentos_cards', ['documentos' => $documentos]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Documento -->
<div class="modal fade" id="modalEditarDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarDocumento" action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="idConductor" value="<?= $conductor['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                        <select class="form-select" name="tipo_documento" id="edit_tipo_documento" required>
                            <option value="">Seleccione un tipo...</option>
                            <?php foreach ($tiposDocumento as $tipo): ?>
                                <option value="<?= $tipo['id'] ?>"><?= esc($tipo['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Seleccione un tipo de documento.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Número de Documento</label>
                        <input type="text" class="form-control" name="numero_documento" id="edit_numero_documento">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Emisión</label>
                            <input type="date" class="form-control" name="fecha_emision" id="edit_fecha_emision">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Vencimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_vencimiento" id="edit_fecha_vencimiento" required>
                            <div class="invalid-feedback">Ingrese la fecha de vencimiento.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reemplazar Archivo <small class="text-muted">(opcional)</small></label>
                        <input class="form-control" type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Formatos: PDF, JPG, PNG (Máx. 5MB). Dejar vacío para mantener el actual.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notificación previa</label>
                        <div class="input-group">
                            <input type="number" min="0" class="form-control" name="notificacion" id="edit_notificacion" placeholder="Ej: 7">
                            <span class="input-group-text">días</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="edit_observaciones" rows="2"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Documento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Documento -->
<div class="modal fade" id="modalAgregarDocumento" tabindex="-1" aria-labelledby="modalAgregarDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarDocumentoLabel">Agregar Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <form action="<?= base_url('conductores/guardarDocumento') ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="idConductor" value="<?= $conductor['id'] ?>">
            
            <div class="mb-3">
                <label for="tipo_documento" class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                    <option value="">Seleccione un tipo...</option>
                    <?php foreach ($tiposDocumento as $tipo): ?>
                        <option value="<?= $tipo['id'] ?>"><?= esc($tipo['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    Por favor seleccione un tipo de documento.
                </div>
            </div>
            
            <div class="mb-3">
                <label for="numero_documento" class="form-label">Número de Documento</label>
                <input type="text" class="form-control" id="numero_documento" name="numero_documento">
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha_emision" class="form-label">Fecha de Emisión</label>
                    <input type="date" class="form-control" id="fecha_emision" name="fecha_emision">
                </div>
                <div class="col-md-6">
                    <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                    <div class="invalid-feedback">
                        Por favor ingrese la fecha de vencimiento.
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="archivo" class="form-label">Archivo <span class="text-danger">*</span></label>
                <input class="form-control" type="file" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required>
                <div class="form-text">Formatos aceptados: PDF, JPG, PNG (Máx. 5MB)</div>
                <div class="invalid-feedback">
                    Por favor seleccione un archivo.
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notificacion" class="form-label">Notificación previa</label>
                <div class="input-group">
                    <input type="number" min="0" class="form-control" id="notificacion" name="notificacion" placeholder="Ej: 7">
                    <span class="input-group-text">días</span>
                </div>
                <div class="form-text">Cantidad de días antes del vencimiento para generar la alerta</div>
                <div class="invalid-feedback">
                    Por favor ingrese un número válido de días.
                </div>
            </div>
            
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Documento
                </button>
            </div>
        </form>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Plugins js -->
<script src="<?= base_url('assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        $('#tablaDocumentos').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [[3, 'desc']]
        });

        // Validación del formulario
        const form = document.querySelector('form.needs-validation');
            
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add('was-validated');
                } else {
                    // Mostrar mensaje de carga
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
                    
                    // Restaurar el botón después de 5 segundos por si hay un error
                    setTimeout(() => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    }, 5000);
                }
            }, false);

        // Eliminar documento
        $(document).on('click', '.btn-eliminar-documento', function() {
            const idDocumento = $(this).data('id');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('conductores/eliminarDocumento/') ?>' + idDocumento,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: response.message || 'Ocurrió un error al eliminar el documento',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la solicitud',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }
            });
        });

        // Limpiar formulario al cerrar el modal
        $('#modalAgregarDocumento').on('hidden.bs.modal', function () {
            document.getElementById('formAgregarDocumento').reset();
        });

        // Editar documento - cargar datos en el modal
        window.editarDocumento = function(id) {
            fetch('<?= base_url('conductores/editar-documento/') ?>' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(res => {
                if (!res.success) {
                    Swal.fire('Error', res.message || 'No se pudo cargar el documento', 'error');
                    return;
                }
                const d = res.data;
                document.getElementById('edit_tipo_documento').value = d.tipo_documento || '';
                document.getElementById('edit_numero_documento').value = d.numero_documento || '';
                document.getElementById('edit_fecha_emision').value = (d.fecha_emision && d.fecha_emision !== '0000-00-00') ? d.fecha_emision : '';
                document.getElementById('edit_fecha_vencimiento').value = (d.fecha_vencimiento && d.fecha_vencimiento !== '0000-00-00') ? d.fecha_vencimiento : '';
                document.getElementById('edit_notificacion').value = d.notificacion || '';
                document.getElementById('edit_observaciones').value = d.observaciones || '';
                document.getElementById('formEditarDocumento').action = '<?= base_url('conductores/actualizar-documento/') ?>' + id;
                new bootstrap.Modal(document.getElementById('modalEditarDocumento')).show();
            })
            .catch(() => Swal.fire('Error', 'Error de conexión', 'error'));
        };

        // Confirmar eliminación
        window.confirmarEliminarDocumento = function(id, nombre) {
            Swal.fire({
                title: '¿Eliminar documento?',
                text: 'Se eliminará: ' + nombre,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('conductores/eliminarDocumento/') ?>' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('¡Eliminado!', response.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error', response.message || 'Error', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Error de conexión', 'error');
                        }
                    });
                }
            });
        };
    });

    // Manejar cambio de vista entre tarjetas y tabla
    document.addEventListener('DOMContentLoaded', function() {
        const vistaTarjetas = document.getElementById('vistaTarjetas');
        const vistaTabla = document.getElementById('vistaTabla');
        const contenedorVista = document.getElementById('contenedorVista');
        
        // Cargar la vista guardada en localStorage o usar 'cards' por defecto
        const vistaGuardada = localStorage.getItem('vistaDocumentos') || 'cards';
        
        // Establecer el radio button correspondiente
        if (vistaGuardada === 'table') {
            vistaTabla.checked = true;
            cargarVista('table');
        } else {
            vistaTarjetas.checked = true;
            cargarVista('cards');
        }
        
        // Manejar cambios en los radio buttons
        vistaTarjetas.addEventListener('change', function() {
            if (this.checked) {
                cargarVista('cards');
            }
        });
        
        vistaTabla.addEventListener('change', function() {
            if (this.checked) {
                cargarVista('table');
            }
        });
        
        // Función para cargar la vista seleccionada
        function cargarVista(tipo) {
            // Guardar preferencia
            localStorage.setItem('vistaDocumentos', tipo);
            
            // Mostrar loader
            contenedorVista.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando vista ${tipo === 'cards' ? 'de tarjetas' : 'de tabla'}...</p>
                </div>`;
            
            // Cargar la vista correspondiente vía AJAX
            const url = new URL(window.location.href);
            url.searchParams.set('vista', tipo);
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Si hay un error HTTP, intentar obtener más detalles del error
                    return response.text().then(text => {
                        try {
                            // Intentar analizar como JSON
                            const json = JSON.parse(text);
                            throw new Error(json.message || json.error || `Error HTTP ${response.status}`);
                        } catch (e) {
                            // Si no es JSON válido, devolver el texto del error
                            throw new Error(`Error HTTP ${response.status}: ${text}`);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success && data.html) {
                    contenedorVista.innerHTML = data.html;
                } else if (data && data.error) {
                    throw new Error(data.error);
                } else {
                    throw new Error('La respuesta del servidor no es válida');
                }
            })
            .catch(error => {
                console.error('Error al cargar la vista:', error);
                let errorMessage = error.message || 'Error al cargar la vista. Por favor, recarga la página.';
                
                // Mostrar mensaje de error más detallado
                contenedorVista.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>
                        ${errorMessage}
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                                <i class="mdi mdi-refresh me-1"></i> Recargar Página
                            </button>
                        </div>
                    </div>`;
            });
        }
    });
</script>
<?= $this->endSection() ?>
