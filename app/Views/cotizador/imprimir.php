<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cotización <?= esc($cotizacion['numero_cotizacion'] ?? '') ?> - GCM Transportes</title>
<style>
@page { margin: 1.5cm; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10.5pt; color: #111827; line-height: 1.5; }
.print-wrap { max-width: 800px; margin: 0 auto; }

/* Header */
.header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
.header .logo { width: 130px; height: auto; }
.header .company { text-align: right; }
.header .company h1 { font-size: 16pt; color: #0B5E73; margin: 0; }
.header .company .quote-num { font-size: 10.5pt; margin-top: 4px; }

/* Date */
.date { text-align: right; margin-bottom: 18px; font-size: 10.5pt; }

/* Attention */
.attention { margin-bottom: 20px; }
.attention table { width: 100%; border-collapse: collapse; }
.attention td { padding: 3px 0; vertical-align: top; }
.attention td:first-child { width: 120px; font-weight: bold; }

/* Body text */
.body-text { margin-bottom: 18px; text-align: justify; }
.section-title { font-weight: bold; font-size: 11pt; margin: 18px 0 10px; }

/* Offer table */
.offer-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
.offer-table th { background: #BFEAF7; padding: 7px; text-align: center; font-weight: bold; border: 0.7pt solid #111827; font-size: 9.5pt; }
.offer-table td { padding: 7px; text-align: center; border: 0.7pt solid #111827; font-size: 10pt; }

/* Details table */
.details-table { width: 65%; border-collapse: collapse; margin-bottom: 20px; }
.details-table td { padding: 5px 10px; border: 0.4pt solid #CBD5E1; }
.details-table td:first-child { background: #F8FAFC; font-weight: bold; }
.details-table td:last-child { text-align: right; }

/* Conditions */
.conditions { margin-bottom: 24px; }
.conditions ol { padding-left: 20px; }
.conditions li { margin-bottom: 4px; }

/* Signature */
.signature { margin-top: 30px; }
.signature .closing { margin-bottom: 20px; }
.signature .name { font-weight: bold; margin-bottom: 2px; }

/* Footer */
.footer { margin-top: 25px; padding-top: 12px; border-top: 1px solid #CBD5E1; font-size: 9pt; color: #374151; }
.footer p { margin: 2px 0; }

/* Print button */
.no-print { text-align: center; margin-bottom: 20px; }
.no-print button { padding: 8px 24px; font-size: 12pt; cursor: pointer; background: #07b889; color: #fff; border: none; border-radius: 6px; }
.no-print button:hover { background: #059c73; }
@media print { .no-print { display: none !important; } }
</style>
</head>
<body>
<div class="print-wrap">
    <div class="no-print">
        <button onclick="window.print()"><i class="fas fa-print"></i> Imprimir / Guardar PDF</button>
        &nbsp;&nbsp;
        <button onclick="window.close()" style="background:#64748b;">Cerrar</button>
    </div>

    <?php
    $d = $desglose;
    $producto = $cotizacion['producto_nombre'] ?? $cotizacion['producto_id'] ?? 'Diesel';
    $precioUnitario = $d['flete_por_galon'] ?? ($cotizacion['flete_por_galon'] ?? 0);
    $volumen = $cotizacion['volumen_galones'] ?? 0;
    $total = $cotizacion['precio_final'] ?? ($d['precio_final'] ?? 0);
    $distancia = $cotizacion['distancia_km'] ?? 0;
    $cliente = $cotizacion['cliente_nombre'] ?? 'Cliente';
    $ruc = $cotizacion['cliente_ruc'] ?? '';
    $telefono = $cotizacion['cliente_telefono'] ?? '';
    $numero = $cotizacion['numero_cotizacion'] ?? '';

    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $now = time();
    $fechaEs = 'Managua, ' . sprintf('%02d', (int)date('d', $now)) . ' de ' . $meses[(int)date('m', $now) - 1] . ' de ' . date('Y', $now);
    ?>

    <!-- Header -->
    <div class="header">
        <img src="<?= base_url('assets/img/sello-gcm.png') ?>" alt="GCM" class="logo" onerror="this.style.display='none'">
        <div class="company">
            <h1>GCM TRANSPORTES, S.A.</h1>
            <div class="quote-num">Cotización No. <?= esc($numero) ?></div>
        </div>
    </div>

    <div class="date"><?= $fechaEs ?></div>

    <!-- Attention -->
    <div class="attention">
        <table>
            <tr><td><strong>Atención</strong></td><td><?= esc($cliente) ?></td></tr>
            <tr><td><strong>RUC</strong></td><td><?= esc($ruc ?: '-') ?></td></tr>
            <tr><td><strong>Teléfono</strong></td><td><?= esc($telefono ?: '-') ?></td></tr>
            <tr><td><strong>Confidencial</strong></td><td>Sus Manos</td></tr>
        </table>
    </div>

    <!-- Body -->
    <div class="body-text">
        Reciba un cordial saludo de nuestra parte. A continuación tenemos el agrado de presentarle nuestra oferta de servicio de transporte y suministro de producto.
    </div>

    <div class="section-title">Oferta:</div>

    <!-- Offer table -->
    <table class="offer-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Precio combustible unitario C$/Gln</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= esc($producto) ?></td>
                <td>GCM Transportes</td>
                <td><?= esc($cliente) ?></td>
                <td>C$ <?= number_format($precioUnitario, 2) ?></td>
                <td><?= number_format($volumen, 0) ?></td>
                <td>C$ <?= number_format($total, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Details -->
    <table class="details-table">
        <tr><td>Distancia recorrida</td><td><?= number_format($distancia, 2) ?> km</td></tr>
    </table>

    <!-- Conditions -->
    <div class="conditions">
        <div class="section-title">Condiciones de Compra:</div>
        <ol>
            <li>Orden de compra con 48 horas de anticipación.</li>
            <li>Entrega de producto según coordinación operativa.</li>
            <li>Trámite de cheque 15 días.</li>
            <li>Precio sujeto a volumen y ruta cotizada.</li>
            <li>Tenemos carta de no retención IR.</li>
            <li>Vigencia de precio según confirmación comercial.</li>
        </ol>
    </div>

    <!-- Signature -->
    <div class="signature">
        <div class="closing">Sin más a qué referirme, me despido a la espera de sus comentarios.</div>
        <div>Atentamente,</div>
        <br><br>
        <div class="name">Supervisor de ventas y operaciones</div>
        <div>GCM Transportes S.A.</div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Carretera Cuesta del Plomo Km 9 1/2 Base Cuesta del Plomo, Ciudad Sandino</p>
        <p>Teléfono: (505) 22243064   Cel. (505) 83960715</p>
        <p>E-mail: llopez@gcmtransportes.com</p>
    </div>
</div>
</body>
</html>
