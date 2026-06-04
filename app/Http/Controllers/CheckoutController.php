<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // Checkout para un solo producto (botón COMPRAR)
    public function create($producto, Request $request)
    {
        $producto = \App\Models\Producto::with('tallas')->findOrFail($producto);
        $talla = $request->query('talla');
        return view('checkout.create', compact('producto', 'talla'));
    }

    // Store para un solo producto
    public function store(Request $request, $producto)
    {
        $request->validate([
            'talla' => 'nullable|string|max:10',
            'cantidad' => 'required|integer|min:1',
            'nombre' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'correo' => 'required|email|max:255',
            'direccion' => 'required|string|max:500',
            'ciudad' => 'required|string|max:100',
        ]);

        $producto = \App\Models\Producto::findOrFail($producto);
        $total = $producto->precio * $request->cantidad;

        $pedido = \App\Models\Pedido::create([
            'nombre' => $request->nombre,
            'celular' => $request->celular,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'estado' => 'pendiente',
            'total' => $total,
        ]);

        $pedido->productos()->attach($producto->id, [
            'talla' => $request->talla,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $producto->precio,
        ]);

        $mensaje = "Hola! Quiero {$producto->tipo}:\n";
        $mensaje .= "- {$producto->nombre}";
        $mensaje .= $request->talla ? " talla {$request->talla}" : "";
        $mensaje .= " x{$request->cantidad} - $" . number_format($total, 0, ',', '.');
        $mensaje .= "\n\nDatos de entrega:";
        $mensaje .= "\nNombre: {$request->nombre}";
        $mensaje .= "\nCelular: {$request->celular}";
        $mensaje .= "\nDirección: {$request->direccion}, {$request->ciudad}";

        $telefono = "573044229882";
        $url = "https://wa.me/{$telefono}?text=" . urlencode($mensaje);
        return redirect($url);
    }

    // Checkout para el carrito completo
    public function createFromCarrito(Request $request)
    {
        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }
        return view('checkout.carrito', compact('carrito'));
    }

    // Store para el carrito completo
    public function storeFromCarrito(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'correo' => 'required|email|max:255',
            'direccion' => 'required|string|max:500',
            'ciudad' => 'required|string|max:100',
        ]);

        $carrito = session()->get('carrito', []);
        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));

        $mensaje = "Hola! Quiero hacer un pedido:\n\n";
        foreach ($carrito as $item) {
            $mensaje .= "- {$item['nombre']} talla {$item['talla']} x{$item['cantidad']} - $" . number_format($item['precio'] * $item['cantidad'], 0, ',', '.') . "\n";
        }
        $mensaje .= "\nTOTAL: $" . number_format($total, 0, ',', '.');
        $mensaje .= "\n\nDatos de entrega:";
        $mensaje .= "\nNombre: {$request->nombre}";
        $mensaje .= "\nCelular: {$request->celular}";
        $mensaje .= "\nDirección: {$request->direccion}, {$request->ciudad}";

        session()->forget('carrito');

        $telefono = "573044229882";
        $url = "https://wa.me/{$telefono}?text=" . urlencode($mensaje);
        return redirect($url);
    }
}