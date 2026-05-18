<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('autorizacaos', function (Blueprint $table) {
            $table->id();
            $table->string('aluno_nome');
            $table->string('responsavel_nome');
            $table->enum('status', ['pendente', 'aprovado', 'negado']);
            $table->timestamp('data_saida')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autorizacaos');
    }
};
