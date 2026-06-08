<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
   public function index()
{
    $categorias = \App\Models\Categoria::all();
    $productos = \App\Models\Producto::with('categoria')
                    ->where('destacado', true)
                    ->latest()
                    ->take(8)
                    ->get();
    return view('tienda.index', compact('categorias', 'productos'));
}

public function catalogo(request $request)
{
    $categorias = \App\Models\Categoria::all();
    $query = \App\Models\Producto::with('categoria')->latest();

    if ($request->has('categoria')) {
        $query->where('categoria_id', $request->categoria);
    }

    $productos = $query->get();
    return view('tienda.catalogo', compact('categorias', 'productos'));
}

public function show($id)
{
    $producto = \App\Models\Producto::with(['categoria', 'tallas'])->findOrFail($id);
    return view('tienda.show', compact('producto'));
}
}
