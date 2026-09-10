<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos'; // Definir el mombre de la tabla
    protected $fillable = ['nombre', 'descripcion', 'precio'];
    // Campos permitidos para inserción masiva
}
