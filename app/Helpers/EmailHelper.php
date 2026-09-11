<?php

/**
 * Email Helper para el Sistema GMV
 * 
 * Proporciona funciones para el envío de correos electrónicos
 * con plantillas, validaciones y manejo de errores
 */

if (!function_exists('isEmailEnabled')) {
    /**
     * Verifica si el sistema de correo está habilitado
     * 
     * @return bool
     */
    function isEmailEnabled(): bool
    {
        $config = config('Email');
        return $config->emailEnabled ?? false;
    }
}

if (!function_exists('isEmailConfigured')) {
    /**
     * Verifica si el correo está correctamente configurado
     * 
     * @return bool
     */
    function isEmailConfigured(): bool
    {
        $config = config('Email');
        
        // Verificar configuración básica
        if (empty($config->fromEmail) || empty($config->fromName)) {
            return false;
        }
        
        // Si usa SMTP, verificar configuración SMTP
        if ($config->protocol === 'smtp') {
            return !empty($config->SMTPHost) && 
                   !empty($config->SMTPUser) && 
                   !empty($config->SMTPPass) && 
                   !empty($config->SMTPPort);
        }
        
        return true;
    }
}

if (!function_exists('getEmailStatus')) {
    /**
     * Obtiene el estado completo del sistema de correo
     * 
     * @return array
     */
    function getEmailStatus(): array
    {
        $config = config('Email');
        
        return [
            'enabled' => isEmailEnabled(),
            'configured' => isEmailConfigured(),
            'protocol' => $config->protocol,
            'development_mode' => $config->developmentMode ?? false,
            'smtp_host' => $config->SMTPHost ?? '',
            'smtp_port' => $config->SMTPPort ?? 0,
            'from_email' => $config->fromEmail ?? '',
            'from_name' => $config->fromName ?? ''
        ];
    }
}

