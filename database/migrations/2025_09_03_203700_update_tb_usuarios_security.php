<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_usuarios', function (Blueprint $table) {
            if (! Schema::hasColumn('tb_usuarios', 'remember_token')) {
                $table->rememberToken()->nullable();
            }

            $table->unique('emailUsuario', 'tb_usuarios_email_unique');
        });
    }

    public function down(): void
    {
        Schema::table('tb_usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('tb_usuarios', 'remember_token')) {
                $table->dropColumn('remember_token');
            }

            if (Schema::hasIndex('tb_usuarios', 'tb_usuarios_email_unique')) {
                $table->dropUnique('tb_usuarios_email_unique');
            }
        });
    }
};
