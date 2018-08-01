<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
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
        
      DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS log_stats');
      
      DB::connection()->getPdo()->exec('CREATE DEFINER=`root`@`localhost` PROCEDURE `log_stats`()
BEGIN

	INSERT INTO rolling_summary
	SELECT enrolled_count,
		   failed_enrolled_count,
		   avg_enrolment_mins,
		   avg_wait_mins,
		   NOW()
	  FROM enrol.summary;


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
        DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS clear_down');
        DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS log_stats');
    }
}
