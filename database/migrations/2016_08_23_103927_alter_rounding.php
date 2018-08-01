<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRounding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary` AS select (select count(0) from `assignments` where (`assignments`.`asn_status` = 'COM')) AS `enrolled_count`,(select count(0) from `assignments` where (`assignments`.`asn_status` = 'FAI')) AS `failed_enrolled_count`,(select ceiling(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`assignments`.`asn_created_ts`)) / 60))) AS `avg_enrolment_mins` from `assignments` where (`assignments`.`asn_completed_ts` is not null)) AS `avg_enrolment_mins`,(select ceiling(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`registrations`.`reg_created_ts`)) / 60))) AS `avg_wait_mins` from (`assignments` join `registrations`) where ((`assignments`.`asn_reg_id` = `registrations`.`reg_id`) and (`assignments`.`asn_id` = (select min(`assignments`.`asn_id`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))))) AS `avg_wait_mins`");
        
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary_by_queue` AS select `a`.`que_id` AS `que_id`,`a`.`que_name` AS `que_name`,ceiling(`a`.`avg_enrolment_mins`) AS `avg_enrolment_mins`,ceiling(`b`.`avg_wait_mins`) AS `avg_wait_mins`,`c`.`enrolled_count` AS `enrolled_count`,`d`.`unable_to_enrol` AS `unable_to_enrol` from (((`summary_avg_enrol_time_by_queue` `a` join `summary_avg_wait_time_by_queue` `b`) join `summary_enrolled_by_queue` `c`) join `summary_unable_to_enrol_by_queue` `d`) where ((`a`.`que_id` = `b`.`que_id`) and (`b`.`que_id` = `c`.`que_id`) and (`c`.`que_id` = `d`.`que_id`))");
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
