<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Prueba de Conectividad - Asignación de Vehículos
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools"></i>
                        Prueba de Conectividad - Asignación de Vehículos
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Estado de la Base de Datos</h5>
                            <div id="db-status" class="alert alert-info">
                                <i class="fas fa-spinner fa-spin"></i> Verificando conexión...
                            </div>
                            
                            <h5>Conteo de Registros</h5>
                            <div id="records-count">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body">
                                                <h6>Asignaciones</h6>
                                                <h3 id="count-asignaciones">-</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                                <h6>Vehículos</h6>
                                                <h3 id="count-vehiculos">-</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body">
                                                <h6>Conductores</h6>
                                                <h3 id="count-conductores">-</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card bg-info text-white">
                                            <div class="card-body">
                                                <h6>Tipos de Unidad</h6>
                                                <h3 id="count-tipos">-</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Prueba de DataTables AJAX</h5>
                            <button id="test-ajax" class="btn btn-primary">
                                <i class="fas fa-play"></i> Probar getData()
                            </button>
                            <div id="ajax-result" class="mt-3"></div>
                            
                            <h5 class="mt-4">Acciones</h5>
                            <div class="btn-group-vertical d-grid gap-2">
                                <a href="<?= base_url('asignacion-vehiculos') ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> Ver Lista Principal
                                </a>
                                <a href="<?= base_url('asignacion-vehiculos/wizard') ?>" class="btn btn-outline-success">
                                    <i class="fas fa-magic"></i> Probar Wizard
                                </a>
                                <button id="refresh-test" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync"></i> Actualizar Pruebas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para probar conexión
    function testConnection() {
        fetch('<?= base_url('asignacion-vehiculos/testConnection') ?>')
            .then(response => response.json())
            .then(data => {
                const statusDiv = document.getElementById('db-status');
                
                if (data.success) {
                    statusDiv.className = 'alert alert-success';
                    statusDiv.innerHTML = '<i class="fas fa-check"></i> ' + data.message;
                    
                    // Actualizar contadores
                    document.getElementById('count-asignaciones').textContent = data.data.asignaciones;
                    document.getElementById('count-vehiculos').textContent = data.data.vehiculos;
                    document.getElementById('count-conductores').textContent = data.data.conductores;
                    document.getElementById('count-tipos').textContent = data.data.tipos_unidad;
                } else {
                    statusDiv.className = 'alert alert-danger';
                    statusDiv.innerHTML = '<i class="fas fa-times"></i> ' + data.message + '<br><small>' + data.error + '</small>';
                }
            })
            .catch(error => {
                const statusDiv = document.getElementById('db-status');
                statusDiv.className = 'alert alert-danger';
                statusDiv.innerHTML = '<i class="fas fa-times"></i> Error de conexión: ' + error.message;
            });
    }
    
    // Función para probar AJAX de DataTables
    function testAjax() {
        const resultDiv = document.getElementById('ajax-result');
        resultDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Probando AJAX...</div>';
        
        fetch('<?= base_url('asignacion-vehiculos/getData') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.data && Array.isArray(data.data)) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check"></i> AJAX exitoso<br>
                            <small>Registros obtenidos: ${data.data.length}</small>
                        </div>
                    `;
                } else if (data.error) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-times"></i> Error AJAX<br>
                            <small>${data.error}</small>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Respuesta inesperada<br>
                            <small>${JSON.stringify(data)}</small>
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times"></i> Error de red: ${error.message}
                    </div>
                `;
            });
    }
    
    // Event listeners
    document.getElementById('test-ajax').addEventListener('click', testAjax);
    document.getElementById('refresh-test').addEventListener('click', testConnection);
    
    // Ejecutar prueba inicial
    testConnection();
});
</script>
<?= $this->endSection() ?>
