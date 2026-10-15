<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Gestión de Trámites</title>


    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <style>

        /* =====================================================
           VARIABLES
        ===================================================== */

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


        /* =====================================================
           GENERAL
        ===================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html,
        body {
            width: 100%;
            min-height: 100%;

            font-family: "Segoe UI", Arial, sans-serif;

            background: var(--gris-fondo);

            color: #333;
        }


        body {
            overflow-x: hidden;
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

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


        /* =====================================================
           LOGO
        ===================================================== */

        .sidebar-logo {

            height: 110px;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 10px 15px;

            border-bottom: 1px solid var(--borde);

            flex-shrink: 0;

            background: white;
        }


        .sidebar-logo img {

            width: 210px;

            height: 85px;

            object-fit: contain;
        }


        /* Si no existe la imagen */
        .logo-alternativo {

            display: none;

            text-align: center;

            color: var(--rojo-principal);

            font-weight: 800;

            font-size: 17px;

            line-height: 1.2;
        }


        /* =====================================================
           USUARIO
        ===================================================== */

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
        }


        .sidebar-user-icon {

            width: 45px;

            height: 45px;

            min-width: 45px;

            border-radius: 50%;

            background: rgba(255,255,255,0.18);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 20px;
        }


        .sidebar-user-name {

            font-size: 14px;

            font-weight: 600;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }


        .sidebar-user-role {

            font-size: 11px;

            opacity: 0.85;

            margin-top: 2px;
        }


        /* =====================================================
           MENÚ SCROLL
        ===================================================== */

        .sidebar-menu {

            flex: 1;

            min-height: 0;

            overflow-y: auto;

            padding: 12px 10px;
        }


        /* Scrollbar */

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


        /* =====================================================
           TITULO MENU
        ===================================================== */

        .menu-title {

            padding: 7px 12px;

            font-size: 10px;

            font-weight: 700;

            color: #999;

            text-transform: uppercase;

            letter-spacing: 1px;
        }


        /* =====================================================
           LINKS
        ===================================================== */

        .nav-link {

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 11px 13px;

            margin-bottom: 4px;

            border-radius: 8px;

            color: #555;

            font-size: 13px;

            font-weight: 500;

            text-decoration: none;

            transition: all 0.25s ease;
        }


        .nav-link i {

            width: 22px;

            min-width: 22px;

            text-align: center;

            font-size: 16px;
        }


        .nav-link:hover {

            background: var(--rojo-suave);

            color: var(--rojo-principal);

            transform: translateX(3px);
        }


        .nav-link.active {

            background: var(--rojo-principal);

            color: white;

            box-shadow:
                0 4px 10px rgba(181, 18, 27, 0.25);
        }


        .nav-link.active:hover {

            background: var(--rojo-oscuro);

            color: white;

            transform: none;
        }


        /* =====================================================
           FOOTER SIDEBAR
        ===================================================== */

        .sidebar-footer {

            flex-shrink: 0;

            background: white;

            border-top: 1px solid var(--borde);

            padding: 8px 10px;
        }


        .sidebar-footer .nav-link {

            margin-bottom: 0;

            color: #dc3545;

            font-weight: 600;
        }


        .sidebar-footer .nav-link:hover {

            background: #fff0f1;

            color: var(--rojo-principal);

            transform: none;
        }


        /* =====================================================
           CONTENIDO PRINCIPAL
        ===================================================== */

        .main-content {

            margin-left: 265px;

            min-height: 100vh;

            display: flex;

            flex-direction: column;
        }


        /* =====================================================
           TOPBAR
        ===================================================== */

        .topbar {

            height: 70px;

            background: white;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 30px;

            box-shadow: 0 2px 10px rgba(0,0,0,0.05);

            position: sticky;

            top: 0;

            z-index: 900;
        }


        .topbar-left {

            display: flex;

            align-items: center;

            gap: 12px;
        }


        .topbar-logo-icon {

            width: 40px;

            height: 40px;

            border-radius: 10px;

            background: var(--rojo-suave);

            color: var(--rojo-principal);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 18px;
        }


        .topbar-title {

            font-weight: 700;

            color: var(--rojo-principal);

            font-size: 18px;
        }


        .topbar-subtitle {

            font-size: 12px;

            color: #999;

            margin-top: 2px;
        }


        /* =====================================================
           USUARIO TOPBAR
        ===================================================== */

        .topbar-user {

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .topbar-user-icon {

            width: 38px;

            height: 38px;

            border-radius: 50%;

            background: var(--rojo-suave);

            color: var(--rojo-principal);

            display: flex;

            align-items: center;

            justify-content: center;
        }


        .topbar-user-name {

            font-size: 13px;

            font-weight: 600;
        }


        /* =====================================================
           CONTENIDO
        ===================================================== */

        .content {

            padding: 30px;
        }


        /* =====================================================
           BANNER
        ===================================================== */

        .welcome-banner {

            background:
                linear-gradient(
                    135deg,
                    rgba(181,18,27,0.98),
                    rgba(143,13,20,0.96)
                );

            border-radius: 15px;

            padding: 28px;

            color: white;

            margin-bottom: 25px;

            position: relative;

            overflow: hidden;

            box-shadow: var(--sombra);
        }


        .welcome-banner::before {

            content: "";

            position: absolute;

            width: 230px;

            height: 230px;

            border-radius: 50%;

            border: 35px solid rgba(255,255,255,0.05);

            right: -70px;

            top: -100px;
        }


        .welcome-banner::after {

            content: "";

            position: absolute;

            width: 100px;

            height: 100px;

            border-radius: 50%;

            background: rgba(255,255,255,0.05);

            right: 150px;

            bottom: -60px;
        }


        .welcome-icon {

            width: 55px;

            height: 55px;

            border-radius: 12px;

            background: rgba(255,255,255,0.15);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 24px;

            margin-bottom: 15px;

            position: relative;

            z-index: 2;
        }


        .welcome-banner h2 {

            font-size: 24px;

            font-weight: 700;

            margin-bottom: 8px;

            position: relative;

            z-index: 2;
        }


        .welcome-banner p {

            margin: 0;

            opacity: 0.9;

            font-size: 14px;

            position: relative;

            z-index: 2;
        }


        /* =====================================================
           TARJETAS
        ===================================================== */

        .stats-card {

            background: white;

            border-radius: 14px;

            padding: 20px;

            box-shadow: var(--sombra);

            border: 1px solid var(--borde);

            transition: all 0.25s ease;

            height: 100%;
        }


        .stats-card:hover {

            transform: translateY(-4px);

            box-shadow: 0 10px 25px rgba(0,0,0,0.10);
        }


        .stats-icon {

            width: 50px;

            height: 50px;

            border-radius: 12px;

            background: var(--rojo-suave);

            color: var(--rojo-principal);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 20px;

            margin-bottom: 15px;
        }


        .stats-number {

            font-size: 28px;

            font-weight: 700;

            color: #222;
        }


        .stats-title {

            color: #888;

            font-size: 13px;
        }


        /* =====================================================
           SECCIÓN
        ===================================================== */

        .section-card {

            background: white;

            border-radius: 14px;

            box-shadow: var(--sombra);

            border: 1px solid var(--borde);

            overflow: hidden;
        }


        .section-header {

            padding: 18px 22px;

            border-bottom: 1px solid var(--borde);

            display: flex;

            justify-content: space-between;

            align-items: center;
        }


        .section-title {

            margin: 0;

            font-size: 16px;

            font-weight: 700;

            color: #333;
        }


        .section-title i {

            color: var(--rojo-principal);

            margin-right: 8px;
        }


        /* =====================================================
           TABLA
        ===================================================== */

        .table-container {

            overflow-x: auto;
        }


        .table {

            margin-bottom: 0;
        }


        .table thead th {

            background: #fafafa;

            color: #666;

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 0.4px;

            padding: 14px;

            white-space: nowrap;
        }


        .table tbody td {

            padding: 14px;

            font-size: 13px;

            vertical-align: middle;
        }


        .table tbody tr {

            transition: 0.2s;
        }


        .table tbody tr:hover {

            background: var(--rojo-suave);
        }


        /* =====================================================
           ESTADOS
        ===================================================== */

        .badge-activo {

            background: #e9f8ef;

            color: #198754;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 11px;
        }


        .badge-inactivo {

            background: #fff0f1;

            color: #dc3545;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 11px;
        }


        /* =====================================================
           BOTÓN ROJO
        ===================================================== */

        .btn-rojo {

            background: var(--rojo-principal);

            border: none;

            color: white;

            border-radius: 7px;

            padding: 8px 14px;

            font-size: 13px;

            text-decoration: none;
        }


        .btn-rojo:hover {

            background: var(--rojo-oscuro);

            color: white;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .main-footer {

            margin-top: auto;

            padding: 20px 30px;

            text-align: center;

            color: #999;

            font-size: 12px;
        }


        /* =====================================================
           RESPONSIVE TABLET
        ===================================================== */

        @media (max-width: 992px) {

            .sidebar {

                width: 230px;
            }


            .main-content {

                margin-left: 230px;
            }


            .topbar {

                padding: 0 20px;
            }


            .content {

                padding: 20px;
            }


            .sidebar-logo img {

                width: 180px;
            }
        }


        /* =====================================================
           RESPONSIVE CELULAR
        ===================================================== */

        @media (max-width: 768px) {

            .sidebar {

                width: 75px;
            }


            .sidebar-logo {

                height: 80px;

                padding: 7px;
            }


            .sidebar-logo img {

                width: 58px;

                height: 58px;
            }


            .sidebar-user {

                padding: 12px 8px;
            }


            .sidebar-user-content {

                justify-content: center;
            }


            .sidebar-user-icon {

                width: 40px;

                height: 40px;

                min-width: 40px;
            }


            .sidebar-user-name,
            .sidebar-user-role {

                display: none;
            }


            .sidebar-menu {

                padding: 10px 7px;
            }


            .menu-title {

                display: none;
            }


            .nav-link {

                justify-content: center;

                padding: 12px 5px;
            }


            .nav-link span {

                display: none;
            }


            .nav-link i {

                margin: 0;

                font-size: 17px;
            }


            .sidebar-footer {

                padding: 8px 7px;
            }


            .sidebar-footer .nav-link {

                justify-content: center;
            }


            .main-content {

                margin-left: 75px;
            }


            .topbar {

                padding: 0 15px;

                height: 60px;
            }


            .topbar-subtitle {

                display: none;
            }


            .topbar-user-name {

                display: none;
            }


            .topbar-title {

                font-size: 14px;
            }


            .topbar-logo-icon {

                width: 35px;

                height: 35px;
            }


            .content {

                padding: 15px;
            }


            .welcome-banner {

                padding: 22px;
            }


            .welcome-banner h2 {

                font-size: 20px;
            }
        }


        /* TÍTULO DEL MENÚ */

        .sidebar-title {

            font-size: 16px;

            font-weight: 700;

            padding: 15px 20px 10px;

            color: #8f0d14;
        }


        /* OPCIONES DEL MENÚ */

        .sidebar-menu .nav-link,
        .sidebar-footer .nav-link {

            display: flex;

            align-items: center;

            gap: 14px;

            padding: 14px 20px;

            margin: 4px 10px;

            border-radius: 10px;

            font-size: 16px;

            font-weight: 600;

            color: #343a40;

            transition: all 0.2s ease;
        }


        /* ÍCONOS */

        .sidebar-menu .nav-link i,
        .sidebar-footer .nav-link i {

            width: 24px;

            font-size: 19px;

            text-align: center;
        }


        /* EFECTO AL PASAR EL MOUSE */

        .sidebar-menu .nav-link:hover,
        .sidebar-footer .nav-link:hover {

            background: #fff1f2;

            color: #b5121b;

            transform: translateX(3px);
        }


        /* OPCIÓN ACTIVA */

        .sidebar-menu .nav-link.active {

            background: #b5121b;

            color: white;

            box-shadow: 0 4px 10px rgba(181, 18, 27, 0.20);
        }


        /* ICONO DE LA OPCIÓN ACTIVA */

        .sidebar-menu .nav-link.active i {

            color: white;
        }


        /* CERRAR SESIÓN */

        .sidebar-footer .nav-link {

            color: #b5121b;

            font-weight: 700;
        }


        .sidebar-footer .nav-link:hover {

            background: #fff1f2;
        }


        /* =====================================================
           CELULAR PEQUEÑO
        ===================================================== */

        @media (max-width: 576px) {

            .welcome-banner {

                padding: 18px;
            }


            .welcome-banner h2 {

                font-size: 18px;
            }


            .welcome-banner p {

                font-size: 13px;
            }


            .stats-number {

                font-size: 23px;
            }


            .section-header {

                align-items: flex-start;

                flex-direction: column;

                gap: 12px;
            }


            .section-header .btn-rojo {

                width: 100%;

                text-align: center;
            }
        }

        /* =========================================================
   INFORMACIÓN DEL SISTEMA
   ========================================================= */

.dashboard-card {
    background: var(--blanco);
    border: 1px solid var(--borde);
    border-radius: 12px;
    box-shadow: var(--sombra);
    overflow: hidden;
}

.dashboard-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--borde);
}

