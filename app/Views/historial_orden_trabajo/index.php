<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Historial de Órdenes de Trabajo<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Historial de Órdenes de Trabajo</h4>
                    <div class="d-flex">
                        <a href="<?= site_url('historial-orden-trabajo/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nuevo Registro
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="historial-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Solicitud</th>
                                    <th>Vehículo</th>
                                    <th>Estado</th>
                                    <th>Comentario</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial as $registro): 
                                    // Obtener información de la solicitud (asumiendo que se pasa con la relación cargada)
                                    $solicitud = $registro['solicitud'] ?? [];
                                ?>
                                    <tr>
                                        <td><?= $registro['id'] ?></td>
                                        <td>
                                            <?php if (!empty($solicitud['codigo_consecutivo'])): ?>
                                                <a href="<?= site_url('historial-orden-trabajo/create/' . $registro['id_solicitud']) ?>">
                                                    <?= $solicitud['codigo_consecutivo'] ?>
                                                </a>
                                            <?php else: ?>
                                                Solicitud #<?= $registro['id_solicitud'] ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $solicitud['placa'] ?? 'N/A' ?></td>
                                        <td>
                                            <span class="badge bg-<?= getEstadoBadgeClass($registro['estado']) ?>">
                                                <?= $registro['estado'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= character_limiter($registro['comentario'] ?? 'Sin comentario', 50) ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($registro['fecha_registro'])) ?></td>
                                        <td>Usuario #<?= $registro['usuario_registra'] ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('historial-orden-trabajo/show/' . $registro['id']) ?>" 
                                                   class="btn btn-sm btn-info" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= site_url('historial-orden-trabajo/create/' . $registro['id_solicitud']) ?>" 
                                                   class="btn btn-sm btn-primary" title="Ver historial completo">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                            </div>
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
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    if (document.getElementById('historial-table')) {
        $('#historial-table').DataTable({
            responsive: true,
            order: [[0, 'desc']], // Ordenar por ID descendente por defecto
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            columnDefs: [
                { orderable: false, targets: [7] } // Deshabilitar ordenación en columna de acciones
            ]
        });
    }
});
</script>
<?= $this->endSection() ?>
