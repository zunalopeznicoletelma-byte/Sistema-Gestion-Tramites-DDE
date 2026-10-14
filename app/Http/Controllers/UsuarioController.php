<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR USUARIOS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        try {

            $usuarios = DB::table('e_usuario')
                ->leftJoin(
                    'b_acceso',
                    'e_usuario.cod_acceso',
                    '=',
                    'b_acceso.cod_acceso'
                )
                ->select(
                    'e_usuario.*',
                    'b_acceso.nombre as nombre_acceso'
                )
                ->orderBy('e_usuario.cod_usuario', 'desc')
                ->get();

            return view(
                'usuarios.index',
                compact('usuarios')
            );

        } catch (\Throwable $e) {

            return view('usuarios.index', [
                'usuarios' => [],
                'error' =>
                    'No se pudieron cargar los usuarios: ' .
                    $e->getMessage()
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO CREAR USUARIO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        try {

            $roles = DB::table('b_acceso')
                ->whereIn('cod_acceso', [1, 2, 3, 7])
                ->where('activo', 1)
                ->orderBy('cod_acceso')
                ->get();

            return view(
                'usuarios.create',
                compact('roles')
            );

        } catch (\Throwable $e) {

            return back()->withErrors([
                'usuario' =>
                    'No se pudieron cargar los roles: ' .
                    $e->getMessage()
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR USUARIO
    |--------------------------------------------------------------------------
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

            'cod_acceso' => [
                'required',
                'integer',
                'in:1,2,3,7'
            ],
        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | VALIDAR USUARIO
            |--------------------------------------------------------------------------
            */

            if (
                DB::table('e_usuario')
                    ->where('usuario', $datos['usuario'])
                    ->exists()
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'usuario' =>
                            'El nombre de usuario ya existe.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDAR CARNET
            |--------------------------------------------------------------------------
            */

            if (
                DB::table('e_usuario')
                    ->where('carnet', $datos['carnet'])
                    ->exists()
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'carnet' =>
                            'El carnet de identidad ya está registrado.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDAR CORREO
            |--------------------------------------------------------------------------
            */

            if (
                DB::table('e_usuario')
                    ->where('e_mail', $datos['e_mail'])
                    ->exists()
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'e_mail' =>
                            'El correo electrónico ya está registrado.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | CONTRASEÑA
            |--------------------------------------------------------------------------
            */

            $claveHash = Hash::make(
                $datos['clave']
            );


            /*
            |--------------------------------------------------------------------------
            | ROL
            |--------------------------------------------------------------------------
            */

            $codAcceso = (int) $datos['cod_acceso'];


            /*
            |--------------------------------------------------------------------------
            | USUARIOS CREADOS POR ADMINISTRADOR
            |
            | Como estos usuarios fueron creados por el Administrador,
            | quedan activos.
            |--------------------------------------------------------------------------
            */

            DB::statement(
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
                    1
                ]
            );


            return redirect()
                ->route('usuarios.index')
                ->with(
                    'success',
                    'El usuario fue registrado correctamente.'
                );


        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'usuario' =>
                        'No se pudo registrar el usuario: ' .
                        $e->getMessage()
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO EDITAR
    |--------------------------------------------------------------------------
    */

    public function edit($cod_usuario)
    {
        try {

            $usuario = DB::table('e_usuario')
                ->where(
                    'cod_usuario',
                    $cod_usuario
                )
                ->first();

            if (!$usuario) {

                return redirect()
                    ->route('usuarios.index')
                    ->withErrors([
                        'usuario' =>
                            'El usuario no existe.'
                    ]);
            }


            $roles = DB::table('b_acceso')
                ->whereIn(
                    'cod_acceso',
                    [1, 2, 3, 7]
                )
                ->where('activo', 1)
                ->orderBy('cod_acceso')
                ->get();


            return view(
                'usuarios.edit',
                compact(
                    'usuario',
                    'roles'
                )
            );


        } catch (\Throwable $e) {

            return redirect()
                ->route('usuarios.index')
                ->withErrors([
                    'usuario' =>
                        'No se pudo cargar el usuario: ' .
                        $e->getMessage()
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR USUARIO
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $cod_usuario
    ) {

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

            'e_mail' => [
                'required',
                'email',
                'max:100'
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:15'
            ],

            'cod_acceso' => [
                'required',
                'integer',
                'in:1,2,3,7'
            ],

            'activo' => [
                'required',
                'boolean'
            ],

        ]);


        try {

            $usuario = DB::table('e_usuario')
                ->where(
                    'cod_usuario',
                    $cod_usuario
                )
                ->first();


            if (!$usuario) {

                return back()->withErrors([
                    'usuario' =>
                        'El usuario no existe.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR
            |--------------------------------------------------------------------------
            */

            DB::table('e_usuario')
                ->where(
                    'cod_usuario',
                    $cod_usuario
                )
                ->update([

                    'nombre1' =>
                        $datos['nombre1'],

                    'nombre2' =>
                        $datos['nombre2'] ?? null,

                    'paterno' =>
                        $datos['paterno'],

                    'materno' =>
                        $datos['materno'] ?? null,

                    'e_mail' =>
                        $datos['e_mail'],

                    'telefono' =>
                        $datos['telefono'] ?? null,

                    'cod_acceso' =>
                        $datos['cod_acceso'],

                    'activo' =>
                        $datos['activo'],
                ]);


            return redirect()
                ->route('usuarios.index')
                ->with(
                    'success',
                    'El usuario fue actualizado correctamente.'
                );


        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'usuario' =>
                        'No se pudo actualizar el usuario: ' .
                        $e->getMessage()
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR / DESACTIVAR USUARIO
    |--------------------------------------------------------------------------
    */

    public function destroy($cod_usuario)
    {
        try {

            $usuario = DB::table('e_usuario')
                ->where(
                    'cod_usuario',
                    $cod_usuario
                )
                ->first();


            if (!$usuario) {

                return back()->withErrors([
                    'usuario' =>
                        'El usuario no existe.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | NO PERMITIR DESACTIVAR AL PROPIO ADMIN
            |--------------------------------------------------------------------------
            */

            if (
                session('usuario_id') ==
                $cod_usuario
            ) {

                return back()->withErrors([
                    'usuario' =>
                        'No puede desactivar su propia cuenta.'
                ]);
            }


            DB::table('e_usuario')
                ->where(
                    'cod_usuario',
                    $cod_usuario
                )
                ->update([
                    'activo' => 0
                ]);


            return back()->with(
                'success',
                'El usuario fue desactivado correctamente.'
            );


        } catch (\Throwable $e) {

            return back()->withErrors([
                'usuario' =>
                    'No se pudo desactivar el usuario: ' .
                    $e->getMessage()
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVAR DIRECTIVO
    |--------------------------------------------------------------------------
    */

    public function activar($cod_usuario)
    {
        try {

            $usuario = DB::table('e_usuario')
                ->where(
                    'cod_usuario',
                    $cod_usuario
                )
                ->first();


            if (!$usuario) {

                return back()->withErrors([
                    'usuario' =>
                        'El usuario no existe.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | SOLO DIRECTIVOS
            |--------------------------------------------------------------------------
            */

            if (
                (int) $usuario->cod_acceso !== 3
            ) {

                return back()->withErrors([
                    'usuario' =>
                        'Esta cuenta no corresponde a un Directivo de Instituto.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | ACTIVAR
            |--------------------------------------------------------------------------
            */

            DB::table('e_usuario')
                ->where(
                    'cod_usuario',
                    $cod_usuario
                )
                ->update([
                    'activo' => 1
                ]);


            return back()->with(
                'success',
                'La cuenta del Directivo fue activada correctamente.'
            );


        } catch (\Throwable $e) {

            return back()->withErrors([
                'usuario' =>
                    'No se pudo activar la cuenta: ' .
                    $e->getMessage()
            ]);
        }
    }
}