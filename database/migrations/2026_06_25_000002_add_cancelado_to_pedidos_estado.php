<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cambia el enum para incluir 'cancelado'
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('pendiente','confirmado','entregado','cancelado') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('pendiente','confirmado','entregado') DEFAULT 'pendiente'");
    }
};
