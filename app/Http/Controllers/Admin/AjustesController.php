<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AjustesController extends Controller
{
    public function index()
    {
        $config = Configuracion::all()->pluck('valor', 'clave');
        return view('admin.ajustes.index', compact('config'));
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'nombre_tienda' => 'required|string|max:100',
            'whatsapp'      => 'required|string|max:20',
            'instagram'     => 'nullable|string|max:100',
            'tiktok'        => 'nullable|string|max:100',
            'banner_texto'  => 'nullable|string|max:255',
        ]);

        Configuracion::set('nombre_tienda', $request->nombre_tienda);
        Configuracion::set('whatsapp',      $request->whatsapp);
        Configuracion::set('instagram',     $request->instagram ?? '');
        Configuracion::set('tiktok',        $request->tiktok ?? '');
        Configuracion::set('banner_texto',  $request->banner_texto ?? '');
        Configuracion::set('banner_activo', $request->has('banner_activo') ? '1' : '0');

        // Logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('configuracion', 'public');
            Configuracion::set('logo', $path);
        }

        return back()->with('success', 'Ajustes guardados correctamente.');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password_nuevo'  => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->password_actual, Auth::user()->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password_nuevo),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}