/**
 * Vehiculos.js — Módulo de gestión de flota
 * Usa HttpClient (core) y UI (core). No depende de jQuery.
 */

/* ─────────────────────────────────────────────
 * VehiculosShow — lógica de vehiculos/show/:id
 * ───────────────────────────────────────────── */
const VehiculosShow = {
    vehiculoId: null,
    baseUrl: '',

    init(vehiculoId, baseUrl) {
        this.vehiculoId = vehiculoId;
        this.baseUrl    = baseUrl;

        this._bindCambiarEstado();
        this._bindSolicitudMantenimiento();
        this._cargarEstadisticas();
    },

    // ── Cambiar estado ──────────────────────────
    _bindCambiarEstado() {
        document.querySelectorAll('.cambiar-estado').forEach(btn => {
            btn.addEventListener('click', () => {
                const nuevoEstado = btn.dataset.estado;

                const mensajes = {
                    'ACTIVO':        'El vehículo será marcado como ACTIVO y estará disponible para asignaciones.',
                    'INACTIVO':      'El vehículo será marcado como INACTIVO y no estará disponible para asignaciones.',
                    'EN REPARACION': 'El vehículo será marcado como EN REPARACIÓN hasta completar el mantenimiento.',
                };

                document.getElementById('nuevo_estado').value          = nuevoEstado;
                document.getElementById('mensaje_cambio_estado').textContent = mensajes[nuevoEstado] ?? '';

                new bootstrap.Modal(document.getElementById('modalCambiarEstado')).show();
            });
        });

        document.getElementById('formCambiarEstado')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn  = e.target.querySelector('[type="submit"]');
            const formData = new FormData(e.target);

            UI.toggleButtonLoading(btn, true);
            try {
                const data = await HttpClient.post(
                    this.baseUrl + 'vehiculos/cambiarEstado',
                    formData
                );

                bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado'))?.hide();
                UI.showToast('success', data.message ?? 'Estado actualizado correctamente');
                setTimeout(() => location.reload(), 1800);
            } catch (err) {
                UI.showToast('error', err.message ?? 'Error al cambiar el estado');
                UI.toggleButtonLoading(btn, false);
            }
        });
    },

    // ── Solicitud de mantenimiento ──────────────
    _bindSolicitudMantenimiento() {
        document.getElementById('btnSolicitudMantenimiento')?.addEventListener('click', () => {
            new bootstrap.Modal(document.getElementById('modalSolicitudMantenimiento')).show();
        });

        document.getElementById('formSolicitudMantenimiento')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn      = e.target.querySelector('[type="submit"]');
            const formData = new FormData(e.target);

            UI.toggleButtonLoading(btn, true);
            try {
                const data = await HttpClient.post(
                    this.baseUrl + 'vehiculos/crearSolicitudMantenimiento',
                    formData
                );

                bootstrap.Modal.getInstance(document.getElementById('modalSolicitudMantenimiento'))?.hide();
                UI.showToast('success', data.message ?? 'Solicitud creada exitosamente');
                setTimeout(() => location.reload(), 1800);
            } catch (err) {
                UI.showToast('error', err.message ?? 'Error al crear la solicitud');
                UI.toggleButtonLoading(btn, false);
            }
        });
    },

    // ── Estadísticas ────────────────────────────
    async _cargarEstadisticas() {
        try {
            const data = await HttpClient.get(
                this.baseUrl + 'vehiculos/getEstadisticasVehiculo/' + this.vehiculoId
            );

            const elTotal       = document.getElementById('total_solicitudes');
            const elCompletados = document.getElementById('mantenimientos_completados');

            if (elTotal)       elTotal.textContent       = data.total_solicitudes        ?? 0;
            if (elCompletados) elCompletados.textContent = data.mantenimientos_completados ?? 0;
        } catch {
            // Estadísticas opcionales — fallo silencioso
        }
    },
};

/* ─────────────────────────────────────────────
 * VehiculosIndex — lógica de vehiculos/index
 * ───────────────────────────────────────────── */