.dashboard-card-header h5 {
    font-weight: 700;
    color: var(--gris-oscuro);
}

.dashboard-card-body {
    padding: 24px;
}

.system-description {
    color: var(--gris-texto);
    font-size: 15px;
    line-height: 1.7;
    margin-bottom: 10px;
}

.info-system-card {
    height: 100%;
    padding: 22px;
    border: 1px solid var(--borde);
    border-radius: 12px;
    background: #fff;
    transition: all 0.25s ease;
}

.info-system-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--sombra);
    border-color: var(--rojo-claro);
}

.info-system-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: var(--rojo-suave);
    color: var(--rojo-principal);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    margin-bottom: 15px;
}

.info-system-card h6 {
    font-weight: 700;
    color: var(--gris-oscuro);
    margin-bottom: 8px;
}

.info-system-card p {
    color: var(--gris-texto);
    font-size: 13px;
    line-height: 1.6;
    margin-bottom: 0;
}

    </style>

</head>


<body>

@php

    $codAcceso = (int) session('cod_acceso');

@endphp


<!-- =========================================================
     SIDEBAR
========================================================= -->

<aside class="sidebar">


    <!-- LOGO -->

    <div class="sidebar-logo">

        <img
            src="{{ asset('img/logo.png') }}"
            alt="Logo institucional"
            onerror="
                this.style.display='none';
                document.getElementById('logoAlternativo').style.display='block';
            "
        >

        <div id="logoAlternativo" class="logo-alternativo">

            <img
                src="{{ asset('img/logo2.png') }}"
                alt="Logo Ministerio de Educación"
                class="logo-institucional"
            >

        </div>

    </div>


    <!-- USUARIO -->

    <div class="sidebar-user">

        <div class="sidebar-user-content">


            <div class="sidebar-user-icon">

                <i class="fa-solid fa-user"></i>

            </div>


            <div>

                <!-- NOMBRE DEL USUARIO -->

                <div class="sidebar-user-name">

                    {{ session('usuario_nombre') ?? 'Usuario' }}

                </div>


                <!-- ROL DEL USUARIO -->

                <div class="sidebar-user-role">

                    <i class="fa-solid fa-circle-check"></i>

                    @if ($codAcceso === 1)

                        Administrador

                    @elseif ($codAcceso === 2)

                        Funcionario

                    @elseif ($codAcceso === 3)

                        Usuario

                    @else

                        Sin rol

                    @endif

                </div>

            </div>


        </div>

    </div>


    <!-- =====================================================
         MENÚ
    ====================================================== -->

    <div class="sidebar-menu">

        <div class="menu-title">

            Menú principal

        </div>


        <!-- DASHBOARD - TODOS LOS ROLES -->

        <a
            href="{{ url('/dashboard') }}"
            class="nav-link active"
        >

            <i class="fa-solid fa-house"></i>

            <span>

                Dashboard

            </span>

        </a>


        {{-- =================================================
             ADMINISTRADOR
             cod_acceso = 1
        ================================================== --}}

        @if ($codAcceso === 1)

            <div class="menu-title">

                Administración

            </div>


            <!-- USUARIOS -->

            <a
                href="{{ url('/usuarios') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-users"></i>

                <span>

                    Usuarios

                </span>

            </a>


            <!-- SOLICITUDES -->

            <a
                href="{{ url('/solicitudes') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-file-circle-check"></i>

                <span>

                    Solicitudes de trámites

                </span>

            </a>


            <!-- TIPOS DE TRÁMITES -->

            <a
                href="{{ url('/tipos-tramites') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-list-check"></i>

                <span>

                    Tipos de trámites

                </span>

            </a>


            <!-- INSTITUTOS -->

            <a
                href="{{ url('/institutos') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-school"></i>

                <span>

                    Institutos

                </span>

            </a>


            <!-- PLANES DE ESTUDIO -->

            <a
                href="{{ url('/planes-estudio') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-book-open"></i>

                <span>

                    Planes de estudio

                </span>

            </a>


            <!-- REPORTES -->

            <a
                href="{{ url('/reportes') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-chart-column"></i>

                <span>

                    Reportes administrativos

                </span>

            </a>

        @endif


        
{{-- =================================================
     TÉCNICO DEPARTAMENTAL
     cod_acceso = 2
================================================== --}}

