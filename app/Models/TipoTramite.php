<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTramite extends Model
{
    protected $table = 'b_tipos';

    protected $primaryKey = 'cod_tipo';

    public $timestamps = false;

    protected $fillable = [
        'nombre_tramite',
        'requisitos',
        'descripcion',
        'tiempo_estimado',
        'habilitado',
    ];
}