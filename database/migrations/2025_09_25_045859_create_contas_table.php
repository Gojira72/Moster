<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a criação da tabela de contas.
     */
    public function up(): void
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('tb_usuarios')->cascadeOnDelete();
            $table->decimal('saldo_atual', 12, 2);
            $table->decimal('limite_credito', 12, 2)->default(0);
            $table->decimal('limite_disponivel', 12, 2)->default(0);
            $table->timestamps();

            $table->unique('usuario_id');
            $table->index('saldo_atual');
        });
    }

    /**
     * Reverte a criação da tabela.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
