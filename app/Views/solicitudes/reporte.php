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

        /* ===== SEGUNDA HOJA: Hoja de trabajo del técnico ===== */
        .page-break { page-break-before: always; }
        .worksheet-title { background: #374151; color: white; text-align: center; padding: 8px; font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 14px; border-radius: 4px; }
        .ref-bar { display: flex; justify-content: space-between; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px 10px; font-size: 11px; margin-bottom: 14px; }
        .ref-bar strong { color: #1a56db; }

        .check-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; }
        .check-grid.cols-2 { grid-template-columns: repeat(2, 1fr); }
        .check-item { display: flex; align-items: center; gap: 6px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 11px; }
        .checkbox { width: 14px; height: 14px; border: 2px solid #374151; border-radius: 3px; flex-shrink: 0; display: inline-block; }

        .write-lines { margin-top: 6px; }
        .write-line { border-bottom: 1px solid #9ca3af; height: 22px; }

        .field-row { display: flex; gap: 12px; margin-bottom: 10px; }
        .field-box { flex: 1; }
        .field-box .f-label { font-size: 9px; text-transform: uppercase; color: #888; font-weight: 600; letter-spacing: 0.5px; }
        .field-box .f-line { border-bottom: 1px solid #9ca3af; height: 20px; }

        .materials-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .materials-table th { background: #f3f4f6; border: 1px solid #d1d5db; padding: 5px 8px; text-align: left; font-size: 10px; text-transform: uppercase; color: #555; }
        .materials-table td { border: 1px solid #d1d5db; padding: 5px 8px; height: 22px; }

        @media print {
            body { background: white; }
            .page { margin: 0; padding: 10mm 15mm; min-height: auto; }
            .no-print-bar { display: none !important; }
            .report-footer { position: fixed; bottom: 0; }
            .page-break { page-break-before: always; }
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

    <!-- ==================== SEGUNDA HOJA: HOJA DE TRABAJO DEL TÉCNICO ==================== -->
    <div class="page page-break">
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

        <div class="worksheet-title">
            Hoja de Trabajo del Técnico &mdash; <?= esc($solicitud['codigo_consecutivo']) ?>
        </div>

        <!-- Referencia -->
        <div class="ref-bar">
            <span><strong>Placa:</strong> <?= esc($vehiculo['placa'] ?? 'N/A') ?></span>
            <span><strong>Vehículo:</strong> <?= esc(($vehiculo['marca'] ?? '') . ' ' . ($vehiculo['modelo'] ?? '')) ?></span>
            <span><strong>Km:</strong> <?= number_format((int)($vehiculo['kilometraje'] ?? 0)) ?></span>
            <span><strong>Técnico:</strong> <?= !empty($tecnico) ? esc(($tecnico['nombre'] ?? '') . ' ' . ($tecnico['apellido'] ?? '')) : '_______________' ?></span>
        </div>

        <!-- Recepción del vehículo -->
        <div class="section">
            <div class="section-title"><i class="fas fa-clipboard-check"></i> Recepción del Vehículo</div>
            <div class="field-row">
                <div class="field-box">
                    <div class="f-label">Fecha de ingreso</div>
                    <div class="f-line"></div>
                </div>
                <div class="field-box">
                    <div class="f-label">Hora</div>
                    <div class="f-line"></div>
                </div>
                <div class="field-box">
                    <div class="f-label">Kilometraje al ingreso</div>
                    <div class="f-line"></div>
                </div>
                <div class="field-box">
                    <div class="f-label">Nivel de combustible</div>
                    <div class="f-line"></div>
                </div>
            </div>
        </div>

        <!-- Diagnóstico -->
        <div class="section">
            <div class="section-title"><i class="fas fa-stethoscope"></i> Diagnóstico Inicial</div>
            <div class="check-grid">
                <div class="check-item"><span class="checkbox"></span> Motor</div>
                <div class="check-item"><span class="checkbox"></span> Transmisión</div>
                <div class="check-item"><span class="checkbox"></span> Frenos</div>
                <div class="check-item"><span class="checkbox"></span> Suspensión</div>
                <div class="check-item"><span class="checkbox"></span> Dirección</div>
                <div class="check-item"><span class="checkbox"></span> Sistema eléctrico</div>
                <div class="check-item"><span class="checkbox"></span> Neumáticos</div>
                <div class="check-item"><span class="checkbox"></span> Carrocería</div>
                <div class="check-item"><span class="checkbox"></span> Aire acondicionado</div>
                <div class="check-item"><span class="checkbox"></span> Sistema de escape</div>
                <div class="check-item"><span class="checkbox"></span> Refrigeración</div>
                <div class="check-item"><span class="checkbox"></span> Otro: ____________</div>
            </div>
            <div class="write-lines">
                <div class="f-label" style="margin-top:8px;">Observaciones del diagnóstico</div>
                <div class="write-line"></div>
                <div class="write-line"></div>
                <div class="write-line"></div>
            </div>
        </div>

        <!-- Trabajos realizados -->
        <div class="section">
            <div class="section-title"><i class="fas fa-tools"></i> Trabajos Realizados</div>
            <div class="check-grid cols-2">
                <div class="check-item"><span class="checkbox"></span> Cambio de aceite y filtro</div>
                <div class="check-item"><span class="checkbox"></span> Cambio de filtro de aire</div>
                <div class="check-item"><span class="checkbox"></span> Cambio de filtro de combustible</div>
                <div class="check-item"><span class="checkbox"></span> Ajuste de frenos</div>
                <div class="check-item"><span class="checkbox"></span> Cambio de pastillas/zapatas</div>
                <div class="check-item"><span class="checkbox"></span> Reparación de motor</div>
                <div class="check-item"><span class="checkbox"></span> Reparación eléctrica</div>
                <div class="check-item"><span class="checkbox"></span> Cambio de neumáticos</div>
                <div class="check-item"><span class="checkbox"></span> Alineación y balanceo</div>
                <div class="check-item"><span class="checkbox"></span> Otro: ____________</div>
            </div>
            <div class="write-lines">
                <div class="f-label" style="margin-top:8px;">Detalle de trabajos realizados</div>
                <div class="write-line"></div>
                <div class="write-line"></div>
                <div class="write-line"></div>
                <div class="write-line"></div>
            </div>
        </div>

        <!-- Materiales utilizados -->
        <div class="section">
            <div class="section-title"><i class="fas fa-boxes"></i> Materiales / Repuestos Utilizados</div>
            <table class="materials-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Descripción</th>
                        <th style="width:80px;">Cantidad</th>
                        <th style="width:100px;">Unidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <!-- Resultado final -->
        <div class="section">
            <div class="section-title"><i class="fas fa-flag-checkered"></i> Resultado Final</div>
            <div class="check-grid cols-2">
                <div class="check-item"><span class="checkbox"></span> Reparado — Vehículo operativo</div>
                <div class="check-item"><span class="checkbox"></span> Reparación parcial — Requiere repuestos</div>
                <div class="check-item"><span class="checkbox"></span> No reparado — Derivar a taller externo</div>
                <div class="check-item"><span class="checkbox"></span> Pendiente — Esperando aprobación</div>
            </div>
            <div class="field-row" style="margin-top:10px;">
                <div class="field-box">
                    <div class="f-label">Fecha de salida</div>
                    <div class="f-line"></div>
                </div>
                <div class="field-box">
                    <div class="f-label">Hora</div>
                    <div class="f-line"></div>
                </div>
                <div class="field-box">
                    <div class="f-label">Kilometraje a la salida</div>
                    <div class="f-line"></div>
                </div>
                <div class="field-box">
                    <div class="f-label">Horas trabajadas</div>
                    <div class="f-line"></div>
                </div>
            </div>
            <div class="write-lines">
                <div class="f-label" style="margin-top:4px;">Observaciones finales / recomendaciones</div>
                <div class="write-line"></div>
                <div class="write-line"></div>
            </div>
        </div>

        <!-- Firmas -->
        <div class="signatures">
            <div class="signature">
                <div class="line"></div>
                <div class="name"><?= !empty($tecnico) ? esc(($tecnico['nombre'] ?? '') . ' ' . ($tecnico['apellido'] ?? '')) : '&nbsp;' ?></div>
                <div class="role">Técnico responsable</div>
            </div>
            <div class="signature">
                <div class="line"></div>
                <div class="name">&nbsp;</div>
                <div class="role">Supervisor de mantenimiento</div>
            </div>
            <div class="signature">
                <div class="line"></div>
                <div class="name">&nbsp;</div>
                <div class="role">Recibido por (conductor)</div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="report-footer">
            <?= esc($empresa['nombre'] ?? 'Transportes GMV S.A.C.') ?> &mdash; Hoja de trabajo &mdash; <?= esc($solicitud['codigo_consecutivo']) ?>
        </div>
    </div>
</body>
</html>
