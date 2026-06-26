<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Talla;

class CheckoutController extends Controller
{
    public function create($producto, Request $request)
    {
        $producto = Producto::with('tallas')->findOrFail($producto);
        $talla    = $request->query('talla');
        return view('checkout.create', compact('producto', 'talla'));
    }

    public function store(Request $request, $producto)
    {
        $request->validate([
            'talla'     => 'nullable|string|max:10',
            'cantidad'  => 'required|integer|min:1',
            'nombre'    => 'required|string|max:255',
            'celular'   => 'required|string|max:20',
            'correo'    => 'required|email|max:255',
            'direccion' => 'required|string|max:500',
            'ciudad'    => 'required|string|max:100',
        ]);

        $producto = Producto::findOrFail($producto);

        // Validar stock si tiene talla
        if ($request->talla) {
            $talla = Talla::where('producto_id', $producto->id)
                ->where('talla', $request->talla)
                ->first();
            if ($talla && $request->cantidad > $talla->stock) {
                return back()->with('error', "Solo quedan {$talla->stock} unidades en talla {$request->talla}.");
            }
        }

        $total = $producto->precio * $request->cantidad;

        $pedido = Pedido::create([
            'nombre'    => $request->nombre,
            'celular'   => $request->celular,
            'correo'    => $request->correo,
            'direccion' => $request->direccion,
            'ciudad'    => $request->ciudad,
            'estado'    => 'pendiente',
            'total'     => $total,
        ]);

        $pedido->productos()->attach($producto->id, [
            'talla'           => $request->talla,
            'cantidad'        => $request->cantidad,
            'precio_unitario' => $producto->precio,
        ]);

        // Descontar stock de la talla
        if ($request->talla) {
            Talla::where('producto_id', $producto->id)
                ->where('talla', $request->talla)
                ->decrement('stock', $request->cantidad);
        }

        $mensaje  = "Hola! Quiero {$producto->tipo}:\n";
        $mensaje .= "- {$producto->nombre}";
        $mensaje .= $request->talla ? " talla {$request->talla}" : "";
        $mensaje .= " x{$request->cantidad} - $" . number_format($total, 0, ',', '.');
        $mensaje .= "\n\nDatos de entrega:";
        $mensaje .= "\nNombre: {$request->nombre}";
        $mensaje .= "\nCelular: {$request->celular}";
        $mensaje .= "\nDirección: {$request->direccion}, {$request->ciudad}";

        $telefono = "573044229882";
        $url      = "https://wa.me/{$telefono}?text=" . urlencode($mensaje);
        return redirect($url);
    }
}
