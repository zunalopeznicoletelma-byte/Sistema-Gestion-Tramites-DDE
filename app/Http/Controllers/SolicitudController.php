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
     */
    public function index()
    {
        try {

            $solicitudes = Solicitud::listar();

            // Estados disponibles
            $estados = DB::table('b_estado_tramite')
                ->where('habilitado', 1)
                ->orderBy('cod_estado')
                ->get();

            // Usuarios disponibles
            $usuarios = DB::table('e_usuario')
                ->where('activo', 1)
                ->orderBy('paterno')
                ->orderBy('nombre1')
                ->get();

            return view(
                'solicitudes',
                compact(
                    'solicitudes',
                    'estados',
                    'usuarios'
                )
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

        // Tipos de trámite habilitados
        $tipos = DB::table('b_tipos')
            ->where('habilitado', 1)
            ->orderBy('nombre_tramite')
            ->get();

        // Institutos tecnológicos habilitados
        $institutos = DB::table('b_instituto')
            ->where('habilitado', 1)
            ->orderBy('nombre_inst')
            ->get();

        // Carreras habilitadas, asociadas a su instituto
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
            compact(
                'tipos',
                'institutos',
                'carreras'
            )
        );

    } catch (\Throwable $e) {

        return back()
            ->with(
                'error',
                'No se pudieron cargar los datos: '
                . $e->getMessage()
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
        // Verificar que el usuario haya iniciado sesión.
        $cod_usuario = session('usuario_id');

        if (!$cod_usuario) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'usuario' => 'La sesión ha expirado. Debe iniciar sesión nuevamente.'
                ]);
        }

        // Verificar que la carrera pertenezca al instituto seleccionado.
        $carreraValida = DB::table('b_carrera')
            ->where('cod_carrera', $datos['cod_carrera'])
            ->where('cod_instituto', $datos['cod_instituto'])
            ->where('habilitado', 1)
            ->exists();

        if (!$carreraValida) {
            return back()
                ->withInput()
                ->withErrors([
                    'cod_carrera' => 'La carrera seleccionada no pertenece al instituto elegido.'
                ]);
        }

        // Verificar que el instituto esté habilitado.
        $institutoValido = DB::table('b_instituto')
            ->where('cod_instituto', $datos['cod_instituto'])
            ->where('habilitado', 1)
            ->exists();

        if (!$institutoValido) {
            return back()
                ->withInput()
                ->withErrors([
                    'cod_instituto' => 'El instituto seleccionado no está habilitado.'
                ]);
        }

        // Preparar los datos del formulario como una descripción.
        $observacion = implode("\n", [
            'DATOS ERRADOS: ' . $datos['datos_errados'],
            'DATOS CORRECTOS: ' . $datos['datos_correctos'],
            'CARRERA: ' . DB::table('b_carrera')
                ->where('cod_carrera', $datos['cod_carrera'])
                ->value('nombre_carrera'),
            'ASIGNATURA: '.($datos['asignatura'] ?? 'No especificada'),
            'NIVEL: ' . $datos['nivel'],
            'CURSO: ' . $datos['curso'],
            'SEMESTRE/GESTIÓN: ' . $datos['semestre_gestion'],
        ]);

        // El campo observacion existente admite hasta 1000 caracteres
        // según la validación usada actualmente en el controlador.
        if (mb_strlen($observacion, 'UTF-8') > 1000) {
            return back()
                ->withInput()
                ->withErrors([
                    'datos_errados' => 'La información combinada supera los 1000 caracteres permitidos. Reduzca el texto.'
                ]);
        }

        // Registrar con el procedimiento almacenado existente.
        Solicitud::insertar([
            'cod_usuario' => $cod_usuario,
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
            ->with(
                'error',
                'No se pudo registrar la solicitud: ' . $e->getMessage()
            );
    }
}


    /**
     * FORMULARIO PARA EDITAR SOLICITUD
     */
    public function edit($cod_solicitud)
    {
        try {

            $solicitud = Solicitud::obtener($cod_solicitud);

            if (!$solicitud) {

                return redirect()
                    ->route('solicitudes.index')
                    ->with(
                        'error',
                        'La solicitud no fue encontrada.'
                    );
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
                compact(
                    'solicitud',
                    'tipos',
                    'institutos',
                    'planes',
                    'estados'
                )
            );

        } catch (\Throwable $e) {

            return redirect()
                ->route('solicitudes.index')
                ->with(
                    'error',
                    'No se pudo cargar la solicitud: '
                    . $e->getMessage()
                );
        }
    }


    /**
     * ACTUALIZAR SOLICITUD
     */
    public function update(
        Request $request,
        $cod_solicitud
    ) {
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

            Solicitud::actualizar(
                $cod_solicitud,
                $datos
            );

            return redirect()
                ->route('solicitudes.index')
                ->with(
                    'success',
                    'La solicitud fue actualizada correctamente.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No se pudo actualizar la solicitud: '
                    . $e->getMessage()
                );
        }
    }


    /**
     * ELIMINAR SOLICITUD
     */
    public function destroy($cod_solicitud)
    {
        try {

            Solicitud::eliminar($cod_solicitud);

            return redirect()
                ->route('solicitudes.index')
                ->with(
                    'success',
                    'La solicitud fue eliminada correctamente.'
                );

        } catch (\Throwable $e) {

            return back()
                ->with(
                    'error',
                    'No se pudo eliminar la solicitud: '
                    . $e->getMessage()
                );
        }
    }


    /**
     * CAMBIAR ESTADO MANUALMENTE
     *
     * Este método se conserva temporalmente para evitar
     * problemas con la ruta existente.
     *
     * El flujo oficial NO permite modificar estados
     * manualmente desde este método.
     *
     * La modificación del estado debe realizarse
     * mediante la REVISIÓN TÉCNICA.
     */
    public function cambiarEstado(
        Request $request,
        $cod_solicitud
    ) {
        return redirect()
            ->route('solicitudes.index')
            ->with(
                'error',
                'El cambio manual de estado no está habilitado. '
                . 'La solicitud debe ser procesada mediante la revisión técnica.'
            );
    }


    /**
     * REVISIÓN TÉCNICA
     *
     * Únicamente el Técnico Departamental
     * puede realizar esta operación.
     */
    public function revisionTecnica(
        Request $request,
        $cod_solicitud
    ) {
        $request->validate([
            'decision' => 'required|in:APROBADO,RECHAZADO,OBSERVADO',
            'informe' => 'nullable|string|max:2000',
            'observacion' => 'nullable|string|max:1000',
        ]);

        try {

            // Obtener usuario de la sesión
            $cod_usuario = session('usuario_id');

            // Verificar sesión
            if (!$cod_usuario) {

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'usuario' =>
                            'La sesión ha expirado. Debe iniciar sesión nuevamente.'
                    ]);
            }

            // Verificar que sea Técnico Departamental
            if (session('cod_acceso') != 2) {

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'No tiene autorización para realizar la revisión técnica.'
                    );
            }

            // Ejecutar procedimiento almacenado
            Solicitud::revisionTecnica(
                $cod_solicitud,
                $cod_usuario,
                $request->decision,
                $request->informe,
                $request->observacion
            );

            return redirect()
                ->route('solicitudes.index')
                ->with(
                    'success',
                    'La solicitud fue procesada correctamente como '
                    . strtolower($request->decision)
                    . '.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /**
     * MOSTRAR HISTORIAL DE LA SOLICITUD
     */
    public function historial($cod_solicitud)
    {
        try {

            $solicitud = Solicitud::obtener(
                $cod_solicitud
            );

            if (!$solicitud) {

                return redirect()
                    ->route('solicitudes.index')
                    ->with(
                        'error',
                        'La solicitud no fue encontrada.'
                    );
            }

            $historial = Solicitud::historial(
                $cod_solicitud
            );

            return view(
                'solicitudes.historial',
                compact(
                    'solicitud',
                    'historial'
                )
            );

        } catch (\Throwable $e) {

            return redirect()
                ->route('solicitudes.index')
                ->with(
                    'error',
                    'No se pudo obtener el historial: '
                    . $e->getMessage()
                );
        }
    }
}