<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{
    public function agregar(Request $request)
    {
        $carrito = session()->get('carrito', []);
        $id      = $request->producto_id;
        $talla   = $request->talla;
        $clave   = $id . '_' . $talla;

        if (isset($carrito[$clave])) {
            $carrito[$clave]['cantidad']++;
        } else {
            $producto        = Producto::findOrFail($id);
            $carrito[$clave] = [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => $producto->precio,
                'imagen'   => $producto->imagen,
                'talla'    => $talla,
                'cantidad' => 1,
            ];
        }

        session()->put('carrito', $carrito);
        return redirect()->route('carrito.index');
    }

    public function index()
    {
        $carrito = session()->get('carrito', []);
        $total   = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        return view('tienda.carrito', compact('carrito', 'total'));
    }

    public function checkout()
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.index');
        }

        return view('tienda.carrito_checkout', compact('carrito'));
    }

    public function confirmar(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'celular'   => 'required|string|max:20',
            'correo'    => 'required|email|max:255',
            'direccion' => 'required|string|max:500',
            'ciudad'    => 'required|string|max:100',
        ]);

        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.index');
        }

        $mensaje = "Hola! Quiero hacer un pedido:\n\n";
        $total   = 0;

        foreach ($carrito as $item) {
            $subtotal  = $item['precio'] * $item['cantidad'];
            $total    += $subtotal;
            $mensaje  .= "- {$item['nombre']}";
            if ($item['talla']) $mensaje .= " talla {$item['talla']}";
            $mensaje  .= " x{$item['cantidad']} — $" . number_format($subtotal, 0, ',', '.') . "\n";
        }

        $mensaje .= "\nTOTAL: $" . number_format($total, 0, ',', '.');
        $mensaje .= "\n\nDatos de entrega:";
        $mensaje .= "\nNombre: {$request->nombre}";
        $mensaje .= "\nCelular: {$request->celular}";
        $mensaje .= "\nDirección: {$request->direccion}, {$request->ciudad}";

        session()->forget('carrito');

        $telefono = "573044229882";
        $url      = "https://wa.me/{$telefono}?text=" . urlencode($mensaje);

        return redirect($url);
    }

    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$id]);
        session()->put('carrito', $carrito);
        return redirect()->route('carrito.index');
    }

    public function vaciar()
    {
        session()->forget('carrito');
        return redirect()->route('carrito.index');
    }
}