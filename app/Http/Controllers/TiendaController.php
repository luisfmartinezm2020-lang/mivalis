<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function index()
{
    $categorias = \App\Models\Categoria::all();
    $productos = \App\Models\Producto::with('categoria')->latest()->take(8)->get();
    return view('tienda.index', compact('categorias', 'productos'));
}

public function catalogo()
{
    $categorias = \App\Models\Categoria::all();
    $productos = \App\Models\Producto::with('categoria')->latest()->get();
    return view('tienda.catalogo', compact('categorias', 'productos'));
}

public function show($id)
{
    $producto = \App\Models\Producto::with('categoria')->findOrFail($id);
    return view('tienda.show', compact('producto'));
}
}
