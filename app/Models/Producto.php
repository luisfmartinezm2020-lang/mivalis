<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'tipo',
        'genero',
        'categoria_id',
        'destacado'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tallas()
    {
        return $this->hasMany(Talla::class);
    }
}
