<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-table me-2"></i><?= $title ?>
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('mantenimiento-tablas') ?>">Mantenimiento</a></li>
                <li class="breadcrumb-item active"><?= esc($nombre_tabla) ?></li>
            </ol>
        </nav>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" id="tabsVista" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-estadisticas" data-bs-toggle="tab"
                    data-bs-target="#pane-estadisticas" type="button" role="tab">
                <i class="fas fa-chart-bar me-1"></i> Estadísticas
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-datos" data-bs-toggle="tab"
                    data-bs-target="#pane-datos" type="button" role="tab">
                <i class="fas fa-list me-1"></i> Datos
            </button>
        </li>
    </ul>

    <div class="tab-content" id="tabsVistaContent">

    <!-- ── Tab Estadísticas ── -->
    <div class="tab-pane fade show active" id="pane-estadisticas" role="tabpanel">

    <!-- Cards de resumen -->
    <div class="row g-3 mb-4" id="cards-resumen">

        <!-- Total registros (siempre) -->
        <div class="col-12 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-database text-primary fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold" style="font-size:.7rem">Total Registros</div>
                        <div class="fw-bold fs-5"><?= number_format($paginacion['total_registros']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!is_null($porcentaje_vencimiento)): ?>
        <!-- % Vencimiento -->
        <?php
            $pct     = $porcentaje_vencimiento;
            $pctFmt  = number_format($pct, 2, '.', ',') . '%';
            $barColor = $pct >= 75 ? 'danger' : ($pct >= 40 ? 'warning' : 'success');
        ?>
        <div class="col-12 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="fas fa-exclamation-triangle text-danger fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="text-muted small text-uppercase fw-semibold" style="font-size:.7rem">% Vencimiento</div>
                        <div class="fw-bold fs-5 text-<?= $barColor ?>"><?= $pctFmt ?></div>
                        <div class="progress mt-1" style="height:4px">
                            <div class="progress-bar bg-<?= $barColor ?>" style="width:<?= min($pct,100) ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Columnas (cuando no hay % vencimiento) -->
        <div class="col-12 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-columns text-success fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold" style="font-size:.7rem">Columnas</div>
                        <div class="fw-bold fs-5"><?= count($columnas) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Sumas de Monto / Saldo (agrupadas por nombre base) -->
        <?php
        // Agrupar campos por nombre base: quitar sufijos de moneda al final (NIO, USD, C$, $, etc.)
        $grupos = [];
        foreach ($sumas_montos as $campo => $suma) {
            // Extraer nombre base eliminando sufijo de moneda al final
            $base = trim(preg_replace('/[\s_\-]*(NIO|USD|C\$|\$|EUR|GTQ|HNL|CRC|DOP|MXN|PEN|COP|BRL)$/i', '', $campo));
            if ($base === '') $base = $campo;
            $grupos[$base][] = ['campo' => $campo, 'suma' => $suma];
        }
        $coloresMonto = ['warning','info','secondary','dark'];
        $ci = 0;
        foreach ($grupos as $base => $items):
            $color   = $coloresMonto[$ci % count($coloresMonto)];
            $ci++;
            $primary = $items[0];
            $rest    = array_slice($items, 1);
        ?>
        <div class="col-12 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-<?= $color ?> bg-opacity-10 p-3 flex-shrink-0">
                        <i class="fas fa-dollar-sign text-<?= $color ?> fa-lg"></i>
                    </div>
                    <div class="overflow-hidden flex-grow-1">
                        <div class="text-muted small text-uppercase fw-semibold text-truncate" style="font-size:.7rem" title="<?= esc($primary['campo']) ?>"><?= esc($primary['campo']) ?></div>
                        <div class="fw-bold fs-6 font-monospace"><?= number_format($primary['suma'], 2, '.', ',') ?></div>
                        <?php foreach ($rest as $sec): ?>
                        <div class="text-muted mt-1" style="font-size:.75rem">
                            <span class="fw-semibold text-truncate d-inline-block" style="max-width:100%" title="<?= esc($sec['campo']) ?>"><?= esc($sec['campo']) ?></span>
                            <span class="font-monospace ms-1"><?= number_format($sec['suma'], 2, '.', ',') ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Conteo por Estado -->
        <?php
        $coloresEstado = ['primary','success','danger','warning','info','secondary'];
        $ei = 0;
        foreach ($conteo_estados as $estado => $conteo):
            $color = $coloresEstado[$ei % count($coloresEstado)];
            $ei++;
        ?>
        <div class="col-12 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-<?= $color ?> bg-opacity-10 p-3">
                        <i class="fas fa-tag text-<?= $color ?> fa-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-muted small text-uppercase fw-semibold text-truncate" style="font-size:.7rem">
                            Estado: <?= esc($estado) ?>
                        </div>
                        <div class="fw-bold fs-5"><?= number_format($conteo) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Montos por Estado -->
        <?php
        $coloresMontoEstado = ['danger','success','warning','info','primary','secondary'];
        $mi = 0;
        foreach ($montos_por_estado as $estado => $monto):
            $color = $coloresMontoEstado[$mi % count($coloresMontoEstado)];
            $mi++;
        ?>
        <div class="col-12 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-<?= $color ?> bg-opacity-10 p-3">
                        <i class="fas fa-dollar-sign text-<?= $color ?> fa-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-muted small text-uppercase fw-semibold text-truncate" style="font-size:.7rem" title="<?= esc($estado) ?>">
                            <?= esc($estado) ?>
                        </div>
                        <div class="fw-bold fs-6 font-monospace"><?= number_format($monto, 2, '.', ',') ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div><!-- /.row cards-resumen -->
    </div><!-- /.tab-pane #pane-estadisticas -->

    <!-- ── Tab Datos ── -->
    <div class="tab-pane fade" id="pane-datos" role="tabpanel">

    <!-- Lista de registros -->
    <div class="card border-0 shadow-sm mb-4" id="card-lista">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2">
            <span class="fw-semibold">
                <i class="fas fa-list me-2 text-primary"></i><?= esc($nombre_tabla) ?>
            </span>
            <small class="text-muted">
                <?= number_format($paginacion['total_registros']) ?> registros
                &nbsp;·&nbsp; página <?= $paginacion['page'] ?> de <?= $paginacion['total_paginas'] ?>
            </small>
        </div>
        <?php if (!empty($datos)): ?>
            <?php
            $colsCabecera = [];
            foreach ($columnas as $col) {
                if (!in_array($col['DATA_TYPE'], ['image','varbinary','binary','timestamp','rowversion'])) {
                    $colsCabecera[] = $col['COLUMN_NAME'];
                }
                if (count($colsCabecera) === 3) break;
            }
            $offset = ($paginacion['page'] - 1) * $paginacion['per_page'];
            ?>
            <div class="accordion accordion-flush" id="acordeon-registros">
                <?php foreach ($datos as $idx => $fila): ?>
                <?php
                $num    = $offset + $idx + 1;
                $itemId = 'reg-' . $num;
                $partesCab = [];
                foreach ($colsCabecera as $cn) {
                    $v = $fila[$cn] ?? null;
                    if (!is_null($v) && $v !== '') {
                        $partesCab[] = '<span class="text-muted small me-1">' . esc($cn) . ':</span>'
                                     . '<strong>' . esc(strlen((string)$v) > 40 ? substr($v,0,40).'…' : $v) . '</strong>';
                    }
                }
                $cabecera = implode('<span class="mx-2 text-muted">|</span>', $partesCab) ?: '<em class="text-muted">Registro '.$num.'</em>';
                $tiposNum = ['int','bigint','smallint','tinyint','decimal','numeric','float','real','money','smallmoney'];
                ?>
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="head-<?= $itemId ?>">
                        <button class="accordion-button collapsed py-2 px-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#body-<?= $itemId ?>"
                                aria-expanded="false">
                            <span class="badge bg-light text-secondary border me-3" style="min-width:2rem"><?= $num ?></span>
                            <span class="flex-grow-1"><?= $cabecera ?></span>
                        </button>
                    </h2>
                    <div id="body-<?= $itemId ?>" class="accordion-collapse collapse" data-bs-parent="">
                        <div class="accordion-body py-3 px-4 bg-light">
                            <div class="row g-2">
                                <?php foreach ($columnas as $col): ?>
                                <?php
                                $cn  = $col['COLUMN_NAME'];
                                $val = $fila[$cn] ?? null;
                                if (is_null($val))        $display = '<span class="text-muted fst-italic">NULL</span>';
                                elseif ($val === '')      $display = '<span class="text-muted fst-italic">Vacío</span>';
                                elseif (in_array($col['DATA_TYPE'], $tiposNum) && is_numeric($val))
                                                          $display = '<span class="font-monospace">'.number_format((float)$val,2,'.',',').'</span>';
                                else                      $display = '<span>'.esc($val).'</span>';
                                ?>
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted fw-semibold text-uppercase" style="font-size:.7rem">
                                            <?= esc($cn) ?> <span class="fw-normal text-lowercase">(<?= esc($col['DATA_TYPE']) ?>)</span>
                                        </span>
                                        <span class="text-break"><?= $display ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if ($paginacion['total_paginas'] > 1): ?>
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-2">
                <small class="text-muted">
                    <?= $offset+1 ?>–<?= min($offset+$paginacion['per_page'],$paginacion['total_registros']) ?>
                    de <?= number_format($paginacion['total_registros']) ?>
                </small>
                <nav><ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= !$paginacion['tiene_anterior']?'disabled':'' ?>">
                        <a class="page-link" href="<?= current_url()?>?<?= http_build_query(array_merge($_GET,['page'=>$paginacion['page']-1])) ?>"><i class="fas fa-chevron-left"></i></a>
                    </li>
                    <?php $ini=max(1,$paginacion['page']-2); $fin=min($paginacion['total_paginas'],$paginacion['page']+2); ?>
                    <?php if($ini>1): ?><li class="page-item"><a class="page-link" href="<?= current_url()?>?<?= http_build_query(array_merge($_GET,['page'=>1])) ?>">1</a></li><?php if($ini>2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?><?php endif; ?>
                    <?php for($p=$ini;$p<=$fin;$p++): ?>
                    <li class="page-item <?= $p==$paginacion['page']?'active':'' ?>"><a class="page-link" href="<?= current_url()?>?<?= http_build_query(array_merge($_GET,['page'=>$p])) ?>"><?= $p ?></a></li>
                    <?php endfor; ?>
                    <?php if($fin<$paginacion['total_paginas']): ?><?php if($fin<$paginacion['total_paginas']-1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?><li class="page-item"><a class="page-link" href="<?= current_url()?>?<?= http_build_query(array_merge($_GET,['page'=>$paginacion['total_paginas']])) ?>"><?= $paginacion['total_paginas'] ?></a></li><?php endif; ?>
                    <li class="page-item <?= !$paginacion['tiene_siguiente']?'disabled':'' ?>">
                        <a class="page-link" href="<?= current_url()?>?<?= http_build_query(array_merge($_GET,['page'=>$paginacion['page']+1])) ?>"><i class="fas fa-chevron-right"></i></a>
                    </li>
                </ul></nav>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">No se encontraron registros</h6>
                <p class="text-muted small">La tabla está vacía o no coincide con los filtros aplicados.</p>
            </div>
        <?php endif; ?>
    </div><!-- /.card-lista -->

    </div><!-- /.tab-pane #pane-datos -->
    </div><!-- /.tab-content -->

    <!-- Modal de Filtros (fuera del tab-content) -->
    <div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fw-bold" id="modalFiltrosLabel">
                        <i class="fas fa-filter me-2 text-primary"></i>Filtros por columna
                        <small class="text-muted fw-normal fs-6 ms-2"><?= esc($nombre_tabla) ?></small>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <form method="GET" action="<?= current_url() ?>" id="form-filtros">

                <!-- Filas de filtro por columna -->
                <div id="filtros-container">
                    <?php
                    $colFiltros = $filtros['col'] ?? [];
                    $opFiltros  = $filtros['op']  ?? [];
                    $valFiltros = $filtros['val'] ?? [];
                    if (empty($colFiltros)) { $colFiltros = ['']; $opFiltros = ['like']; $valFiltros = ['']; }
                    foreach ($colFiltros as $fi => $fc):
                    ?>
                    <div class="row g-2 align-items-end mb-3 fila-filtro">
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold mb-1">Columna</label>
                            <select class="form-select" name="col[]">
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($columnas as $col): ?>
                                    <option value="<?= esc($col['COLUMN_NAME']) ?>"
                                        <?= ($fc === $col['COLUMN_NAME']) ? 'selected' : '' ?>>
                                        <?= esc($col['COLUMN_NAME']) ?> (<?= esc($col['DATA_TYPE']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-semibold mb-1">Operador</label>
                            <select class="form-select" name="op[]">
                                <option value="like"    <?= (($opFiltros[$fi] ?? '') === 'like')    ? 'selected' : '' ?>>Contiene</option>
                                <option value="eq"      <?= (($opFiltros[$fi] ?? '') === 'eq')      ? 'selected' : '' ?>>=</option>
                                <option value="neq"     <?= (($opFiltros[$fi] ?? '') === 'neq')     ? 'selected' : '' ?>>&lt;&gt; (distinto)</option>
                                <option value="gt"      <?= (($opFiltros[$fi] ?? '') === 'gt')      ? 'selected' : '' ?>>&gt;</option>
                                <option value="gte"     <?= (($opFiltros[$fi] ?? '') === 'gte')     ? 'selected' : '' ?>>&gt;=</option>
                                <option value="lt"      <?= (($opFiltros[$fi] ?? '') === 'lt')      ? 'selected' : '' ?>>&lt;</option>
                                <option value="lte"     <?= (($opFiltros[$fi] ?? '') === 'lte')     ? 'selected' : '' ?>>&lt;=</option>
                                <option value="null"    <?= (($opFiltros[$fi] ?? '') === 'null')    ? 'selected' : '' ?>>Es NULL</option>
                                <option value="notnull" <?= (($opFiltros[$fi] ?? '') === 'notnull') ? 'selected' : '' ?>>No es NULL</option>
                            </select>
                        </div>
                        <div class="col-5 col-md-4">
                            <label class="form-label small fw-semibold mb-1">Valor</label>
                            <input type="text" class="form-control" name="val[]"
                                   value="<?= esc($valFiltros[$fi] ?? '') ?>"
                                   placeholder="Valor...">
                        </div>
                        <div class="col-1 d-flex align-items-end">
                            <?php if ($fi > 0): ?>
                                <button type="button" class="btn btn-outline-danger btn-remove-filtro w-100">
                                    <i class="fas fa-times"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-muted small">AND</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="btn-add-filtro">
                    <i class="fas fa-plus me-1"></i> Agregar condición
                </button>

                <!-- Controles de orden y paginación -->
                <div class="row g-2 align-items-end border-top pt-3">
                    <div class="col-6 col-md-4">
                        <label class="form-label small fw-semibold mb-1">Ordenar por</label>
                        <select class="form-select form-select-sm" name="order_by">
                            <option value="">-- Sin orden --</option>
                            <?php foreach ($columnas as $col): ?>
                                <option value="<?= esc($col['COLUMN_NAME']) ?>"
                                    <?= ($filtros['order_by'] ?? '') === $col['COLUMN_NAME'] ? 'selected' : '' ?>>
                                    <?= esc($col['COLUMN_NAME']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Dirección</label>
                        <select class="form-select form-select-sm" name="order_dir">
                            <option value="ASC"  <?= ($filtros['order_dir'] ?? 'ASC') === 'ASC'  ? 'selected' : '' ?>>Ascendente</option>
                            <option value="DESC" <?= ($filtros['order_dir'] ?? 'ASC') === 'DESC' ? 'selected' : '' ?>>Descendente</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold mb-1">Por página</label>
                        <select class="form-select form-select-sm" name="per_page">
                            <?php foreach ([10, 25, 50, 100] as $pp): ?>
                                <option value="<?= $pp ?>" <?= ($paginacion['per_page'] ?? 25) == $pp ? 'selected' : '' ?>><?= $pp ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                </div><!-- /.modal-body -->
                <div class="modal-footer border-top">
                    <a href="<?= current_url() ?>" class="btn btn-outline-danger btn-sm me-auto">
                        <i class="fas fa-trash me-1"></i> Limpiar filtros
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="form-filtros" class="btn btn-primary px-4" id="btn-procesar">
                        <i class="fas fa-play me-1"></i> Procesar
                    </button>
                </div>
                    </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /#modalFiltros -->

    <!-- Dropup flotante -->
    <div class="dropup" style="position:fixed;bottom:28px;right:28px;z-index:1055;">
        <button class="btn btn-dark rounded-circle shadow-lg dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false"
                style="width:56px;height:56px;font-size:1.2rem;">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow mb-2">
            <li>
                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalFiltros">
                    <i class="fas fa-filter me-2 text-primary"></i> Filtrar
                </button>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="<?= base_url('mantenimiento-tablas') ?>">
                    <i class="fas fa-arrow-left me-2 text-secondary"></i> Volver
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="<?= base_url('mantenimiento-tablas/estructura/' . $nombre_tabla) ?>">
                    <i class="fas fa-cogs me-2 text-info"></i> Estructura
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="<?= base_url('mantenimiento-tablas/exportar/' . $nombre_tabla) ?>">
                    <i class="fas fa-download me-2 text-success"></i> Exportar CSV
                </a>
            </li>
        </ul>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {


    // ── Agregar fila de filtro ────────────────────────────────────────────
    $('#btn-add-filtro').on('click', function () {
        $('#filtros-container').append(nuevaFilaFiltro());
    });

    function nuevaFilaFiltro() {
        return `
        <div class="row g-2 align-items-end mb-3 fila-filtro">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold mb-1">Columna</label>
                <select class="form-select" name="col[]">
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($columnas as $col): ?>
                    <option value="<?= esc($col['COLUMN_NAME']) ?>"><?= esc($col['COLUMN_NAME']) ?> (<?= esc($col['DATA_TYPE']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small fw-semibold mb-1">Operador</label>
                <select class="form-select" name="op[]">
                    <option value="like">Contiene</option>
                    <option value="eq">=</option>
                    <option value="neq">&lt;&gt; (distinto)</option>
                    <option value="gt">&gt;</option>
                    <option value="gte">&gt;=</option>
                    <option value="lt">&lt;</option>
                    <option value="lte">&lt;=</option>
                    <option value="null">Es NULL</option>
                    <option value="notnull">No es NULL</option>
                </select>
            </div>
            <div class="col-5 col-md-4">
                <label class="form-label small fw-semibold mb-1">Valor</label>
                <input type="text" class="form-control" name="val[]" placeholder="Valor...">
            </div>
            <div class="col-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-remove-filtro w-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>`;
    }

    // ── Eliminar fila ─────────────────────────────────────────────────────
    $(document).on('click', '.btn-remove-filtro', function () {
        $(this).closest('.fila-filtro').remove();
    });

    // ── Deshabilitar valor en NULL / NOT NULL ─────────────────────────────
    $(document).on('change', 'select[name="op[]"]', function () {
        const op    = $(this).val();
        const input = $(this).closest('.fila-filtro').find('input[name="val[]"]');
        if (op === 'null' || op === 'notnull') {
            input.val('').prop('disabled', true).addClass('bg-light');
        } else {
            input.prop('disabled', false).removeClass('bg-light');
        }
    });

    // ── Procesar: habilita inputs y envía el form ─────────────────────────
    $('#btn-procesar').on('click', function () {
        $('#form-filtros input[name="val[]"]').prop('disabled', false);
        $('#form-filtros').submit();
    });

});
</script>
<?= $this->endSection() ?>
