<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
#mainMap {
    height: 70vh;
    width: 100%;
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.stats-card {
    border-left: 4px solid #007bff;
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.direccion-item {
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 12px;
    margin: 8px 12px;
    border: 1px solid transparent;
}

.direccion-item:hover {
    background-color: #f0f7ff;
    transform: translateX(8px);
    border-color: #dbeafe;
}

.direccion-item.selected {
    background-color: #eef2ff;
    border-left: 5px solid #4f46e5;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
}

.sidebar-direcciones {
    max-height: 65vh;
    overflow-y: auto;
    padding-bottom: 20px;
}

/* Scrollbar styling */
.sidebar-direcciones::-webkit-scrollbar {
    width: 6px;
}
.sidebar-direcciones::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}

.marker-popup {
    max-width: 250px;
    padding: 5px;
}

.controls-panel {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid rgba(0,0,0,0.05);
}

.map-theme-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1000;
    background: white;
    padding: 5px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-map me-2"></i>Mapa de Direcciones
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('direcciones') ?>">Direcciones</a></li>
                    <li class="breadcrumb-item active">Mapa</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('direcciones') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-list me-2"></i>Lista
            </a>
            <a href="<?= base_url('direcciones/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva Dirección
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Direcciones</h6>
                            <h4 class="mb-0"><?= $estadisticas['total'] ?></h4>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Con Coordenadas</h6>
                            <h4 class="mb-0 text-success"><?= $estadisticas['con_coordenadas'] ?></h4>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-map-pin fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Sin Coordenadas</h6>
                            <h4 class="mb-0 text-warning"><?= $estadisticas['sin_coordenadas'] ?></h4>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">En el Mapa</h6>
                            <h4 class="mb-0 text-info" id="markersCount"><?= count($direcciones) ?></h4>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-map fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mapa Principal -->
        <div class="col-lg-8">
            <!-- Controles -->
            <div class="controls-panel animate__animated animate__fadeInDown">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="input-group shadow-sm rounded-pill overflow-hidden">
                            <span class="input-group-text border-0 bg-white ps-3"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control border-0 py-2" 
                                   placeholder="Filtrar por dirección o ciudad...">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex gap-2 justify-content-md-end mt-2 mt-md-0">
                            <div class="btn-group shadow-sm">
                                <button type="button" class="btn btn-white btn-sm border" onclick="changeMapStyle('osm')" id="btnStyleOSM">Mapa</button>
                                <button type="button" class="btn btn-white btn-sm border" onclick="changeMapStyle('satellite')" id="btnStyleSat">Satélite</button>
                                <button type="button" class="btn btn-white btn-sm border" onclick="changeMapStyle('dark')" id="btnStyleDark">Oscuro</button>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm shadow-sm" onclick="mostrarTodas()">
                                <i class="fas fa-expand me-1"></i>Ver Todas
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="card border-0 bg-transparent mb-4">
                <div class="card-body p-0 position-relative">
                    <div id="mainMap" class="animate__animated animate__zoomIn"></div>
                </div>
            </div>
        </div>
        </div>

        <!-- Sidebar con Lista -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Direcciones en el Mapa
                        <span class="badge bg-primary ms-2" id="totalDirecciones"><?= count($direcciones) ?></span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="sidebar-direcciones" id="listaDirecciones">
                        <!-- Se llena dinámicamente -->
                    </div>
                </div>
            </div>

            <!-- Leyenda -->
            <div class="card shadow mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Leyenda
                    </h6>
                </div>
                <div class="card-body">
                    <div class="legend">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2" style="width: 20px; height: 20px; background: #007bff; border-radius: 50%;"></div>
                            <span>Direcciones activas</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2" style="width: 20px; height: 20px; background: #28a745; border-radius: 50%;"></div>
                            <span>Dirección seleccionada</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-2" style="width: 20px; height: 20px; background: #ffc107; border-radius: 50%;"></div>
                            <span>Cluster de direcciones</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet MarkerCluster -->
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

<script>
let map;
let markers = [];
let markerClusterGroup;
let direccionesData = [];
let selectedMarker = null;
let clustersEnabled = true;
let currentLayer;

const mapLayers = {
    osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }),
    satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EBP, and the GIS User Community'
    }),
    dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 20
    })
};

// Inicializar mapa
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    cargarDirecciones();
    setupEventListeners();
});

function initMap() {
    map = L.map('mainMap', {
        zoomControl: false
    }).setView([-12.046374, -77.042793], 11);
    
    L.control.zoom({ position: 'bottomright' }).addTo(map);
    
    currentLayer = mapLayers.osm;
    currentLayer.addTo(map);
    document.getElementById('btnStyleOSM').classList.add('active', 'btn-primary');
    document.getElementById('btnStyleOSM').classList.remove('btn-white');
    
    // Inicializar cluster group
    markerClusterGroup = L.markerClusterGroup({
        chunkedLoading: true,
        maxClusterRadius: 50,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true
    });
    
    map.addLayer(markerClusterGroup);
}

