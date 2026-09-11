<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * Configuración del Sistema GMV
     */

    // Configuración básica del remitente
    public string $fromEmail  = 'tms.alert.system@gmail.com';
    public string $fromName   = 'Sistema GMV - Gestión de Mantenimiento de Vehículos';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'Sistema GMV v1.0';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     * Opciones: 'mail', 'sendmail', 'smtp'
     * Recomendado: 'smtp' para mayor confiabilidad
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     * Ejemplos comunes:
     * - Gmail: smtp.gmail.com
     * - Outlook: smtp-mail.outlook.com
     * - Yahoo: smtp.mail.yahoo.com
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * SMTP Username (generalmente el correo completo)
     */
    public string $SMTPUser = 'tms.alert.system@gmail.com';

    /**
     * SMTP Password (usar contraseña de aplicación si es Gmail)
     */
    public string $SMTPPass = '$@l3rt42026';

    /**
     * SMTP Port
     * - 587: TLS (recomendado)
     * - 465: SSL
     * - 25: Sin encriptación (no recomendado)
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 30;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     * 'tls' para puerto 587
     * 'ssl' para puerto 465
     * '' para puerto 25 (no recomendado)
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     * 'html' permite correos con formato
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = true;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use "\r\n" to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use "\r\n" to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;

    /**
     * Configuraciones adicionales del Sistema GMV
     */
    
    /**
     * Habilitar/deshabilitar el envío de correos
     */
    public bool $emailEnabled = true;

    /**
     * Modo de desarrollo (true = no envía correos reales, solo logs)
     */
    public bool $developmentMode = false;

    /**
     * Correo de administrador para notificaciones del sistema
     */
    public string $adminEmail = 'tms.alert.system@gmail.com';

    /**
     * Plantillas de correo disponibles
     */
    public array $templates = [
        'welcome' => 'emails/welcome',
        'password_reset' => 'emails/password_reset',
        'maintenance_alert' => 'emails/maintenance_alert',
        'document_expiry' => 'emails/document_expiry',
        'system_notification' => 'emails/system_notification',
        'registro_bloqueado' => 'emails/registro_bloqueado',
    ];

    /**
     * Configuración de reintentos
     */
    public int $maxRetries = 3;
    public int $retryDelay = 5; // segundos
}
