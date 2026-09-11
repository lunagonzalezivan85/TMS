<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style>
.cot-kpi { border-radius:10px; padding:1rem 1.25rem; }
.cot-kpi .label { font-size:.72rem; color:#64748b; text-transform:uppercase; letter-spacing:.04em; }
.cot-kpi .val { font-size:1.5rem; font-weight:700; color:#1e293b; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-history fa-2x text-primary me-3"></i>
            <div>
                <h2 class="fw-bold mb-0">Historial de Cotizaciones</h2>
                <small class="text-muted">Todas las cotizaciones registradas</small>
            </div>
        </div>
        <a href="<?= base_url('cotizador/nueva') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva cotización
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-1"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- KPIs por estado -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="cot-kpi" style="background:#f1f5f9;">
                <div class="label">Pendientes</div>
                <div class="val"><?= $resumen['total_borradores'] ?? 0 ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="cot-kpi" style="background:#f0fdf4;">
                <div class="label">Aprobadas</div>
                <div class="val"><?= $resumen['total_aprobadas'] ?? 0 ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="cot-kpi" style="background:#fef2f2;">
                <div class="label">Rechazadas</div>
                <div class="val"><?= $resumen['total_rechazadas'] ?? 0 ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="cot-kpi" style="background:#eff6ff;">
                <div class="label">Monto aprobado</div>
                <div class="val">C$ <?= number_format($resumen['monto_total'] ?? 0, 0) ?></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if(empty($cotizaciones)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-3x mb-2 opacity-50"></i>
                <p class="mb-0">No hay cotizaciones registradas aún.</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th class="text-end">Distancia</th>
                            <th class="text-end">Volumen</th>
                            <th class="text-end">Costo viaje</th>
                            <th class="text-end">Precio final</th>
                            <th class="text-end">Margen</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cotizaciones as $cot): ?>
                        <tr>
                            <td><span class="fw-semibold text-primary"><?= esc($cot['numero_cotizacion']) ?></span></td>
                            <td><?= esc($cot['cliente_nombre']) ?></td>
                            <td><?= esc($cot['producto_nombre'] ?? '-') ?></td>
                            <td class="text-end"><?= number_format($cot['distancia_km'], 0) ?> km</td>
                            <td class="text-end"><?= number_format($cot['volumen_galones'], 0) ?> gal</td>
                            <td class="text-end">C$ <?= number_format($cot['costo_viaje'], 0) ?></td>
                            <td class="text-end fw-bold">C$ <?= number_format($cot['precio_final'], 0) ?></td>
                            <td class="text-end">
                                <span class="badge <?= ($cot['margen_porcentaje'] ?? 0) >= 15 ? 'bg-success' : 'bg-warning' ?>">
                                    <?= number_format($cot['margen_porcentaje'], 1) ?>%
                                </span>
                            </td>
                            <td>
                                <?php
                                $estColors = ['BORRADOR'=>'secondary','ENVIADA'=>'info','APROBADA'=>'success','RECHAZADA'=>'danger'];
                                $est = $cot['estado'] ?? 'BORRADOR';
                                ?>
                                <span class="badge bg-<?= $estColors[$est] ?? 'secondary' ?>"><?= $est ?></span>
                            </td>
                            <td><small><?= $cot['fecha_creacion'] ? date('d/m/Y', strtotime($cot['fecha_creacion'])) : '-' ?></small></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?= base_url('cotizador/show/'.$cot['id']) ?>"><i class="fas fa-eye me-2 text-primary"></i>Ver detalle</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('cotizador/imprimir/'.$cot['id']) ?>" target="_blank"><i class="fas fa-print me-2 text-success"></i>Imprimir</a></li>
                                        <?php if (($cot['estado'] ?? '') === 'BORRADOR'): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?= base_url('cotizador/edit/'.$cot['id']) ?>"><i class="fas fa-edit me-2 text-secondary"></i>Editar</a></li>
                                        <li>
                                            <form action="<?= base_url('cotizador/aprobar/'.$cot['id']) ?>" method="POST">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item text-success"><i class="fas fa-check me-2"></i>Aprobar</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="<?= base_url('cotizador/rechazar/'.$cot['id']) ?>" method="POST">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Rechazar esta cotización?')"><i class="fas fa-ban me-2"></i>Rechazar</button>
                                            </form>
                                        </li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button class="dropdown-item text-danger btnEliminar" data-id="<?= $cot['id'] ?>"><i class="fas fa-trash me-2"></i>Eliminar</button></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.btnEliminar').forEach(btn => {
    btn.addEventListener('click', async function() {
        if (!confirm('¿Eliminar esta cotización?')) return;
        const id = this.dataset.id;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const csrfHeader = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
        try {
            const resp = await fetch('<?= base_url("cotizador/delete/") ?>' + id, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest', [csrfHeader]: csrfToken },
            });
            const result = await resp.json();
            if (result.success) {
                this.closest('tr').remove();
            } else {
                alert('Error: ' + (result.message || 'No se pudo eliminar'));
            }
        } catch(err) {
            alert('Error: ' + err.message);
        }
    });
});
</script>
<?= $this->endSection() ?>