@if ($codAcceso === 2)

    <div class="menu-title">
        Gestión técnica
    </div>

    <!-- SOLICITUDES -->
    <a href="{{ route('solicitudes.index') }}" class="nav-link">
        <i class="fa-solid fa-file-circle-check"></i>
        <span>Solicitudes de trámites</span>
    </a>

    <!-- REVISIÓN TÉCNICA -->
    <a href="{{ route('solicitudes.index') }}" class="nav-link">
        <i class="fa-solid fa-clipboard-check"></i>
        <span>Revisión técnica de solicitudes</span>
    </a>

    <!-- HISTORIAL -->
    <a href="{{ route('solicitudes.index') }}" class="nav-link">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span>Historial de solicitudes</span>
    </a>

    <!-- INSTITUTOS -->
    <a href="{{ url('/institutos') }}" class="nav-link">
        <i class="fa-solid fa-school"></i>
        <span>Institutos</span>
    </a>

    <!-- PLANES DE ESTUDIO -->
    <a href="{{ url('/planes-estudio') }}" class="nav-link">
        <i class="fa-solid fa-book-open"></i>
        <span>Planes de estudio</span>
    </a>

    <!-- REPORTES -->
    <a href="{{ url('/reportes') }}" class="nav-link">
        <i class="fa-solid fa-chart-column"></i>
        <span>Reportes administrativos</span>
    </a>

