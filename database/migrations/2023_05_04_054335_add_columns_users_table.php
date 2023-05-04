<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users') && !Schema::hasColumns('users', ['created_by', 'updated_by', 'last_name', 'image', 'country', 'city', 'address'])) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('last_name')->nullable();
                $table->string('image')->nullable();
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('address')->nullable();
                $table->foreignId('created_by')->constrained('users')->nullable();
                $table->foreignId('updated_by')->constrained('users')->nullable();
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
        if (Schema::hasTable('users') && Schema::hasColumns('users', ['created_by', 'updated_by', 'last_name', 'image', 'country', 'city', 'address'])) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by', 'last_name', 'image', 'country', 'city', 'address']);
            });
        }

    }
}
