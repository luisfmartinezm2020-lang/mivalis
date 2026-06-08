<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = \App\Models\Producto::with('categoria')->orderBy('nombre')->get();
        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nombre'       => 'required|string|max:255',
        'descripcion'  => 'nullable|string',
        'precio'       => 'required|numeric',
        'imagen'       => 'nullable|image|max:10240',
        'tipo'         => 'required|string|max:255',
        'genero'       => 'required|string|max:255',
        'categoria_id' => 'nullable|exists:categorias,id',
    ]);

    $data = $request->only('nombre', 'descripcion', 'precio', 'tipo', 'genero', 'categoria_id', 'destacado');
    $data['destacado'] = $request->has('destacado') ? 1 : 0;

    if ($request->hasFile('imagen')) {
        $data['imagen'] = $request->file('imagen')->store('productos', 'public');
    }

    $producto = \App\Models\Producto::create($data);

    return redirect()->route('admin.productos.edit', $producto)->with('success', 'Producto creado. Ahora puedes agregar tallas');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $producto = \App\Models\Producto::with('tallas')->findOrFail($id);
    $categorias = \App\Models\Categoria::orderBy('nombre')->get();
    return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'nombre'       => 'required|string|max:255',
        'descripcion'  => 'nullable|string',
        'precio'       => 'required|numeric',
        'imagen'       => 'nullable|image|max:10240',
        'tipo'         => 'required|string|max:255',
        'genero'       => 'required|string|max:255',
        'categoria_id' => 'nullable|exists:categorias,id',
    ]);

    $producto = \App\Models\Producto::findOrFail($id);

    $data = $request->only('nombre', 'descripcion', 'precio', 'tipo', 'genero', 'categoria_id', 'destacado');
    $data['destacado'] = $request->has('destacado') ? 1 : 0;

    if ($request->hasFile('imagen')) {
        $data['imagen'] = $request->file('imagen')->store('productos', 'public');
    }

    $producto->update($data);

    return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado exitosamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = \App\Models\Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
