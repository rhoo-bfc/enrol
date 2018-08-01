<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalDashViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dash_failed_enrollments` AS select concat(`failed_enrollment`.`reg_first_name`,' ',`failed_enrollment`.`reg_last_name`) AS `ENROLLEE`,date_format(`failed_enrollment`.`reg_dob`,'%d/%m/%Y') AS `DOB`,ifnull(`failed_enrollment`.`reg_email`,'') AS `EMAIL`,ifnull(`failed_enrollment`.`reg_mob`,'') AS `MOBILE NO`,cast(`failed_enrollment`.`asn_created_ts` as time) AS `START TIME`,timediff(`failed_enrollment`.`asn_completed_ts`,`failed_enrollment`.`asn_created_ts`) AS `ENROL TIME`,`failed_enrollment`.`asn_notes` AS `REASON` from `failed_enrollment`");
        
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dash_enrolled` AS select concat(`enrolled`.`reg_first_name`,' ',`enrolled`.`reg_last_name`) AS `ENROLLEE`,date_format(`enrolled`.`reg_dob`,'%d/%m/%Y') AS `DOB`,ifnull(`enrolled`.`reg_email`,'') AS `EMAIL`,ifnull(`enrolled`.`reg_mob`,'') AS `MOBILE NO`,cast(`enrolled`.`asn_created_ts` as time) AS `START TIME`,timediff(`enrolled`.`asn_completed_ts`,`enrolled`.`asn_created_ts`) AS `ENROL TIME` from `enrolled`");
        
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
