
@extends('layouts.app')

@section('title', 'Gestión de Solicitudes - DDE Cochabamba')

@section('content')

<style>
    .solicitudes-page {
        --sol-rojo: #b5121b;
        --sol-rojo-oscuro: #8f0d14;
        --sol-rojo-suave: #fff1f2;
        --sol-fondo: #f5f6f8;
        --sol-texto: #343a40;
        --sol-gris: #6b7280;
        color: var(--sol-texto);
        font-family: "Segoe UI", Arial, sans-serif;
    }

    .solicitudes-page .encabezado-modulo {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }

    .solicitudes-page .titulo-modulo {
        color: var(--sol-rojo-oscuro);
        font-size: 25px;
        font-weight: 750;
        margin: 0 0 5px;
    }

    .solicitudes-page .subtitulo-modulo {
        color: var(--sol-gris);
        font-size: 14px;
        margin: 0;
    }

    .solicitudes-page .tarjeta-estadistica {
        background: #fff;
        border: 1px solid #eeeeee;
        border-radius: 15px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(0,0,0,.055);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .solicitudes-page .tarjeta-estadistica:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,.09);
    }

    .solicitudes-page .icono-estadistica {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--sol-rojo-suave);
        color: var(--sol-rojo);
        font-size: 21px;
        flex-shrink: 0;
    }

    .solicitudes-page .numero-estadistica {
        font-size: 27px;
        line-height: 1.2;
        font-weight: 750;
        color: #343a40;
    }

    .solicitudes-page .texto-estadistica {
        color: var(--sol-gris);
        font-size: 13px;
        margin-top: 5px;
    }

    .solicitudes-page .panel-solicitudes {
        background: #fff;
        border: 1px solid #eeeeee;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,.055);
    }

    .solicitudes-page .cabecera-panel {
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
        border-bottom: 1px solid #eeeeee;
    }

    .solicitudes-page .cabecera-panel h5 {
        font-size: 17px;
        font-weight: 750;
        margin: 0 0 5px;
    }

    .solicitudes-page .texto-secundario {
        color: var(--sol-gris);
        font-size: 13px;
    }

    .solicitudes-page .btn-institucional {
        color: #fff;
        background: var(--sol-rojo);
        border: 1px solid var(--sol-rojo);
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        transition: background .2s ease;
    }

    .solicitudes-page .btn-institucional:hover {
        color: #fff;
        background: var(--sol-rojo-oscuro);
        border-color: var(--sol-rojo-oscuro);
    }

    .solicitudes-page .form-control,
    .solicitudes-page .form-select {
        min-height: 42px;
        border-radius: 8px;
        font-size: 14px;
    }

    .solicitudes-page .form-control:focus,
    .solicitudes-page .form-select:focus {
        border-color: var(--sol-rojo);
        box-shadow: 0 0 0 .2rem rgba(181,18,27,.12);
    }

    .solicitudes-page .form-label {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .solicitudes-page .buscador-solicitudes {
        position: relative;
    }

    .solicitudes-page .buscador-solicitudes > i {
        position: absolute;
        left: 13px;
        top: 14px;
        color: #888;
        pointer-events: none;
    }

    .solicitudes-page .buscador-solicitudes input {
        padding-left: 38px;
    }

    .solicitudes-page .tabla-contenedor {
        width: 100%;
        overflow-x: auto;
    }

    .solicitudes-page .tabla-solicitudes {
        margin: 0;
        min-width: 950px;
    }

    .solicitudes-page .tabla-solicitudes thead th {
        background: var(--sol-rojo);
        color: #fff;
        border: none;
        padding: 14px 12px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .solicitudes-page .tabla-solicitudes tbody td {
        padding: 13px 12px;
        font-size: 13px;
        vertical-align: middle;
        border-color: #f0f0f0;
    }

    .solicitudes-page .tabla-solicitudes tbody tr:hover > * {
        background-color: #fff8f8;
    }

    .solicitudes-page .estado {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .solicitudes-page .estado-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .solicitudes-page .estado-revision {
        background: #cfe2ff;
        color: #084298;
    }

    .solicitudes-page .estado-aprobado {
        background: #d1e7dd;
        color: #0f5132;
    }

    .solicitudes-page .estado-rechazado {
        background: #f8d7da;
        color: #842029;
    }

    .solicitudes-page .estado-observado {
        background: #ffe5d0;
        color: #984c0c;
    }

    .solicitudes-page .estado-finalizado {
        background: #e2e3e5;
        color: #41464b;
    }

    .solicitudes-page .accion {
        width: 33px;
        height: 33px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 8px;
        margin: 2px;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .solicitudes-page .accion-editar {
        color: #0d6efd;
        background: #e7f1ff;
    }

    .solicitudes-page .accion-editar:hover {
        color: #fff;
        background: #0d6efd;
    }

    .solicitudes-page .accion-historial {
        color: #495057;
        background: #e9ecef;
    }

    .solicitudes-page .accion-historial:hover {
        color: #fff;
        background: #495057;
    }

    .solicitudes-page .accion-revision {
        color: #198754;
        background: #d1e7dd;
    }

    .solicitudes-page .accion-revision:hover {
        color: #fff;
        background: #198754;
    }

    .solicitudes-page .accion-eliminar {
        color: var(--sol-rojo);
        background: #fee2e2;
    }

    .solicitudes-page .accion-eliminar:hover {
        color: #fff;
        background: var(--sol-rojo);
    }

    .solicitudes-page .modal-content {
        border: none;
        border-radius: 14px;
        overflow: hidden;
    }

    .solicitudes-page .modal-header {
        background: var(--sol-rojo);
        color: #fff;
    }

    .solicitudes-page .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .solicitudes-page .alert {
        border-radius: 10px;
    }

    .solicitudes-page .seccion-formulario {
        padding: 24px;
    }

    .solicitudes-page .separador-seccion {
        color: var(--sol-rojo-oscuro);
        font-size: 14px;
        font-weight: 750;
        padding-bottom: 10px;
        margin: 5px 0 20px;
        border-bottom: 1px solid #eeeeee;
    }

    .solicitudes-page .vacio {
        padding: 45px 20px;
        text-align: center;
        color: var(--sol-gris);
    }

    .solicitudes-page .vacio i {
        display: block;
        font-size: 42px;
        margin-bottom: 15px;
        color: #c4c7cc;
    }

    @media (max-width: 768px) {
        .solicitudes-page .titulo-modulo {
            font-size: 21px;
        }

        .solicitudes-page .cabecera-panel,
        .solicitudes-page .seccion-formulario {
            padding: 17px;
        }

        .solicitudes-page .encabezado-modulo {
            align-items: flex-start;
        }
    }

    @media print {
       
        .solicitudes-page .accion,
        .solicitudes-page .modal {
            display: none !important;
        }

        .solicitudes-page .panel-solicitudes,
        .solicitudes-page .tarjeta-estadistica {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .solicitudes-page .tabla-solicitudes {
            min-width: 0;
        }

        .solicitudes-page .tabla-solicitudes thead th {
            color: #000;
            background: #eee !important;
        }
    }
</style>

<div class="solicitudes-page">

    {{-- MENSAJES DEL SISTEMA --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Cerrar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Cerrar"></button>
        </div>
    @endif

    @if(isset($error_solicitudes) && $error_solicitudes)
        <div class="alert alert-danger">
            <strong><i class="fa-solid fa-database me-2"></i>
                No se pudieron cargar las solicitudes.</strong>
            <div class="mt-2">{{ $error_solicitudes }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Revise los siguientes datos:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ENCABEZADO DEL MÓDULO --}}

    <div class="encabezado-modulo">
        <div>
            <h1 class="titulo-modulo">
                <i class="fa-solid fa-file-circle-check me-2"></i>
                Gestión de Solicitudes
            </h1>
            <p class="subtitulo-modulo">
                Registro, administración y seguimiento de trámites.
            </p>
        </div>

        <div>
            @if(($modo ?? 'listar') === 'listar')
                

                <a href="{{ route('solicitudes.create') }}"
                   class="btn-institucional">
                    <i class="fa-solid fa-plus"></i>
                    Nueva solicitud
                </a>
            @else
                <a href="{{ route('solicitudes.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Volver al listado
                </a>
            @endif
        </div>
    </div>

    {{-- CREAR O EDITAR SOLICITUD --}}

    @if(in_array(($modo ?? 'listar'), ['crear', 'editar'], true))

        @php
            $esEditar = ($modo ?? '') === 'editar';
        @endphp

        <div class="panel-solicitudes">
            <div class="cabecera-panel">
                <div>
                    <h5>
                        <i class="fa-solid fa-file-pen text-danger me-2"></i>
                        {{ $esEditar ? 'Editar solicitud' : 'Registrar nueva solicitud' }}
                    </h5>
                    <span class="texto-secundario">
                        Complete los campos correspondientes al trámite.
                    </span>
                </div>
            </div>

            <div class="seccion-formulario">
                <form
                    action="{{ $esEditar
                        ? route('solicitudes.update', $solicitud->cod_solicitud)
                        : route('solicitudes.store') }}"
                    method="POST">

                    @csrf

                    @if($esEditar)
                        @method('PUT')
                    @endif

                    <div class="separador-seccion">
                        <i class="fa-solid fa-address-card me-2"></i>
                        Información de la solicitud
                    </div>

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label for="cod_usuario" class="form-label">
                                Usuario solicitante <span class="text-danger">*</span>
                            </label>

                            <select id="cod_usuario" name="cod_usuario"
                                    class="form-select" required>
                                <option value="">Seleccione un usuario</option>

                                @foreach($usuarios ?? [] as $usuario)
                                    <option value="{{ $usuario->cod_usuario }}"
                                        @selected(old('cod_usuario', $solicitud->cod_usuario ?? '') == $usuario->cod_usuario)>
                                        {{ trim(($usuario->paterno ?? '') . ' ' .
                                            ($usuario->materno ?? '') . ' ' .
                                            ($usuario->nombre1 ?? '') . ' ' .
                                            ($usuario->nombre2 ?? '')) }}
                                        @if(!empty($usuario->usuario))
                                            — {{ $usuario->usuario }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="cod_tipo" class="form-label">
                                Tipo de trámite <span class="text-danger">*</span>
                            </label>

                            <select id="cod_tipo" name="cod_tipo"
                                    class="form-select" required>
                                <option value="">Seleccione un tipo de trámite</option>

                                @foreach($tipos ?? [] as $tipo)
                                    <option value="{{ $tipo->cod_tipo }}"
                                        @selected(old('cod_tipo', $solicitud->cod_tipo ?? '') == $tipo->cod_tipo)>
                                        {{ $tipo->nombre_tramite }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="cod_instituto" class="form-label">
                                Instituto <span class="text-danger">*</span>
                            </label>

                            <select id="cod_instituto" name="cod_instituto"
                                    class="form-select" required>
                                <option value="">Seleccione un instituto</option>

                                @foreach($institutos ?? [] as $instituto)
                                    <option value="{{ $instituto->cod_instituto }}"
                                        @selected(old('cod_instituto', $solicitud->cod_instituto ?? '') == $instituto->cod_instituto)>
                                        {{ $instituto->nombre_inst }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="cod_plan" class="form-label">
                                Plan de estudio
                            </label>

                            <select id="cod_plan" name="cod_plan" class="form-select">
                                <option value="">Sin plan de estudio</option>

                                @foreach($planes ?? [] as $plan)
                                    <option value="{{ $plan->cod_plan }}"
                                        @selected(old('cod_plan', $solicitud->cod_plan ?? '') == $plan->cod_plan)>
                                        {{ $plan->nombre_inst ?? '' }}
                                        @if(!empty($plan->nombre_inst))
                                            —
                                        @endif
                                        {{ $plan->nombre_carrera ?? '' }}
                                        @if(!empty($plan->titulo_profesional))
                                            — {{ $plan->titulo_profesional }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if($esEditar)
                            <div class="col-md-6">
                                <label for="cod_estado" class="form-label">
                                    Estado del trámite <span class="text-danger">*</span>
                                </label>

                                <select id="cod_estado" name="cod_estado"
                                        class="form-select" required>
                                    @foreach($estados ?? [] as $estado)
                                        <option value="{{ $estado->cod_estado }}"
                                            @selected(old('cod_estado', $solicitud->cod_estado ?? '') == $estado->cod_estado)>
                                            {{ $estado->nombre_estado }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="activo" class="form-label">
                                    Estado del registro
                                </label>

                                <select id="activo" name="activo" class="form-select">
                                    <option value="1"
                                        @selected(old('activo', $solicitud->activo ?? 1) == 1)>
                                        Activo
                                    </option>
                                    <option value="0"
                                        @selected(old('activo', $solicitud->activo ?? 1) == 0)>
                                        Inactivo
                                    </option>
                                </select>
                            </div>
                        @endif

                        <div class="col-12">
                            <label for="observacion" class="form-label">
                                Observación
                            </label>

                            <textarea id="observacion" name="observacion"
                                      class="form-control" rows="4"
                                      maxlength="250"
                                      placeholder="Ingrese una observación..."
                            >{{ old('observacion', $solicitud->observacion ?? '') }}</textarea>

                            <small class="texto-secundario">
                                Máximo 250 caracteres.
                            </small>
                        </div>

                        <div class="col-12">
                            <hr>

                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <a href="{{ route('solicitudes.index') }}"
                                   class="btn btn-outline-secondary">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn-institucional">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    {{ $esEditar ? 'Actualizar solicitud' : 'Registrar solicitud' }}
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    {{-- HISTORIAL DE SOLICITUD --}}

    @elseif(($modo ?? 'listar') === 'historial')

        <div class="panel-solicitudes">
            <div class="cabecera-panel">
                <div>
                    <h5>
                        <i class="fa-solid fa-clock-rotate-left text-danger me-2"></i>
                        Historial de solicitud
                    </h5>
                    <span class="texto-secundario">
                        Solicitud #{{ $solicitud->cod_solicitud }}
                    </span>
                </div>

                <a href="{{ route('solicitudes.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Volver
                </a>
            </div>

            <div class="seccion-formulario">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <strong>Solicitante</strong>
                        <div>{{ $solicitud->solicitante ?? '—' }}</div>
                    </div>

                    <div class="col-md-6">
                        <strong>Tipo de trámite</strong>
                        <div>{{ $solicitud->nombre_tramite ?? '—' }}</div>
                    </div>

                    <div class="col-md-6">
                        <strong>Instituto</strong>
                        <div>{{ $solicitud->instituto ?? '—' }}</div>
                    </div>

                    <div class="col-md-6">
                        <strong>Estado actual</strong>
                        <div>{{ $solicitud->estado ?? '—' }}</div>
                    </div>
                </div>

                <div class="tabla-contenedor">
                    <table class="table table-hover tabla-solicitudes">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Usuario responsable</th>
                                <th>Observación</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($historial ?? [] as $registro)
                                <tr>
                                    <td>
                                        @if(!empty($registro->fecha))
                                            {{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y H:i') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <span class="estado estado-revision">
                                            {{ $registro->nombre_estado ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ $registro->usuario ?? '—' }}</td>
                                    <td>{{ $registro->observacion ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="vacio">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                            <strong>No existe historial registrado.</strong>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- LISTADO PRINCIPAL --}}

    @else

        @php
            $listaSolicitudes = collect($solicitudes ?? []);

            $totalSolicitudes = $listaSolicitudes->count();

            $pendientes = $listaSolicitudes->filter(function ($item) {
                return mb_strtolower(trim($item->estado ?? '')) === 'pendiente';
            })->count();

            $enRevision = $listaSolicitudes->filter(function ($item) {
                return in_array(
                    mb_strtolower(trim($item->estado ?? '')),
                    ['en revisión', 'en revision'],
                    true
                );
            })->count();

            $aprobadas = $listaSolicitudes->filter(function ($item) {
                return mb_strtolower(trim($item->estado ?? '')) === 'aprobado';
            })->count();
        @endphp

        {{-- INDICADORES --}}

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="tarjeta-estadistica">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icono-estadistica">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div>
                            <div class="numero-estadistica">{{ $totalSolicitudes }}</div>
                            <div class="texto-estadistica">Total de solicitudes</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="tarjeta-estadistica">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icono-estadistica">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <div class="numero-estadistica">{{ $pendientes }}</div>
                            <div class="texto-estadistica">Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="tarjeta-estadistica">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icono-estadistica">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <div>
                            <div class="numero-estadistica">{{ $enRevision }}</div>
                            <div class="texto-estadistica">En revisión</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="tarjeta-estadistica">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icono-estadistica">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <div class="numero-estadistica">{{ $aprobadas }}</div>
                            <div class="texto-estadistica">Aprobadas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLA DE SOLICITUDES --}}

        <div class="panel-solicitudes">
            <div class="cabecera-panel">
                <div>
                    <h5>
                        <i class="fa-solid fa-list-check text-danger me-2"></i>
                        Solicitudes registradas
                    </h5>
                    <span class="texto-secundario">
                        Consulta y seguimiento de las solicitudes de trámite.
                    </span>
                </div>

                <span class="badge text-bg-light border">
                    {{ $totalSolicitudes }} registros
                </span>
            </div>

            {{-- FILTROS --}}

            <div class="p-3 border-bottom no-imprimir">
                <div class="row g-3">
                    <div class="col-12 col-lg-5">
                        <label for="buscarSolicitud" class="form-label">
                            Buscar solicitud
                        </label>

                        <div class="buscador-solicitudes">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="search" id="buscarSolicitud"
                                   class="form-control"
                                   placeholder="Solicitante, usuario, instituto o trámite...">
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="filtroEstado" class="form-label">
                            Filtrar por estado
                        </label>

                        <select id="filtroEstado" class="form-select">
                            <option value="">Todos los estados</option>
                            @foreach($estados ?? [] as $estado)
                                <option value="{{ mb_strtolower(trim($estado->nombre_estado)) }}">
                                    {{ $estado->nombre_estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="filtroTipo" class="form-label">
                            Filtrar por tipo de trámite
                        </label>

                        <select id="filtroTipo" class="form-select">
                            <option value="">Todos los tipos</option>

                            @foreach($listaSolicitudes->pluck('nombre_tramite')->filter()->unique()->sort() as $tipo)
                                <option value="{{ mb_strtolower(trim($tipo)) }}">
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" id="limpiarFiltros"
                            class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left me-1"></i>
                        Limpiar filtros
                    </button>

                    <span id="contadorFiltrado" class="texto-secundario ms-2">
                        {{ $totalSolicitudes }} solicitudes
                    </span>
                </div>
            </div>

            <div class="tabla-contenedor">
                <table class="table table-hover tabla-solicitudes"
                       id="tablaSolicitudes">
                    <thead>
                        <tr>
                            <th>N.º</th>
                            <th>Fecha</th>
                            <th>Solicitante</th>
                            <th>Tipo de trámite</th>
                            <th>Instituto</th>
                            <th>Carrera</th>
                            <th>Estado</th>
                            <th class="text-center no-imprimir">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($listaSolicitudes as $index => $item)
                            @php
                                $estadoNormalizado = mb_strtolower(trim($item->estado ?? ''));

                                $claseEstado = match($estadoNormalizado) {
                                    'pendiente' => 'estado-pendiente',
                                    'en revisión', 'en revision' => 'estado-revision',
                                    'aprobado', 'aprobada' => 'estado-aprobado',
                                    'rechazado', 'rechazada' => 'estado-rechazado',
                                    'observado', 'observada' => 'estado-observado',
                                    'finalizado', 'finalizada' => 'estado-finalizado',
                                    default => 'estado-pendiente',
                                };
                            @endphp

                            <tr
                                data-estado="{{ $estadoNormalizado }}"
                                data-tipo="{{ mb_strtolower(trim($item->nombre_tramite ?? '')) }}">

                                <td>
                                    <strong>{{ $item->cod_solicitud }}</strong>
                                </td>

                                <td>
                                    @if(!empty($item->fecha_solicitud))
                                        {{ \Carbon\Carbon::parse($item->fecha_solicitud)->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    <strong>{{ $item->solicitante ?? 'Sin nombre' }}</strong>

                                    @if(!empty($item->usuario))
                                        <div class="texto-secundario">
                                            <i class="fa-solid fa-user me-1"></i>
                                            {{ $item->usuario }}
                                        </div>
                                    @endif
                                </td>

                                <td>{{ $item->nombre_tramite ?? '—' }}</td>

                                <td>{{ $item->instituto ?? '—' }}</td>

                                <td>{{ $item->carrera ?? '—' }}</td>

                                <td>
                                    <span class="estado {{ $claseEstado }}">
                                        {{ $item->estado ?? 'Sin estado' }}
                                    </span>
                                </td>

                                <td class="text-center no-imprimir">
                                    @if(session('cod_acceso') == 2)
                                        <button type="button"
                                                class="accion accion-revision"
                                                title="Revisión técnica"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalRevision{{ $item->cod_solicitud }}">
                                            <i class="fa-solid fa-user-check"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('informes.plantilla') }}"
                                       class="accion"
                                       title="Descargar plantilla de informe Word"
                                       aria-label="Descargar plantilla de informe Word">
                                        <i class="fa-solid fa-file-word"></i>
                                    </a>

                                    <a href="{{ route('solicitudes.edit', $item->cod_solicitud) }}"
                                       class="accion accion-editar"
                                       title="Editar solicitud">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <a href="{{ route('solicitudes.historial', $item->cod_solicitud) }}"
                                       class="accion accion-historial"
                                       title="Ver historial">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </a>

                                    <button type="button"
                                            class="accion accion-eliminar"
                                            title="Desactivar solicitud"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar{{ $item->cod_solicitud }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="vacio">
                                        <i class="fa-solid fa-folder-open"></i>
                                        <strong>No existen solicitudes registradas.</strong>
                                        <p class="mt-2 mb-3">
                                            Puede registrar una nueva solicitud utilizando el botón superior.
                                        </p>
                                        <a href="{{ route('solicitudes.create') }}"
                                           >
                                            <i class="fa-solid fa-plus"></i>
                                            Nueva solicitud
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="sinResultados" class="vacio" style="display:none;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <strong>No se encontraron solicitudes con esos filtros.</strong>
                <p class="mt-2 mb-0">Modifique los criterios de búsqueda.</p>
            </div>
        </div>

    @endif

    {{-- MODALES PARA EL LISTADO --}}

    @if(($modo ?? 'listar') === 'listar')
        @foreach($listaSolicitudes ?? collect() as $item)

            {{-- MODAL DE REVISIÓN TÉCNICA --}}

            @if(session('cod_acceso') == 2)
                <div class="modal fade"
                     id="modalRevision{{ $item->cod_solicitud }}"
                     tabindex="-1"
                     aria-labelledby="tituloRevision{{ $item->cod_solicitud }}"
                     aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"
                                    id="tituloRevision{{ $item->cod_solicitud }}">
                                    <i class="fa-solid fa-user-check me-2"></i>
                                    Revisión técnica
                                </h5>

                                <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                            </div>

                            <form action="{{ route('solicitudes.revisionTecnica', $item->cod_solicitud) }}"
                                  method="POST">
                                @csrf

                                <div class="modal-body">
                                    <div class="alert alert-light border">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Número de solicitud:</strong>
                                                <div>#{{ $item->cod_solicitud }}</div>
                                            </div>

                                            <div class="col-md-6">
                                                <strong>Fecha:</strong>
                                                <div>
                                                    @if(!empty($item->fecha_solicitud))
                                                        {{ \Carbon\Carbon::parse($item->fecha_solicitud)->format('d/m/Y') }}
                                                    @else
                                                        —
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <strong>Solicitante:</strong>
                                                <div>{{ $item->solicitante ?? '—' }}</div>
                                            </div>

                                            <div class="col-md-6">
                                                <strong>Tipo de trámite:</strong>
                                                <div>{{ $item->nombre_tramite ?? '—' }}</div>
                                            </div>

                                            <div class="col-md-6">
                                                <strong>Instituto:</strong>
                                                <div>{{ $item->instituto ?? '—' }}</div>
                                            </div>

                                            <div class="col-md-6">
                                                <strong>Estado actual:</strong>
                                                <div>
                                                    <span class="estado estado-revision">
                                                        {{ $item->estado ?? '—' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="decision{{ $item->cod_solicitud }}"
                                               class="form-label">
                                            Decisión de la revisión
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select name="decision"
                                                id="decision{{ $item->cod_solicitud }}"
                                                class="form-select" required>
                                            <option value="">Seleccione una decisión</option>
                                            <option value="APROBADO">Aprobar solicitud</option>
                                            <option value="OBSERVADO">Observar / solicitar subsanación</option>
                                            <option value="RECHAZADO">Rechazar solicitud</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="informe{{ $item->cod_solicitud }}"
                                               class="form-label">
                                            Informe técnico
                                        </label>

                                        <textarea name="informe"
                                                  id="informe{{ $item->cod_solicitud }}"
                                                  class="form-control"
                                                  rows="4"
                                                  maxlength="2000"
                                                  placeholder="Ingrese el informe técnico..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="observacionRevision{{ $item->cod_solicitud }}"
                                               class="form-label">
                                            Observación
                                        </label>

                                        <textarea name="observacion"
                                                  id="observacionRevision{{ $item->cod_solicitud }}"
                                                  class="form-control"
                                                  rows="3"
                                                  maxlength="1000"
                                                  placeholder="Indique observaciones o aspectos por subsanar..."></textarea>
                                    </div>

                                    <div class="alert alert-warning mb-0">
                                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                        Verifique la decisión antes de registrar la revisión.
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">
                                        Cancelar
                                    </button>

                                    <button type="submit" class="btn-institucional">
                                        <i class="fa-solid fa-check"></i>
                                        Registrar revisión
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- MODAL DE ELIMINACIÓN / DESACTIVACIÓN --}}

            <div class="modal fade"
                 id="modalEliminar{{ $item->cod_solicitud }}"
                 tabindex="-1"
                 aria-labelledby="tituloEliminar{{ $item->cod_solicitud }}"
                 aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"
                                id="tituloEliminar{{ $item->cod_solicitud }}">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                Desactivar solicitud
                            </h5>

                            <button type="button" class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body text-center py-4">
                            <i class="fa-solid fa-trash-can text-danger"
                               style="font-size:45px;"></i>

                            <h5 class="mt-3">¿Desea desactivar esta solicitud?</h5>

                            <p class="text-muted mb-0">
                                Solicitud
                                <strong>#{{ $item->cod_solicitud }}</strong>.
                                La operación dependerá de la lógica implementada en el controlador.
                            </p>
                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <form action="{{ route('solicitudes.destroy', $item->cod_solicitud) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">
                                    <i class="fa-solid fa-trash me-1"></i>
                                    Confirmar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buscador = document.getElementById('buscarSolicitud');
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroTipo = document.getElementById('filtroTipo');
    const botonLimpiar = document.getElementById('limpiarFiltros');
    const contador = document.getElementById('contadorFiltrado');
    const sinResultados = document.getElementById('sinResultados');
    const filas = Array.from(
        document.querySelectorAll('#tablaSolicitudes tbody tr[data-estado]')
    );

    function normalizar(valor) {
        return (valor || '').toString().toLocaleLowerCase('es').trim();
    }

    function filtrarSolicitudes() {
        if (!buscador || !filtroEstado || !filtroTipo) {
            return;
        }

        const texto = normalizar(buscador.value);
        const estadoSeleccionado = normalizar(filtroEstado.value);
        const tipoSeleccionado = normalizar(filtroTipo.value);
        let visibles = 0;

        filas.forEach(function (fila) {
            const contenido = normalizar(fila.innerText);
            const estado = normalizar(fila.dataset.estado);
            const tipo = normalizar(fila.dataset.tipo);

            const coincideTexto = contenido.includes(texto);
            const coincideEstado =
                estadoSeleccionado === '' || estado === estadoSeleccionado;
            const coincideTipo =
                tipoSeleccionado === '' || tipo === tipoSeleccionado;

            const mostrar = coincideTexto && coincideEstado && coincideTipo;
            fila.style.display = mostrar ? '' : 'none';

            if (mostrar) {
                visibles++;
            }
        });

        if (contador) {
            contador.textContent =
                visibles + (visibles === 1 ? ' solicitud encontrada' : ' solicitudes encontradas');
        }

        if (sinResultados) {
            sinResultados.style.display =
                filas.length > 0 && visibles === 0 ? 'block' : 'none';
        }
    }

    if (buscador) {
        buscador.addEventListener('input', filtrarSolicitudes);
    }

    if (filtroEstado) {
        filtroEstado.addEventListener('change', filtrarSolicitudes);
    }

    if (filtroTipo) {
        filtroTipo.addEventListener('change', filtrarSolicitudes);
    }

    if (botonLimpiar) {
        botonLimpiar.addEventListener('click', function () {
            if (buscador) buscador.value = '';
            if (filtroEstado) filtroEstado.value = '';
            if (filtroTipo) filtroTipo.value = '';
            filtrarSolicitudes();
        });
    }

    filtrarSolicitudes();
});
</script>

@endsection
