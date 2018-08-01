<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchiveTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //     
        Schema::create('messages_archive', function (Blueprint $table) {
            $table->integer('msg_id')->unsigned();
            $table->integer('msg_reg_id')->unsigned();
            $table->integer('msg_que_id')->unsigned();
            $table->integer('msg_mtp_id')->unsigned();
            $table->datetime('msg_created_ts');
            $table->datetime('msg_sent_ts')->nullable();
            $table->text('msg_gateway_response')->nullable();
            $table->integer('msg_sent_attempts');
            $table->string('msg_status',3);        
	});
        
        Schema::create('assignments_archive', function (Blueprint $table) {
            $table->integer('asn_id')->unsigned();
            $table->integer('asn_ats_id')->unsigned();
            $table->integer('asn_reg_id')->unsigned();
            $table->string('asn_status',3);
            $table->datetime('asn_created_ts');
            $table->datetime('asn_completed_ts')->nullable();
            $table->text('asn_notes')->nullable();
	});
        
        Schema::create('service_attendant_sessions_archive', function (Blueprint $table) {
            $table->integer('ats_id')->unsigned();
            $table->integer('ats_att_id')->unsigned();
            $table->integer('ats_src_id')->unsigned();
            $table->integer('ats_que_id')->unsigned();
            $table->datetime('ats_start_ts');
            $table->datetime('ats_end_ts')->nullable();
            $table->string('ats_session_id',255);
	});
        
        Schema::create('registrations_archive', function (Blueprint $table) {
            $table->integer('reg_id')->unsigned();
            $table->string('reg_first_name',255);
            $table->string('reg_last_name',255);
            $table->date('reg_dob');
            $table->string('reg_email',255)->nullable();
            $table->string('reg_mob')->nullable();
            $table->datetime('reg_created_ts');
	});
        
         DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS clear_down');
        
        DB::connection()->getPdo()->exec('CREATE DEFINER=`root`@`localhost` PROCEDURE `clear_down`()
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
	asn_notes
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
        
        DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS clear_down');
        
        DB::connection()->getPdo()->exec('CREATE DEFINER=`root`@`localhost` PROCEDURE `clear_down`()
BEGIN
	
	SET FOREIGN_KEY_CHECKS = 0; 
	TRUNCATE TABLE messages;
	TRUNCATE TABLE assignments;
	TRUNCATE TABLE service_attendant_sessions;
	TRUNCATE TABLE registrations;
	SET FOREIGN_KEY_CHECKS = 1;


END');

        //
        Schema::drop('messages_archive');
	Schema::drop('assignments_archive');
	Schema::drop('service_attendant_sessions_archive');
	Schema::drop('registrations_archive');
    }
}
