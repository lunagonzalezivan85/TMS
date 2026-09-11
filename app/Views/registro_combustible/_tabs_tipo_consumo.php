<?php
$catalogosCRC = is_array($catalogosCRC ?? null) ? $catalogosCRC : [];
$registrosPorTipo = [];
$registrosCatalogados = [];

foreach ($catalogosCRC as $referencia => $nombre) {
    $clave = (string) $referencia;
    $registrosPorTipo[$clave] = [];
}

foreach ($registros as $registroTipo) {
    $referencia = (string) ($registroTipo['referencia1'] ?? '');
    if ($referencia !== '' && array_key_exists($referencia, $registrosPorTipo)) {
        $registrosPorTipo[$referencia][] = $registroTipo;
        $registrosCatalogados[] = $registroTipo['id'] ?? null;
    }
}

$registrosOtros = array_values(array_filter($registros, static function ($registroTipo) use ($registrosCatalogados) {
    return !in_array($registroTipo['id'] ?? null, $registrosCatalogados, true);
}));

$tabsTipoConsumo = [];
foreach ($catalogosCRC as $referencia => $nombre) {
    $tabsTipoConsumo[] = [
        'id' => 'tipo-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', (string) $referencia),
        'referencia' => (string) $referencia,
        'nombre' => (string) $nombre,
        'registros' => $registrosPorTipo[(string) $referencia] ?? [],
    ];
}
$tabsTipoConsumo[] = [
    'id' => 'tipo-otros',
    'referencia' => null,
    'nombre' => 'Otros',
    'registros' => $registrosOtros,
];
?>

<ul class="nav nav-tabs mb-2" id="rcTabsTipoConsumo" role="tablist">
    <?php foreach ($tabsTipoConsumo as $indice => $tab): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $indice === 0 ? 'active' : '' ?> fw-semibold" id="tab-<?= esc($tab['id']) ?>" data-bs-toggle="tab" data-bs-target="#pane-<?= esc($tab['id']) ?>" type="button" role="tab">
                <i class="fas fa-gas-pump me-1 <?= $tab['id'] === 'tipo-otros' ? 'text-secondary' : 'text-success' ?>"></i><?= esc($tab['nombre']) ?>
                <span class="badge <?= $tab['id'] === 'tipo-otros' ? 'bg-secondary' : 'bg-success' ?> ms-1"><?= count($tab['registros']) ?></span>
            </button>
        </li>
    <?php endforeach; ?>
</ul>

<div class="tab-content" id="rcTabsTipoConsumoContent">
    <?php foreach ($tabsTipoConsumo as $indice => $tab): ?>
        <div class="tab-pane fade <?= $indice === 0 ? 'show active' : '' ?>" id="pane-<?= esc($tab['id']) ?>" role="tabpanel">
            <div class="card border shadow-sm">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0 table-sm">
                        <thead>
                            <tr>
                                <th>Vehículo</th>
                                <th>Conductor</th>
                                <th>Fecha</th>
                                <th>Consumo</th>
                                <th>Km recorridos</th>
                                <th>Estado</th>
                                <th>SAG</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tab['registros'])): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-gas-pump fa-2x mb-2 opacity-25"></i>
                                        <p class="mb-0">No hay registros en este grupo.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tab['registros'] as $registroTab):
                                    $kmAnteriorTab = (float) ($registroTab['kilometraje_anterior'] ?? 0);
                                    $kmActualTab = (float) ($registroTab['kilometraje_actual'] ?? 0);
                                    $litrosTab = (float) ($registroTab['cantidad_litros'] ?? 0);
                                    $estadoTab = strtoupper($registroTab['estado'] ?? 'APROBADO');
                                    $bloqueadoTab = $estadoTab === 'BLOQUEADO';
                                    $enviadoSagTab = (int) ($registroTab['enviado'] ?? 0) === 1;
                                    $kmRecorridosTab = $kmActualTab - $kmAnteriorTab;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= esc($registroTab['codigo_unidad'] ?? $registroTab['placa'] ?? 'N/A') ?> — <?= esc($registroTab['placa'] ?? 'N/A') ?></div>
                                            <small class="text-muted"><?= esc($registroTab['marca'] ?? '') ?></small>
                                        </td>
                                        <td><?= esc($registroTab['nombreCliente'] ?? '—') ?></td>
                                        <td><?= date('d/m/Y', strtotime($registroTab['fecha_registro'])) ?></td>
                                        <td>
                                            <div class="fw-bold"><?= number_format($litrosTab / 3.78541, 1) ?> gal</div>
                                            <small class="text-muted"><?= number_format($litrosTab, 2) ?> L</small>
                                        </td>
                                        <td><?= number_format($kmRecorridosTab) ?> km</td>
                                        <td><span class="badge <?= $bloqueadoTab ? 'bg-danger' : 'bg-success' ?>"><?= $bloqueadoTab ? 'Bloqueado' : 'Aprobado' ?></span></td>
                                        <td>
                                            <?php if ($enviadoSagTab): ?>
                                                <span class="text-success small"><i class="fas fa-check-circle me-1"></i>Enviado</span>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-warning btn-sm" data-action="reenviar-sag" data-id="<?= $registroTab['id'] ?>" title="Reenviar al SAG">
                                                    <i class="fas fa-sync-alt me-1"></i>Reenviar
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown" data-bs-boundary="window"><i class="fas fa-ellipsis-v"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li><a class="dropdown-item" href="<?= base_url('registro-combustible/show/' . $registroTab['id']) ?>"><i class="fas fa-eye me-2 text-info"></i>Ver detalle</a></li>
                                                    <li><a class="dropdown-item" href="<?= base_url('registro-combustible/edit/' . $registroTab['id']) ?>"><i class="fas fa-edit me-2 text-primary"></i>Editar</a></li>
                                                    <li><a class="dropdown-item" href="<?= base_url('registro-combustible/voucher/consumo/' . $registroTab['id']) ?>"><i class="fas fa-file-alt me-2 text-secondary"></i>Voucher</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><button class="dropdown-item text-danger" data-action="eliminar" data-id="<?= $registroTab['id'] ?>"><i class="fas fa-trash me-2"></i>Eliminar</button></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
