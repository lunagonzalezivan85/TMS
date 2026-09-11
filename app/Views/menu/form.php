<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= isset($menu) ? 'Editar Menú' : 'Crear Nuevo Menú' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-<?= isset($menu) ? 'edit' : 'plus' ?> fa-fw me-2"></i>
            <?= isset($menu) ? 'Editar Menú' : 'Crear Nuevo Menú' ?>
        </h1>
        <a href="<?= base_url('menu') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Lista
        </a>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-<?= isset($menu) ? 'edit' : 'plus' ?> me-2"></i>
                        <?= isset($menu) ? 'Modificar Información del Menú' : 'Información del Nuevo Menú' ?>
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (session('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form id="menuForm" method="post" 
                          action="<?= base_url(isset($menu) ? 'menu/update/' . $menu['id'] : 'menu/store') ?>">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <!-- Nombre del Menú -->
                            <div class="col-md-6 mb-3">
                                <label for="menu" class="form-label">
                                    <i class="fas fa-tag text-primary me-1"></i>
                                    Nombre del Menú <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?= session('errors.menu') ? 'is-invalid' : '' ?>" 
                                       id="menu" 
                                       name="menu" 
                                       value="<?= old('menu', $menu['menu'] ?? '') ?>"
                                       placeholder="Ej: Gestión de Vehículos"
                                       maxlength="100"
                                       required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Nombre descriptivo que aparecerá en el menú de navegación
                                </div>
                                <?php if (session('errors.menu')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.menu') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Menú Superior -->
                            <div class="col-md-6 mb-3">
                                <label for="id_superior" class="form-label">
                                    <i class="fas fa-sitemap text-primary me-1"></i>
                                    Menú Superior
                                </label>
                                <select class="form-select <?= session('errors.id_superior') ? 'is-invalid' : '' ?>" 
                                        id="id_superior" 
                                        name="id_superior">
                                    <option value="">-- Menú Principal (Sin Superior) --</option>
                                    <?php if (!empty($menus_padre)): ?>
                                        <?php foreach ($menus_padre as $id => $nombre): ?>
                                            <option value="<?= $id ?>" 
                                                    <?= old('id_superior', $menu['id_superior'] ?? '') == $id ? 'selected' : '' ?>>
                                                <?= esc($nombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Selecciona un menú padre para crear una jerarquía
                                </div>
                                <?php if (session('errors.id_superior')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.id_superior') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Ruta del Menú -->
                            <div class="col-md-12 mb-3">
                                <label for="ruta" class="form-label">
                                    <i class="fas fa-link text-primary me-1"></i>
                                    Ruta del Menú <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control <?= session('errors.ruta') ? 'is-invalid' : '' ?>" 
                                           id="ruta" 
                                           name="ruta" 
                                           value="<?= old('ruta', $menu['ruta'] ?? '') ?>"
                                           placeholder="Ej: vehiculos, conductores, dashboard"
                                           maxlength="100"
                                           required>
                                    <button type="button" 
                                            class="btn btn-outline-primary" 
                                            id="btnBuscarRuta" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalBuscadorRutas"
                                            title="Buscar ruta en configuración">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    URL relativa del menú (sin barras diagonales). Ejemplos: dashboard, vehiculos, reportes
                                </div>
                                <?php if (session('errors.ruta')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.ruta') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Selector de Icono -->
                        <div class="mb-4">
                            <label for="icono" class="form-label">
                                <i class="fas fa-icons text-primary me-1"></i>
                                Icono del Menú <span class="text-danger">*</span>
                            </label>
                            
                            <!-- Campo oculto para el valor del icono -->
                            <input type="hidden" 
                                   id="icono" 
                                   name="icono" 
                                   value="<?= old('icono', $menu['icono'] ?? '') ?>">
                            
                            <!-- Buscador de iconos -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="iconSearch" 
                                       placeholder="Buscar icono por nombre..."
                                       autocomplete="off">
                            </div>
                            
                            <!-- Preview del icono seleccionado -->
                            <div class="selected-icon-preview mb-3" id="selectedIconPreview" style="display: none;">
                                <div class="alert alert-info d-flex align-items-center">
                                    <div class="me-3">
                                        <i id="previewIcon" class="fa-2x"></i>
                                    </div>
                                    <div>
                                        <strong>Icono Seleccionado:</strong>
                                        <br>
                                        <span id="previewName"></span>
                                        <br>
                                        <small class="text-muted">Clase: <code id="previewClass"></code></small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" onclick="clearIconSelection()">
                                        <i class="fas fa-times"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Contenedor de iconos por categorías -->
                            <div class="icon-selector">
                                <div class="accordion" id="iconAccordion">
                                    <?php if (!empty($iconos)): ?>
                                        <?php $index = 0; ?>
                                        <?php foreach ($iconos as $categoria => $icons): ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading<?= $index ?>">
                                                    <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" 
                                                            type="button" 
                                                            data-bs-toggle="collapse" 
                                                            data-bs-target="#collapse<?= $index ?>" 
                                                            aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                                                            aria-controls="collapse<?= $index ?>">
                                                        <i class="fas fa-folder me-2"></i>
                                                        <?= ucfirst($categoria) ?> 
                                                        <span class="badge bg-secondary ms-2"><?= count($icons) ?></span>
                                                    </button>
                                                </h2>
                                                <div id="collapse<?= $index ?>" 
                                                     class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                                                     aria-labelledby="heading<?= $index ?>" 
                                                     data-bs-parent="#iconAccordion">
                                                    <div class="accordion-body">
                                                        <div class="row g-2">
                                                            <?php foreach ($icons as $class => $name): ?>
                                                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                                                    <div class="icon-option" 
                                                                         data-icon="<?= esc($class) ?>" 
                                                                         data-name="<?= esc($name) ?>"
                                                                         title="<?= esc($name) ?>">
                                                                        <i class="<?= esc($class) ?>"></i>
                                                                        <small><?= esc(substr($name, 0, 15)) ?><?= strlen($name) > 15 ? '...' : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                            </div>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (session('errors.icono')): ?>
                <div class="text-danger mt-2">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    <?= session('errors.icono') ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Botones de Acción -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('menu') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-<?= isset($menu) ? 'save' : 'plus' ?>"></i> 
                        <?= isset($menu) ? 'Actualizar Menú' : 'Crear Menú' ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
</div>
</div>

<!-- Modal Buscador de Rutas -->
<div class="modal fade" id="modalBuscadorRutas" tabindex="-1" aria-labelledby="modalBuscadorRutasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalBuscadorRutasLabel">
                    <i class="fas fa-search me-2"></i>
                    Buscador de Rutas del Sistema
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Buscador -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="buscarRutaInput" 
                               placeholder="Buscar por nombre de menú o ruta...">
                        <button type="button" class="btn btn-outline-secondary" id="limpiarBusqueda">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Resultados -->
                <div id="resultadosRutas">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando rutas disponibles...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.icon-selector {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e3e6f0;
    border-radius: 0.375rem;
}

.icon-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    border: 2px solid transparent;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fc;
    text-align: center;
    min-height: 80px;
    justify-content: center;
}

.icon-option:hover {
    border-color: #4e73df;
    background: #e3ebff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.icon-option.selected {
    border-color: #1cc88a;
    background: #d4edda;
    color: #155724;
}

.icon-option i {
    font-size: 24px;
    margin-bottom: 5px;
    color: #5a5c69;
}

.icon-option.selected i {
    color: #155724;
}

.icon-option small {
    font-size: 11px;
    line-height: 1.2;
    word-break: break-word;
}

.selected-icon-preview {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.accordion-button:not(.collapsed) {
    background-color: #f8f9fc;
    color: #5a5c69;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

/* Estilos para el buscador de rutas */
.ruta-card {
    border-width: 2px;
    transition: all 0.3s ease;
}

.ruta-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-color: #4e73df !important;
}

.ruta-card.border-primary:hover {
    background-color: #f8f9fc;
}

.ruta-card.border-secondary:hover {
    background-color: #f8f9fa;
}

.ruta-card .card-title {
    font-size: 0.9rem;
    line-height: 1.2;
}

.ruta-card code {
    font-size: 0.8rem;
    background-color: #e9ecef;
    padding: 2px 4px;
    border-radius: 3px;
    color: #495057;
}

.ruta-card .badge-sm {
    font-size: 0.7rem;
    padding: 0.25em 0.5em;
}

#modalBuscadorRutas .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

#resultadosRutas {
    min-height: 200px;
}

mark {
    background-color: #fff3cd;
    padding: 1px 2px;
    border-radius: 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const iconoInput = document.getElementById('icono');
    let selectedIcon = iconoInput ? iconoInput.value : '';
    
    // Mostrar icono preseleccionado si existe
    if (selectedIcon) {
        updateIconPreview(selectedIcon);
    }
    
    // Manejar selección de icono
    document.addEventListener('click', function(e) {
        if (e.target.closest('.icon-option')) {
            const iconOption = e.target.closest('.icon-option');
            
            // Limpiar selección anterior
            document.querySelectorAll('.icon-option').forEach(opt => opt.classList.remove('selected'));
            iconOption.classList.add('selected');
            
            const iconClass = iconOption.dataset.icon;
            const iconName = iconOption.dataset.name;
            
            if (iconoInput) {
                iconoInput.value = iconClass;
            }
            updateIconPreview(iconClass, iconName);
        }
    });
    
    // Buscador de iconos
    const iconSearch = document.getElementById('iconSearch');
    if (iconSearch) {
        iconSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            document.querySelectorAll('.icon-option').forEach(function(iconOption) {
                const iconName = iconOption.dataset.name.toLowerCase();
                const iconClass = iconOption.dataset.icon.toLowerCase();
                
                if (iconName.includes(searchTerm) || iconClass.includes(searchTerm)) {
                    iconOption.style.display = 'flex';
                } else {
                    iconOption.style.display = 'none';
                }
            });
            
            // Mostrar/ocultar acordeones basado en si tienen iconos visibles
            document.querySelectorAll('.accordion-item').forEach(function(accordionItem) {
                const visibleIcons = accordionItem.querySelectorAll('.icon-option[style*="flex"], .icon-option:not([style*="none"])');
                if (visibleIcons.length > 0) {
                    accordionItem.style.display = 'block';
                } else {
                    accordionItem.style.display = 'none';
                }
            });
        });
    }
    
    // Validación del formulario
    const menuForm = document.getElementById('menuForm');
    if (menuForm) {
        menuForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validar nombre del menú
            const menuInput = document.getElementById('menu');
            const menuName = menuInput ? menuInput.value.trim() : '';
            if (!menuName) {
                showFieldError('menu', 'El nombre del menú es obligatorio');
                isValid = false;
            } else if (menuName.length < 2) {
                showFieldError('menu', 'El nombre debe tener al menos 2 caracteres');
                isValid = false;
            } else {
                clearFieldError('menu');
            }
            
            // Validar icono
            const icon = iconoInput ? iconoInput.value : '';
            if (!icon) {
                showAlert('error', 'Debes seleccionar un icono para el menú');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            } else {
                // Deshabilitar botón para evitar doble envío
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
                }
            }
        });
    }
    
    function updateIconPreview(iconClass, iconName = '') {
        const previewIcon = document.getElementById('previewIcon');
        const previewClass = document.getElementById('previewClass');
        const previewName = document.getElementById('previewName');
        const selectedIconPreview = document.getElementById('selectedIconPreview');
        
        if (iconClass) {
            if (previewIcon) {
                previewIcon.className = iconClass + ' fa-2x';
            }
            if (previewClass) {
                previewClass.textContent = iconClass;
            }
            
            if (iconName) {
                if (previewName) {
                    previewName.textContent = iconName;
                }
            } else {
                // Buscar el nombre del icono en las opciones
                const iconOption = document.querySelector(`[data-icon="${iconClass}"]`);
                if (iconOption && previewName) {
                    previewName.textContent = iconOption.dataset.name;
                    iconOption.classList.add('selected');
                }
            }
            
            if (selectedIconPreview) {
                selectedIconPreview.style.display = 'block';
            }
        } else {
            if (selectedIconPreview) {
                selectedIconPreview.style.display = 'none';
            }
        }
    }
    
    function showFieldError(fieldName, message) {
        const field = document.getElementById(fieldName);
        if (!field) return;
        
        field.classList.add('is-invalid');
        
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
    }
    
    function clearFieldError(fieldName) {
        const field = document.getElementById(fieldName);
        if (!field) return;
        
        field.classList.remove('is-invalid');
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show`;
        alert.setAttribute('role', 'alert');
        alert.innerHTML = `
            <i class="fas ${iconClass} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertBefore(alert, container.firstChild);
        }
        
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
    
    // === FUNCIONALIDAD DEL BUSCADOR DE RUTAS ===
    
    let rutasDisponibles = [];
    
    // Cargar rutas cuando se abre el modal
    const modalBuscadorRutas = document.getElementById('modalBuscadorRutas');
    if (modalBuscadorRutas) {
        modalBuscadorRutas.addEventListener('show.bs.modal', function() {
            cargarRutasDisponibles();
        });
    }
    
    // Buscar rutas en tiempo real
    const buscarRutaInput = document.getElementById('buscarRutaInput');
    if (buscarRutaInput) {
        buscarRutaInput.addEventListener('input', function() {
            const termino = this.value.trim();
            buscarRutas(termino);
        });
    }
    
    // Limpiar búsqueda
    const limpiarBusqueda = document.getElementById('limpiarBusqueda');
    if (limpiarBusqueda) {
        limpiarBusqueda.addEventListener('click', function() {
            if (buscarRutaInput) {
                buscarRutaInput.value = '';
            }
            mostrarTodasLasRutas();
        });
    }
    
    // Función para cargar rutas disponibles
    function cargarRutasDisponibles() {
        const resultadosRutas = document.getElementById('resultadosRutas');
        if (resultadosRutas) {
            resultadosRutas.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando rutas disponibles...</p>
                </div>
            `;
        }
        
        fetch('<?= base_url('menu-routes/getRutas') ?>', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                rutasDisponibles = data.data;
                mostrarTodasLasRutas();
            } else {
                mostrarErrorRutas('Error al cargar las rutas: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            mostrarErrorRutas('Error de conexión al cargar las rutas');
        });
    }
    
    // Función para buscar rutas
    function buscarRutas(termino) {
        if (!termino) {
            mostrarTodasLasRutas();
            return;
        }
        
        const rutasFiltradas = rutasDisponibles.filter(ruta => 
            ruta.title.toLowerCase().includes(termino.toLowerCase()) ||
            ruta.url.toLowerCase().includes(termino.toLowerCase())
        );
        
        mostrarRutas(rutasFiltradas, termino);
    }
    
    // Función para mostrar todas las rutas
    function mostrarTodasLasRutas() {
        mostrarRutas(rutasDisponibles);
    }
    
    // Función para mostrar rutas
    function mostrarRutas(rutas, terminoBusqueda = '') {
        const resultadosRutas = document.getElementById('resultadosRutas');
        if (!resultadosRutas) return;
        
        if (rutas.length === 0) {
            resultadosRutas.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron rutas</h5>
                    <p class="text-muted">Intenta con otros términos de búsqueda</p>
                </div>
            `;
            return;
        }
        
        let html = `
            <div class="mb-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Se encontraron ${rutas.length} rutas disponibles
                </small>
            </div>
        `;
        
        // Agrupar por tipo
        const menus = rutas.filter(r => r.type === 'menu');
        const submenus = rutas.filter(r => r.type === 'submenu');
        
        if (menus.length > 0) {
            html += '<h6 class="fw-bold text-primary mb-2"><i class="fas fa-folder me-1"></i> Menús Principales</h6>';
            html += '<div class="row g-2 mb-3">';
            menus.forEach(ruta => {
                html += generarTarjetaRuta(ruta, terminoBusqueda);
            });
            html += '</div>';
        }
        
        if (submenus.length > 0) {
            html += '<h6 class="fw-bold text-secondary mb-2"><i class="fas fa-sitemap me-1"></i> Submenús</h6>';
            html += '<div class="row g-2">';
            submenus.forEach(ruta => {
                html += generarTarjetaRuta(ruta, terminoBusqueda);
            });
            html += '</div>';
        }
        
        resultadosRutas.innerHTML = html;
    }
    
    // Función para generar tarjeta de ruta
    function generarTarjetaRuta(ruta, terminoBusqueda = '') {
        const tipoClass = ruta.type === 'menu' ? 'border-primary' : 'border-secondary';
        const tipoIcon = ruta.type === 'menu' ? 'fas fa-folder' : 'fas fa-file';
        
        // Resaltar término de búsqueda
        let titulo = ruta.title;
        let url = ruta.url;
        
        if (terminoBusqueda) {
            const regex = new RegExp(`(${terminoBusqueda})`, 'gi');
            titulo = titulo.replace(regex, '<mark>$1</mark>');
            url = url.replace(regex, '<mark>$1</mark>');
        }
        
        return `
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 ruta-card ${tipoClass}" 
                     style="cursor: pointer; transition: all 0.3s ease;"
                     data-ruta="${ruta.url}"
                     onclick="seleccionarRuta('${ruta.url}')">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                <i class="${ruta.icon} fa-lg text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1 fw-bold">${titulo}</h6>
                                <p class="card-text mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-link me-1"></i>
                                        <code>${url}</code>
                                    </small>
                                </p>
                                <span class="badge ${ruta.type === 'menu' ? 'bg-primary' : 'bg-secondary'} badge-sm">
                                    <i class="${tipoIcon} me-1"></i>
                                    ${ruta.type === 'menu' ? 'Menú' : 'Submenú'}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Función para mostrar error
    function mostrarErrorRutas(mensaje) {
        const resultadosRutas = document.getElementById('resultadosRutas');
        if (resultadosRutas) {
            resultadosRutas.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${mensaje}
                </div>
            `;
        }
    }
    
    // Función global para seleccionar ruta
    window.seleccionarRuta = function(ruta) {
        const rutaInput = document.getElementById('ruta');
        if (rutaInput) {
            rutaInput.value = ruta;
        }
        
        // Cerrar modal usando Bootstrap
        const modal = bootstrap.Modal.getInstance(modalBuscadorRutas);
        if (modal) {
            modal.hide();
        }
        
        // Mostrar feedback visual
        if (rutaInput) {
            rutaInput.classList.add('is-valid');
            setTimeout(() => {
                rutaInput.classList.remove('is-valid');
            }, 2000);
        }
        
        showAlert('success', `Ruta "${ruta}" seleccionada correctamente`);
    };
});

// Función global para limpiar selección de icono
function clearIconSelection() {
    const iconoInput = document.getElementById('icono');
    if (iconoInput) {
        iconoInput.value = '';
    }
    
    const selectedIconPreview = document.getElementById('selectedIconPreview');
    if (selectedIconPreview) {
        selectedIconPreview.style.display = 'none';
    }
    
    document.querySelectorAll('.icon-option').forEach(option => {
        option.classList.remove('selected');
    });
}
</script>

<?= $this->endSection() ?>
