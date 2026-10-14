
@extends('layouts.app')

@section('title', 'Gestión de Tipos de Trámites')

@section('content')

<style>
    .tipos-page {
        color: #343a40;
    }

    .tipos-page .page-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }

    .tipos-page .page-heading h2 {
        font-size: 1.55rem;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .tipos-page .page-heading p {
        color: #6c757d;
        margin: 0;
        font-size: .94rem;
    }

    .tipos-page .btn-institucional {
        background: #b5121b;
        border: 1px solid #b5121b;
        color: #fff;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
    }

    .tipos-page .btn-institucional:hover {
        background: #8f0d14;
        border-color: #8f0d14;
        color: #fff;
    }

    .tipos-page .stat-card {
        background: #fff;
        border: 1px solid #eeeeee;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .tipos-page .stat-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    .tipos-page .icon-total {
        background: #fff1f2;
        color: #b5121b;
    }

    .tipos-page .icon-active {
        background: #e8f7ee;
        color: #198754;
    }

    .tipos-page .icon-inactive {
        background: #fff3cd;
        color: #997404;
    }

    .tipos-page .stat-label {
        color: #6c757d;
        font-size: .88rem;
        margin-bottom: 4px;
    }

    .tipos-page .stat-number {
        font-size: 1.65rem;
        line-height: 1.2;
        font-weight: 700;
        color: #343a40;
    }

    .tipos-page .section-card {
        background: #fff;
        border: 1px solid #eeeeee;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        overflow: hidden;
    }

    .tipos-page .section-header {
        padding: 20px 22px;
        border-bottom: 1px solid #eeeeee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .tipos-page .section-header h5 {
        font-weight: 700;
        font-size: 1.05rem;
        margin: 0 0 4px;
    }

    .tipos-page .section-header p {
        color: #6c757d;
        margin: 0;
        font-size: .88rem;
    }

    .tipos-page .search-box {
        position: relative;
        width: 100%;
        max-width: 330px;
    }

    .tipos-page .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .tipos-page .search-box input {
        padding: 10px 12px 10px 36px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        width: 100%;
    }

    .tipos-page .search-box input:focus {
        border-color: #b5121b;
        box-shadow: 0 0 0 .2rem rgba(181, 18, 27, .10);
        outline: none;
    }

    .tipos-page .table {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .tipos-page .table thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 700;
        padding: 15px 14px;
        white-space: nowrap;
        border-bottom: 1px solid #eeeeee;
    }

    .tipos-page .table tbody td {
        padding: 15px 14px;
        font-size: .88rem;
        border-color: #eeeeee;
    }

    .tipos-page .table tbody tr:hover {
        background: #fffafa;
    }

    .tipos-page .type-code {
        display: inline-block;
        padding: 5px 9px;
        background: #fff1f2;
        color: #b5121b;
        border-radius: 6px;
        font-weight: 700;
        font-size: .8rem;
    }

    .tipos-page .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: .77rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .tipos-page .status-active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .tipos-page .status-inactive {
        background: #f8d7da;
        color: #842029;
    }

    .tipos-page .action-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #495057;
        text-decoration: none;
        cursor: pointer;
    }

    .tipos-page .action-btn:hover {
        background: #fff1f2;
        color: #b5121b;
        border-color: #f1b8bc;
    }

    .tipos-page .action-btn.toggle:hover {
        background: #fff3cd;
        color: #997404;
        border-color: #ffe69c;
    }

    .tipos-page .empty-state {
        text-align: center;
        padding: 42px 15px;
        color: #6c757d;
    }

    .tipos-page .empty-state i {
        display: block;
        font-size: 2.4rem;
        margin-bottom: 12px;
        color: #adb5bd;
    }

    .tipos-page .modal-content {
        border: none;
        border-radius: 14px;
        overflow: hidden;
    }

    .tipos-page .modal-header {
        background: linear-gradient(135deg, #b5121b, #8f0d14);
        color: #fff;
        padding: 19px 22px;
    }

    .tipos-page .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .tipos-page .modal-title {
        font-weight: 700;
    }

    .tipos-page .modal-body {
        padding: 24px;
    }

    .tipos-page .form-label {
        font-weight: 600;
        font-size: .88rem;
    }

    .tipos-page .form-control,
    .tipos-page .form-select {
        padding: 10px 12px;
        border-radius: 8px;
        border-color: #dee2e6;
    }

    .tipos-page .form-control:focus,
    .tipos-page .form-select:focus {
        border-color: #b5121b;
        box-shadow: 0 0 0 .2rem rgba(181, 18, 27, .10);
    }

    @media (max-width: 768px) {
        .tipos-page .page-heading h2 {
            font-size: 1.3rem;
        }

        .tipos-page .section-header {
            align-items: stretch;
        }

        .tipos-page .search-box {
            max-width: none;
        }
    }
</style>

<div class="tipos-page">

    {{-- Encabezado de la página --}}
    <div class="page-heading">
        <div>
            <h2>
                <i class="bi bi-card-list me-2" style="color:#b5121b;"></i>
                Gestión de Tipos de Trámites
            </h2>
            <p>Administración de los trámites disponibles en el sistema institucional.</p>
        </div>

        <button type="button"
                class="btn btn-institucional"
                id="btnNuevoTipo"
                data-bs-toggle="modal"
                data-bs-target="#modalTipo">
            <i class="bi bi-plus-circle me-1"></i>
            Nuevo tipo de trámite
        </button>
    </div>

    {{-- Mensajes del sistema --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Se encontraron errores en el formulario:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tarjetas de estadísticas --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-icon icon-total">
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <div class="stat-label">Total de tipos de trámites</div>
                    <div class="stat-number">{{ $totalTipos ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-icon icon-active">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Tipos habilitados</div>
                    <div class="stat-number">{{ $totalHabilitados ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-icon icon-inactive">
                    <i class="bi bi-pause-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Tipos deshabilitados</div>
                    <div class="stat-number">{{ $totalDeshabilitados ?? 0 }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabla de tipos de trámites --}}
    <div class="section-card">

        <div class="section-header">
            <div>
                <h5><i class="bi bi-table me-2" style="color:#b5121b;"></i>Tipos registrados</h5>
                <p>Consulta y administración de los tipos de trámites.</p>
            </div>

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="search"
                       id="buscarTipo"
                       placeholder="Buscar tipo de trámite..."
                       aria-label="Buscar tipo de trámite">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" id="tablaTipos">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre del trámite</th>
                        <th>Descripción</th>
                        <th>Requisitos</th>
                        <th>Tiempo estimado</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tipos as $tipo)
                        <tr class="fila-tipo">
                            <td>
                                <span class="type-code">
                                    {{ $tipo->cod_tipo }}
                                </span>
                            </td>

                            <td>
                                <strong>{{ $tipo->nombre_tramite }}</strong>
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit($tipo->descripcion ?? 'Sin descripción', 75) }}
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit($tipo->requisitos ?? 'No especificados', 65) }}
                            </td>

                            <td>
                                <i class="bi bi-clock me-1 text-secondary"></i>
                                {{ $tipo->tiempo_estimado }} días
                            </td>

                            <td>
                                @if((int)$tipo->habilitado === 1)
                                    <span class="status-badge status-active">
                                        <i class="bi bi-check-circle me-1"></i>Habilitado
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <i class="bi bi-x-circle me-1"></i>Deshabilitado
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    <button type="button"
                                            class="action-btn btnEditarTipo"
                                            title="Editar tipo de trámite"
                                            data-id="{{ $tipo->cod_tipo }}"
                                            data-nombre="{{ $tipo->nombre_tramite }}"
                                            data-descripcion="{{ $tipo->descripcion }}"
                                            data-requisitos="{{ $tipo->requisitos }}"
                                            data-tiempo="{{ $tipo->tiempo_estimado }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form method="POST"
                                          action="{{ url('/tipos-tramites/'.$tipo->cod_tipo.'/estado') }}"
                                          onsubmit="return confirm('¿Desea cambiar el estado de este tipo de trámite?');">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="action-btn toggle"
                                                title="{{ (int)$tipo->habilitado === 1 ? 'Deshabilitar' : 'Habilitar' }}">
                                            @if((int)$tipo->habilitado === 1)
                                                <i class="bi bi-toggle-on"></i>
                                            @else
                                                <i class="bi bi-toggle-off"></i>
                                            @endif
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h6>No hay tipos de trámites registrados</h6>
                                    <p class="mb-0">Utiliza el botón “Nuevo tipo de trámite” para registrar uno.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="sinResultados" style="display:none;">
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-search"></i>
                                <h6>No se encontraron resultados</h6>
                                <p class="mb-0">Prueba con otro término de búsqueda.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top text-secondary small">
            <i class="bi bi-info-circle me-1"></i>
            Los cambios de estado permiten habilitar o deshabilitar un tipo de trámite sin eliminar su registro.
        </div>

    </div>

</div>

{{-- Modal para registrar y editar --}}
<div class="modal fade" id="modalTipo" tabindex="-1" aria-labelledby="modalTipoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="modalTipoLabel">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo tipo de trámite
                    </h5>
                    <small>Completa los datos solicitados.</small>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formTipo"
                  action="{{ route('tipos-tramites.store') }}"
                  method="POST">
                @csrf

                <input type="hidden" name="_method" id="metodoTipo" value="POST">

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="nombre_tramite" class="form-label">
                            Nombre del trámite <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="nombre_tramite"
                               name="nombre_tramite"
                               maxlength="150"
                               required
                               value="{{ old('nombre_tramite') }}"
                               placeholder="Ej. Modificación de datos personales">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control"
                                  id="descripcion"
                                  name="descripcion"
                                  rows="3"
                                  placeholder="Describe el trámite">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="requisitos" class="form-label">Requisitos</label>
                        <textarea class="form-control"
                                  id="requisitos"
                                  name="requisitos"
                                  rows="3"
                                  placeholder="Detalla los requisitos necesarios">{{ old('requisitos') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="tiempo_estimado" class="form-label">
                            Tiempo estimado (días) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               class="form-control"
                               id="tiempo_estimado"
                               name="tiempo_estimado"
                               min="1"
                               required
                               value="{{ old('tiempo_estimado', 5) }}">
                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-institucional" id="btnGuardarTipo">
                        <i class="bi bi-save me-1"></i>Guardar tipo de trámite
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buscar = document.getElementById('buscarTipo');
    const filas = document.querySelectorAll('#tablaTipos tbody tr.fila-tipo');
    const sinResultados = document.getElementById('sinResultados');

    const modalElemento = document.getElementById('modalTipo');
    const modal = modalElemento
        ? bootstrap.Modal.getOrCreateInstance(modalElemento)
        : null;

    const formulario = document.getElementById('formTipo');
    const tituloModal = document.getElementById('modalTipoLabel');
    const botonGuardar = document.getElementById('btnGuardarTipo');
    const metodo = document.getElementById('metodoTipo');

    const urlRegistro = @json(route('tipos-tramites.store'));
    const urlBase = @json(url('/tipos-tramites'));

    // Búsqueda en la tabla
    if (buscar) {
        buscar.addEventListener('input', function () {
            const termino = this.value.toLocaleLowerCase('es').trim();
            let visibles = 0;

            filas.forEach(function (fila) {
                const coincide = fila.textContent
                    .toLocaleLowerCase('es')
                    .includes(termino);

                fila.style.display = coincide ? '' : 'none';

                if (coincide) visibles++;
            });

            if (sinResultados) {
                sinResultados.style.display =
                    (filas.length > 0 && visibles === 0) ? '' : 'none';
            }
        });
    }

    // Preparar el formulario para un nuevo registro
    const btnNuevo = document.getElementById('btnNuevoTipo');

    if (btnNuevo) {
        btnNuevo.addEventListener('click', function () {
            formulario.reset();
            formulario.action = urlRegistro;
            metodo.value = 'POST';

            tituloModal.innerHTML =
                '<i class="bi bi-plus-circle me-2"></i>Nuevo tipo de trámite';

            botonGuardar.innerHTML =
                '<i class="bi bi-save me-1"></i>Guardar tipo de trámite';

            document.getElementById('tiempo_estimado').value = 5;
        });
    }

    // Preparar el formulario para editar un registro existente
    document.querySelectorAll('.btnEditarTipo').forEach(function (boton) {
        boton.addEventListener('click', function () {
            const id = this.dataset.id;

            formulario.reset();
            formulario.action = urlBase + '/' + encodeURIComponent(id);
            metodo.value = 'PUT';

            document.getElementById('nombre_tramite').value =
                this.dataset.nombre || '';

            document.getElementById('descripcion').value =
                this.dataset.descripcion || '';

            document.getElementById('requisitos').value =
                this.dataset.requisitos || '';

            document.getElementById('tiempo_estimado').value =
                this.dataset.tiempo || 5;

            tituloModal.innerHTML =
                '<i class="bi bi-pencil-square me-2"></i>Editar tipo de trámite';

            botonGuardar.innerHTML =
                '<i class="bi bi-save me-1"></i>Actualizar tipo de trámite';

            modal.show();
        });
    });

    // Si hay errores de validación, abrir el modal para corregirlos.
    @if($errors->any())
        if (modal) {
            modal.show();
        }
    @endif
});
</script>

@endsection
