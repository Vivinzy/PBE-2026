<?php

namespace App\Filament\Pages;

use App\Models\RegistroMovimento;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PortariaPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Portaria';
    protected static ?string $title           = 'Controle de Portaria';
    protected static ?int    $navigationSort  = 10;
    protected static string  $view            = 'filament.pages.portaria';

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['portaria', 'admin', 'coordenacao']);
    }

    public function getSaidasPendentes()
    {
        return RegistroMovimento::with(['aluno', 'turma'])
            ->where('tipo', 'saida')
            ->where('status', 'pendente')
            ->whereDate('horario', today())
            ->orderBy('horario')
            ->get();
    }

    public function getSaidasConfirmadas()
    {
        return RegistroMovimento::with(['aluno', 'turma', 'confirmadoPor'])
            ->where('tipo', 'saida')
            ->where('status', 'confirmado')
            ->whereDate('horario', today())
            ->orderBy('confirmado_at', 'desc')
            ->take(10)
            ->get();
    }

    public function confirmarSaida(int $registroId, bool $responsavelPresente = false): void
    {
        $registro = RegistroMovimento::findOrFail($registroId);

        // Validação extra: menor sem responsável autorizado
        if ($registro->menor_de_idade && ! $registro->responsavel_autorizado && ! $responsavelPresente) {
            Notification::make()
                ->title('Atenção: aluno menor de idade')
                ->body('Confirme a presença do responsável antes de liberar.')
                ->warning()
                ->send();
            return;
        }

        $registro->update([
            'confirmado_portaria'       => true,
            'confirmado_por'            => Auth::id(),
            'confirmado_at'             => now(),
            'status'                    => 'confirmado',
            'responsavel_autorizado'    => $responsavelPresente || $registro->responsavel_autorizado,
        ]);

        Log::info('[SAFE-Portaria] Saída confirmada', [
            'registro_id'     => $registro->id,
            'aluno'           => $registro->aluno->nome,
            'confirmado_por'  => Auth::user()->name,
            'horario'         => now()->format('H:i:s'),
            'menor'           => $registro->menor_de_idade ? 'sim' : 'não',
            'responsavel_ok'  => $registro->responsavel_autorizado ? 'sim' : 'não',
        ]);

        Notification::make()
            ->title("Saída confirmada: {$registro->aluno->nome}")
            ->success()
            ->send();

        $this->redirect(static::getUrl());
    }

    public function negarSaida(int $registroId): void
    {
        $registro = RegistroMovimento::findOrFail($registroId);
        $registro->update(['status' => 'negado']);

        Log::warning('[SAFE-Portaria] Saída NEGADA', [
            'registro_id' => $registro->id,
            'aluno'       => $registro->aluno->nome,
            'negado_por'  => Auth::user()->name,
        ]);

        Notification::make()
            ->title("Saída negada: {$registro->aluno->nome}")
            ->danger()
            ->send();

        $this->redirect(static::getUrl());
    }
}