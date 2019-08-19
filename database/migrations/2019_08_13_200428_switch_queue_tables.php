<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SwitchQueueTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('switch_queues', function (Blueprint $table) {
            $table->increments('swq_id');
            $table->string('swq_ats_session_id', 255);
            $table->integer('swq_que_id')->unsigned();
            $table->timestamp('swq_created_ts');
            $table->timestamp('swq_actioned_ts')->nullable();
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
        Schema::drop('switch_queues');
    }
}
