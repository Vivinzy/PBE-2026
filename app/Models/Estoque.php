<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'produto_id',
        'nome_produto',
        'quantidade',
        'unidade_medida',
        'preco',
        'fornecedor',
        'data_entrada',
    ];

    public function produto()
    {
        return $this->belongsTo(\App\Models\Produto::class);
    }
}