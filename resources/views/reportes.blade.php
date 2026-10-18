
@extends('layouts.app')

@section('title', 'Reportes Administrativos - DDE Cochabamba')

@section('content')

@php
    $estadosReporte = collect($solicitudesPorEstado ?? []);
    $tiposReporte = collect($solicitudesPorTipo ?? []);
    $mesesReporte = collect($solicitudesPorMes ?? []);
    $solicitudesReporte = collect($solicitudes ?? []);

    $totalReporte = (int) ($totalFiltrado ?? $solicitudesReporte->count());

    $cantidadEstado = function ($nombres) use ($estadosReporte) {
        return (int) $estadosReporte
            ->filter(function ($item) use ($nombres) {
                return in_array(
                    mb_strtolower(trim($item->nombre_estado ?? ''), 'UTF-8'),
                    array_map(
                        fn ($nombre) => mb_strtolower($nombre, 'UTF-8'),
                        $nombres
                    ),
                    true
                );
            })
            ->sum('cantidad');
    };

    $cantidadPendiente = $cantidadEstado(['Pendiente']);
    $cantidadRevision = $cantidadEstado(['En revisión', 'En revision']);
    $cantidadAprobado = $cantidadEstado(['Aprobado']);
    $cantidadRechazado = $cantidadEstado(['Rechazado']);

    $porcentaje = function ($cantidad) use ($totalReporte) {
        return $totalReporte > 0
            ? round(($cantidad / $totalReporte) * 100, 1)
            : 0;
    };

    $coloresEstados = [
        '#c8102e',
        '#e5a000',
        '#2471a3',
        '#198754',
        '#6c757d',
        '#7d3c98',
        '#117a65',
        '#d35400',
    ];

    $maximoTipo = max(1, (int) $tiposReporte->max('cantidad'));
    $maximoMes = max(1, (int) $mesesReporte->max('cantidad'));

    $fechaInicio = request('fecha_inicio');
    $fechaFin = request('fecha_fin');

    $periodoTexto = $periodoReporte ?? 'Todos los períodos';

    if ($fechaInicio && $fechaFin) {
        $periodoTexto = \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y')
            . ' al '
            . \Carbon\Carbon::parse($fechaFin)->format('d/m/Y');
    } elseif ($fechaInicio) {
        $periodoTexto = 'Desde '
            . \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y');
    } elseif ($fechaFin) {
        $periodoTexto = 'Hasta '
            . \Carbon\Carbon::parse($fechaFin)->format('d/m/Y');
    }
@endphp

