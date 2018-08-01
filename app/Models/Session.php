<?php

namespace App\Models;

class Session
{
	
	public static function clearAllSessions(  ) {
            
            return \DB::table('service_attendant_sessions')
                    ->where('ats_end_ts', NULL )
                    ->update( ['ats_end_ts' => \DB::raw('NOW()') ] );
            
        }
        
        public static function clearClosedAssignments( ) {
            
            return \DB::update('DELETE
                                 FROM assignments
                                WHERE EXISTS (SELECT *
                                                FROM service_attendant_sessions
                                               WHERE ats_end_ts IS NOT NULL
                                                 AND ats_id = asn_ats_id)
                                  AND asn_status IS NULL');
            
        }
        
        public static function clearSessionsByAttendant( $attId ) {
            
            return \DB::table('service_attendant_sessions')
                    ->where('ats_end_ts', NULL )
                    ->where('ats_att_id', $attId )
                    ->update( ['ats_end_ts' => \DB::raw('NOW()')] );
        }
        
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