<?php

namespace App\Notifications;

use App\Models\RegistroMovimento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MovimentoAlunoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public RegistroMovimento $registro
    ) {}

    /**
     * Canais: mail (capturado pelo Mailpit em dev) e o canal customizado de WhatsApp simulado.
     */
    public function via(object $notifiable): array
    {
        return ['mail', WhatsAppLogChannel::class];
    }

    /**
     * E-mail enviado ao responsável.
     * Em desenvolvimento, o Mailpit captura automaticamente (MAIL_MAILER=smtp, porta 1025).
     */
    public function toMail(object $notifiable): MailMessage
    {
        $aluno  = $this->registro->aluno;
        $tipo   = $this->registro->tipo === 'entrada' ? 'ENTRADA' : 'SAÍDA';
        $emoji  = $this->registro->tipo === 'entrada' ? '✅' : '🚪';
        $horario = $this->registro->horario->format('H:i');
        $dia    = $this->registro->dia_semana;

        $faltas = $this->registro->total_faltas > 0
            ? "Faltas registradas: {$this->registro->faltas_formatada}"
            : 'Sem faltas neste movimento.';

        return (new MailMessage)
            ->subject("{$emoji} SAFE — {$tipo} registrada: {$aluno->nome}")
            ->greeting("Olá, {$notifiable->name}!")
            ->line("{$emoji} **{$tipo}** do(a) aluno(a) **{$aluno->nome}** foi registrada no sistema SAFE.")
            ->line("📅 **{$dia}** às **{$horario}**")
            ->line("🏫 Turma: **{$this->registro->turma->nome}**")
            ->line("📋 {$faltas}")
            ->when($this->registro->motivo, function ($mail) {
                return $mail->line("📝 Motivo: {$this->registro->motivo}");
            })
            ->when($this->registro->tem_empresa, function ($mail) {
                return $mail->line("🏢 Empresa: {$this->registro->empresa_nome}");
            })
            ->line('---')
            ->line('Esta é uma mensagem automática do sistema SAFE.')
            ->salutation('Atenciosamente, Sistema SAFE');
    }

    /**
     * Dados para o canal de WhatsApp simulado.
     */
    public function toWhatsApp(object $notifiable): array
    {
        $aluno   = $this->registro->aluno;
        $tipo    = $this->registro->tipo === 'entrada' ? 'ENTRADA' : 'SAÍDA';
        $horario = $this->registro->horario->format('H:i');

        return [
            'para'      => $notifiable->responsavel_whatsapp ?? $notifiable->phone ?? 'sem-numero',
            'mensagem'  => "*SAFE — {$tipo}* registrada\n"
                         . "Aluno(a): *{$aluno->nome}*\n"
                         . "Horário: *{$horario}*\n"
                         . "Turma: {$this->registro->turma->nome}\n"
                         . ($this->registro->total_faltas > 0
                             ? "Faltas: {$this->registro->faltas_formatada}"
                             : 'Sem faltas'),
            'registro'  => $this->registro->id,
        ];
    }
}