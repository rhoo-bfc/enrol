<?php

namespace App\Models;

class Allocate
{
	
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
	
	public function createAllocation( $atsId, $regId ) {
		
		\DB::table('assignments')->insert(
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