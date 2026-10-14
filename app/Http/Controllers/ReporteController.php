<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Mostrar los reportes administrativos.
     */
    public function index(Request $request)
    {
        // Validar los filtros del reporte.
        $filtros = $request->validate([
            'estado' => 'nullable|integer',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        // Total general de solicitudes registradas.
        $totalSolicitudes = DB::table('e_solici')
            ->count();

        // Cantidad de solicitudes agrupadas por estado.
        $solicitudesPorEstado = DB::table('e_solici as s')
            ->join(
                'b_estado_tramite as e',
                's.cod_estado',
                '=',
                'e.cod_estado'
            )
            ->select(
                'e.cod_estado',
                'e.nombre_estado',
                DB::raw('COUNT(*) AS cantidad')
            )
            ->groupBy('e.cod_estado', 'e.nombre_estado')
            ->orderBy('e.cod_estado')
            ->get();

        // Consulta principal del reporte detallado.
        $consulta = DB::table('e_solici as s')
            ->leftJoin(
                'e_usuario as u',
                's.cod_usuario',
                '=',
                'u.cod_usuario'
            )
            ->leftJoin(
                'b_tipos as t',
                's.cod_tipo',
                '=',
                't.cod_tipo'
            )
            ->leftJoin(
                'b_instituto as i',
                's.cod_instituto',
                '=',
                'i.cod_instituto'
            )
            ->leftJoin(
                'b_estado_tramite as e',
                's.cod_estado',
                '=',
                'e.cod_estado'
            )
            ->select(
                's.cod_solicitud',
                's.fecha_solicitud',
                's.observacion',
                's.activo',
                'u.usuario',
                DB::raw("
                    LTRIM(RTRIM(CONCAT(
                        ISNULL(u.nombre1, ''), ' ',
                        ISNULL(u.nombre2, ''), ' ',
                        ISNULL(u.paterno, ''), ' ',
                        ISNULL(u.materno, '')
                    ))) AS solicitante
                "),
                't.nombre_tramite',
                'i.nombre_inst',
                'e.cod_estado',
                'e.nombre_estado'
            );

        // Filtrar por estado cuando se seleccione uno.
        $consulta->when(
            $request->filled('estado'),
            function ($query) use ($request) {
                $query->where('s.cod_estado', $request->input('estado'));
            }
        );

        // Filtrar desde una fecha determinada.
        $consulta->when(
            $request->filled('fecha_inicio'),
            function ($query) use ($request) {
                $query->whereDate(
                    's.fecha_solicitud',
                    '>=',
                    $request->input('fecha_inicio')
                );
            }
        );

        // Filtrar hasta una fecha determinada.
        $consulta->when(
            $request->filled('fecha_fin'),
            function ($query) use ($request) {
                $query->whereDate(
                    's.fecha_solicitud',
                    '<=',
                    $request->input('fecha_fin')
                );
            }
        );

        // Obtener las solicitudes que cumplen los filtros.
        $solicitudes = $consulta
            ->orderByDesc('s.fecha_solicitud')
            ->get();

        // Estados disponibles para el filtro.
        $estados = DB::table('b_estado_tramite')
            ->orderBy('cod_estado')
            ->get();

        // Cantidad de registros que muestra el reporte filtrado.
        $totalFiltrado = $solicitudes->count();

        return view('reportes', compact(
            'totalSolicitudes',
            'solicitudesPorEstado',
            'solicitudes',
            'estados',
            'totalFiltrado'
        ));
    }
}
