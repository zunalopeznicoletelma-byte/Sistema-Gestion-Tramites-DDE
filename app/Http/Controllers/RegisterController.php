<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Mostrar formulario de registro.
     */
    public function create()
    {
        return view('auth.register');
    }


    /**
     * Registrar un nuevo usuario.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([

            'nombre1' => [
                'required',
                'string',
                'max:30',
                'regex:/^[\pL\s]+$/u'
            ],

            'nombre2' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[\pL\s]+$/u'
            ],

            'paterno' => [
                'required',
                'string',
                'max:30',
                'regex:/^[\pL\s]+$/u'
            ],

            'materno' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[\pL\s]+$/u'
            ],

            'carnet' => [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9A-Za-z-]+$/'
            ],

            'fecha_nac' => [
                'nullable',
                'date',
                'before:today'
            ],

            'e_mail' => [
                'required',
                'email',
                'max:100'
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^[0-9+\-\s]+$/'
            ],

            'usuario' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9._-]+$/'
            ],

            'clave' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed'
            ],

            /*
             * REGISTRO PÚBLICO
             *
             * 3 = Directivo de Instituto
             * 7 = Usuario Solicitante
             *
             * 1 = Administrador
             * 2 = Técnico Departamental
             *
             * Los roles 1 y 2 NO pueden ser
             * seleccionados desde el registro público.
             */
            'cod_acceso' => [
                'required',
                'integer',
                'in:3,7'
            ],

        ], [

            'nombre1.required' =>
                'El primer nombre es obligatorio.',

            'nombre1.regex' =>
                'El primer nombre solo puede contener letras y espacios.',

            'nombre2.regex' =>
                'El segundo nombre solo puede contener letras y espacios.',

            'paterno.required' =>
                'El apellido paterno es obligatorio.',

            'paterno.regex' =>
                'El apellido paterno solo puede contener letras y espacios.',

            'materno.regex' =>
                'El apellido materno solo puede contener letras y espacios.',

            'carnet.required' =>
                'El carnet de identidad es obligatorio.',

            'carnet.regex' =>
                'El carnet contiene caracteres no válidos.',

            'fecha_nac.before' =>
                'La fecha de nacimiento no puede ser futura.',

            'e_mail.required' =>
                'El correo electrónico es obligatorio.',

            'e_mail.email' =>
                'Ingrese un correo electrónico válido.',

            'telefono.regex' =>
                'El teléfono contiene caracteres no válidos.',

            'usuario.required' =>
                'El nombre de usuario es obligatorio.',

            'usuario.regex' =>
                'El nombre de usuario solo puede contener letras, números, punto, guion y guion bajo.',

            'clave.required' =>
                'La contraseña es obligatoria.',

            'clave.min' =>
                'La contraseña debe tener al menos 8 caracteres.',

            'clave.confirmed' =>
                'Las contraseñas no coinciden.',

            'cod_acceso.required' =>
                'Debe seleccionar un tipo de usuario.',

            'cod_acceso.in' =>
                'El tipo de usuario seleccionado no está permitido para el registro público.',
        ]);


        try {

            /*
             * Validación adicional de seguridad.
             *
             * Aunque Laravel valida 3 y 7,
             * se vuelve a comprobar antes de insertar.
             */
            $codAcceso = (int) $datos['cod_acceso'];

            if (!in_array($codAcceso, [3, 7], true)) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'cod_acceso' =>
                            'El tipo de usuario seleccionado no está permitido.'
                    ]);
            }


            /*
             * Verificar nombre de usuario duplicado.
             */
            $usuarioExiste = DB::table('e_usuario')
                ->where('usuario', $datos['usuario'])
                ->exists();

            if ($usuarioExiste) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'usuario' =>
                            'El nombre de usuario ya existe. Elija otro.'
                    ]);
            }


            /*
             * Verificar carnet duplicado.
             */
            $carnetExiste = DB::table('e_usuario')
                ->where('carnet', $datos['carnet'])
                ->exists();

            if ($carnetExiste) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'carnet' =>
                            'El carnet de identidad ya está registrado.'
                    ]);
            }


            /*
             * Verificar correo duplicado.
             */
            $correoExiste = DB::table('e_usuario')
                ->where('e_mail', $datos['e_mail'])
                ->exists();

            if ($correoExiste) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'e_mail' =>
                            'El correo electrónico ya está registrado.'
                    ]);
            }


            /*
             * Generar contraseña cifrada.
             */
            $claveHash = Hash::make(
                $datos['clave']
            );


            /*
             * Estado inicial de la cuenta.
             *
             * Usuario Solicitante = activo
             *
             * Directivo de Instituto = inactivo,
             * hasta que el Administrador valide la cuenta.
             */
            $activo = $codAcceso === 3 ? 0 : 1;


            /*
             * Registrar usuario mediante
             * procedimiento almacenado SQL Server.
             */
            DB::select(
                'EXEC dbo.sp_usuario_insertar
                    @usuario = ?,
                    @e_mail = ?,
                    @carnet = ?,
                    @paterno = ?,
                    @materno = ?,
                    @nombre1 = ?,
                    @nombre2 = ?,
                    @area = ?,
                    @cod_acceso = ?,
                    @clave = ?,
                    @fecha_nac = ?,
                    @cod_dis = ?,
                    @cod_dep = ?,
                    @telefono = ?,
                    @activo = ?',
                [
                    $datos['usuario'],
                    $datos['e_mail'],
                    $datos['carnet'],
                    $datos['paterno'],
                    $datos['materno'] ?? null,
                    $datos['nombre1'],
                    $datos['nombre2'] ?? null,
                    null,
                    $codAcceso,
                    $claveHash,
                    $datos['fecha_nac'] ?? null,
                    null,
                    null,
                    $datos['telefono'] ?? null,
                    $activo,
                ]
            );


            /*
             * Mensaje para Directivo de Instituto.
             */
            if ($codAcceso === 3) {

                return redirect()
                    ->route('login')
                    ->with(
                        'success',
                        'La solicitud de cuenta como Directivo de Instituto fue registrada. Su acceso deberá ser validado por el Administrador.'
                    );
            }


            /*
             * Mensaje para Usuario Solicitante.
             */
            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'La cuenta fue creada correctamente. Ya puede iniciar sesión.'
                );


        } catch (\Throwable $e) {

            /*
             * Error 50001:
             * usuario duplicado en SQL Server.
             */
            if ((int) $e->getCode() === 50001) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'usuario' =>
                            'El nombre de usuario ya existe.'
                    ]);
            }


            /*
             * Error general.
             */
            return back()
                ->withInput()
                ->withErrors([
                    'registro' =>
                        'No se pudo crear la cuenta: '
                        . $e->getMessage()
                ]);
        }
    }
}