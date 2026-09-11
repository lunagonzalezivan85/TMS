<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>

    <!-- Mensajes de éxito/error -->
    <div id="alert-container"></div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary" id="form-title">Nuevo Tipo de Problema</h6>
                </div>
                <div class="card-body">
                    <form id="tipo-problema-form">
                        <input type="hidden" name="id" id="tipo_id">
                        
                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group" id="estado-container">
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="ACTIVO">Activo</option>
                                <option value="INACTIVO">Inactivo</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary" id="btn-submit">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <button type="button" class="btn btn-secondary" id="btn-cancelar" style="display: none;">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Tipos de Problema</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabla-tipos" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tipos as $tipo): ?>
                                <tr id="tipo-<?= $tipo['id'] ?>">
                                    <td><?= $tipo['id'] ?></td>
                                    <td><?= esc($tipo['nombre']) ?></td>
                                    <td>
                                        <span class="badge <?= $tipo['estado'] == 'ACTIVO' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $tipo['estado'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-editar" 
                                                data-id="<?= $tipo['id'] ?>"
                                                data-nombre="<?= esc($tipo['nombre']) ?>"
                                                data-estado="<?= $tipo['estado'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-eliminar" 
                                                data-id="<?= $tipo['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const form = $('#tipo-problema-form');
    const btnSubmit = $('#btn-submit');
    const btnCancelar = $('#btn-cancelar');
    const formTitle = $('#form-title');
    const tipoId = $('#tipo_id');
    const estadoContainer = $('#estado-container');
    
    // Configuración de DataTables que será llamada cuando estén listas las dependencias
    window.initDataTable = function() {
        if ($.fn.DataTable.isDataTable('#tabla-tipos')) {
            $('#tabla-tipos').DataTable().destroy();
        }
        
        $('#tabla-tipos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            order: [[0, 'desc']],
            responsive: true,
            autoWidth: false,
            processing: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });
    };
    
    // Si DataTables ya está cargado, inicializarlo de inmediato
    if (typeof $.fn.DataTable !== 'undefined') {
        window.initDataTable();
    }

    // Enviar formulario (crear/actualizar)
    form.on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const baseUrl = '<?= base_url() ?>';
        const url = tipoId.val() ? `${baseUrl}tipos-problema/update/${tipoId.val()}` : `${baseUrl}tipos-problema/store`;
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', '¡Éxito!', response.message);
                    resetForm();
                    location.reload();
                } else {
                    showAlert('danger', 'Error', response.message);
                    if (response.errors) {
                        $.each(response.errors, function(field, error) {
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}`).next('.invalid-feedback').text(error);
                        });
                    }
                }
            },
            error: function(xhr) {
                showAlert('danger', 'Error', 'Ocurrió un error al procesar la solicitud.');
                console.error(xhr.responseText);
            }
        });
    });

    // Editar tipo de problema
    $(document).on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const estado = $(this).data('estado');
        
        tipoId.val(id);
        $('#nombre').val(nombre);
        $('#estado').val(estado);
        
        formTitle.text('Editar Tipo de Problema');
        btnSubmit.html('<i class="fas fa-save"></i> Actualizar');
        btnCancelar.show();
        estadoContainer.show();
        
        $('html, body').animate({
            scrollTop: form.offset().top - 20
        }, 500);
    });

    // Cancelar edición
    btnCancelar.on('click', function() {
        resetForm();
    });

    // Eliminar tipo de problema
    $(document).on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');
        const baseUrl = '<?= base_url() ?>';
        
        if (confirm('¿Está seguro de eliminar este tipo de problema?')) {
            $.ajax({
                url: `${baseUrl}tipos-problema/delete/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', '¡Éxito!', response.message);
                        $(`#tipo-${id}`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        showAlert('danger', 'Error', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('danger', 'Error', 'Ocurrió un error al eliminar el tipo de problema.');
                    console.error(xhr.responseText);
                }
            });
        }
    });

    // Función para mostrar alertas
    function showAlert(type, title, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <strong>${title}</strong> ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        $('#alert-container').html(alertHtml);
        
        // Ocultar la alerta después de 5 segundos
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }

    // Función para reiniciar el formulario
    function resetForm() {
        form[0].reset();
        tipoId.val('');
        formTitle.text('Nuevo Tipo de Problema');
        btnSubmit.html('<i class="fas fa-save"></i> Guardar');
        btnCancelar.hide();
        estadoContainer.hide();
        $('.is-invalid').removeClass('is-invalid');
    }
    
    // Inicializar el formulario
    resetForm();
});
</script>
<?= $this->endSection() ?>