@endif



        {{-- =================================================
             USUARIO
             cod_acceso = 3
             cod_acceso = 7
        ================================================== --}}

       @if (in_array($codAcceso, [3, 7]))

            <div class="menu-title">

                Mis trámites

            </div>


            <!-- MIS SOLICITUDES -->

            <a
                href="{{ url('/solicitudes') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-file-circle-check"></i>

                <span>

                    Mis solicitudes

                </span>

            </a>


            <!-- NUEVA SOLICITUD -->

            <a
                href="{{ url('/solicitudes/create') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-file-circle-plus"></i>

                <span>

                    Nueva solicitud

                </span>

            </a>


            <!-- PLANES DE ESTUDIO -->

            <a
                href="{{ url('/planes-estudio') }}"
                class="nav-link"
            >

                <i class="fa-solid fa-book-open"></i>

                <span>

                    Planes de estudio

                </span>

            </a>
                        <!-- INSTITUTOS -->

            <a
                href="{{ url('/institutos') }}"
                class="nav-link"
            >
                <i class="fa-solid fa-school"></i>
                <span>Institutos</span>
            </a>

        @endif

    </div>


    <!-- =====================================================
         CERRAR SESIÓN
         SIEMPRE VISIBLE
    ====================================================== -->

    <div class="sidebar-footer">

        <a
            href="{{ url('/login') }}"
            class="nav-link"
        >

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>

                Cerrar sesión

            </span>

        </a>

    </div>


