<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMessageArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('messages_archive', function ($table) {
            $table->binary('msg_params')->after('msg_gateway_response');
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
        msg_params,
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
	FROM `enrol`.`assignments`;
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        
        Schema::table('messages_archive', function ($table) {
            $table->dropColumn('msg_params');
	});
    }
}
