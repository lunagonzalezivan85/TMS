<?php
use Config\Menu;

// Obtener el rol del usuario desde la sesión
$userRole = session('rol_nombre') ?? session('rol_name') ?? null;
$userId = session('user_id') ?? null;

// Si no hay rol en sesión, intentar obtenerlo desde la base de datos
if (!$userRole && $userId) {
    $usuarioModel = new \App\Models\UsuarioModel();
    $usuario = $usuarioModel->select('usuarios.*, roles.nombre as rol_nombre')
                           ->join('roles', 'roles.id = usuarios.id_rol')
                           ->find($userId);
    if ($usuario) {
        $userRole = $usuario['rol_nombre'];
    }
}


// Obtener el menú filtrado por rol
$sidebarMenu = [];
if ($userRole) {
    $sidebarMenu = Menu::getMenuByUserType($userRole);
}


// Obtener URL actual para marcar items activos
$currentUrl = uri_string();
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Header del Sidebar -->
    <div class="sidebar-header">
        <div class="brand-icon">
            <i class="fas fa-truck-fast fa-lg"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold" style="font-size: 1.1rem; letter-spacing: -0.5px;"><span class="text-primary-50">TMS</span></h5>
            <small class="opacity-50" style="font-size: 0.7rem;">Transport Management System</small>
        </div>
        <button class="btn btn-link text-white p-0 d-lg-none ms-auto" id="closeSidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Buscador de Menú -->
    <div class="px-3 mb-3">
        <div class="sidebar-search">
            <i class="fas fa-search"></i>
            <input type="text" id="menuSearch" placeholder="Buscar en menú..." autocomplete="off">
        </div>
    </div>
    
    <!-- Menú del Sidebar -->
    <div class="sidebar-menu">
        <?php foreach ($sidebarMenu as $item): ?>
            <div class="menu-item">
                <?php if (isset($item['submenu']) && !empty($item['submenu'])): ?>
                    <!-- Item con submenú -->
                    <a href="#" class="menu-link" 
                       data-bs-toggle="collapse" 
                       data-bs-target="#submenu-<?= md5($item['title']) ?>" 
                       aria-expanded="false"
                       title="<?= esc($item['title']) ?>"
                       data-bs-placement="right"
                       data-bs-trigger="hover focus"
                       data-bs-custom-class="sidebar-tooltip">
                        <i class="<?= $item['icon'] ?> menu-icon"></i>
                        <span class="menu-text"><?= $item['title'] ?></span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="submenu collapse" id="submenu-<?= md5($item['title']) ?>">
                        <?php foreach ($item['submenu'] as $subitem): ?>
                            <a href="<?= base_url($subitem['url']) ?>" 
                               class="submenu-link <?= $currentUrl === $subitem['url'] ? 'active' : '' ?>"
                               title="<?= esc($subitem['title']) ?>"
                               data-bs-placement="right"
                               data-bs-trigger="hover focus"
                               data-bs-custom-class="sidebar-tooltip">
                                <?= $subitem['title'] ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- Item simple sin submenú -->
                    <a href="<?= base_url($item['url']) ?>" 
                       class="menu-link <?= isset($item['active']) && Menu::isActive($item['active'], $currentUrl) ? 'active' : '' ?>"
                       title="<?= esc($item['title']) ?>"
                       data-bs-placement="right"
                       data-bs-trigger="hover focus"
                       data-bs-custom-class="sidebar-tooltip">
                        <i class="<?= $item['icon'] ?> menu-icon"></i>
                        <span class="menu-text"><?= $item['title'] ?></span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Footer del Sidebar -->
    <div class="sidebar-footer">
        <div class="sidebar-user-card">
            <div class="avatar">
                <?= strtoupper(substr(session('nombre') ?? 'U', 0, 1)) ?>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold text-truncate small"><?= session('nombre') ?? 'Usuario' ?></div>
                <div class="opacity-50 text-truncate" style="font-size: 0.65rem;"><?= $userRole ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay eliminado por requerimiento -->

<style>
/* Sidebar Modernization */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: var(--sidebar-width);
    background: #0f172a; /* Deep Midnight */
    color: #f8fafc;
    z-index: 1051;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 24px rgba(0,0,0,0.15);
    border-right: 1px solid rgba(255,255,255,0.05);
}

/* Solo ocultar en móviles */
@media (max-width: 991.98px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.show { transform: translateX(0); }
}

/* Ocultar en escritorio cuando se colapsa */
@media (min-width: 992px) {
    body.sidebar-collapsed #sidebar { transform: translateX(-100%); }
}