</aside>



<!-- =========================================================
     CONTENIDO PRINCIPAL
========================================================= -->

<main class="main-content">


    <!-- =====================================================
         TOPBAR
    ====================================================== -->

    <header class="topbar">


        <div class="topbar-left">


            <div class="topbar-logo-icon">

                <i class="fa-solid fa-landmark"></i>

            </div>


            <div>

                <div class="topbar-title">

                    Sistema de Gestión de Trámites

                </div>


                <div class="topbar-subtitle">

                    Dirección Departamental de Educación Cochabamba

                </div>

            </div>


        </div>


        <!-- USUARIO -->

        <div class="topbar-user">


            <div class="topbar-user-icon">

                <i class="fa-solid fa-user"></i>

            </div>


            <div class="topbar-user-name">

                {{ session('usuario_nombre') ?? 'Usuario' }}

            </div>


        </div>


    </header>



    <!-- =====================================================
         CONTENIDO
    ====================================================== -->

    <div class="content">


        <!-- =================================================
             BANNER
        ================================================== -->

        <div class="welcome-banner">


            <div class="welcome-icon">

                <i class="fa-solid fa-building-columns"></i>

            </div>


            <h2>

                Bienvenido al Sistema

            </h2>


            <p>

                Gestión y seguimiento de trámites de modificación
                de datos personales y académicos.

            </p>


        </div>



        <!-- =================================================
             ESTADÍSTICAS
             SOLO ADMINISTRADOR
        ================================================== -->

        @if ($codAcceso === 1)

        <div class="row g-4 mb-4">


            <!-- USUARIOS -->

            <div class="col-xl-3 col-md-6">

                <div class="stats-card">


                    <div class="stats-icon">

                        <i class="fa-solid fa-users"></i>

                    </div>


                    <div class="stats-number">

                        {{ isset($usuarios) ? count($usuarios) : 0 }}

                    </div>


                    <div class="stats-title">

                        Usuarios registrados

                    </div>


                </div>

            </div>



            <!-- SOLICITUDES -->

            <div class="col-xl-3 col-md-6">

                <div class="stats-card">


                    <div class="stats-icon">

                        <i class="fa-solid fa-file-circle-check"></i>

                    </div>


                    <div class="stats-number">

                        0

                    </div>


                    <div class="stats-title">

                        Solicitudes

                    </div>


                </div>

            </div>



            <!-- INSTITUTOS -->

            <div class="col-xl-3 col-md-6">

                <div class="stats-card">


                    <div class="stats-icon">

                        <i class="fa-solid fa-school"></i>

                    </div>


                    <div class="stats-number">

                        0

                    </div>


                    <div class="stats-title">

                        Institutos

                    </div>


                </div>

            </div>



            <!-- TIPOS DE TRÁMITES -->

            <div class="col-xl-3 col-md-6">

                <div class="stats-card">


                    <div class="stats-icon">

                        <i class="fa-solid fa-list-check"></i>

                    </div>


                    <div class="stats-number">

                        0

                    </div>


                    <div class="stats-title">

                        Tipos de trámites

                    </div>


                </div>

            </div>


        </div>

        @endif



        <!-- =================================================
             USUARIOS
             SOLO ADMINISTRADOR
        ================================================== -->

        @if ($codAcceso === 1)

        <div class="section-card">


            <div class="section-header">


                <h5 class="section-title">

                    <i class="fa-solid fa-users"></i>

                    Usuarios registrados

                </h5>


                <a
                    href="{{ url('/usuarios') }}"
                    class="btn-rojo"
                >

                    <i class="fa-solid fa-user-plus me-1"></i>

                    Gestionar usuarios

                </a>


            </div>



            <div class="table-container">


                <table class="table table-hover align-middle">


                    <thead>

                        <tr>

                            <th>

                                #

                            </th>


                            <th>

                                <i class="fa-solid fa-user me-1"></i>

                                Usuario

                            </th>


                            <th>

                                <i class="fa-solid fa-id-card me-1"></i>

                                Nombre

                            </th>


                            <th>

                                <i class="fa-solid fa-envelope me-1"></i>

                                Correo

                            </th>


                            <th>

                                <i class="fa-solid fa-building me-1"></i>

                                Área

                            </th>


                            <th>

                                <i class="fa-solid fa-circle-check me-1"></i>

                                Estado

                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        @if(isset($usuarios) && count($usuarios) > 0)


                            @foreach($usuarios as $usuario)


                                <tr>


                                    <td>

                                        {{ $loop->iteration }}

                                    </td>


                                    <td>

                                        <strong>

                                            {{ $usuario->usuario ?? '-' }}

                                        </strong>

                                    </td>


                                    <td>

                                        {{ $usuario->nombre1 ?? '' }}

                                        {{ $usuario->nombre2 ?? '' }}

                                        {{ $usuario->paterno ?? '' }}

                                        {{ $usuario->materno ?? '' }}

                                    </td>


                                    <td>

                                        {{ $usuario->e_mail ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $usuario->area ?? '-' }}

                                    </td>


                                    <td>


                                        @if(isset($usuario->activo) && $usuario->activo)


                                            <span class="badge-activo">

                                                <i class="fa-solid fa-circle-check me-1"></i>

                                                Activo

                                            </span>


                                        @else


                                            <span class="badge-inactivo">

                                                <i class="fa-solid fa-circle-xmark me-1"></i>

                                                Inactivo

                                            </span>


                                        @endif


                                    </td>


                                </tr>


                            @endforeach


                        @else


                            <tr>


                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >


                                    <div class="text-muted">


                                        <i
                                            class="fa-solid fa-users"
                                            style="font-size: 40px;"
                                        ></i>


                                        <p class="mt-3 mb-0">

                                            No existen usuarios registrados.

                                        </p>


                                    </div>


                                </td>


                            </tr>


                        @endif


                    </tbody>


                </table>


            </div>


        </div>

        @endif


    </div>

    <!-- INFORMACIÓN DEL SISTEMA -->
