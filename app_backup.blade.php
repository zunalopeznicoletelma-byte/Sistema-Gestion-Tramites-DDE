<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema de Gestión de Trámites')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6f8;
            color: #333;
        }

        /* CONTENEDOR PRINCIPAL */

        .sistema {
            display: flex;
            min-height: 100vh;
        }

        /* MENU LATERAL */

        .sidebar {
            width: 285px;
            background: #ffffff;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            border-right: 1px solid #eeeeee;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* LOGO */

        .logo-area {
            height: 120px;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            border-bottom: 1px solid #eeeeee;
        }

        .logo-area img {
            max-width: 220px;
            max-height: 90px;
            object-fit: contain;
        }

        /* INFORMACIÓN DEL USUARIO */

        .usuario-sidebar {
            background: #c8102e;
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .usuario-icono {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .usuario-sidebar strong {
            display: block;
            font-size: 16px;
            overflow-wrap: anywhere;
        }

        .usuario-sidebar small {
            opacity: 0.9;
        }

        /* TITULO DEL MENU */

        .menu-titulo {
            padding: 22px 20px 10px;
            font-size: 12px;
            font-weight: bold;
            color: #999;
            letter-spacing: 1px;
            flex-shrink: 0;
        }

        /* OPCIONES DEL MENU */

        .menu {
            padding: 0 16px 15px;
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 16px;
            margin-bottom: 5px;
            border-radius: 10px;
            color: #3d4147;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }

        .menu a i {
            font-size: 20px;
            width: 24px;
            min-width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .menu a:hover {
            background: #fff0f2;
            color: #c8102e;
        }

        .menu a.activo {
            background: #c8102e;
            color: white;
            box-shadow: 0 5px 12px rgba(200, 16, 46, 0.25);
        }

        /* CERRAR SESION */

        .cerrar-sesion {
            position: static;
            flex-shrink: 0;
            padding: 12px 16px;
            background: #ffffff;
            border-top: 1px solid #eeeeee;
        }

        .cerrar-sesion a {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            color: #c8102e;
            text-decoration: none;
            font-weight: bold;
            line-height: 1.5;
            white-space: nowrap;
            margin: 0;
        }

        .cerrar-sesion a:hover {
            background: #fff0f2;
            color: #8f0d14;
        }

        .cerrar-sesion a i {
            flex-shrink: 0;
            font-size: 20px;
            width: 24px;
            min-width: 24px;
            text-align: center;
        }

        /* CONTENIDO PRINCIPAL */

        .contenido-principal {
            margin-left: 285px;
            width: calc(100% - 285px);
            min-height: 100vh;
        }

        /* CABECERA */

        .barra-superior {
            min-height: 105px;
            background: white;
            border-bottom: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 35px;
            gap: 20px;
        }

        .titulo-sistema {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .titulo-icono {
            width: 45px;
            height: 45px;
            min-width: 45px;
            background: #fff0f2;
            color: #c8102e;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .titulo-sistema h1 {
            margin: 0;
            color: #c8102e;
            font-size: 21px;
            font-weight: bold;
        }

        .titulo-sistema span {
            color: #999;
            font-size: 13px;
        }

        .usuario-superior {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .usuario-superior i {
            width: 43px;
            height: 43px;
            min-width: 43px;
            border-radius: 50%;
            background: #fff0f2;
            color: #c8102e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* CONTENIDO DE LAS VISTAS */

        .contenido {
            padding: 30px;
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
                padding: 20px;
            }

            .titulo-sistema h1 {
                font-size: 18px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 75px;
            }

            .logo-area {
                height: 85px;
                min-height: 85px;
                padding: 8px;
            }

            .logo-area img {
                max-width: 60px;
                max-height: 65px;
            }

            .usuario-sidebar {
                justify-content: center;
                padding: 12px 5px;
            }

            .usuario-sidebar > div:last-child {
                display: none;
            }

            .usuario-icono {
                width: 42px;
                height: 42px;
                min-width: 42px;
                font-size: 21px;
            }

            .menu-titulo {
                padding: 15px 5px 10px;
                font-size: 9px;
                text-align: center;
                letter-spacing: 0;
            }

            .menu {
                padding: 0 8px 10px;
            }

            .menu a {
                justify-content: center;
                padding: 14px 5px;
                gap: 0;
            }

            .menu a i {
                font-size: 21px;
            }

            .menu a span {
                display: none;
            }

            .cerrar-sesion {
                padding: 10px 8px;
            }

            .cerrar-sesion a {
                justify-content: center;
                padding: 12px 5px;
                gap: 0;
            }

            .cerrar-sesion a span {
                display: none;
            }

            .cerrar-sesion a i {
                font-size: 21px;
            }

            .contenido-principal {
                margin-left: 75px;
                width: calc(100% - 75px);
            }

            .barra-superior {
                min-height: 85px;
                padding: 15px;
                gap: 10px;
            }

            .titulo-sistema {
                gap: 8px;
            }

            .titulo-icono {
                width: 36px;
                height: 36px;
                min-width: 36px;
                font-size: 18px;
            }

            .titulo-sistema h1 {
                font-size: 14px;
            }

            .titulo-sistema span {
                font-size: 11px;
            }

            .usuario-superior {
                gap: 5px;
                font-size: 12px;
            }

            .usuario-superior i {
                width: 35px;
                height: 35px;
                min-width: 35px;
                font-size: 17px;
            }

            .contenido {
                padding: 15px;
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

    <!-- MENU LATERAL -->

    <aside class="sidebar">

        <div class="logo-area">
            <img src="{{ asset('img/logo2.png') }}"
                 alt="Ministerio de Educación">
        </div>

        <div class="usuario-sidebar">

            <div class="usuario-icono">
                <i class="bi bi-person-fill"></i>
            </div>

            <div>
                <strong>{{ $nombreUsuario }}</strong>
                <small>
                    <i class="bi bi-check-circle-fill"></i>
                    {{ $nombreRol }}
                </small>
            </div>

        </div>

        <div class="menu-titulo">
            MENÚ PRINCIPAL
        </div>

        <nav class="menu">

            <!-- DASHBOARD: TODOS LOS ROLES -->

            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'activo' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>Dashboard</span>
            </a>

            <!-- USUARIOS: SOLO ADMINISTRADOR -->

            @if ($rol === 1)

                <a href="{{ route('usuarios.index') }}"
                   class="{{ request()->routeIs('usuarios.*') ? 'activo' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Usuarios</span>
                </a>

            @endif

            <!-- SOLICITUDES: ADMINISTRADOR Y TECNICO -->

            @if (in_array($rol, [1, 2]))

                <a href="{{ route('solicitudes.index') }}"
                   class="{{ request()->routeIs('solicitudes.index') ? 'activo' : '' }}">
                    <i class="bi bi-file-earmark-check-fill"></i>
                    <span>Solicitudes de trámites</span>
                </a>

            @endif

           
<!-- REVISION TECNICA: SOLO TECNICO -->

@if ($rol === 2)

    <a href="{{ route('solicitudes.index') }}"
       class="{{ request()->is('solicitudes*') && !request()->is('solicitudes/*/historial') ? 'activo' : '' }}">
        <i class="bi bi-clipboard-check"></i>
        <span>Revisión técnica de solicitudes</span>
    </a>

    <a href="{{ route('solicitudes.index') }}#historial"
       class="{{ request()->is('solicitudes/*/historial') ? 'activo' : '' }}">
        <i class="bi bi-clock-history"></i>
        <span>Historial de solicitudes</span>
    </a>

@endif


            <!-- TIPOS DE TRAMITES: SOLO ADMINISTRADOR -->

            @if ($rol === 1)

                <a href="{{ route('tipos-tramites') }}"
                   class="{{ request()->is('tipos-tramites*') ? 'activo' : '' }}">
                    <i class="bi bi-list-check"></i>
                    <span>Tipos de trámites</span>
                </a>

            @endif

            <!-- INSTITUTOS: TODOS LOS ROLES -->

            <a href="{{ url('/institutos') }}"
               class="{{ request()->is('institutos*') ? 'activo' : '' }}">
                <i class="bi bi-building-fill"></i>
                <span>Institutos</span>
            </a>

            <!-- PLANES DE ESTUDIO: TODOS LOS ROLES -->

            <a href="{{ url('/planes-estudio') }}"
               class="{{ request()->is('planes-estudio*') ? 'activo' : '' }}">
                <i class="bi bi-book-fill"></i>
                <span>Planes de estudio</span>
            </a>

            <!-- REPORTES: ADMINISTRADOR Y TECNICO -->

            @if (in_array($rol, [1, 2]))

                <a href="{{ url('/reportes') }}"
                   class="{{ request()->is('reportes*') ? 'activo' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Reportes administrativos</span>
                </a>

            @endif

            <!-- DIRECTIVO Y USUARIO SOLICITANTE -->

            @if (in_array($rol, [3, 7]))

                <a href="{{ route('solicitudes.index') }}"
                   class="{{ request()->routeIs('solicitudes.*') ? 'activo' : '' }}">
                    <i class="bi bi-file-earmark-person"></i>
                    <span>Mis solicitudes</span>
                </a>

            @endif

        </nav>

        <!-- CERRAR SESION -->

        <div class="cerrar-sesion">

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar sesión</span>
            </a>

            <form id="logout-form"
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
                    <i class="bi bi-bank2"></i>
                </div>

                <div>
                    <h1>Sistema de Gestión de Trámites</h1>
                    <span>
                        Dirección Departamental de Educación Cochabamba
                    </span>
                </div>

            </div>

            <div class="usuario-superior">

                <i class="bi bi-person-fill"></i>

                <span>{{ $nombreUsuario }}</span>

            </div>

        </header>

        <section class="contenido">
            @yield('content')
        </section>

    </main>

</div>

</body>
</html>
/*