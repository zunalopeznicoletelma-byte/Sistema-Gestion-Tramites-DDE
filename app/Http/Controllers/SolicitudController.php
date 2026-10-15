<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    /**
     * LISTAR SOLICITUDES
     * El Directivo (rol 3) solo ve sus propias solicitudes.
     * Los demas roles conservan el listado actual.
     */
    public function index()
    {
        try {
            $codUsuario = session('usuario_id');
            $codAcceso = (int) session('cod_acceso');

            if (!$codUsuario) {
                return redirect()->route('login');
            }

            if ($codAcceso === 3) {
                $solicitudes = DB::table('e_solici as s')
                    ->join(
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
                        'b_estado_tramite as es',
                        's.cod_estado',
                        '=',
                        'es.cod_estado'
                    )
                    ->leftJoin(
                        'b_plan_estudio as p',
                        's.cod_plan',
                        '=',
                        'p.cod_plan'
                    )
                    ->leftJoin(
                        'b_carrera as c',
                        'p.cod_carrera',
                        '=',
                        'c.cod_carrera'
                    )
                    ->where('s.cod_usuario', $codUsuario)
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
                        DB::raw(
                            "LTRIM(RTRIM(CONCAT(
                                COALESCE(u.nombre1, ''), ' ',
                                COALESCE(u.nombre2, ''), ' ',
                                COALESCE(u.paterno, ''), ' ',
                                COALESCE(u.materno, '')
                            ))) as solicitante"
                        ),
                        't.nombre_tramite',
                        'i.nombre_inst as instituto',
                        'c.nombre_carrera as carrera',
                        'p.titulo_profesional',
                        'es.nombre_estado as estado'
                    )
                    ->orderByDesc('s.cod_solicitud')
                    ->get();
            } else {
                // Mantener el procedimiento almacenado existente.
                $solicitudes = Solicitud::listar();
            }

            $estados = DB::table('b_estado_tramite')
                ->where('habilitado', 1)
                ->orderBy('cod_estado')
                ->get();

            $usuarios = DB::table('e_usuario')
                ->where('activo', 1)
                ->orderBy('paterno')
                ->orderBy('nombre1')
                ->get();

            return view(
                'solicitudes',
                compact('solicitudes', 'estados', 'usuarios')
            );

        } catch (\Throwable $e) {
            return view('solicitudes', [
                'solicitudes' => [],
                'estados' => [],
                'usuarios' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * FORMULARIO PARA CREAR SOLICITUD
     */
    public function create()
    {
        try {
            $tipos = DB::table('b_tipos')
                ->where('habilitado', 1)
                ->orderBy('nombre_tramite')
                ->get();

            $institutos = DB::table('b_instituto')
                ->where('habilitado', 1)
                ->orderBy('nombre_inst')
                ->get();

            $carreras = DB::table('b_carrera as c')
                ->join(
                    'b_instituto as i',
                    'c.cod_instituto',
                    '=',
                    'i.cod_instituto'
                )
                ->where('c.habilitado', 1)
                ->where('i.habilitado', 1)
                ->select(
                    'c.cod_carrera',
                    'c.nombre_carrera',
                    'c.cod_instituto'
                )
                ->orderBy('c.nombre_carrera')
                ->get();

            return view(
                'solicitudes.create',
                compact('tipos', 'institutos', 'carreras')
            );

        } catch (\Throwable $e) {
            return back()->with(
                'error',
                'No se pudieron cargar los datos: ' . $e->getMessage()
            );
        }
    }

    /**
     * REGISTRAR SOLICITUD
     */
    public function store(Request $request)
    {
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

            if (!$codUsuario) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'usuario' => 'La sesión ha expirado. Debe iniciar sesión nuevamente.'
                    ]);
            }

            $carreraValida = DB::table('b_carrera')
                ->where('cod_carrera', $datos['cod_carrera'])
                ->where('cod_instituto', $datos['cod_instituto'])
                ->where('habilitado', 1)
                ->exists();

            if (!$carreraValida) {
                return back()->withInput()->withErrors([
                    'cod_carrera' => 'La carrera seleccionada no pertenece al instituto elegido.'
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

            $nombreCarrera = DB::table('b_carrera')
                ->where('cod_carrera', $datos['cod_carrera'])
                ->value('nombre_carrera');

            $observacion = implode("\n", [
                'DATOS ERRADOS: ' . $datos['datos_errados'],
                'DATOS CORRECTOS: ' . $datos['datos_correctos'],
                'CARRERA: ' . $nombreCarrera,
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

            return redirect()
                ->route('solicitudes.index')
                ->with('success', 'La solicitud fue registrada correctamente.');

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar la solicitud: ' . $e->getMessage());
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
                return redirect()
                    ->route('solicitudes.index')
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
                ->join(
                    'b_carrera as c',
                    'p.cod_carrera',
                    '=',
                    'c.cod_carrera'
                )
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

            return view(
                'solicitudes.edit',
                compact('solicitud', 'tipos', 'institutos', 'planes', 'estados')
            );

        } catch (\Throwable $e) {
            return redirect()
                ->route('solicitudes.index')
                ->with('error', 'No se pudo cargar la solicitud: ' . $e->getMessage());
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
            // El Directivo no puede transferir su solicitud a otro usuario.
            if ((int) session('cod_acceso') === 3) {
                $datos['cod_usuario'] = session('usuario_id');
            }

            Solicitud::actualizar($cod_solicitud, $datos);

            return redirect()
                ->route('solicitudes.index')
                ->with('success', 'La solicitud fue actualizada correctamente.');

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * ELIMINAR SOLICITUD
     */
    public function destroy($cod_solicitud)
    {
        if (!$this->solicitudAccesible($cod_solicitud)) {
            return $this->accesoDenegado();
        }

        try {
            Solicitud::eliminar($cod_solicitud);

            return redirect()
                ->route('solicitudes.index')
                ->with('success', 'La solicitud fue eliminada correctamente.');

        } catch (\Throwable $e) {
            return back()->with(
                'error',
                'No se pudo eliminar la solicitud: ' . $e->getMessage()
            );
        }
    }

    /**
     * CAMBIO MANUAL DE ESTADO DESHABILITADO
     */
    public function cambiarEstado(Request $request, $cod_solicitud)
    {
        return redirect()
            ->route('solicitudes.index')
            ->with(
                'error',
                'El cambio manual de estado no está habilitado. La solicitud debe ser procesada mediante la revisión técnica.'
            );
    }

    /**
     * REVISIÓN TÉCNICA
     */
    public function revisionTecnica(Request $request, $cod_solicitud)
    {
        $request->validate([
            'decision' => 'required|in:APROBADO,RECHAZADO,OBSERVADO',
            'informe' => 'nullable|string|max:2000',
            'observacion' => 'nullable|string|max:1000',
        ]);

        try {
            $codUsuario = session('usuario_id');

            if (!$codUsuario) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'usuario' => 'La sesión ha expirado. Debe iniciar sesión nuevamente.'
                    ]);
            }

            if ((int) session('cod_acceso') !== 2) {
                return back()->with(
                    'error',
                    'No tiene autorización para realizar la revisión técnica.'
                );
            }

            Solicitud::revisionTecnica(
                $cod_solicitud,
                $codUsuario,
                $request->decision,
                $request->informe,
                $request->observacion
            );

            return redirect()
                ->route('solicitudes.index')
                ->with(
                    'success',
                    'La solicitud fue procesada correctamente como ' .
                    strtolower($request->decision) . '.'
                );

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * MOSTRAR HISTORIAL
     */
    public function historial($cod_solicitud)
    {
        try {
            if (!$this->solicitudAccesible($cod_solicitud)) {
                return $this->accesoDenegado();
            }

            $solicitud = Solicitud::obtener($cod_solicitud);

            if (!$solicitud) {
                return redirect()
                    ->route('solicitudes.index')
                    ->with('error', 'La solicitud no fue encontrada.');
            }

            $historial = Solicitud::historial($cod_solicitud);

            return view(
                'solicitudes.historial',
                compact('solicitud', 'historial')
            );

        } catch (\Throwable $e) {
            return redirect()
                ->route('solicitudes.index')
                ->with('error', 'No se pudo obtener el historial: ' . $e->getMessage());
        }
    }

    /**
     * COMPROBAR SI EL DIRECTIVO PUEDE ACCEDER A LA SOLICITUD.
     * Los roles distintos al 3 conservan el comportamiento actual.
     */
    private function solicitudAccesible($cod_solicitud)
    {
        if ((int) session('cod_acceso') !== 3) {
            return true;
        }

        $codUsuario = session('usuario_id');

        if (!$codUsuario) {
            return false;
        }

        return DB::table('e_solici')
            ->where('cod_solicitud', $cod_solicitud)
            ->where('cod_usuario', $codUsuario)
            ->exists();
    }

    /**
     * RESPUESTA CUANDO SE INTENTA ACCEDER A UNA SOLICITUD AJENA.
     */
    private function accesoDenegado()
    {
        return redirect()
            ->route('solicitudes.index')
            ->with(
                'error',
                'No tiene autorización para acceder a esta solicitud.'
            );
    }
}
