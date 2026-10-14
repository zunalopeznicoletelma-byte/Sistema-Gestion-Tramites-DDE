
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema de Gestión de Trámites')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          rel="stylesheet">

    <style>
        :root {
            --rojo-principal: #b5121b;
            --rojo-oscuro: #8f0d14;
            --rojo-claro: #d71920;
            --rojo-suave: #fff1f2;
            --blanco: #ffffff;
            --gris-fondo: #f5f6f8;
            --gris-texto: #6b7280;
            --gris-oscuro: #343a40;
            --borde: #eeeeee;
            --sombra: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            min-height: 100%;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--gris-fondo);
            color: #333;
        }

        body {
            overflow-x: hidden;
        }

        .sistema {
            min-height: 100vh;
        }

        /* BARRA LATERAL */

        .sidebar {
            width: 265px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--blanco);
            z-index: 1000;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* LOGO */

        .sidebar-logo {
            height: 110px;
            min-height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 15px;
            border-bottom: 1px solid var(--borde);
            background: white;
            flex-shrink: 0;
        }

        .sidebar-logo img {
            width: 210px;
            height: 85px;
            object-fit: contain;
        }

        /* USUARIO */

        .sidebar-user {
            padding: 17px 15px;
            background: linear-gradient(
                135deg,
                var(--rojo-principal),
                var(--rojo-oscuro)
            );
            color: white;
            flex-shrink: 0;
        }

        .sidebar-user-content {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .sidebar-user-icon {
            width: 45px;
            height: 45px;
            min-width: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .sidebar-user-info {
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 165px;
        }

        .sidebar-user-role {
            font-size: 11px;
            opacity: 0.9;
            margin-top: 2px;
        }

        /* MENÚ */

        .sidebar-menu {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 10px;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: #f5f5f5;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #c8c8c8;
            border-radius: 10px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: var(--rojo-principal);
        }

        .menu-title {
            padding: 7px 12px;
            font-size: 10px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        
.sidebar-menu .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    min-width: 0;
    padding: 20px 13px;
    margin-bottom: 4px;
    border-radius: 8px;
    color: #555;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.25s ease, color 0.25s ease;
}


        .sidebar-menu .nav-link i {
            width: 24px;
            min-width: 24px;
            text-align: center;
            font-size: 20px;
        }

        .sidebar-menu .nav-link span {
            min-width: 0;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-menu .nav-link:hover {
            background: var(--rojo-suave);
            color: var(--rojo-principal);
        }

        .sidebar-menu .nav-link.active {
            background: var(--rojo-principal);
            color: white;
            box-shadow: 0 4px 10px rgba(181, 18, 27, 0.25);
        }

        .sidebar-menu .nav-link.active:hover {
            background: var(--rojo-oscuro);
            color: white;
        }

        /* CERRAR SESIÓN */

        .sidebar-footer {
            flex-shrink: 0;
            background: white;
            border-top: 1px solid var(--borde);
            padding: 8px 10px;
        }

        .sidebar-footer .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 13px;
            margin: 0;
            border-radius: 8px;
            color: #dc3545;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
        }

        .sidebar-footer .nav-link i {
            width: 22px;
            min-width: 22px;
            text-align: center;
            font-size: 20px;
        }

        .sidebar-footer .nav-link:hover {
            background: #fff0f1;
            color: var(--rojo-principal);
        }

        /* CONTENIDO PRINCIPAL */

        .contenido-principal {
            margin-left: 265px;
            width: calc(100% - 265px);
            min-height: 100vh;
        }

        /* BARRA SUPERIOR */

        .barra-superior {
            min-height: 85px;
            position: sticky;
            top: 0;
            z-index: 900;
            background: white;
            border-bottom: 1px solid var(--borde);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 30px;
            gap: 20px;
        }

        .titulo-sistema {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .titulo-icono {
            width: 45px;
            height: 45px;
            min-width: 45px;
            background: var(--rojo-suave);
            color: var(--rojo-principal);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .titulo-sistema h1 {
            margin: 0;
            color: var(--rojo-principal);
            font-size: 19px;
            font-weight: 700;
        }

        .titulo-sistema span {
            color: #999;
            font-size: 12px;
        }

        .usuario-superior {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .usuario-superior i {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 50%;
            background: var(--rojo-suave);
            color: var(--rojo-principal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
        }

        /* CONTENIDO DE LAS VISTAS */

        .contenido {
            padding: 28px 30px;
            min-width: 0;
        }

        .contenido .card,
        .contenido .section-card {
            border: 1px solid var(--borde);
            border-radius: 12px;
            box-shadow: var(--sombra);
        }

        .contenido .card-header {
            background: white;
            border-bottom: 1px solid var(--borde);
            font-weight: 600;
        }

        .contenido .table {
            vertical-align: middle;
        }

        .contenido .table thead th {
            background: #fafafa;
            color: var(--gris-oscuro);
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .contenido .btn-danger {
            background: var(--rojo-principal);
            border-color: var(--rojo-principal);
        }

        .contenido .btn-danger:hover {
            background: var(--rojo-oscuro);
            border-color: var(--rojo-oscuro);
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {
            .sidebar {
                width: 230px;
            }

            .contenido-principal {
                margin-left: 230px;
                width: calc(100% - 230px);
            }

            .contenido {
                padding: 20px;
            }

            .barra-superior {
                padding: 16px 20px;
            }

            .titulo-sistema h1 {
                font-size: 16px;
            }

            .sidebar-menu .nav-link {
                font-size: 14px;
                gap: 9px;
                padding-left: 10px;
                padding-right: 10px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 75px;
            }

            .sidebar-logo {
                height: 85px;
                min-height: 85px;
                padding: 8px;
            }

            .sidebar-logo img {
                width: 60px;
                height: 65px;
            }

            .sidebar-user {
                padding: 12px 5px;
            }

            .sidebar-user-content {
                justify-content: center;
            }

            .sidebar-user-info {
                display: none;
            }

            .sidebar-user-icon {
                width: 42px;
                height: 42px;
                min-width: 42px;
                font-size: 19px;
            }

            .menu-title {
    padding: 8px 12px;
    font-size: 30px;
    font-weight: 700;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
}

            .sidebar-menu {
                padding: 10px 6px;
            }

            .sidebar-menu .nav-link {
                justify-content: center;
                padding: 13px 4px;
                gap: 0;
            }

            .sidebar-menu .nav-link span {
                display: none;
            }

            .sidebar-menu .nav-link i {
                font-size: 19px;
            }

            .sidebar-footer {
                padding: 8px 6px;
            }

            .sidebar-footer .nav-link {
                justify-content: center;
                padding: 12px 4px;
            }

            .sidebar-footer .nav-link span {
                display: none;
            }

            .contenido-principal {
                margin-left: 75px;
                width: calc(100% - 75px);
            }

            .barra-superior {
                min-height: 75px;
                padding: 12px;
                gap: 8px;
            }

            .titulo-sistema {
                gap: 7px;
            }

            .titulo-icono {
                width: 34px;
                height: 34px;
                min-width: 34px;
                font-size: 17px;
            }

            .titulo-sistema h1 {
                font-size: 12px;
            }

            .titulo-sistema span {
                display: none;
            }

            .usuario-superior {
                gap: 5px;
                font-size: 10px;
            }

            .usuario-superior i {
                width: 32px;
                height: 32px;
                min-width: 32px;
                font-size: 15px;
            }

            .contenido {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

@php
    $rol = (int) session('cod_acceso');

    $nombreUsuario = session(
        'usuario_nombre_completo',
        session('usuario_nombre', 'Usuario')
    );

    $nombreRol = [
        1 => 'Administrador',
        2 => 'Técnico Departamental',
        3 => 'Directivo de Instituto',
        7 => 'Usuario Solicitante',
    ][$rol] ?? 'Usuario del sistema';
@endphp

<div class="sistema">

    <!-- BARRA LATERAL -->
    <aside class="sidebar">

        <div class="sidebar-logo">
            <img src="{{ asset('img/logo.png') }}"
                 alt="Logo institucional"
                 onerror="this.onerror=null; this.src='{{ asset('img/logo2.png') }}';">
        </div>

        <div class="sidebar-user">
            <div class="sidebar-user-content">
                <div class="sidebar-user-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ $nombreUsuario }}</div>
                    <div class="sidebar-user-role">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ $nombreRol }}
                    </div>
                </div>
            </div>
        </div>

        <nav class="sidebar-menu">

            <div class="menu-title">Menú principal</div>

            <!-- ACCESO GENERAL -->
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <!-- ADMINISTRADOR -->
            @if ($rol === 1)

                <div class="menu-title">Administración</div>

                <a href="{{ route('usuarios.index') }}"
                   class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Usuarios</span>
                </a>

                <a href="{{ route('solicitudes.index') }}"
                   class="nav-link {{ request()->routeIs('solicitudes.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-check"></i>
                    <span>Solicitudes de trámites</span>
                </a>

                <a href="{{ url('/tipos-tramites') }}"
                   class="nav-link {{ request()->is('tipos-tramites*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tipos de trámites</span>
                </a>

                <a href="{{ url('/institutos') }}"
                   class="nav-link {{ request()->is('institutos*') ? 'active' : '' }}">
                    <i class="fa-solid fa-school"></i>
                    <span>Institutos</span>
                </a>

                <a href="{{ url('/planes-estudio') }}"
                   class="nav-link {{ request()->is('planes-estudio*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Planes de estudio</span>
                </a>

                <a href="{{ url('/reportes') }}"
                   class="nav-link {{ request()->is('reportes*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-column"></i>
                    <span>Reportes administrativos</span>
                </a>

            @endif

            <!-- TÉCNICO DEPARTAMENTAL -->
            @if ($rol === 2)

                <div class="menu-title">Gestión técnica</div>

                <a href="{{ route('solicitudes.index') }}"
                   class="nav-link {{ request()->routeIs('solicitudes.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-check"></i>
                    <span>Solicitudes de trámites</span>
                </a>

                <a href="{{ route('solicitudes.index') }}"
                   class="nav-link">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Revisión técnica de solicitudes</span>
                </a>

                <a href="{{ route('solicitudes.index') }}"
                   class="nav-link">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Historial de solicitudes</span>
                </a>

                <a href="{{ url('/institutos') }}"
                   class="nav-link {{ request()->is('institutos*') ? 'active' : '' }}">
                    <i class="fa-solid fa-school"></i>
                    <span>Institutos</span>
                </a>

                <a href="{{ url('/planes-estudio') }}"
                   class="nav-link {{ request()->is('planes-estudio*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Planes de estudio</span>
                </a>

                <a href="{{ url('/reportes') }}"
                   class="nav-link {{ request()->is('reportes*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-column"></i>
                    <span>Reportes administrativos</span>
                </a>

            @endif

            <!-- DIRECTIVO Y USUARIO SOLICITANTE -->
            @if (in_array($rol, [3, 7]))

                <div class="menu-title">Mis trámites</div>

                <a href="{{ route('solicitudes.index') }}"
                   class="nav-link {{ request()->routeIs('solicitudes.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-check"></i>
                    <span>Mis solicitudes</span>
                </a>

                <a href="{{ url('/solicitudes/create') }}"
                   class="nav-link {{ request()->is('solicitudes/create') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    <span>Nueva solicitud</span>
                </a>

                <a href="{{ url('/institutos') }}"
                   class="nav-link {{ request()->is('institutos*') ? 'active' : '' }}">
                    <i class="fa-solid fa-school"></i>
                    <span>Institutos</span>
                </a>

                <a href="{{ url('/planes-estudio') }}"
                   class="nav-link {{ request()->is('planes-estudio*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Planes de estudio</span>
                </a>

            @endif

        </nav>

        <!-- CERRAR SESIÓN -->
        <div class="sidebar-footer">

            <a href="{{ route('logout') }}"
               class="nav-link"
               onclick="event.preventDefault(); document.getElementById('logout-form-layout').submit();">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar sesión</span>
            </a>

            <form id="logout-form-layout"
                  action="{{ route('logout') }}"
                  method="POST"
                  style="display: none;">
                @csrf
            </form>

        </div>

    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido-principal">

        <header class="barra-superior">

            <div class="titulo-sistema">
                <div class="titulo-icono">
                    <i class="fa-solid fa-building-columns"></i>
                </div>

                <div>
                    <h1>@yield('page-title', 'Sistema de Gestión de Trámites')</h1>
                    <span>Dirección Departamental de Educación Cochabamba</span>
                </div>
            </div>

            <div class="usuario-superior">
                <i class="fa-solid fa-user"></i>
                <span>{{ $nombreUsuario }}</span>
            </div>

        </header>

        <section class="contenido">
            @yield('content')
        </section>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
