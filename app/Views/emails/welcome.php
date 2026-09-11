<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Sistema GMV</title>
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
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Bienvenido al Sistema GMV!</h1>
        <p>Gestión de Mantenimiento de Vehículos</p>
    </div>
    
    <div class="content">
        <h2>Hola <?= $user_name ?? 'Usuario' ?>,</h2>
        
        <p>Te damos la bienvenida al <strong><?= $system_name ?? 'Sistema GMV' ?></strong>, tu nueva plataforma para la gestión integral de mantenimiento de vehículos.</p>
        
        <p>Con nuestro sistema podrás:</p>
        <ul>
            <li>Gestionar el mantenimiento de tu flota de vehículos</li>
            <li>Programar y hacer seguimiento de las órdenes de trabajo</li>
            <li>Controlar el inventario de materiales y repuestos</li>
            <li>Generar reportes detallados de mantenimiento</li>
            <li>Administrar conductores y asignaciones</li>
        </ul>
        
        <?php if (isset($message)): ?>
        <div style="background-color: #e9ecef; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0;">
            <strong>Mensaje personalizado:</strong><br>
            <?= nl2br(htmlspecialchars($message)) ?>
        </div>
        <?php endif; ?>
        
        <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactar con nuestro equipo de soporte.</p>
        
        <p>¡Esperamos que tengas una excelente experiencia con nuestro sistema!</p>
        
        <p>Saludos cordiales,<br>
        <strong>Equipo del Sistema GMV</strong><br>
        <?= $company_name ?? 'Tu Empresa' ?></p>
    </div>
    
    <div class="footer">
        <p>Este correo fue enviado el <?= $date ?? date('Y-m-d H:i:s') ?></p>
        <p>Sistema GMV - Gestión de Mantenimiento de Vehículos</p>
    </div>
</body>
</html>