function changeMapStyle(style) {
    if (currentLayer) map.removeLayer(currentLayer);
    currentLayer = mapLayers[style];
    currentLayer.addTo(map);
    
    // UI update
    ['btnStyleOSM', 'btnStyleSat', 'btnStyleDark'].forEach(id => {
        document.getElementById(id).classList.remove('active', 'btn-primary');
        document.getElementById(id).classList.add('btn-white');
    });
    
    const activeId = style === 'osm' ? 'btnStyleOSM' : (style === 'satellite' ? 'btnStyleSat' : 'btnStyleDark');
    document.getElementById(activeId).classList.add('active', 'btn-primary');
    document.getElementById(activeId).classList.remove('btn-white');
}

function cargarDirecciones() {
    // Usar datos del servidor
    direccionesData = <?= json_encode($direcciones) ?>;
    
    if (direccionesData && direccionesData.length > 0) {
        mostrarDireccionesEnMapa();
        mostrarDireccionesEnLista();
        ajustarVistaADirecciones();
    } else {
        document.getElementById('listaDirecciones').innerHTML = 
            '<div class="p-3 text-center text-muted">No hay direcciones con coordenadas</div>';
    }
}

function mostrarDireccionesEnMapa() {
    // Limpiar marcadores existentes
    markerClusterGroup.clearLayers();
    markers = [];
    
    direccionesData.forEach(function(direccion) {
        if (direccion.latitud && direccion.longitud) {
            // Crear marcador
            const marker = L.marker([direccion.latitud, direccion.longitud])
                .bindPopup(`
                    <div class="marker-popup">
                        <h6 class="mb-2">${direccion.direccion}</h6>
                        <p class="mb-1"><strong>Ciudad:</strong> ${direccion.ciudad || 'No especificada'}</p>
                        ${direccion.codigo_integracion ? `<p class="mb-1"><strong>Código:</strong> ${direccion.codigo_integracion}</p>` : ''}
                        <div class="mt-2">
                            <button class="btn btn-sm btn-primary" onclick="verDireccion(${direccion.id})">
                                <i class="fas fa-eye me-1"></i>Ver Detalles
                            </button>
                        </div>
                    </div>
                `);
            
            // Agregar evento de click
            marker.on('click', function() {
                seleccionarDireccion(direccion.id);
            });
            
            marker.direccionId = direccion.id;
            markers.push(marker);
            markerClusterGroup.addLayer(marker);
        }
    });
}

function mostrarDireccionesEnLista() {
    const container = document.getElementById('listaDirecciones');
    let html = '';
    
    direccionesData.forEach(function(direccion) {
        html += `
            <div class="direccion-item p-3 border-bottom" 
                 data-id="${direccion.id}"
                 onclick="centrarEnDireccion(${direccion.id})">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-primary">${direccion.direccion}</h6>
                        <small class="text-muted">${direccion.ciudad || 'Sin ciudad'}</small>
                        ${direccion.codigo_integracion ? `<br><small class="badge bg-light text-dark">${direccion.codigo_integracion}</small>` : ''}
                    </div>
                    <div class="text-end">
                        <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); verDireccion(${direccion.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function centrarEnDireccion(id) {
    const direccion = direccionesData.find(d => d.id == id);
    if (direccion) {
        map.flyTo([direccion.latitud, direccion.longitud], 17, {
            duration: 1.5,
            easeLinearity: 0.25
        });
        seleccionarDireccion(id, true);
        
        const marker = markers.find(m => m.direccionId == id);
        if (marker) {
            if (clustersEnabled) {
                markerClusterGroup.zoomToShowLayer(marker, () => {
                    marker.openPopup();
                });
            } else {
                marker.openPopup();
            }
        }
    }
}

function seleccionarDireccion(id, scroll = false) {
    // Remover selección anterior
    document.querySelectorAll('.direccion-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Agregar selección actual
    const item = document.querySelector(`[data-id="${id}"]`);
    if (item) {
        item.classList.add('selected');
        if (scroll) {
            item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
}

function verDireccion(id) {
    window.location.href = '<?= base_url('direcciones/show/') ?>' + id;
}

function centrarMapa() {
    map.setView([-12.046374, -77.042793], 11);
}

function mostrarTodas() {
    ajustarVistaADirecciones();
}

function ajustarVistaADirecciones() {
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

function toggleClusters() {
    if (clustersEnabled) {
        // Deshabilitar clusters
        map.removeLayer(markerClusterGroup);
        markers.forEach(marker => map.addLayer(marker));
        clustersEnabled = false;
        document.querySelector('[onclick="toggleClusters()"]').innerHTML = 
            '<i class="fas fa-layer-group me-1"></i>Activar Grupos';
    } else {
        // Habilitar clusters
        markers.forEach(marker => map.removeLayer(marker));
        map.addLayer(markerClusterGroup);
        clustersEnabled = true;
        document.querySelector('[onclick="toggleClusters()"]').innerHTML = 
            '<i class="fas fa-layer-group me-1"></i>Agrupar';
    }
}

function setupEventListeners() {
    // Búsqueda en tiempo real
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        
        document.querySelectorAll('.direccion-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
}
</script>

<?= $this->endSection() ?>
