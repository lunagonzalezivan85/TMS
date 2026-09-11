/**
 * Portal.js
 * Lógica del Portal de Conductores (Modernizado, sin jQuery)
 */
const Portal = {
    state: {
        step: 1,
        totalSteps: 5,
        baseUrl: document.querySelector('meta[name="base-url"]')?.content || '/'
    },

    init() {
        this.bindEvents();
        this.restoreOldState();
        this.updateWizardUI();
    },

    bindEvents() {
        // Navegación del Wizard
        document.querySelectorAll('[data-goto]').forEach(btn => {
            btn.addEventListener('click', () => {
                const step = parseInt(btn.dataset.goto);
                if (step > this.state.step) {
                    if (this.validateStep(this.state.step)) {
                        this.irPaso(step);
                    }
                } else {
                    this.irPaso(step);
                }
            });
        });

        // Búsqueda de Vehículo
        const btnBuscar = document.getElementById('btn-buscar');
        const inputBuscar = document.getElementById('buscar-input');

        if (btnBuscar) btnBuscar.addEventListener('click', () => this.buscarVehiculo());
        if (inputBuscar) {
            inputBuscar.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.buscarVehiculo();
                }
            });
        }

        // Selección de Tipo de Problema
        document.querySelectorAll('.btn-tipo-problema').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = btn.dataset.id;
                this.seleccionarTipo(btn, id);
            });
        });

        // Manejo de archivos
        const fotoInput = document.getElementById('foto');
        if (fotoInput) {
            fotoInput.addEventListener('change', (e) => this.mostrarArchivo(e.target));
        }

        const btnQuitarFoto = document.getElementById('btn-quitar-foto');
        if (btnQuitarFoto) {
            btnQuitarFoto.addEventListener('click', () => this.quitarArchivo());
        }

        // Envío final
        const btnEnviar = document.getElementById('btn-enviar');
        if (btnEnviar) {
            btnEnviar.addEventListener('click', () => this.enviarFormulario());
        }
    },

    irPaso(n) {
        const wizard = document.getElementById('main-wizard');
        if (!wizard) return;

        // Animación de salida
        wizard.classList.remove('animate__fadeInRight', 'animate__fadeInLeft');
        wizard.classList.add('animate__fadeOut');

        setTimeout(() => {
            const currentDir = n > this.state.step ? 'animate__fadeInRight' : 'animate__fadeInLeft';
            this.state.step = n;

            // Ocultar todos los pasos
            document.querySelectorAll('.wizard-step').forEach(s => s.classList.add('d-none'));
            
            // Mostrar paso actual
            const targetStep = document.getElementById('step' + n);
            if (targetStep) targetStep.classList.remove('d-none');

            wizard.classList.remove('animate__fadeOut');
            wizard.classList.add(currentDir);
            
            this.updateWizardUI();
        }, 300);
    },

    updateWizardUI() {
        // Actualizar indicadores (bolitas)
        document.querySelectorAll('.step-indicator').forEach(ind => {
            const stepNum = parseInt(ind.dataset.step);
            ind.classList.toggle('active', stepNum === this.state.step);
            ind.classList.toggle('completed', stepNum < this.state.step);
        });
    },

    validateStep(paso) {
        if (paso === 1) {
            const value = document.getElementById('carnet_conductor').value.trim();
            if (value.length < 5) {
                document.getElementById('error-carnet').classList.remove('d-none');
                return false;
            }
            document.getElementById('error-carnet').classList.add('d-none');
        }
        if (paso === 2) {
            if (!document.getElementById('id_vehiculo').value) {
                document.getElementById('error-vehiculo').classList.remove('d-none');
                return false;
            }
            document.getElementById('error-vehiculo').classList.add('d-none');
        }
        if (paso === 3) {
            if (!document.getElementById('id_tipo_problema').value) {
                document.getElementById('error-tipo').classList.remove('d-none');
                return false;
            }
            document.getElementById('error-tipo').classList.add('d-none');
        }
        if (paso === 4) {
            const desc = document.getElementById('descripcion').value.trim();
            if (desc.length < 10) {
                document.getElementById('error-descripcion').classList.remove('d-none');
                return false;
            }
            document.getElementById('error-descripcion').classList.add('d-none');
        }
        return true;
    },

    async buscarVehiculo() {
        const q = document.getElementById('buscar-input').value.trim();
        if (q.length < 2) return;

        const btn = document.getElementById('btn-buscar');
        const skeleton = document.getElementById('buscar-skeleton');
        const resultBox = document.getElementById('buscar-resultado');
        const errorBox = document.getElementById('buscar-error');
        
        UI.toggleButtonLoading(btn, true, '<i class="fas fa-search"></i>');
        
        resultBox.classList.add('d-none');
        errorBox.classList.add('d-none');
        skeleton.classList.remove('d-none');

        try {
            const data = await HttpClient.get(`${this.state.baseUrl}portal/solicitud/buscar-vehiculo?q=${encodeURIComponent(q)}`);
            
            skeleton.classList.add('d-none');

            if (data.ok) {
                document.getElementById('res-placa').textContent = data.placa;
                const detalle = [data.marca, data.modelo, data.anio].filter(Boolean).join(' ');
                document.getElementById('res-detalle').textContent = detalle + (data.codigo ? ' · Cód: ' + data.codigo : '');
                
                resultBox.classList.remove('d-none');
                
                document.getElementById('id_vehiculo').value = data.id;
                document.getElementById('resumen-placa').textContent = data.placa;
                document.getElementById('resumen-detalle').textContent = detalle;

                document.getElementById('btn-paso3').disabled = false;
                document.getElementById('error-vehiculo').classList.add('d-none');
            } else {
                document.getElementById('buscar-error-msg').textContent = data.mensaje;
                errorBox.classList.remove('d-none');
                document.getElementById('id_vehiculo').value = '';
                document.getElementById('btn-paso3').disabled = true;
            }
        } catch (error) {
            skeleton.classList.add('d-none');
            UI.showToast('error', 'Error en la búsqueda');
        } finally {
            UI.toggleButtonLoading(btn, false, '<i class="fas fa-search"></i>');
        }
    },

    seleccionarTipo(el, id) {
        document.querySelectorAll('.btn-tipo-problema').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('id_tipo_problema').value = id;
        document.getElementById('error-tipo').classList.add('d-none');
    },

    mostrarArchivo(input) {
        if (input.files && input.files[0]) {
            document.getElementById('archivo-nombre').textContent = input.files[0].name;
            document.getElementById('archivo-preview').classList.remove('d-none');
            const dropzone = document.getElementById('drop-zone');
            if (dropzone) dropzone.classList.add('d-none');
        }
    },

    quitarArchivo() {
        const input = document.getElementById('foto');
        if (input) input.value = '';
        document.getElementById('archivo-preview').classList.add('d-none');
        const dropzone = document.getElementById('drop-zone');
        if (dropzone) dropzone.classList.remove('d-none');
    },

    enviarFormulario() {
        const form = document.getElementById('wizard-form');
        if (form) form.submit();
    },

    restoreOldState() {
        const oldTipo = document.getElementById('id_tipo_problema')?.value;
        if (oldTipo) {
            const btn = document.querySelector(`.btn-tipo-problema[data-id="${oldTipo}"]`);
            if (btn) btn.classList.add('active');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => Portal.init());
