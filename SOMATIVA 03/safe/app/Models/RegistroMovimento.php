<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroMovimento extends Model
{
    use HasFactory;

    protected $table = 'registro_movimentos';

    protected $fillable = [
        'aluno_id',
        'turma_id',
        'registrado_por',
        'tipo',
        'horario',
        'faltas_aulas',
        'motivo',
        'observacao',
        'tem_empresa',
        'empresa_nome',
        'menor_de_idade',
        'responsavel_autorizado',
        'responsavel_presente_nome',
        'confirmado_portaria',
        'confirmado_por',
        'confirmado_at',
        'status',
        'notificacao_email_enviada',
        'notificacao_whatsapp_enviada',
        'notificacao_enviada_at',
    ];

    protected $casts = [
        'faltas_aulas'              => 'array',
        'tem_empresa'               => 'boolean',
        'menor_de_idade'            => 'boolean',
        'responsavel_autorizado'    => 'boolean',
        'confirmado_portaria'       => 'boolean',
        'notificacao_email_enviada' => 'boolean',
        'notificacao_whatsapp_enviada' => 'boolean',
        'horario'                   => 'datetime',
        'confirmado_at'             => 'datetime',
        'notificacao_enviada_at'    => 'datetime',
    ];

    // Relacionamentos
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function confirmadoPor()
    {
        return $this->belongsTo(User::class, 'confirmado_por');
    }

    // Accessors
    public function getTotalFaltasAttribute(): int
    {
        return count($this->faltas_aulas ?? []);
    }

    public function getFaltasFormatadaAttribute(): string
    {
        if (empty($this->faltas_aulas)) {
            return 'Sem faltas';
        }
        $aulas = array_map(fn($n) => "{$n}ª aula", $this->faltas_aulas);
        return implode(', ', $aulas);
    }

    public function getDiaSemanaAttribute(): string
    {
        $dias = [
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
        ];
        return $dias[$this->horario->dayOfWeekIso] ?? '—';
    }

    // Scopes
    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'entrada');
    }

    public function scopeSaidas($query)
    {
        return $query->where('tipo', 'saida');
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeHoje($query)
    {
        return $query->whereDate('horario', today());
    }

    public function scopeDaTurma($query, int $turmaId)
    {
        return $query->where('turma_id', $turmaId);
    }
}