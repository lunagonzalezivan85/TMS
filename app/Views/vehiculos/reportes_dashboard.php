<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Reportes de Vehículos
                    </h1>
                    <p class="text-muted mb-0">Panel unificado de análisis y reportes de la flota vehicular</p>
                </div>
                <div>
                    <span class="text-muted small"><?= date('d M Y, l') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title text-gray-800 mb-0">Resumen Analítico</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Total Vehículos -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-success text-white shadow-sm border-0 h-100">
                                <div class="card-body text-center">
                                    <div class="display-4 font-weight-bold mb-1" id="stat-total">
                                        <?= $estadisticas['total_vehiculos'] ?? 0 ?>
                                    </div>
                                    <div class="small">Total Vehículos</div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehículos Asignados -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-info text-white shadow-sm border-0 h-100">
                                <div class="card-body text-center">
                                    <div class="display-4 font-weight-bold mb-1" id="stat-asignados">
                                        <?= $estadisticas['asignados'] ?? 0 ?>
                                    </div>
                                    <div class="small">Vehículos Asignados</div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos por Vencer -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-warning text-white shadow-sm border-0 h-100">
                                <div class="card-body text-center">
                                    <div class="display-4 font-weight-bold mb-1" id="stat-documentos">
                                        <?= $estadisticas['documentos_vencer'] ?? 0 ?>
                                    </div>
                                    <div class="small">Documentos por Vencer</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sin Documentación -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-danger text-white shadow-sm border-0 h-100">
                                <div class="card-body text-center">
                                    <div class="display-4 font-weight-bold mb-1" id="stat-sin-docs">
                                        <?= $estadisticas['sin_documentacion'] ?? 0 ?>
                                    </div>
                                    <div class="small">Sin Documentación</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Controles -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <!-- Selector de Reporte -->
                        <div class="col-lg-4 col-md-6">
                            <label for="tipoReporte" class="form-label font-weight-bold">Tipo de Reporte</label>
                            <select class="form-select form-select-lg" id="tipoReporte" onchange="cambiarTipoReporte()">
                                <option value="">Seleccionar tipo de reporte...</option>
                                <option value="estado">Vehículos por Estado</option>
                                <option value="mantenimiento">Mantenimiento Vencido</option>
                                <option value="kilometraje">Reporte de Kilometraje</option>
                                <option value="documentos">Documentos por Vencer</option>
                                <option value="asignaciones">Asignaciones de Conductores</option>
                                <option value="sin_documentacion">Vehículos sin Documentación</option>
                            </select>
                        </div>

                        <!-- Filtro de Estado -->
                        <div class="col-lg-2 col-md-6">
                            <label for="filtroEstado" class="form-label font-weight-bold">Estado</label>
                            <select class="form-select" id="filtroEstado">
                                <option value="TODOS">Todos</option>
                                <option value="ACTIVO">Activo</option>
                                <option value="INACTIVO">Inactivo</option>
                                <option value="EN REPARACION">En Reparación</option>
                                <option value="FUERA DE SERVICIO">Fuera de Servicio</option>
                            </select>
                        </div>

                        <!-- Búsqueda General -->
                        <div class="col-lg-4 col-md-8">
                            <label for="filtroBusqueda" class="form-label font-weight-bold">Búsqueda General</label>
                            <input type="text" class="form-control" id="filtroBusqueda" 
                                   placeholder="Buscar por placa, marca, modelo, año...">
                        </div>

                        <!-- Botones de Acción -->
                        <div class="col-lg-2 col-md-4">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary btn-lg" onclick="generarReporte()" id="btnGenerar" disabled>
                                    <i class="fas fa-search me-1"></i>
                                    Generar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Área de Resultados -->
    <div class="row" id="areaResultados" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-gray-800 mb-0" id="tituloReporte">Resultados del Reporte</h5>
                        <small class="text-muted" id="descripcionReporte"></small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-success btn-sm me-2" onclick="exportarReporte()">
                            <i class="fas fa-file-excel me-1"></i>
                            Exportar Excel
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limpiarResultados()">
                            <i class="fas fa-times me-1"></i>
                            Limpiar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información de Filtros Aplicados -->
                    <div id="filtrosAplicados" class="mb-3" style="display: none;">
                        <div class="alert alert-info border-0 bg-light">
                            <strong><i class="fas fa-filter me-1"></i>Filtros aplicados:</strong>
                            <span id="filtrosTexto"></span>
                        </div>
                    </div>

                    <!-- Loading -->
                    <div id="loadingReporte" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted">Generando reporte...</p>
                    </div>

                    <!-- Tabla de Resultados -->
                    <div id="tablaResultados" class="table-responsive">
                        <!-- Se carga dinámicamente -->
                    </div>

                    <!-- Sin Resultados -->
                    <div id="sinResultados" class="text-center py-5" style="display: none;">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron resultados</h5>
                        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas del Reporte -->
    <div class="row mt-4" id="areaEstadisticas" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title text-gray-800 mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Resumen del Reporte
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row" id="estadisticasRapidas">
                        <!-- Se carga dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let tipoReporteActual = '';
