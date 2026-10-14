<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Usuario;

class ControllerAdmin extends Controller
{
    public function metodoinicio()
    {
        $usuarios = Usuario::all();

        return view('usuarios.usuarios', compact('usuarios'));
    }

    public function metodoventas()
    {
        $productos = Producto::all();

        return view('ventas.panel', compact('productos'));
    }
}
