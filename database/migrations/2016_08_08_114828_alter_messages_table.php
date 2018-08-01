<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('messages', function ($table) {
            $table->text('msg_gateway_response');
            $table->integer('msg_sent_attempts')->default(0);
            $table->string('msg_status',3)->default('RTS');
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
        Schema::table('messages', function ($table) {
            $table->dropColumn('msg_gateway_response');
            $table->dropColumn('msg_sent_attempts');
            $table->dropColumn('msg_status');
	});
    }
}
