<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    <?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
        <h1 class="h2">
            <i class="fas fa-tools me-2"></i><?= $title ?>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('solicitudes/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nueva Solicitud
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i> Filtros de Búsqueda
        </div>
        <div class="card-body">
            <form id="filtroForm" class="row g-3">
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <?php foreach ($estados as $key => $value): ?>
                            <option value="<?= $key ?>" <?= ($filtros['estado'] == $key) ? 'selected' : '' ?>>
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" 
                           value="<?= $filtros['fecha_desde'] ?>">
                </div>
                <div class="col-md-3">
                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"
                           value="<?= $filtros['fecha_hasta'] ?>">
                </div>
                <div class="col-md-3">
                    <label for="id_vehiculo" class="form-label">Vehículo</label>
                    <select name="id_vehiculo" id="id_vehiculo" class="form-select">
                        <option value="">Todos los vehículos</option>
                        <?php foreach ($vehiculos as $vehiculo): ?>
                            <option value="<?= $vehiculo['id'] ?>" 
                                <?= ($filtros['id_vehiculo'] == $vehiculo['id']) ? 'selected' : '' ?>>
                                <?= $vehiculo['placa'] ?: 'Sin placa' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                    <button type="button" id="btnLimpiar" class="btn btn-outline-secondary">
                        <i class="fas fa-eraser me-1"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de solicitudes -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaSolicitudes" class="table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th data-data="codigo_consecutivo">Código</th>
                            <th data-data="fecha_solicitud">Fecha Solicitud</th>
                            <th data-data="placa">Vehículo</th>
                            <th data-data="solicitante">Solicitante</th>
                            <th data-data="tipo_problema">Tipo de Problema</th>
                            <th data-data="descripcion">Descripción</th>
                            <th data-data="estado">Estado</th>
                            <th data-data="acciones" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargarán mediante AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCambiarEstado" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCambiarEstadoLabel">Cambiar Estado de la Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="solicitud_id" name="solicitud_id">
                    <div class="mb-3">
                        <label for="nuevo_estado" class="form-label">Nuevo Estado</label>
                        <select class="form-select" id="nuevo_estado" name="estado" required>
                            <option value="">Seleccione un estado</option>
                            <option value="EN_PROCESO">En Proceso</option>
                            <option value="CERRADA">Cerrar</option>
                            <option value="CANCELADA">Cancelar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentario (Opcional)</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        var tablaSolicitudes = $('#tablaSolicitudes').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '<?= base_url('solicitudes/getData') ?>',
                type: 'POST',
                data: function(d) {
                    d.estado = $('#estado').val();
                    d.fecha_desde = $('#fecha_desde').val();
                    d.fecha_hasta = $('#fecha_hasta').val();
                    d.id_vehiculo = $('#id_vehiculo').val();
                    d.search = $('input[type="search"]').val();
                }
            },
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [[1, 'desc']], // Ordenar por fecha de solicitud por defecto
            pageLength: 10,
            drawCallback: function(settings) {
                // Inicializar tooltips de Bootstrap
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        // Aplicar filtros
        $('#filtroForm').on('submit', function(e) {
            e.preventDefault();
            tablaSolicitudes.ajax.reload();
        });

        // Limpiar filtros
        $('#btnLimpiar').click(function() {
            $('#filtroForm')[0].reset();
            tablaSolicitudes.ajax.reload();
        });

        // Manejar cambio de estado
        $('#formCambiarEstado').on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            var solicitudId = $('#solicitud_id').val();
            
            $.ajax({
                url: '<?= base_url('solicitudes/updateStatus/') ?>' + solicitudId,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        
                        // Cerrar el modal y actualizar la tabla
                        $('#modalCambiarEstado').modal('hide');
                        tablaSolicitudes.ajax.reload();
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Ocurrió un error al actualizar el estado.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la solicitud. Por favor intente nuevamente.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });

        // Abrir modal para cambiar estado
        $(document).on('click', '.btn-cambiar-estado', function() {
            var solicitudId = $(this).data('id');
            var estadoActual = $(this).data('estado');
            
            $('#solicitud_id').val(solicitudId);
            
            // Configurar opciones de estado según el estado actual
            var $selectEstado = $('#nuevo_estado');
            $selectEstado.empty();
            
            // Agregar opciones según el estado actual
            switch(estadoActual) {
                case 'PENDIENTE':
                    $selectEstado.append('<option value="">Seleccione un estado</option>');
                    $selectEstado.append('<option value="EN_PROCESO">En Proceso</option>');
                    $selectEstado.append('<option value="CANCELADA">Cancelar</option>');
                    break;
                    
                case 'EN_PROCESO':
                    $selectEstado.append('<option value="">Seleccione un estado</option>');
                    $selectEstado.append('<option value="CERRADA">Cerrar</option>');
                    $selectEstado.append('<option value="CANCELADA">Cancelar</option>');
                    break;
                    
                default:
                    $selectEstado.append('<option value="">No hay acciones disponibles</option>');
            }
            
            $('#modalCambiarEstado').modal('show');
        });
    });

    // Función para formatear fechas
    function formatDate(dateString) {
        if (!dateString) return '';
        
        const options = { 
            year: 'numeric', 
            month: '2-digit', 
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        };
        
        return new Date(dateString).toLocaleDateString('es-ES', options);
    }
</script>
<?= $this->endSection() ?>
