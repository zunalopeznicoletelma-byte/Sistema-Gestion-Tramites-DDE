
@extends('layouts.app')

@section('title', 'Nueva Solicitud')

@section('content')
<div class="solicitudes-create">

    <style>
        .solicitudes-create {
            color: #343a40;
        }

        .solicitudes-create .encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .solicitudes-create .encabezado h2 {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .solicitudes-create .tarjeta {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
        }

        .solicitudes-create .form-label {
            font-weight: 600;
        }

        .solicitudes-create .form-control,
        .solicitudes-create .form-select {
            min-height: 44px;
            border-radius: 8px;
        }

        .solicitudes-create textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .solicitudes-create .form-control:focus,
        .solicitudes-create .form-select:focus {
            border-color: #b5121b;
            box-shadow: 0 0 0 .2rem rgba(181, 18, 27, .12);
        }

        .solicitudes-create .btn-dd {
            background: #b5121b;
            border-color: #b5121b;
            color: white;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .solicitudes-create .btn-dd:hover {
            background: #8f0d14;
            color: white;
        }

        .solicitudes-create .info {
            background: #fff1f2;
            border-left: 4px solid #b5121b;
            padding: 13px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
        }

        .solicitudes-create .tabla-solicitud {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border: 1px solid #eeeeee;
            border-radius: 10px;
        }

        .solicitudes-create .tabla-solicitud th {
            width: 30%;
            background: #fff1f2;
            color: #343a40;
            padding: 16px;
            vertical-align: top;
            border-bottom: 1px solid #eeeeee;
        }

        .solicitudes-create .tabla-solicitud td {
            background: #ffffff;
            padding: 13px 16px;
            border-bottom: 1px solid #eeeeee;
        }

        .solicitudes-create .tabla-solicitud tr:last-child th,
        .solicitudes-create .tabla-solicitud tr:last-child td {
            border-bottom: none;
        }

        .solicitudes-create .tabla-solicitud .form-control,
        .solicitudes-create .tabla-solicitud .form-select {
            width: 100%;
        }

        .solicitudes-create .obligatorio {
            color: #b5121b;
        }

        @media (max-width: 768px) {
            .solicitudes-create .tarjeta {
                padding: 18px;
            }

            .solicitudes-create .tabla-solicitud,
            .solicitudes-create .tabla-solicitud tbody,
            .solicitudes-create .tabla-solicitud tr,
            .solicitudes-create .tabla-solicitud th,
            .solicitudes-create .tabla-solicitud td {
                display: block;
                width: 100%;
            }

            .solicitudes-create .tabla-solicitud th {
                padding-bottom: 8px;
                border-bottom: none;
            }

            .solicitudes-create .tabla-solicitud td {
                padding-top: 8px;
            }
        }
    </style>

    <div class="encabezado">
        <div>
            <h2>
                <i class="bi bi-file-earmark-plus me-2"
                   style="color:#b5121b"></i>
                Registrar nueva solicitud
            </h2>

            <p class="text-muted mb-0">
                Gestión de trámites - DDE Cochabamba
            </p>
        </div>

        <a href="{{ route('solicitudes.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Volver
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Verifique los datos del formulario.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tarjeta">
        <h5 class="fw-bold mb-4">
            <i class="bi bi-clipboard-check me-2"
               style="color:#b5121b"></i>
            Datos de la modificación solicitada
        </h5>

        <div class="info">
            Complete la tabla con los datos del trámite que desea solicitar.
            Los campos marcados con <span class="obligatorio">*</span> son obligatorios.
        </div>

        <form action="{{ route('solicitudes.store') }}"
              method="POST">
            @csrf

            <table class="tabla-solicitud">
                <tbody>
                    <tr>
                        <th>
                            <label for="cod_instituto">
                                Instituto Tecnológico
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                        <td>
                            <select name="cod_instituto"
                                    id="cod_instituto"
                                    class="form-select @error('cod_instituto') is-invalid @enderror"
                                    required>
                                <option value="">Seleccione un instituto tecnológico</option>

                                @foreach($institutos as $instituto)
                                    <option value="{{ $instituto->cod_instituto }}"
                                        {{ old('cod_instituto') == $instituto->cod_instituto ? 'selected' : '' }}>
                                        {{ $instituto->nombre_inst }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cod_instituto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="datos_errados">
                                Datos errados
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                        <td>
                            <textarea name="datos_errados"
                                      id="datos_errados"
                                      class="form-control @error('datos_errados') is-invalid @enderror"
                                      rows="4"
                                      maxlength="2000"
                                      placeholder="Describa los datos incorrectos..."
                                      required>{{ old('datos_errados') }}</textarea>
                            @error('datos_errados')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="datos_correctos">
                                Datos correctos
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                        <td>
                            <textarea name="datos_correctos"
                                      id="datos_correctos"
                                      class="form-control @error('datos_correctos') is-invalid @enderror"
                                      rows="4"
                                      maxlength="2000"
                                      placeholder="Escriba cómo deben figurar los datos..."
                                      required>{{ old('datos_correctos') }}</textarea>
                            @error('datos_correctos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="cod_carrera">
                                Carrera
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                        <td>
                            <select name="cod_carrera"
                                    id="cod_carrera"
                                    class="form-select @error('cod_carrera') is-invalid @enderror"
                                    required>
                                <option value="">Seleccione una carrera</option>

                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->cod_carrera }}"
                                            data-instituto="{{ $carrera->cod_instituto }}"
                                        {{ old('cod_carrera') == $carrera->cod_carrera ? 'selected' : '' }}>
                                        {{ $carrera->nombre_carrera }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cod_carrera')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="asignatura">
                                Asignatura
                                
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                      
                            <td>
                                <input type="text"
                                    name="asignatura"
                                    id="asignatura"
                                    class="form-control @error('asignatura') is-invalid @enderror"
                                    value="{{ old('asignatura') }}"
                                    maxlength="200"
                                    placeholder="Nombre o código de asignatura (opcional)">

                                <small class="text-muted">
                                    Campo opcional. Si existe un error en el nombre o código de asignatura, verifique la información.
                                </small>

                                @error('asignatura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="nivel">
                                Nivel
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                        <td>
                            <select name="nivel"
                                    id="nivel"
                                    class="form-select @error('nivel') is-invalid @enderror"
                                    required>
                                <option value="">Seleccione el nivel</option>
                                @foreach([
                                    'Técnico Superior',
                                    'Técnico Medio',
                                    'Auxiliar',
                                    'Capacitación',
                                    'Otros'
                                ] as $nivel)
                                    <option value="{{ $nivel }}"
                                        {{ old('nivel') == $nivel ? 'selected' : '' }}>
                                        {{ $nivel }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nivel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="curso">
                                Curso
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                        <td>
                            <input type="text"
                                   name="curso"
                                   id="curso"
                                   class="form-control @error('curso') is-invalid @enderror"
                                   value="{{ old('curso') }}"
                                   maxlength="100"
                                   placeholder="Ejemplo: Segundo"
                                   required>
                            @error('curso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="semestre_gestion">
                                Semestre/Gestión
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                        <td>
                            <input type="text"
                                   name="semestre_gestion"
                                   id="semestre_gestion"
                                   class="form-control @error('semestre_gestion') is-invalid @enderror"
                                   value="{{ old('semestre_gestion') }}"
                                   maxlength="100"
                                   placeholder="Ejemplo: Segundo semestre / 2025"
                                   required>
                            @error('semestre_gestion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="cod_tipo">
                                Tipo de trámite
                                <span class="obligatorio">*</span>
                            </label>
                        </th>
                        <td>
                            <select name="cod_tipo"
                                    id="cod_tipo"
                                    class="form-select @error('cod_tipo') is-invalid @enderror"
                                    required>
                                <option value="">Seleccione el tipo de trámite</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->cod_tipo }}"
                                        {{ old('cod_tipo') == $tipo->cod_tipo ? 'selected' : '' }}>
                                        {{ $tipo->nombre_tramite }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cod_tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2 flex-wrap">
                <a href="{{ route('solicitudes.index') }}"
                   class="btn btn-outline-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-dd">
                    <i class="bi bi-save me-1"></i>
                    Registrar solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const instituto = document.getElementById('cod_instituto');
    const carrera = document.getElementById('cod_carrera');

    function filtrarCarreras() {
        const institutoSeleccionado = instituto.value;
        const carreraAnterior = carrera.value;

        Array.from(carrera.options).forEach(function (opcion) {
            if (!opcion.value) {
                opcion.hidden = false;
                return;
            }

            opcion.hidden = institutoSeleccionado !== ''
                && opcion.dataset.instituto !== institutoSeleccionado;
        });

        const seleccionValida = Array.from(carrera.options).some(function (opcion) {
            return opcion.value === carreraAnterior && !opcion.hidden;
        });

        if (!seleccionValida) {
            carrera.value = '';
        }
    }

    instituto.addEventListener('change', filtrarCarreras);
    filtrarCarreras();
});
</script>
@endsection
