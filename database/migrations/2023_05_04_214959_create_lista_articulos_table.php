<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListaArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('lista_articulos')) {
            Schema::create('lista_articulos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre_articulo');

                $table->foreignId('categoria_id')->constrained('categorias')->nullable();

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
        Schema::dropIfExists('lista_articulos');
    }
}
