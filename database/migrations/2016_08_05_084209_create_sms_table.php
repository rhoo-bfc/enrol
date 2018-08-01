<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('msg_id');
            $table->integer('msg_reg_id')->unsigned();
            $table->integer('msg_que_id')->unsigned();
            $table->string('msg_message',255);
	    $table->timestamp('msg_created_ts');
            $table->timestamp('msg_sent_ts')->nullable();
            $table->foreign('msg_reg_id')->references('reg_id')->on('registrations');
	    $table->foreign('msg_que_id')->references('que_id')->on('queues');
            $table->index(['msg_reg_id', 'msg_que_id']);
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
        Schema::drop('messages');
    }
}
