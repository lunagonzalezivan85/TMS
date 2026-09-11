<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
.sync-card {
    border-left: 4px solid #28a745;
    transition: all 0.3s ease;
}

.sync-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.direccion-item {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    margin-bottom: 10px;
    transition: all 0.2s;
}

.direccion-item:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.direccion-item.selected {
    border-color: #28a745;
    background-color: #d4edda;
}

.stats-row {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.btn-sync {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    color: white;
    transition: all 0.3s;
}

.btn-sync:hover {
    background: linear-gradient(45deg, #20c997, #28a745);
    transform: translateY(-1px);
    color: white;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#mapModal .modal-dialog {
    max-width: 800px;
}

#importMap {
    height: 400px;
    width: 100%;
}

.coordinate-display {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
    margin: 10px 0;
}

.alert-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
    border: none;
}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-sync-alt me-2"></i>Sincronizar Direcciones
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('direcciones') ?>">Direcciones</a></li>
                    <li class="breadcrumb-item active">Sincronizar</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('direcciones') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <button type="button" class="btn btn-sync" onclick="cargarDireccionesSqlServer()">
                <i class="fas fa-sync-alt me-2"></i>Sincronizar Ahora
            </button>
        </div>
    </div>

    <!-- Información -->
    <div class="alert alert-info mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-sync-alt fa-2x me-3"></i>
            <div>
                <h5 class="mb-1">Sincronización con ERP SAG</h5>
                <p class="mb-0">
                    Integra automáticamente las direcciones desde el sistema ERP SAG, 
                    identificando registros nuevos y manteniendo la consistencia de datos 
                    entre ambas plataformas de manera eficiente y segura.
                </p>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="stats-row">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="mb-2">
                    <i class="fas fa-database fa-3x mb-2"></i>
                    <h4 class="mb-0" id="totalSqlServer">-</h4>
                    <small>Total SQL Server</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-2">
                    <i class="fas fa-check-circle fa-3x mb-2"></i>
                    <h4 class="mb-0" id="totalExistentes">-</h4>
                    <small>Ya Sincronizadas</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-2">
                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                    <h4 class="mb-0" id="totalPendientes">-</h4>
                    <small>Pendientes</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-2">
                    <i class="fas fa-download fa-3x mb-2"></i>
                    <h4 class="mb-0" id="totalImportadas">0</h4>
                    <small>Importadas Hoy</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list me-2"></i>Direcciones Pendientes de Sincronización
                        </h6>
                        <div>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="importarTodas()" id="btnImportarTodas" disabled>
                                <i class="fas fa-download me-1"></i>Importar Todas
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="seleccionarTodas()">
                                <i class="fas fa-check-square me-1"></i>Seleccionar Todas
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="direccionesPendientes">
                        <div class="text-center py-5">
                            <i class="fas fa-sync-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Haz clic en "Sincronizar Ahora" para cargar las direcciones pendientes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Importación con Mapa -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">
                    <i class="fas fa-map-marker-alt me-2"></i>Capturar Geolocalización
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="alert alert-info">
                            <strong>Dirección:</strong> <span id="direccionTexto"></span><br>
                            <strong>Código:</strong> <span id="codigoTexto"></span>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchAddress" class="form-control" 
                                   placeholder="Buscar dirección en el mapa...">
                            <button class="btn btn-outline-primary" type="button" onclick="buscarEnMapa()">
                                Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div id="importMap"></div>
                    </div>
                    <div class="col-12">
                        <div class="coordinate-display">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label"><strong>Latitud:</strong></label>
                                    <input type="number" id="latitudInput" class="form-control" step="any" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="form-label"><strong>Longitud:</strong></label>
                                    <input type="number" id="longitudInput" class="form-control" step="any" readonly>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="obtenerUbicacionActual()">
                                    <i class="fas fa-crosshairs me-1"></i>Mi Ubicación
                                </button>
                                <small class="text-muted ms-2">Haz clic en el mapa para seleccionar coordenadas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarImportacion()" id="btnConfirmarImport" disabled>
                    <i class="fas fa-download me-2"></i>Importar Dirección
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="text-center">
        <div class="spinner"></div>
        <p class="text-white mt-3">Procesando...</p>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let direccionesPendientes = [];
