<?= $this->extend('layouts/main') ?>

<?php $this->section('title') ?>
    <?= isset($solicitud) ? 'Agregar Historial - ' . $solicitud['codigo_consecutivo'] : 'Nuevo Registro de Historial' ?>
<?= $this->endSection() ?>

<?php $this->section('content') ?>
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
        <h1 class="h2">
            <i class="fas fa-history me-2"></i>
            <?= isset($solicitud) ? 'Cambio de Estado - ' . $solicitud['codigo_consecutivo'] : 'Nuevo Registro de Historial' ?>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= isset($solicitud) ? site_url('historial-orden-trabajo/create') : site_url('solicitudes') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <?php if (isset($solicitud)): ?>
    <div class="row">
        <!-- Panel izquierdo - Formulario -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-edit me-2"></i>Nuevo Registro de Historial
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('historial-orden-trabajo/store') ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_solicitud" value="<?= $solicitud['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">Nuevo Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="">Seleccionar estado...</option>
                                <?php
                                // Definir opciones válidas según el estado actual
                                $estadoActual = $solicitud['estado'] ?? '';
                                $opcionesValidas = [];

                                switch ($estadoActual) {
                                    case 'PENDIENTE':
                                        $opcionesValidas = ['EN_PROCESO', 'APROBADO'];
                                        break;
                                    case 'EN_PROCESO':
                                        $opcionesValidas = ['FINALIZADO', 'EN_PAUSA'];
                                        break;
                                    case 'EN_PAUSA':
                                        $opcionesValidas = ['EN_PROCESO'];
                                        break;
                                    case 'APROBADO':
                                        $opcionesValidas = ['EN_PROCESO', 'FINALIZADO'];
                                        break;
                                    case 'FINALIZADO':
                                        $opcionesValidas = []; // Estado final, no se puede cambiar
                                        break;
                                    default:
                                        $opcionesValidas = ['EN_PROCESO', 'APROBADO'];
                                }

                                // Mostrar opciones válidas
                                foreach ($opcionesValidas as $opcion) {
                                    $selected = (isset($_POST['estado']) && $_POST['estado'] === $opcion) ? 'selected' : '';
                                    echo "<option value=\"{$opcion}\" {$selected}>{$opcion}</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                Por favor seleccione un estado válido según el estado actual de la solicitud.
                            </div>
                            <?php if (empty($opcionesValidas)): ?>
                                <div class="alert alert-info mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Esta solicitud ya está FINALIZADA y no se puede cambiar su estado.
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Comentario <span class="text-danger">*</span></label>
                            <textarea name="comentario" id="comentario" rows="3" class="form-control" 
                                    placeholder="Ingrese un comentario sobre el cambio de estado" required></textarea>
                            <div class="invalid-feedback">
                                Por favor ingrese un comentario.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="referencia" class="form-label">Referencia (opcional)</label>
                            <input type="text" name="referencia" id="referencia" class="form-control" 
                                   placeholder="Número de factura, orden de compra, etc.">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            
        </div>
        
        <!-- Panel derecho - Historial -->
        <div class="col-lg-6">

        <!-- Información de la Solicitud -->
        <div class="card shadow mb-4">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información de la Solicitud
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <th class="text-nowrap" style="width: 120px;">Código:</th>
                                <td>
                                    <?= $solicitud['codigo_consecutivo'] ?>
                                    <button type="button" class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#buscarSolicitudModal">
                                        <i class="fas fa-search me-1"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th>Vehículo:</th>
                                <td>
                                    <?= $solicitud['placa'] ?? 'N/A' ?>
                                     <!-- Botón para abrir el modal de búsqueda -->
                                  
                                </td>
                            </tr>
                            <tr>
                                <th>Estado Actual:</th>
                                <td>
                                    <span class="badge bg-<?= getEstadoBadgeClass($solicitud['estado'] ?? '') ?>">
                                        <?= $solicitud['estado'] ?? 'SIN ESTADO' ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Descripción:</th>
                                <td><?= $solicitud['descripcion'] ?? 'Sin descripción' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

       

        <!-- Modal de búsqueda -->
        <div class="modal fade" id="buscarSolicitudModal" tabindex="-1" aria-labelledby="buscarSolicitudModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="buscarSolicitudModalLabel">
                            <i class="fas fa-search me-2"></i>Buscar Solicitud
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por código, placa o descripción...">
                            <button class="btn btn-primary" type="button" id="searchButton">
                                <i class="fas fa-search me-1"></i> Buscar
                            </button>
                        </div>
                        
                        <div id="searchResults" class="mt-3">
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <p>Ingrese un término de búsqueda para encontrar solicitudes</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
        <div class="card shadow mb-4">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Historial de Cambios
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($historial)): ?>
                        <div class="list-group list-group-flush" id="historial-container">
                            <?php foreach ($historial as $registro): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <span class="badge bg-<?= getEstadoBadgeClass($registro['estado']) ?>">
                                                <?= $registro['estado'] ?>
                                            </span>
                                        </h6>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($registro['fecha_registro'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= $registro['comentario'] ?: '<em class="text-muted">Sin comentarios</em>' ?></p>
                                    <?php if (!empty($registro['referencia'])): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-link me-1"></i><?= $registro['referencia'] ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-4">
                            <i class="fas fa-inbox fa-3x text-gray-400 mb-3"></i>
                            <p class="text-muted">No hay registros de historial para esta solicitud.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Vista de búsqueda de solicitudes -->
    
    <?php endif; ?>
</div>

<!-- Script para validación del formulario -->
<script>
// Validación de formulario
(function () {
    'use strict'
    
    var forms = document.querySelectorAll('.needs-validation')
    
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<!-- Script para búsqueda de solicitudes -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');
    const buscarSolicitudModal = document.getElementById('buscarSolicitudModal');
    
    // Función para buscar solicitudes
    function buscarSolicitudes(termino) {
        if (!termino || !termino.trim()) {
            searchResults.innerHTML = `
                <div class="text-center text-muted p-4">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <p>Por favor ingrese un término de búsqueda</p>
                </div>`;
            return;
        }
        
        // Mostrar carga
        searchResults.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Buscando...</span>
                </div>
                <p class="mt-2">Buscando solicitudes...</p>
            </div>`;
            
        // Realizar la búsqueda
        fetch(`<?= site_url('solicitudes/buscar') ?>?q=${encodeURIComponent(termino)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.length > 0) {
                    let html = '<div class="list-group">';
                    data.data.forEach(solicitud => {
                        html += `
                        <a href="${solicitud.id}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">${solicitud.codigo_consecutivo}</h6>
                                <small>${solicitud.estado || 'Sin estado'}</small>
                            </div>
                            <p class="mb-1">${solicitud.descripcion || 'Sin descripción'}</p>
                            <small>Vehículo: ${solicitud.placa || 'N/A'}</small>
                        </a>`;
                    });
                    html += '</div>';
                    searchResults.innerHTML = html;
                    
                    // Agregar evento de clic a los resultados
                    document.querySelectorAll('#searchResults a').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            window.location.href = `<?= site_url('historial-orden-trabajo/create/') ?>${this.getAttribute('href')}`;
                        });
                    });
                } else {
                    searchResults.innerHTML = `
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-search fa-2x mb-2"></i>
                        <p>No se encontraron resultados para "${termino}"</p>
                    </div>`;
                }
            })
            .catch(error => {
                console.error('Error al buscar solicitudes:', error);
                searchResults.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Ocurrió un error al buscar las solicitudes. Por favor, intente nuevamente.
                </div>`;
            });
    }
    
    // Event listeners
    if (searchButton) {
        searchButton.addEventListener('click', () => buscarSolicitudes(searchInput.value));
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                buscarSolicitudes(searchInput.value);
            }
        });
    }
    
    // Limpiar búsqueda al cerrar el modal
    if (buscarSolicitudModal) {
        buscarSolicitudModal.addEventListener('hidden.bs.modal', function () {
            searchInput.value = '';
            searchResults.innerHTML = `
                <div class="text-center text-muted p-4">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <p>Ingrese un término de búsqueda para encontrar solicitudes</p>
                </div>`;
        });
    }
});
</script>

<?php $this->endSection() ?>