let datosActuales = [];

// Configuraciones de reportes
const configReportes = {
    'estado': {
        titulo: 'Vehículos por Estado',
        descripcion: 'Lista completa de vehículos agrupados por su estado actual',
        endpoint: '<?= base_url('vehiculos/reporteVehiculosPorEstado') ?>',
        headers: ['Placa', 'Marca', 'Modelo', 'Año', 'Estado', 'Kilometraje', 'Tipo Unidad'],
        campos: ['placa', 'marca', 'modelo', 'anio', 'estado', 'kilometraje', 'tipo_unidad_desc']
    },
    'mantenimiento': {
        titulo: 'Mantenimiento Vencido',
        descripcion: 'Vehículos que requieren mantenimiento urgente (más de 90 días sin servicio)',
        endpoint: '<?= base_url('vehiculos/reporteMantenimientoVencido') ?>',
        headers: ['Placa', 'Marca', 'Modelo', 'Último Mantenimiento', 'Días sin Mantenimiento'],
        campos: ['placa', 'marca', 'modelo', 'ultimo_mantenimiento', 'dias_sin_mantenimiento']
    },
    'kilometraje': {
        titulo: 'Reporte de Kilometraje',
        descripcion: 'Análisis del kilometraje actual de todos los vehículos de la flota',
        endpoint: '<?= base_url('vehiculos/reporteKilometraje') ?>',
        headers: ['Placa', 'Marca', 'Modelo', 'Año', 'Kilometraje', 'Tipo Unidad'],
        campos: ['placa', 'marca', 'modelo', 'anio', 'kilometraje', 'tipo_unidad_desc']
    },
    'documentos': {
        titulo: 'Documentos por Vencer',
        descripcion: 'Documentos de vehículos que vencen en los próximos 30 días',
        endpoint: '<?= base_url('vehiculos/reporteDocumentosPorVencer') ?>',
        headers: ['Placa', 'Vehículo', 'Tipo Documento', 'Fecha Vencimiento', 'Días Restantes'],
        campos: ['placa', 'vehiculo_info', 'tipo_documento', 'fecha_vencimiento', 'dias_restantes']
    },
    'asignaciones': {
        titulo: 'Asignaciones de Conductores',
        descripcion: 'Estado actual de las asignaciones de conductores a vehículos',
        endpoint: '<?= base_url('vehiculos/reporteAsignaciones') ?>',
        headers: ['Placa', 'Vehículo', 'Conductor', 'DNI', 'Estado', 'Fecha Asignación'],
        campos: ['placa', 'vehiculo_info', 'conductor', 'dni', 'estado_desc', 'fecha_asignacion']
    },
    'sin_documentacion': {
        titulo: 'Vehículos sin Documentación',
        descripcion: 'Vehículos que no tienen ningún documento registrado en el sistema',
        endpoint: '<?= base_url('vehiculos/reporteVehiculosSinDocumentacion') ?>',
        headers: ['Placa', 'Marca', 'Modelo', 'Año', 'Estado', 'Kilometraje', 'Tipo Unidad', 'Tipo Operación'],
        campos: ['placa', 'marca', 'modelo', 'anio', 'estado', 'kilometraje', 'tipo_unidad_desc', 'tipo_operacion_desc']
    }
};

function cambiarTipoReporte() {
    const select = document.getElementById('tipoReporte');
    const btnGenerar = document.getElementById('btnGenerar');
    
    if (select.value) {
        btnGenerar.disabled = false;
        tipoReporteActual = select.value;
        
        // Actualizar título y descripción
        const config = configReportes[tipoReporteActual];
        document.getElementById('tituloReporte').textContent = config.titulo;
        document.getElementById('descripcionReporte').textContent = config.descripcion;
    } else {
        btnGenerar.disabled = true;
        tipoReporteActual = '';
        limpiarResultados();
    }
}

