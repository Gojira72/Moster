<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a criação da tabela de cartões.
     */
    public function up(): void
    {
        Schema::create('cartoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('tb_usuarios')->cascadeOnDelete();
            $table->string('apelido')->nullable();
            $table->string('bandeira', 30);
            $table->string('ultimos_digitos', 4);
            $table->decimal('limite_total', 12, 2);
            $table->decimal('limite_disponivel', 12, 2);
            $table->string('status', 30)->default('ativo');
            $table->date('vencimento_fatura');
            $table->timestamps();

            $table->index('usuario_id');
        });
    }

    /**
     * Reverte a criação.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartoes');
    }
};
