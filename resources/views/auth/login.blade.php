<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Gestión de Trámites - DDE Cochabamba</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #eeeeee;
            color: #111;
        }

        /* =========================
           ENCABEZADO
        ========================= */

        .header {
            height: 175px;
            background: #fff;
            display: flex;
            align-items: center;
            padding: 15px 7%;
            position: relative;
            border-bottom: 5px solid #b7b7b7;
        }

        .logo {
            width: 125px;
            height: 125px;
            border-radius: 50%;
            margin-right: 20px;
            object-fit: contain;
        }

        .header-text {
            display: flex;
            align-items: center;
            gap: 22px;
            width: 100%;
        }

        .ministerio {
            font-size: 25px;
            font-weight: bold;
            color: #9a741c;
            line-height: 1.1;
        }

        .ministerio span {
            display: block;
            color: #111;
        }

        .linea {
            height: 55px;
            width: 2px;
            background: #555;
        }

        .unidad {
            font-size: 24px;
            font-weight: bold;
            line-height: 1.1;
        }

        .dde {
            font-size: 24px;
            font-weight: bold;
            line-height: 1.1;
        }

        .titulo-sistema {
            position: absolute;
            right: 8%;
            bottom: 16px;
            font-size: 23px;
            font-weight: bold;
        }

        /* =========================
           CONTENEDOR PRINCIPAL
        ========================= */

        .principal {
            min-height: calc(100vh - 175px);
            padding: 38px 25px 30px;

            display: grid;
            grid-template-columns: 58% 42%;
            gap: 20px;

            background: linear-gradient(
                135deg,
                #eeeeee 0%,
                #dcdcdc 50%,
                #eeeeee 100%
            );
        }

        /* =========================
           COLUMNA IZQUIERDA
        ========================= */

        .columna-izquierda {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .tarjeta {
            background: rgba(255, 255, 255, 0.93);
            border-radius: 0 0 15px 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .20);
            position: relative;
        }

        .tarjeta:first-child {
            border-radius: 15px;
        }

        .franja {
            height: 7px;
            display: flex;
        }

        .franja .rojo {
            width: 35%;
            background: #a80000;
        }

        .franja .amarillo {
            width: 35%;
            background: #e0b800;
        }

        .franja .verde {
            width: 30%;
            background: #00734d;
        }

        .contenido-tarjeta {
            min-height: 270px;
            padding: 18px 55px 30px;
            text-align: center;

            background:
                linear-gradient(
                    rgba(255,255,255,.90),
                    rgba(255,255,255,.90)
                ),
                repeating-linear-gradient(
                    135deg,
                    #eee 0px,
                    #eee 2px,
                    #f8f8f8 2px,
                    #f8f8f8 8px
                );
        }

        .escudo {
            width: 75px;
            height: 75px;
            margin-bottom: 8px;
            object-fit: contain;
        }

        .contenido-tarjeta h2 {
            font-size: 25px;
            margin: 0 auto 12px;
            max-width: 620px;
            line-height: 1.15;
        }

        .contenido-tarjeta p {
            font-size: 19px;
            line-height: 1.3;
            margin: 0 auto;
            max-width: 620px;
        }

        .separador {
            width: 80%;
            height: 2px;
            background: #b7a678;
            margin: 7px auto 13px;
        }

        /* =========================
           IMAGEN
        ========================= */

        .logo1 {
            width: 100%;
            height: 220px;
            object-fit: cover;
            opacity: 0.5;
        }

        /* =========================
           LOGIN
        ========================= */

        .columna-derecha {
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .login {
            width: 95%;
            max-width: 500px;
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 4px 13px rgba(0, 0, 0, .25);
        }

        .login-header {
            background: #a90000;
            color: white;
            text-align: center;
            padding: 25px 20px 23px;
            font-size: 27px;
            line-height: 1.25;
            font-weight: normal;
        }

        .login-body {
            padding: 35px 40px 25px;
            background: #fff;
        }

        .campo {
            position: relative;
            display: flex;
            align-items: center;
            border-bottom: 3px solid #999;
            margin-bottom: 45px;
            padding-bottom: 6px;
        }

        .icono {
            width: 45px;
            text-align: center;
            font-size: 34px;
            color: #666;
            margin-right: 10px;
        }

        .campo input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 20px;
            color: #555;
            padding: 7px 40px 7px 0;
        }

        .campo input::placeholder {
            color: #666;
        }

        /* =========================
           BOTÓN LOGIN
        ========================= */

        .boton-login {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .boton-login button {
            width: 160px;
            height: 50px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(
                #e3b72e,
                #bd8700
            );
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,.3);
        }

        .boton-login button:hover {
            background: linear-gradient(
                #d4a91e,
                #a97400
            );
        }

        /* =========================
           REGISTRO
        ========================= */

        .registro {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .registro-texto {
            margin: 0 0 10px;
            color: #666;
            font-size: 15px;
        }

        .registro a {
            color: #a90000;
            font-weight: bold;
            text-decoration: none;
            font-size: 16px;
        }

        .registro a:hover {
            color: #720000;
            text-decoration: underline;
        }

        /* =========================
           MENSAJES
        ========================= */

        .mensaje-error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f1aeb5;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .mensaje-exito {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #a3cfbb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        /* =========================
           MOSTRAR CONTRASEÑA
        ========================= */

        .boton-ver-clave {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
        }

        .boton-ver-clave:hover {
            opacity: 0.7;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .header {
                height: auto;
                padding: 20px;
                flex-wrap: wrap;
            }

            .logo {
                width: 90px;
                height: 90px;
            }

            .ministerio,
            .unidad,
            .dde {
                font-size: 17px;
            }

            .titulo-sistema {
                position: static;
                width: 100%;
                text-align: center;
                margin-top: 15px;
                font-size: 18px;
            }

            .principal {
                grid-template-columns: 1fr;
            }

            .columna-derecha {
                margin-top: 20px;
            }
        }

        @media (max-width: 600px) {

            .header-text {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .linea {
                display: none;
            }

            .contenido-tarjeta {
                padding: 20px;
            }

            .contenido-tarjeta h2 {
                font-size: 20px;
            }

            .contenido-tarjeta p {
                font-size: 16px;
            }

            .login {
                width: 100%;
            }

            .login-header {
                font-size: 21px;
            }

            .login-body {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>

    <!-- ==========================================
         ENCABEZADO INSTITUCIONAL
    =========================================== -->

    <header class="header">

        <img src="{{ asset('img/logo.jpg') }}"
             alt="Logo Ministerio de Educación"
             class="logo">

        <div class="header-text">

            <div class="ministerio">
                MINISTERIO
                <span>DE EDUCACIÓN</span>
            </div>

            <div class="linea"></div>

            <div class="unidad">
                UNIDAD DE INFORMACIÓN
                <br>
                EDUCATIVA
            </div>

            <div class="dde">
                DIRECCIÓN DEPARTAMENTAL DE
                <br>
                EDUCACIÓN COCHABAMBA
            </div>

        </div>

        <div class="titulo-sistema">
            SISTEMA DE GESTIÓN DE TRÁMITES DE DATOS
        </div>

    </header>


    <!-- ==========================================
         CONTENIDO PRINCIPAL
    =========================================== -->

    <main class="principal">

        <!-- ======================================
             COLUMNA IZQUIERDA
        ======================================= -->

        <section class="columna-izquierda">

            <div class="tarjeta">

                <div class="franja">
                    <div class="rojo"></div>
                    <div class="amarillo"></div>
                    <div class="verde"></div>
                </div>

                <div class="contenido-tarjeta">

                    <img src="{{ asset('img/logo1.jpg') }}"
                         alt="Logo"
                         class="logo1">

                    <h2>
                        GUÍA DE TRÁMITES Y PROCEDIMIENTOS DE
                        MODIFICACIÓN - COCHABAMBA
                    </h2>

                    <div class="separador"></div>

                    <p>
                        Procesos para actualizar datos personales
                        (Nombre, C) y académicos (Carrera, Notas,
                        Institución) en el ámbito departamental.
                    </p>

                </div>

            </div>

        </section>


        <!-- ======================================
             LOGIN
        ======================================= -->

        <section class="columna-derecha">

            <div class="login">

                <div class="login-header">
                    Ingreso a la Sección de Gestión de
                    <br>
                    Trámites - DDE Cochabamba
                </div>

                <div class="login-body">

                    {{-- MENSAJE DE ÉXITO --}}
                    @if (session('success'))
                        <div class="mensaje-exito">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ERRORES --}}
                    @if ($errors->any())
                        <div class="mensaje-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <!-- USUARIO -->

                        <div class="campo">

                            <div class="icono">
                                👤
                            </div>

                            <input
                                type="text"
                                name="usuario"
                                placeholder="Código R.D.A. / Usuario"
                                autocomplete="username"
                                value="{{ old('usuario') }}"
                                required>

                        </div>


                        <!-- CONTRASEÑA -->

                        <div class="campo">

                            <div class="icono">
                                🔒
                            </div>

                            <input
                                type="password"
                                name="clave"
                                id="clave"
                                placeholder="Contraseña"
                                autocomplete="current-password"
                                required>

                            <button
                                type="button"
                                id="mostrarClave"
                                class="boton-ver-clave"
                                title="Mostrar contraseña">
                                👁
                            </button>

                        </div>


                        <!-- BOTÓN INGRESAR -->

                        <div class="boton-login">

                            <button type="submit">
                                Ingresar
                            </button>

                        </div>

                    </form>


                    <!-- ==================================
                         REGISTRO DE NUEVO USUARIO
                    =================================== -->

                    <div class="registro">

                        <p class="registro-texto">
                            ¿No tienes una cuenta?
                        </p>

                        <a href="{{ route('register') }}">
                            Crear una cuenta
                        </a>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <!-- ==========================================
         JAVASCRIPT
    =========================================== -->

    <script>

        const campoClave = document.getElementById('clave');
        const botonMostrarClave = document.getElementById('mostrarClave');

        if (campoClave && botonMostrarClave) {

            botonMostrarClave.addEventListener('click', function () {

                if (campoClave.type === 'password') {

                    campoClave.type = 'text';
                    botonMostrarClave.title = 'Ocultar contraseña';

                } else {

                    campoClave.type = 'password';
                    botonMostrarClave.title = 'Mostrar contraseña';

                }

            });

        }

    </script>

</body>
</html>

