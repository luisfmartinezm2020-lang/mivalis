<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.categorias.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen' => 'nullable|image|max:2048',
        ]);

         $data = ($request->only('nombre'));

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('categorias', 'public');
        }
            
        \App\Models\Categoria::create($data);
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada exitosamente.');
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
        $categoria = \App\Models\Categoria::findOrFail($id);

        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $categoria = \App\Models\Categoria::findOrFail($id);
        $categoria->update($request->only('nombre'));

        if ($request->hasFile('imagen')) {
            $categoria->update(['imagen' => $request->file('imagen')->store('categorias', 'public')]);
        }

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = \App\Models\Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada exitosamente.');
    }
}
