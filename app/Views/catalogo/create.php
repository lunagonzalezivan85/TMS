<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('catalogo') ?>">Catálogo</a></li>
                    <?php if (isset($catalogo_padre)): ?>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('catalogo/show/' . $catalogo_padre['id']) ?>">
                                <?= esc($catalogo_padre['codigo']) ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Nuevo Subcatálogo</li>
                    <?php else: ?>
                        <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-plus me-2"></i><?= $title ?>
                </h1>
                <a href="<?= base_url('catalogo') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Información del Catálogo
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Error:</strong> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('catalogo/store') ?>" method="post" id="formCatalogo">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Código 
                                        <small class="text-muted">(Se genera automáticamente)</small>
                                    </label>
                                    
                                    <?php if (isset($catalogo_padre)): ?>
                                        <!-- Mostrar código que heredará del padre -->
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fas fa-magic me-1"></i>
                                                <?= esc($catalogo_padre['codigo']) ?>
                                            </span>
                                            <input type="text" 
                                                   class="form-control bg-light" 
                                                   value="(Heredado del padre)" 
                                                   readonly>
                                        </div>
                                        <div class="form-text text-success">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Este subcatálogo heredará el código: <strong><?= esc($catalogo_padre['codigo']) ?></strong>
                                        </div>
                                    <?php else: ?>
                                        <!-- Mostrar formato de código que se generará -->
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="fas fa-magic me-1"></i>
                                                CAT-
                                            </span>
                                            <input type="text" 
                                                   class="form-control bg-light" 
                                                   value="Se generará automáticamente" 
                                                   readonly>
                                        </div>
                                        <div class="form-text text-primary">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Se generará automáticamente con formato: <strong>CAT-0001, CAT-0002, etc.</strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">
                                        Estado <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select <?= session()->getFlashdata('errors')['estado'] ?? false ? 'is-invalid' : '' ?>" 
                                            id="estado" 
                                            name="estado" 
                                            required>
                                        <option value="">Seleccionar estado</option>
                                        <option value="1" <?= old('estado') == '1' ? 'selected' : '' ?>>ACTIVO</option>
                                        <option value="0" <?= old('estado') == '0' ? 'selected' : '' ?>>INACTIVO</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['estado'] ?? '' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= session()->getFlashdata('errors')['nombre'] ?? false ? 'is-invalid' : '' ?>" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?= old('nombre') ?>" 
                                   required 
                                   maxlength="255">
                            <div class="invalid-feedback">
                                <?= session()->getFlashdata('errors')['nombre'] ?? '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control <?= session()->getFlashdata('errors')['descripcion'] ?? false ? 'is-invalid' : '' ?>" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3" 
                                      maxlength="500"><?= old('descripcion') ?></textarea>
                            <div class="invalid-feedback">
                                <?= session()->getFlashdata('errors')['descripcion'] ?? '' ?>
                            </div>
                            <div class="form-text">Descripción opcional del catálogo</div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="id_superior" class="form-label">Catálogo Superior</label>
                                    
                                    <?php if (isset($catalogo_padre)): ?>
                                        <!-- Campo bloqueado para subcatálogo -->
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control bg-light" 
                                                   value="<?= esc($catalogo_padre['codigo'] . ' - ' . $catalogo_padre['nombre']) ?>" 
                                                   readonly>
                                            <span class="input-group-text">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="id_superior" value="<?= $catalogo_padre['id'] ?>">
                                        <div class="form-text text-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Este será un subcatálogo de: <strong><?= esc($catalogo_padre['nombre']) ?></strong>
                                        </div>
                                    <?php else: ?>
                                        <!-- Campo normal para seleccionar padre -->
                                        <select class="form-select <?= session()->getFlashdata('errors')['id_superior'] ?? false ? 'is-invalid' : '' ?>" 
                                                id="id_superior" 
                                                name="id_superior">
                                            <option value="">Sin catálogo superior (Raíz)</option>
                                            <?php if (empty($catalogos_padre)): ?>
                                                <option value="" disabled>No hay catálogos disponibles</option>
                                            <?php else: ?>
                                                <?php foreach ($catalogos_padre as $padre): ?>
                                                    <option value="<?= $padre['id'] ?>" 
                                                            <?= old('id_superior', $id_superior_preseleccionado ?? '') == $padre['id'] ? 'selected' : '' ?>>
                                                        <?= str_repeat('└─ ', ($padre['nivel'] ?? 1) - 1) ?>
                                                        <?= esc($padre['codigo'] . ' - ' . $padre['nombre']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Selecciona un catálogo padre si es subcatálogo</div>
                                    <?php endif; ?>
                                    
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['id_superior'] ?? '' ?>
                                    </div>
                                </div>
                            </div>
                           
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="referencia" class="form-label">Referencia</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control <?= session()->getFlashdata('errors')['referencia'] ?? false ? 'is-invalid' : '' ?>" 
                                               id="referencia" 
                                               name="referencia" 
                                               value="<?= old('referencia') ?>"
                                               placeholder="Seleccionar referencia"
                                               >
                                        <input type="hidden" id="referencia_id" name="referencia_id" value="<?= old('referencia_id') ?>">
                                        <input type="hidden" id="tabla_referencia" name="tabla_referencia" value="<?= old('tabla_referencia') ?>">
                                        <!--<button class="btn btn-outline-secondary" type="button" id="btnBuscarTabla">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>-->
                                    </div>
                                    
                                    <?php if (session()->getFlashdata('errors')['referencia'] ?? false): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= session()->getFlashdata('errors')['referencia'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="referencia2" class="form-label">Referencia 2</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control <?= session()->getFlashdata('errors')['referencia2'] ?? false ? 'is-invalid' : '' ?>" 
                                               id="referencia2" 
                                               name="referencia2" 
                                               value="<?= old('referencia2') ?>"
                                               placeholder="Seleccionar referencia 2"
                                               >
                                        <!--<button class="btn btn-outline-secondary" type="button" id="btnBuscarReferencia" disabled>
                                            <i class="fas fa-search"></i> Buscar
                                        </button>-->
                                    </div>
                                    <?php if (session()->getFlashdata('errors')['referencia2'] ?? false): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= session()->getFlashdata('errors')['referencia2'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edicion" class="form-label">Edición</label>
                                    <input type="number" 
                                           class="form-control <?= session()->getFlashdata('errors')['edicion'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="edicion" 
                                           name="edicion" 
                                           value="<?= old('edicion') ?>" 
                                           min="0">
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['edicion'] ?? '' ?>
                                    </div>
                                    <div class="form-text">Número de edición opcional</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('catalogo') ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnGuardar">
                                <i class="fas fa-save me-2"></i>Guardar Catálogo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de ayuda -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información
                    </h5>
                </div>
                <div class="card-body">
                    <h6><i class="fas fa-lightbulb me-1"></i>Consejos</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>El código debe ser único en el sistema</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>El nivel se calcula automáticamente según el catálogo superior</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Los campos marcados con (*) son obligatorios</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Puedes crear una jerarquía seleccionando un catálogo superior</small>
                        </li>
                    </ul>

                    <hr>

                    <h6><i class="fas fa-sitemap me-1"></i>Jerarquía</h6>
                    <p class="small text-muted">
                        Los catálogos pueden organizarse en una estructura jerárquica. 
                        Si seleccionas un catálogo superior, este elemento será un subcatálogo.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para buscar tablas -->
<div class="modal fade" id="modalBuscarTabla" tabindex="-1" aria-labelledby="modalBuscarTablaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalBuscarTablaLabel">
                    <i class="fas fa-table me-2"></i>Seleccionar Tabla
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="buscarTabla" placeholder="Buscar tabla...">
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tablaTablas">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre de la Tabla</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablas">
                            <!-- Las tablas se cargarán aquí por AJAX -->
                            <tr>
                                <td colspan="2" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <nav aria-label="Paginación de tablas">
                    <ul class="pagination justify-content-center" id="paginacionTablas">
                        <!-- La paginación se generará por JavaScript -->
                    </ul>
                </nav>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para buscar referencias -->
<div class="modal fade" id="modalBuscarReferencia" tabindex="-1" aria-labelledby="modalBuscarReferenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalBuscarReferenciaLabel">
                    <i class="fas fa-search me-2"></i>Buscar Referencia en: <span id="nombreTablaSeleccionada"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="buscarReferencia" placeholder="Buscar referencia...">
                            <button class="btn btn-primary" type="button" id="btnAplicarBusqueda">
                                <i class="fas fa-search me-1"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tablaReferencias">
                        <thead class="table-light">
                            <tr id="encabezadoReferencias">
                                <!-- Las columnas se generarán dinámicamente -->
                                <th>Cargando columnas...</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoReferencias">
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <nav aria-label="Paginación de referencias">
                    <ul class="pagination justify-content-center" id="paginacionReferencias">
                        <!-- La paginación se generará por JavaScript -->
                    </ul>
                </nav>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnSeleccionarReferencia" disabled>
                    <i class="fas fa-check me-1"></i> Seleccionar
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Usar delegación de eventos para la paginación
document.addEventListener('click', function(e) {
    if (e.target.closest('.page-link')) {
        manejarCambioPagina(e);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let tablaSeleccionada = '';
    let referenciaSeleccionada = null;
    let paginaActualTablas = 1;
    let modalBuscarTabla;
    let paginaActualReferencias = 1;
    let busquedaTabla = '';
    let busquedaReferencia = '';
    
    // Elementos del DOM
    const btnBuscarTabla = document.getElementById('btnBuscarTabla');
    const btnBuscarReferencia = document.getElementById('btnBuscarReferencia');
    const modalBuscarReferencia = new bootstrap.Modal(document.getElementById('modalBuscarReferencia'));
    const inputTablaReferencia = document.getElementById('tabla_referencia');
    const inputReferenciaId = document.getElementById('referencia_id');
    const inputReferencia = document.getElementById('referencia');
    const inputReferencia2 = document.getElementById('referencia2');
    
    // Inicializar el modal
    const modalElement = document.getElementById('modalBuscarTabla');
    if (modalElement) {
        modalBuscarTabla = new bootstrap.Modal(modalElement);
        
        // 1. Manejar la búsqueda de tablas
        const buscarTablaInput = document.getElementById('buscarTabla');
        if (buscarTablaInput) {
            buscarTablaInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    busquedaTabla = this.value.trim();
                    cargarTablas(1, busquedaTabla);
                }
            });
        }

        // Botón de búsqueda
        const btnBuscarTabla = document.getElementById('btnBuscarTabla');
        if (btnBuscarTabla) {
            btnBuscarTabla.addEventListener('click', function() {
                const buscarInput = document.getElementById('buscarTabla');
                if (buscarInput) {
                    busquedaTabla = buscarInput.value.trim();
                    cargarTablas(1, busquedaTabla);
                }
            });
        }

        // Limpiar búsqueda
        const btnLimpiarBusqueda = document.getElementById('btnLimpiarBusqueda');
        if (btnLimpiarBusqueda) {
            btnLimpiarBusqueda.addEventListener('click', function() {
                const buscarInput = document.getElementById('buscarTabla');
                if (buscarInput) {
                    buscarInput.value = '';
                    busquedaTabla = '';
                    cargarTablas(1, '');
                }
            });
        }

        // Cargar tablas cuando se muestra el modal
        modalElement.addEventListener('shown.bs.modal', function() {
            cargarTablas(paginaActualTablas, busquedaTabla);
        });
    }
    
    // Botón para abrir el modal de búsqueda de tablas
    const btnAbrirModalTablas = document.getElementById('btnBuscarTabla');
    if (btnAbrirModalTablas) {
        btnAbrirModalTablas.addEventListener('click', function(e) {
            e.preventDefault();
            if (modalBuscarTabla) {
                cargarTablas();
                modalBuscarTabla.show();
            }
        });
    }
    
    // 2. Renderizar las tablas en la vista
    function renderizarTablas(tablas, contenedor) {
        if (!tablas || tablas.length === 0) {
            contenedor.innerHTML = `
                <tr>
                    <td colspan="2" class="text-center">No se encontraron tablas</td>
                </tr>`;
            return;
        }
        
        let html = '';
        tablas.forEach(tabla => {
            html += `
                <tr>
                    <td>${tabla}</td>
                    <td>
                        <button class="btn btn-sm btn-primary btn-seleccionar-tabla" data-tabla="${tabla}">
                            <i class="fas fa-check me-1"></i> Seleccionar
                        </button>
                    </td>
                </tr>`;
        });
        
        contenedor.innerHTML = html;
    }
    
    // 3. Actualizar la paginación
    function actualizarPaginacion(elemento, paginaActual, totalPaginas, tipo) {
        if (!elemento) return;
        
        let html = '';
        
        // Botón Anterior
        html += `
            <li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${paginaActual - 1}" data-tipo="${tipo}" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
            
        // Calcular rango de páginas a mostrar
        const startPage = Math.max(1, paginaActual - 2);
        const endPage = Math.min(totalPaginas, paginaActual + 2);
        
        // Mostrar primera página si no está en el rango
        if (startPage > 1) {
            html += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1" data-tipo="${tipo}">1</a>
                </li>`;
            if (startPage > 2) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Mostrar páginas en el rango
        for (let i = startPage; i <= endPage; i++) {
            html += `
                <li class="page-item ${i === paginaActual ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}" data-tipo="${tipo}">${i}</a>
                </li>`;
        }
        
        // Mostrar última página si no está en el rango
        if (endPage < totalPaginas) {
            if (endPage < totalPaginas - 1) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            html += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${totalPaginas}" data-tipo="${tipo}">${totalPaginas}</a>
                </li>`;
        }
        
        // Botón Siguiente
        html += `
            <li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${paginaActual + 1}" data-tipo="${tipo}" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
        
        elemento.innerHTML = html;
        
        // No necesitamos agregar manejadores de eventos aquí ya que usamos delegación de eventos
    }
    
    // 4. Manejar la búsqueda de referencias
    if (btnBuscarReferencia) {
        btnBuscarReferencia.addEventListener('click', function() {
            const tabla = inputTablaReferencia.value.trim();
            if (tabla) {
                tablaSeleccionada = tabla;
                cargarReferencias();
                modalBuscarReferencia.show();
            } else {
                mostrarAlerta('Por favor, seleccione primero una tabla de referencia', 'warning');
            }
        });
    }
    
    // 3. Habilitar/deshabilitar botón de búsqueda de referencias
    if (inputTablaReferencia) {
        inputTablaReferencia.addEventListener('change', function() {
            const habilitar = this.value.trim() !== '';
            btnBuscarReferencia.disabled = !habilitar;
            
            if (!habilitar) {
                inputReferenciaId.value = '';
                inputReferenciaTexto.value = '';
            }
        });
    }
    
    // Definir baseUrl al inicio del script
    const baseUrl = '<?= base_url() ?>';
    
    // 4. Cargar lista de tablas
    function cargarTablas(pagina = 1, buscar = '') {
        const cuerpoTablas = document.getElementById('cuerpoTablas');
        const paginacionTablas = document.getElementById('paginacionTablas');
        
        // Mostrar carga
        cuerpoTablas.innerHTML = `
            <tr>
                <td colspan="2" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando tablas...</span>
                    </div>
                </td>
            </tr>`;
        
        // Realizar la petición AJAX
        fetch(`<?= base_url('mantenimiento-tablas/obtener-tablas') ?>?pagina=${pagina}&buscar=${encodeURIComponent(buscar)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    // Actualizar la lista de tablas
                    renderizarTablas(data.data, cuerpoTablas);
                    
                    // Actualizar la paginación
                    actualizarPaginacion(data.pagination, 'tablas');
                    
                    // Actualizar el número de página actual
                    paginaActualTablas = data.pagination.current_page;
                } else {
                    throw new Error('No se pudieron cargar las tablas');
                }
            })
            .catch(error => {
                console.error('Error al cargar las tablas:', error);
                cuerpoTablas.innerHTML = `
                    <tr>
                        <td colspan="2" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar las tablas. Intente nuevamente.
                        </td>
                    </tr>`;
            });
        cuerpoTablas.innerHTML = `
            <tr>
                <td colspan="2" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </td>
            </tr>`;
        
        // Hacer petición AJAX
        fetch(`${baseUrl}/mantenimiento-tablas/obtener-tablas?buscar=${encodeURIComponent(buscar)}&pagina=${pagina}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar lista de tablas
                    if (data.data.length > 0) {
                        let html = '';
                        data.data.forEach(tabla => {
                            html += `
                                <tr>
                                    <td>${tabla}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-seleccionar-tabla" 
                                                data-tabla="${tabla}">
                                            <i class="fas fa-check me-1"></i> Seleccionar
                                        </button>
                                    </td>
                                </tr>`;
                        });
                        cuerpoTablas.innerHTML = html;
                        
                        // Agregar eventos a los botones de selección
                        document.querySelectorAll('.btn-seleccionar-tabla').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const tabla = this.getAttribute('data-tabla');
                                seleccionarTabla(tabla);
                            });
                        });
                    } else {
                        cuerpoTablas.innerHTML = `
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    No se encontraron tablas
                                </td>
                            </tr>`;
                    }
                    
                    // Actualizar paginación
                    actualizarPaginacion(paginacionTablas, data.pagina_actual, data.total_paginas, 'cambiarPaginaTablas');
                } else {
                    mostrarAlerta(data.error || 'Error al cargar las tablas', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al cargar las tablas', 'danger');
            });
    }
    
    // 5. Cargar referencias de una tabla
    function cargarReferencias(pagina = 1, buscar = '') {
        if (!tablaSeleccionada) return;
        
        const cuerpoReferencias = document.getElementById('cuerpoReferencias');
        const encabezadoReferencias = document.getElementById('encabezadoReferencias');
        const paginacionReferencias = document.getElementById('paginacionReferencias');
        
        // Mostrar carga
        cuerpoReferencias.innerHTML = `
            <tr>
                <td colspan="10" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </td>
            </tr>`;
            
        // Hacer petición AJAX
        fetch(`${baseUrl}/mantenimiento-tablas/obtener-referencias/${encodeURIComponent(tablaSeleccionada)}?buscar=${encodeURIComponent(buscar)}&pagina=${pagina}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar encabezados si es la primera página
                    if (pagina === 1 && data.data.length > 0) {
                        const columnas = Object.keys(data.data[0]);
                        let htmlEncabezado = '';
                        columnas.forEach(col => {
                            htmlEncabezado += `<th>${col}</th>`;
                        });
                        encabezadoReferencias.innerHTML = htmlEncabezado + '<th>Acciones</th>';
                    }
                    
                    // Actualizar datos
                    if (data.data.length > 0) {
                        let html = '';
                        data.data.forEach((item, index) => {
                            html += '<tr>';
                            Object.values(item).forEach(valor => {
                                html += `<td>${valor !== null ? valor : ''}</td>`;
                            });
                            html += `
                                <td>
                                    <button class="btn btn-sm btn-outline-primary btn-seleccionar-referencia" 
                                            data-id="${item.id || index}"
                                            data-texto="${JSON.stringify(item).replace(/"/g, '&quot;')}">
                                        <i class="fas fa-check me-1"></i> Seleccionar
                                    </button>
                                </td>
                            </tr>`;
                        });
                        cuerpoReferencias.innerHTML = html;
                        
                        // Agregar eventos a los botones de selección
                        document.querySelectorAll('.btn-seleccionar-referencia').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const id = this.getAttribute('data-id');
                                const texto = JSON.parse(this.getAttribute('data-texto').replace(/&quot;/g, '"'));
                                referenciaSeleccionada = { id, ...texto };
                                
                                // Resaltar fila seleccionada
                                document.querySelectorAll('#tablaReferencias tbody tr').forEach(row => {
                                    row.classList.remove('table-active');
                                });
                                this.closest('tr').classList.add('table-active');
                                
                                // Habilitar botón de seleccionar
                                document.getElementById('btnSeleccionarReferencia').disabled = false;
                            });
                        });
                    } else {
                        cuerpoReferencias.innerHTML = `
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    No se encontraron referencias
                                </td>
                            </tr>`;
                    }
                    
                    // Actualizar paginación
                    actualizarPaginacion(paginacionReferencias, data.pagina_actual, data.total_paginas, 'cambiarPaginaReferencias');
                } else {
                    mostrarAlerta(data.error || 'Error al cargar las referencias', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al cargar las referencias', 'danger');
            });
    }
    
    // 6. Seleccionar una tabla
    function seleccionarTabla(tabla) {
        tablaSeleccionada = tabla;
        
        // Actualizar el campo de tabla de referencia
        const inputTabla = document.getElementById('tabla_referencia');
        const inputReferencia = document.getElementById('referencia');
        const btnBuscarReferencia = document.getElementById('btnBuscarReferencia');
        
        if (inputTabla && inputReferencia) {
            // Actualizar el valor del campo oculto
            inputTabla.value = tabla;
            
            // Mostrar el nombre de la tabla en el campo de referencia
            inputReferencia.value = tabla;
            
            // Habilitar el botón de búsqueda de referencias
            if (btnBuscarReferencia) {
                btnBuscarReferencia.disabled = false;
            }
            
            // Cerrar el modal de búsqueda de tablas
            const modalBuscarTabla = bootstrap.Modal.getInstance(document.getElementById('modalBuscarTabla'));
            if (modalBuscarTabla) {
                modalBuscarTabla.hide();
            }
            
            // Mostrar notificación de éxito
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Tabla seleccionada',
                    text: `Se ha seleccionado la tabla: ${tabla}`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                alert(`Tabla seleccionada: ${tabla}`);
            }
            
            // Cargar referencias de la tabla seleccionada
            cargarReferencias(1, '');
            
            // Limpiar referencias anteriores
            document.getElementById('referencia_id').value = '';
        }
        
        // Enable reference search
        document.getElementById('btnBuscarReferencia').disabled = false;
        
        // Show success message
        mostrarAlerta(`Tabla "${tabla}" seleccionada correctamente`, 'success');
    }
    
    // 7. Seleccionar una referencia
    if (document.getElementById('btnSeleccionarReferencia')) {
        document.getElementById('btnSeleccionarReferencia').addEventListener('click', function() {
            if (referenciaSeleccionada) {
                const { id, descripcion, nombre, ...rest } = referenciaSeleccionada;
                
                // Set the reference ID in the hidden field
                document.getElementById('referencia_id').value = id;
                
                // Set the display text in the referencia field (use descripcion or nombre if available)
                const displayText = descripcion || nombre || `Referencia #${id}`;
                document.getElementById('referencia').value = displayText;
                
                // Set the full data in the referencia2 field
                document.getElementById('referencia2').value = JSON.stringify(referenciaSeleccionada);
                
                // Close the modal
                modalBuscarReferencia.hide();
                
                // Show success message
                mostrarAlerta('Referencia seleccionada correctamente', 'success');
            }
        });
    }
    
    // 4. Función para manejar el cambio de página (manejo centralizado)
    function manejarCambioPagina(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevenir la propagación del evento
        
        const target = e.target.closest('a.page-link');
        if (!target) return;
        
        // Prevenir múltiples clics rápidos
        if (target.classList.contains('disabled') || target.closest('li').classList.contains('disabled')) {
            return;
        }
        
        const page = target.getAttribute('data-page');
        const tipo = target.getAttribute('data-tipo');
        if (!page || !tipo) return;
        
        const pagina = parseInt(page);
        if (pagina < 1) return;
        
        // Deshabilitar temporalmente el botón
        target.classList.add('disabled');
        
        if (tipo === 'tablas') {
            if (paginaActualTablas === pagina) {
                target.classList.remove('disabled');
                return; // Evitar recargar la misma página
            }
            paginaActualTablas = pagina;
            cargarTablas(pagina, busquedaTabla);
        } else if (tipo === 'referencias') {
            if (paginaActualReferencias === pagina) {
                target.classList.remove('disabled');
                return; // Evitar recargar la misma página
            }
            paginaActualReferencias = pagina;
            cargarReferencias(pagina, busquedaReferencia);
        }
    }
    
    // 9. Búsqueda de tablas
    if (document.getElementById('buscarTabla')) {
        let timeoutBusquedaTabla;
        document.getElementById('buscarTabla').addEventListener('input', function() {
            clearTimeout(timeoutBusquedaTabla);
            busquedaTabla = this.value.trim();
            
            // Esperar 500ms después de que el usuario deje de escribir
            timeoutBusquedaTabla = setTimeout(() => {
                cargarTablas(1, busquedaTabla);
            }, 500);
        });
    }
    
    // 10. Búsqueda de referencias
    if (document.getElementById('btnAplicarBusqueda')) {
        document.getElementById('btnAplicarBusqueda').addEventListener('click', function() {
            busquedaReferencia = document.getElementById('buscarReferencia').value.trim();
            cargarReferencias(1, busquedaReferencia);
        });
        
        // También buscar al presionar Enter
        document.getElementById('buscarReferencia').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                busquedaReferencia = this.value.trim();
                cargarReferencias(1, busquedaReferencia);
            }
        });
    }
    
    // 11. Función para mostrar alertas
    function mostrarAlerta(mensaje, tipo = 'info') {
        // Implementar lógica para mostrar alertas (puedes usar Toast, SweetAlert, etc.)
        console.log(`[${tipo.toUpperCase()}] ${mensaje}`);
        // Ejemplo con SweetAlert2 si está disponible
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            
            Toast.fire({
                icon: tipo,
                title: mensaje
            });
        } else {
            alert(`[${tipo.toUpperCase()}] ${mensaje}`);
        }
    }
    
    // 12. Manejar selección de tablas
    document.addEventListener('click', function(e) {
        // Handle table selection
        const btnSeleccionar = e.target.closest('.btn-seleccionar-tabla');
        if (btnSeleccionar) {
            e.preventDefault();
            const tabla = btnSeleccionar.getAttribute('data-tabla');
            if (tabla) {
                seleccionarTabla(tabla);
            }
        }
    });
    
    // 13. Manejar código de subcatálogo
    const codigoSufijo = document.getElementById('codigo_sufijo');
    const codigoCompleto = document.getElementById('codigo_completo');
    const codigoHidden = document.getElementById('codigo');
    
    if (codigoSufijo && codigoCompleto && codigoHidden) {
        const codigoPadre = '<?= isset($catalogo_padre) ? esc($catalogo_padre['codigo']) : '' ?>';
        
        codigoSufijo.addEventListener('input', function() {
            const sufijo = this.value.trim();
            const codigoFinal = codigoPadre + (codigoPadre ? '-' : '') + sufijo;
            
            // Actualizar el código completo mostrado
            codigoCompleto.textContent = codigoFinal;
            
            // Actualizar el campo hidden
            codigoHidden.value = codigoFinal;
            
            // Verificar código si no está vacío
            if (sufijo) {
                verificarCodigo(codigoFinal);
            }
        });
        
        // Verificar código inicial si hay un sufijo
        const sufijoInicial = codigoSufijo.value.trim();
        if (sufijoInicial) {
            const codigoInicial = codigoPadre + '-' + sufijoInicial;
            codigoHidden.value = codigoInicial;
            verificarCodigo(codigoInicial);
        }
    }
    
    // Verificar código único (para catálogos normales)
    const codigoNormal = document.getElementById('codigo');
    if (codigoNormal && !codigoSufijo) {
        codigoNormal.addEventListener('blur', function() {
            const codigo = this.value.trim();
            if (codigo) {
                verificarCodigo(codigo);
            }
        });
    }

    // Función para verificar si el código existe
    function verificarCodigo(codigo) {
        fetch('<?= base_url('catalogo/verificarCodigo') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `codigo=${encodeURIComponent(codigo)}`
        })
        .then(response => response.json())
        .then(data => {
            const codigoField = document.getElementById('codigo_sufijo') || document.getElementById('codigo');
            const errorDiv = document.getElementById('error_codigo');
            
            if (data.exists) {
                codigoField.classList.add('is-invalid');
                errorDiv.textContent = 'Este código ya existe. Por favor, use uno diferente.';
            } else {
                codigoField.classList.remove('is-invalid');
                errorDiv.textContent = '';
            }
        })
        .catch(error => {
            console.error('Error al verificar código:', error);
        });
    }

    // Validación del formulario
    const formCatalogo = document.getElementById('formCatalogo');
    if (formCatalogo) {
        formCatalogo.addEventListener('submit', function(e) {
            let valid = true;
            
            // Validar código
            const codigo = document.getElementById('codigo')?.value.trim();
            if (!codigo) {
                document.getElementById('codigo')?.classList.add('is-invalid');
                const errorCodigo = document.getElementById('error_codigo');
                if (errorCodigo) errorCodigo.textContent = 'El código es requerido';
                valid = false;
            }

            // Validar nombre
            const nombre = document.getElementById('nombre')?.value.trim();
            if (!nombre) {
                document.getElementById('nombre')?.classList.add('is-invalid');
                valid = false;
            }

            // Validar estado
            const estado = document.getElementById('estado')?.value;
            if (!estado) {
                document.getElementById('estado')?.classList.add('is-invalid');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                // Deshabilitar botón para evitar doble envío
                const btnGuardar = document.getElementById('btnGuardar');
                if (btnGuardar) {
                    btnGuardar.disabled = true;
                    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                }
            }
        });
    }

    // Limpiar errores al escribir
    document.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorElement = this.nextElementSibling;
            if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                errorElement.textContent = '';
            }
        });
        element.addEventListener('change', function() {
            this.classList.remove('is-invalid');
            const errorElement = this.nextElementSibling;
            if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                errorElement.textContent = '';
            }
        });
    });

    // Mostrar errores de validación del servidor
    <?php if (session()->getFlashdata('errors')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const errors = <?= json_encode(session()->getFlashdata('errors')) ?>;
            Object.entries(errors).forEach(([field, message]) => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    let errorElement = input.nextElementSibling;
                    
                    // Si el siguiente elemento no es un div de error, buscar el más cercano
                    if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
                        errorElement = input.closest('.form-group')?.querySelector('.invalid-feedback') || 
                                     input.parentElement.querySelector('.invalid-feedback');
                    }
                    
                    if (errorElement) {
                        errorElement.textContent = message;
                    } else {
                        // Si no encuentra un contenedor de error, crear uno
                        const newError = document.createElement('div');
                        newError.className = 'invalid-feedback d-block';
                        newError.textContent = message;
                        input.parentNode.insertBefore(newError, input.nextSibling);
                    }
                }
            });
            
            // Desplazar al primer error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        });
    <?php endif; ?>
    });
});
</script>
<?= $this->endSection() ?>
