<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFunc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    Schema::table('assignments', function ($table) {
            $table->integer('asn_reason_code')->nullable();
    });
    
    Schema::table('assignments_archive', function ($table) {
            $table->integer('asn_reason_code')->nullable();
    });

    DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS clear_down');
    DB::connection()->getPdo()->exec('CREATE PROCEDURE `clear_down`()
BEGIN
	
	SET SQL_SAFE_UPDATES = 0;
	SET FOREIGN_KEY_CHECKS = 0; 
	
	INSERT INTO messages_archive
	SELECT
	msg_id,
	msg_reg_id,
	msg_que_id,
	msg_mtp_id,
	msg_created_ts,
	msg_sent_ts,
	msg_gateway_response,
	msg_sent_attempts,
	msg_status
	FROM messages;
	DELETE FROM messages;
	
	
    INSERT INTO assignments_archive
	SELECT
	asn_id,
	asn_ats_id,
	asn_reg_id,
	asn_status,
	asn_created_ts,
	asn_completed_ts,
	asn_notes,
	asn_reason_code
	FROM assignments;
	DELETE FROM assignments;

	INSERT INTO service_attendant_sessions_archive
	SELECT
	ats_id,
	ats_att_id,
	ats_src_id,
	ats_que_id,
	ats_start_ts,
	ats_end_ts,
	ats_session_id
	FROM service_attendant_sessions;
	DELETE FROM service_attendant_sessions;
	
	INSERT INTO registrations_archive
	SELECT
	reg_id,
	reg_first_name,
	reg_last_name,
	reg_dob,
	reg_email,
	reg_mob,
	reg_created_ts
	FROM registrations;
	DELETE FROM registrations;
	SET FOREIGN_KEY_CHECKS = 1;


END');
    
    DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `failed_enrollment` AS select `registrations`.`reg_id` AS `reg_id`,`registrations`.`reg_first_name` AS `reg_first_name`,`registrations`.`reg_last_name` AS `reg_last_name`,`registrations`.`reg_dob` AS `reg_dob`,`registrations`.`reg_email` AS `reg_email`,`registrations`.`reg_mob` AS `reg_mob`,`registrations`.`reg_created_ts` AS `reg_created_ts`,`assignments`.`asn_created_ts` AS `asn_created_ts`,`assignments`.`asn_completed_ts` AS `asn_completed_ts`,`assignments`.`asn_notes` AS `asn_notes`,`assignments`.`asn_reason_code` AS `asn_reason_code` from (`registrations` join `assignments`) where ((`assignments`.`asn_reg_id` = `registrations`.`reg_id`) and (`assignments`.`asn_status` = 'FAI'))");
        
    //
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dash_active_service_desks` AS select `active_service_desks`.`src_centre_name` AS `Service Desk`,cast(`active_service_desks`.`ats_start_ts` as time) AS `Start Time`,timediff(now(),`active_service_desks`.`ats_start_ts`) AS `Active Time`,concat(`active_service_desks`.`att_first_name`,' ',`active_service_desks`.`att_second_name`) AS `Attendant`,(select `queues`.`que_name` from `queues` where (`queues`.`que_id` = `active_service_desks`.`ats_que_id`)) AS `queue`,concat('<button data-expire=\"',`active_service_desks`.`ats_session_id`,'\" class=\"secondary button\">Expire</button>') AS `action` from `active_service_desks` order by (select `queues`.`que_name` from `queues` where (`queues`.`que_id` = `active_service_desks`.`ats_que_id`))");
            
        DB::statement("CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dash_failed_enrollments` AS select concat(`failed_enrollment`.`reg_first_name`,' ',`failed_enrollment`.`reg_last_name`) AS `ENROLLEE`,date_format(`failed_enrollment`.`reg_dob`,'%d/%m/%Y') AS `DOB`,ifnull(`failed_enrollment`.`reg_email`,'') AS `EMAIL`,ifnull(`failed_enrollment`.`reg_mob`,'') AS `MOBILE NO`,cast(`failed_enrollment`.`asn_created_ts` as time) AS `START TIME`,timediff(`failed_enrollment`.`asn_completed_ts`,`failed_enrollment`.`asn_created_ts`) AS `ENROL TIME`,`failed_enrollment`.`asn_notes` AS `REASON`,concat('<button data-revert=\"',`failed_enrollment`.`reg_id`,'\" class=\"secondary button\">Restore</button>') AS `Action` from `failed_enrollment`");

        DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS revert');
        DB::connection()->getPdo()->exec('CREATE PROCEDURE `revert`(IN p_reg_id INT)
BEGIN
   DECLARE v_ats_id INTEGER;

   INSERT INTO assignments_archive
   (SELECT * FROM assignments WHERE asn_completed_ts IS not null AND asn_reg_id = p_reg_id);

   DELETE FROM assignments WHERE asn_completed_ts IS NOT NULL AND asn_reg_id = p_reg_id;

   SELECT ats_id INTO v_ats_id FROM enrol.available_service_desks where ats_que_id = (
		SELECT MAX(que_id) from waiting_list where reg_id = p_reg_id
   );

   IF v_ats_id IS NOT NULL THEN
	
		SELECT v_ats_id;

		INSERT INTO assignments (asn_ats_id, 
							     asn_reg_id,
								 asn_status,
								 asn_created_ts,
								 asn_completed_ts) 
              VALUES (v_ats_id,
					  p_reg_id,
					  NULL,
					  NOW(),
					  NULL);

   END IF;

END');
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('assignments', function ($table) {
                $table->dropColumn('asn_reason_code');
        });

        Schema::table('assignments_archive', function ($table) {
            $table->dropColumn('asn_reason_code');
        });

        DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS clear_down');
        DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS revert');
        
    }
}
