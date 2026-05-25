<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Canal de simulação de WhatsApp para o SAFE.
 *
 * Em produção, substitua o Log::info() pela integração real
 * (ex: Twilio, Z-API, Evolution API, WPPConnect).
 *
 * Em desenvolvimento, as mensagens aparecem em:
 *   storage/logs/laravel.log  com prefixo [SAFE-WhatsApp]
 */
class WhatsAppLogChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $dados = $notification->toWhatsApp($notifiable);

        // Simula o envio registrando no log (log::info conforme desafio)
        Log::info('[SAFE-WhatsApp] Mensagem simulada', [
            'para'       => $dados['para'],
            'registro_id'=> $dados['registro'] ?? null,
            'conteudo'   => $dados['mensagem'],
            'timestamp'  => now()->toDateTimeString(),
            'canal'      => 'whatsapp_simulado',
            'status'     => 'ENVIADO (simulação)',
        ]);

        // Atualiza o flag no banco para rastreabilidade
        if (isset($dados['registro'])) {
            \App\Models\RegistroMovimento::where('id', $dados['registro'])->update([
                'notificacao_whatsapp_enviada' => true,
                'notificacao_enviada_at'       => now(),
            ]);
        }
    }
}