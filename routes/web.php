
<?php

use Illuminate\Support\Facades\Route;

use App\Models\Usuario;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TipoTramiteController;
use App\Http\Controllers\InstitutoController;
use App\Http\Controllers\PlanEstudioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\InformeController;

/*
|--------------------------------------------------------------------------
| PÁGINA PRINCIPAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (session()->has('usuario_id')) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('inicio');
/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    try {
        $usuarios = Usuario::listar();

        return view('vistadashboard', compact('usuarios'));

    } catch (\Exception $e) {

        return view('vistadashboard', [
            'usuarios' => [],
            'error_dashboard' => $e->getMessage()
        ]);
    }

})
->name('dashboard')
->middleware('acceso');


/*
|--------------------------------------------------------------------------
| USUARIOS
|--------------------------------------------------------------------------
*/

Route::get('/usuarios', [UsuarioController::class, 'index'])
    ->name('usuarios.index')
    ->middleware('acceso:1');

Route::get('/usuarios/create', [UsuarioController::class, 'create'])
    ->name('usuarios.create')
    ->middleware('acceso:1');

Route::post('/usuarios', [UsuarioController::class, 'store'])
    ->name('usuarios.store')
    ->middleware('acceso:1');

Route::get('/usuarios/{cod_usuario}/edit', [UsuarioController::class, 'edit'])
    ->name('usuarios.edit')
    ->middleware('acceso:1');

Route::put('/usuarios/{cod_usuario}', [UsuarioController::class, 'update'])
    ->name('usuarios.update')
    ->middleware('acceso:1');

Route::delete('/usuarios/{cod_usuario}', [UsuarioController::class, 'destroy'])
    ->name('usuarios.destroy')
    ->middleware('acceso:1');


/*
|--------------------------------------------------------------------------
| ACTIVAR DIRECTIVO
|--------------------------------------------------------------------------
*/

Route::put('/usuarios/{cod_usuario}/activar', [UsuarioController::class, 'activar'])
    ->name('usuarios.activar')
    ->middleware('acceso:1');


/*
|--------------------------------------------------------------------------
| SOLICITUDES
|--------------------------------------------------------------------------
*/

Route::get('/solicitudes', [SolicitudController::class, 'index'])
    ->name('solicitudes.index');

Route::get('/solicitudes/create', [SolicitudController::class, 'create'])
    ->name('solicitudes.create');

Route::post('/solicitudes', [SolicitudController::class, 'store'])
    ->name('solicitudes.store');

Route::get('/solicitudes/{cod_solicitud}/edit', [SolicitudController::class, 'edit'])
    ->name('solicitudes.edit');

Route::put('/solicitudes/{cod_solicitud}', [SolicitudController::class, 'update'])
    ->name('solicitudes.update');

Route::delete('/solicitudes/{cod_solicitud}', [SolicitudController::class, 'destroy'])
    ->name('solicitudes.destroy');


/*
|--------------------------------------------------------------------------
| REVISIÓN TÉCNICA
|--------------------------------------------------------------------------
*/

Route::post(
    '/solicitudes/{cod_solicitud}/revision-tecnica',
    [SolicitudController::class, 'revisionTecnica']
)
    ->name('solicitudes.revisionTecnica')
    ->middleware('acceso:2');


/*
|--------------------------------------------------------------------------
| HISTORIAL DE SOLICITUD
|--------------------------------------------------------------------------
*/

Route::get(
    '/solicitudes/{cod_solicitud}/historial',
    [SolicitudController::class, 'historial']
)
    ->name('solicitudes.historial');


/*
|--------------------------------------------------------------------------
| TIPOS DE TRÁMITES
|--------------------------------------------------------------------------
*/

Route::get('/tipos-tramites', [TipoTramiteController::class, 'index'])
    ->middleware('acceso:1,3')
    ->name('tipos-tramites');

Route::post('/tipos-tramites', [TipoTramiteController::class, 'store'])
    ->middleware('acceso:1')
    ->name('tipos-tramites.store');

Route::put('/tipos-tramites/{cod_tipo}', [TipoTramiteController::class, 'update'])
    ->middleware('acceso:1')
    ->name('tipos-tramites.update');

Route::patch('/tipos-tramites/{cod_tipo}/estado', [TipoTramiteController::class, 'cambiarEstado'])
    ->middleware('acceso:1')
    ->name('tipos-tramites.estado');


/*
|--------------------------------------------------------------------------
| INSTITUTOS
|--------------------------------------------------------------------------
*/

Route::get('/institutos', [InstitutoController::class, 'index'])
    ->name('institutos');


/*
|--------------------------------------------------------------------------
| PLANES DE ESTUDIO
|--------------------------------------------------------------------------
*/



Route::get('/planes-estudio', [PlanEstudioController::class, 'index'])
    ->name('planes-estudio');


/*
|--------------------------------------------------------------------------
| REPORTES ADMINISTRATIVOS
|--------------------------------------------------------------------------
*/

Route::get('/reportes', [ReporteController::class, 'index'])
    ->name('reportes')
    ->middleware('acceso');
/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| REGISTRO PÚBLICO
|--------------------------------------------------------------------------
*/

Route::get('/register', [RegisterController::class, 'create'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

Route::get(
    '/solicitudes/{cod_solicitud}/informe-word',
    [InformeController::class, 'generarWord']
)->name('solicitudes.informeWord')
  ->middleware('acceso:2');


use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


Route::get('/informes/plantilla', function () {
    // Verificar que el usuario haya iniciado sesión.
    if (!session()->has('usuario_id')) {
        return redirect()->route('login');
    }

    // Permitir la descarga al Técnico Departamental.
    if ((int) session('cod_acceso') !== 2) {
        abort(403, 'No tiene permiso para descargar la plantilla.');
    }

    $ruta = public_path('plantillas/plantilla_informe.docx');

    if (!file_exists($ruta)) {
        abort(404, 'No se encontró la plantilla de informe.');
    }

    return response()->download(
        $ruta,
        'plantilla_informe.docx'
    );
})->name('informes.plantilla');


/*
|--------------------------------------------------------------------------
| HISTORIAL GENERAL DE SOLICITUDES
|--------------------------------------------------------------------------
*/

Route::get(
    '/historial-solicitudes',
    [SolicitudController::class, 'historialGeneral']
)
    ->middleware('acceso:2')
    ->name('solicitudes.historialGeneral');
