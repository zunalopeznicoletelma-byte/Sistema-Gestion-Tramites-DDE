
@extends('layouts.app')

@section('title', 'Historial de solicitudes')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#b5121b;">
                <i class="bi bi-clock-history me-2"></i>
                Historial de solicitudes
            </h2>
            <p class="text-muted mb-0">
                Consulta general de los movimientos registrados en los trámites.
            </p>
        </div>

        <a href="{{ route('solicitudes.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Volver a solicitudes
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-list-check me-2" style="color:#b5121b;"></i>
                Movimientos registrados
            </h5>
        </div>

        <div class="card-body">
@if(!empty($historial))
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background:#fff1f2;">
                            <tr>
                                <th>N.º solicitud</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Usuario responsable</th>
                                <th>Observación</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historial as $movimiento)
                                <tr>
                                    <td>{{ $movimiento->cod_solicitud }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill"
                                              style="background:#fff1f2;color:#b5121b;">
                                            {{ $movimiento->nombre_estado }}
                                        </span>
                                    </td>
                                    <td>{{ $movimiento->usuario }}</td>
                                    <td style="white-space:pre-line;">
                                        {{ $movimiento->observacion ?? 'Sin observaciones' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('solicitudes.historial', $movimiento->cod_solicitud) }}"
                                           class="btn btn-sm btn-outline-danger">
                                            Ver historial
                                        </a>
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
                        No existen movimientos disponibles para mostrar.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
