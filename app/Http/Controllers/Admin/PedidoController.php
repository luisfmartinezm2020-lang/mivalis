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

    public function confirmar($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->update(['estado' => 'confirmado']);
        return back()->with('success', 'Pedido confirmado correctamente.');
    }

    public function cancelar($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->update(['estado' => 'cancelado']);
        return back()->with('success', 'Pedido cancelado.');
    }
}