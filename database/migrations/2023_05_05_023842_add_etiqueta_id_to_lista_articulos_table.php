<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEtiquetaIdToListaArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('lista_articulos') && !Schema::hasColumn('lista_articulos', 'etiqueta_id')) {
            Schema::table('lista_articulos', function (Blueprint $table) {
                $table->foreignId('etiqueta_id')->constrained('lista_articulos')->nullable();
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
        if(Schema::hasTable('lista_articulos') && Schema::hasColumn('lista_articulos', 'etiqueta_id')) {
            Schema::table('lista_articulos', function (Blueprint $table) {
                $table->dropForeign(['etiqueta_id']);
                $table->dropColumn('etiqueta_id');
            });
        }
    }
}
