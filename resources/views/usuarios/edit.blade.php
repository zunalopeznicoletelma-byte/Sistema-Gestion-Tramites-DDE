@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-pencil-square text-danger"></i>
                Editar usuario
            </h2>
            <p class="text-muted mb-0">
                Modificación de los datos de la cuenta institucional.
            </p>
        </div>

        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>No se pudo actualizar el usuario.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Datos del usuario</h5>
            <small class="text-muted">
                Código de usuario: {{ $usuario->cod_usuario }}
            </small>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('usuarios.update', $usuario->cod_usuario) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="usuario" class="form-label">Nombre de usuario</label>
                        <input type="text" id="usuario" class="form-control"
                               value="{{ $usuario->usuario }}" disabled>
                        <div class="form-text">
                            El nombre de usuario no se modifica desde este formulario.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="carnet" class="form-label">Carnet de identidad</label>
                        <input type="text" id="carnet" class="form-control"
                               value="{{ $usuario->carnet }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="nombre1" class="form-label">Primer nombre *</label>
                        <input type="text" id="nombre1" name="nombre1"
                               class="form-control" maxlength="30" required
                               value="{{ old('nombre1', $usuario->nombre1) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="nombre2" class="form-label">Segundo nombre</label>
                        <input type="text" id="nombre2" name="nombre2"
                               class="form-control" maxlength="30"
                               value="{{ old('nombre2', $usuario->nombre2) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="paterno" class="form-label">Apellido paterno *</label>
                        <input type="text" id="paterno" name="paterno"
                               class="form-control" maxlength="30" required
                               value="{{ old('paterno', $usuario->paterno) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="materno" class="form-label">Apellido materno</label>
                        <input type="text" id="materno" name="materno"
                               class="form-control" maxlength="30"
                               value="{{ old('materno', $usuario->materno) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="e_mail" class="form-label">Correo electrónico *</label>
                        <input type="email" id="e_mail" name="e_mail"
                               class="form-control" maxlength="100" required
                               value="{{ old('e_mail', $usuario->e_mail) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" name="telefono"
                               class="form-control" maxlength="15"
                               value="{{ old('telefono', $usuario->telefono) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="cod_acceso" class="form-label">Rol de acceso *</label>
                        <select id="cod_acceso" name="cod_acceso"
                                class="form-select" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->cod_acceso }}"
                                    @selected((string) old('cod_acceso', $usuario->cod_acceso)
                                        === (string) $rol->cod_acceso)>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="activo" class="form-label">Estado de la cuenta *</label>
                        <select id="activo" name="activo" class="form-select" required>
                            <option value="1"
                                @selected((string) old('activo', $usuario->activo) === '1')>
                                Activo
                            </option>
                            <option value="0"
                                @selected((string) old('activo', $usuario->activo) === '0')>
                                Inactivo
                            </option>
                        </select>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end flex-wrap gap-2">
                    <a href="{{ route('usuarios.index') }}"
                       class="btn btn-light border">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-circle"></i>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
