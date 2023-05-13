<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceNameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users') && !Schema::hasColumn('users', 'device_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('device_name')->nullable();
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
        if(Schema::hasTable('users') && Schema::hasColumn('users', 'device_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('device_name');
            });
        }
    }
}
