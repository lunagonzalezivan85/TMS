<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('acceso') ?>">
                    <i class="fas fa-key"></i> Accesos
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-plus"></i> <?= $title ?>
            </li>
        </ol>
    </nav>

    <div class="row">
        <!-- Selector de Rol -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-shield me-2"></i>
                        Seleccionar Rol
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="id_rol" class="form-label">
                            Rol <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="id_rol" name="id_rol" required>
                            <option value="">Seleccione un rol</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['id'] ?>" data-descripcion="<?= esc($rol['descripcion'] ?? 'Sin descripción') ?>">
                                    <?= esc($rol['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" id="error-id_rol"></div>
                    </div>
                    
                    <div id="rol-info" class="alert alert-info" style="display: none;">
                        <h6><i class="fas fa-info-circle me-1"></i> Información del Rol:</h6>
                        <div id="rol-descripcion"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Árbol de Menús -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-sitemap me-2"></i>
                            Menús y Permisos
                        </h5>
                        <div>
                            <button type="button" class="btn btn-light btn-sm" id="btn-select-all">
                                <i class="fas fa-check-double me-1"></i> Seleccionar Todo
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" id="btn-deselect-all">
                                <i class="fas fa-times me-1"></i> Deseleccionar Todo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="menu-tree-container" style="display: none;">
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Nota:</strong> Al seleccionar un submenú, el menú principal se seleccionará automáticamente.
                        </div>
                        
                        <div id="menu-tree">
                            <?php if (!empty($menus)): ?>
                                <?php foreach ($menus as $menu): ?>
                                    <div class="menu-item mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input menu-checkbox parent-menu" 
                                                   type="checkbox" 
                                                   value="<?= $menu['id'] ?>" 
                                                   id="menu_<?= $menu['id'] ?>" 
                                                   data-level="1">
                                            <label class="form-check-label fw-bold" for="menu_<?= $menu['id'] ?>">
                                                <?php if (!empty($menu['icono'])): ?>
                                                    <i class="<?= esc($menu['icono']) ?> me-2"></i>
                                                <?php endif; ?>
                                                <?= esc($menu['menu']) ?>
                                            </label>
                                        </div>
                                        
                                        <?php if (!empty($menu['children'])): ?>
                                            <div class="submenu-container ms-4 mt-2">
                                                <?php foreach ($menu['children'] as $submenu): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input menu-checkbox child-menu" 
                                                               type="checkbox" 
                                                               value="<?= $submenu['id'] ?>" 
                                                               id="menu_<?= $submenu['id'] ?>" 
                                                               data-parent="<?= $menu['id'] ?>" 
                                                               data-level="2">
                                                        <label class="form-check-label" for="menu_<?= $submenu['id'] ?>">
                                                            <?php if (!empty($submenu['icono'])): ?>
                                                                <i class="<?= esc($submenu['icono']) ?> me-2"></i>
                                                            <?php endif; ?>
                                                            <?= esc($submenu['menu']) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    No hay menús disponibles.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div id="no-rol-selected" class="text-center text-muted py-5">
                        <i class="fas fa-arrow-left fa-3x mb-3"></i>
                        <h5>Seleccione un rol para ver los menús disponibles</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span id="selected-count" class="badge bg-info">0 menús seleccionados</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('acceso') ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Cancelar
                            </a>
                            <button type="button" class="btn btn-primary" id="btn-save" disabled>
                                <i class="fas fa-save me-1"></i>
                                Guardar Accesos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const rolSelect = document.getElementById('id_rol');
    const menuTree = document.getElementById('menu-tree-container');
    const noRolSelected = document.getElementById('no-rol-selected');
    const btnSelectAll = document.getElementById('btn-select-all');
    const btnDeselectAll = document.getElementById('btn-deselect-all');
    const btnSave = document.getElementById('btn-save');
    const selectedCount = document.getElementById('selected-count');
    const rolInfo = document.getElementById('rol-info');
    const rolDescripcion = document.getElementById('rol-descripcion');
    
    // Variables globales
    let accesosExistentes = [];
    
    // Inicializar vista
    actualizarVista();
    
    // Event listeners
    rolSelect.addEventListener('change', function() {
        const rolId = this.value;
        
        if (rolId) {
            cargarAccesosExistentes(rolId);
            mostrarInfoRol();
        } else {
            accesosExistentes = [];
            actualizarCheckboxes();
        }
        
        actualizarVista();
    });
    
    // Botones de selección
    btnSelectAll.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.menu-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        actualizarConteoSeleccionados();
    });
    
    btnDeselectAll.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.menu-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        actualizarConteoSeleccionados();
    });
    
    // Lógica de checkboxes padre-hijo
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('menu-checkbox')) {
            const checkbox = e.target;
            
            if (checkbox.classList.contains('parent-menu')) {
                // Si es un menú padre, actualizar hijos
                const menuId = checkbox.value;
                const childCheckboxes = document.querySelectorAll(`[data-parent="${menuId}"]`);
                
                childCheckboxes.forEach(child => {
                    child.checked = checkbox.checked;
                });
            } else if (checkbox.classList.contains('child-menu')) {
                // Si es un submenú, verificar si debe marcar el padre
                const parentId = checkbox.getAttribute('data-parent');
                const parentCheckbox = document.getElementById(`menu_${parentId}`);
                
                if (checkbox.checked && parentCheckbox) {
                    parentCheckbox.checked = true;
                }
            }
            
            actualizarConteoSeleccionados();
        }
    });
    
    // Botón guardar
    btnSave.addEventListener('click', function() {
        guardarAccesos();
    });
    
    // Funciones auxiliares
    function actualizarVista() {
        const rolSeleccionado = rolSelect.value;
        
        if (rolSeleccionado) {
            menuTree.style.display = 'block';
            noRolSelected.style.display = 'none';
        } else {
            menuTree.style.display = 'none';
            noRolSelected.style.display = 'block';
            rolInfo.style.display = 'none';
        }
        
        actualizarConteoSeleccionados();
    }
    
    function mostrarInfoRol() {
        const selectedOption = rolSelect.options[rolSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const descripcion = selectedOption.getAttribute('data-descripcion') || 'Sin descripción';
            rolDescripcion.textContent = descripcion;
            rolInfo.style.display = 'block';
        }
    }
    
    function cargarAccesosExistentes(rolId) {
        fetch('<?= base_url('acceso/api/accesos-rol') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `id_rol=${rolId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                accesosExistentes = data.data || [];
                actualizarCheckboxes();
            } else {
                console.error('Error al cargar accesos:', data.message);
                accesosExistentes = [];
            }
        })
        .catch(error => {
            console.error('Error:', error);
            accesosExistentes = [];
        });
    }
    
    function actualizarCheckboxes() {
        const checkboxes = document.querySelectorAll('.menu-checkbox');
        checkboxes.forEach(checkbox => {
            const menuId = parseInt(checkbox.value);
            checkbox.checked = accesosExistentes.includes(menuId);
        });
        actualizarConteoSeleccionados();
    }
    
    function actualizarConteoSeleccionados() {
        const checkboxesSeleccionados = document.querySelectorAll('.menu-checkbox:checked');
        const count = checkboxesSeleccionados.length;
        
        selectedCount.textContent = `${count} menús seleccionados`;
        btnSave.disabled = count === 0 || !rolSelect.value;
    }
    
    function guardarAccesos() {
        const rolId = rolSelect.value;
        if (!rolId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe seleccionar un rol'
            });
            return;
        }
        
        const checkboxesSeleccionados = document.querySelectorAll('.menu-checkbox:checked');
        const menuIds = Array.from(checkboxesSeleccionados).map(cb => parseInt(cb.value));
        
        if (menuIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe seleccionar al menos un menú'
            });
            return;
        }
        
        // Confirmar acción
        Swal.fire({
            title: '¿Guardar accesos?',
            text: `Se asignarán ${menuIds.length} menús al rol seleccionado`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                enviarAccesos(rolId, menuIds);
            }
        });
    }
    
    function enviarAccesos(rolId, menuIds) {
        // Deshabilitar botón mientras se procesa
        btnSave.disabled = true;
        btnSave.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';
        
        fetch('<?= base_url('acceso/store-multiple') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `id_rol=${rolId}&menu_ids=${JSON.stringify(menuIds)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '<?= base_url('acceso') ?>';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión. Intente nuevamente.'
            });
        })
        .finally(() => {
            // Rehabilitar botón
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="fas fa-save me-1"></i> Guardar Accesos';
        });
    }
});
</script>
<?= $this->endSection() ?>