const VehiculosIndex = {
    baseUrl: '',

    // ── Bootstrap ───────────────────────────────
    init(baseUrl, centrosCosto = {}) {
        this.baseUrl = baseUrl;
        this.centrosCosto = centrosCosto;
        this._bindFiltros();
        this._bindCambiarEstado();
        this._bindImportar();
        this.cargarVehiculos();
        this.cargarEstadisticas();
    },

    // ── Filtros ──────────────────────────────────
    _bindFiltros() {
        document.getElementById('btn_filtrar')
            ?.addEventListener('click', () => this.cargarVehiculos());

        document.getElementById('filtro_estado')
            ?.addEventListener('change', () => this.cargarVehiculos());

        document.getElementById('filtro_buscar')
            ?.addEventListener('input', this._debounce(() => this.cargarVehiculos(), 450));
    },

    // ── Cargar vehículos ─────────────────────────
    async cargarVehiculos() {
        const tbody = document.getElementById('vehiculosBody');
        if (!tbody) return;

        this._renderSkeleton(tbody);

        const filtros = {
            draw:            1,
            start:           0,
            length:          100,
            'search[value]': document.getElementById('filtro_buscar')?.value ?? '',
            estado:          document.getElementById('filtro_estado')?.value  ?? '',
        };

        try {
            const data = await HttpClient.post(this.baseUrl + 'vehiculos/getData', filtros);

            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-truck fa-3x text-muted mb-3 d-block opacity-25"></i>
                            <p class="text-muted mb-0">No se encontraron vehículos</p>
                            <small class="text-muted">Prueba con otros filtros</small>
                        </td>
                    </tr>`;
                this._setInfo(0, data.recordsTotal ?? 0);
                return;
            }

            tbody.innerHTML = data.data.map((v, i) => this._renderFila(v, i)).join('');
            this._setInfo(data.data.length, data.recordsTotal ?? data.data.length);

        } catch (err) {
            tbody.innerHTML = `
                <tr><td colspan="6" class="text-center py-4 text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>${err.message ?? 'Error al cargar vehículos'}
                </td></tr>`;
        }
    },

    // ── Render de fila ───────────────────────────
    _renderFila(v) {
        const estadoClases = {
            'ACTIVO':        'bg-success',
            'INACTIVO':      'bg-secondary',
            'EN REPARACION': 'bg-warning text-dark',
        };
        const estadoLabels = {
            'ACTIVO':        'Activo',
            'INACTIVO':      'Inactivo',
            'EN REPARACION': 'En Reparación',
        };
        const raw      = v.estado_raw ?? '';
        const badgeCls = estadoClases[raw] ?? 'bg-secondary';
        const badgeTxt = estadoLabels[raw]  ?? raw ?? '—';

        const conductorHtml = v.conductor
            ? `<span><i class="fas fa-user-circle text-success me-1 fa-sm"></i>${v.conductor}</span>`
            : `<span class="text-warning"><i class="fas fa-user-slash me-1 fa-sm"></i>Sin conductor</span>`;

        // Centro de costo: tres estados posibles
        // 1. Sin código asignado → '—'
        // 2. Código + descripción encontrada en catálogo SQL Server
        // 3. Código en vehículo pero NO encontrado en catálogo SQL Server → muestra código + aviso
        const ccCode = v.centro_costo ?? '';
        let ccHtml = '<span class="text-muted">—</span>';
        if (ccCode) {
            const ccFull = this.centrosCosto[ccCode] ?? '';
            if (ccFull && ccFull.includes(' - ')) {
                const ccDesc = ccFull.split(' - ')[1];
                ccHtml = `<span class="badge bg-primary me-1">${ccCode}</span><small class="text-muted">${ccDesc}</small>`;
            } else {
                // Código existe en vehículo pero no en catálogo SQL Server
                ccHtml = `<span class="badge bg-warning text-dark me-1" title="Código no encontrado en catálogo SQL Server">${ccCode}</span><small class="text-muted fst-italic">Sin catálogo</small>`;
            }
        }

        return `
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:40px;height:40px">
                        <i class="fas fa-truck text-secondary fa-sm"></i>
                    </div>
                    <div>
                        <div>
                            <span class="badge bg-dark me-1 fw-semibold">${v.placa ?? '—'}</span>
                            ${v.codigo ? `<small class="text-muted">${v.codigo}</small>` : ''}
                            ${ccCode ? `<span class="badge bg-info text-dark ms-1" title="Centro de Costo">${ccCode}</span>` : ''}
                        </div>
                        <small class="text-muted">${v.vehiculo ?? '—'} ${v.anio ? '(' + v.anio + ')' : ''}</small>
                    </div>
                </div>
            </td>
            <td><small class="text-muted"><i class="fas fa-tachometer-alt me-1 fa-xs"></i>${v.kilometraje ?? '0 km'}</small></td>
            <td><small>${ccHtml}</small></td>
            <td><small>${conductorHtml}</small></td>
            <td><span class="badge ${badgeCls} px-3 py-2 rounded-pill">${badgeTxt}</span></td>
            <td class="text-end pe-4">${v.acciones ?? ''}</td>
        </tr>`;
    },

    // ── Estadísticas ─────────────────────────────
    async cargarEstadisticas() {
        try {
            const data = await HttpClient.get(this.baseUrl + 'vehiculos/getEstadisticas');

            const set = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val ?? 0;
            };
            set('stat_total',         data.total);
            set('stat_activos',       data.por_estado?.['ACTIVO']);
            set('stat_reparacion',    data.por_estado?.['EN REPARACION']);
            set('stat_sin_conductor', data.sin_conductor);
        } catch {
            // estadísticas opcionales
        }
    },

    // ── Cambiar estado desde index ────────────────
    _bindCambiarEstado() {
        const mensajes = {
            'ACTIVO':        'El vehículo será marcado como ACTIVO.',
            'INACTIVO':      'El vehículo será marcado como INACTIVO.',
            'EN REPARACION': 'El vehículo será marcado como EN REPARACIÓN.',
        };

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.cambiar-estado');
            if (!btn) return;

            document.getElementById('vehiculo_id').value             = btn.dataset.id;
            document.getElementById('nuevo_estado').value            = btn.dataset.estado;
            document.getElementById('mensaje_cambio_estado').textContent = mensajes[btn.dataset.estado] ?? '';
            document.getElementById('motivo_cambio').value           = '';

            new bootstrap.Modal(document.getElementById('modalCambiarEstado')).show();
        });

        document.getElementById('formCambiarEstado')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = e.target.querySelector('[type="submit"]');
            UI.toggleButtonLoading(submitBtn, true);

            try {
                const data = await HttpClient.post(
                    this.baseUrl + 'vehiculos/cambiarEstado',
                    new FormData(e.target)
                );
                bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado'))?.hide();
                UI.showToast('success', data.message ?? 'Estado actualizado');
                setTimeout(() => this.cargarVehiculos(), 600);
                this.cargarEstadisticas();
            } catch (err) {
                UI.showToast('error', err.message ?? 'Error al cambiar estado');
                UI.toggleButtonLoading(submitBtn, false);
            }
        });
    },

    // ── Importación masiva ──────────────────────
    _bindImportar() {
        document.getElementById('btn_importar')?.addEventListener('click', () => {
            // Reset del formulario y resultados previos
            document.getElementById('formImportar')?.reset();
            document.getElementById('resultado_importar')?.classList.add('d-none');
            document.getElementById('contenedor_errores_importar')?.classList.add('d-none');
            new bootstrap.Modal(document.getElementById('modalImportar')).show();
        });

        document.getElementById('formImportar')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = e.target.querySelector('[type="submit"]');
            const archivo   = document.getElementById('archivo_importar')?.files[0];

            if (!archivo) {
                UI.showToast('error', 'Seleccione un archivo CSV');
                return;
            }

            const formData = new FormData();
            formData.append('archivo', archivo);

            UI.toggleButtonLoading(submitBtn, true);
            try {
                const data = await HttpClient.post(this.baseUrl + 'vehiculos/importar', formData);
                this._renderResultadoImportar(data);

                if ((data.insertados ?? 0) + (data.actualizados ?? 0) > 0) {
                    this.cargarVehiculos();
                    this.cargarEstadisticas();
                }
            } catch (err) {
                UI.showToast('error', err.message ?? 'Error al importar el archivo');
            } finally {
                UI.toggleButtonLoading(submitBtn, false);
            }
        });
    },

    _renderResultadoImportar(data) {
        const contenedor = document.getElementById('resultado_importar');
        const resumen    = document.getElementById('resumen_importar');
        const contErrores = document.getElementById('contenedor_errores_importar');
        const tbody      = document.getElementById('errores_importar');
        if (!contenedor || !resumen) return;

        contenedor.classList.remove('d-none');

        const numErrores = data.errores ? Object.keys(data.errores).length : 0;
        resumen.className = 'alert mb-2 ' + (data.success ? (numErrores ? 'alert-warning' : 'alert-success') : 'alert-danger');
        resumen.textContent = data.message ?? 'Importación finalizada';

        if (numErrores > 0 && tbody) {
            tbody.innerHTML = Object.entries(data.errores)
                .map(([fila, msg]) => `<tr><td>${fila}</td><td>${msg}</td></tr>`)
                .join('');
            contErrores?.classList.remove('d-none');
        } else {
            contErrores?.classList.add('d-none');
        }
    },

    // ── Utilidades ───────────────────────────────
    _renderSkeleton(tbody) {
        const row = () => `
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded" style="width:40px;height:40px"></div>
                        <div>
                            <div class="bg-light rounded mb-1" style="width:80px;height:13px"></div>
                            <div class="bg-light rounded" style="width:120px;height:11px"></div>
                        </div>
                    </div>
                </td>
                <td><div class="bg-light rounded" style="width:70px;height:13px"></div></td>
                <td><div class="bg-light rounded" style="width:90px;height:13px"></div></td>
                <td><div class="bg-light rounded" style="width:110px;height:13px"></div></td>
                <td><div class="bg-light rounded" style="width:65px;height:22px;border-radius:20px!important"></div></td>
                <td class="text-end pe-4"><div class="bg-light rounded d-inline-block" style="width:60px;height:28px"></div></td>
            </tr>`;
        tbody.innerHTML = Array(6).fill(row()).join('');
    },

    _setInfo(shown, total) {
        const el = document.getElementById('tabla_info');
        if (el) el.textContent = `Mostrando ${shown} de ${total} vehículos`;

        const ts = document.getElementById('tabla_last_update');
        if (ts) {
            const now = new Date();
            ts.textContent = `Actualizado ${now.toLocaleTimeString()}`;
        }
    },

    _debounce(fn, ms) {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
    },
};

// Exportar al scope global
window.VehiculosShow  = VehiculosShow;
window.VehiculosIndex = VehiculosIndex;
