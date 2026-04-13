<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estoques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->string('nome_produto');
            $table->decimal('quantidade', 10, 2);
            $table->string('unidade_medida');
            $table->decimal('preco', 10, 2);
            $table->string('fornecedor');
            $table->date('data_entrada');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estoques');
    }
};