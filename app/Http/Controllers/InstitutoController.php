<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstitutoController extends Controller
{
    public function index(Request $request)
    {
        $rutaArchivo = resource_path('data/institutos.php');

        if (!file_exists($rutaArchivo)) {
            abort(500, 'No se encontró resources/data/institutos.php');
        }

        $datos = require $rutaArchivo;

        if (!is_array($datos)) {
            abort(500, 'El archivo institutos.php debe devolver un arreglo.');
        }

        $institutos = collect($datos);

        $busqueda = trim($request->input('buscar', ''));
        $dependenciaSeleccionada = $request->input('dependencia', '');
        $municipioSeleccionado = $request->input('municipio', '');

        $dependencias = ['Fiscal', 'Privado', 'Convenio'];

        $municipios = $institutos
            ->pluck('municipio')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $institutosFiltrados = $institutos->filter(function ($instituto) use (
            $busqueda,
            $dependenciaSeleccionada,
            $municipioSeleccionado
        ) {
            $nombre = $instituto['nombre'] ?? '';
            $dependencia = $instituto['dependencia'] ?? '';
            $municipio = $instituto['municipio'] ?? '';

            $coincideBusqueda = $busqueda === ''
                || str_contains(
                    mb_strtolower($nombre),
                    mb_strtolower($busqueda)
                );

            $coincideDependencia = $dependenciaSeleccionada === ''
                || $dependencia === $dependenciaSeleccionada;

            $coincideMunicipio = $municipioSeleccionado === ''
                || $municipio === $municipioSeleccionado;

            return $coincideBusqueda
                && $coincideDependencia
                && $coincideMunicipio;
        })->values();

        return view('institutos', [
            'institutos' => $institutosFiltrados,
            'municipios' => $municipios,
            'dependencias' => $dependencias,
            'buscar' => $busqueda,
            'dependenciaSeleccionada' => $dependenciaSeleccionada,
            'municipioSeleccionado' => $municipioSeleccionado,
            'totalInstitutos' => $institutos->count(),
            'totalFiscales' => $institutos->where('dependencia', 'Fiscal')->count(),
            'totalPrivados' => $institutos->where('dependencia', 'Privado')->count(),
            'totalConvenio' => $institutos->where('dependencia', 'Convenio')->count(),
        ]);
    }
}
