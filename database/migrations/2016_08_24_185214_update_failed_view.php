<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFailedView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dash_failed_enrollments` AS select concat(`failed_enrollment`.`reg_first_name`,' ',`failed_enrollment`.`reg_last_name`) AS `ENROLLEE`,date_format(`failed_enrollment`.`reg_dob`,'%d/%m/%Y') AS `DOB`,ifnull(`failed_enrollment`.`reg_email`,'') AS `EMAIL`,ifnull(`failed_enrollment`.`reg_mob`,'') AS `MOBILE NO`,cast(`failed_enrollment`.`asn_created_ts` as time) AS `START TIME`,timediff(`failed_enrollment`.`asn_completed_ts`,`failed_enrollment`.`asn_created_ts`) AS `ENROL TIME`,(case `failed_enrollment`.`asn_reason_code` when 1 then 'No enrolment form' when 2 then 'No register group on form' when 3 then 'No course code on form' when 4 then 'Incorrect course code on form' when 5 then 'No waiver code on form' when 6 then 'Invalid course code - W/D' when 7 then 'No learning support group' when 8 then 'No prior quals form' when 9 then 'Incorrect IAG' when 10 then 'Student unsure of course wanted to cancel' when 11 then 'No benefit evidence of fee waiver' when 12 then 'No method of payment' when 13 then 'No adv loan evidence' when 14 then 'Unwilling to pay course fees' when 15 then 'Safeguarding issue' when 16 then 'Immigration issue' when 17 then 'Student enrolled - pressed wrong button' when 18 then 'Student no show - pressed wrong button' when 19 then 'Not an enrolment - ref\'d to as careers' when 20 then 'Student already enrolled' when 21 then 'Not an enrolment - ref\'d to IAG' else 'Other' end) AS `REASON`,`failed_enrollment`.`asn_notes` AS `NOTES`,concat('<button data-revert=\"',`failed_enrollment`.`reg_id`,'\" class=\"secondary button\">Restore</button>') AS `Action` from `failed_enrollment`");
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
