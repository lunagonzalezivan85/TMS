<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
#map {
    height: 400px;
    width: 100%;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.search-results {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-top: none;
    background: white;
    position: absolute;
    width: 100%;
    z-index: 1000;
}

.search-result-item {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.coordinates-display {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $page_title ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('direcciones') ?>">Direcciones</a></li>
                    <li class="breadcrumb-item active"><?= isset($direccion['id']) ? 'Editar' : 'Nueva' ?></li>
                </ol>
            </nav>
        </div>
        <a href="<?= base_url('direcciones') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="row">
        <!-- Formulario -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt me-2"></i>Información de la Dirección
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= isset($direccion['id']) ? base_url('direcciones/update/' . $direccion['id']) : base_url('direcciones/store') ?>">
                        <?= csrf_field() ?>
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-tag me-1"></i>Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" class="form-control" 
                                   value="<?= isset($direccion['nombre']) ? esc($direccion['nombre']) : '' ?>" 
                                   placeholder="Ej: Casa de Juan, Oficina Central, Almacén Norte..." required>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Nombre descriptivo para identificar fácilmente esta dirección
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label">
                                <i class="fas fa-home me-1"></i>Dirección <span class="text-danger">*</span>
                            </label>
                            <div class="position-relative">
                                <input type="text" name="direccion" id="direccion" class="form-control" 
                                       placeholder="Ingrese la dirección completa"
                                       value="<?= old('direccion') ?? $direccion['direccion'] ?? '' ?>" required>
                                <div id="searchResults" class="search-results" style="display: none;"></div>
                            </div>
                            <small class="form-text text-muted">
                                Escriba para buscar direcciones automáticamente
                            </small>
                        </div>

                        <!-- Ciudad -->
                        <div class="mb-3">
                            <label for="ciudad" class="form-label">
                                <i class="fas fa-city me-1"></i>Ciudad
                            </label>
                            <input type="text" name="ciudad" id="ciudad" class="form-control" 
                                   placeholder="Ciudad"
                                   value="<?= old('ciudad') ?? $direccion['ciudad'] ?? '' ?>">
                        </div>

                        <!-- Código de Integración -->
                        <div class="mb-3">
                            <label for="codigo_integracion" class="form-label">
                                <i class="fas fa-code me-1"></i>Código de Integración
                            </label>
                            <input type="text" name="codigo_integracion" id="codigo_integracion" class="form-control" 
                                   placeholder="Código para integración con otros sistemas"
                                   value="<?= old('codigo_integracion') ?? $direccion['codigo_integracion'] ?? '' ?>">
                        </div>

                        <!-- Coordenadas -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitud" class="form-label">
                                        <i class="fas fa-crosshairs me-1"></i>Latitud
                                    </label>
                                    <input type="number" name="latitud" id="latitud" class="form-control" 
                                           step="0.000001" placeholder="Ej: -12.046374"
                                           value="<?= old('latitud') ?? $direccion['latitud'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitud" class="form-label">
                                        <i class="fas fa-crosshairs me-1"></i>Longitud
                                    </label>
                                    <input type="number" name="longitud" id="longitud" class="form-control" 
                                           step="0.000001" placeholder="Ej: -77.042793"
                                           value="<?= old('longitud') ?? $direccion['longitud'] ?? '' ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Información de coordenadas -->
                        <div id="coordinatesInfo" class="coordinates-display" style="display: none;">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="coordinatesText">Haga clic en el mapa para seleccionar coordenadas</span>
                            </small>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button type="button" id="getCurrentLocation" class="btn btn-info">
                                    <i class="fas fa-location-arrow me-2"></i>Mi Ubicación
                                </button>
                                <button type="button" id="clearCoordinates" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Limpiar Coordenadas
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    <?= isset($direccion['id']) ? 'Actualizar' : 'Guardar' ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mapa -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map me-2"></i>Ubicación en el Mapa
                    </h6>
                </div>
                <div class="card-body">
                    <div id="map"></div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-mouse-pointer me-1"></i>
                            Haga clic en el mapa para seleccionar la ubicación exacta
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map;
let marker;
let searchTimeout;

// Inicializar mapa
function initMap() {
    // Coordenadas por defecto (Lima, Perú)
    const defaultLat = <?= $direccion['latitud'] ?? '-12.046374' ?>;
    const defaultLng = <?= $direccion['longitud'] ?? '-77.042793' ?>;
    
    map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    // Agregar capa de mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Agregar marcador si hay coordenadas
    if (defaultLat && defaultLng) {
        addMarker(defaultLat, defaultLng);
        updateCoordinatesDisplay(defaultLat, defaultLng);
    }
    
    // Evento de clic en el mapa
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        addMarker(lat, lng);
        updateCoordinates(lat, lng);
        reverseGeocode(lat, lng);
    });
}

