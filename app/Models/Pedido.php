<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'nombre',
        'celular',
        'correo',
        'direccion',
        'ciudad',
        'estado',
        'total'
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_productos')
                    ->withPivot('talla', 'cantidad', 'precio_unitario')
                    ->withTimestamps();
    }
}
