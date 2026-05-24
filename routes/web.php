<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categorias', \App\Http\Controllers\Admin\CategoriaController::class);
    Route::resource('productos', \App\Http\Controllers\Admin\ProductoController::class);
});
Route::get('/', [App\Http\Controllers\TiendaController::class, 'index'])->name('inicio');

Route::get('/catalogo', [App\Http\Controllers\TiendaController::class, 'catalogo'])->name('catalogo');

Route::get('/producto/{producto}', [App\Http\Controllers\TiendaController::class, 'show'])->name('producto');

require __DIR__.'/auth.php';
