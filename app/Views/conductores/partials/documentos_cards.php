<div class="row">
    <?php if (!empty($documentos)): ?>
        <?php foreach ($documentos as $documento): 
            $estadoClass = '';
            $icon = 'mdi-file-document-outline';
            
            switch($documento['estado']) {
                case 'VIGENTE':
                    $estadoClass = 'success';
                    $icon = 'mdi-check-circle';
                    break;
                case 'POR_VENCER':
                    $estadoClass = 'warning';
                    $icon = 'mdi-alert-circle';
                    break;
                case 'VENCIDO':
                    $estadoClass = 'danger';
                    $icon = 'mdi-close-circle';
                    break;
                default:
                    $estadoClass = 'secondary';
            }
            
            $diasRestantes = $documento['fecha_vencimiento'] ? 
                floor((strtotime($documento['fecha_vencimiento']) - time()) / (60 * 60 * 24)) : 
                null;
        ?>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 border-<?= $estadoClass ?> border-top-0 border-end-0 border-bottom-0 border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="mdi <?= $icon ?> text-<?= $estadoClass ?> me-2"></i>
                                <?= esc($documento['nombre_tipo_documento'] ?? 'Documento') ?>
                            </h5>
                            <?php if (!empty($documento['descripcion_tipo_documento'])): ?>
                                <p class="text-muted small mb-0"><?= esc($documento['descripcion_tipo_documento']) ?></p>
                            <?php endif; ?>
                        </div>
                        <span class="badge bg-<?= $estadoClass ?> rounded-pill"><?= $documento['estado'] ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Número:</span>
                            <span class="fw-medium"><?= esc($documento['numero_documento'] ?? 'N/A') ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Emisión:</span>
                            <span><?= $documento['fecha_emision'] ? date('d/m/Y', strtotime($documento['fecha_emision'])) : 'N/A' ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Vencimiento:</span>
                            <span class="fw-medium">
                                <?= $documento['fecha_vencimiento'] ? date('d/m/Y', strtotime($documento['fecha_vencimiento'])) : 'N/A' ?>
                                <?php if ($diasRestantes !== null): ?>
                                    <small class="text-<?= $estadoClass ?>">
                                        (<?= $diasRestantes >= 0 ? $diasRestantes . ' días' : abs($diasRestantes) . ' días de vencido' ?>)
                                    </small>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php if (!empty($documento['ruta_documento'])): ?>
                                <a href="<?= site_url('conductores/ver-documento/' . $documento['id']) ?>" 
                                   class="btn btn-sm btn-outline-primary me-1" 
                                   target="_blank">
                                    <i class="mdi mdi-eye-outline"></i> Ver
                                </a>
                                <a href="<?= site_url('conductores/descargar-documento/' . $documento['id']) ?>" 
                                   class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="mdi mdi-download"></i>
                                </a>
                            <?php else: ?>
                                <span class="badge bg-light text-muted">Sin archivo</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                    onclick="editarDocumento(<?= $documento['id'] ?>)">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                    onclick="confirmarEliminarDocumento(<?= $documento['id'] ?>, '<?= addslashes($documento['nombre_tipo_documento'] ?? 'este documento') ?>')">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="mdi mdi-file-document-remove-outline" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
                <h5>No se encontraron documentos</h5>
                <p class="text-muted">Agrega un nuevo documento para comenzar</p>
                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalAgregarDocumento">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> Agregar Documento
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>
