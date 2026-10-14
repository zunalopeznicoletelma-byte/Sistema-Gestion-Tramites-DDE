
@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
@php
    $listaUsuarios = collect($usuarios ?? []);
    $listaAccesos = collect($accesos ?? []);
    $listaDepartamentos = collect($departamentos ?? []);
    $listaDistritos = collect($distritos ?? []);

    $totalUsuarios = $listaUsuarios->count();

    $usuariosActivos = $listaUsuarios->filter(function ($usuario) {
        return (int) data_get($usuario, 'activo', 0) === 1;
    })->count();

    $usuariosInactivos = $totalUsuarios - $usuariosActivos;
@endphp

<div class="usuarios-page">

    {{-- ENCABEZADO --}}
    <div class="usuarios-header">
        <div>
            <div class="usuarios-breadcrumb">
                <i class="bi bi-house-door"></i>
                <span>Inicio</span>
                <i class="bi bi-chevron-right"></i>
                <span>Administración</span>
                <i class="bi bi-chevron-right"></i>
                <span>Usuarios</span>
            </div>

            <h1><i class="bi bi-people-fill"></i> Gestión de Usuarios</h1>
            <p>Administración de las cuentas y los accesos al sistema institucional.</p>
        </div>

        <button type="button" class="usuarios-btn usuarios-btn-primary"
                onclick="abrirFormularioUsuario()">
            <i class="bi bi-person-plus-fill"></i>
            Registrar usuario
        </button>
    </div>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="usuarios-alert usuarios-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" aria-label="Cerrar">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="usuarios-alert usuarios-alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" onclick="this.parentElement.remove()" aria-label="Cerrar">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="usuarios-alert usuarios-alert-danger">
            <div>
                <strong>No se pudo completar la operación.</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="this.parentElement.remove()" aria-label="Cerrar">&times;</button>
        </div>
    @endif

    {{-- TARJETAS --}}
    <div class="usuarios-stats">
        <div class="usuarios-stat-card">
            <div class="usuarios-stat-icon icon-red">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span class="usuarios-stat-label">Total de usuarios</span>
                <strong>{{ $totalUsuarios }}</strong>
                <small>Cuentas registradas</small>
            </div>
        </div>

        <div class="usuarios-stat-card">
            <div class="usuarios-stat-icon icon-green">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <span class="usuarios-stat-label">Usuarios activos</span>
                <strong>{{ $usuariosActivos }}</strong>
                <small>Cuentas habilitadas</small>
            </div>
        </div>

        <div class="usuarios-stat-card">
            <div class="usuarios-stat-icon icon-gray">
                <i class="bi bi-person-x-fill"></i>
            </div>
            <div>
                <span class="usuarios-stat-label">Usuarios inactivos</span>
                <strong>{{ $usuariosInactivos }}</strong>
                <small>Cuentas deshabilitadas</small>
            </div>
        </div>

        <div class="usuarios-stat-card">
            <div class="usuarios-stat-icon icon-blue">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span class="usuarios-stat-label">Tipos de acceso</span>
                <strong>{{ $listaAccesos->count() }}</strong>
                <small>Roles disponibles</small>
            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="usuarios-panel">
        <div class="usuarios-panel-header">
            <div>
                <h2><i class="bi bi-list-ul"></i> Usuarios registrados</h2>
                <p>Consulta y administra las cuentas registradas en el sistema.</p>
            </div>
            <span class="usuarios-count">{{ $totalUsuarios }} registros</span>
        </div>

        <div class="usuarios-toolbar">
            <div class="usuarios-search">
                <i class="bi bi-search"></i>
                <input type="search" id="buscarUsuario"
                       placeholder="Buscar por usuario, nombre, carnet o correo..."
                       autocomplete="off">
            </div>

            <div class="usuarios-filter">
                <i class="bi bi-funnel"></i>
                <select id="filtroEstado" aria-label="Filtrar por estado">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                </select>
            </div>
        </div>

        <div class="usuarios-table-wrapper">
            <table class="usuarios-table" id="tablaUsuarios">
                <thead>
                    <tr>
                        <th>USUARIO</th>
                        <th>NOMBRE COMPLETO</th>
                        <th>CARNET</th>
                        <th>CORREO ELECTRÓNICO</th>
                        <th>ROL / ACCESO</th>
                        <th>ESTADO</th>
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($listaUsuarios as $usuario)
                        @php
                            $nombreCompleto = trim(
                                (string) data_get($usuario, 'nombre1', '') . ' ' .
                                (string) data_get($usuario, 'nombre2', '') . ' ' .
                                (string) data_get($usuario, 'paterno', '') . ' ' .
                                (string) data_get($usuario, 'materno', '')
                            );

                            $activo = (int) data_get($usuario, 'activo', 0) === 1;
                            $nombreAcceso = data_get($usuario, 'acceso')
                                ?? data_get($usuario, 'nombre_acceso')
                                ?? data_get($usuario, 'rol')
                                ?? ('Acceso ' . data_get($usuario, 'cod_acceso', ''));
                        @endphp

                        <tr class="fila-usuario"
                            data-estado="{{ $activo ? 'activo' : 'inactivo' }}"
                            data-busqueda="{{ mb_strtolower(
                                (string) data_get($usuario, 'usuario', '') . ' ' .
                                $nombreCompleto . ' ' .
                                (string) data_get($usuario, 'carnet', '') . ' ' .
                                (string) data_get($usuario, 'e_mail', '') . ' ' .
                                (string) $nombreAcceso
                            ) }}">

                            <td>
                                <div class="usuarios-identidad">
                                    <div class="usuarios-avatar">
                                        {{ mb_strtoupper(mb_substr(
                                            (string) data_get($usuario, 'nombre1', 'U'), 0, 1
                                        )) }}
                                    </div>
                                    <div>
                                        <strong>{{ data_get($usuario, 'usuario', '—') }}</strong>
                                        <small>Código: {{ data_get($usuario, 'cod_usuario', '—') }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $nombreCompleto !== '' ? $nombreCompleto : '—' }}</td>
                            <td>{{ data_get($usuario, 'carnet', '—') }}</td>
                            <td>{{ data_get($usuario, 'e_mail', '—') }}</td>

                            <td>
                                <span class="usuarios-role">
                                    <i class="bi bi-shield-check"></i>
                                    {{ $nombreAcceso }}
                                </span>
                            </td>

                            <td>
                                @if($activo)
                                    <span class="usuarios-status status-active">
                                        <span></span> Activo
                                    </span>
                                @else
                                    <span class="usuarios-status status-inactive">
                                        <span></span> Inactivo
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="usuarios-actions">
                                    <a href="{{ route('usuarios.edit', data_get($usuario, 'cod_usuario')) }}"
                                       class="usuarios-action-btn action-edit"
                                       title="Editar usuario"
                                       aria-label="Editar usuario">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    @if($activo)
                                        <form action="{{ route('usuarios.destroy', data_get($usuario, 'cod_usuario')) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Está seguro de desactivar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="usuarios-action-btn action-disable"
                                                    title="Desactivar usuario"
                                                    aria-label="Desactivar usuario">
                                                <i class="bi bi-person-dash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('usuarios.activar', data_get($usuario, 'cod_usuario')) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Desea activar nuevamente este usuario?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="usuarios-action-btn action-enable"
                                                    title="Activar usuario"
                                                    aria-label="Activar usuario">
                                                <i class="bi bi-person-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="usuarios-empty">
                                    <i class="bi bi-people"></i>
                                    <strong>No hay usuarios registrados</strong>
                                    <p>Cuando existan cuentas registradas, aparecerán en esta tabla.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="sinResultados" style="display:none">
                        <td colspan="7">
                            <div class="usuarios-empty">
                                <i class="bi bi-search"></i>
                                <strong>No se encontraron resultados</strong>
                                <p>Prueba con otro término de búsqueda o cambia el filtro.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="usuarios-panel-footer">
            <span>Dirección Departamental de Educación Cochabamba</span>
            <span>Gestión de usuarios</span>
        </div>
    </div>

    {{-- MODAL DE REGISTRO --}}
    <div class="usuarios-modal" id="modalUsuario" aria-hidden="true">
        <div class="usuarios-modal-backdrop" onclick="cerrarFormularioUsuario()"></div>

        <div class="usuarios-modal-dialog">
            <div class="usuarios-modal-header">
                <div class="usuarios-modal-heading-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <h2>Registrar usuario</h2>
                    <p>Completa los datos para crear una nueva cuenta.</p>
                </div>
                <button type="button" class="usuarios-modal-close"
                        onclick="cerrarFormularioUsuario()" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="usuarios-modal-body">
                    <div class="usuarios-form-section">
                        <h3><i class="bi bi-person-vcard"></i> Datos personales</h3>

                        <div class="usuarios-form-grid">
                            <div class="usuarios-field">
                                <label for="nombre1">Primer nombre <span>*</span></label>
                                <input id="nombre1" name="nombre1" required maxlength="100"
                                       value="{{ old('nombre1') }}" placeholder="Primer nombre">
                            </div>

                            <div class="usuarios-field">
                                <label for="nombre2">Segundo nombre</label>
                                <input id="nombre2" name="nombre2" maxlength="100"
                                       value="{{ old('nombre2') }}" placeholder="Segundo nombre">
                            </div>

                            <div class="usuarios-field">
                                <label for="paterno">Apellido paterno <span>*</span></label>
                                <input id="paterno" name="paterno" required maxlength="100"
                                       value="{{ old('paterno') }}" placeholder="Apellido paterno">
                            </div>

                            <div class="usuarios-field">
                                <label for="materno">Apellido materno</label>
                                <input id="materno" name="materno" maxlength="100"
                                       value="{{ old('materno') }}" placeholder="Apellido materno">
                            </div>

                            <div class="usuarios-field">
                                <label for="carnet">Carnet de identidad <span>*</span></label>
                                <input id="carnet" name="carnet" required maxlength="30"
                                       value="{{ old('carnet') }}" placeholder="Número de carnet">
                            </div>

                            <div class="usuarios-field">
                                <label for="fecha_nac">Fecha de nacimiento</label>
                                <input type="date" id="fecha_nac" name="fecha_nac"
                                       value="{{ old('fecha_nac') }}">
                            </div>
                        </div>
                    </div>

                    <div class="usuarios-form-section">
                        <h3><i class="bi bi-person-gear"></i> Datos de la cuenta</h3>

                        <div class="usuarios-form-grid">
                            <div class="usuarios-field">
                                <label for="usuario">Nombre de usuario <span>*</span></label>
                                <input id="usuario" name="usuario" required maxlength="100"
                                       value="{{ old('usuario') }}" autocomplete="username"
                                       placeholder="Nombre para iniciar sesión">
                            </div>

                            <div class="usuarios-field">
                                <label for="e_mail">Correo electrónico <span>*</span></label>
                                <input type="email" id="e_mail" name="e_mail" required maxlength="150"
                                       value="{{ old('e_mail') }}" autocomplete="email"
                                       placeholder="correo@ejemplo.com">
                            </div>

                            <div class="usuarios-field">
                                <label for="telefono">Teléfono</label>
                                <input id="telefono" name="telefono" maxlength="30"
                                       value="{{ old('telefono') }}" placeholder="Número de contacto">
                            </div>

                            <div class="usuarios-field">
                                <label for="area">Área</label>
                                <input id="area" name="area" maxlength="100"
                                       value="{{ old('area') }}" placeholder="Área o unidad">
                            </div>

                            <div class="usuarios-field">
                                <label for="cod_acceso">Rol de acceso <span>*</span></label>
                                <select id="cod_acceso" name="cod_acceso" required>
                                    <option value="">Seleccionar rol</option>
                                    @foreach($listaAccesos as $acceso)
                                        @php
                                            $codigoAcceso = data_get($acceso, 'cod_acceso');
                                            $nombreRol = data_get($acceso, 'nombre');
                                        @endphp
                                        <option value="{{ $codigoAcceso }}"
                                            @selected((string) old('cod_acceso') === (string) $codigoAcceso)>
                                            {{ $nombreRol }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="usuarios-field">
                                <label for="clave">Contraseña <span>*</span></label>
                                <input type="password" id="clave" name="clave" required
                                       minlength="8" autocomplete="new-password"
                                       placeholder="Mínimo 8 caracteres">
                            </div>
                        </div>
                    </div>

                    <div class="usuarios-form-section">
                        <h3><i class="bi bi-geo-alt"></i> Ubicación</h3>

                        <div class="usuarios-form-grid">
                            <div class="usuarios-field">
                                <label for="cod_dep">Departamento</label>
                                <select id="cod_dep" name="cod_dep">
                                    <option value="">Seleccionar departamento</option>
                                    @foreach($listaDepartamentos as $departamento)
                                        <option value="{{ data_get($departamento, 'cod_dep') }}"
                                            @selected((string) old('cod_dep') === (string) data_get($departamento, 'cod_dep'))>
                                            {{ data_get($departamento, 'nombre') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="usuarios-field">
                                <label for="cod_dis">Distrito</label>
                                <select id="cod_dis" name="cod_dis">
                                    <option value="">Seleccionar distrito</option>
                                    @foreach($listaDistritos as $distrito)
                                        <option value="{{ data_get($distrito, 'cod_dis') }}"
                                                data-departamento="{{ data_get($distrito, 'cod_dep') }}"
                                            @selected((string) old('cod_dis') === (string) data_get($distrito, 'cod_dis'))>
                                            {{ data_get($distrito, 'nombre') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="usuarios-modal-footer">
                    <button type="button" class="usuarios-btn usuarios-btn-light"
                            onclick="cerrarFormularioUsuario()">
                        Cancelar
                    </button>
                    <button type="submit" class="usuarios-btn usuarios-btn-primary">
                        <i class="bi bi-check-circle"></i> Registrar usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.usuarios-page {
    --u-primary: #b5121b;
    --u-dark: #8f0d14;
    --u-light: #d71920;
    --u-bg: #f5f6f8;
    --u-border: #eeeeee;
    color: #343a40;
    font-family: "Segoe UI", Arial, sans-serif;
    padding-bottom: 28px;
}
.usuarios-page * { box-sizing: border-box; }
.usuarios-page .usuarios-header {
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:18px; margin-bottom:24px;
}
.usuarios-page .usuarios-breadcrumb {
    display:flex; align-items:center; flex-wrap:wrap; gap:9px;
    color:#9298a1; font-size:12px; margin-bottom:10px;
}
.usuarios-page .usuarios-breadcrumb i:last-of-type { font-size:10px; }
.usuarios-page .usuarios-header h1 {
    display:flex; align-items:center; gap:10px; margin:0 0 7px;
    font-size:25px; font-weight:750; color:#30343b;
}
.usuarios-page .usuarios-header h1 > i { color:var(--u-primary); }
.usuarios-page .usuarios-header p {
    margin:0; font-size:13px; color:#7a818b;
}
.usuarios-page .usuarios-btn {
    border:0; border-radius:8px; padding:11px 16px;
    display:inline-flex; justify-content:center; align-items:center; gap:8px;
    font-size:13px; font-weight:650; cursor:pointer; text-decoration:none;
    transition:background .2s, transform .2s;
}
.usuarios-page .usuarios-btn:hover { transform:translateY(-1px); }
.usuarios-page .usuarios-btn-primary {
    color:#fff; background:var(--u-primary);
    box-shadow:0 4px 10px rgba(181,18,27,.15);
}
.usuarios-page .usuarios-btn-primary:hover { background:var(--u-dark); color:#fff; }
.usuarios-page .usuarios-btn-light { color:#555d67; background:#f0f1f3; }
.usuarios-page .usuarios-btn-light:hover { background:#e3e5e8; }

.usuarios-page .usuarios-alert {
    padding:13px 15px; border-radius:9px; margin-bottom:18px;
    display:flex; align-items:flex-start; gap:10px; font-size:13px;
}
.usuarios-page .usuarios-alert button {
    margin-left:auto; border:0; background:transparent; font-size:22px;
    line-height:1; color:inherit; cursor:pointer;
}
.usuarios-page .usuarios-alert-success { background:#eaf7ef; color:#22683b; }
.usuarios-page .usuarios-alert-danger { background:#fff0f0; color:#a21a23; }

.usuarios-page .usuarios-stats {
    display:grid; grid-template-columns:repeat(4,minmax(0,1fr));
    gap:16px; margin-bottom:23px;
}
.usuarios-page .usuarios-stat-card {
    min-width:0; display:flex; align-items:center; gap:15px;
    padding:20px 18px; background:#fff; border:1px solid #f0f0f0;
    border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,.045);
}
.usuarios-page .usuarios-stat-icon {
    width:48px; height:48px; flex:0 0 48px; display:flex;
    align-items:center; justify-content:center; border-radius:12px; font-size:22px;
}
.usuarios-page .icon-red { background:#fff0f1; color:#b5121b; }
.usuarios-page .icon-green { background:#e9f8ef; color:#21834b; }
.usuarios-page .icon-gray { background:#f0f1f3; color:#737b86; }
.usuarios-page .icon-blue { background:#edf4ff; color:#3869b4; }
.usuarios-page .usuarios-stat-label { display:block; color:#777f89; font-size:12px; margin-bottom:5px; }
.usuarios-page .usuarios-stat-card strong { display:block; font-size:25px; line-height:1.1; color:#30343b; }
.usuarios-page .usuarios-stat-card small { display:block; color:#9a9fa7; font-size:11px; margin-top:5px; }

.usuarios-page .usuarios-panel {
    background:#fff; border:1px solid #ededed; border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,.045); overflow:hidden;
}
.usuarios-page .usuarios-panel-header {
    display:flex; justify-content:space-between; align-items:center;
    gap:15px; padding:22px 23px 18px; border-bottom:1px solid #f0f0f0;
}
.usuarios-page .usuarios-panel-header h2 {
    font-size:17px; font-weight:750; margin:0 0 6px; color:#333840;
}
.usuarios-page .usuarios-panel-header h2 i { color:var(--u-primary); margin-right:7px; }
.usuarios-page .usuarios-panel-header p { margin:0; font-size:12px; color:#9298a1; }
.usuarios-page .usuarios-count {
    white-space:nowrap; border-radius:20px; padding:6px 11px;
    background:#fff0f1; color:var(--u-primary); font-size:11px; font-weight:700;
}
.usuarios-page .usuarios-toolbar {
    display:flex; justify-content:space-between; align-items:center;
    flex-wrap:wrap; gap:12px; padding:17px 22px;
}
.usuarios-page .usuarios-search, .usuarios-page .usuarios-filter {
    display:flex; align-items:center; gap:9px; min-height:41px;
    padding:0 12px; border:1px solid #e6e8eb; border-radius:8px; background:#fff;
}
.usuarios-page .usuarios-search { flex:1; min-width:230px; max-width:520px; }
.usuarios-page .usuarios-search i, .usuarios-page .usuarios-filter i { color:#969da6; }
.usuarios-page .usuarios-search input {
    border:0; outline:0; width:100%; min-width:0; font-size:12px;
    background:transparent; color:#343a40;
}
.usuarios-page .usuarios-filter select {
    border:0; outline:0; background:transparent; font-size:12px;
    color:#505761; min-width:145px; cursor:pointer;
}
.usuarios-page .usuarios-table-wrapper { width:100%; overflow-x:auto; }
.usuarios-page .usuarios-table {
    width:100%; border-collapse:collapse; font-size:12px; white-space:nowrap;
}
.usuarios-page .usuarios-table thead { background:#f8f9fb; }
.usuarios-page .usuarios-table th {
    color:#858c96; font-size:10px; font-weight:750; letter-spacing:.45px;
    padding:14px 16px; text-align:left; border-top:1px solid #f0f0f0;
    border-bottom:1px solid #e9ebee;
}
.usuarios-page .usuarios-table td {
    padding:14px 16px; border-bottom:1px solid #f0f1f3; color:#555d67;
    vertical-align:middle;
}
.usuarios-page .usuarios-table tbody tr:hover { background:#fffafb; }
.usuarios-page .usuarios-identidad { display:flex; align-items:center; gap:10px; }
.usuarios-page .usuarios-avatar {
    width:37px; height:37px; flex:0 0 37px; border-radius:10px;
    display:flex; justify-content:center; align-items:center;
    background:#fff0f1; color:var(--u-primary); font-size:15px; font-weight:800;
}
.usuarios-page .usuarios-identidad strong { display:block; color:#343a40; font-size:12px; }
.usuarios-page .usuarios-identidad small { display:block; color:#a0a5ad; font-size:10px; margin-top:4px; }
.usuarios-page .usuarios-role {
    display:inline-flex; align-items:center; gap:6px; padding:6px 9px;
    border-radius:6px; background:#f3f4f6; color:#555d67; font-size:10px;
}
.usuarios-page .usuarios-status {
    display:inline-flex; align-items:center; gap:6px; border-radius:20px;
    padding:6px 9px; font-size:10px; font-weight:700;
}
.usuarios-page .usuarios-status > span { width:6px; height:6px; border-radius:50%; }
.usuarios-page .status-active { background:#eaf7ef; color:#247b43; }
.usuarios-page .status-active > span { background:#2b9b55; }
.usuarios-page .status-inactive { background:#f0f1f3; color:#6d747e; }
.usuarios-page .status-inactive > span { background:#8a919b; }
.usuarios-page .usuarios-actions { display:flex; align-items:center; justify-content:center; gap:7px; }
.usuarios-page .usuarios-actions form { margin:0; display:inline-flex; }
.usuarios-page .usuarios-action-btn {
    width:32px; height:32px; display:inline-flex; justify-content:center;
    align-items:center; border:0; border-radius:7px; cursor:pointer;
    text-decoration:none; font-size:14px; transition:background .2s;
}
.usuarios-page .action-edit { background:#edf4ff; color:#3569b7; }
.usuarios-page .action-edit:hover { background:#dceaff; }
.usuarios-page .action-disable { background:#fff0f0; color:#b5121b; }
.usuarios-page .action-disable:hover { background:#ffdddd; }
.usuarios-page .action-enable { background:#eaf7ef; color:#21834b; }
.usuarios-page .action-enable:hover { background:#d6f0df; }
.usuarios-page .usuarios-empty { padding:42px 15px; text-align:center; color:#9a9fa7; white-space:normal; }
.usuarios-page .usuarios-empty > i { display:block; font-size:34px; margin-bottom:10px; color:#c5c9cf; }
.usuarios-page .usuarios-empty strong { display:block; font-size:14px; color:#555d67; }
.usuarios-page .usuarios-empty p { font-size:12px; margin:6px 0 0; }
.usuarios-page .usuarios-panel-footer {
    display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;
    padding:13px 22px; font-size:10px; color:#9a9fa7; background:#fff;
}

.usuarios-page .usuarios-modal { display:none; position:fixed; inset:0; z-index:2000; }
.usuarios-page .usuarios-modal.is-open { display:flex; justify-content:center; align-items:center; padding:20px; }
.usuarios-page .usuarios-modal-backdrop { position:absolute; inset:0; background:rgba(25,28,33,.58); }
.usuarios-page .usuarios-modal-dialog {
    position:relative; z-index:1; background:#fff; border-radius:13px;
    width:100%; max-width:850px; max-height:92vh; overflow:auto;
    box-shadow:0 20px 70px rgba(0,0,0,.25); animation:usuariosModalIn .2s ease-out;
}
@keyframes usuariosModalIn {
    from { opacity:0; transform:translateY(8px); }
    to { opacity:1; transform:translateY(0); }
}
.usuarios-page .usuarios-modal-header {
    display:flex; align-items:center; gap:13px; padding:20px 24px;
    border-bottom:1px solid #eceef0; position:sticky; top:0; background:#fff; z-index:2;
}
.usuarios-page .usuarios-modal-heading-icon {
    width:43px; height:43px; border-radius:11px; display:flex;
    align-items:center; justify-content:center; background:#fff0f1;
    color:var(--u-primary); font-size:21px;
}
.usuarios-page .usuarios-modal-header h2 { font-size:17px; margin:0 0 4px; font-weight:750; }
.usuarios-page .usuarios-modal-header p { font-size:12px; margin:0; color:#9298a1; }
.usuarios-page .usuarios-modal-close {
    margin-left:auto; border:0; background:#f2f3f5; border-radius:8px;
    width:34px; height:34px; color:#69717b; cursor:pointer;
}
.usuarios-page .usuarios-modal-body { padding:22px 24px; }
.usuarios-page .usuarios-form-section + .usuarios-form-section {
    border-top:1px solid #eff0f2; margin-top:22px; padding-top:20px;
}
.usuarios-page .usuarios-form-section h3 {
    font-size:13px; font-weight:750; margin:0 0 15px; color:#424851;
}
.usuarios-page .usuarios-form-section h3 i { color:var(--u-primary); margin-right:7px; }
.usuarios-page .usuarios-form-grid {
    display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:15px;
}
.usuarios-page .usuarios-field { min-width:0; }
.usuarios-page .usuarios-field label {
    display:block; font-size:11px; font-weight:700; color:#555d67; margin-bottom:7px;
}
.usuarios-page .usuarios-field label span { color:var(--u-primary); }
.usuarios-page .usuarios-field input, .usuarios-page .usuarios-field select {
    display:block; width:100%; min-height:41px; border:1px solid #dfe2e6;
    border-radius:7px; padding:9px 11px; outline:0; font-size:12px;
    color:#343a40; background:#fff; transition:border-color .2s, box-shadow .2s;
}
.usuarios-page .usuarios-field input:focus, .usuarios-page .usuarios-field select:focus {
    border-color:#cf777c; box-shadow:0 0 0 3px rgba(181,18,27,.08);
}
.usuarios-page .usuarios-modal-footer {
    display:flex; justify-content:flex-end; flex-wrap:wrap; gap:10px;
    padding:16px 24px; background:#fafbfc; border-top:1px solid #eceef0;
    position:sticky; bottom:0;
}
@media(max-width:1100px) {
    .usuarios-page .usuarios-stats { grid-template-columns:repeat(2,minmax(0,1fr)); }
}
@media(max-width:650px) {
    .usuarios-page .usuarios-header { align-items:flex-start; }
    .usuarios-page .usuarios-header h1 { font-size:21px; }
    .usuarios-page .usuarios-header > button { width:100%; }
    .usuarios-page .usuarios-stats { grid-template-columns:1fr; }
    .usuarios-page .usuarios-panel-header { padding:18px 15px; }
    .usuarios-page .usuarios-toolbar { padding:14px; }
    .usuarios-page .usuarios-search { min-width:100%; max-width:none; }
    .usuarios-page .usuarios-filter { width:100%; }
    .usuarios-page .usuarios-filter select { flex:1; }
    .usuarios-page .usuarios-form-grid { grid-template-columns:1fr; }
    .usuarios-page .usuarios-modal.is-open { padding:8px; }
    .usuarios-page .usuarios-modal-dialog { max-height:96vh; }
    .usuarios-page .usuarios-modal-header, .usuarios-page .usuarios-modal-body { padding:16px; }
    .usuarios-page .usuarios-modal-footer { padding:13px 16px; }
}
</style>

<script>
(function () {
    const modal = document.getElementById('modalUsuario');
    const buscador = document.getElementById('buscarUsuario');
    const filtro = document.getElementById('filtroEstado');
    const filas = Array.from(document.querySelectorAll('#tablaUsuarios .fila-usuario'));
    const sinResultados = document.getElementById('sinResultados');

    window.abrirFormularioUsuario = function () {
        if (!modal) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        const primerCampo = modal.querySelector('input[name="nombre1"]');
        if (primerCampo) primerCampo.focus();
    };

    window.cerrarFormularioUsuario = function () {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    function filtrarUsuarios() {
        const texto = (buscador?.value || '').trim().toLocaleLowerCase();
        const estado = filtro?.value || '';
        let visibles = 0;

        filas.forEach(function (fila) {
            const coincideTexto = (fila.dataset.busqueda || '').includes(texto);
            const coincideEstado = !estado || fila.dataset.estado === estado;
            const mostrar = coincideTexto && coincideEstado;

            fila.style.display = mostrar ? '' : 'none';
            if (mostrar) visibles++;
        });

        if (sinResultados) {
            sinResultados.style.display = filas.length > 0 && visibles === 0 ? '' : 'none';
        }
    }

    if (buscador) buscador.addEventListener('input', filtrarUsuarios);
    if (filtro) filtro.addEventListener('change', filtrarUsuarios);

    const departamento = document.getElementById('cod_dep');
    const distrito = document.getElementById('cod_dis');

    function filtrarDistritos() {
        if (!departamento || !distrito) return;

        const codDepartamento = departamento.value;
        const opcionSeleccionada = distrito.value;

        Array.from(distrito.options).forEach(function (opcion) {
            if (!opcion.value) return;
            const pertenece = !codDepartamento ||
                opcion.dataset.departamento === codDepartamento;

            opcion.hidden = !pertenece;
            opcion.disabled = !pertenece;
        });

        const actual = distrito.options[distrito.selectedIndex];
        if (actual && actual.value && actual.disabled) {
            distrito.value = '';
        } else if (opcionSeleccionada) {
            distrito.value = opcionSeleccionada;
        }
    }

    if (departamento) departamento.addEventListener('change', filtrarDistritos);

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape' && modal?.classList.contains('is-open')) {
            window.cerrarFormularioUsuario();
        }
    });

    // Reabrir el formulario si la validación del servidor devolvió errores.
    @if($errors->any())
        window.abrirFormularioUsuario();
    @endif

    filtrarUsuarios();
    filtrarDistritos();
})();
</script>
@endsection
