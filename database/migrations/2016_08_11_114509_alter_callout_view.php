<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCalloutView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //current_enrolments
        DB::statement("CREATE OR REPLACE VIEW `feed_callouts` AS select concat(`callouts`.`reg_first_name`,' ',`callouts`.`reg_last_name`,' (',date_format(`callouts`.`reg_dob`,'%d-%b'),')') AS `Enrollee - DOB (Day-Month)`,concat('Go to ',`callouts`.`src_centre_name`) AS `&nbsp;` from `callouts`");
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
