<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-bar me-2"></i><?= $page_title ?>
            </h1>
            <p class="mb-0 text-muted">Análisis y estadísticas de consumo de combustible</p>
        </div>
        <a href="<?= base_url('registro-combustible') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver a Registros
        </a>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>Filtros de Análisis
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('registro-combustible/estadisticas') ?>">
                <div class="row">
                    <div class="col-md-4">
                        <label for="vehiculo" class="form-label">Vehículo</label>
                        <select name="vehiculo" id="vehiculo" class="form-select">
                            <option value="">Todos los vehículos</option>
                            <?php foreach ($vehiculos as $vehiculo): ?>
                                <option value="<?= $vehiculo['id'] ?>" <?= $filtros['vehiculo'] == $vehiculo['id'] ? 'selected' : '' ?>>
                                    <?= esc($vehiculo['placa']) ?> - <?= esc($vehiculo['marca'] . ' ' . $vehiculo['modelo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_desde" class="form-label">Fecha Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="<?= $filtros['fecha_desde'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="<?= $filtros['fecha_hasta'] ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Analizar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="row mb-4">
        <!-- Total Registros: Interno vs Externo -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-list text-primary"></i>
                        <h6 class="mb-0">Total Registros</h6>
                    </div>
                    <h3 class="text-primary mb-2"><?= number_format($estadisticas['total_registros']) ?></h3>
                    <div class="d-flex gap-2">
                        <span class="badge bg-info text-white">
                            <i class="fas fa-warehouse me-1"></i>Internos: <?= number_format($estadisticas['total_registros_interno']) ?>
                        </span>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-truck-moving me-1"></i>Externos: <?= number_format($estadisticas['total_registros_externo']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Litros Despachados: Interno vs Externo -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-gas-pump text-info"></i>
                        <h6 class="mb-0">Litros Despachados</h6>
                    </div>
                    <h3 class="text-info mb-2"><?= number_format($estadisticas['total_litros'], 1) ?> L</h3>
                    <div class="d-flex flex-column gap-1">
                        <small><span class="text-info fw-semibold">Interno:</span> <?= number_format($estadisticas['total_litros_interno'], 1) ?> L</small>
                        <small><span class="text-warning fw-semibold">Externo:</span> <?= number_format($estadisticas['total_litros_externo'], 1) ?> L</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promedio Diario en Litros -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <div class="text-success">
                        <i class="fas fa-calendar-day fa-2x mb-2"></i>
                    </div>
                    <h4 class="text-success"><?= number_format($estadisticas['promedio_diario_litros'], 2) ?> L</h4>
                    <p class="text-muted mb-0">Promedio Diario (Litros)</p>
                </div>
            </div>
        </div>

        <!-- Total Kilómetros (solo internos) -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-secondary h-100">
                <div class="card-body text-center">
                    <div class="text-secondary">
                        <i class="fas fa-tachometer-alt fa-2x mb-2"></i>
                    </div>
                    <h4 class="text-secondary"><?= number_format($estadisticas['total_kilometros']) ?> km</h4>
                    <p class="text-muted mb-0">Total Kilómetros (Internos)</p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($estadisticas['total_registros'] > 0):
        $rendimientoPromedio = $estadisticas['total_litros'] > 0 ?
            round($estadisticas['total_kilometros'] / $estadisticas['total_litros'], 2) : 0;
    ?>
    <div class="row">
        <!-- Gráfico de Rendimiento -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Análisis de Rendimiento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="rendimientoChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="consumoChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicadores de Eficiencia -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-speedometer me-2"></i>Indicadores de Eficiencia
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Rendimiento Promedio -->
                    <div class="mb-4">
                        <label class="form-label small text-muted">Rendimiento Promedio</label>
                        <div class="progress mb-2" style="height: 25px;">
                            <?php 
                                $porcentaje = min(($rendimientoPromedio / 15) * 100, 100);
                                $colorClass = $rendimientoPromedio >= 10 ? 'bg-success' : ($rendimientoPromedio >= 7 ? 'bg-warning' : 'bg-danger');
                            ?>
                            <div class="progress-bar <?= $colorClass ?>" style="width: <?= $porcentaje ?>%">
                                <?= $rendimientoPromedio ?> km/L
                            </div>
                        </div>
                        <div class="alert alert-<?= $rendimientoPromedio >= 10 ? 'success' : ($rendimientoPromedio >= 7 ? 'warning' : 'danger') ?> alert-sm">
                            <i class="fas fa-<?= $rendimientoPromedio >= 10 ? 'check-circle' : ($rendimientoPromedio >= 7 ? 'exclamation-triangle' : 'times-circle') ?> me-2"></i>
                            <strong>
                                <?php if ($rendimientoPromedio >= 10): ?>
                                    Excelente eficiencia
                                <?php elseif ($rendimientoPromedio >= 7): ?>
                                    Eficiencia aceptable
                                <?php else: ?>
                                    Eficiencia baja
                                <?php endif; ?>
                            </strong>
                        </div>
                    </div>

                    <!-- Consumo Promedio -->
                    <div class="mb-4">
                        <label class="form-label small text-muted">Consumo Promedio por Registro</label>
                        <h4 class="text-info"><?= number_format($estadisticas['promedio_litros'], 2) ?> L</h4>
                    </div>

                    <!-- Recomendaciones -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Recomendaciones:</h6>
                        <ul class="mb-0 small">
                            <?php if ($rendimientoPromedio < 7): ?>
                                <li>Revisar el estado del motor</li>
                                <li>Verificar presión de neumáticos</li>
                                <li>Evaluar hábitos de conducción</li>
                            <?php elseif ($rendimientoPromedio < 10): ?>
                                <li>Mantener velocidad constante</li>
                                <li>Realizar mantenimiento preventivo</li>
                            <?php else: ?>
                                <li>Mantener las buenas prácticas</li>
                                <li>Continuar con el mantenimiento regular</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Resumen por Período -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>Resumen Detallado
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Métrica</th>
                            <th>Valor</th>
                            <th>Promedio</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fas fa-list me-2"></i>Total de Registros</td>
                            <td><span class="badge bg-primary"><?= number_format($estadisticas['total_registros']) ?></span></td>
                            <td>-</td>
                            <td>Registros en el período seleccionado</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-gas-pump me-2"></i>Combustible Total</td>
                            <td><span class="badge bg-info"><?= number_format($estadisticas['total_litros'], 2) ?> L</span></td>
                            <td><?= number_format($estadisticas['promedio_litros'], 2) ?> L</td>
                            <td>Consumo total y promedio por registro</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-tachometer-alt me-2"></i>Kilómetros Recorridos</td>
                            <td><span class="badge bg-success"><?= number_format($estadisticas['total_kilometros']) ?> km</span></td>
                            <td><?= $estadisticas['total_registros'] > 0 ? number_format($estadisticas['total_kilometros'] / $estadisticas['total_registros'], 2) : 0 ?> km</td>
                            <td>Distancia total y promedio por registro</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-gauge me-2"></i>Rendimiento</td>
                            <td>
                                <span class="badge bg-<?= $rendimientoPromedio >= 10 ? 'success' : ($rendimientoPromedio >= 7 ? 'warning' : 'danger') ?>">
                                    <?= $rendimientoPromedio ?> km/L
                                </span>
                            </td>
                            <td><?= $rendimientoPromedio ?> km/L</td>
                            <td>
                                <?php if ($rendimientoPromedio >= 10): ?>
                                    Excelente eficiencia de combustible
                                <?php elseif ($rendimientoPromedio >= 7): ?>
                                    Eficiencia aceptable, se puede mejorar
                                <?php else: ?>
                                    Eficiencia baja, requiere atención
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-dollar-sign me-2"></i>Ventas Totales</td>
                            <td>
                                <span class="badge bg-success">C$ <?= number_format($estadisticas['total_monto_nio'] ?? 0, 2) ?></span>
                                <span class="badge bg-secondary">$ <?= number_format($estadisticas['total_monto_usd'] ?? 0, 2) ?></span>
                            </td>
                            <td><?= $estadisticas['total_registros_venta'] > 0 ? 'C$ ' . number_format(($estadisticas['total_monto_nio'] ?? 0) / $estadisticas['total_registros_venta'], 2) : '-' ?></td>
                            <td>Montos acumulados por ventas registradas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php if (!empty($estadisticas['ventas_por_mes'])): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-alt me-2"></i>Ventas Mensuales
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Periodo</th>
                                    <th>C$</th>
                                    <th>$</th>
                                    <th>Galones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estadisticas['ventas_por_mes'] as $ventaMes): ?>
                                    <tr>
                                        <td><?= esc($ventaMes['periodo']) ?></td>
                                        <td>C$ <?= number_format($ventaMes['monto_nio'], 2) ?></td>
                                        <td>$ <?= number_format($ventaMes['monto_usd'], 2) ?></td>
                                        <td><?= number_format($ventaMes['litros'], 2) ?> gal</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <canvas id="ventasChart" width="400" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No hay datos para mostrar</h4>
            <p class="text-muted">No se encontraron registros con los filtros seleccionados</p>
            <a href="<?= base_url('registro-combustible/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Agregar Primer Registro
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if ($estadisticas['total_registros'] > 0): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Rendimiento
    const rendimientoCtx = document.getElementById('rendimientoChart').getContext('2d');
    new Chart(rendimientoCtx, {
        type: 'doughnut',
        data: {
            labels: ['Excelente (≥10)', 'Aceptable (7-9.9)', 'Bajo (<7)'],
            datasets: [{
                data: [
                    <?= $rendimientoPromedio >= 10 ? 1 : 0 ?>,
                    <?= $rendimientoPromedio >= 7 && $rendimientoPromedio < 10 ? 1 : 0 ?>,
                    <?= $rendimientoPromedio < 7 ? 1 : 0 ?>
                ],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Clasificación de Rendimiento'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Consumo
    const consumoCtx = document.getElementById('consumoChart').getContext('2d');
    new Chart(consumoCtx, {
        type: 'bar',
        data: {
            labels: ['Litros Totales', 'Km Recorridos', 'Rendimiento'],
            datasets: [{
                label: 'Valores',
                data: [
                    <?= $estadisticas['total_litros'] ?>,
                    <?= $estadisticas['total_kilometros'] / 100 ?>, // Dividido para escala
                    <?= $rendimientoPromedio * 10 ?> // Multiplicado para escala
                ],
                backgroundColor: ['#17a2b8', '#28a745', '#ffc107'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Resumen de Consumo'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ventasPorMes = <?= json_encode($estadisticas['ventas_por_mes'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const ventasChartCanvas = document.getElementById('ventasChart');
    if (ventasChartCanvas && Array.isArray(ventasPorMes) && ventasPorMes.length > 0) {
        const labels = ventasPorMes.map(item => item.periodo);
        const dataNio = ventasPorMes.map(item => parseFloat(item.monto_nio));
        const dataUsd = ventasPorMes.map(item => parseFloat(item.monto_usd));

        new Chart(ventasChartCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'C$ (NIO)',
                        data: dataNio,
                        backgroundColor: '#6c757d'
                    },
                    {
                        label: '$ (USD)',
                        data: dataUsd,
                        backgroundColor: '#198754'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Ventas mensuales por moneda'
                    }
                },
                scales: {
                    x: {
                        stacked: false
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>
