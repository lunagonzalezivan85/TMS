<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('movimientos') ?>">Movimientos</a></li>
                    <li class="breadcrumb-item active"><?= esc($movimiento['codigo']) ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('movimientos') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <?php if (in_array($movimiento['estado'], [0, 1])): // Borrador o Pendiente ?>
                <a href="<?= base_url('movimientos/edit/' . $movimiento['id']) ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <!-- Información del Movimiento -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Información del Movimiento
                    </h6>
                    <?php
                    $estadoBadges = [
                        0 => '<span class="badge badge-secondary">Borrador</span>',
                        1 => '<span class="badge badge-warning">Pendiente</span>',
                        2 => '<span class="badge badge-info">Aprobado</span>',
                        3 => '<span class="badge badge-success">Procesado</span>',
                        4 => '<span class="badge badge-danger">Cancelado</span>'
                    ];
                    echo $estadoBadges[$movimiento['estado']] ?? '<span class="badge badge-secondary">Desconocido</span>';
                    ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Código:</strong></td>
                                    <td><?= esc($movimiento['codigo']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo:</strong></td>
                                    <td>
                                        <?php
                                        $tipoIcons = [
                                            'ENTRADA' => '<i class="fas fa-arrow-down text-success"></i>',
                                            'SALIDA' => '<i class="fas fa-arrow-up text-warning"></i>',
                                            'TRANSFERENCIA' => '<i class="fas fa-exchange-alt text-info"></i>',
                                            'AJUSTE_POSITIVO' => '<i class="fas fa-plus-circle text-success"></i>',
                                            'AJUSTE_NEGATIVO' => '<i class="fas fa-minus-circle text-danger"></i>'
                                        ];
                                        echo ($tipoIcons[$movimiento['tipo_movimiento']] ?? '') . ' ' . esc($tipos_movimiento[$movimiento['tipo_movimiento']] ?? $movimiento['tipo_movimiento']);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha Movimiento:</strong></td>
                                    <td><?= date('d/m/Y', strtotime($movimiento['fecha_movimiento'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Referencia:</strong></td>
                                    <td><?= esc($movimiento['referencia'] ?? 'Sin referencia') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <?php if (!empty($movimiento['codigo_origen']) || !empty($movimiento['codigo_destino'])): ?>
                                    <tr>
                                        <td><strong>Código Origen:</strong></td>
                                        <td><?= esc($movimiento['codigo_origen'] ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Código Destino:</strong></td>
                                        <td><?= esc($movimiento['codigo_destino'] ?? 'N/A') ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><strong>Monto Total:</strong></td>
                                    <td class="h5 text-primary">$<?= number_format($movimiento['monto'] ?? 0, 2) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Items:</strong></td>
                                    <td><?= count($movimiento['detalles']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del Movimiento -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Detalles del Movimiento
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($movimiento['detalles'])): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Material</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-right">Precio Unit.</th>
                                        <th class="text-center">Impuesto %</th>
                                        <th class="text-right">Subtotal</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalGeneral = 0;
                                    foreach ($movimiento['detalles'] as $detalle): 
                                        $totalGeneral += $detalle['total_linea'];
                                    ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($detalle['material_nombre']) ?></strong><br>
                                                <small class="text-muted">
                                                    Código: <?= esc($detalle['codigo_consecutivo']) ?> | 
                                                    Unidad: <?= esc($detalle['unidad_medida']) ?>
                                                </small>
                                            </td>
                                            <td class="text-center"><?= number_format($detalle['cantidad'], 2) ?></td>
                                            <td class="text-right">$<?= number_format($detalle['precio'], 2) ?></td>
                                            <td class="text-center"><?= number_format($detalle['impuesto'], 2) ?>%</td>
                                            <td class="text-right">$<?= number_format($detalle['subtotal'], 2) ?></td>
                                            <td class="text-right"><strong>$<?= number_format($detalle['total_linea'], 2) ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="5" class="text-right">Total General:</th>
                                        <th class="text-right h5 text-primary">$<?= number_format($totalGeneral, 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay detalles registrados para este movimiento</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Acciones -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs"></i> Acciones
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($movimiento['estado'] == 0): // Borrador ?>
                        <button class="btn btn-success btn-block mb-2" onclick="cambiarEstado(1)">
                            <i class="fas fa-paper-plane"></i> Enviar a Pendiente
                        </button>
                    <?php elseif ($movimiento['estado'] == 1): // Aprobado ?>
                        
                    <?php elseif ($movimiento['estado'] == 3): // Rechazado ?>
                        <button class="btn btn-success btn-block mb-2" onclick="cambiarEstado(3)">
                            <i class="fas fa-check-double"></i> Marcar como Procesado
                        </button>
                    <?php endif; ?>
                    
                    <?php if (in_array($movimiento['estado'], [0])): ?>
                        <a href="<?= base_url('movimientos/edit/' . $movimiento['id']) ?>" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Editar Movimiento
                        </a>
                        <button class="btn btn-danger btn-block mb-2" onclick="eliminarMovimiento()">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    <?php endif; ?>
                    
                    <button class="btn btn-secondary btn-block" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>

            <!-- Información de Auditoría -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-history"></i> Información de Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Creado por:</strong></td>
                            <td><?= esc($movimiento['usuario_crea']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha creación:</strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($movimiento['fecha_registro'])) ?></td>
                        </tr>
                        <?php if (!empty($movimiento['usuario_edita'])): ?>
                            <tr>
                                <td><strong>Editado por:</strong></td>
                                <td><?= esc($movimiento['usuario_edita']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha edición:</strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($movimiento['fecha_actualizacion'])) ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($movimiento['usuario_aprueba'])): ?>
                            <tr>
                                <td><strong>Aprobado por:</strong></td>
                                <td><?= esc($movimiento['usuario_aprueba']) ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Resumen -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-chart-pie"></i> Resumen
                    </h6>
                </div>
                <div class="card-body">
                    <?php
                    $totalItems = count($movimiento['detalles']);
                    $totalCantidad = 0;
                    $totalImpuestos = 0;
                    
                    foreach ($movimiento['detalles'] as $detalle) {
                        $totalCantidad += $detalle['cantidad'];
                        $totalImpuestos += ($detalle['subtotal'] * ($detalle['impuesto'] / 100));
                    }
                    ?>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <div class="h4 text-primary"><?= $totalItems ?></div>
                                <small class="text-muted">Materiales</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success"><?= number_format($totalCantidad, 2) ?></div>
                            <small class="text-muted">Cantidad Total</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <div class="h5 text-info">$<?= number_format($totalGeneral - $totalImpuestos, 2) ?></div>
                                <small class="text-muted">Subtotal</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h5 text-warning">$<?= number_format($totalImpuestos, 2) ?></div>
                            <small class="text-muted">Impuestos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmarModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Confirmar Acción
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="mensaje-confirmacion"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmar-accion">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let accionPendiente = null;

function cambiarEstado(nuevoEstado) {
    const estados = {
        1: 'Pendiente',
        2: 'Aprobado',
        3: 'Procesado',
        4: 'Cancelado'
    };
    
    const mensaje = `¿Está seguro de que desea cambiar el estado del movimiento a "${estados[nuevoEstado]}"?`;
    mostrarConfirmacion(mensaje, function() {
        ejecutarCambioEstado(nuevoEstado);
    });
}

function eliminarMovimiento() {
    const mensaje = '¿Está seguro de que desea eliminar este movimiento? Esta acción no se puede deshacer.';
    mostrarConfirmacion(mensaje, function() {
        ejecutarEliminacion();
    });
}

function mostrarConfirmacion(mensaje, callback) {
    $('#mensaje-confirmacion').text(mensaje);
    accionPendiente = callback;
    $('#confirmarModal').modal('show');
}

$('#confirmar-accion').click(function() {
    $('#confirmarModal').modal('hide');
    if (accionPendiente) {
        accionPendiente();
        accionPendiente = null;
    }
});

function ejecutarCambioEstado(nuevoEstado) {
    $.ajax({
        url: '<?= base_url('movimientos/cambiarEstado') ?>',
        type: 'POST',
        data: {
            id_movimiento: <?= $movimiento['id'] ?>,
            nuevo_estado: nuevoEstado
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarAlerta('success', response.message);
                // Recargar página después de 2 segundos
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                mostrarAlerta('error', response.message);
            }
        },
        error: function() {
            mostrarAlerta('error', 'Error al cambiar el estado del movimiento');
        }
    });
}

function ejecutarEliminacion() {
    $.ajax({
        url: '<?= base_url('movimientos/delete/' . $movimiento['id']) ?>',
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarAlerta('success', response.message);
                // Redirigir a la lista después de 2 segundos
                setTimeout(function() {
                    window.location.href = '<?= base_url('movimientos') ?>';
                }, 2000);
            } else {
                mostrarAlerta('error', response.message);
            }
        },
        error: function() {
            mostrarAlerta('error', 'Error al eliminar el movimiento');
        }
    });
}

function mostrarAlerta(tipo, mensaje) {
    const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alerta = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${iconClass}"></i> ${mensaje}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    
    $('.container-fluid').prepend(alerta);
    
    // Auto-hide después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
<?= $this->endSection() ?>
