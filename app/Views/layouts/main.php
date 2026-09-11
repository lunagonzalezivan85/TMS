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

    <!-- Bootstrap 5 CSS (local) -->
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
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Seguridad CSRF -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">
    <meta name="base-url" content="<?= base_url() ?>">

    <?= $this->renderSection('head') ?>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-600: #4338ca;
            --primary-50: #eef2ff;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
            --sidebar-width: 260px;
            --sidebar-mini-width: 80px;
            --radius: 16px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            --content-gutter: 2rem;
        }

        html, body { height: 100%; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* Layout Structure */
        .main2-wrapper { display: flex; min-height: 100vh; }
        
        #sidebar { 
            position: fixed; 
            top: 0; 
            left: 0; 
            height: 100vh; 
            z-index: 1040;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main2-content { 
            margin-left: var(--sidebar-width); 
            flex: 1; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 992px) {
            body.sidebar-mini .main2-content { margin-left: var(--sidebar-mini-width); }
        }
        body.sidebar-collapsed .main2-content { margin-left: 0; }

        /* Modern Top Navbar */
        .content-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            padding: 0.75rem var(--content-gutter);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-search {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            width: 300px;
            transition: all 0.2s;
        }

        .navbar-search:focus-within {
            background: #fff;
            box-shadow: 0 0 0 2px var(--primary-50);
            border: 1px solid var(--primary);
        }

        .navbar-search input {
            background: transparent;
            border: none;
            outline: none;
            margin-left: 8px;
            width: 100%;
            font-size: 0.9rem;
        }

        /* Page Headers */
        .page-heading { 
            padding: 2rem var(--content-gutter) 0.5rem; 
        }
        
        .page-title { 
            font-size: 1.875rem; 
            font-weight: 800; 
            letter-spacing: -0.025em;
            color: #0f172a;
        }
        
        .page-subtitle { 
            color: var(--muted); 
            font-size: 1rem;
            margin-top: 0.25rem;
        }
        
        .page-breadcrumb { 
            padding: 0 var(--content-gutter) 1.5rem; 
        }

        /* Content Area Enhancements */
        .content-area { 
            padding: 0 var(--content-gutter) 2rem; 
        }
        
        .card { 
            border: 1px solid var(--border); 
            border-radius: var(--radius); 
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            transition: all 0.2s;
        }

        .btn-white {
            background: #fff;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-white:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* ─── RESPONSIVE ──────────────────────────────────────── */

        /* Móvil: sidebar fuera del canvas */
        @media (max-width: 991.98px) {
            .main2-content { margin-left: 0 !important; }
        }

        /* Tablet (≤ 991px): reducir gutter y fuentes */
        @media (max-width: 991.98px) {
            :root { --content-gutter: 1.25rem; }
            .page-title  { font-size: 1.375rem; }
            .page-heading { padding-top: 1.25rem; }
        }

        /* Mobile (≤ 575px): gutter mínimo */
        @media (max-width: 575.98px) {
            :root { --content-gutter: 0.875rem; }
            .content-navbar { padding-left: 0.875rem; padding-right: 0.875rem; }
            .page-title  { font-size: 1.125rem; }
            .btn { padding: 0.5rem 1rem; font-size: 0.875rem; }
        }

        /* Eliminar doble padding: container-fluid/container ya no agrega lateral
           cuando está dentro de .content-area (el gutter lo maneja el layout) */
        .content-area > .container-fluid,
        .content-area > .container {
            padding-left: 0;
            padding-right: 0;
        }

        /* Tablas dentro del área de contenido: scroll horizontal en lugar de desborde */
        .content-area table {
            min-width: 0;
        }
        .content-area .table-responsive,
        .content-area .dataTables_wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Evitar overflow horizontal global en el contenido */
        .main2-content { overflow-x: hidden; }

        /* ─── Notification Sidebar Derecho ───────────────────── */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            background: #dc2626;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid #fff;
        }

        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: transparent;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .notification-btn:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        #notificationSidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 380px;
            max-width: 90vw;
            height: 100vh;
            background: #fff;
            border-left: 1px solid var(--border);
            box-shadow: -8px 0 24px rgba(0,0,0,0.08);
            z-index: 1080;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        #notificationSidebar.open {
            transform: translateX(0);
        }

        #notificationOverlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1070;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        #notificationOverlay.open {
            opacity: 1;
            visibility: visible;
        }

        .notif-sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notif-sidebar-body {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0;
        }

        .notif-sidebar-footer {
            padding: 0.75rem 1.5rem;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .notif-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            gap: 0.75rem;
            transition: background 0.15s;
            cursor: pointer;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-item.unread {
            background: #eff6ff;
        }

        .notif-item.unread:hover {
            background: #dbeafe;
        }

        .notif-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .notif-icon.warning { background: #fef3c7; color: #d97706; }
        .notif-icon.danger  { background: #fee2e2; color: #dc2626; }
        .notif-icon.info    { background: #dbeafe; color: #2563eb; }
        .notif-icon.success { background: #d1fae5; color: #16a34a; }

        .notif-content {
            flex: 1;
            min-width: 0;
        }

        .notif-title {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text);
            margin-bottom: 0.15rem;
        }

        .notif-desc {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.4;
        }

        .notif-time {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 0.25rem;
        }

        .notif-empty {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--muted);
        }

        @media (max-width: 575.98px) {
            #notificationSidebar { width: 100vw; }
        }
    </style>
</head>
<body>
<div class="main2-wrapper">
    <!-- Sidebar -->
    <?= $this->include('layouts/sidebar') ?>

    <!-- Contenido principal -->
    <div class="main2-content" id="mainContent">
        <!-- Content Navbar -->
        <div class="content-navbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn btn-light border-0 rounded-circle p-2" type="button" title="Mostrar/Ocultar menú" style="width: 40px; height: 40px;">
                    <i class="fas fa-indent fs-5"></i>
                </button>
                
                <?= $this->renderSection('navbar-left') ?>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <?= $this->renderSection('navbar-right') ?>

                <!-- Botón de Notificaciones -->
                <button class="notification-btn" id="notifToggle" title="Notificaciones">
                    <i class="fas fa-bell fs-5"></i>
                    <span class="notification-badge" id="notifBadge" style="display:none;">0</span>
                </button>
                
                <div class="vr h-100 mx-1 opacity-25 d-none d-md-block" style="min-height: 30px;"></div>
                
                <div class="dropdown">
                    <button class="btn btn-white border-0 d-flex align-items-center gap-2 p-1 pe-3 rounded-pill" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                            <?= strtoupper(substr(session('nombre') ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="d-none d-sm-inline small fw-bold"><?= esc(session('nombre') ?? 'Usuario') ?></span>
                        <i class="fas fa-chevron-down small opacity-50"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2 rounded-4">
                        <li><h6 class="dropdown-header text-uppercase small ls-wide opacity-50">Mi Gestión</h6></li>
                        <?php foreach ($userMenu as $menuItem): ?>
                            <?php if (isset($menuItem['divider'])): ?>
                                <li><hr class="dropdown-divider opacity-50"></li>
                            <?php else: ?>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 <?= $menuItem['class'] ?? '' ?>" href="<?= base_url($menuItem['url']) ?>">
                                        <i class="<?= $menuItem['icon'] ?> me-2 opacity-75"></i>
                                        <?= $menuItem['title'] ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Heading + Breadcrumbs (opcional) -->
        <?php if ($this->renderSection('page-header') !== ''): ?>
            <div class="page-heading">
                <?= $this->renderSection('page-header') ?>
            </div>
        <?php endif; ?>

        <?php if ($this->renderSection('breadcrumbs') !== ''): ?>
            <nav class="page-breadcrumb" aria-label="breadcrumb">
                <?= $this->renderSection('breadcrumbs') ?>
            </nav>
        <?php endif; ?>

        <!-- Contenido -->
        <div class="content-area">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<!-- Overlay para cerrar sidebar de notificaciones -->
<div id="notificationOverlay"></div>

<!-- Sidebar derecho de Notificaciones -->
<div id="notificationSidebar">
    <div class="notif-sidebar-header">
        <h6 class="fw-bold mb-0">
            <i class="fas fa-bell text-primary me-2"></i>Notificaciones
        </h6>
        <button class="btn btn-sm btn-light border-0 rounded-circle" id="notifClose" style="width:32px;height:32px;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="notif-sidebar-body" id="notifBody">
        <div class="notif-empty">
            <i class="fas fa-bell-slash fs-2 mb-2 d-block opacity-50"></i>
            <p class="small mb-0">No tienes notificaciones</p>
        </div>
    </div>
    <div class="notif-sidebar-footer">
        <a href="#" class="small text-muted text-decoration-none" id="notifMarkAll">Marcar todas como leídas</a>
    </div>
</div>

<!-- Botón flotante para cierre de bomba (FAB) -->
<div id="bomba-fab" class="d-none d-md-block">
    <a href="<?= base_url('lectura-bomba/cierre') ?>" class="btn btn-danger btn-fab shadow-lg" data-bs-toggle="tooltip" data-bs-placement="left" title="Cerrar Bomba">
        <i class="fas fa-power-off fa-lg"></i>
    </a>
</div>

<style>
.btn-fab {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    transition: all 0.3s ease;
}
.btn-fab:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
}
</style>

<!-- Core Scripts (External) -->
    <!-- Third Party -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <!-- Custom Core -->
    <script src="<?= base_url('public/assets/js/core/HttpClient.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/core/UI.js') ?>"></script>

    <!-- CSRF: inyectar token en todos los POST (fetch nativo + jQuery AJAX) -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name"  content="<?= csrf_token() ?>">
    <script>
    (function() {
        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>';

        // ── 1. Override window.fetch para inyectar CSRF en todos los POST fetch() ──
        var _fetch = window.fetch;
        window.fetch = function(url, opts) {
            opts = opts || {};
            if (opts.method && opts.method.toUpperCase() === 'POST') {
                // Header
                opts.headers = opts.headers || {};
                if (opts.headers instanceof Headers) {
                    opts.headers.set('X-CSRF-TOKEN', csrfHash);
                } else {
                    opts.headers['X-CSRF-TOKEN'] = csrfHash;
                }
                // Body — NO modificar si el Content-Type es application/json
                var isJson = false;
                if (opts.headers instanceof Headers) {
                    isJson = (opts.headers.get('content-type') || '').includes('application/json');
                } else if (opts.headers && opts.headers['Content-Type']) {
                    isJson = opts.headers['Content-Type'].includes('application/json');
                }
                if (opts.body instanceof URLSearchParams) {
                    opts.body.append(csrfName, csrfHash);
                } else if (opts.body instanceof FormData) {
                    opts.body.append(csrfName, csrfHash);
                } else if (typeof opts.body === 'string' && !isJson) {
                    opts.body += (opts.body ? '&' : '') + encodeURIComponent(csrfName) + '=' + encodeURIComponent(csrfHash);
                }
            }
            return _fetch.call(this, url, opts);
        };

        // ── 2. jQuery AJAX: header + body (DataTables y $.ajax genérico) ──
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfHash } });

        $(document).ajaxSend(function(e, xhr, settings) {
            if (settings.type === 'POST' || settings.type === 'post') {
                if (typeof settings.data === 'string') {
                    settings.data += (settings.data ? '&' : '') + encodeURIComponent(csrfName) + '=' + encodeURIComponent(csrfHash);
                } else if (settings.data instanceof FormData) {
                    settings.data.append(csrfName, csrfHash);
                } else if (settings.data && typeof settings.data === 'object') {
                    settings.data[csrfName] = csrfHash;
                } else {
                    settings.data = encodeURIComponent(csrfName) + '=' + encodeURIComponent(csrfHash);
                }
            }
        });
    })();
    </script>

<script>
// DataTables init hook compatible
(function(){
  function initDataTables(){
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
      if (typeof window.initDataTable === 'function') { window.initDataTable(); }
    }
  }
  $(document).ready(initDataTables);

  // Verificar si el usuario tiene apertura de bomba pendiente para mostrar FAB
  function checkBombaApertura() {
    console.log('Verificando apertura de bomba...');
    $.ajax({
      url: '<?= base_url('lectura-bomba/verificar-apertura-usuario') ?>',
      method: 'GET',
      success: function(response) {
        console.log('Respuesta AJAX:', response);
        if (response.has_pendiente) {
          console.log('Mostrando FAB, apertura ID:', response.apertura_id);
          // Solo mostrar FAB en desktop
          if (window.innerWidth >= 768) {
            $('#bomba-fab').removeClass('d-none');
          }
          $('#bomba-fab a').attr('href', '<?= base_url('lectura-bomba/cierre/') ?>' + response.apertura_id);
          // Mostrar botón en navegación móvil
          $('#bomba-mobile-nav').css('display', 'flex');
          $('#bomba-mobile-nav').attr('href', '<?= base_url('lectura-bomba/cierre/') ?>' + response.apertura_id);
        } else {
          console.log('Ocultando FAB');
          $('#bomba-fab').addClass('d-none');
          // Ocultar botón en navegación móvil
          $('#bomba-mobile-nav').css('display', 'none');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error verificando apertura:', error);
        $('#bomba-fab').addClass('d-none');
        $('#bomba-mobile-nav').css('display', 'none');
      }
    });
  }

  // Verificar al cargar la página
  $(document).ready(function() {
    checkBombaApertura();
  });

  // Verificar periódicamente (cada 30 segundos)
  setInterval(checkBombaApertura, 30000);
})();
</script>

<!-- Notification Sidebar Logic -->
<script>
(function() {
    const sidebar   = document.getElementById('notificationSidebar');
    const overlay   = document.getElementById('notificationOverlay');
    const btnToggle = document.getElementById('notifToggle');
    const btnClose  = document.getElementById('notifClose');
    const badge     = document.getElementById('notifBadge');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('open');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }

    if (btnToggle) btnToggle.addEventListener('click', openSidebar);
    if (btnClose)  btnClose.addEventListener('click', closeSidebar);
    if (overlay)   overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });

    // Exponer API global para que otros módulos puedan push notificaciones
    window.GMVNotifications = {
        updateBadge: function(count) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        },
        render: function(items) {
            const body = document.getElementById('notifBody');
            if (!body) return;

            if (!items || items.length === 0) {
                body.innerHTML = '<div class="notif-empty">' +
                    '<i class="fas fa-bell-slash fs-2 mb-2 d-block opacity-50"></i>' +
                    '<p class="small mb-0">No tienes notificaciones</p></div>';
                return;
            }

            body.innerHTML = items.map(function(n) {
                const iconClass = n.type || 'info';
                const unreadClass = n.read ? '' : 'unread';
                const clickAttr = n.url ? 'style="cursor:pointer" onclick="window.location.href=\'' + n.url + '\'"' : '';
                return '<div class="notif-item ' + unreadClass + '" data-id="' + (n.id || '') + '" ' + clickAttr + '>' +
                    '<div class="notif-icon ' + iconClass + '"><i class="' + (n.icon || 'fas fa-info-circle') + '"></i></div>' +
                    '<div class="notif-content">' +
                        '<div class="notif-title">' + (n.title || '') + '</div>' +
                        '<div class="notif-desc">' + (n.desc || '') + '</div>' +
                        '<div class="notif-time">' + (n.time || '') + '</div>' +
                    '</div>' +
                '</div>';
            }).join('');
        },
        open: openSidebar,
        close: closeSidebar
    };

    // Cargar alertas de documentos al iniciar
    (function loadDocAlerts() {
        fetch('<?= base_url("vehiculos/getAlertasDocumentos") ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.count > 0) {
                const items = data.items.map(function(a) {
                    return {
                        id: a.id,
                        title: a.title,
                        desc: a.desc,
                        time: a.time,
                        type: a.type,
                        icon: a.icon,
                        url: '<?= base_url("vehiculos/documentos/") ?>' + a.id_vehiculo,
                        read: false
                    };
                });
                window.GMVNotifications.updateBadge(data.count);
                window.GMVNotifications.render(items);
            }
        })
        .catch(function() {});
    })();
})();
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
