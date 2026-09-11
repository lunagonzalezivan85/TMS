<div class="table-responsive">
    <table id="tablaDocumentos" class="table table-striped dt-responsive nowrap w-100">
        <thead>
            <tr>
                <th>Tipo de Documento</th>
                <th>Número</th>
                <th>Fecha Emisión</th>
                <th>Fecha Vencimiento</th>
                <th>Estado</th>
                <th>Archivo</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documentos as $documento): ?>
            <tr>
                <td>
                    <div class="fw-semibold"><?= esc($documento['nombre_tipo_documento'] ?? 'N/A') ?></div>
                    <?php if (!empty($documento['descripcion_tipo_documento'])): ?>
                        <small class="text-muted"><?= esc($documento['descripcion_tipo_documento']) ?></small>
                    <?php endif; ?>
                </td>
                <td><?= esc($documento['numero_documento'] ?? 'N/A') ?></td>
                <td><?= $documento['fecha_emision'] ? date('d/m/Y', strtotime($documento['fecha_emision'])) : 'N/A' ?></td>
                <td><?= $documento['fecha_vencimiento'] ? date('d/m/Y', strtotime($documento['fecha_vencimiento'])) : 'N/A' ?></td>
                <td>
                    <?php 
                    $estadoClass = '';
                    switch($documento['estado']) {
                        case 'VIGENTE':
                            $estadoClass = 'success';
                            break;
                        case 'POR_VENCER':
                            $estadoClass = 'warning';
                            break;
                        case 'VENCIDO':
                            $estadoClass = 'danger';
                            break;
                        default:
                            $estadoClass = 'secondary';
                    }
                    ?>
                    <span class="badge bg-<?= $estadoClass ?>"><?= $documento['estado'] ?></span>
                </td>
                <td>
                    <?php if (!empty($documento['ruta_documento'])): ?>
                        <a href="<?= base_url('conductores/ver-documento/' . $documento['id']) ?>" 
                           class="btn btn-sm btn-outline-primary" 
                           target="_blank">
                            <i class="mdi mdi-file-document-outline"></i> Ver
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Sin archivo</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                onclick="editarDocumento(<?= $documento['id'] ?>)">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                onclick="confirmarEliminarDocumento(<?= $documento['id'] ?>, '<?= addslashes($documento['nombre_tipo_documento'] ?? 'este documento') ?>')">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($documentos)): ?>
                <tr>
                    <td colspan="7" class="text-center">No se encontraron documentos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
