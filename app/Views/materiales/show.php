<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box"></i> <?= $title ?>
        </h1>
        <div>
            <a href="<?= base_url('materiales/edit/' . $material['id']) ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="<?= base_url('materiales') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Información principal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Información del Material
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary"><?= esc($material['nombre']) ?></h5>
                            <p class="text-muted mb-3">
                                <strong>Código:</strong> <code><?= esc($material['codigo_consecutivo']) ?></code>
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h3 class="text-success">$<?= number_format($material['costo_unitario'], 2) ?></h3>
                            <p class="text-muted">por <?= esc($material['unidad_medida']) ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-primary">Unidad de Medida</h6>
                            <p class="mb-3">
                                <span class="badge badge-secondary badge-lg"><?= esc($material['unidad_medida']) ?></span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-primary">Fecha de Registro</h6>
                            <p class="mb-3">
                                <?= date('d/m/Y H:i', strtotime($material['fechaRegistro'])) ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-primary">Creado Por</h6>
                            <p class="mb-3">
                                <?= esc($material['nombre_creador'] ?? 'N/A') ?>
                                <?php if (!empty($material['email_creador'])): ?>
                                    <br><small class="text-muted"><?= esc($material['email_creador']) ?></small>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <?php if (!empty($material['fechaUpdate'])): ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Última Actualización</h6>
                                <p class="mb-3">
                                    <?= date('d/m/Y H:i', strtotime($material['fechaUpdate'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Actualizado Por</h6>
                                <p class="mb-3">
                                    <?= esc($material['nombre_editor'] ?? 'N/A') ?>
                                    <?php if (!empty($material['email_editor'])): ?>
                                        <br><small class="text-muted"><?= esc($material['email_editor']) ?></small>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial de uso -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-history"></i> Historial de Uso
                    </h6>
                </div>
                <div class="card-body">
                    <div id="historial-uso">
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-gray-300 mb-3"></i>
                            <p class="text-muted">Cargando historial de uso...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Acciones rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('materiales/edit/' . $material['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar Material
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="confirmarEliminacion(<?= $material['id'] ?>, '<?= esc($material['nombre']) ?>')">
                            <i class="fas fa-trash"></i> Eliminar Material
                        </button>
                        <hr>
                        <a href="<?= base_url('materiales/create') ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Material
                        </a>
                        <a href="<?= base_url('materiales') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-list"></i> Ver Todos los Materiales
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-bar"></i> Estadísticas de Uso
                    </h6>
                </div>
                <div class="card-body">
                    <div id="estadisticas-uso">
                        <div class="text-center py-3">
                            <i class="fas fa-spinner fa-spin text-gray-300 mb-2"></i>
                            <p class="text-muted small">Calculando estadísticas...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-info"></i> Información Adicional
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Código QR</h6>
                    <p class="text-muted small mb-3">
                        Código QR para identificación rápida del material.
                    </p>
                    
                    <!-- Contenedor del QR -->
                    <div id="qr-container" class="text-center mb-3" style="display: none;">
                        <div id="qrcode" class="mb-3"></div>
                        <button type="button" class="btn btn-success btn-sm me-2" onclick="descargarQR()">
                            <i class="fas fa-download"></i> Descargar PNG
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="imprimirQR()">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </div>
                    
                    <button type="button" id="btn-generar-qr" class="btn btn-outline-primary btn-sm" onclick="generarQR()">
                        <i class="fas fa-qrcode"></i> Generar QR
                    </button>
                    <button type="button" id="btn-ocultar-qr" class="btn btn-outline-secondary btn-sm" onclick="ocultarQR()" style="display: none;">
                        <i class="fas fa-eye-slash"></i> Ocultar QR
                    </button>

                    <hr>

                    <h6 class="text-primary">Exportar</h6>
                    <p class="text-muted small mb-3">
                        Exporte la información de este material.
                    </p>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="exportarMaterial()">
                        <i class="fas fa-download"></i> Exportar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el material <strong id="nombreMaterial"></strong>?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer y puede afectar registros históricos.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="formEliminar" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- QR Code usando API online -->
<script>
function confirmarEliminacion(id, nombre) {
    $('#nombreMaterial').text(nombre);
    $('#formEliminar').attr('action', '<?= base_url('materiales/delete/') ?>' + id);
    $('#modalEliminar').modal('show');
}

function generarQR() {
    console.log('Generando QR...');
    
    // Datos del material para el QR
    const materialData = {
        id: <?= $material['id'] ?>,
        codigo: '<?= esc($material['codigo_consecutivo']) ?>',
        nombre: '<?= esc($material['nombre']) ?>',
        unidad: '<?= esc($material['unidad_medida']) ?>',
        costo: <?= $material['costo_unitario'] ?>,
        url: '<?= base_url('materiales/show/' . $material['id']) ?>'
    };
    
    console.log('Datos del material:', materialData);
    
    // Crear texto para el QR (JSON con información del material)
    const qrText = JSON.stringify(materialData);
    console.log('Texto QR:', qrText);
    
    // Limpiar QR anterior si existe
    const qrElement = document.getElementById('qrcode');
    if (!qrElement) {
        alert('Error: Elemento qrcode no encontrado');
        return;
    }
    
    // Generar QR usando API de QR Server
    const qrSize = 200;
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${qrSize}x${qrSize}&data=${encodeURIComponent(qrText)}`;
    
    // Crear imagen del QR
    const qrImg = document.createElement('img');
    qrImg.src = qrUrl;
    qrImg.alt = 'Código QR del Material';
    qrImg.style.maxWidth = '100%';
    qrImg.style.border = '1px solid #ddd';
    qrImg.style.borderRadius = '5px';
    
    // Limpiar contenedor y agregar imagen
    qrElement.innerHTML = '';
    qrElement.appendChild(qrImg);
    
    // Mostrar contenedor del QR
    document.getElementById('qr-container').style.display = 'block';
    document.getElementById('btn-generar-qr').style.display = 'none';
    document.getElementById('btn-ocultar-qr').style.display = 'inline-block';
    
    console.log('QR generado exitosamente');
}

function ocultarQR() {
    document.getElementById('qr-container').style.display = 'none';
    document.getElementById('btn-generar-qr').style.display = 'inline-block';
    document.getElementById('btn-ocultar-qr').style.display = 'none';
}

function descargarQR() {
    const qrImg = document.querySelector('#qrcode img');
    if (qrImg) {
        // Crear canvas para convertir imagen a PNG descargable
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        canvas.width = 200;
        canvas.height = 200;
        
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            ctx.drawImage(img, 0, 0, 200, 200);
            
            // Descargar imagen
            const link = document.createElement('a');
            link.download = 'material_<?= $material['codigo_consecutivo'] ?>_qr.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        };
        img.src = qrImg.src;
    } else {
        alert('Error: No se encontró la imagen del QR para descargar');
    }
}

function imprimirQR() {
    const qrContainer = document.getElementById('qr-container').cloneNode(true);
    const materialInfo = `
        <div style="text-align: center; margin-bottom: 20px;">
            <h2><?= esc($material['nombre']) ?></h2>
            <p><strong>Código:</strong> <?= esc($material['codigo_consecutivo']) ?></p>
            <p><strong>Unidad:</strong> <?= esc($material['unidad_medida']) ?></p>
            <p><strong>Costo:</strong> $<?= number_format($material['costo_unitario'], 2) ?></p>
        </div>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>QR - <?= esc($material['nombre']) ?></title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
                    .qr-container { margin: 20px 0; }
                </style>
            </head>
            <body>
                ${materialInfo}
                <div class="qr-container">${qrContainer.innerHTML}</div>
                <p><small>Generado el <?= date('d/m/Y H:i') ?></small></p>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function exportarMaterial() {
    // Implementar exportación a PDF
    alert('Funcionalidad de exportación en desarrollo');
}

$(document).ready(function() {
    // Cargar historial de uso
    cargarHistorialUso();
    
    // Cargar estadísticas
    cargarEstadisticas();
});

function cargarHistorialUso() {
    // Simular carga de historial
    setTimeout(function() {
        $('#historial-uso').html(`
            <div class="text-center py-4">
                <i class="fas fa-clipboard-list fa-2x text-gray-300 mb-3"></i>
                <p class="text-muted">No hay registros de uso disponibles.</p>
                <small class="text-muted">Este material aún no ha sido utilizado en trabajos de mantenimiento.</small>
            </div>
        `);
    }, 1000);
}

function cargarEstadisticas() {
    // Simular carga de estadísticas
    setTimeout(function() {
        $('#estadisticas-uso').html(`
            <div class="text-center">
                <div class="row">
                    <div class="col-6">
                        <h4 class="text-primary">0</h4>
                        <small class="text-muted">Veces Usado</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">$0.00</h4>
                        <small class="text-muted">Costo Total</small>
                    </div>
                </div>
                <hr>
                <p class="text-muted small mb-0">
                    Las estadísticas se actualizarán cuando el material sea utilizado en trabajos.
                </p>
            </div>
        `);
    }, 1200);
}
</script>
<?= $this->endSection() ?>
