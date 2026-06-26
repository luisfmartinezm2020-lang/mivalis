<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Talla;

class CarritoController extends Controller
{
    public function agregar(Request $request)
    {
        $carrito = session()->get('carrito', []);
        $id      = $request->producto_id;
        $talla   = $request->talla;
        $clave   = $id . '_' . $talla;

        $producto = Producto::findOrFail($id);

        if (isset($carrito[$clave])) {
            $carrito[$clave]['cantidad'] += $request->cantidad ?? 1;
        } else {
            $carrito[$clave] = [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => $producto->precio,
                'imagen'   => $producto->imagen,
                'talla'    => $talla,
                'cantidad' => $request->cantidad ?? 1,
                'tipo'     => $producto->tipo, // ← agrega esta línea
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
            $carrito[$clave]['cantidad'] += $delta;
            if ($carrito[$clave]['cantidad'] <= 0) {
                unset($carrito[$clave]);
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
}public function confirmar(Request $request)
{
    $request->validate([
        'nombre'          => 'required|string|max:255',
        'celular'         => 'required|string|max:20',
        'correo'          => 'required|email|max:255',
        'direccion'       => 'required|string|max:500',
        'ciudad'          => 'required|string|max:100',
        'fecha_entrega'   => 'required|date',
        'fecha_devolucion'=> 'nullable|date|after:fecha_entrega',
    ]);

    $carrito = session()->get('carrito', []);
    if (empty($carrito)) {
        return redirect()->route('carrito.index');
    }

    // Determinar tipo del pedido
    $tieneAlquiler = collect($carrito)->contains(fn($i) => $i['tipo'] === 'alquiler');
    $tieneVenta    = collect($carrito)->contains(fn($i) => $i['tipo'] === 'venta');
    $tipoPedido    = $tieneAlquiler && $tieneVenta ? 'mixto' : ($tieneAlquiler ? 'alquiler' : 'venta');

    $total = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));

    // Guardar pedido
    $pedido = \App\Models\Pedido::create([
        'nombre'          => $request->nombre,
        'celular'         => $request->celular,
        'correo'          => $request->correo,
        'direccion'       => $request->direccion,
        'ciudad'          => $request->ciudad,
        'estado'          => 'pendiente',
        'total'           => $total,
        'tipo'            => $tipoPedido,
        'fecha_entrega'   => $request->fecha_entrega,
        'fecha_devolucion'=> $request->fecha_devolucion,
    ]);

    // Guardar productos y bajar stock
    foreach ($carrito as $item) {
        $pedido->productos()->attach($item['id'], [
            'talla'           => $item['talla'],
            'cantidad'        => $item['cantidad'],
            'precio_unitario' => $item['precio'],
        ]);

        $talla = \App\Models\Talla::where('producto_id', $item['id'])
                                   ->where('talla', $item['talla'])
                                   ->first();
        if ($talla) {
            $nuevoStock = max(0, $talla->stock - $item['cantidad']);
            $talla->update(['stock' => $nuevoStock]);

            if ($nuevoStock <= 3) {
                \Illuminate\Support\Facades\Log::warning("STOCK BAJO: {$item['nombre']} talla {$item['talla']} — quedan {$nuevoStock} unidades.");
            }
        }
    }

    session()->forget('carrito');

    // Redirigir a página de confirmación
    return redirect()->route('pedido.confirmado', $pedido->id);
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