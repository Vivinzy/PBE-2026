<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('ra')->unique()->comment('Registro do aluno');
            $table->string('nome');
            $table->string('email')->nullable();
            $table->date('data_nascimento');
            $table->string('telefone')->nullable();

            // Dados do responsável (obrigatório para menores)
            $table->string('responsavel_nome')->nullable();
            $table->string('responsavel_telefone')->nullable();
            $table->string('responsavel_email')->nullable();
            $table->string('responsavel_whatsapp')->nullable();

            // Empresa (estágio)
            $table->boolean('tem_empresa')->default(false);
            $table->string('empresa_nome')->nullable();

            $table->foreignId('turma_id')->constrained('turmas')->onDelete('cascade');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};