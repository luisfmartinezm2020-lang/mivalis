<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ====== TIENDA PÚBLICA ======
Route::get('/', [App\Http\Controllers\TiendaController::class, 'index'])->name('inicio');
Route::get('/catalogo', [App\Http\Controllers\TiendaController::class, 'catalogo'])->name('catalogo');
Route::get('/producto/{producto}', [App\Http\Controllers\TiendaController::class, 'show'])->name('producto');

// ====== CARRITO (público) ======
Route::post('/carrito/agregar', [App\Http\Controllers\CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::get('/carrito', [App\Http\Controllers\CarritoController::class, 'index'])->name('carrito.index');
Route::get('/carrito/checkout', [App\Http\Controllers\CarritoController::class, 'checkout'])->name('carrito.checkout');
Route::post('/carrito/confirmar', [App\Http\Controllers\CarritoController::class, 'confirmar'])->name('carrito.confirmar');
Route::delete('/carrito/eliminar-ajax/{clave}', [App\Http\Controllers\CarritoController::class, 'eliminarAjax'])->name('carrito.eliminarAjax');
Route::post('/carrito/actualizar-ajax', [App\Http\Controllers\CarritoController::class, 'actualizarAjax'])->name('carrito.actualizarAjax');
Route::delete('/carrito/{id}', [App\Http\Controllers\CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::delete('/carrito', [App\Http\Controllers\CarritoController::class, 'vaciar'])->name('carrito.vaciar');

// ====== CHECKOUT PRODUCTO INDIVIDUAL (público) ======
Route::get('/checkout/{producto}', [\App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{producto}', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

// ====== DASHBOARD ======
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.categorias.index');
    }
    return redirect()->route('inicio');
})->middleware(['auth', 'verified'])->name('dashboard');
// ====== PERFIL ======
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ====== ADMIN ======
Route::middleware(['auth', 'es.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categorias', \App\Http\Controllers\Admin\CategoriaController::class);
    Route::resource('productos', \App\Http\Controllers\Admin\ProductoController::class);
    Route::post('productos/{producto}/tallas', [\App\Http\Controllers\Admin\TallaController::class, 'store'])->name('tallas.store');
    Route::delete('tallas/{talla}', [\App\Http\Controllers\Admin\TallaController::class, 'destroy'])->name('tallas.destroy');
    Route::get('pedidos', [\App\Http\Controllers\Admin\PedidoController::class, 'index'])->name('pedidos.index');
    Route::post('pedidos/{pedido}/confirmar', [\App\Http\Controllers\Admin\PedidoController::class, 'confirmar'])->name('pedidos.confirmar');
});

require __DIR__.'/auth.php';