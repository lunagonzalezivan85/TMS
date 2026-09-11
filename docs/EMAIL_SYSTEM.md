# Sistema de Correo Electrónico - GMV

## Descripción General

El Sistema GMV incluye un sistema completo de correo electrónico que permite enviar notificaciones, alertas y comunicaciones automatizadas. El sistema está diseñado para ser flexible, seguro y fácil de configurar.

## Componentes del Sistema

### 1. Configuración (`app/Config/Email.php`)

Archivo de configuración principal que contiene todas las opciones del sistema de correo:

- **Configuración SMTP**: Servidor, puerto, credenciales
- **Configuración del remitente**: Email y nombre por defecto
- **Plantillas**: Lista de plantillas disponibles
- **Opciones avanzadas**: Reintentos, modo desarrollo, etc.

### 2. Helper (`app/Helpers/EmailHelper.php`)

Conjunto de funciones auxiliares para facilitar el envío de correos:

#### Funciones Principales:

- `isEmailEnabled()`: Verifica si el sistema de correo está habilitado
- `isEmailConfigured()`: Verifica si la configuración es correcta
- `getEmailStatus()`: Obtiene el estado completo del sistema
- `sendEmail()`: Envía un correo básico
- `sendEmailWithTemplate()`: Envía correo usando plantillas
- `sendSystemNotification()`: Envía notificaciones al administrador
- `testEmailConnection()`: Prueba la conexión de correo
- `validateEmailAddress()`: Valida direcciones de correo

### 3. Controlador de Pruebas (`app/Controllers/TestEmail.php`)

Controlador especializado para probar y diagnosticar el sistema de correo:

- Interfaz web para pruebas
- Envío de correos de prueba
- Diagnóstico de configuración
- Visualización de logs
- Pruebas de conexión

### 4. Plantillas de Correo (`app/Views/emails/`)

Plantillas HTML predefinidas para diferentes tipos de correo:

- `welcome.php`: Correo de bienvenida
- `system_notification.php`: Notificaciones del sistema
- `maintenance_alert.php`: Alertas de mantenimiento
- `password_reset.php`: Reseteo de contraseñas
- `document_expiry.php`: Vencimiento de documentos

## Configuración Inicial

### 1. Configurar Credenciales SMTP

Editar `app/Config/Email.php`:

```php
// Para Gmail
public string $SMTPHost = 'smtp.gmail.com';
public string $SMTPUser = 'tu-email@gmail.com';
public string $SMTPPass = 'tu-contraseña-de-aplicacion';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';

// Para Outlook
public string $SMTPHost = 'smtp-mail.outlook.com';
public string $SMTPUser = 'tu-email@outlook.com';
public string $SMTPPass = 'tu-contraseña';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

### 2. Configurar Remitente

```php
public string $fromEmail = 'noreply@tuempresa.com';
public string $fromName = 'Sistema GMV - Tu Empresa';
public string $adminEmail = 'admin@tuempresa.com';
```

### 3. Habilitar el Sistema

```php
public bool $emailEnabled = true;
public bool $developmentMode = false; // true para pruebas
```

## Uso del Sistema

### 1. Envío de Correo Básico

```php
// Cargar el helper
helper('EmailHelper');

// Enviar correo simple
$result = sendEmail(
    'destinatario@email.com',
    'Asunto del correo',
    'Mensaje del correo',
    [
        'priority' => 1, // Alta prioridad
        'cc' => 'copia@email.com'
    ]
);

if ($result['success']) {
    echo "Correo enviado exitosamente";
} else {
    echo "Error: " . $result['error'];
}
```

### 2. Envío con Plantilla

```php
// Datos para la plantilla
$data = [
    'user_name' => 'Juan Pérez',
    'system_name' => 'Sistema GMV',
    'message' => 'Mensaje personalizado',
    'company_name' => 'Mi Empresa'
];

// Enviar con plantilla
$result = sendEmailWithTemplate(
    'usuario@email.com',
    'welcome', // Nombre de la plantilla
    $data,
    ['subject' => 'Bienvenido al Sistema GMV']
);
```

### 3. Notificación del Sistema

```php
// Enviar notificación al administrador
$result = sendSystemNotification(
    'Error en el sistema',
    'Se ha detectado un error crítico en el módulo de mantenimiento.'
);
```

### 4. Verificar Estado del Sistema

```php
$status = getEmailStatus();

