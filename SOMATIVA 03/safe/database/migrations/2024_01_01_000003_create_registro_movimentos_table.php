<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_movimentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('turma_id')->constrained('turmas')->onDelete('cascade');
            $table->foreignId('registrado_por')->constrained('users')->onDelete('cascade')
                  ->comment('Usuário da coordenação que registrou');

            $table->enum('tipo', ['entrada', 'saida']);
            $table->datetime('horario');

            // Faltas: array de aulas (ex: [1, 2] = faltou 1ª e 2ª aula)
            $table->json('faltas_aulas')->nullable()->comment('Array com números das aulas perdidas');

            $table->string('motivo')->nullable();
            $table->text('observacao')->nullable();

            // Empresa
            $table->boolean('tem_empresa')->default(false);
            $table->string('empresa_nome')->nullable();

            // Controle de menor
            $table->boolean('menor_de_idade')->default(false);
            $table->boolean('responsavel_autorizado')->default(false);
            $table->string('responsavel_presente_nome')->nullable();

            // Confirmação da portaria
            $table->boolean('confirmado_portaria')->default(false);
            $table->foreignId('confirmado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('confirmado_at')->nullable();

            // Status geral
            $table->enum('status', ['pendente', 'confirmado', 'negado'])->default('pendente');

            // Notificações
            $table->boolean('notificacao_email_enviada')->default(false);
            $table->boolean('notificacao_whatsapp_enviada')->default(false);
            $table->datetime('notificacao_enviada_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_movimentos');
    }
};