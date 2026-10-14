<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes de Estudio - DDE Cochabamba</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    
</head>
<body>


@extends('layouts.app')

@section('title', 'Planes de Estudio - DDE Cochabamba')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-2" style="color: #c8102e;">
            <i class="bi bi-book-fill me-2"></i>
            Planes de Estudio
        </h2>

        <p class="text-muted mb-0">
            Consulta los institutos, carreras y mallas curriculares registradas.
        </p>
    </div>

    <div class="rounded-3 d-flex align-items-center justify-content-center"
         style="width: 55px; height: 55px; background: #fff0f2; color: #c8102e;">
        <i class="bi bi-mortarboard-fill fs-3"></i>
    </div>
</div>

<div class="row g-4">

    @forelse($planes as $plan)

        @php
            $imagenes = [
                'sistemas informaticos' => 'sistemas-informaticos.jpg',
                'enfermeria' => 'enfermeria.jpg',
                'administracion de empresas' => 'administracion-empresas.jpg',
                'contaduria general' => 'contaduria-general.jpg',
            ];

            $carreraNormalizada = mb_strtolower(
                \Illuminate\Support\Str::ascii(trim($plan->nombre_carrera))
            );

            $nombreImagen = $imagenes[$carreraNormalizada] ?? null;

            $rutaImagen = $nombreImagen
                ? public_path('img/planes-estudio/' . $nombreImagen)
                : null;

            $imagenExiste = $rutaImagen && file_exists($rutaImagen);
        @endphp

        <div class="col-12 col-md-6 col-xl-4">

            <article class="card h-100 border-0 rounded-4"
                     style="box-shadow: 0 5px 20px rgba(0,0,0,.08);">

                <div class="card-body p-4">

                    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                        <span class="badge rounded-pill px-3 py-2"
                              style="background: #fff0f2; color: #c8102e;">
                            {{ $plan->modalidad ?: 'Modalidad no especificada' }}
                        </span>

                        <i class="bi bi-journal-bookmark-fill fs-4"
                           style="color: #c8102e;"></i>
                    </div>

                    <h5 class="fw-bold mb-3">
                        {{ $plan->nombre_carrera }}
                    </h5>

                    <div class="d-flex gap-2 align-items-start mb-3">
                        <i class="bi bi-building-fill mt-1"
                           style="color: #c8102e;"></i>

                        <div>
                            <small class="text-muted d-block">Instituto</small>
                            <span>{{ $plan->nombre_inst }}</span>
                        </div>
                    </div>

                    <hr style="border-color: #eeeeee;">

                    <div class="mb-3">
                        <small class="text-muted d-block">Título profesional</small>
                        <span class="fw-semibold">
                            {{ $plan->titulo_profesional ?: 'No especificado' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Área de formación</small>
                        <span>
                            {{ $plan->area_formacion ?: 'No especificada' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Resolución</small>
                        <span>{{ $plan->resolucion ?: 'No especificada' }}</span>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block">Total de horas</small>
                        <span>
                            {{ $plan->total_horas ?? 'No especificado' }}
                        </span>
                    </div>

                    <div class="rounded-3 p-3 text-center"
                         style="background: #f5f6f8;">

                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-file-earmark-image me-1"
                               style="color: #c8102e;"></i>
                            Malla curricular
                        </h6>

                        @if($imagenExiste)

                            <a href="{{ asset('img/planes-estudio/' . $nombreImagen) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="Ver malla curricular en tamaño completo">

                                <img
                                    src="{{ asset('img/planes-estudio/' . $nombreImagen) }}"
                                    alt="Malla curricular de {{ $plan->nombre_carrera }}"
                                    class="img-fluid rounded"
                                    style="max-height: 240px; width: 100%; object-fit: contain; background: #fff;"
                                >
                            </a>

                            <small class="text-muted d-block mt-2">
                                Selecciona la imagen para ampliarla.
                            </small>

                        @else

                            <i class="bi bi-image fs-1 d-block mb-2"
                               style="color: #c8102e;"></i>

                            <p class="text-muted small mb-0">
                                La imagen de esta malla curricular
                                todavía no está disponible.
                            </p>

                        @endif

                    </div>

                    <div class="border-top mt-3 pt-3">
                        <small class="text-muted">
                            Código del plan:
                            <strong>{{ $plan->cod_plan }}</strong>
                        </small>
                    </div>

                </div>

            </article>

        </div>

    @empty

        <div class="col-12">
            <div class="alert alert-warning rounded-3">
                <i class="bi bi-exclamation-circle me-2"></i>
                No existen planes de estudio habilitados para mostrar.
            </div>
        </div>

    @endforelse

</div>

@endsection


</body>
</html>
