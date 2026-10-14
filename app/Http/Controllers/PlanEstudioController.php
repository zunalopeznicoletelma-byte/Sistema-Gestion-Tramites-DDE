<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PlanEstudioController extends Controller
{
    public function index()
    {
        // Obtener los planes habilitados y relacionarlos con sus carreras e institutos.
        $planes = DB::table('b_plan_estudio as p')
            ->join('b_carrera as c', 'p.cod_carrera', '=', 'c.cod_carrera')
            ->join('b_instituto as i', 'c.cod_instituto', '=', 'i.cod_instituto')
            ->select(
                'p.cod_plan',
                'p.resolucion',
                'p.area_formacion',
                'p.carga_horaria',
                'p.total_horas',
                'p.titulo_profesional',
                'p.modalidad',
                'c.nombre_carrera',
                'i.nombre_inst'
            )
            ->where('p.habilitado', 1)
            ->where('c.habilitado', 1)
            ->where('i.habilitado', 1)
            ->orderBy('i.nombre_inst')
            ->orderBy('c.nombre_carrera')
            ->get();

        return view('planes-estudio', compact('planes'));
    }
}

