<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    protected $table = 'e_usuario';

    protected $primaryKey = 'cod_usuario';

    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'e_mail',
        'carnet',
        'paterno',
        'materno',
        'nombre1',
        'nombre2',
        'area',
        'cod_acceso',
        'clave',
        'fecha_nac',
        'activo',
        'cod_dis',
        'cod_dep',
        'telefono',
    ];

    public static function listar()
    {
        return DB::select('EXEC sp_usuario_listar');
    }

    public static function obtener($cod_usuario)
    {
        $resultado = DB::select(
            'EXEC sp_usuario_obtener @cod_usuario = ?',
            [$cod_usuario]
        );

        return !empty($resultado) ? $resultado[0] : null;
    }

    public static function insertar(array $datos)
    {
        return DB::select(
            'EXEC sp_usuario_insertar
                @usuario = ?,
                @e_mail = ?,
                @carnet = ?,
                @paterno = ?,
                @materno = ?,
                @nombre1 = ?,
                @nombre2 = ?,
                @area = ?,
                @cod_acceso = ?,
                @clave = ?,
                @fecha_nac = ?,
                @cod_dis = ?,
                @cod_dep = ?,
                @telefono = ?',
            [
                $datos['usuario'],
                $datos['e_mail'],
                $datos['carnet'],
                $datos['paterno'],
                $datos['materno'],
                $datos['nombre1'],
                $datos['nombre2'],
                $datos['area'],
                $datos['cod_acceso'],
                $datos['clave'],
                $datos['fecha_nac'],
                $datos['cod_dis'],
                $datos['cod_dep'],
                $datos['telefono'],
            ]
        );
    }

    public static function actualizar($cod_usuario, array $datos)
{
    return DB::statement(
        'EXEC sp_usuario_actualizar
            @cod_usuario = ?,
            @usuario = ?,
            @e_mail = ?,
            @carnet = ?,
            @paterno = ?,
            @materno = ?,
            @nombre1 = ?,
            @nombre2 = ?,
            @area = ?,
            @cod_acceso = ?,
            @clave = ?,
            @fecha_nac = ?,
            @activo = ?,
            @cod_dis = ?,
            @cod_dep = ?,
            @telefono = ?',
        [
            $cod_usuario,
            $datos['usuario'],
            $datos['e_mail'],
            $datos['carnet'],
            $datos['paterno'],
            $datos['materno'],
            $datos['nombre1'],
            $datos['nombre2'],
            $datos['area'],
            $datos['cod_acceso'],
            $datos['clave'],
            $datos['fecha_nac'],
            $datos['activo'],
            $datos['cod_dis'],
            $datos['cod_dep'],
            $datos['telefono'],
        ]
    );
}

    public static function eliminar($cod_usuario)
    {
        return DB::statement(
            'EXEC sp_usuario_eliminar @cod_usuario = ?',
            [$cod_usuario]
        );
    }
}