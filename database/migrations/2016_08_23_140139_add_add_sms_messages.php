<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddSmsMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
       
        DB::table('message_types')->insert([
            'mtp_desc'     => 'Message sent when assigned to desk',
            'mtp_message'  => "Hi, we are ready to sign you up now; please go to !!SERVICE_DESK!!. B&FC"
        ]);
        
        Schema::table('messages', function ($table) {
            $table->binary('msg_params')->after('msg_gateway_response');
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
    }
}