function generarReporte() {
    if (!tipoReporteActual) return;
    
    const config = configReportes[tipoReporteActual];
    const estado = document.getElementById('filtroEstado').value;
    const busqueda = document.getElementById('filtroBusqueda').value.trim();
    
    // Mostrar área de resultados y loading
    document.getElementById('areaResultados').style.display = 'block';
    document.getElementById('loadingReporte').style.display = 'block';
    document.getElementById('tablaResultados').innerHTML = '';
    document.getElementById('sinResultados').style.display = 'none';
    
    // Construir URL con parámetros
    let url = config.endpoint;
    const params = new URLSearchParams();
    
    if (estado && estado !== 'TODOS') {
        params.append('estado', estado);
    }
    if (busqueda) {
        params.append('busqueda', busqueda);
    }
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    // Mostrar filtros aplicados
    mostrarFiltrosAplicados(estado, busqueda);
    
    // Realizar petición con headers correctos
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('loadingReporte').style.display = 'none';
            
            if (data.success && data.data && data.data.length > 0) {
                datosActuales = data.data;
                mostrarTablaResultados(data.data, config);
                mostrarEstadisticasRapidas(data.data);
            } else {
                document.getElementById('sinResultados').style.display = 'block';
                document.getElementById('areaEstadisticas').style.display = 'none';
                datosActuales = [];
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingReporte').style.display = 'none';
            mostrarError('Error cargando el reporte');
        });
}

function mostrarFiltrosAplicados(estado, busqueda) {
    let filtros = [];
    
    if (estado && estado !== 'TODOS') {
        filtros.push(`<span class="badge bg-primary me-1">Estado: ${estado}</span>`);
    }
    if (busqueda) {
        filtros.push(`<span class="badge bg-info me-1">Búsqueda: "${busqueda}"</span>`);
    }
    
    const filtrosDiv = document.getElementById('filtrosAplicados');
    const filtrosTexto = document.getElementById('filtrosTexto');
    
    if (filtros.length > 0) {
        filtrosTexto.innerHTML = filtros.join(' ');
        filtrosDiv.style.display = 'block';
    } else {
        filtrosDiv.style.display = 'none';
    }
}

function mostrarTablaResultados(datos, config) {
    let html = `
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    ${config.headers.map(header => `<th>${header}</th>`).join('')}
                </tr>
            </thead>
            <tbody>
    `;
    
    datos.forEach(item => {
        html += '<tr>';
        config.campos.forEach(campo => {
            let valor = item[campo] || 'N/A';
            
            // Formateo especial según el campo
            if (campo === 'estado') {
                valor = getEstadoBadge(valor);
            } else if (campo === 'kilometraje') {
                valor = formatNumber(valor) + ' km';
            } else if (campo.includes('fecha')) {
                valor = formatDate(valor);
            } else if (campo === 'dias_sin_mantenimiento') {
                const badge = valor > 180 ? 'bg-danger' : 'bg-warning';
                valor = `<span class="badge ${badge}">${valor} días</span>`;
            } else if (campo === 'dias_restantes') {
                const badge = valor <= 7 ? 'bg-danger' : 'bg-warning';
                valor = `<span class="badge ${badge}">${valor} días</span>`;
            } else if (campo === 'estado_desc') {
                const badge = valor === 'Asignado' ? 'bg-success' : 'bg-secondary';
                valor = `<span class="badge ${badge}">${valor}</span>`;
            } else if (campo === 'vehiculo_info') {
                valor = `${item.marca} ${item.modelo} (${item.anio})`;
            } else if (campo === 'placa') {
                valor = `<strong>${valor}</strong>`;
            }
            
            html += `<td>${valor}</td>`;
        });
        html += '</tr>';
    });
    
    html += `
            </tbody>
        </table>
        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Total de registros encontrados: <strong>${datos.length}</strong>
            </small>
        </div>
    `;
    
    document.getElementById('tablaResultados').innerHTML = html;
}