// Agregar marcador al mapa
function addMarker(lat, lng) {
    if (marker) {
        map.removeLayer(marker);
    }
    
    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);
}

// Actualizar campos de coordenadas
function updateCoordinates(lat, lng) {
    document.getElementById('latitud').value = lat.toFixed(6);
    document.getElementById('longitud').value = lng.toFixed(6);
    updateCoordinatesDisplay(lat, lng);
}

// Actualizar información de coordenadas
function updateCoordinatesDisplay(lat, lng) {
    const info = document.getElementById('coordinatesInfo');
    const text = document.getElementById('coordinatesText');
    
    if (lat && lng) {
        text.textContent = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}

// Geocodificación inversa
function reverseGeocode(lat, lng) {
    fetch('<?= base_url('direcciones/reverseGeocode') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `lat=${lat}&lng=${lng}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.address && !document.getElementById('direccion').value) {
                document.getElementById('direccion').value = data.address;
            }
            if (data.city && !document.getElementById('ciudad').value) {
                document.getElementById('ciudad').value = data.city;
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Búsqueda de direcciones
function searchAddress(query) {
    if (query.length < 3) {
        hideSearchResults();
        return;
    }
    
    fetch('<?= base_url('direcciones/geocode') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `direccion=${encodeURIComponent(query)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.results) {
            showSearchResults(data.results);
        } else {
            hideSearchResults();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideSearchResults();
    });
}

// Mostrar resultados de búsqueda
function showSearchResults(results) {
    const container = document.getElementById('searchResults');
    container.innerHTML = '';
    
    results.forEach(result => {
        const item = document.createElement('div');
        item.className = 'search-result-item';
        item.textContent = result.display_name;
        item.onclick = () => selectSearchResult(result);
        container.appendChild(item);
    });
    
    container.style.display = 'block';
}

// Ocultar resultados de búsqueda
function hideSearchResults() {
    document.getElementById('searchResults').style.display = 'none';
}

// Seleccionar resultado de búsqueda
function selectSearchResult(result) {
    const lat = parseFloat(result.lat);
    const lng = parseFloat(result.lon);
    
    document.getElementById('direccion').value = result.display_name;
    
    // Extraer ciudad si está disponible
    if (result.address && result.address.city) {
        document.getElementById('ciudad').value = result.address.city;
    }
    
    addMarker(lat, lng);
    updateCoordinates(lat, lng);
    hideSearchResults();
}

// Obtener ubicación actual
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                addMarker(lat, lng);
                updateCoordinates(lat, lng);
                reverseGeocode(lat, lng);
            },
            function(error) {
                alert('Error al obtener la ubicación: ' + error.message);
            }
        );
    } else {
        alert('La geolocalización no es compatible con este navegador');
    }
}

// Limpiar coordenadas
function clearCoordinates() {
    document.getElementById('latitud').value = '';
    document.getElementById('longitud').value = '';
    
    if (marker) {
        map.removeLayer(marker);
        marker = null;
    }
    
    updateCoordinatesDisplay(null, null);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Búsqueda de direcciones
    document.getElementById('direccion').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchAddress(e.target.value);
        }, 500);
    });
    
    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            hideSearchResults();
        }
    });
    
    // Botones
    document.getElementById('getCurrentLocation').addEventListener('click', getCurrentLocation);
    document.getElementById('clearCoordinates').addEventListener('click', clearCoordinates);
    
    // Actualizar mapa cuando cambien las coordenadas manualmente
    document.getElementById('latitud').addEventListener('change', function() {
        const lat = parseFloat(this.value);
        const lng = parseFloat(document.getElementById('longitud').value);
        
        if (lat && lng) {
            addMarker(lat, lng);
            updateCoordinatesDisplay(lat, lng);
        }
    });
    
    document.getElementById('longitud').addEventListener('change', function() {
        const lat = parseFloat(document.getElementById('latitud').value);
        const lng = parseFloat(this.value);
        
        if (lat && lng) {
            addMarker(lat, lng);
            updateCoordinatesDisplay(lat, lng);
        }
    });
});
</script>

<?= $this->endSection() ?>
