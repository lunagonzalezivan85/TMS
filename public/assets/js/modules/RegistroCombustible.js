/**
 * RegistroCombustibleIndex — módulo JS para la vista index.
 * Maneja: filtros colapsables, confirmación de eliminación,
 *         reenvío al SAG y carga de estadísticas.
 */
const RegistroCombustibleIndex = {
    baseUrl: '',

    init(baseUrl) {
        this.baseUrl = baseUrl;
        this._bindFiltros();
        this._bindAcciones();
        this.cargarEstadisticas();
    },

    // ── Filtros ──────────────────────────────────────────────────
    _bindFiltros() {
        const header = document.getElementById('filtrosHeader');
        if (header) {
            header.addEventListener('click', () => this._toggleFiltros());
        }

        // Recargar estadísticas al cambiar filtros
        const form = document.querySelector('form[method="GET"]');
        if (form) {
            form.addEventListener('submit', (e) => {
                // Dejar que el formulario se envíe normalmente para recargar la página
                // Las estadísticas se cargarán con los nuevos filtros al recargar
            });
        }
    },

    _toggleFiltros() {
        const body = document.getElementById('filtrosBody');
        const icon = document.getElementById('filtrosIcon');
        if (!body) return;
        
        // Verificar el estado actual del display
        const computedStyle = window.getComputedStyle(body);
        const visible = computedStyle.display !== 'none';
        
        body.style.display = visible ? 'none' : '';
        if (icon) icon.style.transform = visible ? 'rotate(-90deg)' : '';
    },

    // ── Acciones (event delegation) ───────────────────────────────
    _bindAcciones() {
        document.addEventListener('click', (e) => {
            const btnSAG = e.target.closest('[data-action="reenviar-sag"]');
            if (btnSAG) {
                e.preventDefault();
                this._reenviarSAG(btnSAG);
                return;
            }

            const btnDel = e.target.closest('[data-action="eliminar"]');
            if (btnDel) {
                e.preventDefault();
                this._confirmarEliminacion(btnDel.dataset.id);
            }
        });
    },

    // ── Reenvío SAG ───────────────────────────────────────────────
    async _reenviarSAG(btn) {
        const id = btn.dataset.id;
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const res = await HttpClient.post(
                this.baseUrl + 'registro-combustible/reenviar-sag/' + id,
                {}
            );

            if (res.success) {
                btn.outerHTML = '<span class="sag-ok"><i class="fas fa-check-circle me-1"></i>SAG</span>';
                if (typeof UI !== 'undefined') UI.showToast('success', res.message ?? 'Éxito: se envió el registro al SAG correctamente');
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Error';
                btn.classList.replace('btn-warning', 'btn-danger');
                btn.title = res.message ?? 'Error al enviar';
                if (typeof UI !== 'undefined') UI.showToast('error', res.message ?? 'Error al enviar el registro al SAG');
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = original;
            if (typeof UI !== 'undefined') UI.showToast('error', 'Error de conexión');
        }
    },

    // ── Confirmación eliminación ──────────────────────────────────
    _confirmarEliminacion(id) {
        const form = document.getElementById('deleteForm');
        if (!form) return;
        form.action = this.baseUrl + 'registro-combustible/delete/' + id;
        const modal = document.getElementById('deleteModal');
        if (modal) new bootstrap.Modal(modal).show();
    },

    // ── Estadísticas (cards) ──────────────────────────────────────
    async cargarEstadisticas() {
        try {
            // Obtener filtros de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const queryParams = new URLSearchParams();
            
            if (urlParams.get('vehiculo')) queryParams.set('vehiculo', urlParams.get('vehiculo'));
            if (urlParams.get('fecha_desde')) queryParams.set('fecha_desde', urlParams.get('fecha_desde'));
            if (urlParams.get('fecha_hasta')) queryParams.set('fecha_hasta', urlParams.get('fecha_hasta'));
            if (urlParams.get('usuario')) queryParams.set('usuario', urlParams.get('usuario'));
            
            const queryString = queryParams.toString();
            const url = this.baseUrl + 'registro-combustible/getEstadisticas' + (queryString ? '?' + queryString : '');
            
            console.log('Cargando estadísticas desde:', url);
            const data = await HttpClient.get(url);
            console.log('Datos recibidos:', data);
            
            const set = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val ?? '—';
            };
            set('stat_total',         data.total);
            const litros = data.total_litros || 0;
            const galones = litros / 3.78541;
            set('stat_litros',        galones.toLocaleString('es-NI', {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' gal');
            set('stat_litros_small', litros.toLocaleString() + ' L');
            set('stat_vehiculos',     data.total_vehiculos);
            set('stat_sag_pendiente', data.pendientes_sag);
        } catch (err) {
            console.error('Error cargando estadísticas:', err);
        }
    },

    // ── Utilidades ────────────────────────────────────────────────
    _debounce(fn, ms) {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
    },
};

window.RegistroCombustibleIndex = RegistroCombustibleIndex;
