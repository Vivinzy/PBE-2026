<?php

namespace App\Observers;

use App\Models\RegistroMovimento;
use App\Notifications\MovimentoAlunoNotification;
use Illuminate\Support\Facades\Log;

class RegistroMovimentoObserver
{
    /**
     * Disparado imediatamente após criar um novo registro.
     * Envia e-mail (Mailpit) + WhatsApp simulado ao responsável.
     */
    public function created(RegistroMovimento $registro): void
    {
        $aluno = $registro->aluno;
        $tipo  = strtoupper($registro->tipo);

        // Log geral do sistema (desafio: log::info)
        Log::info("[SAFE] Novo registro de {$tipo}", [
            'registro_id' => $registro->id,
            'aluno'       => $aluno->nome,
            'ra'          => $aluno->ra,
            'turma'       => $registro->turma->nome,
            'horario'     => $registro->horario->format('d/m/Y H:i'),
            'faltas'      => $registro->faltas_formatada,
            'tipo'        => $registro->tipo,
            'status'      => $registro->status,
            'registrado_por' => $registro->registradoPor->name,
        ]);

        // Dispara notificação ao responsável (se menor e tiver e-mail/whatsapp)
        if ($aluno->responsavel_email) {
            // Cria objeto anônimo como notifiable com dados do responsável
            $responsavel = new class($aluno) {
                public string $name;
                public string $email;
                public ?string $responsavel_whatsapp;

                public function __construct(\App\Models\Aluno $aluno)
                {
                    $this->name                   = $aluno->responsavel_nome ?? 'Responsável';
                    $this->email                  = $aluno->responsavel_email;
                    $this->responsavel_whatsapp   = $aluno->responsavel_whatsapp;
                }

                public function routeNotificationFor(string $driver, $notification = null): mixed
                {
                    return match ($driver) {
                        'mail'  => $this->email,
                        default => null,
                    };
                }

                public function notify($notification): void
                {
                    app(\Illuminate\Notifications\ChannelManager::class)
                        ->send($this, $notification);
                }
            };

            $responsavel->notify(new MovimentoAlunoNotification($registro));

            // Marca e-mail como enviado
            $registro->updateQuietly(['notificacao_email_enviada' => true]);
        }

        // Se não tem responsável mas tem aluno com e-mail, notifica o próprio aluno
        if (! $aluno->responsavel_email && $aluno->email) {
            Log::info("[SAFE] Responsável sem e-mail, aluno notificado diretamente", [
                'aluno_email' => $aluno->email,
            ]);
        }
    }

    /**
     * Quando a portaria confirma a saída.
     */
    public function updated(RegistroMovimento $registro): void
    {
        if ($registro->wasChanged('confirmado_portaria') && $registro->confirmado_portaria) {
            Log::info("[SAFE] Saída CONFIRMADA pela portaria", [
                'registro_id'    => $registro->id,
                'aluno'          => $registro->aluno->nome,
                'confirmado_por' => $registro->confirmadoPor?->name,
                'confirmado_at'  => now()->format('d/m/Y H:i:s'),
            ]);
        }

        if ($registro->wasChanged('status') && $registro->status === 'negado') {
            Log::warning("[SAFE] Registro NEGADO pela portaria", [
                'registro_id' => $registro->id,
                'aluno'       => $registro->aluno->nome,
            ]);
        }
    }
}