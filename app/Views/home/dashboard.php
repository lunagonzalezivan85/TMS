<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="hero-title"><?= $title ?></h1>
                <p class="hero-subtitle"><?= $subtitle ?></p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?= base_url('login') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Iniciar Sesión
                    </a>
                    <a href="<?= base_url('register') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>
                        Registrarse
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">¿Qué puedes hacer con GMV Sistema?</h2>
                <p class="lead text-muted">Descubre todas las funcionalidades que tenemos para ti</p>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Gestión de Vehículos -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Gestión de Vehículos</h4>
                    <p class="text-muted mb-4">Registra y administra toda tu flota vehicular con información detallada de cada unidad.</p>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-check text-success me-2"></i>Registro completo de vehículos</li>
                        <li><i class="fas fa-check text-success me-2"></i>Historial de cada unidad</li>
                        <li><i class="fas fa-check text-success me-2"></i>Documentación digital</li>
                    </ul>
                </div>
            </div>
            
            <!-- Mantenimiento Preventivo -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Mantenimiento Preventivo</h4>
                    <p class="text-muted mb-4">Programa y gestiona el mantenimiento preventivo para evitar averías costosas.</p>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-check text-success me-2"></i>Calendario de mantenimientos</li>
                        <li><i class="fas fa-check text-success me-2"></i>Alertas automáticas</li>
                        <li><i class="fas fa-check text-success me-2"></i>Seguimiento de servicios</li>
                    </ul>
                </div>
            </div>
            
            <!-- Mantenimiento Correctivo -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Mantenimiento Correctivo</h4>
                    <p class="text-muted mb-4">Registra y da seguimiento a las reparaciones y mantenimientos correctivos.</p>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-check text-success me-2"></i>Registro de averías</li>
                        <li><i class="fas fa-check text-success me-2"></i>Órdenes de trabajo</li>
                        <li><i class="fas fa-check text-success me-2"></i>Control de costos</li>
                    </ul>
                </div>
            </div>
            
            <!-- Control de Combustible -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-gas-pump"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Control de Combustible</h4>
                    <p class="text-muted mb-4">Monitorea el consumo de combustible y optimiza los costos operativos.</p>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-check text-success me-2"></i>Registro de cargas</li>
                        <li><i class="fas fa-check text-success me-2"></i>Análisis de consumo</li>
                        <li><i class="fas fa-check text-success me-2"></i>Reportes detallados</li>
                    </ul>
                </div>
            </div>
            
            <!-- Gestión de Conductores -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Gestión de Conductores</h4>
                    <p class="text-muted mb-4">Administra la información de conductores y asigna vehículos de manera eficiente.</p>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-check text-success me-2"></i>Perfiles de conductores</li>
                        <li><i class="fas fa-check text-success me-2"></i>Asignación de vehículos</li>
                        <li><i class="fas fa-check text-success me-2"></i>Licencias y documentos</li>
                    </ul>
                </div>
            </div>
            
            <!-- Reportes y Análisis -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Reportes y Análisis</h4>
                    <p class="text-muted mb-4">Genera reportes detallados y obtén insights valiosos sobre tu flota.</p>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-check text-success me-2"></i>Reportes personalizados</li>
                        <li><i class="fas fa-check text-success me-2"></i>Gráficos interactivos</li>
                        <li><i class="fas fa-check text-success me-2"></i>Exportación de datos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">100%</div>
                    <div>Control Total</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">24/7</div>
                    <div>Disponibilidad</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">∞</div>
                    <div>Vehículos</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">+50%</div>
                    <div>Ahorro en Costos</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-6 fw-bold mb-3">¿Listo para comenzar?</h2>
                <p class="lead text-muted mb-4">
                    Únete a miles de conductores que ya confían en GMV Sistema para gestionar sus vehículos de manera profesional.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?= base_url('register') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket me-2"></i>
                        Comenzar Ahora
                    </a>
                    <a href="#features" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-info-circle me-2"></i>
                        Más Información
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Counter animation for stats
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            if (target === Infinity) {
                element.textContent = '∞';
            } else if (typeof target === 'string') {
                element.textContent = target;
            } else {
                element.textContent = Math.floor(current) + (target.toString().includes('%') ? '%' : '');
            }
        }, 20);
    }
    
    // Animate stats when they come into view
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const numberElement = entry.target.querySelector('.stats-number');
                const text = numberElement.textContent;
                
                if (text === '100%') {
                    animateCounter(numberElement, 100);
                } else if (text === '24/7') {
                    numberElement.textContent = '24/7';
                } else if (text === '∞') {
                    numberElement.textContent = '∞';
                } else if (text === '+50%') {
                    animateCounter(numberElement, 50);
                }
                
                statsObserver.unobserve(entry.target);
            }
        });
    });
    
    document.querySelectorAll('.stats-card').forEach(card => {
        statsObserver.observe(card);
    });
</script>
<?= $this->endSection() ?>
