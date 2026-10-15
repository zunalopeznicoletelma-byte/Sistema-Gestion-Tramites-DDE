
@extends('layouts.app')

@section('title', 'Institutos - DDE Cochabamba')

@section('content')

@php
    $institutos = $institutos ?? collect([]);
    $municipios = $municipios ?? collect([]);
    $dependencias = $dependencias ?? ['Fiscal', 'Privado', 'Convenio'];

    $buscar = $buscar ?? request('buscar', '');
    $dependenciaSeleccionada = $dependenciaSeleccionada ?? request('dependencia', '');
    $municipioSeleccionado = $municipioSeleccionado ?? request('municipio', '');

    $totalInstitutos = $totalInstitutos ?? $institutos->count();
    $totalFiscales = $totalFiscales ?? $institutos->where('dependencia', 'Fiscal')->count();
    $totalPrivados = $totalPrivados ?? $institutos->where('dependencia', 'Privado')->count();
    $totalConvenio = $totalConvenio ?? $institutos->where('dependencia', 'Convenio')->count();
@endphp

<style>
    .institutos-page {
        --inst-rojo: #b5121b;
        --inst-rojo-oscuro: #8f0d14;
        --inst-rojo-suave: #fff1f2;
        --inst-fondo: #f5f6f8;
        --inst-gris: #6b7280;
        --inst-borde: #eeeeee;
        color: #343a40;
        font-family: "Segoe UI", Arial, sans-serif;
    }

    .institutos-page .encabezado-modulo {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }

    .institutos-page .titulo-modulo {
        margin: 0 0 6px;
        color: var(--inst-rojo-oscuro);
        font-size: 25px;
        font-weight: 750;
    }

    .institutos-page .subtitulo-modulo {
        margin: 0;
        color: var(--inst-gris);
        font-size: 14px;
    }

    .institutos-page .tarjeta-estadistica {
        height: 100%;
        padding: 22px;
        background: #fff;
        border: 1px solid var(--inst-borde);
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,.08);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .institutos-page .tarjeta-estadistica:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,.10);
    }

    .institutos-page .icono-estadistica {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--inst-rojo-suave);
        color: var(--inst-rojo);
        font-size: 24px;
    }

    .institutos-page .numero-estadistica {
        color: #343a40;
        font-size: 27px;
        line-height: 1.2;
        font-weight: 750;
    }

    .institutos-page .texto-estadistica {
        margin-top: 5px;
        color: var(--inst-gris);
        font-size: 13px;
    }

    .institutos-page .panel-institutos {
        margin-top: 24px;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--inst-borde);
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,.08);
    }

    .institutos-page .cabecera-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--inst-borde);
    }

    .institutos-page .cabecera-panel h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 750;
    }

    .institutos-page .texto-secundario {
        color: var(--inst-gris);
        font-size: 13px;
    }

    .institutos-page .filtros-institutos {
        display: grid;
        grid-template-columns: minmax(200px, 2fr) repeat(2, minmax(150px, 1fr)) auto;
        align-items: end;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--inst-borde);
    }

    .institutos-page .form-label {
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 700;
    }

    .institutos-page .form-control,
    .institutos-page .form-select {
        min-height: 43px;
        border-color: #e1e4e8;
        border-radius: 8px;
        font-size: 14px;
    }

    .institutos-page .form-control:focus,
    .institutos-page .form-select:focus {
        border-color: var(--inst-rojo);
        box-shadow: 0 0 0 .2rem rgba(181,18,27,.12);
    }

    .institutos-page .btn-institucional {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border: 1px solid var(--inst-rojo);
        border-radius: 8px;
        background: var(--inst-rojo);
        color: #fff;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
        transition: background .2s ease;
    }

    .institutos-page .btn-institucional:hover {
        border-color: var(--inst-rojo-oscuro);
        background: var(--inst-rojo-oscuro);
        color: #fff;
    }

    .institutos-page .btn-limpiar {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-height: 42px;
        padding: 9px 14px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        color: #495057;
        font-size: 13px;
        text-decoration: none;
    }

    .institutos-page .btn-limpiar:hover {
        background: #f5f6f8;
    }

    .institutos-page .tabla-contenedor {
        width: 100%;
        overflow-x: auto;
    }

    .institutos-page .tabla-institutos {
        min-width: 750px;
        margin: 0;
        vertical-align: middle;
    }

    .institutos-page .tabla-institutos thead th {
        padding: 14px 16px;
        border: none;
        background: var(--inst-rojo);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .institutos-page .tabla-institutos tbody td {
        padding: 15px 16px;
        border-color: #f0f0f0;
        font-size: 13px;
        vertical-align: middle;
    }

    .institutos-page .tabla-institutos tbody tr:hover > * {
        background: #fff8f8;
    }

    .institutos-page .badge-dependencia {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        background: var(--inst-rojo-suave);
        color: var(--inst-rojo);
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .institutos-page .estado-vacio {
        padding: 45px 20px !important;
        color: var(--inst-gris);
        text-align: center;
    }

    .institutos-page .estado-vacio i {
        display: block;
        margin-bottom: 12px;
        color: #c7cbd1;
        font-size: 42px;
    }

    .institutos-page .fuente-institutos {
        padding: 15px 20px;
        border-top: 1px solid var(--inst-borde);
        color: var(--inst-gris);
        font-size: 12px;
        line-height: 1.6;
    }

    @media (max-width: 1100px) {
        .institutos-page .filtros-institutos {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .institutos-page .titulo-modulo {
            font-size: 21px;
        }

        .institutos-page .cabecera-panel {
            padding: 17px;
        }

        .institutos-page .filtros-institutos {
            grid-template-columns: 1fr;
            padding: 17px;
        }
    }

    @media print {
        

        .institutos-page .tarjeta-estadistica,
        .institutos-page .panel-institutos {
            box-shadow: none;
        }

        .institutos-page .tabla-institutos {
            min-width: 0;
        }

        .institutos-page .tabla-institutos thead th {
            background: #eee !important;
            color: #000;
        }
    }
</style>

<div class="institutos-page">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="encabezado-modulo">
        <div>
            <h2 class="titulo-modulo">
                <i class="bi bi-building me-2"></i>
                Directorio de institutos
            </h2>
            <p class="subtitulo-modulo">
                Consulta de institutos fiscales, privados y de convenio de Cochabamba.
            </p>
        </div>

        
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tarjeta-estadistica">
                <div class="d-flex align-items-center gap-3">
                    <div class="icono-estadistica"><i class="bi bi-buildings"></i></div>
                    <div>
                        <div class="numero-estadistica">{{ $totalInstitutos }}</div>
                        <div class="texto-estadistica">Total de institutos</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tarjeta-estadistica">
                <div class="d-flex align-items-center gap-3">
                    <div class="icono-estadistica"><i class="bi bi-bank"></i></div>
                    <div>
                        <div class="numero-estadistica">{{ $totalFiscales }}</div>
                        <div class="texto-estadistica">Fiscales</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tarjeta-estadistica">
                <div class="d-flex align-items-center gap-3">
                    <div class="icono-estadistica"><i class="bi bi-building"></i></div>
                    <div>
                        <div class="numero-estadistica">{{ $totalPrivados }}</div>
                        <div class="texto-estadistica">Privados</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tarjeta-estadistica">
                <div class="d-flex align-items-center gap-3">
                    <div class="icono-estadistica"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="numero-estadistica">{{ $totalConvenio }}</div>
                        <div class="texto-estadistica">De convenio</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="panel-institutos">
        <div class="cabecera-panel">
            <div>
                <h5><i class="bi bi-list-ul text-danger me-2"></i>Institutos del directorio</h5>
                <span class="texto-secundario">Consulta y filtrado de los registros disponibles.</span>
            </div>
            <span class="badge text-bg-light border">{{ $institutos->count() }} registros</span>
        </div>

        <form method="GET" action="{{ url('/institutos') }}" class="filtros-institutos no-imprimir">
            <div>
                <label for="buscarInstituto" class="form-label">Buscar instituto</label>
                <input
                    id="buscarInstituto"
                    type="search"
                    name="buscar"
                    class="form-control"
                    placeholder="Nombre del instituto..."
                    value="{{ $buscar }}"
                >
            </div>

            <div>
                <label for="filtroDependencia" class="form-label">Dependencia</label>
                <select id="filtroDependencia" name="dependencia" class="form-select">
                    <option value="">Todas las dependencias</option>
                    @foreach($dependencias as $dependencia)
                        <option value="{{ $dependencia }}" @selected($dependenciaSeleccionada == $dependencia)>
                            {{ $dependencia }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filtroMunicipio" class="form-label">Municipio</label>
                <select id="filtroMunicipio" name="municipio" class="form-select">
                    <option value="">Todos los municipios</option>
                    @foreach($municipios as $municipio)
                        <option value="{{ $municipio }}" @selected($municipioSeleccionado == $municipio)>
                            {{ $municipio }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="btn-institucional">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ url('/institutos') }}" class="btn-limpiar">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar
                </a>
            </div>
        </form>

        <div class="tabla-contenedor">
            <table class="table table-hover tabla-institutos">
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Nombre del instituto</th>
                        <th>Dependencia</th>
                        <th>Municipio</th>
                        <th>Dirección</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($institutos as $instituto)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $instituto['nombre'] ?? 'Sin nombre' }}</td>
                            <td>
                                <span class="badge-dependencia">
                                    {{ $instituto['dependencia'] ?? 'No especificada' }}
                                </span>
                            </td>
                            <td>{{ $instituto['municipio'] ?? 'No disponible' }}</td>
                            <td>{{ $instituto['direccion'] ?? 'No disponible' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="estado-vacio">
                                <i class="bi bi-building-exclamation"></i>
                                <strong>No hay institutos para mostrar.</strong>
                                <div class="mt-2">Pruebe modificando los filtros de búsqueda.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="fuente-institutos">
            <i class="bi bi-info-circle me-1"></i>
            Fuente prevista: Guía de Institutos Técnicos, Tecnológicos y Artísticos del Ministerio de Educación.
            Los datos se muestran conforme a la información disponible en el sistema.
        </div>
    </section>

</div>
@endsection
