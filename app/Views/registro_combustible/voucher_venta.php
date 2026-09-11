<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Voucher de Venta</title>
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
    $fechaRegistro = $registro['fecha_registro'] ?? date('Y-m-d H:i:s');
    $fechaFormateada = date('d/m/Y H:i', strtotime($fechaRegistro));
?>
    <div class="voucher">
        <div class="header">
            <div class="title">GCM Transportes, S.A</div>
            <div class="subtitle">Voucher de Venta</div>
            <div class="meta">Venta Nº <?= esc($registro['id']) ?></div>
            <div class="meta">Fecha: <?= $fechaFormateada ?></div>
        </div>

        <div class="section">
            <div class="section-title">Cliente</div>
            <div class="row">
                <span>Nombre</span>
                <span><?= esc($registro['nombreCliente'] ?? 'N/A') ?></span>
            </div>
            <div class="row">
                <span>DNI</span>
                <span><?= esc($registro['dni'] ?? '—') ?></span>
            </div>
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
            <div class="section-title">Detalle de Venta</div>
            <div class="row">
                <span>Galones vendidos</span>
                <span><?= number_format((float) ($registro['cantidad_litros'] ?? 0), 2) ?></span>
            </div>
            <div class="row">
                <span>Observaciones</span>
                <span><?= esc($registro['observaciones'] ?? '—') ?></span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Montos</div>
            <div class="row">
                <span>Monto C$</span>
                <span><?= number_format((float) ($registro['monto_nio'] ?? 0), 2) ?></span>
            </div>
            <div class="row">
                <span>Monto $</span>
                <span><?= number_format((float) ($registro['monto_usd'] ?? 0), 2) ?></span>
            </div>
        </div>

        <div class="section">
            <div class="row">
                <span>Atendido por</span>
                <span><?= esc($registro['usuario_crea'] ?? $registro['usuario_edita'] ?? 'N/A') ?></span>
            </div>
        </div>

        <div class="separator"></div>

        <div class="footer">
            Gracias por su compra
        </div>

        <div class="print-button">
            <button onclick="window.print()">Imprimir</button>
        </div>
    </div>
</body>
</html>
