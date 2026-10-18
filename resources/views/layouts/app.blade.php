<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema Web para la Gestión de Trámites - DDE Cochabamba')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --dde-rojo: #b5121b;
            --dde-rojo-oscuro: #8f0d14;
            --dde-rojo-claro: #d71920;
            --dde-rojo-suave: #fff1f2;
            --dde-fondo: #f5f6f8;
            --dde-texto: #343a40;
            --dde-borde: #eeeeee;
            --dde-sombra: 0 5px 20px rgba(0, 0, 0, .08);
            --dde-sidebar: 265px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--dde-fondo);
            color: var(--dde-texto);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--dde-sidebar);
            background: #fff;
            border-right: 1px solid var(--dde-borde);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }

        .sidebar-logo {
            min-height: 110px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--dde-borde);
        }

        .sidebar-logo img {
            max-width: 210px;
            max-height: 85px;
            object-fit: contain;
        }

        .sidebar-user {
            padding: 18px 20px;
            color: #fff;
            background: linear-gradient(135deg, var(--dde-rojo), var(--dde-rojo-oscuro));
        }

        .sidebar-user .user-icon {
            width: 43px;
            height: 43px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .18);
            font-size: 20px;
            flex-shrink: 0;
        }

        .sidebar-user .user-name {
            font-weight: 700;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .sidebar-user .user-role {
            font-size: 11px;
            opacity: .9;
            margin-top: 3px;
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 15px 0;
        }

        .menu-heading {
            padding: 10px 20px 7px;
            color: #8b929a;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 20px;
            color: #555d65;
            font-size: 13px;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: background .2s, color .2s;
        }

        .nav-link i {
            width: 22px;
            min-width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .nav-link:hover {
            color: var(--dde-rojo);
            background: var(--dde-rojo-suave);
        }

        .nav-link.active {
            color: var(--dde-rojo);
            background: var(--dde-rojo-suave);
            border-left-color: var(--dde-rojo);
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--dde-borde);
            background: #fff;
        }

        .logout-button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
            padding: 12px 16px;
            border: 0;
            border-radius: 7px;
            color: #a51a22;
            background: #fff1f2;
            font-size: 13px;
            font-weight: 600;
        }

        .logout-button:hover {
            background: #ffe0e3;
        }

        .main-content {
            min-height: 100vh;
            margin-left: var(--dde-sidebar);
        }

        .topbar {
            height: 70px;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: #fff;
            border-bottom: 1px solid var(--dde-borde);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .025);
        }

        .topbar-title {
            font-size: 17px;
            font-weight: 700;
            color: #343a40;
        }

        .topbar-subtitle {
            color: #818891;
            font-size: 12px;
            margin-top: 2px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .topbar-user-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: var(--dde-rojo);
            font-size: 16px;
            flex-shrink: 0;
        }

        .page-content {
            padding: 28px;
        }

        .welcome-banner {
            padding: 25px 28px;
            margin-bottom: 24px;
            color: #fff;
            border-radius: 12px;
            background: linear-gradient(120deg, var(--dde-rojo), var(--dde-rojo-oscuro));
            box-shadow: var(--dde-sombra);
        }

        .welcome-banner h1 {
            font-size: 23px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-banner p {
            margin: 0;
            opacity: .92;
        }

        .section-card {
            margin-bottom: 24px;
            border: 1px solid var(--dde-borde);
            border-radius: 10px;
            background: #fff;
            box-shadow: var(--dde-sombra);
            overflow: hidden;
        }

        .section-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--dde-borde);
        }

        .section-card-header h2,
        .section-card-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .section-card-body {
            padding: 22px;
        }

        .btn-dde {
            background: var(--dde-rojo);
            border-color: var(--dde-rojo);
            color: #fff;
        }

        .btn-dde:hover,
        .btn-dde:focus {
            background: var(--dde-rojo-oscuro);
            border-color: var(--dde-rojo-oscuro);
            color: #fff;
        }

        .table thead th {
            background: #fff1f2;
            color: #6e2025;
            font-weight: 700;
            white-space: nowrap;
        }

        .alert {
            border-radius: 8px;
        }

        @media (max-width: 900px) {
            :root {
                --dde-sidebar: 225px;
            }

            .page-content {
                padding: 20px;
            }

            .topbar {
                padding: 0 20px;
            }
        }

        @media (max-width: 650px) {
            .sidebar {
                width: 75px;
            }

            .sidebar-logo {
                padding: 8px;
                min-height: 80px;
            }

            .sidebar-logo img {
                width: 58px;
                height: auto;
            }

            .sidebar-user {
                padding: 12px 8px;
                display: flex;
                justify-content: center;
            }

            .sidebar-user .user-details,
            .nav-link span,
            .menu-heading,
            .logout-button span {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 15px 8px;
                border-left-width: 3px;
            }

            .nav-link i {
                font-size: 19px;
            }

            .sidebar-footer {
                padding: 8px;
            }

            .logout-button {
                justify-content: center;
                padding: 13px 5px;
            }

            .main-content {
                margin-left: 75px;
            }

            .page-content {
                padding: 14px;
            }

            .topbar {
                height: auto;
                min-height: 65px;
                padding: 12px;
            }

            .topbar-title {
                font-size: 14px;
            }

            .topbar-subtitle,
            .topbar-user-details {
                display: none;
            }

            .welcome-banner {
                padding: 20px;
            }

            .welcome-banner h1 {
                font-size: 19px;
            }
        }
    </style>

    @stack('styles')
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

        $rutaActual = request()->path();
    @endphp

    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="{{ url('/dashboard') }}" aria-label="Ir al inicio">
                <img src="{{ asset('img/logo2.png') }}" alt="Ministerio de Educación - DDE Cochabamba">
            </a>
        </div>

        <div class="sidebar-user">
            <div class="d-flex align-items-center gap-3">
                <div class="user-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="user-details">
                    <div class="user-name">{{ $nombreUsuario }}</div>
                    <div class="user-role">{{ $nombreRol }}</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-heading">Menú principal</div>

            <a href="{{ url('/dashboard') }}"
               class="nav-link {{ $rutaActual === 'dashboard' ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            {{-- ADMINISTRADOR --}}
            @if ($rol === 1)
                <div class="menu-heading">Administración</div>

                <a href="{{ url('/usuarios') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'usuarios') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Gestión de usuarios</span>
                </a>

                <a href="{{ url('/solicitudes') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'solicitudes') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Solicitudes</span>
                </a>

                <a href="{{ url('/tipos-tramites') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'tipos-tramites') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tipos de trámites</span>
                </a>

                <a href="{{ url('/institutos') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'institutos') ? 'active' : '' }}">
                    <i class="fa-solid fa-school"></i>
                    <span>Institutos</span>
                </a>

                <a href="{{ url('/planes-estudio') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'planes-estudio') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Planes de estudio</span>
                </a>

                <a href="{{ url('/reportes') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'reportes') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-column"></i>
                    <span>Reportes administrativos</span>
                </a>
            @endif

            {{-- TÉCNICO DEPARTAMENTAL --}}
            @if ($rol === 2)
                <div class="menu-heading">Gestión de trámites</div>

                <a href="{{ url('/solicitudes') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'solicitudes') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Revisar solicitudes</span>
                </a>

               
