<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('message_types', function (Blueprint $table) {
            $table->increments('mtp_id');
            $table->string('mtp_desc',255);
            $table->string('mtp_message',255);
        });
        
        DB::table('message_types')->insert([
            'mtp_desc'     => 'Message sent on registration',
            'mtp_message'  => "Hi, thank you for registering to enrol at B&FC. We'll "
            . "text you when it's time to go to the room and sign up."
        ]);
        
        DB::table('message_types')->insert([
            'mtp_desc'     => 'Message sent when ready to enrol',
            'mtp_message'  => "Hi, we're ready to sign you up for your course now; "
            . "please go to the room where you registered in the ATC and watch for your "
            . "name on the display screens and listen for your name to be called out."
        ]);
        
        DB::table('message_types')->insert([
            'mtp_desc'     => 'Message sent on missed appointment',
            'mtp_message'  => "Hi, you've missed your time slot to sign up; don’t worry, "
            . "we've added you to a fast track queue and you will shortly be hearing/seeing "
            . "your name in the main sign up room where you registered."
        ]);
        

        Schema::table('messages', function ($table) {
            $table->renameColumn('msg_message','msg_mtp_id');
           
	});
        
        Schema::table('messages', function ($table) {
            $table->integer('msg_mtp_id')->unsigned()->nullable(false)->change();
        });
        
        Schema::table('messages', function ($table) {
            $table->foreign('msg_mtp_id')->references('mtp_id')->on('message_types');
        });
         
        
        Schema::table('messages', function ($table) {
            $table->integer('msg_que_id')->unsigned()->nullable(true)->change();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        
    }
}
