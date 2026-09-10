<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto; //Incrustamos el modelo

class ControllerAdmin extends Controller
{
    public function metodoinicio()
    {
        return view('usuarios.usuarios');
    }

    public function metodoventas()
    {
        $productos=Producto::all(); //select * from productos
        //$productos=Producto::where('stock','>=',100)->get();
        return view('ventas.panel',compact('productos'));
    }
}