let direccionSeleccionada = null;
let importMap = null;
let selectedMarker = null;
let selectedCoordinates = null;

// Cargar direcciones de SQL Server
function cargarDireccionesSqlServer() {
    mostrarLoading(true);
    
    fetch('<?= base_url('direcciones/getDireccionesSqlServer') ?>')
        .then(response => response.json())
        .then(data => {
            mostrarLoading(false);
            console.log('Respuesta del servidor:', data);
            
            if (data.success) {
                // Verificar que data.direcciones existe y es un array
                if (data.direcciones && Array.isArray(data.direcciones)) {
                    direccionesPendientes = data.direcciones;
                    actualizarEstadisticas(data);
                    mostrarDireccionesPendientes(data.direcciones);
                } else {
                    console.error('data.direcciones no es un array válido:', data.direcciones);
                    mostrarDireccionesPendientes([]);
                }
            } else {
                // Mostrar diagnóstico detallado del error
                mostrarErrorDiagnostico(data);
            }
        })
        .catch(error => {
            mostrarLoading(false);
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al conectar con el servidor'
            });
        });
}

function actualizarEstadisticas(data) {
    // Usar total_sqlserver si está disponible, sino calcular
    const totalSqlServer = data.total_sqlserver || (data.total + data.existentes);
    
    document.getElementById('totalSqlServer').textContent = totalSqlServer;
    document.getElementById('totalExistentes').textContent = data.existentes || 0;
    document.getElementById('totalPendientes').textContent = data.total || 0;
    
    // Habilitar botón si hay pendientes
    if ((data.total || 0) > 0) {
        document.getElementById('btnImportarTodas').disabled = false;
    }
}

