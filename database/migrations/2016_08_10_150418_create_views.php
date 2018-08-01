<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
         //current_enrolments
        DB::statement("CREATE OR REPLACE VIEW `current_enrolments` AS select `assignments`.`asn_ats_id` AS `asn_ats_id`,`assignments`.`asn_reg_id` AS `asn_reg_id`,`assignments`.`asn_status` AS `asn_status`,`assignments`.`asn_created_ts` AS `asn_created_ts`,`assignments`.`asn_completed_ts` AS `asn_completed_ts`,`assignments`.`asn_id` AS `asn_id`,`service_attendant_sessions`.`ats_att_id` AS `ats_att_id`,`service_attendant_sessions`.`ats_src_id` AS `ats_src_id`,`service_attendant_sessions`.`ats_que_id` AS `ats_que_id`,`service_attendant_sessions`.`ats_start_ts` AS `ats_start_ts`,`service_attendant_sessions`.`ats_end_ts` AS `ats_end_ts`,`service_attendant_sessions`.`ats_session_id` AS `ats_session_id`,`service_attendant_sessions`.`ats_id` AS `ats_id`,`registrations`.`reg_id` AS `reg_id`,`registrations`.`reg_first_name` AS `reg_first_name`,`registrations`.`reg_last_name` AS `reg_last_name`,`registrations`.`reg_dob` AS `reg_dob`,`registrations`.`reg_email` AS `reg_email`,`registrations`.`reg_mob` AS `reg_mob`,`registrations`.`reg_created_ts` AS `reg_created_ts` from ((`assignments` join `service_attendant_sessions`) join `registrations`) where (isnull(`assignments`.`asn_completed_ts`) and (`service_attendant_sessions`.`ats_id` = `assignments`.`asn_ats_id`) and isnull(`service_attendant_sessions`.`ats_end_ts`) and (`registrations`.`reg_id` = `assignments`.`asn_reg_id`))");
      
         
        //active_service_desks
        DB::statement("CREATE OR REPLACE VIEW `active_service_desks` AS select `service_desks`.`src_id` AS `src_id`,`service_desks`.`src_centre_name` AS `src_centre_name`,`service_desks`.`src_centre_desc` AS `src_centre_desc`,`service_desks`.`src_active` AS `src_active`,`service_attendant_sessions`.`ats_att_id` AS `ats_att_id`,`service_attendant_sessions`.`ats_src_id` AS `ats_src_id`,`service_attendant_sessions`.`ats_que_id` AS `ats_que_id`,`service_attendant_sessions`.`ats_start_ts` AS `ats_start_ts`,`service_attendant_sessions`.`ats_end_ts` AS `ats_end_ts`,`service_attendant_sessions`.`ats_session_id` AS `ats_session_id`,`service_attendant_sessions`.`ats_id` AS `ats_id`,`attendants`.`att_id` AS `att_id`,`attendants`.`att_email` AS `att_email`,`attendants`.`att_first_name` AS `att_first_name`,`attendants`.`att_second_name` AS `att_second_name`,`attendants`.`att_active` AS `att_active` from ((`service_desks` join `service_attendant_sessions`) join `attendants`) where ((`service_attendant_sessions`.`ats_src_id` = `service_desks`.`src_id`) and (`service_attendant_sessions`.`ats_att_id` = `attendants`.`att_id`) and isnull(`service_attendant_sessions`.`ats_end_ts`) and (`service_desks`.`src_active` = 'Y') and (`attendants`.`att_active` = 'Y'))");
        
        //available_service_desks
        DB::statement("CREATE OR REPLACE VIEW `available_service_desks` AS select `active_service_desks`.`src_id` AS `src_id`,`active_service_desks`.`src_centre_name` AS `src_centre_name`,`active_service_desks`.`src_centre_desc` AS `src_centre_desc`,`active_service_desks`.`src_active` AS `src_active`,`active_service_desks`.`ats_att_id` AS `ats_att_id`,`active_service_desks`.`ats_src_id` AS `ats_src_id`,`active_service_desks`.`ats_que_id` AS `ats_que_id`,`active_service_desks`.`ats_start_ts` AS `ats_start_ts`,`active_service_desks`.`ats_end_ts` AS `ats_end_ts`,`active_service_desks`.`ats_session_id` AS `ats_session_id`,`active_service_desks`.`ats_id` AS `ats_id`,`active_service_desks`.`att_id` AS `att_id`,`active_service_desks`.`att_email` AS `att_email`,`active_service_desks`.`att_first_name` AS `att_first_name`,`active_service_desks`.`att_second_name` AS `att_second_name`,`active_service_desks`.`att_active` AS `att_active` from `active_service_desks` where (not(`active_service_desks`.`src_id` in (select `current_enrolments`.`ats_src_id` from `current_enrolments`)))");
        
            //active_attendants  
        DB::statement('CREATE OR REPLACE VIEW `active_attendants` AS select `attendants`.`att_id` AS `att_id`,`attendants`.`att_email` AS `att_email`,`attendants`.`att_first_name` AS `att_first_name`,`attendants`.`att_second_name` AS `att_second_name`,`attendants`.`att_active` AS `att_active` from `attendants` where `attendants`.`att_id` in (select `active_service_desks`.`att_id` from `active_service_desks`)');
         
        //callouts
        DB::statement("CREATE OR REPLACE VIEW `callouts` AS select `assignments`.`asn_ats_id` AS `asn_ats_id`,`assignments`.`asn_reg_id` AS `asn_reg_id`,`assignments`.`asn_status` AS `asn_status`,`assignments`.`asn_created_ts` AS `asn_created_ts`,`assignments`.`asn_completed_ts` AS `asn_completed_ts`,`assignments`.`asn_id` AS `asn_id`,`registrations`.`reg_id` AS `reg_id`,`registrations`.`reg_first_name` AS `reg_first_name`,`registrations`.`reg_last_name` AS `reg_last_name`,`registrations`.`reg_dob` AS `reg_dob`,`registrations`.`reg_email` AS `reg_email`,`registrations`.`reg_mob` AS `reg_mob`,`registrations`.`reg_created_ts` AS `reg_created_ts`,`service_attendant_sessions`.`ats_att_id` AS `ats_att_id`,`service_attendant_sessions`.`ats_src_id` AS `ats_src_id`,`service_attendant_sessions`.`ats_que_id` AS `ats_que_id`,`service_attendant_sessions`.`ats_start_ts` AS `ats_start_ts`,`service_attendant_sessions`.`ats_end_ts` AS `ats_end_ts`,`service_attendant_sessions`.`ats_session_id` AS `ats_session_id`,`service_attendant_sessions`.`ats_id` AS `ats_id`,`service_desks`.`src_id` AS `src_id`,`service_desks`.`src_centre_name` AS `src_centre_name`,`service_desks`.`src_centre_desc` AS `src_centre_desc`,`service_desks`.`src_active` AS `src_active` from (((`assignments` join `registrations`) join `service_attendant_sessions`) join `service_desks`) where ((`assignments`.`asn_reg_id` = `registrations`.`reg_id`) and (`assignments`.`asn_ats_id` = `service_attendant_sessions`.`ats_id`) and (`service_attendant_sessions`.`ats_src_id` = `service_desks`.`src_id`) and isnull(`assignments`.`asn_status`) and isnull(`service_attendant_sessions`.`ats_end_ts`))");
        
        //callouts_18_to_20
        DB::statement("CREATE OR REPLACE VIEW `callouts_18_to_20` AS select `callouts`.`asn_ats_id` AS `asn_ats_id`,`callouts`.`asn_reg_id` AS `asn_reg_id`,`callouts`.`asn_status` AS `asn_status`,`callouts`.`asn_created_ts` AS `asn_created_ts`,`callouts`.`asn_completed_ts` AS `asn_completed_ts`,`callouts`.`asn_id` AS `asn_id`,`callouts`.`reg_id` AS `reg_id`,`callouts`.`reg_first_name` AS `reg_first_name`,`callouts`.`reg_last_name` AS `reg_last_name`,`callouts`.`reg_dob` AS `reg_dob`,`callouts`.`reg_email` AS `reg_email`,`callouts`.`reg_mob` AS `reg_mob`,`callouts`.`reg_created_ts` AS `reg_created_ts`,`callouts`.`ats_att_id` AS `ats_att_id`,`callouts`.`ats_src_id` AS `ats_src_id`,`callouts`.`ats_que_id` AS `ats_que_id`,`callouts`.`ats_start_ts` AS `ats_start_ts`,`callouts`.`ats_end_ts` AS `ats_end_ts`,`callouts`.`ats_session_id` AS `ats_session_id`,`callouts`.`ats_id` AS `ats_id`,`callouts`.`src_id` AS `src_id`,`callouts`.`src_centre_name` AS `src_centre_name`,`callouts`.`src_centre_desc` AS `src_centre_desc`,`callouts`.`src_active` AS `src_active` from `callouts` where (`callouts`.`ats_que_id` = 1)");
        
        //callouts_19_plus
        DB::statement("CREATE OR REPLACE VIEW `callouts_19_plus` AS select `callouts`.`asn_ats_id` AS `asn_ats_id`,`callouts`.`asn_reg_id` AS `asn_reg_id`,`callouts`.`asn_status` AS `asn_status`,`callouts`.`asn_created_ts` AS `asn_created_ts`,`callouts`.`asn_completed_ts` AS `asn_completed_ts`,`callouts`.`asn_id` AS `asn_id`,`callouts`.`reg_id` AS `reg_id`,`callouts`.`reg_first_name` AS `reg_first_name`,`callouts`.`reg_last_name` AS `reg_last_name`,`callouts`.`reg_dob` AS `reg_dob`,`callouts`.`reg_email` AS `reg_email`,`callouts`.`reg_mob` AS `reg_mob`,`callouts`.`reg_created_ts` AS `reg_created_ts`,`callouts`.`ats_att_id` AS `ats_att_id`,`callouts`.`ats_src_id` AS `ats_src_id`,`callouts`.`ats_que_id` AS `ats_que_id`,`callouts`.`ats_start_ts` AS `ats_start_ts`,`callouts`.`ats_end_ts` AS `ats_end_ts`,`callouts`.`ats_session_id` AS `ats_session_id`,`callouts`.`ats_id` AS `ats_id`,`callouts`.`src_id` AS `src_id`,`callouts`.`src_centre_name` AS `src_centre_name`,`callouts`.`src_centre_desc` AS `src_centre_desc`,`callouts`.`src_active` AS `src_active` from `callouts` where (`callouts`.`ats_que_id` = 2)");
        
        //callouts_missed_appointments
        DB::statement("CREATE OR REPLACE VIEW `callouts_missed_appointments` AS select `callouts`.`asn_ats_id` AS `asn_ats_id`,`callouts`.`asn_reg_id` AS `asn_reg_id`,`callouts`.`asn_status` AS `asn_status`,`callouts`.`asn_created_ts` AS `asn_created_ts`,`callouts`.`asn_completed_ts` AS `asn_completed_ts`,`callouts`.`asn_id` AS `asn_id`,`callouts`.`reg_id` AS `reg_id`,`callouts`.`reg_first_name` AS `reg_first_name`,`callouts`.`reg_last_name` AS `reg_last_name`,`callouts`.`reg_dob` AS `reg_dob`,`callouts`.`reg_email` AS `reg_email`,`callouts`.`reg_mob` AS `reg_mob`,`callouts`.`reg_created_ts` AS `reg_created_ts`,`callouts`.`ats_att_id` AS `ats_att_id`,`callouts`.`ats_src_id` AS `ats_src_id`,`callouts`.`ats_que_id` AS `ats_que_id`,`callouts`.`ats_start_ts` AS `ats_start_ts`,`callouts`.`ats_end_ts` AS `ats_end_ts`,`callouts`.`ats_session_id` AS `ats_session_id`,`callouts`.`ats_id` AS `ats_id`,`callouts`.`src_id` AS `src_id`,`callouts`.`src_centre_name` AS `src_centre_name`,`callouts`.`src_centre_desc` AS `src_centre_desc`,`callouts`.`src_active` AS `src_active` from `callouts` where (`callouts`.`ats_que_id` = 3)");
        
          
        //waiting_list
        DB::statement("CREATE OR REPLACE VIEW `waiting_list` AS select `registrations`.`reg_id` AS `reg_id`,`registrations`.`reg_first_name` AS `reg_first_name`,`registrations`.`reg_last_name` AS `reg_last_name`,`registrations`.`reg_email` AS `reg_email`,`registrations`.`reg_mob` AS `reg_mob`,`registrations`.`reg_dob` AS `reg_dob`,(case when ((select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) between 1 and 3) then 3 when ((select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) > (select `config_vars`.`con_value` from `config_vars` where (`config_vars`.`con_name` = 'NO_SHOW_MAX_ATTEMPTS'))) then 0 when (truncate(((to_days(str_to_date(concat(year(curdate()),'-AUG-31'),'%Y-%b-%d')) - to_days(`registrations`.`reg_dob`)) / 365),0) < 19) then 1 when (truncate(((to_days(str_to_date(concat(year(curdate()),'-AUG-31'),'%Y-%b-%d')) - to_days(`registrations`.`reg_dob`)) / 365),0) > 19) then 2 end) AS `que_id`,(select count(0) AS `no_show_count` from `assignments` where ((`assignments`.`asn_status` = 'NOS') and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))) AS `no_show_count`,`registrations`.`reg_created_ts` AS `reg_created_ts`,(select max(`assignments`.`asn_created_ts`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`)) AS `last_activity_ts` from `registrations` where (not(`registrations`.`reg_id` in (select `assignments`.`asn_reg_id` from `assignments` where ((`assignments`.`asn_status` in ('COM','FAI')) or isnull(`assignments`.`asn_status`))))) order by `registrations`.`reg_created_ts`,(select max(`assignments`.`asn_created_ts`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))");
        
        //queue_missed_appointments
        DB::statement("CREATE OR REPLACE VIEW `queue_missed_appointments` AS select `waiting_list`.`reg_id` AS `reg_id`,`waiting_list`.`reg_first_name` AS `reg_first_name`,`waiting_list`.`reg_last_name` AS `reg_last_name`,`waiting_list`.`reg_email` AS `reg_email`,`waiting_list`.`reg_mob` AS `reg_mob`,`waiting_list`.`reg_dob` AS `reg_dob`,`waiting_list`.`que_id` AS `que_id`,`waiting_list`.`no_show_count` AS `no_show_count`,`waiting_list`.`reg_created_ts` AS `reg_created_ts`,`waiting_list`.`last_activity_ts` AS `last_activity_ts` from `waiting_list` where (`waiting_list`.`que_id` = 3) order by `waiting_list`.`last_activity_ts`");
        
        //queue_19_plus
        DB::statement("CREATE OR REPLACE VIEW `queue_19_plus` AS select `waiting_list`.`reg_id` AS `reg_id`,`waiting_list`.`reg_first_name` AS `reg_first_name`,`waiting_list`.`reg_last_name` AS `reg_last_name`,`waiting_list`.`reg_email` AS `reg_email`,`waiting_list`.`reg_mob` AS `reg_mob`,`waiting_list`.`reg_dob` AS `reg_dob`,`waiting_list`.`que_id` AS `que_id`,`waiting_list`.`no_show_count` AS `no_show_count`,`waiting_list`.`reg_created_ts` AS `reg_created_ts`,`waiting_list`.`last_activity_ts` AS `last_activity_ts` from `waiting_list` where (`waiting_list`.`que_id` = 2)");
        
        //queue_16_to_18
        DB::statement("CREATE OR REPLACE VIEW `queue_16_to_18` AS select `waiting_list`.`reg_id` AS `reg_id`,`waiting_list`.`reg_first_name` AS `reg_first_name`,`waiting_list`.`reg_last_name` AS `reg_last_name`,`waiting_list`.`reg_email` AS `reg_email`,`waiting_list`.`reg_mob` AS `reg_mob`,`waiting_list`.`reg_dob` AS `reg_dob`,`waiting_list`.`que_id` AS `que_id`,`waiting_list`.`no_show_count` AS `no_show_count`,`waiting_list`.`reg_created_ts` AS `reg_created_ts`,`waiting_list`.`last_activity_ts` AS `last_activity_ts` from `waiting_list` where (`waiting_list`.`que_id` = 1)");
        
        //no_shows
        DB::statement("CREATE OR REPLACE VIEW `no_shows` AS select `waiting_list`.`reg_id` AS `reg_id`,`waiting_list`.`reg_first_name` AS `reg_first_name`,`waiting_list`.`reg_last_name` AS `reg_last_name`,`waiting_list`.`reg_email` AS `reg_email`,`waiting_list`.`reg_mob` AS `reg_mob`,`waiting_list`.`reg_dob` AS `reg_dob`,`waiting_list`.`que_id` AS `que_id`,`waiting_list`.`no_show_count` AS `no_show_count`,`waiting_list`.`reg_created_ts` AS `reg_created_ts`,`waiting_list`.`last_activity_ts` AS `last_activity_ts` from `waiting_list` where (`waiting_list`.`que_id` = 0)");
        
        //in_active_service_desks
        DB::statement("CREATE OR REPLACE VIEW `in_active_service_desks` AS select `service_desks`.`src_id` AS `src_id`,`service_desks`.`src_centre_name` AS `src_centre_name`,`service_desks`.`src_centre_desc` AS `src_centre_desc`,`service_desks`.`src_active` AS `src_active` from `service_desks` where ((`service_desks`.`src_active` = 'Y') and (not(`service_desks`.`src_id` in (select `active_service_desks`.`src_id` from `active_service_desks`))))");
        
        //in_active_attendants
        DB::statement("CREATE OR REPLACE VIEW `in_active_attendants` AS select `attendants`.`att_id` AS `att_id`,`attendants`.`att_email` AS `att_email`,`attendants`.`att_first_name` AS `att_first_name`,`attendants`.`att_second_name` AS `att_second_name`,`attendants`.`att_active` AS `att_active` from `attendants` where (not(`attendants`.`att_id` in (select `active_service_desks`.`att_id` from `active_service_desks`)))");
        
        //feed_queue_missed_appointments
        DB::statement("CREATE OR REPLACE VIEW `feed_queue_missed_appointments` AS select concat(`queue_missed_appointments`.`reg_first_name`,' ',`queue_missed_appointments`.`reg_last_name`) AS `Enrollee`,cast(`queue_missed_appointments`.`reg_created_ts` as time) AS `Expected Enrol Time` from `queue_missed_appointments`");
        
        //feed_queue_16_to_18
        DB::statement("CREATE OR REPLACE VIEW `feed_queue_16_to_18` AS select concat(`queue_16_to_18`.`reg_first_name`,' ',`queue_16_to_18`.`reg_last_name`) AS `Enrollee`,NULL AS `Expected Enrol Time` from `queue_16_to_18`");
        
        //feed_queue_19_plus
        DB::statement("CREATE OR REPLACE VIEW `feed_queue_19_plus` AS select concat(`queue_19_plus`.`reg_first_name`,' ',`queue_19_plus`.`reg_last_name`) AS `Enrollee`,cast(`queue_19_plus`.`reg_created_ts` as time) AS `Expected Enrol Time` from `queue_19_plus`");
        
        //feed_callouts
        DB::statement("CREATE OR REPLACE VIEW `feed_callouts` AS select concat(`callouts`.`reg_first_name`,' ',`callouts`.`reg_last_name`) AS `Enrollee`,concat('Go to ',`callouts`.`src_centre_name`) AS `&nbsp;` from `callouts`");
        
        //dash_no_shows
        DB::statement("CREATE OR REPLACE VIEW `dash_no_shows` AS select concat(`no_shows`.`reg_first_name`,' ',`no_shows`.`reg_last_name`) AS `Enrollee`,date_format(`no_shows`.`reg_dob`,'%d/%m/%Y') AS `DOB`,`no_shows`.`reg_email` AS `Email`,`no_shows`.`reg_mob` AS `Mobile No`,cast(`no_shows`.`reg_created_ts` as time) AS `Registration Time`,`no_shows`.`no_show_count` AS `Call count`,cast(`no_shows`.`last_activity_ts` as time) AS `Last Call Time` from `no_shows`");
        
        //dash_missed_appointments
        DB::statement("CREATE OR REPLACE VIEW `dash_missed_appointments` AS select concat(`queue_missed_appointments`.`reg_first_name`,' ',`queue_missed_appointments`.`reg_last_name`) AS `Enrollee`,date_format(`queue_missed_appointments`.`reg_dob`,'%d/%m/%Y') AS `DOB`,ifnull(`queue_missed_appointments`.`reg_email`,'') AS `Email`,ifnull(`queue_missed_appointments`.`reg_mob`,'') AS `Mobile No`,cast(`queue_missed_appointments`.`reg_created_ts` as time) AS `Registration Time`,timediff(now(),`queue_missed_appointments`.`reg_created_ts`) AS `Waiting Time` from `queue_missed_appointments`");
        
        //dash_enrolling_now
        DB::statement("CREATE OR REPLACE VIEW `dash_enrolling_now` AS select concat(`current_enrolments`.`reg_first_name`,' ',`current_enrolments`.`reg_last_name`) AS `Enrollee`,date_format(`current_enrolments`.`reg_dob`,'%d/%m/%Y') AS `DOB`,(select `service_desks`.`src_centre_name` from `service_desks` where (`service_desks`.`src_id` = `current_enrolments`.`ats_src_id`)) AS `Service Desk`,(select `queues`.`que_name` from `queues` where (`queues`.`que_id` = `current_enrolments`.`ats_que_id`)) AS `Queue`,cast(`current_enrolments`.`reg_created_ts` as time) AS `Registration Time`,timediff(now(),`current_enrolments`.`asn_created_ts`) AS `Wait Time` from `current_enrolments`");
        
        //dash_active_service_desks
        DB::statement("CREATE OR REPLACE VIEW `dash_active_service_desks` AS select `active_service_desks`.`src_centre_name` AS `Service Desk`,cast(`active_service_desks`.`ats_start_ts` as time) AS `Start Time`,timediff(now(),`active_service_desks`.`ats_start_ts`) AS `Active Time`,concat(`active_service_desks`.`att_first_name`,' ',`active_service_desks`.`att_second_name`) AS `Attendant` from `active_service_desks`");
        
        //dash_19_plus
        DB::statement("CREATE OR REPLACE VIEW `dash_19_plus` AS select concat(`queue_19_plus`.`reg_first_name`,' ',`queue_19_plus`.`reg_last_name`) AS `Enrollee`,date_format(`queue_19_plus`.`reg_dob`,'%d/%m/%Y') AS `DOB`,ifnull(`queue_19_plus`.`reg_email`,'') AS `Email`,ifnull(`queue_19_plus`.`reg_mob`,'') AS `Mobile No`,cast(`queue_19_plus`.`reg_created_ts` as time) AS `Registration Time`,timediff(now(),`queue_19_plus`.`reg_created_ts`) AS `Waiting Time` from `queue_19_plus`");
        
        //dash_16_to_18
        DB::statement("CREATE OR REPLACE VIEW `dash_16_to_18` AS select concat(`queue_16_to_18`.`reg_first_name`,' ',`queue_16_to_18`.`reg_last_name`) AS `Enrollee`,date_format(`queue_16_to_18`.`reg_dob`,'%d/%m/%Y') AS `DOB`,ifnull(`queue_16_to_18`.`reg_email`,'') AS `Email`,ifnull(`queue_16_to_18`.`reg_mob`,'') AS `Mobile No`,cast(`queue_16_to_18`.`reg_created_ts` as time) AS `Registration Time`,timediff(now(),`queue_16_to_18`.`reg_created_ts`) AS `Waiting Time` from `queue_16_to_18`");
        
        //summary
        DB::statement("CREATE OR REPLACE VIEW `summary` AS select (select count(0) from `assignments` where (`assignments`.`asn_status` = 'COM')) AS `enrolled_count`,(select count(0) from `assignments` where (`assignments`.`asn_status` = 'FAI')) AS `failed_enrolled_count`,(select round(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`assignments`.`asn_created_ts`)) / 60)),2) AS `avg_enrolment_mins` from `assignments` where (`assignments`.`asn_completed_ts` is not null)) AS `avg_enrolment_mins`,(select round(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`registrations`.`reg_created_ts`)) / 60)),2) AS `avg_wait_mins` from (`assignments` join `registrations`) where ((`assignments`.`asn_reg_id` = `registrations`.`reg_id`) and (`assignments`.`asn_id` = (select min(`assignments`.`asn_id`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`))))) AS `avg_wait_mins`");
        
        //summary_avg_enrol_time_by_queue
        DB::statement("CREATE OR REPLACE VIEW `summary_avg_enrol_time_by_queue` AS select round(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`assignments`.`asn_created_ts`)) / 60)),2) AS `avg_enrolment_mins`,`service_attendant_sessions`.`ats_que_id` AS `que_id`,`queues`.`que_name` AS `que_name` from ((`assignments` join `service_attendant_sessions`) join `queues`) where ((`assignments`.`asn_ats_id` = `service_attendant_sessions`.`ats_id`) and (`service_attendant_sessions`.`ats_que_id` = `queues`.`que_id`) and (`assignments`.`asn_completed_ts` is not null)) group by `service_attendant_sessions`.`ats_que_id`");
        
        //summary_avg_wait_time_by_queue
        DB::statement("CREATE OR REPLACE VIEW `summary_avg_wait_time_by_queue` AS select round(avg((abs(timestampdiff(SECOND,`assignments`.`asn_completed_ts`,`registrations`.`reg_created_ts`)) / 60)),2) AS `avg_wait_mins`,`service_attendant_sessions`.`ats_que_id` AS `que_id`,`queues`.`que_name` AS `que_name` from (((`assignments` join `registrations`) join `service_attendant_sessions`) join `queues`) where ((`assignments`.`asn_ats_id` = `service_attendant_sessions`.`ats_id`) and (`service_attendant_sessions`.`ats_que_id` = `queues`.`que_id`) and (`assignments`.`asn_reg_id` = `registrations`.`reg_id`) and (`assignments`.`asn_id` = (select min(`assignments`.`asn_id`) from `assignments` where (`assignments`.`asn_reg_id` = `registrations`.`reg_id`)))) group by `service_attendant_sessions`.`ats_que_id`");
        
        //summary_enrolled_by_queue
        DB::statement("CREATE OR REPLACE VIEW `summary_enrolled_by_queue` AS select sum(1) AS `enrolled_count`,`service_attendant_sessions`.`ats_que_id` AS `que_id`,`queues`.`que_name` AS `que_name` from ((`assignments` join `service_attendant_sessions`) join `queues`) where ((`assignments`.`asn_ats_id` = `service_attendant_sessions`.`ats_id`) and (`service_attendant_sessions`.`ats_que_id` = `queues`.`que_id`) and (`assignments`.`asn_status` = 'COM')) group by `service_attendant_sessions`.`ats_que_id`");
        
          //summary_unable_to_enrol_by_queue
        DB::statement("CREATE OR REPLACE VIEW `summary_unable_to_enrol_by_queue` AS select sum(1) AS `unable_to_enrol`,`service_attendant_sessions`.`ats_que_id` AS `que_id`,`queues`.`que_name` AS `que_name` from ((`assignments` join `service_attendant_sessions`) join `queues`) where ((`assignments`.`asn_ats_id` = `service_attendant_sessions`.`ats_id`) and (`service_attendant_sessions`.`ats_que_id` = `queues`.`que_id`) and (`assignments`.`asn_status` = 'FAI')) group by `service_attendant_sessions`.`ats_que_id`");
      
        
        //summary_by_queue
        DB::statement("CREATE OR REPLACE VIEW `summary_by_queue` AS select `a`.`que_id` AS `que_id`,`a`.`que_name` AS `que_name`,`a`.`avg_enrolment_mins` AS `avg_enrolment_mins`,`b`.`avg_wait_mins` AS `avg_wait_mins`,`c`.`enrolled_count` AS `enrolled_count`,`d`.`unable_to_enrol` AS `unable_to_enrol` from (((`summary_avg_enrol_time_by_queue` `a` join `summary_avg_wait_time_by_queue` `b`) join `summary_enrolled_by_queue` `c`) join `summary_unable_to_enrol_by_queue` `d`) where ((`a`.`que_id` = `b`.`que_id`) and (`b`.`que_id` = `c`.`que_id`) and (`c`.`que_id` = `d`.`que_id`))");
        
         
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
