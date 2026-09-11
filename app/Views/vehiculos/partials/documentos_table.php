<?php if (!empty($documentos)): ?>
    <?php 
    // Función para calcular el estado basado en la fecha de vencimiento
    function calcularEstadoDocumento($fechaVencimiento) {
        if (empty($fechaVencimiento)) {
            return [
                'estado' => 'SIN_VENCIMIENTO', 
                'class' => 'secondary', 
                'icon' => 'mdi-file-document-outline',
                'dias_restantes' => null
            ];
        }
        
        $hoy = new DateTime();
        $vencimiento = new DateTime($fechaVencimiento);
        $diferencia = $hoy->diff($vencimiento);
        $diasRestantes = $diferencia->days;
        
        // Ajustar días restantes si la fecha ya pasó
        if ($hoy > $vencimiento) {
            $diasRestantes = -$diasRestantes;
        }
        
        if ($vencimiento < $hoy) {
            return [
                'estado' => 'VENCIDO', 
                'class' => 'danger', 
                'icon' => 'mdi-close-circle',
                'dias_restantes' => $diasRestantes
            ];
        } elseif ($diasRestantes <= 30) {
            return [
                'estado' => 'POR_VENCER', 
                'class' => 'warning', 
                'icon' => 'mdi-alert-circle',
                'dias_restantes' => $diasRestantes
            ];
        } else {
            return [
                'estado' => 'VIGENTE', 
                'class' => 'success', 
                'icon' => 'mdi-check-circle',
                'dias_restantes' => $diasRestantes
            ];
        }
    }
    ?>
    <div class="table-responsive">
        <table class="table table-hover table-centered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tipo de Documento</th>
                    <th>Número</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documentos as $documento): 
                    // Calcular el estado basado en la fecha de vencimiento
                    $estadoInfo = calcularEstadoDocumento($documento['fecha_vencimiento'] ?? null);
                    $estadoClass = $estadoInfo['class'];
                    $icon = $estadoInfo['icon'];
                    $estado = $estadoInfo['estado'];
                    $diasRestantes = $estadoInfo['dias_restantes'];
                    $numeroDocumento = $documento['numero'] ?? $documento['numero_documento'] ?? null;
                    $notificacionDias = $documento['notificacion'] ?? null;
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
                    <td>
                        <div><?= $numeroDocumento ? esc($numeroDocumento) : 'N/A' ?></div>
                        <small class="text-muted">Avisar con: <?= $notificacionDias !== null ? esc($notificacionDias) . ' días' : 'Sin configurar' ?></small>
                    </td>
                
                    <td>
                        <?php if ($documento['fecha_vencimiento']): ?>
                            <?= date('d/m/Y', strtotime($documento['fecha_vencimiento'])) ?>
                            <?php if ($diasRestantes !== null): ?>
                                <br>
                                <small class="text-<?= $estadoClass ?>">
                                </small>
                            <?php endif; ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-<?= $estadoClass ?>">
                            <i class="mdi <?= $icon ?> me-1"></i>
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <?php if (!empty($documento['ruta_archivo'])): ?>
                                <a href="<?= site_url('vehiculos/verDocumento/' . $documento['id']) ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Ver documento"
                                   target="_blank">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="<?= site_url('vehiculos/descargarDocumento/' . $documento['id']) ?>" 
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
<?php else: ?>
    <div class="alert alert-info">
        <i class="mdi mdi-information-outline me-2"></i>
        No se encontraron documentos para este vehículo.
    </div>
<?php endif; ?>
