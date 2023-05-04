<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReglaAplicadaPresupuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('regla_aplicada_presupuesto')) {
            Schema::create('regla_aplicada_presupuesto', function (Blueprint $table) {
                $table->id();

                $table->foreignId('regla_id')->constrained('reglas')->nullable();
                $table->foreignId('presupuesto_id')->constrained('presupuesto')->nullable();
                $table->decimal('total', 13, 3);

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
        Schema::dropIfExists('regla_aplicada_presupuesto');
    }
}
