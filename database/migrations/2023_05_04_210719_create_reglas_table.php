<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReglasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('reglas')) {
            Schema::create('reglas', function (Blueprint $table) {
                $table->id();
                $table->decimal('porcentaje', 13,3);

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
        Schema::dropIfExists('reglas');
    }
}
