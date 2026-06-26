<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Services\TelegramService;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // Solo procesamos pulsaciones de botones
        if (!isset($data['callback_query'])) {
            return response()->json(['ok' => true]);
        }

        $callback   = $data['callback_query'];
        $callbackId = $callback['id'];
        $messageId  = $callback['message']['message_id'];
        $callData   = $callback['data']; // ej: "confirmar_5" o "denegar_5"

        [$accion, $pedidoId] = explode('_', $callData, 2);

        $pedido = Pedido::find($pedidoId);

        if (!$pedido) {
            TelegramService::responderCallback($callbackId, '❌ Pedido no encontrado.');
            return response()->json(['ok' => true]);
        }

        if ($pedido->estado !== 'pendiente') {
            TelegramService::responderCallback($callbackId, 'Este pedido ya fue procesado.');
            return response()->json(['ok' => true]);
        }

        if ($accion === 'confirmar') {
            $pedido->update(['estado' => 'confirmado']);
            TelegramService::responderCallback($callbackId, '✅ Pedido confirmado');
            TelegramService::editarMensaje($messageId,
                "✅ <b>Pedido #{$pedido->id} CONFIRMADO</b>\n\n" .
                "👤 {$pedido->nombre}\n" .
                "📱 {$pedido->celular}\n" .
                "📍 {$pedido->direccion}, {$pedido->ciudad}\n" .
                "<b>Total: $" . number_format($pedido->total, 0, ',', '.') . "</b>"
            );
        } elseif ($accion === 'denegar') {
            $pedido->update(['estado' => 'cancelado']);
            TelegramService::responderCallback($callbackId, '❌ Pedido denegado');
            TelegramService::editarMensaje($messageId,
                "❌ <b>Pedido #{$pedido->id} DENEGADO</b>\n\n" .
                "👤 {$pedido->nombre}\n" .
                "📱 {$pedido->celular}\n" .
                "<b>Total: $" . number_format($pedido->total, 0, ',', '.') . "</b>"
            );
        }

        return response()->json(['ok' => true]);
    }
}
