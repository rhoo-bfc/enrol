<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_vars', function (Blueprint $table) {
            $table->string('con_name');
            $table->string('con_value');
        });
        
        DB::table('config_vars')->insert([
            'con_name'     => 'SYSTEM_STATUS',
            'con_value'   => '1'
        ]);
        
        DB::table('config_vars')->insert([
            'con_name'     => 'SMS_WAIT_TIME_MINS',
            'con_value'    => '30'
        ]);
        
        DB::table('config_vars')->insert([
            'con_name'     => 'AVG_ENROL_TIME_MINS',
            'con_value'    => '15'
        ]);
        
        DB::table('config_vars')->insert([
            'con_name'     => 'NO_SHOW_MAX_ATTEMPTS',
            'con_value'    => '3'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('config_variables');
    }
}
