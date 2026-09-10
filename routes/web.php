<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerAdmin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Inicio de sesión
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Rutas del proyecto anterior
Route::get('/inicio', [ControllerAdmin::class, 'metodoinicio']);

Route::get('/ventas', [ControllerAdmin::class, 'metodoventas']);