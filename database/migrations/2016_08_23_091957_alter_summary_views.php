<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSummaryViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary` AS select (select count(0) from `assignments` where (`assignments`.`asn_status` = 'COM')) AS `enrolled_count`,(select count(0) from `assignments` where (`assignments`.`asn_status` = 'FAI')) AS `failed_enrolled_count`,(select round(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`assignments`.`asn_created_ts`)) / 60)),0) AS `avg_enrolment_mins` from `assignments` where (`assignments`.`asn_completed_ts` is not null)) AS `avg_enrolment_mins`,(select round(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`registrations`.`reg_created_ts`)) / 60)),0) AS `avg_wait_mins` from (`assignments` join `registrations`) where ((`assignments`.`asn_reg_id` = `registrations`.`reg_id`) and (`assignments`.`asn_id` = (select min(`assignments`.`asn_id`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))))) AS `avg_wait_mins`");
    
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary_by_queue` AS select `a`.`que_id` AS `que_id`,`a`.`que_name` AS `que_name`,round(`a`.`avg_enrolment_mins`,0) AS `avg_enrolment_mins`,round(`b`.`avg_wait_mins`,0) AS `avg_wait_mins`,`c`.`enrolled_count` AS `enrolled_count`,`d`.`unable_to_enrol` AS `unable_to_enrol` from (((`summary_avg_enrol_time_by_queue` `a` join `summary_avg_wait_time_by_queue` `b`) join `summary_enrolled_by_queue` `c`) join `summary_unable_to_enrol_by_queue` `d`) where ((`a`.`que_id` = `b`.`que_id`) and (`b`.`que_id` = `c`.`que_id`) and (`c`.`que_id` = `d`.`que_id`))");
    
        DB::connection()->getPdo()->exec('ALTER TABLE assignments '
                . 'modify COLUMN  asn_created_ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL;');
        
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dash_active_service_desks` AS select `active_service_desks`.`src_centre_name` AS `Service Desk`,cast(`active_service_desks`.`ats_start_ts` as time) AS `Start Time`,timediff(now(),`active_service_desks`.`ats_start_ts`) AS `Active Time`,concat(`active_service_desks`.`att_first_name`,' ',`active_service_desks`.`att_second_name`) AS `Attendant`,(select `queues`.`que_name` from `queues` where (`queues`.`que_id` = `active_service_desks`.`ats_que_id`)) AS `queue` from `active_service_desks` order by (select `queues`.`que_name` from `queues` where (`queues`.`que_id` = `active_service_desks`.`ats_que_id`))");
    
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dash_queues_attendants_count` AS select `queues`.`que_name` AS `Queue`,(select count(0) from `active_service_desks` where (`active_service_desks`.`ats_que_id` = `queues`.`que_id`)) AS `Active Attendants`,(select count(0) from `waiting_list` where (`waiting_list`.`que_id` = `queues`.`que_id`)) AS `Enrollees In Queue` from `queues`");
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
