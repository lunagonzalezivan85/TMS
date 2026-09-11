<?php
use Config\Menu;

// Obtener el menú del usuario para el dropdown
$userMenu = Menu::getUserMenu();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'GMV Sistema - Dashboard' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
            --navbar-height: 56px; /* altura de la navbar interna del contenido */
            --sidebar-mini-width: 70px;
            --content-gutter: 24px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            font-size: 14px;
        }
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        /* Cuando el sidebar está colapsado, el contenido ocupa todo el ancho */
        body.sidebar-collapsed .main-content {
            margin-left: 0;
        }

        /* Modo mini (desktop): contenido con margen del ancho mini */
        @media (min-width: 992px) {
            body.sidebar-mini .main-content { margin-left: var(--sidebar-mini-width); }
        }
        
        /* Content Navbar (dentro del contenido, no fija global) */
        .content-navbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 8px var(--content-gutter);
        }
        .content-navbar .btn { padding: 6px 12px; }
        .page-heading { padding: 12px var(--content-gutter) 4px; }
        .page-title { font-size: 1.5rem; font-weight: 600; color: var(--dark-color); }
        .page-subtitle { color: var(--secondary-color); }
        .page-breadcrumb { padding: 0 var(--content-gutter) 12px; }
        .content-area { padding: 0 var(--content-gutter) var(--content-gutter); }

        /* Sidebar full height (no depende de navbar fija) */
        #sidebar {
            position: fixed;
            top: 0;
            height: 100vh;
            z-index: 1040;
        }
        /* Ancho normal y mini del sidebar en desktop */
        @media (min-width: 992px) {
            #sidebar { width: var(--sidebar-width); transition: width .3s ease, transform .3s ease; }
            body.sidebar-mini #sidebar { width: var(--sidebar-mini-width); overflow: hidden; }
            body.sidebar-mini #sidebar .menu-text { display: none !important; }
        }
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0; right: 0; bottom: 0;
            z-index: 1035;
            display: none;
            background: rgba(0,0,0,0.3);
        }
        .sidebar-overlay.show { display: block; }
        
        .navbar-left {
            display: flex;
            align-items: center;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--dark-color);
            margin-right: 15px;
            cursor: pointer;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: auto;
        }
        
        .user-dropdown {
            position: relative;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
        }
        
        /* Content Area */
        .content-area {
            padding: 30px;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }
        
        .page-subtitle {
            color: var(--secondary-color);
            margin: 5px 0 0 0;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 20px;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <!-- Incluir Sidebar -->
    <?= $this->include('layouts/sidebar') ?>
    
    <!-- Top Navbar -->
   
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container-fluid">
            <!-- Sidebar Toggle Button -->
            <button id="sidebarToggle" class="btn btn-outline-secondary me-2 d-none d-lg-inline-flex" type="button" title="Mostrar/Ocultar menú">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand" href="#">
                <i class="fas fa-car-side me-2"></i>GMV Sistema
            </a>
            
            <div class="d-flex align-items-center">
                <!-- User Dropdown -->
                <div class="dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Usuario Actual</h6></li>
                        <?php foreach ($userMenu as $menuItem): ?>
                            <?php if (isset($menuItem['divider'])): ?>
                                <li><hr class="dropdown-divider"></li>
                            <?php else: ?>
                                <li>
                                    <a class="dropdown-item <?= $menuItem['class'] ?? '' ?>" href="<?= base_url($menuItem['url']) ?>">
                                        <i class="<?= $menuItem['icon'] ?> me-2"></i>
                                        <?= $menuItem['title'] ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
        <!-- Content Area -->
        <div class="content-area ">
            <div class="mt-4 pt-2">
            <?php if ($this->renderSection('page-header') !== ''): ?>
                <div class="page-header">
                    <?= $this->renderSection('page-header') ?>
                </div>
            <?php endif; ?>
            
            <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <!-- jQuery (cargado en el head para compatibilidad con DataTables) -->
    <script>
        // Función para manejar el menú móvil
        function setupMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const closeSidebarBtn = document.getElementById('closeSidebar');
            const isDesktop = () => window.innerWidth >= 992;

            // Función para mostrar/ocultar sidebar
            function toggleSidebar(show = null) {
                console.log('toggleSidebar called with show =', show);
                if (!sidebar || !sidebarOverlay) {
                    console.error('Sidebar or overlay not found');
                    return;
                }

                
                const isOpen = sidebar.classList.contains('show');
                const shouldShow = show !== null ? show : !isOpen;
                
                console.log('Current state - isOpen:', isOpen, 'shouldShow:', shouldShow);
                
                if (shouldShow) {
                    console.log('Opening sidebar');
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.classList.add('show');
                    sidebarOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    // Quitar colapsado para que el contenido deje margen
                    document.body.classList.remove('sidebar-collapsed');
                } else {
                    console.log('Closing sidebar');
                    sidebar.style.transform = 'translateX(-100%)';
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                    // Marcar colapsado para que el contenido use todo el ancho
                    document.body.classList.add('sidebar-collapsed');
                }
            }
            
            // Eventos para abrir/cerrar el menú
            function setupEventListeners() {
                // Botón de toggle en la barra superior
                if (sidebarToggle) {
                    sidebarToggle.onclick = (e) => {
                        e.preventDefault();
                        if (isDesktop()) {
                            // En escritorio alterna modo mini vs expandido
                            document.body.classList.toggle('sidebar-mini');
                            // Limpiar colapsado (solo para móvil)
                            document.body.classList.remove('sidebar-collapsed');
                            // Persistir preferencia
                            try {
                                localStorage.setItem('sidebarState', document.body.classList.contains('sidebar-mini') ? 'mini' : 'expanded');
                            } catch(err) {}
                        } else {
                            // En móvil abre/cierra overlay
                            toggleSidebar();
                        }
                    };
                }
                
                // Botón de menú en la barra inferior
                if (mobileMenuToggle) {
                    mobileMenuToggle.onclick = (e) => {
                        e.preventDefault();
                        toggleSidebar(true);
                    };
                }
                
                // Botón de cierre del menú
                if (closeSidebarBtn) {
                    closeSidebarBtn.onclick = (e) => {
                        e.preventDefault();
                        toggleSidebar(false);
                    };
                }
                
                // Cerrar al hacer clic en el overlay
                if (sidebarOverlay) {
                    sidebarOverlay.onclick = () => toggleSidebar(false);
                }
                
                // Cerrar al hacer clic en un enlace del menú (solo en móviles)
                const menuLinks = document.querySelectorAll('.sidebar-menu a');
                menuLinks.forEach(link => {
                    link.onclick = () => {
                        if (window.innerWidth < 992) {
                            toggleSidebar(false);
                        }
                    };
                });
            }
            
            // Ajustar sidebar según el tamaño de pantalla
            function handleResize() {
                if (!sidebar) {
                    console.error('Sidebar not found in handleResize');
                    return;
                }
                
                console.log('Resize detected. Width:', window.innerWidth);
                
                if (window.innerWidth >= 992) {
                    // En escritorio
                    console.log('Desktop mode - showing sidebar');
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.classList.add('show');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('show');
                    }
                    document.body.style.overflow = '';
                    // En escritorio por defecto no colapsado
                    document.body.classList.remove('sidebar-collapsed');
                    // Restaurar preferencia de mini/expandido
                    try {
                        const saved = localStorage.getItem('sidebarState');
                        if (saved === 'mini') document.body.classList.add('sidebar-mini');
                        else document.body.classList.remove('sidebar-mini');
                    } catch(err) {}
                } else {
                    // En móviles
                    console.log('Mobile mode - checking sidebar state');
                    if (!sidebar.classList.contains('show')) {
                        sidebar.style.transform = 'translateX(-100%)';
                        document.body.classList.add('sidebar-collapsed');
                    } else {
                        sidebar.style.transform = 'translateX(0)';
                        document.body.classList.remove('sidebar-collapsed');
                    }
                    // En móvil nunca usamos mini
                    document.body.classList.remove('sidebar-mini');
                }
            }
            
            // Escuchar cambios de tamaño de ventana
            window.addEventListener('resize', handleResize);
            
            // Inicializar
            setupEventListeners();
            handleResize();
        }
        
        // Inicializar el menú móvil cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            setupMobileMenu();
            // Aplicar preferencia al cargar (solo desktop)
            if (window.innerWidth >= 992) {
                try {
                    const saved = localStorage.getItem('sidebarState');
                    if (saved === 'mini') document.body.classList.add('sidebar-mini');
                } catch(err) {}
            }
        });
    </script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    
    <!-- DataTables Initialization -->
    <script>
        // Función para inicializar DataTables cuando esté disponible
        function initDataTables() {
            // Verificar que jQuery y DataTables estén disponibles
            if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                // Inicializar DataTables si existe la función en la página
                if (typeof window.initDataTable === 'function') {
                    window.initDataTable();
                }
            } else {
                console.warn('jQuery o DataTables no están disponibles');
            }
        }
        
        // Inicializar cuando el documento esté listo
        $(document).ready(function() {
            initDataTables();
        });
    </script>
    
    <!-- Custom JS -->
    <script>
        // Funcionalidad del sidebar ya está implementada en setupMobileMenu() arriba
        // No duplicar código aquí para evitar errores
        
        // Auto-collapse submenu when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.sidebar')) {
                const openSubmenus = document.querySelectorAll('.submenu.show');
                openSubmenus.forEach(submenu => {
                    submenu.classList.remove('show');
                });
            }
        });
        
        // Keep active submenu open
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuLink = document.querySelector('.submenu-link.active');
            if (activeSubmenuLink) {
                const submenu = activeSubmenuLink.closest('.submenu');
                if (submenu) {
                    submenu.classList.add('show');
                }
            }
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>

    <!-- Mobile Bottom Navbar -->
    <nav class="mobile-bottom-navbar d-lg-none">
        <a href="#" class="mobile-nav-item" id="mobileMenuToggle">
            <i class="fas fa-bars"></i>
            <span>Menú</span>
        </a>
        <a href="<?= base_url() ?>" class="mobile-nav-item">
            <i class="fas fa-home"></i>
            <span>Inicio</span>
        </a>
        <a href="#" class="mobile-nav-item" id="mobileMenuToggle">
            <i class="fas fa-bars"></i>
            <span>Menú</span>
        </a>
        <a href="<?= base_url('perfil') ?>" class="mobile-nav-item">
            <i class="fas fa-user"></i>
            <span>Perfil</span>
        </a>
    </nav>

    <style>
        /* Estilos para la barra de navegación móvil */
        .mobile-bottom-navbar {
            position: fixed;
            bottom: 0;
            z-index: 1030;
            left: 0;
            right: 0;
            background: #fff;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            -webkit-tap-highlight-color: transparent;
        }
        
        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            text-decoration: none;
            padding: 8px 5px;
            font-size: 0.7rem;
            flex: 1;
            transition: all 0.2s;
            text-align: center;
            min-height: 56px;
        }
        
        .mobile-nav-item i {
            font-size: 1.4rem;
            margin-bottom: 4px;
            display: block;
        }
        
        .mobile-nav-item span {
            display: block;
            line-height: 1.1;
            font-size: 0.7rem;
        }
        
        .mobile-nav-item.active,
        .mobile-nav-item:active {
            color: #2563eb;
            background-color: rgba(37, 99, 235, 0.05);
        }
        
        /* Mejoras de accesibilidad en dispositivos táctiles */
        @media (hover: hover) and (pointer: fine) {
            .mobile-nav-item:hover {
                color: #2563eb;
                background-color: rgba(37, 99, 235, 0.05);
            }
        }

        @media (min-width: 992px) {
            .mobile-bottom-navbar {
                display: none;
            }
        }
    </style>

    <script>
        // Toggle sidebar en móviles
        document.getElementById('mobileMenuToggle')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Cerrar sidebar al hacer clic en un enlace
        document.querySelectorAll('.mobile-nav-item:not(#mobileMenuToggle)').forEach(link => {
            link.addEventListener('click', function() {
                document.getElementById('sidebar').classList.remove('show');
            });
        });
    </script>
</body>
</html>
