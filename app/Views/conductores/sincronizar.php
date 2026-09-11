<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-sync-alt text-primary"></i> <?= $page_title ?>
            </h1>
            <p class="text-muted mb-0">Consulta los conductores disponibles en el ERP y sincronízalos con GMV.</p>
        </div>
        <div>
            <a href="<?= base_url('conductores') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database"></i> Conductores ERP</h6>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary" id="btn-recargar">
                    <i class="fas fa-sync"></i> Recargar
                </button>
                <button class="btn btn-sm btn-primary" id="btn-sincronizar-seleccion">
                    <i class="fas fa-cloud-upload-alt"></i> Sincronizar seleccionados
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="filtro-busqueda" class="form-label">Buscar</label>
                    <div class="input-group">
                        <input type="text" id="filtro-busqueda" class="form-control" placeholder="Nombre, apellido, código o identificación">
                        <button class="btn btn-outline-secondary" id="btn-buscar"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabla-erp">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 45px;">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th>No. Empleado</th>
                            <th>Nombres y Apellidos</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Fecha de Ingreso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4" id="estado-tabla">
                                <i class="fas fa-spinner fa-spin"></i> Cargando información...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let datosErp = [];
let sincronizacionEnCurso = false;

$(document).ready(function() {
    cargarConductores();

    $('#btn-recargar').on('click', function() {
        cargarConductores();
    });

    $('#btn-sincronizar-seleccion').on('click', function() {
        iniciarSincronizacionSeleccion();
    });

    $('#btn-buscar').on('click', function() {
        filtrarConductores();
    });

    $('#filtro-busqueda').on('keypress', function(e) {
        if (e.which === 13) {
            filtrarConductores();
        }
    });

    $(document).on('change', '#select-all', function() {
        if (sincronizacionEnCurso) return;
        const checked = $(this).is(':checked');
        $('.seleccion-conductor').prop('checked', checked);
    });

    $(document).on('change', '.seleccion-conductor', function() {
        if (!$(this).is(':checked')) {
            $('#select-all').prop('checked', false);
        } else if ($('.seleccion-conductor').length === $('.seleccion-conductor:checked').length) {
            $('#select-all').prop('checked', true);
        }
    });

    $(document).on('click', '.btn-sincronizar', function() {
        const codigo = $(this).data('codigo');
        const nombre = $(this).data('nombre');
        sincronizarConductor(codigo, nombre);
    });
});

function cargarConductores() {
    $('#estado-tabla').html('<i class="fas fa-spinner fa-spin"></i> Consultando ERP...');

    $.get('<?= base_url('conductores/conductores-erp') ?>')
        .done(function(response) {
            if (response.success) {
                datosErp = response.conductores || [];
                renderizarTabla(datosErp);
            } else {
                mostrarMensaje('No fue posible cargar los datos del ERP.', 'danger');
                renderizarTabla([]);
            }
        })
        .fail(function() {
            mostrarMensaje('Error de conexión al consultar el ERP.', 'danger');
            renderizarTabla([]);
        });
}

function filtrarConductores() {
    const termino = $('#filtro-busqueda').val().toLowerCase().trim();

    if (!termino) {
        renderizarTabla(datosErp);
        return;
    }

    const filtrados = datosErp.filter(conductor => {
        return [
            conductor['No. Empleado'],
            conductor['Nombres y Apellidos'],
            conductor['Cedula'],
            conductor['Telefono'],
            conductor['Ubicación']
        ].some(campo => (campo || '').toString().toLowerCase().includes(termino));
    });

    renderizarTabla(filtrados);
}

function renderizarTabla(conductores) {
    const tbody = $('#tabla-erp tbody');

    if (!conductores.length) {
        tbody.html('<tr><td colspan="7" class="text-center text-muted py-4">Sin datos para mostrar</td></tr>');
        return;
    }

    const filas = conductores.map(conductor => {
        const numeroEmpleado = conductor['No. Empleado'] || '-';
        const nombreCompleto = conductor['Nombres y Apellidos'] || '-';
        const cedula = conductor['Cedula'] || '-';
        const telefono = conductor['Telefono'] || '-';
        const fechaIngreso = conductor['Fecha de Ingreso'] || '-';

        return `
            <tr data-codigo="${numeroEmpleado}">
                <td class="text-center">
                    <input type="checkbox" class="form-check-input seleccion-conductor" data-codigo="${numeroEmpleado}" data-nombre="${nombreCompleto}">
                </td>
                <td><code>${numeroEmpleado}</code></td>
                <td>${nombreCompleto}</td>
                <td>${cedula}</td>
                <td>${telefono}</td>
                <td>${fechaIngreso}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-primary btn-sincronizar" data-codigo="${numeroEmpleado}" data-nombre="${nombreCompleto}">
                        <i class="fas fa-cloud-download-alt"></i> Sincronizar
                    </button>
                </td>
            </tr>
        `;
    }).join('');

    tbody.html(filas);
}

