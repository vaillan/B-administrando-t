<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastosReporteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos_reporte', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 13, 3);

            $table->foreignId('regla_aplicada_presupuesto_id')->constrained('regla_aplicada_presupuesto')->nullable();
            $table->foreignId('gasto_id')->constrained('gastos')->nullable();

            $table->foreignId('created_by')->constrained('users')->nullable();
            $table->foreignId('updated_by')->constrained('users')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gastos_reporte');
    }
}
