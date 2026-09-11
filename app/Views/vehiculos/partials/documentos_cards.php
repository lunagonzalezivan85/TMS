<?php if (!empty($documentos)): ?>
    <?php
    function calcularEstadoDocumento($fechaVencimiento) {
        if (empty($fechaVencimiento)) {
            return ['estado' => 'SIN_VENCIMIENTO', 'class' => 'sinvenc', 'icon' => 'fas fa-file', 'dias_restantes' => null];
        }
        $hoy = new DateTime();
        $vencimiento = new DateTime($fechaVencimiento);
        $diferencia = $hoy->diff($vencimiento);
        $diasRestantes = $diferencia->days;
        if ($hoy > $vencimiento) $diasRestantes = -$diasRestantes;
        if ($vencimiento < $hoy) {
            return ['estado' => 'VENCIDO', 'class' => 'vencido', 'icon' => 'fas fa-times-circle', 'dias_restantes' => $diasRestantes];
        } elseif ($diasRestantes <= 30) {
            return ['estado' => 'POR_VENCER', 'class' => 'porvencer', 'icon' => 'fas fa-exclamation-triangle', 'dias_restantes' => $diasRestantes];
        } else {
            return ['estado' => 'VIGENTE', 'class' => 'vigente', 'icon' => 'fas fa-check-circle', 'dias_restantes' => $diasRestantes];
        }
    }
    ?>
    <style>
    .doc-card { border-radius: 1rem; border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); transition: all .25s ease; overflow: hidden; position: relative; }
    .doc-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.12); transform: translateY(-2px); }
    .doc-card .accent-bar { position: absolute; top: 0; left: 0; right: 0; height: 4px; }
    .doc-card.vigente  .accent-bar { background: #28a745; }
    .doc-card.porvencer .accent-bar { background: #ffc107; }
    .doc-card.vencido  .accent-bar { background: #dc3545; }
    .doc-card.sinvenc  .accent-bar { background: #6c757d; }
    .doc-card .doc-icon { width: 44px; height: 44px; border-radius: .75rem; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; }
    .doc-card.vigente  .doc-icon { background: rgba(40,167,69,.12); color: #28a745; }
    .doc-card.porvencer .doc-icon { background: rgba(255,193,7,.12); color: #ffc107; }
    .doc-card.vencido  .doc-icon { background: rgba(220,53,69,.12); color: #dc3545; }
    .doc-card.sinvenc  .doc-icon { background: rgba(108,117,125,.12); color: #6c757d; }
    .doc-badge { font-size: .68rem; font-weight: 600; padding: .25rem .6rem; border-radius: 50px; }
    .doc-badge.vigente  { background: rgba(40,167,69,.12); color: #28a745; }
    .doc-badge.porvencer { background: rgba(255,193,7,.15); color: #d39e00; }
    .doc-badge.vencido  { background: rgba(220,53,69,.12); color: #dc3545; }
    .doc-badge.sinvenc  { background: rgba(108,117,125,.12); color: #6c757d; }
    .doc-info-row { display: flex; justify-content: space-between; align-items: center; padding: .35rem 0; border-bottom: 1px solid #f1f3f5; }
    .doc-info-row:last-child { border-bottom: none; }
    .doc-info-row .lbl { font-size: .78rem; color: #868e96; }
    .doc-info-row .val { font-size: .82rem; font-weight: 500; }
    </style>
    <div class="row g-3">
        <?php foreach ($documentos as $documento):
            $estadoInfo = calcularEstadoDocumento($documento['fecha_vencimiento'] ?? null);
            $estadoClass = $estadoInfo['class'];
            $icon = $estadoInfo['icon'];
            $estado = $estadoInfo['estado'];
            $diasRestantes = $estadoInfo['dias_restantes'];
            $numeroDocumento = $documento['numero'] ?? $documento['numero_documento'] ?? null;
            $notificacionDias = $documento['notificacion'] ?? null;
            $tipoArchivo = strtolower(pathinfo($documento['ruta_archivo'] ?? '', PATHINFO_EXTENSION));
        ?>
        <div class="col-xl-4 col-md-6">
            <div class="card h-100 doc-card <?= $estadoClass ?>">
                <div class="accent-bar"></div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="doc-icon">
                                <i class="<?= $icon ?>"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold"><?= esc($documento['nombre_tipo_documento'] ?? 'Documento') ?></h6>
                                <?php if (!empty($documento['descripcion_tipo_documento'])): ?>
                                    <small class="text-muted" style="font-size:.72rem;"><?= esc($documento['descripcion_tipo_documento']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="doc-badge <?= $estadoClass ?>"><?= $estado ?></span>
                    </div>

                    <div class="mb-2">
                        <div class="doc-info-row">
                            <span class="lbl"><i class="fas fa-hashtag me-1 opacity-50"></i>Número</span>
                            <span class="val"><?= $numeroDocumento ? esc($numeroDocumento) : 'N/A' ?></span>
                        </div>
                        <div class="doc-info-row">
                            <span class="lbl"><i class="fas fa-bell me-1 opacity-50"></i>Avisar con</span>
                            <span class="val"><?= $notificacionDias !== null ? esc($notificacionDias) . ' días' : '—' ?></span>
                        </div>
                        <div class="doc-info-row">
                            <span class="lbl"><i class="fas fa-calendar me-1 opacity-50"></i>Vencimiento</span>
                            <span class="val">
                                <?= $documento['fecha_vencimiento'] ? date('d/m/Y', strtotime($documento['fecha_vencimiento'])) : 'N/A' ?>
                                <?php if ($diasRestantes !== null): ?>
                                    <small class="text-<?= $estadoClass === 'vencido' ? 'danger' : ($estadoClass === 'porvencer' ? 'warning' : 'success') ?>">
                                        (<?= $diasRestantes >= 0 ? $diasRestantes . 'd' : abs($diasRestantes) . 'd vencido' ?>)
                                    </small>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px solid #f1f3f5;">
                        <div class="d-flex gap-1">
                            <?php if (!empty($documento['ruta_archivo'])): ?>
                                <a href="<?= site_url('vehiculos/verDocumento/' . $documento['id']) ?>" 
                                   class="btn btn-sm btn-light rounded-pill px-2 ver-documento"
                                   data-id="<?= $documento['id'] ?>"
                                   data-nombre="<?= esc($documento['nombre_tipo_documento'] ?? 'Documento') ?>"
                                   data-tipo="<?= $tipoArchivo ?>"
                                   title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= site_url('vehiculos/descargarDocumento/' . $documento['id']) ?>" 
                                   class="btn btn-sm btn-light rounded-pill px-2" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </a>
                            <?php else: ?>
                                <span class="badge bg-light text-muted">Sin archivo</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-light rounded-pill px-2" 
                                    onclick="editarDocumento(<?= $documento['id'] ?>)" title="Editar">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light rounded-pill px-2 text-danger" 
                                    onclick="confirmarEliminarDocumento(<?= $documento['id'] ?>, '<?= addslashes($documento['nombre_tipo_documento'] ?? 'este documento') ?>')" 
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-folder-open" style="font-size: 3.5rem; opacity: .2;"></i>
        <h5 class="mt-3 text-muted">No se encontraron documentos</h5>
        <p class="text-muted small">Usa el botón "Subir Documento" para agregar el primero</p>
    </div>
<?php endif; ?>
