<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnGastoIdToPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('periodos') && !Schema::hasColumn('periodos', 'gasto_id')) {
            Schema::table('periodos', function (Blueprint $table) {
                $table->foreignId('gasto_id')->constrained('gastos')->nullable();
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
        if (Schema::hasTable('periodos') && Schema::hasColumn('periodos', 'gasto_id')) {
            Schema::table('periodos', function (Blueprint $table) {
                $table->dropForeign(['gasto_id']);
                $table->dropColumn('gasto_id');
            });
        }
    }
}
