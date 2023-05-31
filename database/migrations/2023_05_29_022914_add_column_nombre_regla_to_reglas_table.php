<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNombreReglaToReglasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('reglas') && !Schema::hasColumn('reglas', 'nombre_regla')) {
            Schema::table('reglas', function (Blueprint $table) {
                $table->string('nombre_regla')->nullable();
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
        if (Schema::hasTable('reglas') && Schema::hasColumn('reglas', 'nombre_regla')) {
            Schema::table('reglas', function (Blueprint $table) {
                $table->dropColumn('nombre_regla');
            });
        }
    }
}
