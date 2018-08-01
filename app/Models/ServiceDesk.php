<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDesk extends Model
{

    protected $table      = 'service_desks';
    
    protected $primaryKey = 'src_id';
	
    protected $fillable = ['src_id','src_centre_name','src_centre_desc'];

    public    $timestamps  = false;
	
	public static function getFreeServiceDesks() {
			
		$results = \DB::select('SELECT src_id,
						           src_centre_name,
						           src_centre_desc,
						           src_active
					          FROM in_active_service_desks');
							  
		$temp = array();
		foreach( $results as $result ) {
			
			$temp[$result->src_id] = $result->src_centre_name;
		}
		
		return $temp;				 
			
	}
	
}