if (!function_exists('sendEmail')) {
    /**
     * Envía un correo electrónico básico
     * 
     * @param string $to Destinatario
     * @param string $subject Asunto
     * @param string $message Mensaje
     * @param array $options Opciones adicionales
     * @return array Resultado del envío
     */
    function sendEmail(string $to, string $subject, string $message, array $options = []): array
    {
        // Verificar si el correo está habilitado
        if (!isEmailEnabled()) {
            return [
                'success' => false,
                'error' => 'El sistema de correo está deshabilitado',
                'code' => 'EMAIL_DISABLED'
            ];
        }
        
        // Verificar configuración
        if (!isEmailConfigured()) {
            return [
                'success' => false,
                'error' => 'El sistema de correo no está configurado correctamente',
                'code' => 'EMAIL_NOT_CONFIGURED'
            ];
        }
        
        $config = config('Email');
        
        // Modo de desarrollo - solo log
        if ($config->developmentMode) {
            log_message('info', "EMAIL (DEV MODE) - To: {$to}, Subject: {$subject}");
            return [
                'success' => true,
                'message' => 'Correo enviado en modo desarrollo (solo log)',
                'dev_mode' => true
            ];
        }
        
        try {
            $email = \Config\Services::email();
            
            // Configurar remitente
            $email->setFrom($config->fromEmail, $config->fromName);
            
            // Configurar destinatario
            $email->setTo($to);
            
            // Configurar CC si se proporciona
            if (!empty($options['cc'])) {
                $email->setCC($options['cc']);
            }
            
            // Configurar BCC si se proporciona
            if (!empty($options['bcc'])) {
                $email->setBCC($options['bcc']);
            }
            
            // Configurar asunto y mensaje
            $email->setSubject($subject);
            $email->setMessage($message);
            
            // Configurar prioridad si se proporciona
            if (!empty($options['priority'])) {
                $email->setPriority($options['priority']);
            }
            
            // Adjuntar archivos si se proporcionan
            if (!empty($options['attachments'])) {
                foreach ($options['attachments'] as $attachment) {
                    if (is_array($attachment)) {
                        $email->attach($attachment['path'], $attachment['disposition'] ?? 'attachment', $attachment['name'] ?? null);
                    } else {
                        $email->attach($attachment);
                    }
                }
            }
            
            // Intentar envío con reintentos
            $maxRetries = $config->maxRetries ?? 3;
            $retryDelay = $config->retryDelay ?? 5;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                if ($email->send()) {
                    log_message('info', "Email enviado exitosamente a: {$to}");
                    return [
                        'success' => true,
                        'message' => 'Correo enviado exitosamente',
                        'attempt' => $attempt
                    ];
                }
                
                if ($attempt < $maxRetries) {
                    sleep($retryDelay);
                }
            }
            
            // Si llegamos aquí, falló después de todos los intentos
            $error = $email->printDebugger(['headers']);
            log_message('error', "Error enviando email a {$to}: " . $error);
            
            return [
                'success' => false,
                'error' => 'Error al enviar el correo después de ' . $maxRetries . ' intentos',
                'debug' => $error,
                'code' => 'SEND_FAILED'
            ];
            
        } catch (\Exception $e) {
            log_message('error', "Excepción enviando email: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error interno: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }
}

if (!function_exists('sendEmailWithTemplate')) {
    /**
     * Envía un correo usando una plantilla
     * 
     * @param string $to Destinatario
     * @param string $template Nombre de la plantilla
     * @param array $data Datos para la plantilla
     * @param array $options Opciones adicionales
     * @return array Resultado del envío
     */
    function sendEmailWithTemplate(string $to, string $template, array $data = [], array $options = []): array
    {
        $config = config('Email');
        
        // Verificar si la plantilla existe
        if (!isset($config->templates[$template])) {
            return [
                'success' => false,
                'error' => "Plantilla de correo '{$template}' no encontrada",
                'code' => 'TEMPLATE_NOT_FOUND'
            ];
        }
        
        try {
            // Cargar la vista de la plantilla
            $templatePath = $config->templates[$template];
            $message = view($templatePath, $data);
            
            // Usar el asunto de las opciones o generar uno por defecto
            $subject = $options['subject'] ?? "Notificación del Sistema GMV";
            
            // Enviar el correo
            return sendEmail($to, $subject, $message, $options);
            
        } catch (\Exception $e) {
            log_message('error', "Error cargando plantilla de email '{$template}': " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error cargando la plantilla de correo: ' . $e->getMessage(),
                'code' => 'TEMPLATE_ERROR'
            ];
        }
    }
}

if (!function_exists('sendSystemNotification')) {
    /**
     * Envía una notificación del sistema al administrador
     * 
     * @param string $subject Asunto
     * @param string $message Mensaje
     * @param array $options Opciones adicionales
     * @return array Resultado del envío
     */
    function sendSystemNotification(string $subject, string $message, array $options = []): array
    {
        $config = config('Email');
        $adminEmail = $config->adminEmail ?? '';
        
        if (empty($adminEmail)) {
            return [
                'success' => false,
                'error' => 'No se ha configurado el correo del administrador',
                'code' => 'ADMIN_EMAIL_NOT_SET'
            ];
        }
        
        // Prefijo para identificar notificaciones del sistema
        $subject = "[Sistema GMV] " . $subject;
        
        return sendEmail($adminEmail, $subject, $message, $options);
    }
}

if (!function_exists('notificarSupervisorRegistroBloqueado')) {
    /**
     * Notifica al supervisor que un registro de combustible quedó bloqueado.
     *
     * @param array $datosRegistro Datos del registro (id, placa, marca, modelo, cantidad_litros, etc.)
     * @return array Resultado del envío
     */
    function notificarSupervisorRegistroBloqueado(array $datosRegistro): array
    {
        $config = config('Email');
        $to = $config->adminEmail ?? '';

        if (empty($to)) {
            log_message('warning', '[EmailHelper] No hay correo de supervisor configurado para notificación de bloqueo.');
            return [
                'success' => false,
                'error'   => 'No hay correo de supervisor configurado',
                'code'    => 'SUPERVISOR_EMAIL_NOT_SET',
            ];
        }

        $subject = 'Registro de Combustible Bloqueado — Revisión de Supervisor Requerida';

        return sendEmailWithTemplate($to, 'registro_bloqueado', $datosRegistro, [
            'subject' => $subject,
            'priority' => 1,
        ]);
    }
}

if (!function_exists('testEmailConnection')) {
    /**
     * Prueba la conexión de correo electrónico
     * 
     * @return array Resultado de la prueba
     */
    function testEmailConnection(): array
    {
        if (!isEmailEnabled()) {
            return [
                'success' => false,
                'error' => 'El sistema de correo está deshabilitado',
                'code' => 'EMAIL_DISABLED'
            ];
        }
        
        if (!isEmailConfigured()) {
            return [
                'success' => false,
                'error' => 'El sistema de correo no está configurado correctamente',
                'code' => 'EMAIL_NOT_CONFIGURED'
            ];
        }
        
        $config = config('Email');
        
        // En modo desarrollo, simular éxito
        if ($config->developmentMode) {
            return [
                'success' => true,
                'message' => 'Conexión simulada en modo desarrollo',
                'dev_mode' => true
            ];
        }
        
        try {
            // Para SMTP, intentar conectar
            if ($config->protocol === 'smtp') {
                $email = \Config\Services::email();
                
                // Intentar una conexión básica
                $testMessage = "Prueba de conexión - " . date('Y-m-d H:i:s');
                $email->setFrom($config->fromEmail, $config->fromName);
                $email->setTo($config->fromEmail); // Enviar a sí mismo
                $email->setSubject('Prueba de Conexión - Sistema GMV');
                $email->setMessage($testMessage);
                
                // No enviar realmente, solo validar configuración
                if ($email->send(false)) {
                    return [
                        'success' => true,
                        'message' => 'Conexión SMTP exitosa'
                    ];
                } else {
                    return [
                        'success' => false,
                        'error' => 'Error en la conexión SMTP',
                        'debug' => $email->printDebugger(['headers']),
                        'code' => 'SMTP_CONNECTION_FAILED'
                    ];
                }
            }
            
            return [
                'success' => true,
                'message' => 'Configuración válida para protocolo: ' . $config->protocol
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Error probando conexión: ' . $e->getMessage(),
                'code' => 'CONNECTION_TEST_FAILED'
            ];
        }
    }
}

if (!function_exists('validateEmailAddress')) {
    /**
     * Valida una dirección de correo electrónico
     * 
     * @param string $email
     * @return bool
     */
    function validateEmailAddress(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('getEmailTemplates')) {
    /**
     * Obtiene la lista de plantillas de correo disponibles
     * 
     * @return array
     */
    function getEmailTemplates(): array
    {
        $config = config('Email');
        return $config->templates ?? [];
    }
}
