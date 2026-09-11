<?php if (empty($documentos)): ?>
    <div class="alert alert-info">
        <i class="mdi mdi-information-outline me-2"></i>
        No se encontraron documentos para este conductor.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover table-centered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tipo de Documento</th>
                    <th>Número</th>
                    <th>Emisión</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
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
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="mdi <?= $icon ?> text-<?= $estadoClass ?> me-2"></i>
                            <div>
                                <h6 class="mb-0"><?= esc($documento['nombre_tipo_documento'] ?? 'Documento') ?></h6>
                                <?php if (!empty($documento['descripcion_tipo_documento'])): ?>
                                    <small class="text-muted"><?= esc($documento['descripcion_tipo_documento']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><?= esc($documento['numero_documento'] ?? 'N/A') ?></td>
                    <td><?= $documento['fecha_emision'] ? date('d/m/Y', strtotime($documento['fecha_emision'])) : 'N/A' ?></td>
                    <td>
                        <?php if ($documento['fecha_vencimiento']): ?>
                            <?= date('d/m/Y', strtotime($documento['fecha_vencimiento'])) ?>
                            <?php if ($diasRestantes !== null): ?>
                                <br>
                                <small class="text-<?= $estadoClass ?>">
                                    (<?= $diasRestantes >= 0 ? $diasRestantes . ' días' : abs($diasRestantes) . ' días de vencido' ?>)
                                </small>
                            <?php endif; ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-<?= $estadoClass ?>">
                            <i class="mdi <?= $icon ?> me-1"></i>
                            <?= $documento['estado'] ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <?php if (!empty($documento['ruta_documento'])): ?>
                                <a href="<?= site_url('conductores/ver-documento/' . $documento['id']) ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   target="_blank"
                                   title="Ver documento">
                                    <i class="mdi mdi-eye-outline"></i>
                                </a>
                                <a href="<?= site_url('conductores/descargar-documento/' . $documento['id']) ?>" 
                                   class="btn btn-sm btn-outline-secondary"
                                   title="Descargar">
                                    <i class="mdi mdi-download"></i>
                                </a>
                            <?php else: ?>
                                <span class="badge bg-light text-muted">Sin archivo</span>
                            <?php endif; ?>
                            
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary" 
                                    onclick="editarDocumento(<?= $documento['id'] ?>)"
                                    title="Editar">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="confirmarEliminarDocumento(<?= $documento['id'] ?>, '<?= addslashes($documento['nombre_tipo_documento'] ?? 'este documento') ?>')"
                                    title="Eliminar">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
