<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/*
 * Attendant model
 * 
 */
class Attendant extends Model
{
    protected $table   = 'attendants';
	
    protected $primaryKey = 'att_id';
	
    protected $fillable = ['att_id','att_email','att_first_name','att_second_name','att_active'];

    public    $timestamps = false;
    
        /**
         * Logs the attendant out of a service desk
         * 
         * @return boolean
         */
	public function logout() {
		
		$affectedRows = \DB::update( 'UPDATE service_attendant_sessions
   				                 SET ats_end_ts = NOW()
 				               WHERE ats_end_ts IS NULL
   				                 AND ats_att_id = ?', [ $this->att_id ] );
						
		return (boolean) ( $affectedRows ? true : false );
			
	}
	
        /**
         * Gets all attendants not currently assigned to a service desk
         * 
         * @return array 
         */
	public static function getFreeAttendants() {
			
		$results = \DB::select("SELECT att_id,
					       att_email,
					       att_first_name,
					       att_second_name,
					       att_active
				          FROM in_active_attendants WHERE att_active = 'Y'");
							  
		$temp = array();
		foreach( $results as $result ) {
			
                    $temp[$result->att_id] = $result->att_first_name . ' ' . $result->att_second_name;
		}
		
		return $temp;				 			
	}
	
}