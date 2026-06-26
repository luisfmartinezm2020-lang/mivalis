<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('productos')->latest()->get();
        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function cambiarEstado($id, $estado)
    {
        $estadosValidos = ['pendiente','confirmado','entregado','alquilado','devuelto','cancelado'];
        if (!in_array($estado, $estadosValidos)) abort(400);

        $pedido = Pedido::findOrFail($id);
        $pedido->update(['estado' => $estado]);
        return back()->with('success', 'Estado actualizado correctamente.');
    }
}