.sidebar-header {
    padding: 1.5rem 1.25rem 1rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar-search {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    padding: 0.5rem 0.875rem;
    display: flex;
    align-items: center;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.2s;
}

.sidebar-search:focus-within {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
}

.sidebar-search input {
    background: transparent;
    border: none;
    outline: none;
    color: #fff;
    font-size: 0.85rem;
    margin-left: 8px;
    width: 100%;
}

.sidebar-search i {
    font-size: 0.85rem;
    opacity: 0.5;
}

.sidebar-menu {
    flex: 1;
    padding: 0.5rem 0.75rem;
    overflow-y: auto;
}

.menu-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    border-radius: 10px;
    margin-bottom: 2px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    font-size: 0.9rem;
    font-weight: 500;
}

.menu-link:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
}

.menu-link.active {
    background: var(--primary);
    color: #fff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.menu-link .fa-chevron-down {
    font-size: 0.7rem;
    opacity: 0.5;
    transition: transform 0.3s;
}

.menu-link[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}

.menu-icon {
    width: 20px;
    margin-right: 12px;
    font-size: 1.1rem;
    opacity: 0.8;
}

.submenu {
    padding-left: 0;
    margin-bottom: 8px;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

.submenu-link {
    display: block;
    padding: 0.5rem 1rem 0.5rem 3rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.4);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s;
    position: relative;
}

.submenu-link:before {
    content: '';
    position: absolute;
    left: 1.75rem;
    top: 50%;
    width: 4px;
    height: 4px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translateY(-50%);
}

.submenu-link:hover, .submenu-link.active {
    color: #fff;
    background: rgba(255, 255, 255, 0.05);
}

.submenu-link.active:before {
    background: var(--primary);
    box-shadow: 0 0 8px var(--primary);
}

.sidebar-footer {
    padding: 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.05);
    background: rgba(0,0,0,0.2);
}

.sidebar-user-card {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar-user-card .avatar {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
}

/* Scrollbar minimal */
.sidebar::-webkit-scrollbar { width: 5px; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Buscador de Menú
    const menuSearch = document.getElementById('menuSearch');
    const menuItems = document.querySelectorAll('.menu-item');

    if (menuSearch) {
        menuSearch.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            
            menuItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                const isMatch = text.includes(term);
                item.style.display = isMatch ? 'block' : 'none';
                
                // Si hay match y es un submenú, expandirlo
                if (isMatch && term.length > 1) {
                    const submenu = item.querySelector('.submenu');
                    if (submenu) {
                        submenu.classList.add('show');
                        const link = item.querySelector('.menu-link');
                        if (link) link.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        });
    }

    // 2. Manejar el toggle de submenús (Mejorado)
    const menuLinks = document.querySelectorAll('.menu-link[data-bs-toggle="collapse"]');
    
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('data-bs-target');
            const target = document.querySelector(targetId);
            
            if (target) {
                const isOpen = target.classList.contains('show');
                
                // En móvil, prevenir que el sidebar se cierre al expandir submenú
                if (window.innerWidth < 992) {
                    e.stopPropagation();
                }
                
                // Cerrar otros menús si se abre uno nuevo
                if (!isOpen) {
                    document.querySelectorAll('.submenu.show').forEach(el => {
                        el.classList.remove('show');
                        const otherLink = document.querySelector(`[data-bs-target="#${el.id}"]`);
                        if (otherLink) otherLink.setAttribute('aria-expanded', 'false');
                    });
                }

                target.classList.toggle('show');
                this.setAttribute('aria-expanded', !isOpen);
            }
        });
    });
    
    // 3. Toggle unificado: móvil (.show) + escritorio (body.sidebar-collapsed)
    const closeSidebar  = document.getElementById('closeSidebar');
    const sidebar       = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.add('show');
        document.body.classList.remove('sidebar-collapsed');
    }

    function closeSidebarFn() {
        sidebar.classList.remove('show');
    }

    function toggleSidebar() {
        if (window.innerWidth < 992) {
            // Móvil: usar clase .show en el sidebar
            sidebar.classList.toggle('show');
        } else {
            // Escritorio: ocultar/mostrar via body class (CSS ajusta el margin)
            document.body.classList.toggle('sidebar-collapsed');
        }
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
    if (closeSidebar)  closeSidebar.addEventListener('click', closeSidebarFn);

    // Cerrar sidebar al cambiar a escritorio si estaba abierto en móvil
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('show');
        }
    });
    
    // 4. Auto-expandir activo
    const activeSub = document.querySelector('.submenu-link.active');
    if (activeSub) {
        const parent = activeSub.closest('.submenu');
        if (parent) {
            parent.classList.add('show');
            const parentLink = document.querySelector(`[data-bs-target="#${parent.id}"]`);
            if (parentLink) parentLink.setAttribute('aria-expanded', 'true');
        }
    }
});
</script>
