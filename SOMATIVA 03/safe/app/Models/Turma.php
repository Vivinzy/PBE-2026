<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'curso',
        'periodo',
        'turno',
        'horario_inicio',
        'horario_fim',
        'total_aulas_dia',
        'dias_semana',
        'professor_id',
        'ativa',
    ];

    protected $casts = [
        'dias_semana' => 'array',
        'ativa'       => 'boolean',
    ];

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }

    public function registroMovimentos()
    {
        return $this->hasMany(RegistroMovimento::class);
    }

    /**
     * Verifica se a turma tem aula hoje baseado nos dias_semana configurados.
     * dias_semana usa ISO: 1=seg, 2=ter, 3=qua, 4=qui, 5=sex, 6=sab
     */
    public function temAulaHoje(): bool
    {
        $hoje = now()->dayOfWeekIso; // 1=seg...7=dom
        return in_array($hoje, $this->dias_semana ?? []);
    }

    /**
     * Calcula qual aula está acontecendo agora ou qual já passou.
     * Divide o período da aula em blocos iguais (total_aulas_dia).
     */
    public function aulaAtual(): ?int
    {
        if (! $this->temAulaHoje()) {
            return null;
        }

        $inicio = strtotime($this->horario_inicio);
        $fim    = strtotime($this->horario_fim);
        $agora  = time();

        if ($agora < $inicio || $agora > $fim) {
            return null;
        }

        $duracaoTotal = ($fim - $inicio) / $this->total_aulas_dia;
        $aula = (int) ceil(($agora - $inicio) / $duracaoTotal);

        return min($aula, $this->total_aulas_dia);
    }

    /**
     * Retorna o número da aula em que o aluno irá faltar
     * baseado no horário de entrada.
     */
    public function calcularFaltasPorEntrada(string $horarioEntrada): array
    {
        $inicio  = strtotime($this->horario_inicio);
        $entrada = strtotime($horarioEntrada);
        $fim     = strtotime($this->horario_fim);

        if ($entrada <= $inicio) {
            return [];
        }

        $duracaoAula = ($fim - $inicio) / $this->total_aulas_dia;
        $aulaEntrada = (int) ceil(($entrada - $inicio) / $duracaoAula);
        $faltas      = [];

        for ($i = 1; $i < $aulaEntrada; $i++) {
            $faltas[] = $i;
        }

        return $faltas;
    }
}