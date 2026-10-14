@extends('layouts.app')

@section('title', 'Editar Solicitud')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#b5121b;">
                <i class="bi bi-pencil-square me-2"></i>Editar Solicitud
            </h2>
            <p class="text-muted mb-0">
                Actualización de la información de la solicitud.
            </p>
        </div>

        <a href="{{ route('solicitudes.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Verifica la información:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-text me-2"
                   style="color:#b5121b;"></i>
                Solicitud N.º {{ $solicitud->cod_solicitud }}
            </h5>
        </div>

        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('solicitudes.update', $solicitud->cod_solicitud) }}">
                @csrf
                @method('PUT')

                {{-- El usuario solicitante se conserva --}}
                <input type="hidden"
                       name="cod_usuario"
                       value="{{ old('cod_usuario', $solicitud->cod_usuario) }}">

                <div class="row g-3">

                    {{-- Tipo de trámite --}}
                    <div class="col-md-6">
                        <label for="cod_tipo" class="form-label fw-semibold">
                            Tipo de trámite
                        </label>

                        <select name="cod_tipo"
                                id="cod_tipo"
                                class="form-select @error('cod_tipo') is-invalid @enderror"
                                required>
                            <option value="">Seleccione un trámite</option>

                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->cod_tipo }}"
                                    @selected((string) old('cod_tipo', $solicitud->cod_tipo) === (string) $tipo->cod_tipo)>
                                    {{ $tipo->nombre_tramite }}
                                </option>
                            @endforeach
                        </select>

                        @error('cod_tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Instituto --}}
                    <div class="col-md-6">
                        <label for="cod_instituto" class="form-label fw-semibold">
                            Instituto
                        </label>

                        <select name="cod_instituto"
                                id="cod_instituto"
                                class="form-select @error('cod_instituto') is-invalid @enderror"
                                required>
                            <option value="">Seleccione un instituto</option>

                            @foreach($institutos as $instituto)
                                <option value="{{ $instituto->cod_instituto }}"
                                    @selected((string) old('cod_instituto', $solicitud->cod_instituto) === (string) $instituto->cod_instituto)>
                                    {{ $instituto->nombre_inst }}
                                </option>
                            @endforeach
                        </select>

                        @error('cod_instituto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Plan de estudio --}}
                    <div class="col-md-6">
                        <label for="cod_plan" class="form-label fw-semibold">
                            Plan de estudio
                        </label>

                        <select name="cod_plan"
                                id="cod_plan"
                                class="form-select @error('cod_plan') is-invalid @enderror">
                            <option value="">Sin especificar</option>

                            @foreach($planes as $plan)
                                <option value="{{ $plan->cod_plan }}"
                                    @selected((string) old('cod_plan', $solicitud->cod_plan) === (string) $plan->cod_plan)>
                                    {{ $plan->nombre_carrera }}
                                    - {{ $plan->titulo_profesional }}
                                </option>
                            @endforeach
                        </select>

                        @error('cod_plan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-6">
                        <label for="cod_estado" class="form-label fw-semibold">
                            Estado de la solicitud
                        </label>

                        <select name="cod_estado"
                                id="cod_estado"
                                class="form-select @error('cod_estado') is-invalid @enderror"
                                required>
                            <option value="">Seleccione un estado</option>

                            @foreach($estados as $estado)
                                <option value="{{ $estado->cod_estado }}"
                                    @selected((string) old('cod_estado', $solicitud->cod_estado) === (string) $estado->cod_estado)>
                                    {{ $estado->nombre_estado }}
                                </option>
                            @endforeach
                        </select>

                        @error('cod_estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">
                            El flujo institucional contempla la revisión técnica
                            para procesar las solicitudes.
                        </small>
                    </div>

                    {{-- Observación --}}
                    <div class="col-12">
                        <label for="observacion" class="form-label fw-semibold">
                            Observaciones y datos del trámite
                        </label>

                        <textarea name="observacion"
                                  id="observacion"
                                  rows="7"
                                  maxlength="1000"
                                  class="form-control @error('observacion') is-invalid @enderror"
                                  placeholder="Información de la solicitud...">{{ old('observacion', $solicitud->observacion) }}</textarea>

                        @error('observacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Registro activo --}}
                    <div class="col-md-6">
                        <label for="activo" class="form-label fw-semibold">
                            Registro
                        </label>

                        <select name="activo"
                                id="activo"
                                class="form-select @error('activo') is-invalid @enderror"
                                required>
                            <option value="1"
                                @selected((string) old('activo', $solicitud->activo) === '1')>
                                Activo
                            </option>

                            <option value="0"
                                @selected((string) old('activo', $solicitud->activo) === '0')>
                                Inactivo
                            </option>
                        </select>

                        @error('activo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end flex-wrap gap-2">
                    <a href="{{ route('solicitudes.index') }}"
                       class="btn btn-light border">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="btn text-white"
                            style="background:#b5121b;">
                        <i class="bi bi-save me-1"></i>
                        Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection