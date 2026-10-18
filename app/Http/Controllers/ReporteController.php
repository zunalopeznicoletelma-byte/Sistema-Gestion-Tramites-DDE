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

        /*
         * Consulta base:
         * se reutiliza para que las estadísticas, los gráficos
         * y la tabla respondan a los mismos filtros.
         */
        $consultaBase = DB::table('e_solici as s')
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
            );

        // Aplicar filtro por estado.
        if ($request->filled('estado')) {
            $consultaBase->where(
                's.cod_estado',
                $filtros['estado']
            );
        }

        // Aplicar fecha inicial.
        if ($request->filled('fecha_inicio')) {
            $consultaBase->whereDate(
                's.fecha_solicitud',
                '>=',
                $filtros['fecha_inicio']
            );
        }

        // Aplicar fecha final.
        if ($request->filled('fecha_fin')) {
            $consultaBase->whereDate(
                's.fecha_solicitud',
                '<=',
                $filtros['fecha_fin']
            );
        }

        /*
         * Total de solicitudes que cumplen los filtros.
         */
        $totalFiltrado = (clone $consultaBase)
            ->count('s.cod_solicitud');

        /*
         * Total general sin filtros.
         * Se conserva como referencia del sistema.
         */
        $totalSolicitudes = DB::table('e_solici')
            ->count();

        /*
         * Estadísticas por estado.
         */
        $solicitudesPorEstado = (clone $consultaBase)
            ->select(
                'e.cod_estado',
                'e.nombre_estado',
                DB::raw('COUNT(s.cod_solicitud) AS cantidad')
            )
            ->groupBy(
                'e.cod_estado',
                'e.nombre_estado'
            )
            ->orderBy('e.cod_estado')
            ->get()
            ->map(function ($estado) use ($totalFiltrado) {
                $estado->porcentaje = $totalFiltrado > 0
                    ? round(
                        ($estado->cantidad / $totalFiltrado) * 100,
                        2
                    )
                    : 0;

                return $estado;
            });

        /*
         * Estadísticas por tipo de trámite.
         */
        $solicitudesPorTipo = (clone $consultaBase)
            ->select(
                't.cod_tipo',
                't.nombre_tramite',
                DB::raw('COUNT(s.cod_solicitud) AS cantidad')
            )
            ->groupBy(
                't.cod_tipo',
                't.nombre_tramite'
            )
            ->orderByDesc('cantidad')
            ->get()
            ->map(function ($tipo) use ($totalFiltrado) {
                $tipo->porcentaje = $totalFiltrado > 0
                    ? round(
                        ($tipo->cantidad / $totalFiltrado) * 100,
                        2
                    )
                    : 0;

                return $tipo;
            });

        /*
         * Solicitudes agrupadas por mes.
         * Se utiliza SQL Server para extraer año y mes.
         */
        $solicitudesPorMes = (clone $consultaBase)
            ->whereNotNull('s.fecha_solicitud')
            ->selectRaw("
                YEAR(s.fecha_solicitud) AS anio,
                MONTH(s.fecha_solicitud) AS mes,
                COUNT(s.cod_solicitud) AS cantidad
            ")
            ->groupByRaw("
                YEAR(s.fecha_solicitud),
                MONTH(s.fecha_solicitud)
            ")
            ->orderByRaw("
                YEAR(s.fecha_solicitud),
                MONTH(s.fecha_solicitud)
            ")
            ->get()
            ->map(function ($registro) {
                $meses = [
                    1 => 'Enero',
                    2 => 'Febrero',
                    3 => 'Marzo',
                    4 => 'Abril',
                    5 => 'Mayo',
                    6 => 'Junio',
                    7 => 'Julio',
                    8 => 'Agosto',
                    9 => 'Septiembre',
                    10 => 'Octubre',
                    11 => 'Noviembre',
                    12 => 'Diciembre',
                ];

                $registro->periodo =
                    $meses[(int) $registro->mes]
                    . ' '
                    . $registro->anio;

                return $registro;
            });

        /*
         * Tabla detallada de solicitudes.
         */
        $solicitudes = (clone $consultaBase)
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
            )
            ->orderByDesc('s.fecha_solicitud')
            ->get();

        /*
         * Estados disponibles para el filtro.
         */
        $estados = DB::table('b_estado_tramite')
            ->orderBy('cod_estado')
            ->get();

        /*
         * Tipos de trámite disponibles.
         */
        $tiposTramite = DB::table('b_tipos')
            ->orderBy('nombre_tramite')
            ->get();

        /*
         * Texto del período seleccionado para mostrarlo
         * en la pantalla y en la impresión.
         */
        $periodoReporte = 'Todos los períodos';

        if (
            $request->filled('fecha_inicio')
            || $request->filled('fecha_fin')
        ) {
            $inicio = $request->input('fecha_inicio')
                ?: 'Inicio no especificado';

            $fin = $request->input('fecha_fin')
                ?: 'Fin no especificado';

            $periodoReporte = $inicio . ' al ' . $fin;
        }

        return view('reportes', compact(
            'totalSolicitudes',
            'totalFiltrado',
            'solicitudesPorEstado',
            'solicitudesPorTipo',
            'solicitudesPorMes',
            'solicitudes',
            'estados',
            'tiposTramite',
            'periodoReporte'
        ));
    }
}
