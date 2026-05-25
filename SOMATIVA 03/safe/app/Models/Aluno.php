<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'ra',
        'nome',
        'email',
        'data_nascimento',
        'telefone',
        'responsavel_nome',
        'responsavel_telefone',
        'responsavel_email',
        'responsavel_whatsapp',
        'tem_empresa',
        'empresa_nome',
        'turma_id',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'tem_empresa'     => 'boolean',
        'ativo'           => 'boolean',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function registroMovimentos()
    {
        return $this->hasMany(RegistroMovimento::class);
    }

    /**
     * Verifica se o aluno é menor de idade (< 18 anos).
     */
    public function isMenor(): bool
    {
        return $this->data_nascimento->age < 18;
    }

    /**
     * Retorna a string formatada da idade.
     */
    public function getIdadeAttribute(): string
    {
        return $this->data_nascimento->age . ' anos';
    }

    /**
     * Retorna as iniciais do nome para avatar.
     */
    public function getIniciaisAttribute(): string
    {
        $partes = explode(' ', trim($this->nome));
        $ini    = strtoupper(substr($partes[0], 0, 1));
        if (count($partes) > 1) {
            $ini .= strtoupper(substr(end($partes), 0, 1));
        }
        return $ini;
    }

    /**
     * Total de faltas registradas no mês atual.
     */
    public function totalFaltasMes(): int
    {
        return $this->registroMovimentos()
            ->whereMonth('horario', now()->month)
            ->whereYear('horario', now()->year)
            ->get()
            ->sum(fn($r) => count($r->faltas_aulas ?? []));
    }
}