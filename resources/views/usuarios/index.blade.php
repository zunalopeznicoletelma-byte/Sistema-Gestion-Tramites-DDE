
@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')

<style>
    .usuarios-page {
        font-family: Arial, Helvetica, sans-serif;
        color: #343a40;
    }

    .usuarios-header {
        background: linear-gradient(135deg, #8f0d14, #d71920);
        color: #fff;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .12);
    }

    .usuarios-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 26px;
    }

    .usuarios-card {
        background: #fff;
        border: 1px solid #eeeeee;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
        overflow: hidden;
    }

    .usuarios-table {
        margin-bottom: 0;
    }

    .usuarios-table thead th {
        background: #8f0d14;
        color: #fff;
        white-space: nowrap;
        padding: 15px 12px;
        font-size: 13px;
        font-weight: 600;
        border: none;
    }

    .usuarios-table tbody td {
        padding: 13px 12px;
        vertical-align: middle;
        font-size: 13px;
    }

    .usuarios-table tbody tr:hover {
        background: #fff1f2;
    }

    .usuarios-badge {
        display: inline-block;
        font-size: 11px;
        padding: 7px 10px;
        border-radius: 20px;
        font-weight: 600;
        white-space: nowrap;
    }

    .role-admin {
        background: #343a40;
        color: #fff;
    }

    .role-tecnico {
        background: #0d6efd;
        color: #fff;
    }

    .role-directivo {
        background: #6f42c1;
        color: #fff;
    }

    .role-solicitante {
        background: #198754;
        color: #fff;
    }

    .usuarios-action {
        margin: 2px;
    }

    .usuarios-empty {
        text-align: center;
        padding: 50px 20px;
        color: #777;
    }

    .usuarios-empty i {
        font-size: 48px;
        color: #b5121b;
    }

    .usuarios-count {
        padding: 16px 20px;
        background: #fff;
        border-bottom: 1px solid #eeeeee;
        color: #6c757d;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .usuarios-header {
            padding: 20px;
        }

        .usuarios-header h2 {
            font-size: 21px;
        }
    }
</style>

