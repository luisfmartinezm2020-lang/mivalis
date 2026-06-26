<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Talla;
use App\Services\TelegramService;

class CarritoController extends Controller
{
    public function agregar(Request $request)
    {
        $carrito  = session()->get('carrito', []);
        $id       = $request->producto_id;
        $tallaStr = $request->talla;
        $clave    = $id . '_' . $tallaStr;
        $cantidad = $request->cantidad ?? 1;

        $producto = Producto::findOrFail($id);

        if ($tallaStr) {
            $talla = Talla::where('producto_id', $id)->where('talla', $tallaStr)->first();
            if ($talla) {
                $enCarrito = isset($carrito[$clave]) ? $carrito[$clave]['cantidad'] : 0;
                if (($enCarrito + $cantidad) > $talla->stock) {
                    $msg = $talla->stock > 0
                        ? "Solo quedan {$talla->stock} unidades disponibles en talla {$tallaStr}."
                        : "La talla {$tallaStr} no tiene stock disponible.";
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => $msg], 422);
                    }
                    return back()->with('error', $msg);
                }
            }
        }

        if (isset($carrito[$clave])) {
            $carrito[$clave]['cantidad'] += $cantidad;
        } else {
            $carrito[$clave] = [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => $producto->precio,
                'imagen'   => $producto->imagen,
                'talla'    => $tallaStr,
                'cantidad' => $cantidad,
            ];
        }

        session()->put('carrito', $carrito);

        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'carrito'  => array_values($carrito),
                'total'    => $total,
                'cantidad' => count($carrito),
            ]);
        }

        return redirect()->route('carrito.index');
    }

    public function eliminarAjax($clave)
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$clave]);
        session()->put('carrito', $carrito);

        $total = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));

        return response()->json([
            'success'  => true,
            'carrito'  => array_values($carrito),
            'total'    => $total,
            'cantidad' => count($carrito),
        ]);
    }

    public function actualizarAjax(Request $request)
    {
        $carrito = session()->get('carrito', []);
        $clave   = $request->clave;
        $delta   = $request->delta;

        if (isset($carrito[$clave])) {
            $item          = $carrito[$clave];
            $nuevaCantidad = $item['cantidad'] + $delta;

            if ($delta > 0 && $item['talla']) {
                $talla = Talla::where('producto_id', $item['id'])->where('talla', $item['talla'])->first();
                if ($talla && $nuevaCantidad > $talla->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock máximo disponible: {$talla->stock}",
                    ], 422);
                }
            }

            if ($nuevaCantidad <= 0) {
                unset($carrito[$clave]);
            } else {
                $carrito[$clave]['cantidad'] = $nuevaCantidad;
            }
        }

        session()->put('carrito', $carrito);

        $total = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));

        return response()->json([
            'success'  => true,
            'carrito'  => array_values($carrito),
            'total'    => $total,
            'cantidad' => count($carrito),
        ]);
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
        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        return view('tienda.carrito_checkout', compact('carrito', 'total'));
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

        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));

        $pedido = Pedido::create([
            'nombre'    => $request->nombre,
            'celular'   => $request->celular,
            'correo'    => $request->correo,
            'direccion' => $request->direccion,
            'ciudad'    => $request->ciudad,
            'estado'    => 'pendiente',
            'total'     => $total,
        ]);

        $lineas = '';
        foreach ($carrito as $item) {
            $pedido->productos()->attach($item['id'], [
                'talla'           => $item['talla'],
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $item['precio'],
            ]);

            if ($item['talla']) {
                $talla = Talla::where('producto_id', $item['id'])
                    ->where('talla', $item['talla'])->first();
                if ($talla) {
                    $talla->decrement('stock', $item['cantidad']);
                    if ($talla->fresh()->stock <= 2) {
                        TelegramService::notificarStockCritico(
                            "⚠️ <b>Stock crítico</b>\n" .
                            "Producto: {$item['nombre']}\n" .
                            "Talla: {$item['talla']}\n" .
                            "Stock restante: {$talla->fresh()->stock} unidades"
                        );
                    }
                }
            }

            $subtotal = $item['precio'] * $item['cantidad'];
            $lineas  .= "• {$item['nombre']}";
            if ($item['talla']) $lineas .= " talla {$item['talla']}";
            $lineas  .= " x{$item['cantidad']} — $" . number_format($subtotal, 0, ',', '.') . "\n";
        }

        TelegramService::notificarPedido($pedido->id,
            "🛍️ <b>Nuevo pedido #{$pedido->id}</b>\n\n" .
            $lineas .
            "\n<b>Total: $" . number_format($total, 0, ',', '.') . "</b>\n\n" .
            "👤 {$request->nombre}\n" .
            "📱 {$request->celular}\n" .
            "📧 {$request->correo}\n" .
            "📍 {$request->direccion}, {$request->ciudad}"
        );

        $mensaje  = "Hola! Quiero hacer un pedido:\n\n";
        foreach ($carrito as $item) {
            $subtotal  = $item['precio'] * $item['cantidad'];
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
