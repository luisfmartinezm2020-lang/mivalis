<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->enum('tipo', ['venta', 'alquiler'])->default('venta')->after('estado');
            $table->date('fecha_entrega')->nullable()->after('tipo');
            $table->date('fecha_devolucion')->nullable()->after('fecha_entrega');
        });
    }

    public function down(): void {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'fecha_entrega', 'fecha_devolucion']);
        });
    }
};