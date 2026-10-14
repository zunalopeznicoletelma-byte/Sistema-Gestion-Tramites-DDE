<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta - Sistema de Gestión de Trámites</title>


    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <style>

        body {
            min-height: 100vh;
            margin: 0;

            background:
                linear-gradient(
                    135deg,
                    #7f0000,
                    #b30000,
                    #dc3545
                );

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 15px;
        }


        .register-container {
            width: 100%;
            max-width: 1050px;

            background: #ffffff;

            border-radius: 20px;
            overflow: hidden;

            box-shadow:
                0 20px 50px
                rgba(0,0,0,0.25);
        }


        .register-header {
            background:
                linear-gradient(
                    135deg,
                    #8b0000,
                    #c82333
                );

            color: white;

            padding: 30px;

            text-align: center;
        }


        .register-header i {
            font-size: 50px;
            margin-bottom: 10px;
        }


        .register-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }


        .register-header p {
            margin: 0;
            opacity: 0.9;
        }


        .register-body {
            padding: 35px;
        }


        .section-title {
            color: #9b0000;

            font-weight: 700;

            border-bottom: 2px solid #eee;

            padding-bottom: 10px;
            margin-bottom: 20px;

            display: flex;
            align-items: center;

            gap: 10px;
        }


        .form-label {
            font-weight: 600;
            color: #333;
        }


        .form-control,
        .form-select {
            min-height: 46px;
            border-radius: 10px;
        }


        .form-control:focus,
        .form-select:focus {
            border-color: #dc3545;

            box-shadow:
                0 0 0 0.2rem
                rgba(220,53,69,.15);
        }


        .btn-register {
            background:
                linear-gradient(
                    135deg,
                    #8b0000,
                    #dc3545
                );

            border: none;

            color: white;

            padding: 13px 30px;

            border-radius: 10px;

            font-size: 16px;
            font-weight: 700;

            transition: 0.2s;
        }


        .btn-register:hover {
            transform: translateY(-1px);

            box-shadow:
                0 8px 20px
                rgba(139,0,0,.25);

            color: white;
        }


        .btn-login {
            color: #8b0000;

            text-decoration: none;

            font-weight: 600;
        }


        .btn-login:hover {
            text-decoration: underline;
        }


        .required {
            color: #dc3545;
        }


        .help-text {
            font-size: 13px;
            color: #777;
            margin-top: 5px;
        }


        .alert {
            border-radius: 10px;
        }


        @media (max-width: 768px) {

            .register-body {
                padding: 25px 20px;
            }

            .register-header h1 {
                font-size: 23px;
            }
        }

    </style>

</head>


<body>