function sincronizarConductor(codigo, nombre) {
    if (!codigo) {
        mostrarMensaje('Código no válido para sincronización.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Sincronizar Conductor',
        text: `¿Deseas sincronizar a ${nombre || 'este conductor'}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, sincronizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (!result.isConfirmed) return;

        ejecutarSincronizacionSecuencial([{ codigo, nombre }]);
    });
}

function iniciarSincronizacionSeleccion() {
    if (sincronizacionEnCurso) {
        mostrarMensaje('Ya hay una sincronización en curso.', 'info');
        return;
    }

    const seleccionados = obtenerSeleccionados();

    if (seleccionados.length === 0) {
        mostrarMensaje('Seleccione al menos un conductor para sincronizar.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Sincronizar Conductores',
        text: `Se sincronizarán ${seleccionados.length} conductores. El proceso se ejecutará uno a uno.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Iniciar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (!result.isConfirmed) return;
        ejecutarSincronizacionSecuencial(seleccionados);
    });
}

function obtenerSeleccionados() {
    return $('.seleccion-conductor:checked').map(function() {
        return {
            codigo: $(this).data('codigo'),
            nombre: $(this).data('nombre')
        };
    }).get();
}

function ejecutarSincronizacionSecuencial(lista) {
    if (!Array.isArray(lista) || lista.length === 0) return;

    sincronizacionEnCurso = true;
    $('#btn-sincronizar-seleccion').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sincronizando...');
    $('.seleccion-conductor, #select-all').prop('disabled', true);

    const pendientes = [...lista];
    const resultados = [];

    const procesarSiguiente = () => {
        if (pendientes.length === 0) {
            finalizarSincronizacion(resultados);
            return;
        }

        const actual = pendientes.shift();
        marcarFilaEnProceso(actual.codigo);

        $.post('<?= base_url('conductores/sincronizar-conductor') ?>', { codigo: actual.codigo })
            .done(function(response) {
                const success = response && response.success;
                resultados.push({
                    codigo: actual.codigo,
                    nombre: actual.nombre,
                    success: !!success,
                    message: response && response.message ? response.message : ''
                });

                marcarFilaResultado(actual.codigo, success);

                if (!success) {
                    mostrarMensaje(response && response.message ? response.message : `Error al sincronizar ${actual.nombre}`, 'warning');
                }
            })
            .fail(function() {
                resultados.push({
                    codigo: actual.codigo,
                    nombre: actual.nombre,
                    success: false,
                    message: 'Error de conexión'
                });
                marcarFilaResultado(actual.codigo, false);
                mostrarMensaje(`Error de conexión al sincronizar ${actual.nombre}`, 'danger');
            })
            .always(function() {
                procesarSiguiente();
            });
    };

    procesarSiguiente();
}

function finalizarSincronizacion(resultados) {
    sincronizacionEnCurso = false;
    $('#btn-sincronizar-seleccion').prop('disabled', false).html('<i class="fas fa-cloud-upload-alt"></i> Sincronizar seleccionados');
    $('.seleccion-conductor, #select-all').prop('disabled', false).prop('checked', false);

    const exitosos = resultados.filter(r => r.success).length;
    const fallidos = resultados.length - exitosos;

    if (exitosos > 0) {
        mostrarMensaje(`${exitosos} conductor(es) sincronizado(s) correctamente.`, 'success');
    }

    if (fallidos > 0) {
        mostrarMensaje(`${fallidos} conductor(es) no pudieron sincronizarse. Revise los mensajes previos.`, 'warning');
    }
}

function marcarFilaEnProceso(codigo) {
    const fila = buscarFilaPorCodigo(codigo);
    if (!fila) return;
    fila.removeClass('table-success table-danger').addClass('table-active');
}

function marcarFilaResultado(codigo, exito) {
    const fila = buscarFilaPorCodigo(codigo);
    if (!fila) return;
    fila.removeClass('table-active');
    fila.addClass(exito ? 'table-success' : 'table-danger');
    fila.find('.seleccion-conductor').prop('checked', false);
}

function buscarFilaPorCodigo(codigo) {
    return $(`#tabla-erp tbody tr[data-codigo="${codigo}"]`);
}

function mostrarMensaje(mensaje, tipo = 'info') {
    const alert = $(`
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('.container-fluid').prepend(alert);

    setTimeout(() => alert.alert('close'), 4000);
}
</script>
<?= $this->endSection() ?>
