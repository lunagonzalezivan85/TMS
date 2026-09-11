<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-cogs me-2"></i><?= $title ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('mantenimiento-tablas') ?>">Mantenimiento</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('mantenimiento-tablas/ver/' . $nombre_tabla) ?>"><?= esc($nombre_tabla) ?></a></li>
                    <li class="breadcrumb-item active">Estructura</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('mantenimiento-tablas/ver/' . $nombre_tabla) ?>" class="btn btn-primary">
                <i class="fas fa-table me-1"></i>Ver Datos
            </a>
            <a href="<?= base_url('mantenimiento-tablas') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <!-- Información general -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Columnas</div>
                            <div class="h5 mb-0 font-weight-bold"><?= count($columnas) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-columns fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Índices</div>
                            <div class="h5 mb-0 font-weight-bold"><?= count($indices) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estructura de columnas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-columns me-2"></i>Estructura de Columnas
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Nombre de Columna</th>
                            <th width="20%">Tipo de Dato</th>
                            <th width="15%">Longitud Máxima</th>
                            <th width="15%">Permite NULL</th>
                            <th width="20%">Valor por Defecto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($columnas as $columna): ?>
                            <tr>
                                <td><?= esc($columna['ORDINAL_POSITION']) ?></td>
                                <td>
                                    <strong><?= esc($columna['COLUMN_NAME']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= strtoupper(esc($columna['DATA_TYPE'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($columna['CHARACTER_MAXIMUM_LENGTH']): ?>
                                        <span class="badge badge-secondary">
                                            <?= number_format($columna['CHARACTER_MAXIMUM_LENGTH']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($columna['IS_NULLABLE'] === 'YES'): ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-check"></i> SÍ
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> NO
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($columna['COLUMN_DEFAULT']): ?>
                                        <code><?= esc($columna['COLUMN_DEFAULT']) ?></code>
                                    <?php else: ?>
                                        <span class="text-muted font-italic">NULL</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Índices -->
    <?php if (!empty($indices)): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-key me-2"></i>Índices de la Tabla
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="30%">Nombre del Índice</th>
                                <th width="20%">Tipo</th>
                                <th width="15%">Único</th>
                                <th width="35%">Columnas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $indicesAgrupados = [];
                            foreach ($indices as $indice) {
                                $indicesAgrupados[$indice['INDEX_NAME']][] = $indice;
                            }
                            ?>
                            <?php foreach ($indicesAgrupados as $nombreIndice => $columnasIndice): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($nombreIndice) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?= esc($columnasIndice[0]['INDEX_TYPE']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($columnasIndice[0]['IS_UNIQUE']): ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> SÍ
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-times"></i> NO
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $columnas_indice = array_column($columnasIndice, 'COLUMN_NAME');
                                        echo implode(', ', array_map('esc', $columnas_indice));
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>Índices
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-key fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron índices</h5>
                    <p class="text-muted">Esta tabla no tiene índices definidos</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Resumen de tipos de datos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-chart-pie me-2"></i>Resumen de Tipos de Datos
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <?php 
                $tiposDatos = [];
                foreach ($columnas as $columna) {
                    $tipo = $columna['DATA_TYPE'];
                    $tiposDatos[$tipo] = ($tiposDatos[$tipo] ?? 0) + 1;
                }
                arsort($tiposDatos);
                ?>
                <?php foreach ($tiposDatos as $tipo => $cantidad): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card border-left-primary">
                            <div class="card-body py-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            <?= strtoupper(esc($tipo)) ?>
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            <?= $cantidad ?> columna<?= $cantidad > 1 ? 's' : '' ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="h4 mb-0 text-primary">
                                            <?= round(($cantidad / count($columnas)) * 100) ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
