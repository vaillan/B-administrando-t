<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNombreTipoUsuarioToTipoUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tipo_usuarios') && !Schema::hasColumn('tipo_usuarios', 'nombre_tipo_usuario')) {
            Schema::table('tipo_usuarios', function (Blueprint $table) {
                $table->string('nombre_tipo_usuario');
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
        if(Schema::hasTable('tipo_usuarios') && Schema::hasColumn('tipo_usuarios', 'nombre_tipo_usuario')) {
            Schema::table('tipo_usuarios', function (Blueprint $table) {
                $table->dropColumn('nombre_tipo_usuario');
            });
        }
    }
}