<style>
    .reportes-wrapper {
        color: #343a40;
        font-size: 14px;
    }

    .reporte-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .reporte-header h2 {
        margin: 0 0 6px;
        color: #c8102e;
        font-size: 25px;
        font-weight: 700;
    }

    .reporte-header p {
        margin: 0;
        color: #777;
        font-size: 14px;
    }

    .btn-reporte {
        border: none;
        border-radius: 8px;
        padding: 11px 17px;
        background: #c8102e;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-reporte:hover {
        background: #8f0d14;
        color: #fff;
    }

    .btn-secundario {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #555;
        background: #fff;
        text-decoration: none;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-secundario:hover {
        background: #f5f6f8;
        color: #333;
    }

    .estadisticas {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 25px;
    }

    .estadistica-card {
        background: #fff;
        border-radius: 12px;
        padding: 18px;
        border: 1px solid #eee;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .estadistica-icono {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff0f2;
        color: #c8102e;
        font-size: 21px;
    }

    .estadistica-card small {
        display: block;
        color: #777;
        font-size: 12px;
        margin-bottom: 6px;
    }

    .estadistica-card h3 {
        margin: 0;
        font-size: 25px;
        color: #343a40;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .estadistica-porcentaje {
        display: block;
        margin-top: 6px;
        color: #777;
        font-size: 11px;
    }

    .estadistica-porcentaje strong {
        color: #c8102e;
    }

    .reporte-panel {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 23px;
        margin-bottom: 24px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .04);
        min-width: 0;
    }

    .panel-titulo {
        color: #343a40;
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .panel-descripcion {
        margin: 0 0 20px;
        color: #777;
        font-size: 13px;
    }

    .filtros {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        align-items: end;
    }

    .campo-reporte label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #555;
        margin-bottom: 8px;
    }

    .campo-reporte input,
    .campo-reporte select {
        width: 100%;
        min-height: 43px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 9px 12px;
        background: #fff;
        color: #333;
        font-family: inherit;
    }

    .acciones-filtro {
        grid-column: 1 / -1;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 5px;
    }

    .resumen-periodo {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 24px;
        padding: 13px 16px;
        margin-bottom: 22px;
        background: #fff6f7;
        border: 1px solid #f0d6da;
        border-radius: 9px;
        color: #555;
        font-size: 12px;
    }

    .resumen-periodo strong {
        color: #8f0d14;
    }

    .graficas-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
        align-items: stretch;
    }

    .grafica-panel {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 23px;
        min-width: 0;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .04);
    }

    .grafica-circular-contenedor {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
        padding: 12px 0;
    }

    .grafica-circular {
        width: 190px;
        max-width: 100%;
        height: auto;
        flex-shrink: 0;
    }

    .grafica-circular .anillo-base {
        fill: none;
        stroke: #f0f0f0;
        stroke-width: 7;
    }

    .grafica-circular .segmento {
        fill: none;
        stroke-width: 7;
        stroke-linecap: butt;
        transform: rotate(-90deg);
        transform-origin: 21px 21px;
    }

    .grafica-centro-numero {
        font-size: 7px;
        font-weight: 700;
        fill: #343a40;
        text-anchor: middle;
    }

    .grafica-centro-texto {
        font-size: 3px;
        fill: #777;
        text-anchor: middle;
    }

    .leyenda-grafica {
        flex: 1;
        min-width: 150px;
    }

    .leyenda-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 12px;
        color: #555;
    }

    .leyenda-color {
        width: 11px;
        height: 11px;
        min-width: 11px;
        border-radius: 3px;
    }

    .leyenda-nombre {
        flex: 1;
        overflow-wrap: anywhere;
    }

    .leyenda-valor {
        font-weight: 700;
        color: #343a40;
        white-space: nowrap;
    }

    .mensaje-sin-datos {
        padding: 30px 15px;
        text-align: center;
        color: #888;
        font-size: 13px;
        background: #fafafa;
        border: 1px dashed #ddd;
        border-radius: 8px;
    }

    .mensaje-sin-datos i {
        display: block;
        font-size: 27px;
        color: #c8102e;
        margin-bottom: 8px;
    }

    .grafica-barras {
        display: flex;
        flex-direction: column;
        gap: 19px;
        padding-top: 12px;
    }

    .barra-item {
        min-width: 0;
    }

    .barra-encabezado {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 7px;
        font-size: 12px;
    }

    .barra-nombre {
        color: #555;
        overflow-wrap: anywhere;
    }

    .barra-cantidad {
        color: #8f0d14;
        font-weight: 700;
        white-space: nowrap;
    }

    .barra-fondo {
        width: 100%;
        height: 13px;
        overflow: hidden;
        border-radius: 5px;
        background: #f0f0f0;
    }

    .barra-progreso {
        height: 100%;
        min-width: 0;
        border-radius: 5px;
        background: #c8102e;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    .barra-porcentaje {
        margin-top: 5px;
        text-align: right;
        font-size: 11px;
        color: #777;
    }

    .tabla-contenedor {
        width: 100%;
        overflow-x: auto;
    }

    .tabla-reportes {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .tabla-reportes thead {
        background: #fff0f2;
        display: table-header-group;
    }

    .tabla-reportes th {
        color: #8f0d14;
        text-align: left;
        padding: 12px 10px;
        white-space: nowrap;
        border-bottom: 1px solid #f0d6da;
    }

    .tabla-reportes td {
        padding: 11px 10px;
        border-bottom: 1px solid #eee;
        vertical-align: top;
        overflow-wrap: anywhere;
    }

    .tabla-reportes tbody tr {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .tabla-reportes tbody tr:hover {
        background: #fffafb;
    }

    .estado-etiqueta {
        display: inline-block;
        border-radius: 20px;
        padding: 5px 9px;
        background: #f0f0f0;
        color: #555;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    .estado-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .estado-revision {
        background: #cfe2ff;
        color: #084298;
    }

    .estado-observado {
        background: #f8d7da;
        color: #842029;
    }

    .estado-aprobado,
    .estado-finalizado {
        background: #d1e7dd;
        color: #0f5132;
    }

    .estado-rechazado {
        background: #e2e3e5;
        color: #41464b;
    }

    .tabla-vacia {
        text-align: center;
        padding: 35px !important;
        color: #888;
    }

    .tabla-vacia i {
        display: block;
        font-size: 30px;
        margin-bottom: 10px;
        color: #c8102e;
    }

    .reporte-pie {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
        color: #777;
        font-size: 12px;
    }

    .reporte-pie strong {
        color: #8f0d14;
    }

    .reporte-institucional-print {
        display: none;
    }

    @media (max-width: 1250px) {
        .estadisticas {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .graficas-grid {
            grid-template-columns: 1fr;
        }

        .estadisticas {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .estadisticas,
        .filtros {
            grid-template-columns: 1fr;
        }

        .reporte-panel,
        .grafica-panel {
            padding: 16px;
        }

        .reporte-header h2 {
            font-size: 21px;
        }

        .acciones-filtro {
            grid-column: auto;
        }
    }

    @page {
        size: A4 landscape;
        margin: 12mm;
    }

    @media print {
        html,
        body {
            background: #fff !important;
            color: #222 !important;
            font-size: 10px !important;
        }

        .sidebar,
        .barra-superior,
        .reporte-header .acciones-header,
        .panel-filtros,
        .no-imprimir,
        nav,
        .btn-reporte,
        .btn-secundario {
            display: none !important;
        }

        .contenido-principal {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .contenido {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .reportes-wrapper {
            width: 100% !important;
            margin: 0 !important;
        }

        .reporte-institucional-print {
            display: block !important;
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid #c8102e;
        }

        .reporte-institucional-print h1 {
            color: #8f0d14 !important;
            font-size: 17px;
            margin: 0 0 4px;
        }

        .reporte-institucional-print h2 {
            font-size: 12px;
            margin: 0 0 5px;
        }

        .reporte-institucional-print p {
            margin: 3px 0;
            color: #555;
            font-size: 9px;
        }

        .reporte-header {
            margin-bottom: 12px;
        }

        .reporte-header h2 {
            font-size: 17px;
            color: #8f0d14 !important;
        }

        .reporte-header p {
            font-size: 9px;
        }

        .estadisticas {
            grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
            gap: 7px;
            margin-bottom: 12px;
        }

        .estadistica-card {
            padding: 10px;
            gap: 7px;
            border-radius: 5px;
            box-shadow: none !important;
            border: 1px solid #ddd;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .estadistica-icono {
            width: 28px;
            height: 28px;
            min-width: 28px;
            font-size: 13px;
        }

        .estadistica-card small {
            font-size: 8px;
            margin-bottom: 4px;
        }

        .estadistica-card h3 {
            font-size: 17px;
        }

        .estadistica-porcentaje {
            font-size: 8px;
        }

        .resumen-periodo {
            padding: 7px 9px;
            margin-bottom: 12px;
            font-size: 8px;
            gap: 5px 15px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .graficas-grid {
            grid-template-columns: 1fr 1fr !important;
            gap: 12px;
            margin-bottom: 12px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .grafica-panel,
        .reporte-panel {
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: none !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .panel-titulo {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .panel-descripcion {
            font-size: 8px;
            margin-bottom: 8px;
        }

        .grafica-circular-contenedor {
            gap: 10px;
            padding: 5px 0;
            flex-wrap: nowrap;
        }

        .grafica-circular {
            width: 120px;
            max-width: 120px;
        }

        .leyenda-item {
            margin-bottom: 5px;
            font-size: 8px;
        }

        .leyenda-color {
            width: 8px;
            height: 8px;
            min-width: 8px;
        }

        .grafica-barras {
            gap: 10px;
            padding-top: 6px;
        }

        .barra-encabezado {
            font-size: 8px;
            margin-bottom: 4px;
        }

        .barra-fondo {
            height: 8px;
        }

        .barra-porcentaje {
            font-size: 8px;
            margin-top: 2px;
        }

        .tabla-contenedor {
            overflow: visible !important;
        }

        .tabla-reportes {
            width: 100% !important;
            font-size: 8px;
        }

        .tabla-reportes th {
            padding: 7px 5px;
            white-space: normal;
            background: #fff0f2 !important;
            color: #8f0d14 !important;
        }

        .tabla-reportes td {
            padding: 6px 5px;
        }

        .estado-etiqueta {
            font-size: 7px;
            padding: 3px 5px;
            white-space: normal;
        }

        .reporte-pie {
            font-size: 8px;
            margin-top: 10px;
        }

        .barra-progreso,
        .estado-etiqueta,
        .estadistica-icono,
        .grafica-circular .segmento {
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
        }

        a {
            color: inherit !important;
            text-decoration: none !important;
        }
    }
</style>

<div class="reportes-wrapper">

    {{-- ENCABEZADO PARA IMPRESIÓN --}}

    <div class="reporte-institucional-print">
        <h1>MINISTERIO DE EDUCACIÓN</h1>
        <h2>Dirección Departamental de Educación Cochabamba</h2>
        <p>Área de Educación Superior de Formación Profesional</p>
        <p><strong>REPORTE ADMINISTRATIVO DE SOLICITUDES</strong></p>
        <p>Período: {{ $periodoTexto }}</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- ENCABEZADO DE PANTALLA --}}

    <div class="reporte-header">
        <div>
            <h2>
                <i class="bi bi-bar-chart-line"></i>
                Reportes administrativos
            </h2>
            <p>
                Consulta, estadísticas y análisis de las solicitudes de trámites registrados en el sistema.
            </p>
        </div>

        <div class="acciones-header">
            <button type="button" class="btn-reporte" onclick="window.print()">
                <i class="bi bi-printer"></i>
                Imprimir reporte
            </button>
        </div>
    </div>

    {{-- FILTROS --}}

    <div class="reporte-panel panel-filtros">
        <h3 class="panel-titulo">
            <i class="bi bi-funnel"></i>
            Filtrar reportes
        </h3>

        <p class="panel-descripcion">
            Selecciona el estado y el período que deseas consultar.
        </p>

        <form method="GET" action="{{ route('reportes') }}" class="filtros">

            <div class="campo-reporte">
                <label for="estado">Estado de la solicitud</label>

                <select name="estado" id="estado">
                    <option value="">Todos los estados</option>

                    @foreach ($estados ?? [] as $estado)
                        <option
                            value="{{ $estado->cod_estado }}"
                            @selected((string) request('estado') === (string) $estado->cod_estado)
                        >
                            {{ $estado->nombre_estado }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="campo-reporte">
                <label for="fecha_inicio">Fecha desde</label>
                <input
                    type="date"
                    name="fecha_inicio"
                    id="fecha_inicio"
                    value="{{ request('fecha_inicio') }}"
                >
            </div>

            <div class="campo-reporte">
                <label for="fecha_fin">Fecha hasta</label>
                <input
                    type="date"
                    name="fecha_fin"
                    id="fecha_fin"
                    value="{{ request('fecha_fin') }}"
                >
            </div>

            <div class="acciones-filtro">
                <button type="submit" class="btn-reporte">
                    <i class="bi bi-search"></i>
                    Consultar
                </button>

                <a href="{{ route('reportes') }}" class="btn-secundario">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Limpiar filtros
                </a>
            </div>

        </form>
    </div>

    {{-- PERÍODO Y ALCANCE --}}

    <div class="resumen-periodo">
        <span>
            <i class="bi bi-calendar3"></i>
            Período: <strong>{{ $periodoTexto }}</strong>
        </span>

        <span>
            <i class="bi bi-file-earmark-text"></i>
            Solicitudes del reporte:
            <strong>{{ number_format($totalReporte, 0, ',', '.') }}</strong>
        </span>

        <span>
            <i class="bi bi-clock"></i>
            Generado: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
        </span>
    </div>

    {{-- TARJETAS ESTADÍSTICAS --}}

    <div class="estadisticas">

        <div class="estadistica-card">
            <div class="estadistica-icono">
                <i class="bi bi-files"></i>
            </div>
            <div>
                <small>Solicitudes del reporte</small>
                <h3>{{ number_format($totalReporte, 0, ',', '.') }}</h3>
                <span class="estadistica-porcentaje">
                    Registros filtrados
                </span>
            </div>
        </div>

        <div class="estadistica-card">
            <div class="estadistica-icono">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <small>Solicitudes pendientes</small>
                <h3>{{ $cantidadPendiente }}</h3>
                <span class="estadistica-porcentaje">
                    <strong>{{ number_format($porcentaje($cantidadPendiente), 1, ',', '.') }}%</strong>
                    del reporte
                </span>
            </div>
        </div>

        <div class="estadistica-card">
            <div class="estadistica-icono">
                <i class="bi bi-search"></i>
            </div>
            <div>
                <small>Solicitudes en revisión</small>
                <h3>{{ $cantidadRevision }}</h3>
                <span class="estadistica-porcentaje">
                    <strong>{{ number_format($porcentaje($cantidadRevision), 1, ',', '.') }}%</strong>
                    del reporte
                </span>
            </div>
        </div>

        <div class="estadistica-card">
            <div class="estadistica-icono">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <small>Solicitudes aprobadas</small>
                <h3>{{ $cantidadAprobado }}</h3>
                <span class="estadistica-porcentaje">
                    <strong>{{ number_format($porcentaje($cantidadAprobado), 1, ',', '.') }}%</strong>
                    del reporte
                </span>
            </div>
        </div>

        <div class="estadistica-card">
            <div class="estadistica-icono">
                <i class="bi bi-x-circle"></i>
            </div>
            <div>
                <small>Solicitudes rechazadas</small>
                <h3>{{ $cantidadRechazado }}</h3>
                <span class="estadistica-porcentaje">
                    <strong>{{ number_format($porcentaje($cantidadRechazado), 1, ',', '.') }}%</strong>
                    del reporte
                </span>
            </div>
        </div>

    </div>

    {{-- GRÁFICAS --}}

    <div class="graficas-grid">

        {{-- GRÁFICA CIRCULAR POR ESTADO --}}

        <section class="grafica-panel">
            <h3 class="panel-titulo">
                <i class="bi bi-pie-chart"></i>
                Solicitudes por estado
            </h3>

            <p class="panel-descripcion">
                Distribución porcentual de las solicitudes que cumplen los filtros.
            </p>

            @if ($estadosReporte->sum('cantidad') > 0)

                @php
                    $acumulado = 0;
                    $totalEstadosGrafica = (int) $estadosReporte->sum('cantidad');
                @endphp

                <div class="grafica-circular-contenedor">

                    <svg
                        class="grafica-circular"
                        viewBox="0 0 42 42"
                        role="img"
                        aria-label="Gráfica circular de solicitudes por estado"
                    >
                        <circle
                            class="anillo-base"
                            cx="21"
                            cy="21"
                            r="15.9155"
                        />

                        @foreach ($estadosReporte as $indice => $item)
                            @php
                                $cantidadSegmento = (int) $item->cantidad;

                                $porcentajeSegmento = $totalEstadosGrafica > 0
                                    ? ($cantidadSegmento / $totalEstadosGrafica) * 100
                                    : 0;

                                $desplazamiento = -$acumulado;
                                $colorSegmento = $coloresEstados[
                                    $indice % count($coloresEstados)
                                ];

                                $acumulado += $porcentajeSegmento;
                            @endphp

                            @if ($porcentajeSegmento > 0)
                                <circle
                                    class="segmento"
                                    cx="21"
                                    cy="21"
                                    r="15.9155"
                                    stroke="{{ $colorSegmento }}"
                                    stroke-dasharray="{{ $porcentajeSegmento }} {{ 100 - $porcentajeSegmento }}"
                                    stroke-dashoffset="{{ $desplazamiento }}"
                                >
                                    <title>
                                        {{ $item->nombre_estado }}:
                                        {{ $cantidadSegmento }}
                                        ({{ number_format($porcentajeSegmento, 1, ',', '.') }}%)
                                    </title>
                                </circle>
                            @endif
                        @endforeach

                        <text x="21" y="20" class="grafica-centro-numero">
                            {{ $totalEstadosGrafica }}
                        </text>

                        <text x="21" y="25" class="grafica-centro-texto">
                            SOLICITUDES
                        </text>
                    </svg>

                    <div class="leyenda-grafica">
                        @foreach ($estadosReporte as $indice => $item)
                            @php
                                $porcentajeEstado = $totalEstadosGrafica > 0
                                    ? round(((int) $item->cantidad / $totalEstadosGrafica) * 100, 1)
                                    : 0;
                            @endphp

                            <div class="leyenda-item">
                                <span
                                    class="leyenda-color"
                                    style="background: {{ $coloresEstados[$indice % count($coloresEstados)] }};"
                                ></span>

                                <span class="leyenda-nombre">
                                    {{ $item->nombre_estado }}
                                </span>

                                <span class="leyenda-valor">
                                    {{ $item->cantidad }}
                                    ({{ number_format($porcentajeEstado, 1, ',', '.') }}%)
                                </span>
                            </div>
                        @endforeach
                    </div>

                </div>
            @else
                <div class="mensaje-sin-datos">
                    <i class="bi bi-pie-chart"></i>
                    No existen solicitudes para representar en la gráfica.
                </div>
            @endif
        </section>

        {{-- GRÁFICA DE BARRAS POR TIPO --}}

        <section class="grafica-panel">
            <h3 class="panel-titulo">
                <i class="bi bi-bar-chart"></i>
                Solicitudes por tipo de trámite
            </h3>

            <p class="panel-descripcion">
                Cantidad de solicitudes agrupadas por tipo de trámite.
            </p>

            @if ($tiposReporte->sum('cantidad') > 0)

                <div class="grafica-barras">
                    @foreach ($tiposReporte as $item)
                        @php
                            $cantidadTipo = (int) $item->cantidad;

                            $anchoBarra = $maximoTipo > 0
                                ? ($cantidadTipo / $maximoTipo) * 100
                                : 0;

                            $porcentajeTipo = $totalReporte > 0
                                ? ($cantidadTipo / $totalReporte) * 100
                                : 0;
                        @endphp

                        <div class="barra-item">
                            <div class="barra-encabezado">
                                <span class="barra-nombre">
                                    {{ $item->nombre_tramite ?? 'Sin tipo de trámite' }}
                                </span>

                                <span class="barra-cantidad">
                                    {{ $cantidadTipo }}
                                </span>
                            </div>

                            <div
                                class="barra-fondo"
                                role="img"
                                aria-label="{{ $item->nombre_tramite ?? 'Tipo de trámite' }}: {{ $cantidadTipo }} solicitudes"
                            >
                                <div
                                    class="barra-progreso"
                                    style="width: {{ $anchoBarra }}%;"
                                ></div>
                            </div>

                            <div class="barra-porcentaje">
                                {{ number_format($porcentajeTipo, 1, ',', '.') }}%
                                del reporte
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="mensaje-sin-datos">
                    <i class="bi bi-bar-chart"></i>
                    No existen datos por tipo de trámite para mostrar.
                </div>
            @endif
        </section>

    </div>

    {{-- GRÁFICA MENSUAL, SI HAY DATOS --}}

    @if ($mesesReporte->isNotEmpty())
        <section class="grafica-panel" style="margin-bottom: 24px;">
            <h3 class="panel-titulo">
                <i class="bi bi-calendar3"></i>
                Solicitudes por mes
            </h3>

            <p class="panel-descripcion">
                Distribución mensual de las solicitudes incluidas en la consulta.
            </p>

            @if ($mesesReporte->sum('cantidad') > 0)
                <div class="grafica-barras">
                    @foreach ($mesesReporte as $mes)
                        @php
                            $cantidadMes = (int) $mes->cantidad;

                            $anchoMes = $maximoMes > 0
                                ? ($cantidadMes / $maximoMes) * 100
                                : 0;

                            $porcentajeMes = $totalReporte > 0
                                ? ($cantidadMes / $totalReporte) * 100
                                : 0;
                        @endphp

                        <div class="barra-item">
                            <div class="barra-encabezado">
                                <span class="barra-nombre">
                                    {{ $mes->periodo ?? 'Período sin identificar' }}
                                </span>

                                <span class="barra-cantidad">
                                    {{ $cantidadMes }}
                                </span>
                            </div>

                            <div class="barra-fondo">
                                <div
                                    class="barra-progreso"
                                    style="width: {{ $anchoMes }}%;"
                                ></div>
                            </div>

                            <div class="barra-porcentaje">
                                {{ number_format($porcentajeMes, 1, ',', '.') }}%
                                del reporte
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mensaje-sin-datos">
                    No hay cantidades mensuales para mostrar.
                </div>
            @endif
        </section>
    @endif

    {{-- TABLA DETALLADA --}}

    <section class="reporte-panel">
        <h3 class="panel-titulo">
            <i class="bi bi-table"></i>
            Detalle de solicitudes
        </h3>

        <p class="panel-descripcion">
            Relación de solicitudes que cumplen los criterios de búsqueda seleccionados.
        </p>

        <div class="tabla-contenedor">
            <table class="tabla-reportes">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Solicitante</th>
                        <th>Tipo de trámite</th>
                        <th>Instituto</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($solicitudesReporte as $solicitud)
                        @php
                            $nombreEstado = mb_strtolower(
                                trim($solicitud->nombre_estado ?? ''),
                                'UTF-8'
                            );

                            $claseEstado = match ($nombreEstado) {
                                'pendiente' => 'estado-pendiente',
                                'en revisión', 'en revision' => 'estado-revision',
                                'observado' => 'estado-observado',
                                'aprobado' => 'estado-aprobado',
                                'rechazado' => 'estado-rechazado',
                                'finalizado' => 'estado-finalizado',
                                default => '',
                            };
                        @endphp

                        <tr>
                            <td>#{{ $solicitud->cod_solicitud }}</td>

                            <td>
                                {{ $solicitud->fecha_solicitud
                                    ? \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y')
                                    : '—' }}
                            </td>

                            <td>
                                {{ trim($solicitud->solicitante ?? '')
                                    ?: ($solicitud->usuario ?? 'Sin datos') }}
                            </td>

                            <td>
                                {{ $solicitud->nombre_tramite ?? 'Sin datos' }}
                            </td>

                            <td>
                                {{ $solicitud->nombre_inst ?? 'Sin datos' }}
                            </td>

                            <td>
                                <span class="estado-etiqueta {{ $claseEstado }}">
                                    {{ $solicitud->nombre_estado ?? 'Sin estado' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="tabla-vacia">
                                <i class="bi bi-inbox"></i>
                                No se encontraron solicitudes con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="reporte-pie">
            <span>
                Registros encontrados:
                <strong>{{ number_format($totalReporte, 0, ',', '.') }}</strong>
            </span>

            <span>
                Período: <strong>{{ $periodoTexto }}</strong>
            </span>

            <span>
                Reporte generado el {{ now()->format('d/m/Y H:i') }}
            </span>
        </div>
    </section>

</div>

@endsection
