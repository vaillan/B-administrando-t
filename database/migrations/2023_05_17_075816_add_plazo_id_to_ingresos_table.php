<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlazoIdToIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ingresos') && !Schema::hasColumn('ingresos', 'plazo_id')) {
            Schema::table('ingresos', function (Blueprint $table) {
                $table->foreignId('plazo_id')->constrained('plazos')->nullable(1);
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
        if (Schema::hasTable('ingresos') && Schema::hasColumn('ingresos', 'plazo_id')) {
            Schema::table('ingresos', function (Blueprint $table) {
                $table->dropForeign(['plazo_id']);
                $table->dropColumn('plazo_id');
            });
        }
    }
}
