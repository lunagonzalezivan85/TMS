<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-envelope-open-text text-primary me-2"></i>
                        Pruebas de Correo Electrónico
                    </h1>
                    <p class="text-muted mb-0">Sistema de pruebas y diagnóstico de correo electrónico</p>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-primary" onclick="refreshEmailStatus()">
                        <i class="fas fa-sync-alt me-1"></i>
                        Actualizar Estado
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado del Sistema -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Estado del Sistema de Correo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="emailStatusCards">
                        <!-- Se carga dinámicamente -->
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-success me-2" onclick="testConnection()">
                            <i class="fas fa-plug me-1"></i>
                            Probar Conexión
                        </button>
                        <button type="button" class="btn btn-outline-info me-2" onclick="showEmailInfo()">
                            <i class="fas fa-cog me-1"></i>
                            Ver Configuración
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="showEmailLogs()">
                            <i class="fas fa-file-alt me-1"></i>
                            Ver Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pruebas de Envío -->
    <div class="row">
        <!-- Correo Básico -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-paper-plane me-2"></i>
                        Envío de Correo Básico
                    </h5>
                </div>
                <div class="card-body">
                    <form id="basicEmailForm">
                        <div class="mb-3">
                            <label for="basicToEmail" class="form-label">Destinatario *</label>
                            <input type="email" class="form-control" id="basicToEmail" name="to_email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="basicCcEmail" class="form-label">CC (Opcional)</label>
                            <input type="email" class="form-control" id="basicCcEmail" name="cc_email">
                        </div>
                        
                        <div class="mb-3">
                            <label for="basicSubject" class="form-label">Asunto *</label>
                            <input type="text" class="form-control" id="basicSubject" name="subject" 
                                   value="Prueba de Correo - Sistema GMV" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="basicMessage" class="form-label">Mensaje *</label>
                            <textarea class="form-control" id="basicMessage" name="message" rows="4" required>Este es un correo de prueba enviado desde el Sistema GMV.

Fecha: <?= date('Y-m-d H:i:s') ?>
Usuario: <?= session()->get('user_name') ?? 'Sistema' ?>

Si recibe este correo, significa que el sistema de correo está funcionando correctamente.</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="basicPriority" class="form-label">Prioridad</label>
                            <select class="form-select" id="basicPriority" name="priority">
                                <option value="1">Alta (1)</option>
                                <option value="2">Media-Alta (2)</option>
                                <option value="3" selected>Normal (3)</option>
                                <option value="4">Media-Baja (4)</option>
                                <option value="5">Baja (5)</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-paper-plane me-1"></i>
                            Enviar Correo Básico
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Correo con Plantilla -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-code me-2"></i>
                        Envío con Plantilla
                    </h5>
                </div>
                <div class="card-body">
                    <form id="templateEmailForm">
                        <div class="mb-3">
                            <label for="templateToEmail" class="form-label">Destinatario *</label>
                            <input type="email" class="form-control" id="templateToEmail" name="to_email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="templateSelect" class="form-label">Plantilla *</label>
                            <select class="form-select" id="templateSelect" name="template" required>
                                <option value="">Seleccionar plantilla...</option>
                                <?php foreach ($templates as $key => $path): ?>
                                <option value="<?= $key ?>"><?= ucfirst(str_replace('_', ' ', $key)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="templateSubject" class="form-label">Asunto *</label>
                            <input type="text" class="form-control" id="templateSubject" name="subject" 
                                   value="Prueba de Plantilla - Sistema GMV" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="templateUserName" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="templateUserName" name="user_name" 
                                   value="<?= session()->get('user_name') ?? 'Usuario de Prueba' ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="templateMessage" class="form-label">Mensaje Personalizado</label>
                            <textarea class="form-control" id="templateMessage" name="template_message" rows="3">Este es un mensaje de prueba usando plantillas del Sistema GMV.</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-file-code me-1"></i>
                            Enviar con Plantilla
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificación del Sistema -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>
                        Notificación del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <form id="notificationForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="notificationSubject" class="form-label">Asunto *</label>
                                        <input type="text" class="form-control" id="notificationSubject" name="subject" 
                                               value="Prueba de Notificación del Sistema" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Destinatario</label>
                                        <input type="text" class="form-control" value="<?= $emailStatus['from_email'] ?? 'Administrador' ?>" readonly>
                                        <small class="text-muted">Se envía automáticamente al administrador del sistema</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notificationMessage" class="form-label">Mensaje *</label>
                                    <textarea class="form-control" id="notificationMessage" name="message" rows="3" required>Esta es una notificación de prueba del Sistema GMV.

Se ha ejecutado una prueba del sistema de correo electrónico el <?= date('Y-m-d H:i:s') ?>.

El sistema está funcionando correctamente.</textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-bell me-1"></i>
                                    Enviar Notificación
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-1"></i> Información</h6>
                                <p class="mb-0 small">
                                    Las notificaciones del sistema se envían automáticamente al correo del administrador 
                                    configurado en el sistema. Son útiles para alertas automáticas y reportes del sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->

<!-- Modal de Información del Sistema -->
<div class="modal fade" id="emailInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cog me-2"></i>
                    Configuración del Sistema de Correo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="emailInfoContent">
                <!-- Se carga dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de Logs -->
<div class="modal fade" id="emailLogsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt me-2"></i>
                    Logs de Correo Electrónico
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <small class="text-muted">Logs del día: <?= date('Y-m-d') ?></small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearEmailLogs()">
                        <i class="fas fa-trash me-1"></i>
                        Limpiar Logs
                    </button>
                </div>
                <div id="emailLogsContent" style="max-height: 400px; overflow-y: auto;">
                    <!-- Se carga dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar estado inicial
    refreshEmailStatus();
    
    // Configurar formularios
    setupForms();
});

