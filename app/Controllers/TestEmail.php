<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestEmail extends Controller
{
    protected $helpers = ['EmailHelper', 'form'];

    public function __construct()
    {
        // Cargar el helper de correo
        helper(['EmailHelper', 'form']);
    }

    /**
     * Página principal de pruebas de correo
     */
    public function index()
    {
        // Verificar autenticación (opcional - comentar si no se requiere)
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión para acceder a esta página');
        }

        $data = [
            'title' => 'Pruebas de Correo Electrónico - Sistema GMV',
            'emailStatus' => getEmailStatus(),
            'templates' => getEmailTemplates()
        ];

        return view('test_email/index', $data);
    }

    /**
     * Prueba el estado del sistema de correo
     */
    public function testStatus()
    {
        $status = getEmailStatus();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Prueba la conexión de correo
     */
    public function testConnection()
    {
        $result = testEmailConnection();
        
        return $this->response->setJSON($result);
    }

    /**
     * Envía un correo de prueba básico
     */
    public function sendTestEmail()
    {
        // Validar datos de entrada
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'to_email' => 'required|valid_email',
            'subject' => 'required|min_length[3]|max_length[200]',
            'message' => 'required|min_length[10]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Datos de entrada inválidos',
                'validation_errors' => $validation->getErrors()
            ]);
        }

        $toEmail = $this->request->getPost('to_email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $priority = $this->request->getPost('priority') ?? 3;

        // Preparar opciones
        $options = [
            'priority' => (int)$priority
        ];

        // Agregar CC si se proporciona
        $ccEmail = $this->request->getPost('cc_email');
        if (!empty($ccEmail) && validateEmailAddress($ccEmail)) {
            $options['cc'] = $ccEmail;
        }

        // Enviar correo
        $result = sendEmail($toEmail, $subject, $message, $options);
        
        // Log del resultado
        if ($result['success']) {
            log_message('info', "Correo de prueba enviado exitosamente a: {$toEmail}");
        } else {
            log_message('error', "Error enviando correo de prueba a {$toEmail}: " . ($result['error'] ?? 'Error desconocido'));
        }

        return $this->response->setJSON($result);
    }

    /**
     * Envía un correo usando una plantilla
     */
    public function sendTemplateEmail()
    {
        // Validar datos de entrada
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'to_email' => 'required|valid_email',
            'template' => 'required',
            'subject' => 'required|min_length[3]|max_length[200]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Datos de entrada inválidos',
                'validation_errors' => $validation->getErrors()
            ]);
        }

        $toEmail = $this->request->getPost('to_email');
        $template = $this->request->getPost('template');
        $subject = $this->request->getPost('subject');

        // Datos de ejemplo para la plantilla
        $templateData = [
            'user_name' => $this->request->getPost('user_name') ?? 'Usuario de Prueba',
            'system_name' => 'Sistema GMV',
            'date' => date('Y-m-d H:i:s'),
            'message' => $this->request->getPost('template_message') ?? 'Este es un mensaje de prueba del sistema.',
            'company_name' => 'Empresa de Prueba'
        ];

        // Opciones del correo
        $options = [
            'subject' => $subject,
            'priority' => (int)($this->request->getPost('priority') ?? 3)
        ];

        // Enviar correo con plantilla
        $result = sendEmailWithTemplate($toEmail, $template, $templateData, $options);
        
        // Log del resultado
        if ($result['success']) {
            log_message('info', "Correo con plantilla '{$template}' enviado exitosamente a: {$toEmail}");
        } else {
            log_message('error', "Error enviando correo con plantilla '{$template}' a {$toEmail}: " . ($result['error'] ?? 'Error desconocido'));
        }

        return $this->response->setJSON($result);
    }

    /**
     * Envía una notificación del sistema
     */
    public function sendSystemNotification()
    {
        // Validar datos de entrada
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'subject' => 'required|min_length[3]|max_length[200]',
            'message' => 'required|min_length[10]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Datos de entrada inválidos',
                'validation_errors' => $validation->getErrors()
            ]);
        }

        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');

        // Enviar notificación del sistema
        $result = sendSystemNotification($subject, $message);
        
        // Log del resultado
        if ($result['success']) {
            log_message('info', "Notificación del sistema enviada: {$subject}");
        } else {
            log_message('error', "Error enviando notificación del sistema: " . ($result['error'] ?? 'Error desconocido'));
        }

        return $this->response->setJSON($result);
    }

    /**
     * Obtiene información detallada del sistema de correo
     */
    public function getEmailInfo()
    {
        $config = config('Email');
        
        $info = [
            'status' => getEmailStatus(),
            'configuration' => [
                'protocol' => $config->protocol,
                'smtp_host' => $config->SMTPHost ?? 'N/A',
                'smtp_port' => $config->SMTPPort ?? 'N/A',
                'smtp_crypto' => $config->SMTPCrypto ?? 'N/A',
                'mail_type' => $config->mailType ?? 'text',
                'charset' => $config->charset ?? 'UTF-8',
                'from_email' => $config->fromEmail ?? 'N/A',
                'from_name' => $config->fromName ?? 'N/A',
                'admin_email' => $config->adminEmail ?? 'N/A',
                'max_retries' => $config->maxRetries ?? 3,
                'retry_delay' => $config->retryDelay ?? 5
            ],
            'templates' => getEmailTemplates(),
            'php_extensions' => [
                'openssl' => extension_loaded('openssl'),
                'curl' => extension_loaded('curl'),
                'mbstring' => extension_loaded('mbstring')
            ]
        ];

        return $this->response->setJSON([
            'success' => true,
            'data' => $info
        ]);
    }

    /**
     * Valida una dirección de correo electrónico
     */
    public function validateEmail()
    {
        $email = $this->request->getPost('email');
        
        if (empty($email)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Dirección de correo requerida'
            ]);
        }

        $isValid = validateEmailAddress($email);
        
        return $this->response->setJSON([
            'success' => true,
            'valid' => $isValid,
            'email' => $email
        ]);
    }

    /**
     * Obtiene logs relacionados con correo electrónico
     */
    public function getEmailLogs()
    {
        try {
            $logFile = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.php';
            
            if (!file_exists($logFile)) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'No hay logs disponibles para hoy'
                ]);
            }

            $content = file_get_contents($logFile);
            
            // Filtrar solo líneas relacionadas con email
            $lines = explode("\n", $content);
            $emailLines = array_filter($lines, function($line) {
                return stripos($line, 'email') !== false || 
                       stripos($line, 'correo') !== false ||
                       stripos($line, 'smtp') !== false;
            });

            return $this->response->setJSON([
                'success' => true,
                'logs' => array_values($emailLines),
                'total_lines' => count($emailLines)
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error leyendo logs: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Limpia los logs de correo electrónico
     */
    public function clearEmailLogs()
    {
        try {
            $logFile = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.php';
            
            if (file_exists($logFile)) {
                // No eliminar completamente, solo agregar una marca de limpieza
                $timestamp = date('Y-m-d H:i:s');
                $clearMark = "\n<!-- LOGS CLEARED AT: {$timestamp} -->\n";
                file_put_contents($logFile, $clearMark, FILE_APPEND);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Logs marcados como limpiados'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error limpiando logs: ' . $e->getMessage()
            ]);
        }
    }
}