<div class="usuarios-page">

    {{-- CABECERA --}}
    <div class="usuarios-header">
        <div class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center gap-3">

            <div>
                <h2>
                    <i class="bi bi-people-fill me-2"></i>
                    Gestión de Usuarios
                </h2>

                <p class="mb-0 mt-2">
                    Administración de usuarios del sistema institucional
                </p>
            </div>

            <a href="{{ route('usuarios.create') }}"
               class="btn btn-light fw-semibold">
                <i class="bi bi-person-plus-fill me-1"></i>
                Nuevo usuario
            </a>

        </div>
    </div>

    {{-- MENSAJE DE ÉXITO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- MENSAJES DE VALIDACIÓN --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first() }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- MENSAJE DE ERROR DEL CONTROLADOR --}}
    @if(isset($error) && $error)
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ $error }}
        </div>
    @endif

    {{-- TABLA DE USUARIOS --}}
    <div class="usuarios-card">

        <div class="usuarios-count d-flex
                    justify-content-between align-items-center flex-wrap gap-2">
            <span>
                <i class="bi bi-list-ul me-1"></i>
                Listado de usuarios registrados
            </span>

            <span>
                Total:
                <strong>{{ count($usuarios) }}</strong>
            </span>
        </div>

        @if(count($usuarios) > 0)

            <div class="table-responsive">
                <table class="table table-hover usuarios-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre completo</th>
                            <th>Correo electrónico</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($usuarios as $usuario)

                            <tr>
                                {{-- ID --}}
                                <td>
                                    {{ $usuario->cod_usuario }}
                                </td>

                                {{-- NOMBRE DE USUARIO --}}
                                <td>
                                    <strong>
                                        {{ $usuario->usuario }}
                                    </strong>
                                </td>

                                {{-- NOMBRE COMPLETO --}}
                                <td>
                                    {{ $usuario->nombre1 }}
                                    {{ $usuario->nombre2 }}
                                    {{ $usuario->paterno }}
                                    {{ $usuario->materno }}
                                </td>

                                {{-- CORREO --}}
                                <td>
                                    {{ $usuario->e_mail ?: 'Sin correo' }}
                                </td>

                                {{-- ROL --}}
                                <td>
                                    @if($usuario->cod_acceso == 1)
                                        <span class="usuarios-badge role-admin">
                                            <i class="bi bi-shield-lock-fill me-1"></i>
                                            Administrador
                                        </span>

                                    @elseif($usuario->cod_acceso == 2)
                                        <span class="usuarios-badge role-tecnico">
                                            <i class="bi bi-person-gear me-1"></i>
                                            Técnico Departamental
                                        </span>

                                    @elseif($usuario->cod_acceso == 3)
                                        <span class="usuarios-badge role-directivo">
                                            <i class="bi bi-building me-1"></i>
                                            Directivo de Instituto
                                        </span>

                                    @elseif($usuario->cod_acceso == 7)
                                        <span class="usuarios-badge role-solicitante">
                                            <i class="bi bi-person-fill me-1"></i>
                                            Usuario Solicitante
                                        </span>

                                    @else
                                        <span class="usuarios-badge bg-secondary text-white">
                                            Sin rol
                                        </span>
                                    @endif
                                </td>

                                {{-- ESTADO --}}
                                <td>
                                    @if($usuario->activo)
                                        <span class="usuarios-badge bg-success text-white">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            Activo
                                        </span>
                                    @elseif($usuario->cod_acceso == 3)
                                        <span class="usuarios-badge bg-warning text-dark">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Pendiente
                                        </span>
                                    @else
                                        <span class="usuarios-badge bg-danger text-white">
                                            <i class="bi bi-x-circle-fill me-1"></i>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                {{-- ACCIONES --}}
                                <td class="text-center">

                                    {{-- EDITAR --}}
                                    <a href="{{ route(
                                            'usuarios.edit',
                                            $usuario->cod_usuario
                                        ) }}"
                                       class="btn btn-sm btn-outline-primary usuarios-action"
                                       title="Editar usuario"
                                       aria-label="Editar usuario">

                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- ACTIVAR DIRECTIVO PENDIENTE --}}
                                    @if($usuario->cod_acceso == 3 && !$usuario->activo)

                                        <form action="{{ route(
                                                    'usuarios.activar',
                                                    $usuario->cod_usuario
                                                ) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Desea activar esta cuenta de Directivo de Instituto?');">

                                            @csrf
                                            @method('PUT')

                                            <button type="submit"
                                                    class="btn btn-sm btn-success usuarios-action"
                                                    title="Activar cuenta">

                                                <i class="bi bi-person-check-fill"></i>
                                                Activar
                                            </button>
                                        </form>

                                    @endif

                                    {{-- DESACTIVAR USUARIO --}}
                                    @if($usuario->activo &&
                                        (int) session('usuario_id') !==
                                        (int) $usuario->cod_usuario)

                                        <form action="{{ route(
                                                    'usuarios.destroy',
                                                    $usuario->cod_usuario
                                                ) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Desea desactivar este usuario?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger usuarios-action"
                                                    title="Desactivar usuario"
                                                    aria-label="Desactivar usuario">

                                                <i class="bi bi-person-x-fill"></i>
                                            </button>
                                        </form>

                                    @endif

                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

        @else

            <div class="usuarios-empty">
                <i class="bi bi-people"></i>

                <h5 class="mt-3">
                    No existen usuarios registrados
                </h5>

                <p class="mb-3">
                    Puede registrar un nuevo usuario utilizando el botón superior.
                </p>

                <a href="{{ route('usuarios.create') }}"
                   class="btn btn-danger">
                    <i class="bi bi-person-plus-fill me-1"></i>
                    Registrar usuario
                </a>
            </div>

        @endif

    </div>

</div>

@endsection
