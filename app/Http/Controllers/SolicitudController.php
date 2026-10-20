<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolicitudController extends Controller
{
    /**
     * LISTAR SOLICITUDES
     */
    public function index()
    {
        try {
            $codUsuario = session('usuario_id');
            $codAcceso = (int) session('cod_acceso');

            if (!$codUsuario) {
                return redirect()->route('login');
            }

            if (!in_array($codAcceso, [1, 2, 3, 7], true)) {
                return $this->accesoDenegado();
            }

            $consulta = DB::table('e_solici as s')
                ->join('e_usuario as u', 's.cod_usuario', '=', 'u.cod_usuario')
                ->leftJoin('b_tipos as t', 's.cod_tipo', '=', 't.cod_tipo')
                ->leftJoin('b_instituto as i', 's.cod_instituto', '=', 'i.cod_instituto')
                ->leftJoin('b_estado_tramite as es', 's.cod_estado', '=', 'es.cod_estado')
                ->leftJoin('b_plan_estudio as p', 's.cod_plan', '=', 'p.cod_plan')
                ->leftJoin('b_carrera as c', 'p.cod_carrera', '=', 'c.cod_carrera')
                ->where('s.activo', 1)
                ->select(
                    's.cod_solicitud',
                    's.fecha_solicitud',
                    's.cod_usuario',
                    's.cod_tipo',
                    's.cod_instituto',
                    's.cod_plan',
                    's.cod_estado',
                    's.observacion',
                    's.activo',
                    'u.usuario as usuario',
                    DB::raw("LTRIM(RTRIM(CONCAT(
                        COALESCE(u.nombre1, ''), ' ',
                        COALESCE(u.nombre2, ''), ' ',
                        COALESCE(u.paterno, ''), ' ',
                        COALESCE(u.materno, '')
                    ))) as solicitante"),
                    't.nombre_tramite',
                    'i.nombre_inst as instituto',
                    'c.nombre_carrera as carrera',
                    'p.titulo_profesional',
                    'es.nombre_estado as estado'
                );

            if (!in_array($codAcceso, [1, 2], true)) {
                $consulta->where('s.cod_usuario', $codUsuario);
            }

            $solicitudes = $consulta->orderByDesc('s.cod_solicitud')->get();

            $estados = DB::table('b_estado_tramite')
                ->where('habilitado', 1)
                ->orderBy('cod_estado')
                ->get();

            $usuarios = DB::table('e_usuario')
                ->where('activo', 1)
                ->orderBy('paterno')
                ->orderBy('nombre1')
                ->get();

            return view('solicitudes', compact('solicitudes', 'estados', 'usuarios'));

        } catch (\Throwable $e) {
            Log::error('Error al listar solicitudes: ' . $e->getMessage());

            return view('solicitudes', [
                'solicitudes' => collect(),
                'estados' => collect(),
                'usuarios' => collect(),
                'error' => 'No se pudieron cargar las solicitudes. Verifique la conexión y vuelva a intentarlo.'
            ]);
        }
    }

    /**
     * FORMULARIO PARA CREAR SOLICITUD
     */
    public function create()
    {
        try {
            if (!session('usuario_id')) {
                return redirect()->route('login');
            }

            if (!in_array((int) session('cod_acceso'), [1, 2, 3, 7], true)) {
                return $this->accesoDenegado();
            }

            $tipos = DB::table('b_tipos')
                ->where('habilitado', 1)
                ->orderBy('nombre_tramite')
                ->get();

            $institutos = DB::table('b_instituto')
                ->where('habilitado', 1)
                ->orderBy('nombre_inst')
                ->get();

            $carreras = DB::table('b_carrera as c')
                ->join('b_instituto as i', 'c.cod_instituto', '=', 'i.cod_instituto')
                ->where('c.habilitado', 1)
                ->where('i.habilitado', 1)
                ->select('c.cod_carrera', 'c.nombre_carrera', 'c.cod_instituto')
                ->orderBy('c.nombre_carrera')
                ->get();

            return view('solicitudes.create', compact('tipos', 'institutos', 'carreras'));

        } catch (\Throwable $e) {
            Log::error('Error al cargar formulario de solicitud: ' . $e->getMessage());

            return back()->with('error', 'No se pudieron cargar los datos del formulario.');
        }
    }

    /**
     * REGISTRAR SOLICITUD DE USUARIOS EN EL SISTEMA
     */
    public function store(Request $request)
    {
        if (!session('usuario_id')) {
            return redirect()->route('login')->withErrors([
                'usuario' => 'La sesión ha expirado. Debe iniciar sesión nuevamente.'
            ]);
        }

        if (!in_array((int) session('cod_acceso'), [1, 2, 3, 7], true)) {
            return $this->accesoDenegado();
        }

        $datos = $request->validate([
            'cod_tipo' => 'required|integer|exists:b_tipos,cod_tipo',
            'cod_instituto' => 'required|integer|exists:b_instituto,cod_instituto',
            'cod_carrera' => 'required|integer|exists:b_carrera,cod_carrera',
            'datos_errados' => 'required|string|max:350',
            'datos_correctos' => 'required|string|max:350',
            'asignatura' => 'nullable|string|max:100',
            'nivel' => 'required|in:Técnico Superior,Técnico Medio,Auxiliar,Capacitación,Otros',
            'curso' => 'required|string|max:50',
            'semestre_gestion' => 'required|string|max:50',
        ]);

        try {
            $codUsuario = session('usuario_id');

            $tipoValido = DB::table('b_tipos')
                ->where('cod_tipo', $datos['cod_tipo'])
                ->where('habilitado', 1)
                ->exists();

            if (!$tipoValido) {
                return back()->withInput()->withErrors([
                    'cod_tipo' => 'El tipo de trámite seleccionado no está habilitado.'
                ]);
            }

            $institutoValido = DB::table('b_instituto')
                ->where('cod_instituto', $datos['cod_instituto'])
                ->where('habilitado', 1)
                ->exists();

            if (!$institutoValido) {
                return back()->withInput()->withErrors([
                    'cod_instituto' => 'El instituto seleccionado no está habilitado.'
                ]);
            }

            $carrera = DB::table('b_carrera')
                ->where('cod_carrera', $datos['cod_carrera'])
                ->where('cod_instituto', $datos['cod_instituto'])
                ->where('habilitado', 1)
                ->first();

            if (!$carrera) {
                return back()->withInput()->withErrors([
                    'cod_carrera' => 'La carrera seleccionada no pertenece al instituto elegido o no está habilitada.'
                ]);
            }

            $observacion = implode("\n", [
                'DATOS ERRADOS: ' . $datos['datos_errados'],
                'DATOS CORRECTOS: ' . $datos['datos_correctos'],
                'CARRERA: ' . $carrera->nombre_carrera,
                'ASIGNATURA: ' . ($datos['asignatura'] ?? 'No especificada'),
                'NIVEL: ' . $datos['nivel'],
                'CURSO: ' . $datos['curso'],
                'SEMESTRE/GESTIÓN: ' . $datos['semestre_gestion'],
            ]);

            if (mb_strlen($observacion, 'UTF-8') > 1000) {
                return back()->withInput()->withErrors([
                    'datos_errados' => 'La información combinada supera los 1000 caracteres permitidos. Reduzca el texto.'
                ]);
            }

            Solicitud::insertar([
                'cod_usuario' => $codUsuario,
                'cod_tipo' => $datos['cod_tipo'],
                'cod_instituto' => $datos['cod_instituto'],
                'cod_plan' => null,
                'observacion' => $observacion,
            ]);

            return redirect()->route('solicitudes.index')
                ->with('success', 'La solicitud fue registrada correctamente.');

        } catch (\Throwable $e) {
            Log::error('Error al registrar solicitud: ' . $e->getMessage());

            return back()->withInput()->with(
                'error',
                'No se pudo registrar la solicitud. Verifique los datos e inténtelo nuevamente.'
            );
        }
    }

    /**
     * FORMULARIO PARA EDITAR SOLICITUD
     */
    public function edit($cod_solicitud)
    {
        try {
            if (!$this->solicitudAccesible($cod_solicitud)) {
                return $this->accesoDenegado();
            }

            $solicitud = Solicitud::obtener($cod_solicitud);

            if (!$solicitud) {
                return redirect()->route('solicitudes.index')
                    ->with('error', 'La solicitud no fue encontrada.');
            }

            $tipos = DB::table('b_tipos')
                ->where('habilitado', 1)
                ->orderBy('nombre_tramite')
                ->get();

            $institutos = DB::table('b_instituto')
                ->where('habilitado', 1)
                ->orderBy('nombre_inst')
                ->get();

            $planes = DB::table('b_plan_estudio as p')
                ->join('b_carrera as c', 'p.cod_carrera', '=', 'c.cod_carrera')
                ->where('p.habilitado', 1)
                ->where('c.habilitado', 1)
                ->select(
                    'p.cod_plan',
                    'p.resolucion',
                    'p.area_formacion',
                    'p.titulo_profesional',
                    'p.modalidad',
                    'c.nombre_carrera'
                )
                ->orderBy('c.nombre_carrera')
                ->get();

            $estados = DB::table('b_estado_tramite')
                ->where('habilitado', 1)
                ->orderBy('cod_estado')
                ->get();

            return view('solicitudes.edit', compact(
                'solicitud',
                'tipos',
                'institutos',
                'planes',
                'estados'
            ));

        } catch (\Throwable $e) {
            Log::error('Error al cargar solicitud para edición: ' . $e->getMessage());

            return redirect()->route('solicitudes.index')
                ->with('error', 'No se pudo cargar la solicitud.');
        }
    }

    /**
     * ACTUALIZAR SOLICITUD
     */
    public function update(Request $request, $cod_solicitud)
    {
        if (!$this->solicitudAccesible($cod_solicitud)) {
            return $this->accesoDenegado();
        }

        $datos = $request->validate([
            'cod_usuario' => 'required|integer',
            'cod_tipo' => 'required|integer',
            'cod_instituto' => 'required|integer',
            'cod_plan' => 'nullable|integer',
            'cod_estado' => 'required|integer',
            'observacion' => 'nullable|string|max:1000',
            'activo' => 'required|boolean',
        ]);

        try {
            $codAcceso = (int) session('cod_acceso');

            if ($codAcceso !== 1) {
                $solicitudActual = DB::table('e_solici')
                    ->where('cod_solicitud', $cod_solicitud)
                    ->first();

                if (!$solicitudActual) {
                    return redirect()->route('solicitudes.index')
                        ->with('error', 'La solicitud no fue encontrada.');
                }

                $datos['cod_usuario'] = $solicitudActual->cod_usuario;
                $datos['cod_tipo'] = $solicitudActual->cod_tipo;
                $datos['cod_instituto'] = $solicitudActual->cod_instituto;
                $datos['cod_plan'] = $solicitudActual->cod_plan;
                $datos['cod_estado'] = $solicitudActual->cod_estado;
                $datos['activo'] = $solicitudActual->activo;
            }

            Solicitud::actualizar($cod_solicitud, $datos);

            return redirect()->route('solicitudes.index')
                ->with('success', 'La solicitud fue actualizada correctamente.');

        } catch (\Throwable $e) {
            Log::error('Error al actualizar solicitud: ' . $e->getMessage());

            return back()->withInput()->with(
                'error',
                'No se pudo actualizar la solicitud. Verifique los datos e inténtelo nuevamente.'
            );
        }
    }

    /**
     * ELIMINAR SOLICITUD
     */
    public function destroy($cod_solicitud)
    {
        if ((int) session('cod_acceso') !== 1) {
            return $this->accesoDenegado();
        }

        if (!$this->solicitudAccesible($cod_solicitud)) {
            return $this->accesoDenegado();
        }

        try {
            Solicitud::eliminar($cod_solicitud);

            return redirect()->route('solicitudes.index')
                ->with('success', 'La solicitud fue eliminada correctamente.');

        } catch (\Throwable $e) {
            Log::error('Error al eliminar solicitud: ' . $e->getMessage());

            return back()->with('error', 'No se pudo eliminar la solicitud.');
        }
    }

    /**
     * CAMBIO MANUAL DE ESTADO DESHABILITADO
     */
    public function cambiarEstado(Request $request, $cod_solicitud)
    {
        return redirect()->route('solicitudes.index')->with(
            'error',
            'El cambio manual de estado no está habilitado. La solicitud debe ser procesada mediante la revisión técnica.'
        );
    }

    /**
     * REVISIÓN TÉCNICA
     */
    public function revisionTecnica(Request $request, $cod_solicitud)
    {
        $codUsuario = session('usuario_id');

        if (!$codUsuario) {
            return redirect()->route('login')->withErrors([
                'usuario' => 'La sesión ha expirado. Debe iniciar sesión nuevamente.'
            ]);
        }

        if ((int) session('cod_acceso') !== 2) {
            return $this->accesoDenegado();
        }

        $request->validate([
            'decision' => 'required|in:APROBADO,RECHAZADO,OBSERVADO',
            'informe' => 'nullable|string|max:2000',
            'observacion' => 'nullable|string|max:1000',
        ]);

        try {
            $existe = DB::table('e_solici')
                ->where('cod_solicitud', $cod_solicitud)
                ->where('activo', 1)
                ->exists();

            if (!$existe) {
                return redirect()->route('solicitudes.index')
                    ->with('error', 'La solicitud no fue encontrada o está inactiva.');
            }

            Solicitud::revisionTecnica(
                $cod_solicitud,
                $codUsuario,
                $request->decision,
                $request->informe,
                $request->observacion
            );

            return redirect()->route('solicitudes.index')->with(
                'success',
                'La solicitud fue procesada correctamente como ' .
                strtolower($request->decision) . '.'
            );

        } catch (\Throwable $e) {
            Log::error('Error en revisión técnica: ' . $e->getMessage());

            return back()->withInput()->with(
                'error',
                'No se pudo procesar la revisión técnica. Verifique la solicitud e inténtelo nuevamente.'
            );
        }
    }

    /**
     * HISTORIAL INDIVIDUAL DE UNA SOLICITUD
     */
    public function historial($cod_solicitud)
    {
        if (!session('usuario_id')) {
            return redirect()->route('login');
        }

        try {
            if (!$this->solicitudAccesible($cod_solicitud)) {
                return $this->accesoDenegado();
            }

            $solicitud = Solicitud::obtener($cod_solicitud);

            if (!$solicitud) {
                return redirect()->route('solicitudes.index')
                    ->with('error', 'La solicitud no fue encontrada.');
            }

            $historial = Solicitud::historial($cod_solicitud);

            return view('solicitudes.historial', compact('solicitud', 'historial'));

        } catch (\Throwable $e) {
            Log::error('Error en historial individual: ' . $e->getMessage());

            return redirect()->route('solicitudes.index')
                ->with('error', 'No se pudo obtener el historial individual.');
        }
    }

    /**
     * HISTORIAL GENERAL
     * Acceso exclusivo para el Técnico Departamental.
     */
    public function historialGeneral()
    {
        if (!session('usuario_id')) {
            return redirect()->route('login');
        }

        if ((int) session('cod_acceso') !== 2) {
            return $this->accesoDenegado();
        }

        try {
            $historial = DB::table('e_historial_solicitud as h')
                ->join(
                    'e_solici as s',
                    's.cod_solicitud',
                    '=',
                    'h.cod_solicitud'
                )
                ->join(
                    'b_estado_tramite as e',
                    'e.cod_estado',
                    '=',
                    'h.cod_estado'
                )
                ->join(
                    'e_usuario as u',
                    'u.cod_usuario',
                    '=',
                    'h.cod_usuario'
                )
                ->where('s.activo', 1)
                ->select(
                    'h.cod_historial',
                    'h.cod_solicitud',
                    'h.fecha',
                    'e.nombre_estado',
                    'h.observacion',
                    DB::raw("LTRIM(RTRIM(CONCAT(
                        COALESCE(u.paterno, ''), ' ',
                        COALESCE(u.materno, ''), ' ',
                        COALESCE(u.nombre1, ''), ' ',
                        COALESCE(u.nombre2, '')
                    ))) AS usuario")
                )
                ->orderByDesc('h.fecha')
                ->get();

            return view(
                'solicitudes.historial-general',
                compact('historial')
            );

        } catch (\Throwable $e) {
            Log::error('Error en historial general: ' . $e->getMessage(), [
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ]);

            // Mostrar el error técnico temporalmente para identificar la causa.
            return response()->json([
                'error' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ], 500);
        }
    }

    /**
     * VERIFICAR ACCESO A UNA SOLICITUD
     */
    private function solicitudAccesible($cod_solicitud)
    {
        $codUsuario = session('usuario_id');
        $codAcceso = (int) session('cod_acceso');

        if (!$codUsuario) {
            return false;
        }

        $consulta = DB::table('e_solici')
            ->where('cod_solicitud', $cod_solicitud)
            ->where('activo', 1);

        if (in_array($codAcceso, [1, 2], true)) {
            return $consulta->exists();
        }

        if (in_array($codAcceso, [3, 7], true)) {
            return $consulta
                ->where('cod_usuario', $codUsuario)
                ->exists();
        }

        return false;
    }

    /**
     * ACCESO DENEGADO
     */
    private function accesoDenegado()
    {
        return redirect()->route('solicitudes.index')->with(
            'error',
            'No tiene autorización para realizar esta acción.'
        );
    }
}
