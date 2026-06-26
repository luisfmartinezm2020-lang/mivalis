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

    public function confirmar(Pedido $pedido)
    {
        $pedido->update(['estado' => 'confirmado']);
        return back()->with('success', 'Pedido #' . $pedido->id . ' confirmado.');
    }

    public function entregar(Pedido $pedido)
    {
        $pedido->update(['estado' => 'entregado']);
        return back()->with('success', 'Pedido #' . $pedido->id . ' marcado como entregado.');
    }
}
