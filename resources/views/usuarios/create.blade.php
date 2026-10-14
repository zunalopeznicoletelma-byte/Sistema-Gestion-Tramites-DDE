<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar usuario</title>
</head>
<body>

```
<h1>Registrar nuevo usuario</h1>

{{-- Mostrar errores de validación --}}
@if ($errors->any())
    <div>
        <strong>Se encontraron los siguientes errores:</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/usuarios">

    @csrf

    <div>
        <label>Usuario:</label>
        <input
            type="text"
            name="usuario"
            value="{{ old('usuario') }}"
            maxlength="20"
            required
        >
    </div>

    <br>

    <div>
        <label>Correo:</label>
        <input
            type="email"
            name="e_mail"
            value="{{ old('e_mail') }}"
            maxlength="100"
        >
    </div>

    <br>

    <div>
        <label>Carnet:</label>
        <input
            type="text"
            name="carnet"
            value="{{ old('carnet') }}"
            maxlength="15"
        >
    </div>

    <br>

    <div>
        <label>Apellido paterno:</label>
        <input
            type="text"
            name="paterno"
            value="{{ old('paterno') }}"
            maxlength="30"
            required
        >
    </div>

    <br>

    <div>
        <label>Apellido materno:</label>
        <input
            type="text"
            name="materno"
            value="{{ old('materno') }}"
            maxlength="30"
        >
    </div>

    <br>

    <div>
        <label>Primer nombre:</label>
        <input
            type="text"
            name="nombre1"
            value="{{ old('nombre1') }}"
            maxlength="30"
            required
        >
    </div>

    <br>

    <div>
        <label>Segundo nombre:</label>
        <input
            type="text"
            name="nombre2"
            value="{{ old('nombre2') }}"
            maxlength="30"
        >
    </div>

    <br>

    <div>
        <label>Área:</label>
        <input
            type="text"
            name="area"
            value="{{ old('area') }}"
            maxlength="60"
        >
    </div>

    <br>

    <div>
        <label>Tipo de acceso:</label>

        <select name="cod_acceso" required>
            <option value="">Seleccione un acceso</option>

            @foreach ($accesos as $acceso)
                <option
                    value="{{ $acceso->cod_acceso }}"
                    {{ old('cod_acceso') == $acceso->cod_acceso ? 'selected' : '' }}
                >
                    {{ $acceso->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <br>

    <div>
        <label>Departamento:</label>

        <select name="cod_dep" required>
            <option value="">Seleccione un departamento</option>

            @foreach ($departamentos as $departamento)
                <option
                    value="{{ $departamento->cod_dep }}"
                    {{ old('cod_dep') == $departamento->cod_dep ? 'selected' : '' }}
                >
                    {{ $departamento->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <br>

    <div>
        <label>Distrito:</label>

        <select name="cod_dis" required>
            <option value="">Seleccione un distrito</option>

            @foreach ($distritos as $distrito)
                <option
                    value="{{ $distrito->cod_dis }}"
                    {{ old('cod_dis') == $distrito->cod_dis ? 'selected' : '' }}
                >
                   Distrito {{ $distrito->cod_dis }}
                </option>
            @endforeach
        </select>
    </div>

    <br>

    <div>
        <label>Fecha de nacimiento:</label>
        <input
            type="date"
            name="fecha_nac"
            value="{{ old('fecha_nac') }}"
        >
    </div>

    <br>

    <div>
        <label>Teléfono:</label>
        <input
            type="text"
            name="telefono"
            value="{{ old('telefono') }}"
            maxlength="15"
        >
    </div>

    <br>

    <div>
        <label>Clave:</label>
        <input
            type="password"
            name="clave"
            maxlength="255"
            required
        >
    </div>

    <br>

    <button type="submit">Guardar usuario</button>

    <a href="/usuarios">Cancelar</a>

</form>
```

</body>
</html>
