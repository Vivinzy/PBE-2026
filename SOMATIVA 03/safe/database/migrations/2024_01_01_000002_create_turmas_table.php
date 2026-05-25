<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->comment('Ex: Desenvolvimento de Sistemas 2ºA');
            $table->string('curso');
            $table->string('periodo')->comment('Ex: 2º ano, 3º ano');
            $table->string('turno')->comment('manhã, tarde, noite');
            $table->time('horario_inicio');
            $table->time('horario_fim');
            $table->integer('total_aulas_dia')->default(5);
            $table->json('dias_semana')->comment('Array: [1=seg,2=ter,...,6=sab]');
            $table->foreignId('professor_id')->constrained('users')->onDelete('cascade');
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};