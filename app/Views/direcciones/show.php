<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
#map {
    height: 300px;
    width: 100%;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.info-card {
    border-left: 4px solid #007bff;
}

.coordinates-badge {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.9em;
}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-map-marker-alt me-2"></i>Detalles de la Dirección
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('direcciones') ?>">Direcciones</a></li>
                    <li class="breadcrumb-item active">Ver Detalles</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('direcciones') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <a href="<?= base_url('direcciones/edit/' . $direccion['id']) ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card shadow mb-4 info-card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información de la Dirección
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-tag me-1"></i>Nombre
                                </label>
                                <p class="h4 mb-0 text-primary"><?= esc($direccion['nombre'] ?? 'Sin nombre') ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-city me-1"></i>Ciudad
                                </label>
                                <p class="h6 mb-0"><?= esc($direccion['ciudad'] ?? 'No especificada') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-home me-1"></i>Dirección Completa
                                </label>
                                <p class="h5 mb-0"><?= esc($direccion['direccion']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-code me-1"></i>Código de Integración
                                </label>
                                <p class="mb-0">
                                    <?php if (!empty($direccion['codigo_integracion'])): ?>
                                        <span class="badge bg-info"><?= esc($direccion['codigo_integracion']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">No asignado</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="fas fa-crosshairs me-1"></i>Coordenadas
                                </label>
                                <p class="mb-0">
                                    <?php if (!empty($direccion['latitud']) && !empty($direccion['longitud'])): ?>
                                        <span class="coordinates-badge">
                                            <?= number_format($direccion['latitud'], 6) ?>, <?= number_format($direccion['longitud'], 6) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">No especificadas</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Mapa -->
                    <?php if (!empty($direccion['latitud']) && !empty($direccion['longitud'])): ?>
                    <div class="mt-4">
                        <label class="form-label text-muted">
                            <i class="fas fa-map me-1"></i>Ubicación en el Mapa
                        </label>
                        <div id="map" class="mt-2"></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Acciones Rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('direcciones/edit/' . $direccion['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar Dirección
                        </a>
                        
                        <?php if (!empty($direccion['latitud']) && !empty($direccion['longitud'])): ?>
                        <button type="button" class="btn btn-info" onclick="abrirEnGoogleMaps()">
                            <i class="fas fa-external-link-alt me-2"></i>Ver en Google Maps
                        </button>
                        
                        <button type="button" class="btn btn-warning" onclick="abrirEnWaze()">
                            <i class="fab fa-waze me-2"></i>Abrir en Waze
                        </button>
                        
                        <button type="button" class="btn btn-success" onclick="copiarCoordenadas()">
                            <i class="fas fa-copy me-2"></i>Copiar Coordenadas
                        </button>
                        <?php endif; ?>
                        
                        <button type="button" class="btn btn-outline-danger" onclick="confirmarEliminacion()">
                            <i class="fas fa-trash me-2"></i>Eliminar Dirección
                        </button>
                    </div>
                </div>
            </div>

            <!-- Información de Auditoría -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Información de Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">
                            <i class="fas fa-user-plus me-1"></i>Creado por
                        </label>
                        <p class="mb-0"><?= esc($direccion['usuario_crea'] ?? 'Sistema') ?></p>
                        <small class="text-muted">
                            <?= date('d/m/Y H:i', strtotime($direccion['fecha_registro'])) ?>
                        </small>
                    </div>

                    <?php if (!empty($direccion['usuario_actualizacion'])): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted">
                            <i class="fas fa-user-edit me-1"></i>Última actualización
                        </label>
                        <p class="mb-0"><?= esc($direccion['usuario_actualizacion']) ?></p>
                        <small class="text-muted">
                            <?= date('d/m/Y H:i', strtotime($direccion['fecha_actualizacion'])) ?>
                        </small>
                    </div>
                    <?php endif; ?>

                    <div class="mb-0">
                        <label class="form-label text-muted">
                            <i class="fas fa-toggle-on me-1"></i>Estado
                        </label>
                        <p class="mb-0">
                            <?php if ($direccion['estado'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Direcciones Cercanas -->
            <?php if (!empty($direccion['latitud']) && !empty($direccion['longitud'])): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-pin me-2"></i>Direcciones Cercanas
                    </h6>
                </div>
                <div class="card-body">
                    <div id="direccionesCercanas">
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 mb-0 text-muted">Buscando direcciones cercanas...</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar esta dirección?</p>
                <div class="alert alert-warning">
                    <strong>Dirección:</strong> <?= esc($direccion['direccion']) ?>
                </div>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="eliminarDireccion()">
                    <i class="fas fa-trash me-2"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map;

// Inicializar mapa si hay coordenadas
<?php if (!empty($direccion['latitud']) && !empty($direccion['longitud'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    cargarDireccionesCercanas();
});

function initMap() {
    const lat = <?= $direccion['latitud'] ?>;
    const lng = <?= $direccion['longitud'] ?>;
    
    map = L.map('map').setView([lat, lng], 15);
    
    // Agregar capa de mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Agregar marcador
    L.marker([lat, lng]).addTo(map)
        .bindPopup('<?= esc($direccion['direccion']) ?>')
        .openPopup();
}

// Cargar direcciones cercanas
function cargarDireccionesCercanas() {
    fetch('<?= base_url('direcciones/nearby/' . $direccion['id']) ?>')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('direccionesCercanas');
            
            if (data.success) {
                if (data.direcciones && data.direcciones.length > 0) {
                    let html = '';
                    data.direcciones.forEach(dir => {
                        html += `
                            <div class="mb-2 p-2 border rounded direccion-cercana" 
                                 style="cursor: pointer; transition: all 0.2s;" 
                                 onclick="irADireccion(${dir.id})"
                                 onmouseover="this.style.backgroundColor='#f8f9fa'"
                                 onmouseout="this.style.backgroundColor='white'">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-primary fw-bold">${dir.direccion}</small>
                                        <br>
                                        <small class="text-muted">${dir.ciudad || 'Sin ciudad'}</small>
                                    </div>
                                    <div class="text-end">
                                        <small class="badge bg-light text-dark">${dir.distancia}m</small>
                                        <br>
                                        <small class="text-muted"><i class="fas fa-eye"></i> Ver</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p class="text-muted text-center mb-0">No hay direcciones cercanas</p>';
                }
            } else {
                container.innerHTML = `<p class="text-danger text-center mb-0">Error: ${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('direccionesCercanas').innerHTML = 
                '<p class="text-danger text-center mb-0">Error al cargar direcciones cercanas</p>';
        });
}

// Función para ir a otra dirección
function irADireccion(id) {
    window.location.href = '<?= base_url('direcciones/show/') ?>' + id;
}
<?php endif; ?>

// Abrir en Google Maps
function abrirEnGoogleMaps() {
    <?php if (!empty($direccion['latitud']) && !empty($direccion['longitud'])): ?>
    const lat = <?= $direccion['latitud'] ?>;
    const lng = <?= $direccion['longitud'] ?>;
    const url = `https://www.google.com/maps?q=${lat},${lng}`;
    window.open(url, '_blank');
    <?php endif; ?>
}

// Abrir en Waze
function abrirEnWaze() {
    <?php if (!empty($direccion['latitud']) && !empty($direccion['longitud'])): ?>
    const lat = <?= $direccion['latitud'] ?>;
    const lng = <?= $direccion['longitud'] ?>;
    
    // Detectar si es móvil para usar deep link o web
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    if (isMobile) {
        // Deep link para app móvil de Waze
        const wazeUrl = `waze://?ll=${lat},${lng}&navigate=yes`;
        const fallbackUrl = `https://waze.com/ul?ll=${lat},${lng}&navigate=yes`;
        
        // Intentar abrir la app, si falla usar web
        window.location.href = wazeUrl;
        
        // Fallback después de 2 segundos si la app no se abre
        setTimeout(() => {
            window.open(fallbackUrl, '_blank');
        }, 2000);
    } else {
        // Para desktop, usar Waze web
        const wazeWebUrl = `https://waze.com/ul?ll=${lat},${lng}&navigate=yes`;
        window.open(wazeWebUrl, '_blank');
    }
    <?php endif; ?>
}

// Copiar coordenadas al portapapeles
function copiarCoordenadas() {
    <?php if (!empty($direccion['latitud']) && !empty($direccion['longitud'])): ?>
    const coordenadas = '<?= $direccion['latitud'] ?>,<?= $direccion['longitud'] ?>';
    navigator.clipboard.writeText(coordenadas).then(function() {
        // Mostrar notificación de éxito
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '¡Coordenadas copiadas!',
            showConfirmButton: false,
            timer: 1500
        });
    }).catch(function() {
        // Fallback para navegadores que no soportan clipboard API
        Swal.fire({
            title: 'Coordenadas',
            text: coordenadas,
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    });
    <?php endif; ?>
}

// Confirmar eliminación
function confirmarEliminacion() {
    const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
    modal.show();
}

// Eliminar dirección
function eliminarDireccion() {
    fetch('<?= base_url('direcciones/delete/' . $direccion['id']) ?>', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Eliminado!',
                text: 'La dirección ha sido eliminada correctamente.',
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.href = '<?= base_url('direcciones') ?>';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo eliminar la dirección.',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al eliminar la dirección.',
            confirmButtonColor: '#ef4444'
        });
    })
    .finally(() => {
        bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
    });
}
</script>

<?= $this->endSection() ?>
