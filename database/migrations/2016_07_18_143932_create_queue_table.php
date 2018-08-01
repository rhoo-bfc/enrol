<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('queues', function (Blueprint $table) {
            $table->increments('que_id');
            $table->string('que_name',255);
            $table->char('que_active',1)->default('Y');
        });
        
        Schema::table('service_attendant_sessions', function ($table) {
            $table->integer('ats_que_id')->unsigned()->after('ats_src_id');
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('queues');
        
        Schema::table('service_attendant_sessions', function ($table) {
            $table->dropColumn('ats_que_id');
	});
    }
}
