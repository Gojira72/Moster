<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_usuarios', function (Blueprint $table) {
            if (! Schema::hasColumn('tb_usuarios', 'telefone')) {
                $table->string('telefone', 20)->nullable()->after('senhaUsuario');
            }

            if (! Schema::hasColumn('tb_usuarios', 'avatar_url')) {
                $table->string('avatar_url')->nullable()->after('telefone');
            }

            if (! Schema::hasColumn('tb_usuarios', 'documento')) {
                $table->string('documento', 20)->nullable()->after('avatar_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('tb_usuarios', 'documento')) {
                $table->dropColumn('documento');
            }

            if (Schema::hasColumn('tb_usuarios', 'avatar_url')) {
                $table->dropColumn('avatar_url');
            }

            if (Schema::hasColumn('tb_usuarios', 'telefone')) {
                $table->dropColumn('telefone');
            }
        });
    }
};
