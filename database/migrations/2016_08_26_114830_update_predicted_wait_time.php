<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePredictedWaitTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary` AS select (select count(0) from `assignments` where (`assignments`.`asn_status` = 'COM')) AS `enrolled_count`,(select count(0) from `assignments` where (`assignments`.`asn_status` = 'FAI')) AS `failed_enrolled_count`,(select ifnull(ceiling(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`assignments`.`asn_created_ts`)) / 60))),0) AS `avg_enrolment_mins` from `assignments` where (`assignments`.`asn_completed_ts` is not null)) AS `avg_enrolment_mins`,(select ifnull(ceiling(((15 * (select count(0) from `waiting_list`)) / (select count(0) from `active_service_desks`))),0) AS `avg_wait_time`) AS `avg_wait_mins`");
        
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `report_failed` AS select distinct `registrations_archive`.`reg_first_name` AS `Forename`,`registrations_archive`.`reg_last_name` AS `Surname`,`registrations_archive`.`reg_dob` AS `DOB`,ifnull(`registrations_archive`.`reg_email`,'') AS `Email`,ifnull(`registrations_archive`.`reg_mob`,'') AS `Mobile` from (`registrations_archive` join `assignments_archive`) where ((`assignments_archive`.`asn_reg_id` = `registrations_archive`.`reg_id`) and (`assignments_archive`.`asn_status` = 'FAI') and (not(`registrations_archive`.`reg_id` in (select `registrations_archive`.`reg_id` from (`registrations_archive` join `assignments_archive`) where ((`assignments_archive`.`asn_reg_id` = `registrations_archive`.`reg_id`) and (`assignments_archive`.`asn_status` = 'COM'))))))");
        
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `report_no_show` AS select distinct `registrations_archive`.`reg_first_name` AS `Forename`,`registrations_archive`.`reg_last_name` AS `Surname`,`registrations_archive`.`reg_dob` AS `DOB`,ifnull(`registrations_archive`.`reg_email`,'') AS `Email`,ifnull(`registrations_archive`.`reg_mob`,'') AS `Mobile` from (`registrations_archive` join `assignments_archive`) where ((`assignments_archive`.`asn_reg_id` = `registrations_archive`.`reg_id`) and (`assignments_archive`.`asn_status` = 'NOS'))");
        
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `reported_completed` AS select distinct `registrations_archive`.`reg_first_name` AS `Forename`,`registrations_archive`.`reg_last_name` AS `Surname`,`registrations_archive`.`reg_dob` AS `DOB`,ifnull(`registrations_archive`.`reg_email`,'') AS `Email`,ifnull(`registrations_archive`.`reg_mob`,'') AS `Mobile` from (`registrations_archive` join `assignments_archive`) where ((`assignments_archive`.`asn_reg_id` = `registrations_archive`.`reg_id`) and (`assignments_archive`.`asn_status` = 'COM'))");
    
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `waiting_list` AS select `registrations`.`reg_id` AS `reg_id`,`registrations`.`reg_first_name` AS `reg_first_name`,`registrations`.`reg_last_name` AS `reg_last_name`,ifnull(`registrations`.`reg_email`,'') AS `reg_email`,ifnull(`registrations`.`reg_mob`,'') AS `reg_mob`,`registrations`.`reg_dob` AS `reg_dob`,(case when ((select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) between 1 and 3) then 3 when ((select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) >= (select `config_vars`.`con_value` from `config_vars` where (`config_vars`.`con_name` = 'NO_SHOW_MAX_ATTEMPTS'))) then 0 when (truncate(((to_days(str_to_date(concat(year(curdate()),'-AUG-31'),'%Y-%b-%d')) - to_days(`registrations`.`reg_dob`)) / 365),0) < 19) then 1 when (truncate(((to_days(str_to_date(concat(year(curdate()),'-AUG-31'),'%Y-%b-%d')) - to_days(`registrations`.`reg_dob`)) / 365),0) >= 19) then 2 end) AS `que_id`,(select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) AS `no_show_count`,`registrations`.`reg_created_ts` AS `reg_created_ts`,(select ifnull(max(`assignments`.`asn_created_ts`),`registrations`.`reg_created_ts`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`)) AS `last_activity_ts` from `registrations` where (not(`registrations`.`reg_id` in (select `assignments`.`asn_reg_id` from `assignments` where ((`assignments`.`asn_status` in ('COM','FAI','STA')) or isnull(`assignments`.`asn_status`))))) order by `registrations`.`reg_created_ts`,(select max(`assignments`.`asn_created_ts`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))");
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
