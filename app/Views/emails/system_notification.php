<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación del Sistema GMV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #ffc107;
            color: #212529;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #fff;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 0 0 5px 5px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            font-size: 12px;
            color: #666;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔔 Notificación del Sistema</h1>
        <p>Sistema GMV - Gestión de Mantenimiento de Vehículos</p>
    </div>
    
    <div class="content">
        <h2>Notificación Automática</h2>
        
        <div class="alert">
            <strong>⚠️ Atención Administrador:</strong><br>
            Se ha generado una notificación automática del sistema que requiere su atención.
        </div>
        
        <div class="info-box">
            <strong>Detalles de la Notificación:</strong><br>
            <strong>Fecha y Hora:</strong> <?= $date ?? date('Y-m-d H:i:s') ?><br>
            <strong>Sistema:</strong> <?= $system_name ?? 'Sistema GMV' ?><br>
            <strong>Usuario:</strong> <?= $user_name ?? 'Sistema Automático' ?>
        </div>
        
        <?php if (isset($message)): ?>
        <h3>Mensaje:</h3>
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; white-space: pre-line;">
<?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>
        
        <h3>Acciones Recomendadas:</h3>
        <ul>
            <li>Revisar el estado del sistema</li>
            <li>Verificar logs de actividad</li>
            <li>Contactar al equipo técnico si es necesario</li>
            <li>Documentar cualquier acción tomada</li>
        </ul>
        
        <div class="alert">
            <strong>Nota:</strong> Esta es una notificación automática del sistema. 
            Si considera que ha recibido este correo por error, por favor ignore este mensaje.
        </div>
        
        <p>Saludos,<br>
        <strong>Sistema Automático GMV</strong><br>
        <?= $company_name ?? 'Su Empresa' ?></p>
    </div>
    
    <div class="footer">
        <p>Notificación generada automáticamente el <?= $date ?? date('Y-m-d H:i:s') ?></p>
        <p>Sistema GMV - Gestión de Mantenimiento de Vehículos</p>
        <p><small>No responder a este correo - Es una notificación automática</small></p>
    </div>
</body>
</html>
