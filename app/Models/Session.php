<?php

namespace App\Models;

class Session
{
	/**
         * Clears all attendant sessions (log all the attendants out)
         * 
         */
	public static function clearAllSessions(  ) {
            
            return \DB::table('service_attendant_sessions')
                    ->where('ats_end_ts', NULL )
                    ->update( ['ats_end_ts' => \DB::raw('NOW()') ] );
            
        }
        
        /**
         * Remove any enrollees that have been assigned to a service desk that 
         * is now closed.  Puts them back in the queue, should go straight to 
         * the front.
         * 
         */
        public static function clearClosedAssignments( ) {
            
            return \DB::update('DELETE
                                 FROM assignments
                                WHERE EXISTS (SELECT *
                                                FROM service_attendant_sessions
                                               WHERE ats_end_ts IS NOT NULL
                                                 AND ats_id = asn_ats_id)
                                  AND asn_status IS NULL');
            
        }
        
        /**
         * Clears a session for a attendant
         * 
         * @param int $attId
         * @return int
         */
        public static function clearSessionsByAttendant( $attId ) {
            
            return \DB::table('service_attendant_sessions')
                    ->where('ats_end_ts', NULL )
                    ->where('ats_att_id', $attId )
                    ->update( ['ats_end_ts' => \DB::raw('NOW()')] );
        }
        
        /**
         * Check for a valid session
         * 
         * @return boolean
         */
        public static function isValidSession() {
            
            $result = \DB::select("SELECT COUNT(*) session
                                     FROM service_attendant_sessions
                                    WHERE ats_session_id = ?
                                      AND ats_end_ts IS NULL", 
                                   [ \Request::session()->getId() ]
                                  );
            
            return  ( ($result[0]->session == 1 ) ? true : false );
            
        }
	
}