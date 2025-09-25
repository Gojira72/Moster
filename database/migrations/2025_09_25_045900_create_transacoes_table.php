<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a criação da tabela de transações.
     */
    public function up(): void
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conta_id')->constrained('contas')->cascadeOnDelete();
            $table->string('tipo', 30);
            $table->string('categoria', 60)->nullable();
            $table->decimal('valor', 12, 2);
            $table->string('descricao', 120);
            $table->string('contraparte', 120)->nullable();
            $table->timestamp('ocorreu_em');
            $table->timestamps();

            $table->index(['conta_id', 'ocorreu_em']);
            $table->index('tipo');
        });
    }

    /**
     * Remove a tabela.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacoes');
    }
};
