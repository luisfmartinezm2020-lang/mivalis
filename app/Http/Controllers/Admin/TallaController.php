<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Talla;
use Illuminate\Http\Request;

class TallaController extends Controller
{
    public function store(Request $request, $productoId)
    {
        $request->validate([
            'talla' => 'required|string|max:10',
            'stock' => 'required|integer|min:0',
        ]);

        Talla::create([
            'producto_id' => $productoId,
            'talla' => $request->talla,
            'stock' => $request->stock,
        ]);

        return redirect()->back()->with('success', 'Talla agregada exitosamente.');
    }

    public function destroy($id)
    {
        $talla = Talla::findOrFail($id);
        $talla->delete();

        return redirect()->back()->with('success', 'Talla eliminada exitosamente.');
    }
}