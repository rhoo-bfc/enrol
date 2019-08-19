<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRollingMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rolling_messages', function (Blueprint $table) {
            $table->increments('rmg_id');
            $table->integer('rmg_que_id')->unsigned;
            $table->mediumText('rmg_message');
            $table->char('rmg_active',1);
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
        Schema::drop('rolling_messages');
    }
}
