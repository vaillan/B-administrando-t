<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoIngresoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tipo_ingresos')) {
            Schema::create('tipo_ingresos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre_ingreso');

                $table->foreignId('created_by')->constrained('users')->nullable();
                $table->foreignId('updated_by')->constrained('users')->nullable();

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
        Schema::dropIfExists('tipo_ingresos');
    }
}
