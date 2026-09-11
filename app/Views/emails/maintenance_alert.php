<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Mantenimiento - Sistema GMV</title>
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
            background-color: #dc3545;
            color: white;
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
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .vehicle-info {
            background-color: #e9ecef;
            padding: 20px;
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
        .btn {
            display: inline-block;
            background-color: #dc3545;
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
        <h1>🚨 Alerta de Mantenimiento</h1>
        <p>Sistema GMV - Gestión de Mantenimiento de Vehículos</p>
    </div>
    
    <div class="content">
        <h2>Estimado/a <?= $user_name ?? 'Usuario' ?>,</h2>
        
        <div class="alert-danger">
            <strong>⚠️ ATENCIÓN:</strong> Se ha detectado una situación que requiere mantenimiento inmediato.
        </div>
        
        <p>El sistema ha identificado que uno o más vehículos de su flota requieren atención de mantenimiento urgente.</p>
        
        <div class="vehicle-info">
            <h3>📋 Detalles del Mantenimiento:</h3>
            <p><strong>Fecha de Alerta:</strong> <?= $date ?? date('Y-m-d H:i:s') ?></p>
            <p><strong>Tipo de Alerta:</strong> Mantenimiento Preventivo/Correctivo</p>
            <p><strong>Prioridad:</strong> <span style="color: #dc3545; font-weight: bold;">ALTA</span></p>
        </div>
        
        <?php if (isset($message)): ?>
        <h3>Descripción del Problema:</h3>
        <div class="alert-warning">
            <?= nl2br(htmlspecialchars($message)) ?>
        </div>
        <?php endif; ?>
        
        <h3>🔧 Acciones Recomendadas:</h3>
        <ol>
            <li><strong>Revisar inmediatamente</strong> el estado del vehículo</li>
            <li><strong>Programar mantenimiento</strong> en el menor tiempo posible</li>
            <li><strong>Verificar disponibilidad</strong> de materiales y repuestos</li>
            <li><strong>Asignar técnico</strong> especializado si es necesario</li>
            <li><strong>Documentar</strong> todas las acciones realizadas</li>
        </ol>
        
        <div class="alert-warning">
            <strong>Importante:</strong> No ignore esta alerta. El mantenimiento preventivo 
            ayuda a evitar averías costosas y garantiza la seguridad de los conductores.
        </div>
        
        <p>Para más información, acceda al sistema y revise los detalles completos en el módulo de mantenimiento.</p>
        
        <p>Si tiene alguna pregunta o necesita asistencia técnica, contacte inmediatamente con el departamento de mantenimiento.</p>
        
        <p>Saludos cordiales,<br>
        <strong>Sistema Automático de Alertas GMV</strong><br>
        <?= $company_name ?? 'Su Empresa' ?></p>
    </div>
    
    <div class="footer">
        <p>Alerta generada automáticamente el <?= $date ?? date('Y-m-d H:i:s') ?></p>
        <p>Sistema GMV - Gestión de Mantenimiento de Vehículos</p>
        <p><small>Esta es una alerta automática del sistema - Responda con urgencia</small></p>
    </div>
</body>
</html>