function refreshEmailStatus() {
    fetch('<?= base_url('test-email/status') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayEmailStatus(data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error cargando estado del sistema', 'danger');
        });
}

function displayEmailStatus(status) {
    const container = document.getElementById('emailStatusCards');
    
    container.innerHTML = `
        <div class="col-md-3 mb-3">
            <div class="card border-0 ${status.enabled ? 'bg-success' : 'bg-danger'} text-white">
                <div class="card-body text-center">
                    <i class="fas fa-power-off fa-2x mb-2"></i>
                    <h6>Estado</h6>
                    <p class="mb-0">${status.enabled ? 'Habilitado' : 'Deshabilitado'}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 ${status.configured ? 'bg-success' : 'bg-warning'} text-white">
                <div class="card-body text-center">
                    <i class="fas fa-cogs fa-2x mb-2"></i>
                    <h6>Configuración</h6>
                    <p class="mb-0">${status.configured ? 'Correcta' : 'Incompleta'}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-server fa-2x mb-2"></i>
                    <h6>Protocolo</h6>
                    <p class="mb-0">${status.protocol.toUpperCase()}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 ${status.development_mode ? 'bg-warning' : 'bg-primary'} text-white">
                <div class="card-body text-center">
                    <i class="fas fa-${status.development_mode ? 'code' : 'rocket'} fa-2x mb-2"></i>
                    <h6>Modo</h6>
                    <p class="mb-0">${status.development_mode ? 'Desarrollo' : 'Producción'}</p>
                </div>
            </div>
        </div>
    `;
}

function testConnection() {
    showAlert('Probando conexión...', 'info');
    
    fetch('<?= base_url('test-email/connection') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message || 'Conexión exitosa', 'success');
            } else {
                showAlert(data.error || 'Error en la conexión', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error probando conexión', 'danger');
        });
}

function showEmailInfo() {
    fetch('<?= base_url('test-email/info') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayEmailInfo(data.data);
                new bootstrap.Modal(document.getElementById('emailInfoModal')).show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error cargando información', 'danger');
        });
}

