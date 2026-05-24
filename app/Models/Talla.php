<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    protected $fillable =[
        'producto_id',
        'talla',
        'stock'
    ];
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }   
}
