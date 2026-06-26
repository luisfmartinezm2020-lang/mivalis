<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  // ← agrega esta línea

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $valores = [
            ['clave' => 'nombre_tienda', 'valor' => 'MiValis'],
            ['clave' => 'whatsapp',      'valor' => '573044229882'],
            ['clave' => 'instagram',     'valor' => ''],
            ['clave' => 'tiktok',        'valor' => ''],
            ['clave' => 'banner_activo', 'valor' => '0'],
            ['clave' => 'banner_texto',  'valor' => ''],
            ['clave' => 'logo',          'valor' => ''],
        ];

        foreach ($valores as $item) {
            DB::table('configuracion')->updateOrInsert(
                ['clave' => $item['clave']],
                ['valor' => $item['valor'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}