<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumb as $item): ?>
                <?php if (empty($item['url'])): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($item['name']) ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="<?= esc($item['url']) ?>"><?= esc($item['name']) ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-key text-primary me-2"></i>
            Gestión de Accesos
        </h1>
    </div>

    <div class="row">
        <!-- Panel izquierdo: Roles -->
        <div class="col-lg-3 col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Roles</h6>
                </div>
                <div class="list-group list-group-flush" id="listaRoles">
                    <?php foreach ($roles as $rol): ?>
                        <button type="button"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rol-item"
                                data-id-rol="<?= $rol['id'] ?>">
                            <span>
                                <i class="fas fa-user-tag text-muted me-2"></i>
                                <?= esc($rol['nombre']) ?>
                            </span>
                            <span class="badge bg-primary rounded-pill" id="badge-rol-<?= $rol['id'] ?>">0</span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Panel derecho: Accesos del rol seleccionado -->
        <div class="col-lg-9 col-md-8">
            <div class="card shadow-sm" id="panelAccesos">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt me-2 text-primary"></i>
                        <span id="tituloPanel">Selecciona un rol</span>
                    </h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnSeleccionarTodos" disabled>
                            <i class="fas fa-check-double"></i> Todos
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDeseleccionarTodos" disabled>
                            <i class="fas fa-times"></i> Ninguno
                        </button>
                        <button type="button" class="btn btn-sm btn-success" id="btnGuardarAccesos" disabled>
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="contenidoAccesos" class="text-center text-muted py-5">
                        <i class="fas fa-hand-point-left fa-3x mb-3 d-block opacity-50"></i>
                        <p class="mb-0">Selecciona un rol del panel izquierdo para ver y editar sus accesos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rolSeleccionado = null;
    var menusData = <?= json_encode($menus) ?>;
    var csrfToken = '<?= csrf_hash() ?>';
    var csrfName = '<?= csrf_token() ?>';

    // Renderizar árbol de menús con checkboxes
    function renderMenuTree(menus, accesosIds, level) {
        level = level || 1;
        var html = '';
        menus.forEach(function(menu) {
            var checked = accesosIds.indexOf(parseInt(menu.id)) !== -1 ? 'checked' : '';
            var hasChildren = menu.children && menu.children.length > 0;
            var indent = (level - 1) * 24;

            html += '<div class="menu-access-item" style="margin-left:' + indent + 'px;">';
            html += '<div class="d-flex align-items-center py-1">';
            if (hasChildren) {
                html += '<i class="fas fa-chevron-down toggle-access-children text-muted me-2" style="cursor:pointer;font-size:11px;"></i>';
            } else {
                html += '<i class="fas fa-circle text-muted me-2" style="font-size:6px;"></i>';
            }
            html += '<div class="form-check">';
            html += '<input class="form-check-input menu-checkbox" type="checkbox" value="' + menu.id + '" id="menu-' + menu.id + '" ' + checked + '>';
            html += '<label class="form-check-label" for="menu-' + menu.id + '">';
            html += '<i class="' + (menu.icono || 'fas fa-folder') + ' me-1 text-muted"></i>';
            html += esc(menu.menu);
            html += ' <span class="badge bg-secondary ms-1">N' + menu.nivel + '</span>';
            html += '</label>';
            html += '</div>';
            html += '</div>';

            if (hasChildren) {
                html += '<div class="submenu-access-container">';
                html += renderMenuTree(menu.children, accesosIds, level + 1);
                html += '</div>';
            }
            html += '</div>';
        });
        return html;
    }

    function esc(str) {
        var div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    // Cargar accesos del rol
    function cargarAccesos(idRol, nombreRol) {
        rolSeleccionado = idRol;
        document.getElementById('tituloPanel').textContent = 'Accesos de: ' + nombreRol;

        // Mostrar loading
        document.getElementById('contenidoAccesos').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Cargando...</p></div>';

        // Habilitar botones
        document.getElementById('btnSeleccionarTodos').disabled = false;
        document.getElementById('btnDeseleccionarTodos').disabled = false;
        document.getElementById('btnGuardarAccesos').disabled = false;

        // Obtener accesos actuales del rol
        fetch('<?= base_url('acceso/api/accesos-rol') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfName]: csrfToken
            },
            body: 'id_rol=' + idRol
        })
        .then(function(r) { return r.json(); })
        .then(function(response) {
            if (response.success) {
                var accesosIds = response.data.map(function(id) { return parseInt(id); });
                var html = renderMenuTree(menusData, accesosIds, 1);
                document.getElementById('contenidoAccesos').innerHTML = html;

                // Actualizar badge
                var badge = document.getElementById('badge-rol-' + idRol);
                if (badge) badge.textContent = accesosIds.length;

                // Toggle children
                document.querySelectorAll('.toggle-access-children').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.stopPropagation();
                        var container = this.closest('.menu-access-item').querySelector('.submenu-access-container');
                        if (container) {
                            var isHidden = container.style.display === 'none';
                            container.style.display = isHidden ? '' : 'none';
                            this.classList.toggle('fa-chevron-down');
                            this.classList.toggle('fa-chevron-right');
                        }
                    });
                });
            } else {
                document.getElementById('contenidoAccesos').innerHTML = '<div class="alert alert-danger">Error: ' + (response.message || 'No se pudieron cargar los accesos') + '</div>';
            }
        })
        .catch(function() {
            document.getElementById('contenidoAccesos').innerHTML = '<div class="alert alert-danger">Error al cargar los accesos</div>';
        });
    }

    // Click en rol
    document.querySelectorAll('.rol-item').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.rol-item').forEach(function(el) {
                el.classList.remove('active', 'bg-primary', 'text-white');
            });
            this.classList.add('active', 'bg-primary', 'text-white');
            var idRol = this.getAttribute('data-id-rol');
            var nombreRol = this.textContent.trim().replace(/\d+$/, '').trim();
            cargarAccesos(idRol, nombreRol);
        });
    });

    // Seleccionar todos
    document.getElementById('btnSeleccionarTodos').addEventListener('click', function() {
        document.querySelectorAll('.menu-checkbox').forEach(function(cb) { cb.checked = true; });
    });

    // Deseleccionar todos
    document.getElementById('btnDeseleccionarTodos').addEventListener('click', function() {
        document.querySelectorAll('.menu-checkbox').forEach(function(cb) { cb.checked = false; });
    });

    // Guardar accesos
    document.getElementById('btnGuardarAccesos').addEventListener('click', function() {
        if (!rolSeleccionado) return;

        var menuIds = [];
        document.querySelectorAll('.menu-checkbox:checked').forEach(function(cb) {
            menuIds.push(parseInt(cb.value));
        });

        var btn = this;
        var originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        fetch('<?= base_url('acceso/storeMultiple') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfName]: csrfToken
            },
            body: 'id_rol=' + rolSeleccionado + '&menu_ids=' + JSON.stringify(menuIds)
        })
        .then(function(r) { return r.json(); })
        .then(function(response) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if (response.success) {
                mostrarAlerta(response.message, 'success');
                var badge = document.getElementById('badge-rol-' + rolSeleccionado);
                if (badge) badge.textContent = menuIds.length;
            } else {
                mostrarAlerta(response.message || 'Error al guardar', 'error');
            }
        })
        .catch(function() {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            mostrarAlerta('Error al guardar los accesos', 'error');
        });
    });

    function mostrarAlerta(mensaje, tipo) {
        var alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        var iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        var alert = document.createElement('div');
        alert.className = 'alert ' + alertClass + ' alert-dismissible fade show';
        alert.setAttribute('role', 'alert');
        alert.innerHTML = '<i class="fas ' + iconClass + ' me-2"></i>' + mensaje +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        var container = document.querySelector('.container-fluid');
        if (container) container.prepend(alert);
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() { alert.remove(); }, 500);
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
