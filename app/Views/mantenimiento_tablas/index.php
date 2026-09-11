<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0"><i class="fas fa-database me-2 text-primary"></i><?= $title ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mantenimiento de Tablas</li>
                </ol>
            </nav>
        </div>
        <span class="badge bg-primary fs-6">
            <i class="fas fa-table me-1"></i><?= $total_tablas ?> Tablas
        </span>
    </div>

    <!-- Alertas -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Error de Conexión:</strong> <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <!-- ── Controles ── -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap gap-3 align-items-end">

                <!-- Tipo -->
                <div>
                    <label class="form-label small fw-semibold mb-1 d-block">Tipo</label>
                    <div class="btn-group" role="group" id="grupo-fuente">
                        <button type="button" class="btn btn-outline-primary btn-fuente active" data-fuente="tablas">
                            <i class="fas fa-table me-1"></i> Tablas
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-fuente" data-fuente="vistas">
                            <i class="fas fa-eye me-1"></i> Vistas
                        </button>
                    </div>
                </div>

                <!-- Buscar por nombre -->
                <div class="flex-grow-1">
                    <label class="form-label small fw-semibold mb-1 d-block">Buscar por nombre</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="input-buscar"
                               placeholder="Escribe el nombre y presiona Enter o Buscar...">
                        <button class="btn btn-primary" id="btn-buscar">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                        <button class="btn btn-outline-secondary" id="btn-limpiar-busqueda" title="Limpiar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Modo vista -->
                <div>
                    <label class="form-label small fw-semibold mb-1 d-block">Modo</label>
                    <div class="btn-group" role="group" id="grupo-vista">
                        <button type="button" class="btn btn-outline-secondary btn-vista" data-vista="tabla" title="Tabla">
                            <i class="fas fa-table"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-vista active" data-vista="lista" title="Lista">
                            <i class="fas fa-list"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-vista" data-vista="card" title="Tarjetas">
                            <i class="fas fa-th-large"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ── Área de resultados ── -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2">
            <span class="fw-semibold" id="resultado-titulo">
                <i class="fas fa-table me-1 text-primary"></i> Tablas
            </span>
            <small class="text-muted" id="tabla-info">—</small>
        </div>
        <div class="card-body p-0" id="resultado-body">
            <!-- Estado inicial -->
            <div class="text-center py-5 text-muted" id="estado-inicial">
                <i class="fas fa-hand-pointer fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Selecciona <strong>Tablas</strong> o <strong>Vistas</strong> para cargar los datos.</p>
            </div>
            <!-- Loading -->
            <div class="text-center py-5 d-none" id="estado-loading">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Cargando...</p>
            </div>
            <!-- Vista: tabla -->
            <div class="d-none" id="vista-tabla">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="4%">#</th>
                                <th>Nombre</th>
                                <th width="12%">Esquema</th>
                                <th width="10%" class="text-end">Filas</th>
                                <th width="10%">Tipo</th>
                                <th width="28%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-tabla"></tbody>
                    </table>
                </div>
            </div>
            <!-- Vista: lista (default) -->
            <div class="d-none" id="vista-lista">
                <ul class="list-group list-group-flush" id="ul-lista"></ul>
            </div>
            <!-- Vista: cards -->
            <div class="d-none p-3" id="vista-card">
                <div class="row g-3" id="grid-cards"></div>
            </div>
        </div>
        <!-- Paginación -->
        <div class="card-footer bg-white border-top d-none d-flex justify-content-between align-items-center py-2" id="paginacion-footer">
            <small class="text-muted" id="pag-info"></small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item" id="btn-prev">
                        <a class="page-link" href="#" data-page="prev"><i class="fas fa-chevron-left"></i></a>
                    </li>
                    <li class="page-item" id="btn-next">
                        <a class="page-link" href="#" data-page="next"><i class="fas fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const BASE       = window.location.origin + window.location.pathname.replace(/\/mantenimiento-tablas.*$/, '/');
    const URL_TABLAS = BASE + 'mantenimiento-tablas/obtener-tablas';
    const URL_VISTAS = BASE + 'mantenimiento-tablas/obtener-vistas';

    let state = {
        fuente:   'tablas',
        vista:    'lista',
        page:     1,
        total:    0,
        lastPage: 1,
        perPage:  20,
        buscar:   '',
        data:     []
    };

    // ── Helpers DOM ───────────────────────────────────────────────────────
    function qs(sel)  { return document.querySelector(sel); }
    function qsa(sel) { return document.querySelectorAll(sel); }

    function hide(sel) {
        qsa(sel).forEach(function (el) { el.classList.add('d-none'); });
    }
    function show(sel) {
        qsa(sel).forEach(function (el) { el.classList.remove('d-none'); });
    }

    // ── Fuente ────────────────────────────────────────────────────────────
    qsa('.btn-fuente').forEach(function (btn) {
        btn.addEventListener('click', function () {
            qsa('.btn-fuente').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            state.fuente = btn.dataset.fuente;
            state.page   = 1;
            qs('#resultado-titulo').innerHTML = state.fuente === 'tablas'
                ? '<i class="fas fa-table me-1 text-primary"></i> Tablas'
                : '<i class="fas fa-eye me-1 text-primary"></i> Vistas';
            cargar();
        });
    });

    // ── Modo de vista ─────────────────────────────────────────────────────
    qsa('.btn-vista').forEach(function (btn) {
        btn.addEventListener('click', function () {
            qsa('.btn-vista').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            state.vista = btn.dataset.vista;
            if (state.data.length > 0) renderDatos();
        });
    });

    // ── Buscar ────────────────────────────────────────────────────────────
    qs('#btn-buscar').addEventListener('click', function () {
        state.buscar = qs('#input-buscar').value.trim();
        state.page   = 1;
        cargar();
    });

    qs('#input-buscar').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            state.buscar = qs('#input-buscar').value.trim();
            state.page   = 1;
            cargar();
        }
    });

    qs('#btn-limpiar-busqueda').addEventListener('click', function () {
        qs('#input-buscar').value = '';
        state.buscar = '';
        state.page   = 1;
        cargar();
    });

    // ── Paginación ────────────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        const link = e.target.closest('#btn-prev a, #btn-next a');
        if (!link) return;
        e.preventDefault();
        const dir = link.dataset.page;
        if (dir === 'prev' && state.page > 1)              { state.page--; cargar(); }
        if (dir === 'next' && state.page < state.lastPage)  { state.page++; cargar(); }
    });

    // ── Carga AJAX ────────────────────────────────────────────────────────
    function cargar() {
        hide('#estado-inicial, #vista-tabla, #vista-lista, #vista-card, #paginacion-footer');
        show('#estado-loading');

        const url = (state.fuente === 'tablas' ? URL_TABLAS : URL_VISTAS)
            + '?pagina=' + state.page
            + '&por_pagina=' + state.perPage
            + '&buscar=' + encodeURIComponent(state.buscar);

        fetch(url)
            .then(function (r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function (res) {
                hide('#estado-loading');
                if (res.success && res.data) {
                    state.data     = res.data;
                    state.total    = (res.pagination && res.pagination.total    != null) ? res.pagination.total    : res.data.length;
                    state.lastPage = (res.pagination && res.pagination.last_page != null) ? res.pagination.last_page : 1;
                    renderDatos();
                    renderPaginacion();
                } else {
                    mostrarError(res.message || 'Error al cargar los datos.');
                }
            })
            .catch(function (err) {
                hide('#estado-loading');
                mostrarError('Error de conexión: ' + err.message);
            });
    }

    // ── Render ────────────────────────────────────────────────────────────
    function renderDatos() {
        const items  = state.data;
        const tipo   = state.fuente === 'tablas' ? 'Tabla' : 'Vista';
        const badge  = state.fuente === 'tablas' ? 'bg-primary' : 'bg-info text-dark';
        const icon   = state.fuente === 'tablas' ? 'table' : 'eye';
        const offset = (state.page - 1) * state.perPage;

        hide('#vista-tabla, #vista-lista, #vista-card');

        if (items.length === 0) {
            show('#estado-inicial');
            qs('#estado-inicial p').innerHTML = 'No se encontraron resultados con los filtros aplicados.';
            return;
        }

        if (state.vista === 'tabla') {
            const tbody = qs('#tbody-tabla');
            tbody.innerHTML = '';
            items.forEach(function (row, i) {
                const name   = row.TABLE_NAME  != null ? row.TABLE_NAME  : row;
                const schema = row.TABLE_SCHEMA != null ? row.TABLE_SCHEMA : '—';
                const filas  = row.row_count   != null ? Number(row.row_count).toLocaleString() : '—';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-muted small">${offset + i + 1}</td>
                    <td><i class="fas fa-${icon} me-2 text-secondary"></i><strong>${name}</strong></td>
                    <td class="text-muted small">${schema}</td>
                    <td class="text-end small">${filas}</td>
                    <td><span class="badge ${badge}">${tipo}</span></td>
                    <td>${acciones(name)}</td>`;
                tbody.appendChild(tr);
            });
            show('#vista-tabla');

        } else if (state.vista === 'lista') {
            const ul = qs('#ul-lista');
            ul.innerHTML = '';
            items.forEach(function (row, i) {
                const name   = row.TABLE_NAME  != null ? row.TABLE_NAME  : row;
                const schema = row.TABLE_SCHEMA != null ? row.TABLE_SCHEMA : '';
                const filas  = row.row_count   != null ? Number(row.row_count).toLocaleString() + ' filas' : '';
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `
                    <div>
                        <span class="text-muted small me-2">${offset + i + 1}.</span>
                        <i class="fas fa-${icon} me-2 text-secondary"></i>
                        <strong>${name}</strong>
                        <span class="badge ${badge} ms-2">${tipo}</span>
                        ${schema ? `<span class="text-muted small ms-2">${schema}</span>` : ''}
                        ${filas  ? `<span class="text-muted small ms-2">${filas}</span>`  : ''}
                    </div>
                    <div class="d-flex gap-1">${acciones(name, true)}</div>`;
                ul.appendChild(li);
            });
            show('#vista-lista');

        } else if (state.vista === 'card') {
            const grid = qs('#grid-cards');
            grid.innerHTML = '';
            items.forEach(function (row) {
                const name  = row.TABLE_NAME != null ? row.TABLE_NAME : row;
                const filas = row.row_count  != null ? Number(row.row_count).toLocaleString() : '—';
                const col = document.createElement('div');
                col.className = 'col-6 col-md-4 col-lg-3';
                col.innerHTML = `
                    <div class="card h-100 border-0 shadow-sm text-center p-3">
                        <i class="fas fa-${icon} fa-2x text-primary opacity-75 mb-2"></i>
                        <div class="fw-semibold small text-truncate mb-1" title="${name}">${name}</div>
                        <span class="badge ${badge} mb-1">${tipo}</span>
                        <small class="text-muted">${filas} filas</small>
                        <div class="d-flex flex-column gap-1 mt-2">${accionesCard(name)}</div>
                    </div>`;
                grid.appendChild(col);
            });
            show('#vista-card');
        }

        qs('#tabla-info').textContent = `${offset + 1}–${Math.min(offset + items.length, state.total)} de ${state.total}`;
    }

    // ── Acciones ──────────────────────────────────────────────────────────
    function acciones(name, compact) {
        compact = compact || false;
        return `
            <div class="btn-group">
                <a href="${BASE}mantenimiento-tablas/ver/${name}" class="btn btn-outline-primary btn-sm" title="Ver datos">
                    <i class="fas fa-eye"></i>${compact ? '' : ' Ver'}
                </a>
                <a href="${BASE}mantenimiento-tablas/estructura/${name}" class="btn btn-outline-secondary btn-sm" title="Estructura">
                    <i class="fas fa-cogs"></i>${compact ? '' : ' Estructura'}
                </a>
                <a href="${BASE}mantenimiento-tablas/exportar/${name}" class="btn btn-outline-success btn-sm" title="CSV">
                    <i class="fas fa-download"></i>${compact ? '' : ' CSV'}
                </a>
            </div>`;
    }

    function accionesCard(name) {
        return `
            <a href="${BASE}mantenimiento-tablas/ver/${name}" class="btn btn-outline-primary btn-sm w-100">
                <i class="fas fa-eye me-1"></i> Ver datos
            </a>
            <a href="${BASE}mantenimiento-tablas/estructura/${name}" class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-cogs me-1"></i> Estructura
            </a>
            <a href="${BASE}mantenimiento-tablas/exportar/${name}" class="btn btn-outline-success btn-sm w-100">
                <i class="fas fa-download me-1"></i> CSV
            </a>`;
    }

    // ── Paginación render ─────────────────────────────────────────────────
    function renderPaginacion() {
        const footer = qs('#paginacion-footer');
        if (state.lastPage <= 1) { footer.classList.add('d-none'); return; }
        footer.classList.remove('d-none');
        qs('#pag-info').textContent = `Página ${state.page} de ${state.lastPage}`;
        qs('#btn-prev').classList.toggle('disabled', state.page === 1);
        qs('#btn-next').classList.toggle('disabled', state.page >= state.lastPage);
    }

    // ── Error ─────────────────────────────────────────────────────────────
    function mostrarError(msg) {
        const div = document.createElement('div');
        div.className = 'alert alert-danger alert-dismissible fade show mt-2';
        div.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        qs('.container-fluid').prepend(div);
        setTimeout(function () { div.remove(); }, 6000);
    }

    // Carga inicial automática
    cargar();
});
</script>
<?= $this->endSection() ?>
