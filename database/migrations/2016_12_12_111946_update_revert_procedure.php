<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRevertProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::connection()->getPdo()->exec('DROP PROCEDURE IF EXISTS revert');
        DB::connection()->getPdo()->exec("CREATE PROCEDURE `revert`(IN p_reg_id INT, 
													 IN POSITION CHAR(1),
													 IN AUTO_ASSIGN CHAR(1))
revert:BEGIN
   
   DECLARE v_ats_id INTEGER;
   DECLARE v_debug TEXT;
   
   SET v_debug = '';
   
   
   INSERT INTO assignments_archive
   (SELECT * FROM assignments WHERE asn_completed_ts IS not null AND asn_reg_id = p_reg_id);
   SET v_debug = concat(v_debug, ' ', 'ARCHIVED ASSIGNMENTS');

   DELETE FROM assignments WHERE asn_completed_ts IS NOT NULL AND asn_reg_id = p_reg_id;
   SET v_debug = concat(v_debug, '-', 'DELECTED CURRENT ASSIGNMENTS');

   /**
    * Assign to a service desk if one is free
    *
    */
   IF AUTO_ASSIGN = 'Y' THEN
	  
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
             SET v_debug = concat(v_debug, '-', 'ASSIGNED TO SERVICE DESK');
             SELECT v_debug;
		     LEAVE revert;
		ELSE
           
			SET v_debug = concat(v_debug, '-', 'UNABLE TO ASSIGN TO SERVICE DESK');
	    END IF;
    
   END IF;

   /**
	* Move to the front of the queue
	*
	**/
   IF UPPER(POSITION) = 'F' THEN

	  UPDATE registrations
		 SET reg_created_ts = (SELECT DATE_SUB(MIN(last_activity_ts), INTERVAL 1 hour)  
								 FROM waiting_list 
								WHERE que_id = ( SELECT MAX(que_id) 
												   FROM waiting_list 
												  WHERE reg_id = p_reg_id ))
	   WHERE reg_id         = p_reg_id;
	   SET v_debug = concat(v_debug, '-', 'PLACED AT FRONT OF QUEUE');

   END IF;
	
   /**
	* Move to the back of the queue
	*
	**/
   IF UPPER(POSITION) = 'B' THEN

	  UPDATE registrations
		 SET reg_created_ts = now()
	   WHERE reg_id         = p_reg_id;
	   SET v_debug = concat(v_debug, '-', 'PLACED AT BACK OF QUEUE');

   END IF;

   IF ( UPPER(POSITION) != 'B' AND UPPER(POSITION) != 'F' ) OR (POSITION IS NULL) THEN
	   
	 SET v_debug = concat(v_debug, '-', 'PLACED BACK IN THE QUEUE AT ORIGINAL POSITION');
   END IF;


   SELECT v_debug;

END");
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
