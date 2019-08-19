<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClearDownEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::connection()->getPdo()->exec("DROP EVENT IF EXISTS RUN_DAILY_CLEAR");

        DB::connection()->getPdo()->exec(
        "CREATE EVENT RUN_DAILY_CLEAR
            ON SCHEDULE EVERY '1' DAY
            STARTS '2010-08-18 01:00:00' 
            DO
            CALL CLEAR_DOWN()");

        DB::connection()->getPdo()->exec(
        "SET GLOBAL event_scheduler = ON");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(
        "DROP EVENT IF EXISTS RUN_DAILY_CLEAR");
    }
}
