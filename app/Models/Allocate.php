<?php

namespace App\Models;

/**
 * Deals with the allocation of enrollee to service desks
 * 
 */
class Allocate
{
	/**
         * Get the current enrollee assigned to a service desk/attendant
         * 
         * @param int $srcId - service desk id
         * @param int $attId - attendent id
         * @return boolean|array
         */
	public static function getCurrentEnrollee( $srcId, $attId ) {
            
            $results = \DB::select('SELECT * '
                                   . 'FROM current_enrolments '
                                   . 'WHERE ats_src_id = ? AND ats_att_id = ?',
                                   [ $srcId, $attId ]
                                  );
            if ( true === isset($results[0]) ) {
			
		return $results[0];	
            }
            
            return false;
        }
        
        /**
         * 
         * 
         * @param int $queId - queue id
         * @return boolean
         */
        public function getNextInWaitingList( $queId ) {
		
		$results = \DB::select("SELECT reg_id,
                				       reg_first_name,
                                       reg_last_name,
                                       reg_email,
			                           reg_mob,
				                       reg_created_ts
		                          FROM waiting_list 
                                         WHERE que_id = ?
		                         LIMIT 1", [$queId] );
								 
		if ( true === isset($results[0]) ) {
			
			return $results[0];	
		}
		
		return false;		
	}
	
        /**
         * Gets the next available service desk for a queue
         * 
         * @param int $queId - queue id
         * @return boolean
         */
	public function getNextAvailableServiceDesks( $queId ) {
		
		$results = \DB::select("SELECT att_email,
                			       att_first_name,
                			       att_second_name,
                			       src_centre_name,
                			       src_centre_desc,
                			       src_id,
                                               ats_id,
                			       att_id
           				  FROM available_service_desks
                                         WHERE ats_que_id = ?
		  		         LIMIT 1", [$queId]);
								 
		if ( true === isset($results[0]) ) {
			
			return $results[0];	
		}
		
		return false;
	}
	
        /**
         * Allocate a enrollee to a service desk/attendant session
         * 
         * @param int $atsId - attendant session id
         * @param int $regId - registration id
         */
	public function createAllocation( $atsId, $regId ) {
		
		return \DB::table('assignments')->insert(
			[
			 'asn_ats_id'       => $atsId, 
			 'asn_reg_id'       => $regId,
			 'asn_status'       => NULL,
			 'asn_created_ts'   => \DB::raw('NOW()'),		
			 'asn_completed_ts' => NULL	 
			]
		);			
	}
	
}