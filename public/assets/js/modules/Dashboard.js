/**
 * Dashboard.js
 * Lógica del panel principal (Modernizado, sin jQuery)
 */
const Dashboard = {
    config: {
        baseUrl: document.querySelector('meta[name="base-url"]')?.content || '/',
        statsUpdateInterval: 1000
    },

    init() {
        console.log('Dashboard Module Initialized');
        try {
            this.initializeCollapseHandlers();
            this.initializeCharts();
            this.initializeWidgetConfig();
            this.initializeFloatingButton();
            this.initClock();
            this.bindEvents();
            
            // Tooltips Globales
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(el => new bootstrap.Tooltip(el));
        } catch (e) {
            console.error('Error initializing Dashboard:', e);
        }
    },

    bindEvents() {
        const btnRefresh = document.getElementById('btnRefreshStats');
        if (btnRefresh) {
            btnRefresh.addEventListener('click', () => this.refreshStats());
        }
    },

    initializeCollapseHandlers() {
        const collapseElements = [
            { target: '#statsSection', icon: '#statsIcon' },
            { target: '#fuelSection', icon: '#fuelIcon' },
            { target: '#chartsSection', icon: '#chartsIcon' },
            { target: '#activitiesSection', icon: '#activitiesIcon' },
            { target: '#urgentSection', icon: '#urgentIcon' },
            { target: '#vehicleDocsSection', icon: '#vehicleDocsIcon' }
        ];

        collapseElements.forEach(element => {
            const sectionElement = document.querySelector(element.target);
            const iconElement = document.querySelector(element.icon);
            
            if (sectionElement && iconElement) {
                sectionElement.addEventListener('show.bs.collapse', () => iconElement.className = 'fas fa-chevron-up');
                sectionElement.addEventListener('hide.bs.collapse', () => iconElement.className = 'fas fa-chevron-down');
            }
        });
    },

    initializeCharts() {
        const maintenanceCanvas = document.getElementById('maintenanceChart');
        const vehicleStatusCanvas = document.getElementById('vehicleStatusChart');
        
        if (maintenanceCanvas) {
            const maintenanceCtx = maintenanceCanvas.getContext('2d');
            new Chart(maintenanceCtx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Preventivo',
                        data: [12, 19, 15, 25, 22, 30],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Correctivo',
                        data: [8, 12, 10, 15, 18, 12],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        if (vehicleStatusCanvas) {
            const vehicleStatusCtx = vehicleStatusCanvas.getContext('2d');
            new Chart(vehicleStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Operativo', 'Mantenimiento', 'Fuera de Servicio'],
                    datasets: [{
                        data: [18, 4, 2],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    },

    initializeWidgetConfig() {
        const saveBtn = document.getElementById('saveWidgetsBtn');
        const resetBtn = document.getElementById('resetWidgetsBtn');
        const form = document.getElementById('widgetConfigForm');

        if (saveBtn && form) {
            saveBtn.addEventListener('click', async () => {
                const formData = new FormData(form);
                const widgets = {};
                
                // Procesar checkboxes
                form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                    const match = cb.name.match(/widgets\[(.+)\]/);
                    if (match) {
                        widgets[match[1]] = cb.checked;
                    }
                });

                UI.toggleButtonLoading(saveBtn, true);

                try {
                    const response = await HttpClient.post(`${this.config.baseUrl}widget-config/save-config`, { widgets });
                    if (response.success) {
                        bootstrap.Modal.getInstance(document.getElementById('widgetConfigModal')).hide();
                        UI.showToast('success', response.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        UI.showToast('error', response.message);
                    }
                } catch (error) {
                    UI.showToast('error', 'Error al guardar la configuración');
                } finally {
                    UI.toggleButtonLoading(saveBtn, false);
                }
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', async () => {
                if (confirm('¿Estás seguro de que deseas restablecer la configuración?')) {
                    UI.toggleButtonLoading(resetBtn, true);
                    try {
                        const response = await HttpClient.post(`${this.config.baseUrl}widget-config/reset-config`);
                        if (response.success) {
                            UI.showToast('success', response.message);
                            setTimeout(() => location.reload(), 1000);
                        }
                    } catch (error) {
                        UI.showToast('error', 'Error al restablecer');
                    } finally {
                        UI.toggleButtonLoading(resetBtn, false);
                    }
                }
            });
        }
    },

    initClock() {
        const update = () => {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('es-ES', options);
            const timeStr = now.toLocaleTimeString('es-ES', { 
                hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false 
            });
            
            const clockEl = document.getElementById('real-time-clock');
            const dateEl = document.getElementById('real-time-date');
            
            if (clockEl) clockEl.textContent = timeStr;
            if (dateEl) dateEl.textContent = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);
        };

        update();
        setInterval(update, 1000);
    },

    refreshStats() {
        const btn = document.getElementById('btnRefreshStats');
        const icon = btn?.querySelector('i');
        const lastUpdate = document.getElementById('lastUpdateText');
        
        if (icon) icon.classList.add('fa-spin');
        if (btn) btn.disabled = true;
        
        // Simulación de búsqueda (en un futuro llamar a HttpClient.get)
        setTimeout(() => {
            if (icon) icon.classList.remove('fa-spin');
            if (btn) btn.disabled = false;
            
            const now = new Date();
            if (lastUpdate) lastUpdate.textContent = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            
            UI.showToast('success', 'Estadísticas actualizadas');
        }, 1500);
    },

    initializeFloatingButton() {
        const floatingBtn = document.getElementById('floatingConfigBtn');
        if (floatingBtn) {
            setTimeout(() => {
                floatingBtn.classList.add('animate__animated', 'animate__pulse');
                setTimeout(() => floatingBtn.classList.remove('animate__animated', 'animate__pulse'), 1000);
            }, 2000);

            floatingBtn.addEventListener('click', () => {
                floatingBtn.classList.add('animate__animated', 'animate__tada');
                setTimeout(() => floatingBtn.classList.remove('animate__animated', 'animate__tada'), 1000);
            });
        }
    }
};

document.addEventListener('DOMContentLoaded', () => Dashboard.init());