<a href="{{ route('solicitudes.historialGeneral') }}"
   class="nav-link {{ str_starts_with($rutaActual, 'historial-solicitudes') ? 'active' : '' }}">
    <i class="fa-solid fa-clock-rotate-left"></i>
    <span>Historial de solicitudes</span>
</a>


                <a href="{{ url('/institutos') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'institutos') ? 'active' : '' }}">
                    <i class="fa-solid fa-school"></i>
                    <span>Institutos</span>
                </a>

                <a href="{{ url('/planes-estudio') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'planes-estudio') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Planes de estudio</span>
                </a>

                <a href="{{ url('/reportes') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'reportes') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-column"></i>
                    <span>Informes y reportes</span>
                </a>
            @endif

            {{-- DIRECTIVO DE INSTITUTO --}}
            @if ($rol === 3)
                <div class="menu-heading">Gestión institucional</div>

                <a href="{{ route('solicitudes.index') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'solicitudes') && !str_starts_with($rutaActual, 'solicitudes/create') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Mis solicitudes</span>
                </a>

                <a href="{{ route('solicitudes.create') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'solicitudes/create') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    <span>Nueva solicitud</span>
                </a>

                <a href="{{ url('/institutos') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'institutos') ? 'active' : '' }}">
                    <i class="fa-solid fa-school"></i>
                    <span>Institutos</span>
                </a>

                <a href="{{ url('/planes-estudio') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'planes-estudio') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Planes de estudio</span>
                </a>

                <a href="{{ url('/tipos-tramites') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'tipos-tramites') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tipos de trámites</span>
                </a>
            @endif

            {{-- USUARIO SOLICITANTE --}}
            @if ($rol === 7)
                <div class="menu-heading">Mis trámites</div>

                <a href="{{ route('solicitudes.index') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'solicitudes') && !str_starts_with($rutaActual, 'solicitudes/create') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Mis solicitudes</span>
                </a>

                <a href="{{ route('solicitudes.create') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'solicitudes/create') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    <span>Nueva solicitud</span>
                </a>

                <a href="{{ url('/institutos') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'institutos') ? 'active' : '' }}">
                    <i class="fa-solid fa-school"></i>
                    <span>Institutos</span>
                </a>

                <a href="{{ url('/planes-estudio') }}"
                   class="nav-link {{ str_starts_with($rutaActual, 'planes-estudio') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Planes de estudio</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Sistema Web para la Gestión de Trámites')</div>
                <div class="topbar-subtitle">
                    Dirección Departamental de Educación Cochabamba
                </div>
            </div>

            <div class="topbar-user">
                <div class="topbar-user-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="topbar-user-details">
                    <div class="fw-bold">{{ $nombreUsuario }}</div>
                    <div class="text-muted small">{{ $nombreRol }}</div>
                </div>
            </div>
        </header>

        <div class="page-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Verifica los siguientes datos:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
