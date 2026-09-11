<?= $this->extend('layouts/main') ?>
<?php helper('vehiculo'); ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">

    <!-- ── Header ──────────────────────────────────── -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0 small">Gestiona los vehículos de tu flota en tiempo real</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('vehiculos/reportes') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-chart-bar me-1 text-info"></i>Reportes
            </a>
            <a href="<?= base_url('vehiculos/exportar') ?>" class="btn btn-outline-success btn-sm">
                <i class="fas fa-file-export me-1"></i>Exportar
            </a>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn_importar">
                <i class="fas fa-file-import me-1"></i>Importar
            </button>
            <?= buttonIfAllowed('vehiculos/create', '<i class="fas fa-plus me-1"></i>Agregar Vehículo', 'btn btn-primary btn-sm px-3') ?>
        </div>
    </div>

    <!-- ── Métricas ─────────────────────────────────── -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;flex-shrink:0">
                        <i class="fas fa-truck text-primary"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0 lh-1" id="stat_total">—</div>
                        <small class="text-muted">Total flota</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;flex-shrink:0">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0 lh-1" id="stat_activos">—</div>
                        <small class="text-muted">Activos</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;flex-shrink:0">
                        <i class="fas fa-tools text-warning"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0 lh-1" id="stat_reparacion">—</div>
                        <small class="text-muted">En reparación</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;flex-shrink:0">
                        <i class="fas fa-user-slash text-secondary"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0 lh-1" id="stat_sin_conductor">—</div>
                        <small class="text-muted">Sin conductor</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Tabla ─────────────────────────────────────── -->
    <div class="card border-0 shadow-sm">

        <!-- Toolbar dentro de la card -->
        <div class="card-header bg-white border-bottom py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">ESTADO</label>
                    <select class="form-select form-select-sm" id="filtro_estado">
                        <option value="">Todos los estados</option>
                        <option value="ACTIVO">Activo</option>
                        <option value="INACTIVO">Inactivo</option>
                        <option value="EN REPARACION">En Reparación</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label small fw-semibold text-muted mb-1">BUSCAR</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="filtro_buscar"
                               placeholder="Placa, marca, modelo, código…">
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-sm btn-primary w-100" id="btn_filtrar">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="vehiculosTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-semibold text-muted small text-uppercase">Vehículo</th>
                            <th class="fw-semibold text-muted small text-uppercase">Kilometraje</th>
                            <th class="fw-semibold text-muted small text-uppercase">Centro Costo</th>
                            <th class="fw-semibold text-muted small text-uppercase">Conductor</th>
                            <th class="fw-semibold text-muted small text-uppercase">Estado</th>
                            <th class="fw-semibold text-muted small text-uppercase text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="vehiculosBody">
                        <!-- skeleton inicial -->
                        <?php for ($i = 0; $i < 6; $i++): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light rounded" style="width:40px;height:40px"></div>
                                    <div>
                                        <div class="bg-light rounded mb-1" style="width:80px;height:14px"></div>
                                        <div class="bg-light rounded" style="width:120px;height:12px"></div>
                                    </div>
                                </div>
                            </td>
                            <td><div class="bg-light rounded" style="width:70px;height:14px"></div></td>
                            <td><div class="bg-light rounded" style="width:90px;height:14px"></div></td>
                            <td><div class="bg-light rounded" style="width:110px;height:14px"></div></td>
                            <td><div class="bg-light rounded" style="width:60px;height:22px;border-radius:20px!important"></div></td>
                            <td class="text-end pe-4"><div class="bg-light rounded" style="width:60px;height:30px;display:inline-block"></div></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío / contador -->
            <div class="px-4 py-3 border-top bg-light d-flex justify-content-between align-items-center">
                <small class="text-muted" id="tabla_info">Cargando…</small>
                <small class="text-muted" id="tabla_last_update"></small>
            </div>
        </div>
    </div>

</div>

<!-- ── Modal: Cambiar Estado ─────────────────────── -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-exchange-alt text-primary me-2"></i>Cambiar Estado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body pt-2">
                    <input type="hidden" id="vehiculo_id" name="id">
                    <input type="hidden" id="nuevo_estado" name="estado">
                    <div class="alert alert-light border mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        <span id="mensaje_cambio_estado"></span>
                    </div>
                    <div>
                        <label class="form-label fw-medium">
                            Motivo <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <textarea class="form-control" id="motivo_cambio" name="motivo" rows="3"
                                  placeholder="Describe el motivo del cambio de estado…"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-check me-1"></i>Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Modal: Importar Vehículos ─────────────────── -->
<div class="modal fade" id="modalImportar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-file-import text-primary me-2"></i>Importar Vehículos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formImportar">
                <div class="modal-body pt-2">
                    <div class="alert alert-light border mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Sube un archivo CSV con los datos de los vehículos. Descarga la
                        <a href="<?= base_url('vehiculos/plantillaImportacion') ?>">plantilla de importación</a>
                        para conocer el formato requerido.
                        <ul class="small text-muted mb-0 mt-2">
                            <li>Si la <code>placa</code> ya existe, el vehículo se <strong>actualiza</strong>; si no, se <strong>registra</strong> como nuevo</li>
                            <li>Campos obligatorios solo al registrar: <code>placa</code>, <code>marca</code>, <code>modelo</code>, <code>anio</code>, <code>codigo_unidad</code></li>
                            <li>En actualizaciones, las columnas vacías conservan el valor actual</li>
                            <li>Las filas con error se omiten; el resto se importa normalmente</li>
                            <li>Tamaño máximo: 5 MB</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Archivo CSV <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="archivo_importar" name="archivo"
                               accept=".csv,.txt" required>
                    </div>
                    <div id="resultado_importar" class="d-none">
                        <div class="alert mb-2" id="resumen_importar"></div>
                        <div class="table-responsive d-none" id="contenedor_errores_importar" style="max-height:240px">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:80px">Fila</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody id="errores_importar"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-upload me-1"></i>Importar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('public/assets/js/modules/Vehiculos.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    VehiculosIndex.init('<?= base_url() ?>', <?= json_encode($centros_costo ?? []) ?>);
});
</script>
<?= $this->endSection() ?>