<div class="container-fluid px-0 mt-4">

    <div class="dashboard-card">

        <div class="dashboard-card-header">
            <div>
                <h5 class="mb-1">
                    <i class="fa-solid fa-circle-info text-danger me-2"></i>
                    Información del sistema
                </h5>

                <p class="text-muted mb-0">
                    Sistema Web para la Gestión de Trámites para la Modificación
                    de Datos Personales y Académicos
                </p>
            </div>
        </div>

        <div class="dashboard-card-body">

            <p class="system-description">
                El sistema permite gestionar de manera organizada los trámites
                relacionados con la modificación de datos personales y académicos,
                facilitando el registro, consulta y seguimiento de las solicitudes
                realizadas ante la Dirección Departamental de Educación Cochabamba.
            </p>

            <div class="row g-4 mt-2">

                <!-- Gestión de trámites -->
                <div class="col-md-6 col-xl-3">
                    <div class="info-system-card">
                        <div class="info-system-icon">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>

                        <h6>Gestión de trámites</h6>

                        <p>
                            Permite registrar y administrar las solicitudes
                            de modificación de datos.
                        </p>
                    </div>
                </div>

                <!-- Datos personales -->
                <div class="col-md-6 col-xl-3">
                    <div class="info-system-card">
                        <div class="info-system-icon">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>

                        <h6>Datos personales</h6>

                        <p>
                            Facilita la gestión de solicitudes relacionadas
                            con la modificación de datos personales.
                        </p>
                    </div>
                </div>

                <!-- Datos académicos -->
                <div class="col-md-6 col-xl-3">
                    <div class="info-system-card">
                        <div class="info-system-icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>

                        <h6>Datos académicos</h6>

                        <p>
                            Permite gestionar información relacionada con
                            carreras, planes de estudio y datos académicos.
                        </p>
                    </div>
                </div>

                <!-- Seguimiento -->
                <div class="col-md-6 col-xl-3">
                    <div class="info-system-card">
                        <div class="info-system-icon">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>

                        <h6>Seguimiento</h6>

                        <p>
                            Permite consultar el estado y seguimiento de
                            las solicitudes registradas.
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>


    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <footer class="main-footer">

        <i class="fa-solid fa-landmark me-1"></i>

        Sistema de Gestión de Trámites

        © {{ date('Y') }}

        -

        Dirección Departamental de Educación Cochabamba

    </footer>


</main>



<!-- =========================================================
     BOOTSTRAP JS
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>