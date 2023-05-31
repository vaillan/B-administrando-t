<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('periodos')) {
            Schema::create('periodos', function (Blueprint $table) {
                $table->id();
                $table->date('periodo');

                $table->unsignedBigInteger('ingreso_id')->nullable();
                $table->foreign('ingreso_id')->references('id')->on('ingresos');

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
        Schema::dropIfExists('periodos');
    }
}
