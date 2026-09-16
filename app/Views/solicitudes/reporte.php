<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #e8e8e8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #333; }
        .page { max-width: 210mm; margin: 0 auto; background: white; min-height: 297mm; padding: 15mm 18mm; position: relative; }

        /* Header */
        .report-header { display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; border-bottom: 3px solid #1a56db; margin-bottom: 16px; }
        .report-header .logo { width: 60px; height: 60px; object-fit: contain; }
        .report-header .company-info { text-align: center; flex: 1; }
        .report-header .company-info h1 { font-size: 20px; font-weight: 700; color: #1a56db; margin: 0; letter-spacing: 0.5px; }
        .report-header .company-info p { font-size: 11px; color: #666; margin: 2px 0 0; }
        .report-header .date-info { text-align: right; font-size: 11px; color: #555; }
        .report-header .date-info strong { display: block; font-size: 13px; color: #333; }

        /* Title bar */
        .report-title { background: #1a56db; color: white; text-align: center; padding: 8px; font-size: 14px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 16px; border-radius: 4px; }

        /* Sections */
        .section { margin-bottom: 14px; }
        .section-title { font-size: 12px; font-weight: 700; color: #1a56db; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1a56db; padding-bottom: 4px; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .section-title i { font-size: 11px; }

        /* Info grid */
        .info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
        .info-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
        .info-grid.cols-2 { grid-template-columns: repeat(2, 1fr); }
        .info-item { padding: 6px 8px; background: #f8fafc; border-radius: 4px; border-left: 3px solid #1a56db; }
        .info-item .label { font-size: 9px; text-transform: uppercase; color: #888; letter-spacing: 0.5px; font-weight: 600; }
        .info-item .value { font-size: 12px; font-weight: 600; color: #222; margin-top: 1px; }

        /* Description box */
        .desc-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px; font-size: 11px; line-height: 1.6; }

        /* Suggestion box */
        .suggestion-box { background: #eff6ff; border: 1px solid #bfdbfe; border-left: 4px solid #1a56db; border-radius: 4px; padding: 10px; font-size: 11px; }
        .suggestion-box .sug-title { font-weight: 700; color: #1a56db; font-size: 11px; margin-bottom: 4px; }

        /* Badges */
        .badge-estado { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .badge-pendiente { background: #fef3c7; color: #92400e; }
        .badge-aprobada { background: #d1fae5; color: #065f46; }
        .badge-asignada { background: #dbeafe; color: #1e40af; }
        .badge-en-proceso { background: #e0e7ff; color: #3730a3; }
        .badge-completada { background: #d1fae5; color: #065f46; }
        .badge-default { background: #f3f4f6; color: #374151; }

        .badge-prioridad { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; }
        .prioridad-1 { background: #d1fae5; color: #065f46; }
        .prioridad-2 { background: #fef3c7; color: #92400e; }
        .prioridad-3 { background: #fed7aa; color: #9a3412; }
        .prioridad-4 { background: #fecaca; color: #991b1b; }

        /* Photo */
        .photo-container { text-align: center; margin-top: 8px; }
        .photo-container img { max-height: 200px; border-radius: 4px; border: 1px solid #e2e8f0; }

        /* Signatures */
        .signatures { display: flex; justify-content: space-between; margin-top: 40px; padding-top: 10px; }
        .signature { text-align: center; width: 30%; }
        .signature .line { border-top: 1px solid #333; margin-bottom: 4px; }
        .signature .name { font-size: 10px; font-weight: 600; color: #333; }
        .signature .role { font-size: 9px; color: #888; }

        /* Footer */
        .report-footer { position: absolute; bottom: 10mm; left: 18mm; right: 18mm; text-align: center; font-size: 9px; color: #aaa; border-top: 1px solid #e2e8f0; padding-top: 6px; }

        /* No print */
        .no-print-bar { max-width: 210mm; margin: 0 auto 10px; display: flex; justify-content: space-between; align-items: center; padding: 10px 0; }

        @media print {
            body { background: white; }
            .page { margin: 0; padding: 10mm 15mm; min-height: auto; }
            .no-print-bar { display: none !important; }
            .report-footer { position: fixed; bottom: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <a href="<?= base_url('solicitudes/show/' . $solicitud['id']) ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-primary">
            <i class="fas fa-print me-1"></i>Imprimir
        </button>
    </div>

    <div class="page">
        <!-- HEADER -->
        <div class="report-header">
            <img src="<?= base_url('public/assets/img/logo_tms.png') ?>" alt="Logo" class="logo">
            <div class="company-info">
                <h1><?= esc($empresa['nombre'] ?? 'Transportes GMV S.A.C.') ?></h1>
                <p>Sistema de Gestión de Mantenimiento Vehicular</p>
            </div>
            <div class="date-info">
                <strong><?= date('d/m/Y') ?></strong>
                <?= date('H:i') ?>
            </div>
        </div>

        <!-- TITLE -->
        <div class="report-title">
            Reporte de Solicitud de Mantenimiento &mdash; <?= esc($solicitud['codigo_consecutivo']) ?>
        </div>

        <!-- DATOS GENERALES -->
        <div class="section">
            <div class="section-title"><i class="fas fa-info-circle"></i> Datos Generales</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Código</div>
                    <div class="value"><?= esc($solicitud['codigo_consecutivo']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Fecha Solicitud</div>
                    <div class="value"><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Solicitante</div>
                    <div class="value"><?= esc($solicitud['solicitante']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Estado</div>
                    <div class="value">
                        <?php
                        $estadoUpper = strtoupper($solicitud['estado']);
                        $badgeClass = 'badge-default';
                        if (str_contains($estadoUpper, 'PENDIENTE')) $badgeClass = 'badge-pendiente';
                        elseif (str_contains($estadoUpper, 'APROBADA')) $badgeClass = 'badge-aprobada';
                        elseif (str_contains($estadoUpper, 'ASIGNADA')) $badgeClass = 'badge-asignada';
                        elseif (str_contains($estadoUpper, 'PROCESO')) $badgeClass = 'badge-en-proceso';
                        elseif (str_contains($estadoUpper, 'COMPLETADA') || str_contains($estadoUpper, 'FINALIZADA')) $badgeClass = 'badge-completada';
                        ?>
                        <span class="badge-estado <?= $badgeClass ?>"><?= esc($solicitud['estado']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- VEHÍCULO -->
        <div class="section">
            <div class="section-title"><i class="fas fa-car"></i> Información del Vehículo</div>
            <div class="info-grid cols-3">
                <div class="info-item">
                    <div class="label">Placa</div>
                    <div class="value"><?= esc($vehiculo['placa'] ?? 'N/A') ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Marca / Modelo</div>
                    <div class="value"><?= esc(($vehiculo['marca'] ?? '') . ' ' . ($vehiculo['modelo'] ?? '')) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Año</div>
                    <div class="value"><?= esc($vehiculo['anio'] ?? 'N/A') ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Kilometraje</div>
                    <div class="value"><?= number_format((int)($vehiculo['kilometraje'] ?? 0)) ?> km</div>
                </div>
                <div class="info-item" style="grid-column: span 2;">
                    <div class="label">Conductor Asignado</div>
                    <div class="value"><?= esc(($conductor['nombre'] ?? '') . ' ' . ($conductor['apellido'] ?? 'N/A')) ?></div>
                </div>
            </div>
        </div>

        <!-- DETALLE DE LA AVERÍA -->
        <div class="section">
            <div class="section-title"><i class="fas fa-wrench"></i> Detalle de la Avería</div>
            <div class="info-grid cols-2">
                <div class="info-item">
                    <div class="label">Tipo de Mantenimiento</div>
                    <div class="value"><?= esc($solicitud['tipo_mantenimiento'] ?? 'N/A') ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Tipo de Problema</div>
                    <div class="value"><?= esc($tipoProblema['nombre'] ?? 'N/A') ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Prioridad</div>
                    <div class="value">
                        <?php
                        $prioridades = [1 => 'Baja', 2 => 'Media', 3 => 'Alta', 4 => 'Crítica'];
                        $p = (int)($solicitud['prioridad'] ?? 2);
                        ?>
                        <span class="badge-prioridad prioridad-<?= $p ?>"><?= $prioridades[$p] ?? 'Media' ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">Condición de Movilidad</div>
                    <div class="value"><?= esc($solicitud['condicion_movilidad'] ?? 'No especificada') ?></div>
                </div>
                <div class="info-item" style="grid-column: span 2;">
                    <div class="label">Ubicación</div>
                    <div class="value"><?= esc($solicitud['ubicacion'] ?? 'No especificada') ?></div>
                </div>
            </div>
        </div>

        <!-- DESCRIPCIÓN -->
        <div class="section">
            <div class="section-title"><i class="fas fa-align-left"></i> Descripción</div>
            <div class="desc-box"><?= nl2br(esc($solicitud['descripcion'])) ?></div>
        </div>

        <!-- SUGERENCIA -->
        <?php if (!empty($sugerencia)): ?>
        <div class="section">
            <div class="suggestion-box">
                <div class="sug-title"><i class="fas fa-lightbulb me-1"></i> Sugerencia de Acción</div>
                <?= esc($sugerencia) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- TÉCNICO -->
        <?php if (!empty($tecnico)): ?>
        <div class="section">
            <div class="section-title"><i class="fas fa-user-cog"></i> Técnico Asignado</div>
            <div class="info-grid cols-2">
                <div class="info-item">
                    <div class="label">Nombre</div>
                    <div class="value"><?= esc(($tecnico['nombre'] ?? '') . ' ' . ($tecnico['apellido'] ?? '')) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Fecha de Asignación</div>
                    <div class="value"><?= !empty($solicitud['fecha_asignacion']) ? date('d/m/Y H:i', strtotime($solicitud['fecha_asignacion'])) : 'N/A' ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- FIRMAS -->
        <div class="signatures">
            <div class="signature">
                <div class="line"></div>
                <div class="name"><?= esc($solicitud['solicitante']) ?></div>
                <div class="role">Solicitante</div>
            </div>
            <div class="signature">
                <div class="line"></div>
                <div class="name">&nbsp;</div>
                <div class="role">Supervisor</div>
            </div>
            <div class="signature">
                <div class="line"></div>
                <div class="name"><?= !empty($tecnico) ? esc(($tecnico['nombre'] ?? '') . ' ' . ($tecnico['apellido'] ?? '')) : '&nbsp;' ?></div>
                <div class="role">Técnico</div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="report-footer">
            <?= esc($empresa['nombre'] ?? 'Transportes GMV S.A.C.') ?> &mdash; Documento generado el <?= date('d/m/Y H:i') ?> &mdash; <?= esc($solicitud['codigo_consecutivo']) ?>
        </div>
    </div>
</body>
</html>
