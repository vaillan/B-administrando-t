<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresupuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('presupuesto')) {
            Schema::create('presupuesto', function (Blueprint $table) {
                $table->id();
                $table->decimal('total', 13, 3);
                $table->unsignedBigInteger('usuario_id')->nullable();
                $table->foreign('usuario_id')->references('id')->on('users');
                
                $table->foreignId('ingreso_id')->constrained('ingresos')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('presupuesto');
    }
}