function displayEmailInfo(info) {
    const content = document.getElementById('emailInfoContent');
    
    content.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Estado del Sistema</h6>
                <table class="table table-sm">
                    <tr><td>Habilitado:</td><td><span class="badge bg-${info.status.enabled ? 'success' : 'danger'}">${info.status.enabled ? 'Sí' : 'No'}</span></td></tr>
                    <tr><td>Configurado:</td><td><span class="badge bg-${info.status.configured ? 'success' : 'warning'}">${info.status.configured ? 'Sí' : 'No'}</span></td></tr>
                    <tr><td>Modo Desarrollo:</td><td><span class="badge bg-${info.status.development_mode ? 'warning' : 'primary'}">${info.status.development_mode ? 'Sí' : 'No'}</span></td></tr>
                </table>
                
                <h6>Configuración SMTP</h6>
                <table class="table table-sm">
                    <tr><td>Protocolo:</td><td>${info.configuration.protocol}</td></tr>
                    <tr><td>Servidor:</td><td>${info.configuration.smtp_host}</td></tr>
                    <tr><td>Puerto:</td><td>${info.configuration.smtp_port}</td></tr>
                    <tr><td>Encriptación:</td><td>${info.configuration.smtp_crypto}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Configuración de Correo</h6>
                <table class="table table-sm">
                    <tr><td>Email Remitente:</td><td>${info.configuration.from_email}</td></tr>
                    <tr><td>Nombre Remitente:</td><td>${info.configuration.from_name}</td></tr>
                    <tr><td>Email Admin:</td><td>${info.configuration.admin_email}</td></tr>
                    <tr><td>Tipo de Correo:</td><td>${info.configuration.mail_type}</td></tr>
                    <tr><td>Charset:</td><td>${info.configuration.charset}</td></tr>
                </table>
                
                <h6>Extensiones PHP</h6>
                <table class="table table-sm">
                    <tr><td>OpenSSL:</td><td><span class="badge bg-${info.php_extensions.openssl ? 'success' : 'danger'}">${info.php_extensions.openssl ? 'Sí' : 'No'}</span></td></tr>
                    <tr><td>cURL:</td><td><span class="badge bg-${info.php_extensions.curl ? 'success' : 'danger'}">${info.php_extensions.curl ? 'Sí' : 'No'}</span></td></tr>
                    <tr><td>mbstring:</td><td><span class="badge bg-${info.php_extensions.mbstring ? 'success' : 'danger'}">${info.php_extensions.mbstring ? 'Sí' : 'No'}</span></td></tr>
                </table>
            </div>
        </div>
        
        <h6>Plantillas Disponibles</h6>
        <div class="row">
            ${Object.entries(info.templates).map(([key, path]) => 
                `<div class="col-md-4 mb-2">
                    <span class="badge bg-secondary">${key}</span>
                    <small class="text-muted d-block">${path}</small>
                </div>`
            ).join('')}
        </div>
    `;
}

function showEmailLogs() {
    fetch('<?= base_url('test-email/logs') ?>')
        .then(response => response.json())
        .then(data => {
            displayEmailLogs(data);
            new bootstrap.Modal(document.getElementById('emailLogsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error cargando logs', 'danger');
        });
}

function displayEmailLogs(data) {
    const content = document.getElementById('emailLogsContent');
    
    if (data.success && data.logs && data.logs.length > 0) {
        content.innerHTML = `
            <div class="alert alert-info">
                <small>Total de líneas encontradas: ${data.total_lines}</small>
            </div>
            <pre class="bg-light p-3 rounded">${data.logs.join('\n')}</pre>
        `;
    } else {
        content.innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-1"></i>
                ${data.error || 'No hay logs de correo disponibles para hoy'}
            </div>
        `;
    }
}

function clearEmailLogs() {
    if (confirm('¿Está seguro de que desea limpiar los logs de correo?')) {
        fetch('<?= base_url('test-email/logs/clear') ?>', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Logs limpiados correctamente', 'success');
                showEmailLogs(); // Recargar logs
            } else {
                showAlert(data.error || 'Error limpiando logs', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error limpiando logs', 'danger');
        });
    }
}

function setupForms() {
    // Formulario de correo básico
    document.getElementById('basicEmailForm').addEventListener('submit', function(e) {
        e.preventDefault();
        sendBasicEmail(new FormData(this));
    });
    
    // Formulario de correo con plantilla
    document.getElementById('templateEmailForm').addEventListener('submit', function(e) {
        e.preventDefault();
        sendTemplateEmail(new FormData(this));
    });
    
    // Formulario de notificación
    document.getElementById('notificationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        sendNotification(new FormData(this));
    });
}

function sendBasicEmail(formData) {
    const button = document.querySelector('#basicEmailForm button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
    
    fetch('<?= base_url('test-email/send-test') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Correo enviado exitosamente', 'success');
        } else {
            showAlert(data.error || 'Error enviando correo', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error enviando correo', 'danger');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function sendTemplateEmail(formData) {
    const button = document.querySelector('#templateEmailForm button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
    
    fetch('<?= base_url('test-email/send-template') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Correo con plantilla enviado exitosamente', 'success');
        } else {
            showAlert(data.error || 'Error enviando correo con plantilla', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error enviando correo con plantilla', 'danger');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function sendNotification(formData) {
    const button = document.querySelector('#notificationForm button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
    
    fetch('<?= base_url('test-email/send-notification') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Notificación enviada exitosamente', 'success');
        } else {
            showAlert(data.error || 'Error enviando notificación', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error enviando notificación', 'danger');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function showAlert(message, type) {
    // Crear alerta temporal
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-hide después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

<?= $this->endSection() ?>