function mostrarDireccionesPendientes(direcciones) {
    const container = document.getElementById('direccionesPendientes');
    
    // Verificar que direcciones sea un array
    if (!Array.isArray(direcciones)) {
        console.error('direcciones no es un array:', direcciones);
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="text-warning">Error en los datos</h5>
                <p class="text-muted">Los datos recibidos no tienen el formato esperado</p>
            </div>
        `;
        return;
    }
    
    if (direcciones.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-success">¡Todas las direcciones están sincronizadas!</h5>
                <p class="text-muted">No hay direcciones pendientes de importar</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    direcciones.forEach((direccion, index) => {
        html += `
            <div class="direccion-item p-3" data-id="${direccion.ID_ADDRESS}">
                <div class="row align-items-center">
                    <div class="col-md-1">
                        <div class="form-check">
                            <input class="form-check-input direccion-checkbox" type="checkbox" 
                                   value="${direccion.ID_ADDRESS}" id="check_${direccion.ID_ADDRESS}">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h6 class="mb-1 text-primary">${direccion.DIRECCION}</h6>
                        <small class="text-muted">
                            <strong>ID:</strong> ${direccion.ID_ADDRESS} | 
                            <strong>Cliente:</strong> ${direccion.CODIGO_DE_CLIENTE || 'N/A'}
                        </small>
                        ${direccion.LATITUD && direccion.LONGITUD ? 
                            `<br><small class="text-success"><i class="fas fa-map-marker-alt"></i> 
                            Coordenadas: ${direccion.LATITUD}, ${direccion.LONGITUD}</small>` : 
                            `<br><small class="text-warning"><i class="fas fa-exclamation-triangle"></i> 
                            Sin coordenadas</small>`
                        }
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-success btn-sm" onclick="abrirModalImportacion('${direccion.ID_ADDRESS}')">
                            <i class="fas fa-download me-1"></i>Importar
                        </button>
                        ${direccion.LATITUD && direccion.LONGITUD ? 
                            `<button class="btn btn-outline-info btn-sm ms-1" onclick="verEnMapa(${direccion.LATITUD}, ${direccion.LONGITUD})">
                                <i class="fas fa-map"></i>
                            </button>` : ''
                        }
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function abrirModalImportacion(idAddress) {
    const direccion = direccionesPendientes.find(d => d.ID_ADDRESS == idAddress);
    if (!direccion) return;
    
    direccionSeleccionada = direccion;
    
    // Llenar información del modal
    document.getElementById('direccionTexto').textContent = direccion.DIRECCION;
    document.getElementById('codigoTexto').textContent = direccion.ID_ADDRESS;
    document.getElementById('searchAddress').value = direccion.DIRECCION;
    
    // Limpiar coordenadas
    document.getElementById('latitudInput').value = '';
    document.getElementById('longitudInput').value = '';
    selectedCoordinates = null;
    document.getElementById('btnConfirmarImport').disabled = true;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('mapModal'));
    modal.show();
    
    // Inicializar mapa después de que el modal se muestre
    setTimeout(() => {
        initImportMap();
        
        // Si tiene coordenadas, centrar ahí
        if (direccion.LATITUD && direccion.LONGITUD) {
            importMap.setView([direccion.LATITUD, direccion.LONGITUD], 16);
            agregarMarcador(direccion.LATITUD, direccion.LONGITUD);
        }
    }, 300);
}

function initImportMap() {
    if (importMap) {
        importMap.remove();
    }
    
    // Coordenadas por defecto (Lima, Perú)
    importMap = L.map('importMap').setView([-12.046374, -77.042793], 11);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(importMap);
    
    // Evento de click en el mapa
    importMap.on('click', function(e) {
        agregarMarcador(e.latlng.lat, e.latlng.lng);
    });
}

function agregarMarcador(lat, lng) {
    // Remover marcador anterior
    if (selectedMarker) {
        importMap.removeLayer(selectedMarker);
    }
    
    // Agregar nuevo marcador
    selectedMarker = L.marker([lat, lng]).addTo(importMap);
    
    // Actualizar coordenadas
    selectedCoordinates = { lat: lat, lng: lng };
    document.getElementById('latitudInput').value = lat.toFixed(6);
    document.getElementById('longitudInput').value = lng.toFixed(6);
    
    // Habilitar botón de importar
    document.getElementById('btnConfirmarImport').disabled = false;
}

function buscarEnMapa() {
    const direccion = document.getElementById('searchAddress').value;
    if (!direccion) return;
    
    // Usar API de geocodificación
    fetch('<?= base_url('direcciones/geocode') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ address: direccion })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.results.length > 0) {
            const result = data.results[0];
            importMap.setView([result.lat, result.lon], 16);
            agregarMarcador(result.lat, result.lon);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'No encontrado',
                text: 'No se pudo encontrar la dirección en el mapa'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function obtenerUbicacionActual() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            importMap.setView([lat, lng], 16);
            agregarMarcador(lat, lng);
        }, function(error) {
            Swal.fire({
                icon: 'error',
                title: 'Error de Geolocalización',
                text: 'No se pudo obtener tu ubicación actual'
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'No Soportado',
            text: 'Tu navegador no soporta geolocalización'
        });
    }
}

function confirmarImportacion() {
    if (!selectedCoordinates || !direccionSeleccionada) return;
    
    mostrarLoading(true);
    
    const datosImportacion = {
        ID_ADDRESS: direccionSeleccionada.ID_ADDRESS,
        DIRECCION: direccionSeleccionada.DIRECCION,
        CODIGO_DE_CLIENTE: direccionSeleccionada.CODIGO_DE_CLIENTE,
        CIUDAD: direccionSeleccionada.CIUDAD,
        latitud: selectedCoordinates.lat,
        longitud: selectedCoordinates.lng
    };
    
    fetch('<?= base_url('direcciones/importarDireccion') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datosImportacion)
    })
    .then(response => response.json())
    .then(data => {
        mostrarLoading(false);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Importado!',
                text: 'Dirección importada correctamente'
            });
            
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('mapModal')).hide();
            
            // Actualizar contador
            const totalImportadas = parseInt(document.getElementById('totalImportadas').textContent) + 1;
            document.getElementById('totalImportadas').textContent = totalImportadas;
            
            // Remover de la lista
            removerDireccionDeLista(direccionSeleccionada.ID_ADDRESS);
            
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        mostrarLoading(false);
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al importar la dirección'
        });
    });
}

