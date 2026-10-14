<?php

namespace App\Http\Controllers;

use App\Models\TipoTramite;
use Illuminate\Http\Request;

class TipoTramiteController extends Controller
{
    public function index()
    {
        $tipos = TipoTramite::orderBy('cod_tipo', 'desc')->get();

        $totalTipos = $tipos->count();

        $totalHabilitados = $tipos->where('habilitado', 1)->count();

        $totalDeshabilitados = $tipos->where('habilitado', 0)->count();

        return view('tipos-tramites', compact(
            'tipos',
            'totalTipos',
            'totalHabilitados',
            'totalDeshabilitados'
        ));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre_tramite' => 'required|string|max:150',
            'requisitos' => 'required|string',
            'descripcion' => 'nullable|string',
            'tiempo_estimado' => 'required|integer|min:1',
        ], [
            'nombre_tramite.required' => 'El nombre del trámite es obligatorio.',
            'requisitos.required' => 'Los requisitos son obligatorios.',
            'tiempo_estimado.required' => 'El tiempo estimado es obligatorio.',
            'tiempo_estimado.integer' => 'El tiempo debe ser un número entero.',
            'tiempo_estimado.min' => 'El tiempo estimado debe ser como mínimo un día.',
        ]);

        $datos['habilitado'] = 1;

        TipoTramite::create($datos);

        return redirect()
            ->route('tipos-tramites')
            ->with('success', 'El tipo de trámite se registró correctamente.');
    }
}
