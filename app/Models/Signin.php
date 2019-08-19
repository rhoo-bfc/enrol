<?php

namespace App\Models;

/**
 * 
 * Deals with assigning an attendant into a service desk
 */
class Signin
{
	
        /**
         * 
         * @param int $atsId - attendant id
         * @param int $srcId - service desk id
         * @param int $queId - queue id
         * @param string $sessionId - session id
         * @return type
         */
    	public function assignToServiceDesk( $atsId, $srcId, $queId, $sessionId ) {
		
            return \DB::table('service_attendant_sessions')->insertGetId(
                [
                 'ats_att_id'     => $atsId, 
                 'ats_src_id'     => $srcId,
                 'ats_que_id'     => $queId,
                 'ats_start_ts'   => date('Y-m-d H:i:s'),
                 'ats_end_ts'     => NULL,		
                 'ats_session_id' => $sessionId	 
                ]
             );	
	}
        
        
        /**
         * 
         * Validator for assigning a attendant to service desk 
         * 
         * @param object $request
         * @return object
         */
 	public function validator( $request ) {
		
            $validator = \Validator::make( $request->all() , [
                'ats_att_id' => 'required|integer',
                'ats_src_id' => 'required|integer',
                'ats_que_id' => 'required|integer',
            ], [
                'ats_att_id.required' => 'Please select attendant name',
                'ats_src_id.required' => 'Please select a service desk',
                'ats_que_id.required' => 'Please select a queue'
            ] 
            );

            return $validator;		
	}
	
	
}