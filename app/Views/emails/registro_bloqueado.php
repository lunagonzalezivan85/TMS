<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro de Combustible Bloqueado - Sistema GMV</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #dc3545; color: #fff; padding: 20px; text-align: center; }
        .header h2 { margin: 0; font-size: 20px; }
        .body { padding: 20px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #fff; background: #dc3545; }
        .info-row { margin-bottom: 8px; }
        .label { font-weight: bold; color: #555; }
        .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-lock"></i> Registro Bloqueado Pendiente de Aprobación</h2>
        </div>
        <div class="body">
            <p>Se ha registrado un nuevo despacho de combustible que requiere revisión de supervisor.</p>

            <p><span class="badge">BLOQUEADO</span></p>

            <h3 style="color:#dc3545; border-bottom:1px solid #eee; padding-bottom:8px;">Detalle del Registro</h3>
            <table>
                <tr><th>ID Registro</th><td>#<?= $id ?? '—' ?></td></tr>
                <tr><th>Fecha</th><td><?= $fecha_registro ?? '—' ?></td></tr>
                <tr><th>Vehículo</th><td><?= esc($placa ?? '—') ?> — <?= esc($marca ?? '') ?> <?= esc($modelo ?? '') ?></td></tr>
                <tr><th>Conductor</th><td><?= esc($nombreCliente ?? '—') ?></td></tr>
                <tr><th>DNI</th><td><?= esc($dni ?? '—') ?></td></tr>
                <tr><th>Litros Despachados</th><td><?= number_format((float)($cantidad_litros ?? 0), 2) ?> L</td></tr>
                <tr><th>Kilometraje Anterior</th><td><?= number_format((float)($kilometraje_anterior ?? 0), 2) ?> km</td></tr>
                <tr><th>Kilometraje Actual</th><td><?= number_format((float)($kilometraje_actual ?? 0), 2) ?> km</td></tr>
                <tr><th>Rendimiento Calculado</th><td><?= number_format((float)($rendimiento ?? 0), 2) ?> km/gal</td></tr>
                <tr><th>Rendimiento Promedio</th><td><?= number_format((float)($rendimiento_promedio ?? 0), 2) ?> km/gal</td></tr>
            </table>

            <p style="margin-top:20px;">Ingrese al sistema para revisar y aprobar o rechazar este registro.</p>
        </div>
        <div class="footer">
            <p>Sistema GMV — Gestión de Mantenimiento de Vehículos</p>
            <p><small>Este es un mensaje automático; no responda a este correo.</small></p>
        </div>
    </div>
</body>
</html>
