<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">
                <i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard de Bomba
            </h1>
            <p class="text-muted mb-0">Estadísticas y métricas de lecturas de bomba</p>
        </div>
        <div>
            <a href="<?= base_url('lectura-bomba') ?>" class="btn btn-outline-primary">
                <i class="fas fa-list me-2"></i>Ver Lecturas
            </a>
            <a href="<?= base_url('lectura-bomba/apertura') ?>" class="btn btn-success ms-2">
                <i class="fas fa-plus me-2"></i>Nueva Apertura
            </a>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <!-- Total Aperturas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clipboard-list fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Aperturas</h6>
                            <h3 class="fw-bold mb-0"><?= number_format($estadisticas['total_aperturas']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aperturas Pendientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pendientes de Cierre</h6>
                            <h3 class="fw-bold mb-0"><?= number_format($estadisticas['aperturas_pendientes']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Cerradas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Cerradas</h6>
                            <h3 class="fw-bold mb-0"><?= number_format($estadisticas['total_cerradas']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Anomalías -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Anomalías</h6>
                            <h3 class="fw-bold mb-0"><?= number_format($estadisticas['total_anomalias']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consumo -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-gas-pump text-primary me-2"></i>Consumo Total
                    </h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted">Litros</small>
                                <h4 class="fw-bold mb-0"><?= number_format($estadisticas['consumo_total_litros'], 2) ?></h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted">Galones</small>
                                <h4 class="fw-bold mb-0"><?= number_format($estadisticas['consumo_total_galones'], 2) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-chart-line text-primary me-2"></i>Consumo Promedio por Turno
                    </h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted">Litros</small>
                                <h4 class="fw-bold mb-0"><?= number_format($estadisticas['consumo_promedio_litros'], 2) ?></h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted">Galones</small>
                                <h4 class="fw-bold mb-0"><?= number_format($estadisticas['consumo_promedio_galones'], 2) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lecturas por Bomba -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-chart-bar text-primary me-2"></i>Lecturas por Bomba
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($lecturas_por_bomba)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bomba</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Cerradas</th>
                                    <th class="text-center">Anomalías</th>
                                    <th class="text-center">Progreso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lecturas_por_bomba as $bomba): ?>
                                <tr>
                                    <td class="fw-bold"><?= esc($bomba['nombre']) ?></td>
                                    <td class="text-center"><?= number_format($bomba['total']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= number_format($bomba['cerradas']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($bomba['anomalias'] > 0): ?>
                                        <span class="badge bg-danger"><?= number_format($bomba['anomalias']) ?></span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                        $progreso = $bomba['total'] > 0 ? ($bomba['cerradas'] / $bomba['total']) * 100 : 0;
                                        ?>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: <?= $progreso ?>%" 
                                                 aria-valuenow="<?= $progreso ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted"><?= number_format($progreso, 1) ?>%</small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de lecturas por bomba</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Aperturas Pendientes -->
    <?php if (!empty($estadisticas['aperturas_pendientes_detalle'])): ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-exclamation-circle text-warning me-2"></i>Aperturas Pendientes de Cierre
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bomba</th>
                                    <th>Usuario</th>
                                    <th>Fecha Apertura</th>
                                    <th>Lectura Inicial</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estadisticas['aperturas_pendientes_detalle'] as $apertura): ?>
                                <tr>
                                    <td class="fw-bold"><?= esc($apertura['nombre_centro_costo'] ?? $apertura['id_centro_costo']) ?></td>
                                    <td><?= esc($apertura['usuario_apertura']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($apertura['fecha_apertura'])) ?></td>
                                    <td><?= number_format($apertura['lectura_inicial_litros'], 2) ?> L</td>
                                    <td>
                                        <?php if ($apertura['estado'] === 'normal'): ?>
                                        <span class="badge bg-success">Normal</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Anomalía</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('lectura-bomba/cierre/' . $apertura['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-lock me-1"></i>Cerrar
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
