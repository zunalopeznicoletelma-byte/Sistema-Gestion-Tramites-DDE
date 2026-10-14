<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos enviados desde el formulario
        $datos = $request->validate([
            'usuario' => 'required|string',
            'clave' => 'required|string',
        ]);

        try {

            // Buscar el usuario en la base de datos
            $usuario = DB::table('e_usuario')
                ->where('usuario', $datos['usuario'])
                ->first();

            // Verificar que el usuario exista
            if (!$usuario) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'usuario' => 'El usuario o la contraseña son incorrectos.'
                    ]);
            }

            // Verificar que el usuario esté activo
            if (!$usuario->activo) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'usuario' => 'El usuario se encuentra inactivo.'
                    ]);
            }

            // Verificar la contraseña
            if (!Hash::check($datos['clave'], $usuario->clave)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'usuario' => 'El usuario o la contraseña son incorrectos.'
                    ]);
            }

            // Guardar los datos necesarios en la sesión
            session([
                'usuario_id' => $usuario->cod_usuario,
                'usuario_nombre' => $usuario->usuario,
                'usuario_nombre_completo' => trim(
                    $usuario->nombre1 . ' ' .
                    $usuario->nombre2 . ' ' .
                    $usuario->paterno . ' ' .
                    $usuario->materno
                ),
                'cod_acceso' => $usuario->cod_acceso,
            ]);

            // Redirigir al dashboard
            return redirect()->route('dashboard');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'usuario' => 'No se pudo iniciar sesión: ' . $e->getMessage()
                ]);
        }
    }

    public function logout(Request $request)
    {
        // Eliminar los datos de sesión
        $request->session()->flush();

        // Regenerar la sesión
        $request->session()->regenerate();

        return redirect()->route('login');
    }
}