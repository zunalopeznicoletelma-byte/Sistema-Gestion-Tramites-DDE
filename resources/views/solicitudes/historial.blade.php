@extends('layouts.app')

@section('title', 'Historial de Solicitud')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#b5121b;">
                <i class="bi bi-clock-history me-2"></i>
                Historial de Solicitud
            </h2>
            <p class="text-muted mb-0">
                Consulta de los movimientos registrados durante la gestión del trámite.
            </p>
        </div>

        <a href="{{ route('solicitudes.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a solicitudes
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Resumen de la solicitud --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-text me-2"
                   style="color:#b5121b;"></i>
                Datos de la solicitud
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <small class="text-muted d-block">Número de solicitud</small>
                    <strong>{{ $solicitud->cod_solicitud }}</strong>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">Tipo de trámite</small>
                    <strong>
                        {{ $solicitud->nombre_tramite ?? $solicitud->cod_tipo ?? 'No disponible' }}
                    </strong>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">Estado actual</small>
                    <strong>
                        {{ $solicitud->estado ?? $solicitud->nombre_estado ?? $solicitud->cod_estado ?? 'No disponible' }}
                    </strong>
                </div>

                <div class="col-12">
                    <small class="text-muted d-block">Observaciones</small>
                    <div class="p-3 rounded"
                         style="background:#f5f6f8; white-space:pre-line;">
                        {{ $solicitud->observacion ?? 'Sin observaciones registradas.' }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Movimientos del historial --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-list-check me-2"
                   style="color:#b5121b;"></i>
                Movimientos registrados
            </h5>
        </div>

        <div class="card-body">

            @if(isset($historial) && count($historial) > 0)

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background:#fff1f2;">
                            <tr>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Usuario responsable</th>
                                <th>Observación</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($historial as $movimiento)
                                <tr>
                                    <td>
                                        {{ isset($movimiento->fecha)
                                            ? \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y H:i')
                                            : 'No disponible' }}
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill"
                                              style="background:#fff1f2; color:#b5121b;">
                                            {{ $movimiento->nombre_estado
                                                ?? $movimiento->estado
                                                ?? $movimiento->cod_estado
                                                ?? 'No disponible' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $movimiento->usuario
                                            ?? $movimiento->nombre_usuario
                                            ?? $movimiento->cod_usuario
                                            ?? 'No disponible' }}
                                    </td>

                                    <td style="white-space:pre-line;">
                                        {{ $movimiento->observacion
                                            ?? 'Sin observaciones' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                <div class="text-center py-5">
                    <i class="bi bi-clock-history display-4 text-muted"></i>
                    <h5 class="mt-3">Sin movimientos registrados</h5>
                    <p class="text-muted mb-0">
                        Todavía no existen movimientos para mostrar
                        o el procedimiento no devolvió registros.
                    </p>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection