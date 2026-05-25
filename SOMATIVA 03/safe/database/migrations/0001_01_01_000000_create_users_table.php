<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('rn_re')->unique()->comment('Registro do aluno ou funcionário');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['professor', 'coordenacao', 'portaria', 'admin'])
                  ->default('professor');
            $table->boolean('ativo')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};