function removerDireccionDeLista(idAddress) {
    // Remover del array
    direccionesPendientes = direccionesPendientes.filter(d => d.ID_ADDRESS != idAddress);
    
    // Remover del DOM
    const elemento = document.querySelector(`[data-id="${idAddress}"]`);
    if (elemento) {
        elemento.remove();
    }
    
    // Actualizar estadísticas
    const totalPendientes = parseInt(document.getElementById('totalPendientes').textContent) - 1;
    document.getElementById('totalPendientes').textContent = totalPendientes;
    
    // Si no quedan pendientes, mostrar mensaje
    if (direccionesPendientes.length === 0) {
        mostrarDireccionesPendientes([]);
        document.getElementById('btnImportarTodas').disabled = true;
    }
}

function seleccionarTodas() {
    const checkboxes = document.querySelectorAll('.direccion-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}

function importarTodas() {
    const checkboxes = document.querySelectorAll('.direccion-checkbox:checked');
    
    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Selección requerida',
            text: 'Selecciona al menos una dirección para importar'
        });
        return;
    }
    
    Swal.fire({
        title: '¿Importar direcciones seleccionadas?',
        text: `Se importarán ${checkboxes.length} direcciones SIN coordenadas. Podrás agregar las coordenadas después.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, importar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            procesarImportacionMasiva(checkboxes);
        }
    });
}

function procesarImportacionMasiva(checkboxes) {
    mostrarLoading(true);
    let procesadas = 0;
    let exitosas = 0;
    
    Array.from(checkboxes).forEach((checkbox, index) => {
        const idAddress = checkbox.value;
        const direccion = direccionesPendientes.find(d => d.ID_ADDRESS == idAddress);
        
        if (direccion) {
            const datosImportacion = {
                ID_ADDRESS: direccion.ID_ADDRESS,
                DIRECCION: direccion.DIRECCION,
                CODIGO_DE_CLIENTE: direccion.CODIGO_DE_CLIENTE,
                CIUDAD: direccion.CIUDAD
                // Sin coordenadas para importación masiva
            };
            
            fetch('<?= base_url('direcciones/importarDireccion') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datosImportacion)
            })
            .then(response => response.json())
            .then(data => {
                procesadas++;
                if (data.success) {
                    exitosas++;
                    removerDireccionDeLista(idAddress);
                }
                
                // Si es la última
                if (procesadas === checkboxes.length) {
                    mostrarLoading(false);
                    
                    Swal.fire({
                        icon: exitosas === procesadas ? 'success' : 'warning',
                        title: 'Importación Completada',
                        text: `${exitosas} de ${procesadas} direcciones importadas correctamente`
                    });
                    
                    // Actualizar contador
                    const totalImportadas = parseInt(document.getElementById('totalImportadas').textContent) + exitosas;
                    document.getElementById('totalImportadas').textContent = totalImportadas;
                }
            })
            .catch(error => {
                procesadas++;
                console.error('Error:', error);
                
                if (procesadas === checkboxes.length) {
                    mostrarLoading(false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en importación masiva',
                        text: 'Algunos elementos no se pudieron importar'
                    });
                }
            });
        }
    });
}

function verEnMapa(lat, lng) {
    // Abrir en nueva ventana con Google Maps
    const url = `https://www.google.com/maps?q=${lat},${lng}&z=16`;
    window.open(url, '_blank');
}

function mostrarErrorDiagnostico(data) {
    let diagnosticoHtml = '<div class="text-left">';
    
    // Error principal
    diagnosticoHtml += `<p><strong>Error:</strong> ${data.message}</p>`;
    
    if (data.error_details) {
        const details = data.error_details;
        
        // Extensiones PHP
        diagnosticoHtml += '<h6 class="mt-3 mb-2">📋 Extensiones PHP:</h6>';
        diagnosticoHtml += `<ul class="mb-2">`;
        diagnosticoHtml += `<li>SQLSRV: ${details.extensiones?.sqlsrv ? '✅ Cargada' : '❌ NO cargada'}</li>`;
        diagnosticoHtml += `<li>PDO_SQLSRV: ${details.extensiones?.pdo_sqlsrv ? '✅ Cargada' : '❌ NO cargada'}</li>`;
        diagnosticoHtml += `</ul>`;
        
        // Configuración
        diagnosticoHtml += '<h6 class="mt-3 mb-2">⚙️ Configuración:</h6>';
        diagnosticoHtml += `<ul class="mb-2">`;
        diagnosticoHtml += `<li>Config SQL Server: ${details.configuracion?.existe_config_sqlserver ? '✅ Existe' : '❌ No existe'}</li>`;
        diagnosticoHtml += `<li>Servidor: ${details.configuracion?.hostname}</li>`;
        diagnosticoHtml += `<li>Base de datos: ${details.configuracion?.database}</li>`;
        diagnosticoHtml += `<li>Puerto: ${details.configuracion?.port}</li>`;
        diagnosticoHtml += `</ul>`;
        
        // Conectividad
        diagnosticoHtml += '<h6 class="mt-3 mb-2">🌐 Conectividad:</h6>';
        if (details.conectividad?.accesible) {
            diagnosticoHtml += `<p class="text-success">✅ ${details.conectividad.mensaje}</p>`;
        } else {
            diagnosticoHtml += `<p class="text-danger">❌ ${details.conectividad?.mensaje}</p>`;
            if (details.conectividad?.sugerencias) {
                diagnosticoHtml += '<p><strong>Sugerencias:</strong></p><ul>';
                details.conectividad.sugerencias.forEach(sugerencia => {
                    diagnosticoHtml += `<li>${sugerencia}</li>`;
                });
                diagnosticoHtml += '</ul>';
            }
        }
        
        // Análisis del error
        if (details.analisis_error && details.analisis_error.length > 0) {
            diagnosticoHtml += '<h6 class="mt-3 mb-2">🔍 Análisis del Error:</h6>';
            diagnosticoHtml += '<ul>';
            details.analisis_error.forEach(analisis => {
                diagnosticoHtml += `<li class="text-warning">${analisis}</li>`;
            });
            diagnosticoHtml += '</ul>';
        }
    }
    
    // Debug info
    if (data.debug_info) {
        diagnosticoHtml += '<h6 class="mt-3 mb-2">🐛 Información de Debug:</h6>';
        diagnosticoHtml += `<small class="text-muted">`;
        diagnosticoHtml += `<strong>Mensaje:</strong> ${data.debug_info.error_message}<br>`;
        diagnosticoHtml += `<strong>Código:</strong> ${data.debug_info.error_code}<br>`;
        diagnosticoHtml += `<strong>Archivo:</strong> ${data.debug_info.file}:${data.debug_info.line}`;
        diagnosticoHtml += `</small>`;
    }
    
    diagnosticoHtml += '</div>';
    
    Swal.fire({
        icon: 'error',
        title: 'Diagnóstico de Conexión SQL Server',
        html: diagnosticoHtml,
        width: '800px',
        showConfirmButton: true,
        confirmButtonText: 'Entendido',
        footer: '<small>Revisa la configuración en app/Config/Database.php</small>'
    });
}

function mostrarLoading(show) {
    const overlay = document.getElementById('loadingOverlay');
    overlay.style.display = show ? 'flex' : 'none';
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Auto-cargar direcciones al entrar
    // cargarDireccionesSqlServer();
});
</script>

<?= $this->endSection() ?>
