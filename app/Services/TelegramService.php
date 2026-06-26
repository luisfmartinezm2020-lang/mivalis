<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    private static function token(): string
    {
        return config('services.telegram.token');
    }

    private static function chatId(): string
    {
        return config('services.telegram.chat_id');
    }

    public static function notificarPedido(int $pedidoId, string $texto): void
    {
        $token  = self::token();
        $chatId = self::chatId();
        if (!$token || !$chatId) return;

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id'      => $chatId,
            'parse_mode'   => 'HTML',
            'text'         => $texto,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => '✅ Confirmar', 'callback_data' => "confirmar_{$pedidoId}"],
                    ['text' => '❌ Denegar',   'callback_data' => "denegar_{$pedidoId}"],
                ]],
            ]),
        ]);
    }

    public static function notificarStockCritico(string $texto): void
    {
        $token  = self::token();
        $chatId = self::chatId();
        if (!$token || !$chatId) return;

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id'    => $chatId,
            'parse_mode' => 'HTML',
            'text'       => $texto,
        ]);
    }

    public static function responderCallback(string $callbackQueryId, string $texto): void
    {
        Http::post("https://api.telegram.org/bot" . self::token() . "/answerCallbackQuery", [
            'callback_query_id' => $callbackQueryId,
            'text'              => $texto,
            'show_alert'        => false,
        ]);
    }

    public static function editarMensaje(int $messageId, string $texto): void
    {
        Http::post("https://api.telegram.org/bot" . self::token() . "/editMessageText", [
            'chat_id'      => self::chatId(),
            'message_id'   => $messageId,
            'parse_mode'   => 'HTML',
            'text'         => $texto,
            'reply_markup' => json_encode(['inline_keyboard' => []]),
        ]);
    }
}
