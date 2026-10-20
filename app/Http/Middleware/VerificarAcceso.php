<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarAcceso
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si existe una sesión inicio
        if (!session()->has('usuario_id')) {
            return redirect()->route('login');
        }

        // Obtener el código de acceso del usuario
        $codAcceso = session('cod_acceso');


        // Si la ruta requiere roles específicos,
        // verificar que el usuario tenga uno de ellos
        if (!empty($roles) && !in_array($codAcceso, $roles)) {
            abort(403, 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}