if ($status['enabled'] && $status['configured']) {
    echo "Sistema de correo listo";
} else {
    echo "Sistema de correo no disponible";
}
```

## Interfaz de Pruebas

Acceder a `/test-email` para usar la interfaz web de pruebas:

### Funcionalidades Disponibles:

1. **Estado del Sistema**: Visualización del estado actual
2. **Prueba de Conexión**: Verificar conectividad SMTP
3. **Envío de Correo Básico**: Formulario para envío simple
4. **Envío con Plantilla**: Formulario para usar plantillas
5. **Notificaciones**: Envío de notificaciones del sistema
6. **Configuración**: Ver detalles de configuración
7. **Logs**: Visualizar y limpiar logs de correo

## Configuraciones Especiales

### Modo Desarrollo

```php
public bool $developmentMode = true;
```

En modo desarrollo:
- No se envían correos reales
- Solo se registran en logs
- Útil para pruebas sin spam

### Configuración de Reintentos

```php
public int $maxRetries = 3;
public int $retryDelay = 5; // segundos
```

### Plantillas Personalizadas

Agregar nuevas plantillas en la configuración:

```php
public array $templates = [
    'mi_plantilla' => 'emails/mi_plantilla',
    // ... otras plantillas
];
```

## Seguridad

### Recomendaciones:

1. **Usar contraseñas de aplicación** para Gmail
2. **Configurar SPF/DKIM** en el dominio
3. **Usar TLS/SSL** para conexiones seguras
4. **Validar direcciones** antes del envío
5. **Limitar reintentos** para evitar bloqueos
6. **Monitorear logs** regularmente

### Validaciones Implementadas:

- Verificación de configuración antes del envío
- Validación de direcciones de correo
- Manejo de errores y excepciones
- Logging de todas las operaciones
- Protección contra envío masivo no autorizado

## Troubleshooting

### Problemas Comunes:

1. **"Authentication failed"**
   - Verificar credenciales SMTP
   - Usar contraseña de aplicación (Gmail)
   - Verificar que la cuenta permita aplicaciones menos seguras

2. **"Connection timeout"**
   - Verificar servidor SMTP y puerto
   - Comprobar firewall/proxy
   - Verificar conectividad a internet

3. **"Invalid address"**
   - Validar formato de direcciones de correo
   - Verificar que el dominio existe

4. **Correos van a spam**
   - Configurar SPF/DKIM
   - Usar dominio propio como remitente
   - Evitar palabras spam en asunto/contenido

### Logs y Debugging:

- Los logs se guardan en `writable/logs/`
- Usar la interfaz web para ver logs filtrados
- Activar modo debug en CodeIgniter para más detalles

## API Endpoints

### Rutas Disponibles:

- `GET /test-email/`: Interfaz principal
- `GET /test-email/status`: Estado del sistema (JSON)
- `GET /test-email/connection`: Prueba de conexión (JSON)
- `POST /test-email/send-test`: Envío de prueba (JSON)
- `POST /test-email/send-template`: Envío con plantilla (JSON)
- `POST /test-email/send-notification`: Notificación del sistema (JSON)
- `GET /test-email/info`: Información completa (JSON)
- `GET /test-email/logs`: Logs de correo (JSON)

## Integración con Otros Módulos

El sistema de correo puede integrarse fácilmente con otros módulos:

```php
// En cualquier controlador
helper('EmailHelper');

// Notificar vencimiento de documentos
if ($documento_vence_pronto) {
    sendEmailWithTemplate(
        $conductor_email,
        'document_expiry',
        ['documento' => $documento, 'dias_restantes' => $dias]
    );
}

// Alertas de mantenimiento
if ($mantenimiento_vencido) {
    sendSystemNotification(
        'Mantenimiento Vencido',
        "El vehículo {$placa} tiene mantenimiento vencido"
    );
}
```

## Conclusión

El sistema de correo del GMV proporciona una solución completa y flexible para todas las necesidades de comunicación por email del sistema. Con configuración adecuada y uso de las funciones helper, es fácil integrar notificaciones automáticas en cualquier parte del sistema.
