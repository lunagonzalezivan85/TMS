<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
.fc-event {
    cursor: pointer;
    border-radius: 4px;
    font-size: 12px;
}

.fc-event:hover {
    opacity: 0.8;
}

.calendar-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 3px;
}

.calendar-controls {
    margin-bottom: 20px;
}

.event-tooltip {
    max-width: 300px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-alt me-2"></i><?= $title ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('ordenes-trabajo') ?>">Órdenes de Trabajo</a></li>
                    <li class="breadcrumb-item active">Calendario</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('ordenes-trabajo') ?>" class="btn btn-secondary">
                <i class="fas fa-list me-1"></i>Vista Lista
            </a>
            <a href="<?= base_url('ordenes-trabajo/kanban') ?>" class="btn btn-info">
                <i class="fas fa-columns me-1"></i>Vista Kanban
            </a>
            <a href="<?= base_url('ordenes-trabajo/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Nueva Orden
            </a>
        </div>
    </div>

    <!-- Leyenda de colores -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-palette me-2"></i>Leyenda de Estados
            </h6>
        </div>
        <div class="card-body">
            <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #ffc107;"></div>
                    <span><strong>Pendiente</strong> - Esperando aprobación</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #fd7e14;"></div>
                    <span><strong>En Proceso</strong> - Trabajo en curso</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #28a745;"></div>
                    <span><strong>Finalizada</strong> - Trabajo completado</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #dc3545;"></div>
                    <span><strong>Rechazada</strong> - No aprobada</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Controles del calendario -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filtros y Controles
            </h6>
        </div>
        <div class="card-body">
            <div class="row calendar-controls">
                <div class="col-md-3">
                    <label for="filtro-estado">Filtrar por Estado:</label>
                    <select class="form-control" id="filtro-estado">
                        <option value="">Todos los estados</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="EN_PROCESO">En Proceso</option>
                        <option value="FINALIZADO">Finalizada</option>
                        <option value="RECHAZADO">Rechazada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro-vehiculo">Filtrar por Vehículo:</label>
                    <input type="text" class="form-control" id="filtro-vehiculo" placeholder="Placa del vehículo...">
                </div>
                <div class="col-md-3">
                    <label for="vista-calendario">Vista:</label>
                    <select class="form-control" id="vista-calendario">
                        <option value="dayGridMonth">Mes</option>
                        <option value="timeGridWeek">Semana</option>
                        <option value="timeGridDay">Día</option>
                        <option value="listWeek">Lista Semanal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div>
                        <button type="button" class="btn btn-primary" id="btn-hoy">
                            <i class="fas fa-calendar-day me-1"></i>Hoy
                        </button>
                        <button type="button" class="btn btn-success" id="btn-actualizar">
                            <i class="fas fa-sync me-1"></i>Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div id="calendario"></div>
        </div>
    </div>
</div>

<!-- Modal para detalles del evento -->
<div class="modal fade" id="modalDetalleEvento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalles de la Orden
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido-detalle-evento">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" class="btn btn-primary" id="btn-ver-orden">Ver Orden Completa</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendario');
    let calendar;
    
    // Eventos iniciales
    const eventosIniciales = <?= $eventos ?>;
    
    // Inicializar calendario
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        height: 'auto',
        events: eventosIniciales,
        eventClick: function(info) {
            mostrarDetalleEvento(info.event);
        },
        eventMouseEnter: function(info) {
            // Tooltip con información básica
            $(info.el).tooltip({
                title: `${info.event.extendedProps.vehiculo} - ${info.event.extendedProps.estado}`,
                placement: 'top',
                trigger: 'hover'
            });
        },
        eventDidMount: function(info) {
            // Agregar clases CSS según el estado
            info.el.classList.add('evento-' + info.event.extendedProps.estado.toLowerCase());
        }
    });
    
    calendar.render();
    
    // Controles del calendario
    document.getElementById('vista-calendario').addEventListener('change', function() {
        calendar.changeView(this.value);
    });
    
    document.getElementById('btn-hoy').addEventListener('click', function() {
        calendar.today();
    });
    
    document.getElementById('btn-actualizar').addEventListener('click', function() {
        actualizarEventos();
    });
    
    // Filtros
    document.getElementById('filtro-estado').addEventListener('change', aplicarFiltros);
    document.getElementById('filtro-vehiculo').addEventListener('input', aplicarFiltros);
    
    function aplicarFiltros() {
        const estadoFiltro = document.getElementById('filtro-estado').value;
        const vehiculoFiltro = document.getElementById('filtro-vehiculo').value.toLowerCase();
        
        // Filtrar eventos
        const eventosFiltrados = eventosIniciales.filter(evento => {
            const cumpleEstado = !estadoFiltro || evento.extendedProps.estado === estadoFiltro;
            const cumpleVehiculo = !vehiculoFiltro || 
                                 evento.extendedProps.vehiculo.toLowerCase().includes(vehiculoFiltro);
            
            return cumpleEstado && cumpleVehiculo;
        });
        
        // Actualizar calendario
        calendar.removeAllEvents();
        calendar.addEventSource(eventosFiltrados);
    }
    
    function mostrarDetalleEvento(evento) {
        const contenido = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-hashtag me-1"></i>Código</h6>
                    <p>${evento.title.split(' - ')[0]}</p>
                    
                    <h6><i class="fas fa-info-circle me-1"></i>Estado</h6>
                    <span class="badge badge-${getBadgeClass(evento.extendedProps.estado)}">
                        ${evento.extendedProps.estado}
                    </span>
                    
                    <h6 class="mt-3"><i class="fas fa-car me-1"></i>Vehículo</h6>
                    <p>${evento.extendedProps.vehiculo}</p>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-calendar me-1"></i>Fecha</h6>
                    <p>${formatearFecha(evento.start)}</p>
                    
                    <h6><i class="fas fa-user-cog me-1"></i>Mecánico</h6>
                    <p>${evento.extendedProps.mecanico}</p>
                    
                    <h6><i class="fas fa-exclamation-triangle me-1"></i>Prioridad</h6>
                    <span class="badge badge-${getPrioridadClass(evento.extendedProps.prioridad)}">
                        ${evento.extendedProps.prioridad}
                    </span>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6><i class="fas fa-clipboard-list me-1"></i>Descripción</h6>
                    <p>{{ ($solicitud['codigo_consecutivo'] ?? 'S-' . $solicitud['id']) . ' - ' . $solicitud['descripcion'] }}</p>
                </div>
            </div>
        `;
        
        document.getElementById('contenido-detalle-evento').innerHTML = contenido;
        
        $('#modalDetalleEvento').modal('show');
    }
    
    function actualizarEventos() {
        // Obtener rango visible del calendario
        const view = calendar.view;
        const start = view.activeStart.toISOString();
        const end = view.activeEnd.toISOString();
        
        // Llamada AJAX para obtener eventos actualizados
        fetch(`<?= base_url('ordenes-trabajo/api-eventos-calendario') ?>?start=${start}&end=${end}`)
            .then(response => response.json())
            .then(eventos => {
                calendar.removeAllEvents();
                calendar.addEventSource(eventos);
            })
            .catch(error => {
                console.error('Error al actualizar eventos:', error);
                Swal.fire('Error', 'No se pudieron actualizar los eventos', 'error');
            });
    }
    
    function getBadgeClass(estado) {
        const clases = {
            'PENDIENTE': 'warning',
            'EN_PROCESO': 'primary',
            'FINALIZADO': 'success',
            'RECHAZADO': 'danger'
        };
        return clases[estado] || 'secondary';
    }
    
    function getPrioridadClass(prioridad) {
        const clases = {
            'ALTA': 'danger',
            'MEDIA': 'warning',
            'NORMAL': 'info',
            'BAJA': 'secondary'
        };
        return clases[prioridad] || 'info';
    }
    
    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
});
</script>
<?= $this->endSection() ?>
