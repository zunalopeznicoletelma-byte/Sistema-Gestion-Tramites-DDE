<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Solicitud extends Model
{
    protected $table = 'e_solici';

    protected $primaryKey = 'cod_solicitud';

    public $timestamps = false;

    protected $fillable = [
        'cod_usuario',
        'cod_tipo',
        'cod_instituto',
        'cod_plan',
        'cod_estado',
        'fecha_solicitud',
        'observacion',
        'activo',
    ];

    /**
     * Listar todas las solicitudes.
     */
    public static function listar()
    {
        return DB::select('EXEC sp_solicitud_listar');
    }

    /**
     * Obtener una solicitud por código.
     */
    public static function obtener($cod_solicitud)
    {
        $resultado = DB::select(
            'EXEC sp_solicitud_obtener @cod_solicitud = ?',
            [$cod_solicitud]
        );

        return !empty($resultado) ? $resultado[0] : null;
    }

    /**
     * Registrar una nueva solicitud.
     */
    public static function insertar(array $datos)
    {
        return DB::select(
            'EXEC sp_solicitud_insertar
                @cod_usuario = ?,
                @cod_tipo = ?,
                @cod_instituto = ?,
                @cod_plan = ?,
                @observacion = ?',
            [
                $datos['cod_usuario'],
                $datos['cod_tipo'],
                $datos['cod_instituto'],
                $datos['cod_plan'] ?? null,
                $datos['observacion'] ?? null,
            ]
        );
    }

    /**
     * Actualizar una solicitud.
     */
    public static function actualizar($cod_solicitud, array $datos)
    {
        return DB::statement(
            'EXEC sp_solicitud_actualizar
                @cod_solicitud = ?,
                @cod_usuario = ?,
                @cod_tipo = ?,
                @cod_instituto = ?,
                @cod_plan = ?,
                @cod_estado = ?,
                @observacion = ?,
                @activo = ?',
            [
                $cod_solicitud,
                $datos['cod_usuario'],
                $datos['cod_tipo'],
                $datos['cod_instituto'],
                $datos['cod_plan'],
                $datos['cod_estado'],
                $datos['observacion'],
                $datos['activo'],
            ]
        );
    }

    /**
     * Inhabilitar una solicitud.
     */
    public static function eliminar($cod_solicitud)
    {
        return DB::statement(
            'EXEC sp_solicitud_eliminar @cod_solicitud = ?',
            [$cod_solicitud]
        );
    }

    /**
     * Cambiar el estado de una solicitud.
     */
    public static function cambiarEstado(
        $cod_solicitud,
        $cod_estado,
        $cod_usuario,
        $observacion = null
    ) {
        return DB::statement(
            'EXEC sp_solicitud_cambiar_estado
                @cod_solicitud = ?,
                @cod_estado = ?,
                @cod_usuario = ?,
                @observacion = ?',
            [
                $cod_solicitud,
                $cod_estado,
                $cod_usuario,
                $observacion,
            ]
        );
    }

    /**
     * Registrar una revisión técnica.
     *
     * El Técnico Departamental puede:
     * APROBADO
     * RECHAZADO
     * OBSERVADO
     */
    public static function revisionTecnica(
        $cod_solicitud,
        $cod_usuario,
        $decision,
        $informe = null,
        $observacion = null
    ) {
        return DB::select(
            'EXEC sp_solicitud_revision_tecnica
                @cod_solicitud = ?,
                @cod_usuario = ?,
                @decision = ?,
                @informe = ?,
                @observacion = ?',
            [
                $cod_solicitud,
                $cod_usuario,
                $decision,
                $informe,
                $observacion,
            ]
        );
    }

    /**
     * Obtener el historial
     */
    public static function historial($cod_solicitud)
    {
        return DB::select(
            'EXEC sp_solicitud_historial @cod_solicitud = ?',
            [$cod_solicitud]
        );
    }
}