<div class="register-container">


    <!-- ========================================== -->
    <!-- CABECERA -->
    <!-- ========================================== -->

    <div class="register-header">

        <i class="bi bi-person-plus-fill"></i>

        <h1>
            Crear una cuenta
        </h1>

        <p>
            Sistema Web para la Gestión de Trámites
        </p>

    </div>


    <!-- ========================================== -->
    <!-- CUERPO -->
    <!-- ========================================== -->

    <div class="register-body">


        <!-- ====================================== -->
        <!-- MENSAJES DE ERROR -->
        <!-- ====================================== -->

        @if ($errors->any())

            <div class="alert alert-danger">

                <div class="fw-bold mb-2">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    Revise los siguientes datos:

                </div>


                <ul class="mb-0">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- ====================================== -->
        <!-- FORMULARIO -->
        <!-- ====================================== -->

        <form
            action="{{ route('register.store') }}"
            method="POST"
        >

            @csrf


            <!-- ================================== -->
            <!-- DATOS PERSONALES -->
            <!-- ================================== -->

            <h5 class="section-title">

                <i class="bi bi-person-vcard-fill"></i>

                Datos personales

            </h5>


            <div class="row g-3">


                <!-- Primer nombre -->

                <div class="col-md-6">

                    <label class="form-label">

                        Primer nombre

                        <span class="required">*</span>

                    </label>

                    <input
                        type="text"
                        name="nombre1"
                        class="form-control"
                        value="{{ old('nombre1') }}"
                        maxlength="30"
                        required
                    >

                </div>


                <!-- Segundo nombre -->

                <div class="col-md-6">

                    <label class="form-label">
                        Segundo nombre
                    </label>

                    <input
                        type="text"
                        name="nombre2"
                        class="form-control"
                        value="{{ old('nombre2') }}"
                        maxlength="30"
                    >

                </div>


                <!-- Apellido paterno -->

                <div class="col-md-6">

                    <label class="form-label">

                        Apellido paterno

                        <span class="required">*</span>

                    </label>

                    <input
                        type="text"
                        name="paterno"
                        class="form-control"
                        value="{{ old('paterno') }}"
                        maxlength="30"
                        required
                    >

                </div>


                <!-- Apellido materno -->

                <div class="col-md-6">

                    <label class="form-label">
                        Apellido materno
                    </label>

                    <input
                        type="text"
                        name="materno"
                        class="form-control"
                        value="{{ old('materno') }}"
                        maxlength="30"
                    >

                </div>


                <!-- Carnet -->

                <div class="col-md-6">

                    <label class="form-label">

                        Carnet de identidad

                        <span class="required">*</span>

                    </label>

                    <input
                        type="text"
                        name="carnet"
                        class="form-control"
                        value="{{ old('carnet') }}"
                        maxlength="15"
                        required
                    >

                </div>


                <!-- Fecha nacimiento -->

                <div class="col-md-6">

                    <label class="form-label">
                        Fecha de nacimiento
                    </label>

                    <input
                        type="date"
                        name="fecha_nac"
                        class="form-control"
                        value="{{ old('fecha_nac') }}"
                    >

                </div>

            </div>


            <br>


            <!-- ================================== -->
            <!-- DATOS DE CONTACTO -->
            <!-- ================================== -->

            <h5 class="section-title">

                <i class="bi bi-telephone-fill"></i>

                Datos de contacto

            </h5>


            <div class="row g-3">


                <!-- Correo -->

                <div class="col-md-6">

                    <label class="form-label">

                        Correo electrónico

                        <span class="required">*</span>

                    </label>

                    <input
                        type="email"
                        name="e_mail"
                        class="form-control"
                        value="{{ old('e_mail') }}"
                        maxlength="100"
                        required
                    >

                </div>


                <!-- Teléfono -->

                <div class="col-md-6">

                    <label class="form-label">
                        Celular
                    </label>

                    <input
                        type="text"
                        name="telefono"
                        class="form-control"
                        value="{{ old('telefono') }}"
                        maxlength="15"
                    >

                </div>

            </div>


            <br>


            <!-- ================================== -->
            <!-- DATOS DE ACCESO -->
            <!-- ================================== -->

            <h5 class="section-title">

                <i class="bi bi-shield-lock-fill"></i>

                Datos de acceso

            </h5>


            <div class="row g-3">


                <!-- Usuario -->

                <div class="col-md-6">

                    <label class="form-label">

                        Nombre de usuario

                        <span class="required">*</span>

                    </label>

                    <input
                        type="text"
                        name="usuario"
                        class="form-control"
                        value="{{ old('usuario') }}"
                        maxlength="20"
                        required
                    >

                    <div class="help-text">

                        Solo letras, números, punto,
                        guion y guion bajo.

                    </div>

                </div>


                <!-- Tipo de usuario -->

                <div class="col-md-6">

                    <label class="form-label">

                        Tipo de usuario

                        <span class="required">*</span>

                    </label>

                    <select
                        name="cod_acceso"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Seleccione un tipo de usuario
                        </option>


                        <option
                            value="3"
                            {{ old('cod_acceso') == 3 ? 'selected' : '' }}
                        >
                            Directivo de Instituto
                        </option>


                        <option
                            value="7"
                            {{ old('cod_acceso') == 7 ? 'selected' : '' }}
                        >
                            Usuario Solicitante
                        </option>

                    </select>


                    <div class="help-text">

                        Los roles Administrador y Técnico
                        Departamental son asignados únicamente
                        por el Administrador.

                    </div>

                </div>


                <!-- Contraseña -->

                <div class="col-md-6">

                    <label class="form-label">

                        Contraseña

                        <span class="required">*</span>

                    </label>

                    <input
                        type="password"
                        name="clave"
                        class="form-control"
                        minlength="8"
                        maxlength="255"
                        required
                    >

                    <div class="help-text">

                        Mínimo 8 caracteres.

                    </div>

                </div>


                <!-- Confirmar contraseña -->

                <div class="col-md-6">

                    <label class="form-label">

                        Confirmar contraseña

                        <span class="required">*</span>

                    </label>

                    <input
                        type="password"
                        name="clave_confirmation"
                        class="form-control"
                        minlength="8"
                        maxlength="255"
                        required
                    >

                </div>

            </div>


            <br>
            <br>


            <!-- ================================== -->
            <!-- INFORMACIÓN -->
            <!-- ================================== -->

            <div class="alert alert-info">

                <i class="bi bi-info-circle-fill"></i>

                <strong>Importante:</strong>

                Los datos registrados deben ser reales
                y corresponder al usuario que utilizará
                el sistema.

            </div>


            <!-- ================================== -->
            <!-- BOTONES -->
            <!-- ================================== -->

            <div
                class="d-flex flex-column flex-md-row
                       justify-content-between
                       align-items-center
                       gap-3
                       mt-4"
            >

                <a
                    href="{{ route('login') }}"
                    class="btn-login"
                >

                    <i class="bi bi-arrow-left"></i>

                    Ya tengo una cuenta

                </a>


                <button
                    type="submit"
                    class="btn btn-register"
                >

                    <i class="bi bi-person-plus-fill"></i>

                    Crear cuenta

                </button>

            </div>


        </form>

    </div>

</div>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>