<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoUsuarioColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'tipo_usuario_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('tipo_usuario_id')->constrained('tipo_usuarios')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'tipo_usuario_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['tipo_usuario_id']);
                $table->dropColumn('tipo_usuario_id');
            });
        }
    }
}
