@extends('layouts.app')

@section('title', 'Reportes Administrativos - DDE Cochabamba')

@section('content')

<style>
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
        font-weight: bold;
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
        color: white;
        font-weight: bold;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-reporte:hover {
        background: #8f0d14;
        color: white;
    }

    .estadisticas {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .estadistica-card {
        background: #fff;
        border-radius: 12px;
        padding: 22px;
        border: 1px solid #eeeeee;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
    }

    .estadistica-icono {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff0f2;
        color: #c8102e;
        font-size: 24px;
    }

    .estadistica-card small {
        display: block;
        color: #777;
        font-size: 12px;
        margin-bottom: 7px;
    }

    .estadistica-card h3 {
        margin: 0;
        font-size: 27px;
        color: #343a40;
        font-weight: bold;
    }

    .reporte-panel {
        background: #fff;
        border: 1px solid #eeeeee;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .04);
    }

    .panel-titulo {
        color: #343a40;
        font-size: 18px;
        font-weight: bold;
        margin: 0 0 20px;
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
        font-weight: bold;
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

    .btn-secundario {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #555;
        background: white;
        text-decoration: none;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-secundario:hover {
        background: #f5f6f8;
        color: #333;
    }

    .estado-resumen {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .estado-item {
        padding: 15px;
        background: #f8f9fa;
        border: 1px solid #eeeeee;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .estado-item span {
        color: #555;
        font-size: 13px;
    }

    .estado-item strong {
        font-size: 20px;
        color: #c8102e;
    }

    .tabla-contenedor {
        width: 100%;
        overflow-x: auto;
    }

    .tabla-reportes {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .tabla-reportes thead {
        background: #fff0f2;
    }

    .tabla-reportes th {
        color: #8f0d14;
        text-align: left;
        padding: 14px 12px;
        white-space: nowrap;
        border-bottom: 1px solid #f0d6da;
    }

    .tabla-reportes td {
        padding: 13px 12px;
        border-bottom: 1px solid #eeeeee;
        vertical-align: middle;
    }

    .tabla-reportes tbody tr:hover {
        background: #fffafb;
    }

    .estado-etiqueta {
        display: inline-block;
        border-radius: 20px;
        padding: 6px 10px;
        background: #f0f0f0;
        color: #555;
        font-size: 11px;
        font-weight: bold;
        white-space: nowrap;
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

    .estado-aprobado {
        background: #d1e7dd;
        color: #0f5132;
    }

    .estado-rechazado {
        background: #e2e3e5;
        color: #41464b;
    }

    .estado-finalizado {
        background: #d1e7dd;
        color: #0f5132;
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
        font-size: 13px;
    }

    @media (max-width: 1100px) {
        .estadisticas {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .estadisticas,
        .filtros,
        .estado-resumen {
            grid-template-columns: 1fr;
        }

        .reporte-panel {
            padding: 16px;
        }

        .reporte-header h2 {
            font-size: 21px;
        }
    }

    @media print {
        body {
            background: #fff !important;
        }

        .sidebar,
        .barra-superior,
        .reporte-header .acciones-header,
        .panel-filtros,
        .no-imprimir {
            display: none !important;
        }

        .contenido-principal {
            width: 100% !important;
            margin: 0 !important;
        }

        .contenido {
            padding: 0 !important;
        }

        .reporte-panel,
        .estadistica-card {
            box-shadow: none !important;
            break-inside: avoid;
        }

        .estadisticas {
            grid-template-columns: repeat(4, 1fr);
        }

        .tabla-reportes {
            font-size: 10px;
        }

        .tabla-reportes th,
        .tabla-reportes td {
            padding: 7px 5px;
        }
    }
</style>

<div class="reporte-header">
    <div>
        <h2>
            <i class="bi bi-bar-chart-line"></i>
            Reportes administrativos
        </h2>
        <p>Consulta y análisis de las solicitudes de trámites registrados en el sistema.</p>
    </div>

    <div class="acciones-header">
        <button type="button" class="btn-reporte" onclick="window.print()">
            <i class="bi bi-printer"></i>
            Imprimir reporte
        </button>
    </div>
</div>

{{-- TARJETAS ESTADÍSTICAS --}}

<div class="estadisticas">

    <div class="estadistica-card">
        <div class="estadistica-icono">
            <i class="bi bi-files"></i>
        </div>
        <div>
            <small>Total de solicitudes registradas</small>
            <h3>{{ number_format($totalSolicitudes ?? 0, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="estadistica-card">
        <div class="estadistica-icono">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div>
            <small>Solicitudes pendientes</small>
            <h3>
                {{ ($solicitudesPorEstado ?? collect())->where('nombre_estado', 'Pendiente')->sum('cantidad') }}
            </h3>
        </div>
    </div>

    <div class="estadistica-card">
        <div class="estadistica-icono">
            <i class="bi bi-search"></i>
        </div>
        <div>
            <small>Solicitudes en revisión</small>
            <h3>
                {{ ($solicitudesPorEstado ?? collect())->where('nombre_estado', 'En revisión')->sum('cantidad') }}
            </h3>
        </div>
    </div>

    <div class="estadistica-card">
        <div class="estadistica-icono">
            <i class="bi bi-check-circle"></i>
        </div>
        <div>
            <small>Solicitudes finalizadas</small>
            <h3>
                {{ ($solicitudesPorEstado ?? collect())->where('nombre_estado', 'Finalizado')->sum('cantidad') }}
            </h3>
        </div>
    </div>

</div>

{{-- FILTROS --}}

<div class="reporte-panel panel-filtros">
    <h3 class="panel-titulo">
        <i class="bi bi-funnel"></i>
        Filtrar reportes
    </h3>

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

{{-- RESUMEN POR ESTADO --}}

<div class="reporte-panel">
    <h3 class="panel-titulo">
        <i class="bi bi-pie-chart"></i>
        Resumen de solicitudes por estado
    </h3>

    @if (($solicitudesPorEstado ?? collect())->isNotEmpty())
        <div class="estado-resumen">
            @foreach ($solicitudesPorEstado as $item)
                <div class="estado-item">
                    <span>{{ $item->nombre_estado }}</span>
                    <strong>{{ $item->cantidad }}</strong>
                </div>
            @endforeach
        </div>
    @else
        <p>No existen datos para mostrar.</p>
    @endif
</div>

{{-- TABLA DETALLADA --}}

<div class="reporte-panel">
    <h3 class="panel-titulo">
        <i class="bi bi-table"></i>
        Detalle de solicitudes
    </h3>

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
                @forelse ($solicitudes ?? [] as $solicitud)
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
                            {{ trim($solicitud->solicitante ?? '') ?: ($solicitud->usuario ?? 'Sin datos') }}
                        </td>

                        <td>{{ $solicitud->nombre_tramite ?? 'Sin datos' }}</td>

                        <td>{{ $solicitud->nombre_inst ?? 'Sin datos' }}</td>

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
            <strong>{{ $totalFiltrado ?? 0 }}</strong>
        </span>

        <span>
            Reporte generado el {{ now()->format('d/m/Y H:i') }}
        </span>
    </div>
</div>

@endsection