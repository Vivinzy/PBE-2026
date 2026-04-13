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
    Schema::table('insumos', function (Blueprint $table) {
        $table->renameColumn('preço_custo', 'preco_custo');
    });
}

public function down(): void
{
    Schema::table('insumos', function (Blueprint $table) {
        $table->renameColumn('preco_custo', 'preço_custo');
    });
}
};
 