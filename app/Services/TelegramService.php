<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public static function notificarPedido(array $datos): void
    {
        $token  = config('services.telegram.token');
        $chatId = config('services.telegram.chat_id');

        if (!$token || !$chatId) return;

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id'    => $chatId,
            'parse_mode' => 'HTML',
            'text'       => $datos['mensaje'],
        ]);
    }
}