function mostrarEstadisticasRapidas(datos) {
    if (!datos.length) {
        document.getElementById('areaEstadisticas').style.display = 'none';
        return;
    }
    
    let estadisticasHtml = '';
    
    // Estadísticas generales
    estadisticasHtml += `
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white border-0">
                <div class="card-body text-center">
                    <h4 class="mb-1">${datos.length}</h4>
                    <small>Total Registros</small>
                </div>
            </div>
        </div>
    `;
    
    // Si tiene campo estado, mostrar distribución
    if (datos[0].hasOwnProperty('estado')) {
        const conteoEstados = {};
        datos.forEach(item => {
            const estado = item.estado || 'Sin Estado';
            conteoEstados[estado] = (conteoEstados[estado] || 0) + 1;
        });
        
        let index = 0;
        const colores = ['bg-success', 'bg-warning', 'bg-danger', 'bg-info'];
        
        Object.entries(conteoEstados).forEach(([estado, cantidad]) => {
            const porcentaje = ((cantidad / datos.length) * 100).toFixed(1);
            const color = colores[index % colores.length];
            
            estadisticasHtml += `
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card ${color} text-white border-0">
                        <div class="card-body text-center">
                            <h4 class="mb-1">${cantidad}</h4>
                            <small>${estado}</small>
                            <div class="mt-1">
                                <small class="opacity-75">${porcentaje}%</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            index++;
        });
    }
    
    // Estadísticas específicas según el tipo de reporte
    if (tipoReporteActual === 'kilometraje') {
        const kilometrajes = datos.map(item => parseInt(item.kilometraje) || 0);
        const maxKm = Math.max(...kilometrajes);
        const minKm = Math.min(...kilometrajes);
        const avgKm = Math.round(kilometrajes.reduce((a, b) => a + b, 0) / kilometrajes.length);
        
        estadisticasHtml += `
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-white border-0">
                    <div class="card-body text-center">
                        <h4 class="mb-1">${formatNumber(maxKm)}</h4>
                        <small>Máximo KM</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-secondary text-white border-0">
                    <div class="card-body text-center">
                        <h4 class="mb-1">${formatNumber(avgKm)}</h4>
                        <small>Promedio KM</small>
                    </div>
                </div>
            </div>
        `;
    }
    
    if (tipoReporteActual === 'documentos') {
        const criticos = datos.filter(item => item.dias_restantes <= 7).length;
        const advertencia = datos.filter(item => item.dias_restantes > 7 && item.dias_restantes <= 15).length;
        
        estadisticasHtml += `
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-danger text-white border-0">
                    <div class="card-body text-center">
                        <h4 class="mb-1">${criticos}</h4>
                        <small>Críticos (≤7 días)</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white border-0">
                    <div class="card-body text-center">
                        <h4 class="mb-1">${advertencia}</h4>
                        <small>Advertencia (8-15 días)</small>
                    </div>
                </div>
            </div>
        `;
    }
    
    document.getElementById('estadisticasRapidas').innerHTML = estadisticasHtml;
    document.getElementById('areaEstadisticas').style.display = 'block';
}

function exportarReporte() {
    if (!tipoReporteActual || !datosActuales.length) {
        alert('No hay datos para exportar');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('vehiculos/exportarReporte') ?>';
    
    const tipoInput = document.createElement('input');
    tipoInput.type = 'hidden';
    tipoInput.name = 'tipo_reporte';
    tipoInput.value = tipoReporteActual;
    
    const formatoInput = document.createElement('input');
    formatoInput.type = 'hidden';
    formatoInput.name = 'formato';
    formatoInput.value = 'excel';
    
    form.appendChild(tipoInput);
    form.appendChild(formatoInput);
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function limpiarResultados() {
    document.getElementById('areaResultados').style.display = 'none';
    document.getElementById('areaEstadisticas').style.display = 'none';
    document.getElementById('filtroEstado').value = 'TODOS';
    document.getElementById('filtroBusqueda').value = '';
    datosActuales = [];
}

function mostrarError(mensaje) {
    document.getElementById('tablaResultados').innerHTML = `
        <div class="alert alert-danger text-center">
            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
            <h5>Error</h5>
            <p class="mb-0">${mensaje}</p>
        </div>
    `;
}

// Funciones auxiliares
function getEstadoBadge(estado) {
    const badges = {
        'ACTIVO': '<span class="badge bg-success">Activo</span>',
        'INACTIVO': '<span class="badge bg-secondary">Inactivo</span>',
        'EN REPARACION': '<span class="badge bg-warning">En Reparación</span>',
        'FUERA DE SERVICIO': '<span class="badge bg-danger">Fuera de Servicio</span>'
    };
    return badges[estado] || `<span class="badge bg-secondary">${estado}</span>`;
}

function formatNumber(number) {
    return new Intl.NumberFormat('es-ES').format(number);
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES');
}

// Función de debug para probar rutas
function testRutas() {
    console.log('Probando ruta de test...');
    fetch('<?= base_url('vehiculos/test-reporte') ?>', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Test response:', data);
        alert('Ruta de prueba funcionando: ' + data.message);
    })
    .catch(error => {
        console.error('Error en test:', error);
        alert('Error en ruta de prueba: ' + error.message);
    });
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Configurar eventos de filtros
    document.getElementById('filtroEstado').addEventListener('change', function() {
        if (tipoReporteActual) {
            // Auto-generar si hay un reporte seleccionado
            // generarReporte();
        }
    });
    
    document.getElementById('filtroBusqueda').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && tipoReporteActual) {
            generarReporte();
        }
    });
    
    // Agregar botón de debug (temporal)
    console.log('Dashboard de reportes cargado. Usa testRutas() para probar.');
});
</script>

<?= $this->endSection() ?>
