<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\IOFactory;


class InformeController extends Controller
{
    /**
     * Generar informe técnico en formato Word.
     */
    public function generarWord($cod_solicitud)
    {
        try {
            $resultado = DB::select(
                'EXEC sp_solicitud_obtener @cod_solicitud = ?',
                [$cod_solicitud]
            );

            if (empty($resultado)) {
                return redirect()
                    ->route('solicitudes.index')
                    ->with('error', 'La solicitud no existe.');
            }

            $solicitud = $resultado[0];

            $phpWord = new PhpWord();

            $phpWord->setDefaultFontName('Times New Roman');
            $phpWord->setDefaultFontSize(12);
            $phpWord->getSettings()->setThemeFontLang(
                new Language('es-BO')
            );

            $section = $phpWord->addSection([
                'pageSizeW' => 12240,
                'pageSizeH' => 15840,
                'marginTop' => 1134,
                'marginBottom' => 1134,
                'marginLeft' => 1134,
                'marginRight' => 1134,
            ]);

            $titulo = [
                'bold' => true,
                'size' => 14,
                'name' => 'Times New Roman',
            ];

            $subtitulo = [
                'bold' => true,
                'size' => 12,
                'name' => 'Times New Roman',
            ];

            $texto = [
                'name' => 'Times New Roman',
                'size' => 12,
            ];

            $campoEditable = [
                'name' => 'Times New Roman',
                'size' => 12,
                'bgColor' => 'FFFF00',
            ];

            $section->addText(
                'MINISTERIO DE EDUCACIÓN',
                $subtitulo,
                ['alignment' => Jc::CENTER]
            );

            $section->addText(
                'DIRECCIÓN DEPARTAMENTAL DE EDUCACIÓN',
                $subtitulo,
                ['alignment' => Jc::CENTER]
            );

            $section->addText(
                'COCHABAMBA',
                $subtitulo,
                ['alignment' => Jc::CENTER]
            );

            $section->addTextBreak(1);

            $section->addText(
                'INFORME TÉCNICO',
                $titulo,
                ['alignment' => Jc::CENTER]
            );

            $section->addTextBreak(1);

            $section->addText(
                'I. DATOS GENERALES',
                $subtitulo
            );

            $section->addText(
                'Número de solicitud: '
                    . $solicitud->cod_solicitud,
                $texto
            );

            $section->addText(
                'Fecha de solicitud: '
                    . $solicitud->fecha_solicitud,
                $texto
            );

            $section->addText(
                'Solicitante: '
                    . $solicitud->solicitante,
                $texto
            );

            $section->addText(
                'Tipo de trámite: '
                    . $solicitud->nombre_tramite,
                $texto
            );

            $section->addText(
                'Instituto: '
                    . $solicitud->instituto,
                $texto
            );

            $section->addText(
                'Estado actual: '
                    . $solicitud->estado,
                $texto
            );

            $section->addTextBreak(1);

            $section->addText(
                'II. ANTECEDENTES',
                $subtitulo
            );

            $section->addText(
                $solicitud->observacion
                    ?: 'Sin observaciones registradas.',
                $texto,
                ['alignment' => Jc::BOTH]
            );

            $section->addTextBreak(1);

            $section->addText(
                'III. REVISIÓN TÉCNICA',
                $subtitulo
            );

            $section->addText(
                'Análisis técnico: ',
                $texto
            );

            $section->addText(
                '[COMPLETAR ANÁLISIS TÉCNICO]',
                $campoEditable
            );

            $section->addTextBreak(1);

            $section->addText(
                'IV. OBSERVACIONES',
                $subtitulo
            );

            $section->addText(
                '[COMPLETAR OBSERVACIONES, SI CORRESPONDE]',
                $campoEditable
            );

            $section->addTextBreak(1);

            $section->addText(
                'V. CONCLUSIÓN Y RECOMENDACIÓN',
                $subtitulo
            );

            $section->addText(
                '[COMPLETAR CONCLUSIÓN Y RECOMENDACIÓN TÉCNICA]',
                $campoEditable
            );

            $section->addTextBreak(2);

            $section->addText(
                '________________________________',
                $texto,
                ['alignment' => Jc::CENTER]
            );

            $section->addText(
                'Técnico Departamental',
                $texto,
                ['alignment' => Jc::CENTER]
            );

            $section->addText(
                'Dirección Departamental de Educación Cochabamba',
                $texto,
                ['alignment' => Jc::CENTER]
            );

            $nombreArchivo = 'Informe_Tecnico_Solicitud_'
                . $solicitud->cod_solicitud
                . '.docx';

            $rutaTemporal = tempnam(
                sys_get_temp_dir(),
                'informe_'
            );

            if ($rutaTemporal === false) {
                throw new \RuntimeException(
                    'No se pudo crear el archivo temporal.'
                );
            }

            $rutaDocx = $rutaTemporal . '.docx';

            rename($rutaTemporal, $rutaDocx);

            $writer = IOFactory::createWriter(
                $phpWord,
                'Word2007'
            );

            $writer->save($rutaDocx);

            return response()
                ->download($rutaDocx, $nombreArchivo)
                ->deleteFileAfterSend(true);

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('solicitudes.index')
                ->with(
                    'error',
                    'No se pudo generar el informe Word. '
                    . 'Revise el registro de errores de Laravel.'
                );
        }
    }
}
