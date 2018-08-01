<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `enrolled` AS select `registrations`.`reg_id` AS `reg_id`,`registrations`.`reg_first_name` AS `reg_first_name`,`registrations`.`reg_last_name` AS `reg_last_name`,`registrations`.`reg_dob` AS `reg_dob`,`registrations`.`reg_email` AS `reg_email`,`registrations`.`reg_mob` AS `reg_mob`,`registrations`.`reg_created_ts` AS `reg_created_ts`,`assignments`.`asn_created_ts` AS `asn_created_ts`,`assignments`.`asn_completed_ts` AS `asn_completed_ts` from (`registrations` join `assignments`) where ((`assignments`.`asn_reg_id` = `registrations`.`reg_id`) and (`assignments`.`asn_status` = 'COM'))");
        
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `failed_enrollment` AS select `registrations`.`reg_id` AS `reg_id`,`registrations`.`reg_first_name` AS `reg_first_name`,`registrations`.`reg_last_name` AS `reg_last_name`,`registrations`.`reg_dob` AS `reg_dob`,`registrations`.`reg_email` AS `reg_email`,`registrations`.`reg_mob` AS `reg_mob`,`registrations`.`reg_created_ts` AS `reg_created_ts`,`assignments`.`asn_created_ts` AS `asn_created_ts`,`assignments`.`asn_completed_ts` AS `asn_completed_ts`,`assignments`.`asn_notes` AS `asn_notes` from (`registrations` join `assignments`) where ((`assignments`.`asn_reg_id` = `registrations`.`reg_id`) and (`assignments`.`asn_status` = 'FAI'))");
        
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `waiting_list` AS select `registrations`.`reg_id` AS `reg_id`,`registrations`.`reg_first_name` AS `reg_first_name`,`registrations`.`reg_last_name` AS `reg_last_name`,ifnull(`registrations`.`reg_email`,'') AS `reg_email`,ifnull(`registrations`.`reg_mob`,'') AS `reg_mob`,`registrations`.`reg_dob` AS `reg_dob`,(case when ((select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) between 1 and 3) then 3 when ((select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) >= (select `config_vars`.`con_value` from `config_vars` where (`config_vars`.`con_name` = 'NO_SHOW_MAX_ATTEMPTS'))) then 0 when (truncate(((to_days(str_to_date(concat(year(curdate()),'-AUG-31'),'%Y-%b-%d')) - to_days(`registrations`.`reg_dob`)) / 365),0) < 19) then 1 when (truncate(((to_days(str_to_date(concat(year(curdate()),'-AUG-31'),'%Y-%b-%d')) - to_days(`registrations`.`reg_dob`)) / 365),0) > 19) then 2 end) AS `que_id`,(select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) AS `no_show_count`,`registrations`.`reg_created_ts` AS `reg_created_ts`,(select ifnull(max(`assignments`.`asn_created_ts`),`registrations`.`reg_created_ts`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`)) AS `last_activity_ts` from `registrations` where (not(`registrations`.`reg_id` in (select `assignments`.`asn_reg_id` from `assignments` where ((`assignments`.`asn_status` in ('COM','FAI','STA')) or isnull(`assignments`.`asn_status`))))) order by `registrations`.`reg_created_ts`,(select max(`assignments`.`asn_created_ts`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))");
    
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `feed_callouts` AS select concat(`callouts`.`reg_first_name`,' ',`callouts`.`reg_last_name`,' (',date_format(`callouts`.`reg_dob`,'%d-%b'),')') AS `Please go to service desk`,concat('',`callouts`.`src_centre_name`) AS `&nbsp;` from `callouts`");
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
