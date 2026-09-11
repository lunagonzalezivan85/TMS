<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Voucher de Consumo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --voucher-width: 58mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            background: #ffffff;
            font-family: 'Courier New', Courier, monospace;
            color: #000000;
        }
        .voucher {
            width: var(--voucher-width);
            max-width: var(--voucher-width);
            margin: 0 auto;
            padding: 8mm 4mm;
        }
        .header {
            text-align: center;
            margin-bottom: 4mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 4mm;
        }
        .header .title {
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }
        .header .subtitle {
            font-size: 12px;
            margin-top: 2mm;
        }
        .header .meta {
            font-size: 10px;
            margin-top: 1mm;
        }
        .section {
            margin-bottom: 4mm;
        }
        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px dashed #000;
            padding-bottom: 1mm;
            margin-bottom: 2mm;
        }
        .row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 1.5mm;
        }
        .row span:first-child {
            max-width: 30mm;
        }
        .row span:last-child {
            text-align: right;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 4mm 0;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 4mm;
        }
        .print-button {
            margin-top: 6mm;
            text-align: center;
        }
        .print-button button {
            background: #000;
            color: #fff;
            border: none;
            padding: 6px 14px;
            font-size: 12px;
            cursor: pointer;
            border-radius: 4px;
        }
        @media print {
            body {
                margin: 0;
            }
            .voucher {
                padding: 6mm 3mm;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
<?php
    $fechaRegistro   = $registro['fecha_registro'] ?? date('Y-m-d H:i:s');
    $fechaFormateada = date('d/m/Y H:i', strtotime($fechaRegistro));
    $kmAnterior      = (float)($registro['kilometraje_anterior'] ?? 0);
    $kmActual        = (float)($registro['kilometraje_actual']   ?? 0);
    $cantidadLitros  = (float)($registro['cantidad_litros']      ?? 0);
    $kmRecorridos    = $kmActual - $kmAnterior;
    $galones         = $cantidadLitros / 3.78541;
    $rendimiento     = ($kmRecorridos > 0 && $galones > 0)
                        ? round($kmRecorridos / $galones, 2) : 0;

    // Frase de rendimiento
    if ($rendimiento <= 0) {
        $fraseRend = 'Mantén tu vehículo en buen estado para un mejor rendimiento.';
    } elseif ($rendimiento >= 45) {
        $fraseRend = "¡Excelente rendimiento! {$rendimiento} km/gal — tu vehículo está en óptimas condiciones.";
    } elseif ($rendimiento >= 34) {
        $fraseRend = "Buen rendimiento: {$rendimiento} km/gal. Sigue así para cuidar el combustible.";
    } elseif ($rendimiento >= 23) {
        $fraseRend = "Rendimiento regular: {$rendimiento} km/gal. Considera revisar filtros y presión de neumáticos.";
    } else {
        $fraseRend = "Rendimiento bajo: {$rendimiento} km/gal. Se recomienda una revisión mecánica del vehículo.";
    }
?>
    <div class="voucher">
        <div class="header">
            <div class="title"><?= 'GCM Transportes, S.A' ?></div>
            <div class="subtitle">Voucher de Consumo</div>
            <div class="meta">Recibo Nº <?= esc($registro['numero_ingreso_sag'] ?? 'Pendiente') ?></div>
            <div class="meta">Fecha: <?= $fechaFormateada ?></div>
        </div>

        <div class="section">
            <div class="section-title">Vehículo</div>
            <div class="row">
                <span>Placa</span>
                <span><?= esc($registro['placa'] ?? 'N/A') ?></span>
            </div>
            <div class="row">
                <span>Marca</span>
                <span><?= esc($registro['marca'] ?? '—') ?></span>
            </div>
            <div class="row">
                <span>Modelo</span>
                <span><?= esc($registro['modelo'] ?? '—') ?></span>
            </div>
        </div>

        
<div class="section">
     <div class="section-title">Consumo</div>
      <div class="row">
                <span>Cantidad (L)</span>
                <span><?= number_format($cantidadLitros, 2) ?></span>
            </div>
            <div class="row">
                <span>Equivalente (gal)</span>
                <span><?= number_format($cantidadLitros / 3.78541, 2) ?></span>
            </div>
        <?php if ((string)($registro['referencia1'] ?? '') !== '6'): ?>
        
           
           
            <div class="row">
                <span>Kilometraje ant.</span>
                <span><?= number_format($kmAnterior, 0) ?> km</span>
            </div>
            <div class="row">
                <span>Kilometraje act.</span>
                <span><?= number_format($kmActual, 0) ?> km</span>
            </div>
            <div class="row">
                <span>Rendimiento</span>
                <span><?= $rendimiento > 0 ? number_format($rendimiento, 2) . ' km/gal' : 'N/A' ?></span>
            </div>
       
        <?php endif; ?>
 </div>
        <?php if (!empty($registro['observaciones'])): ?>
        <div class="section">
            <div class="section-title">Observaciones</div>
            <div style="font-size: 11px; white-space: pre-wrap;">
                <?= esc($registro['observaciones']) ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="section">
            <div class="row">
                <span>Registrado por</span>
                <span><?= esc($registro['usuario_crea'] ?? $registro['usuario_edita'] ?? 'N/A') ?></span>
            </div>
        </div>

        <div class="separator"></div>

        <div class="footer">
            <?= esc($fraseRend) ?>
        </div>

        <div class="print-button">
            <button onclick="window.print()">Imprimir</button>
        </div>
    </div>
</body>
</html>
