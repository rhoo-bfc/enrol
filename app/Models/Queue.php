<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $primaryKey = 'que_id';
    
    
    /**
     * Gets the number of enrollee currently in a queue.
     * 
     * @param type $queueName string
     * @return type integer
     */
    static public function getQueueSize( $queueName ) {
            
           $result = false;
           if ( $queueName === 'feed_queue_16_to_18' ) {
           
            $result = 
                    \DB::select('SELECT count(*) rows
                                  FROM feed_queue_16_to_18', [ ] );        
            
           } else if ( $queueName === 'feed_queue_19_plus' ) {
               
            $result = 
                    \DB::select('SELECT count(*) rows
                                  FROM feed_queue_19_plus', [ ] );   
               
           } else if ( $queueName === 'feed_queue_missed_appointments' ) {
               
            $result = 
                    \DB::select('SELECT count(*) rows
                                  FROM feed_queue_missed_appointments', [ ] );   
           }
           
           return ( $result && isset($result[0]->rows) ) ? (int) $result[0]->rows : false;
           
    }
    
    static public function getQueuePosition( $regId ) {
        
        $sql = "SELECT count(*) c
                  FROM waiting_list
                 WHERE last_activity_ts < (SELECT max(last_activity_ts) FROM waiting_list WHERE reg_id = ?)
                   AND que_id           = (SELECT que_id FROM waiting_list WHERE reg_id = ?)";
        
        $result = \DB::select($sql,[ $regId, $regId ]);
        
        return ( isset($result[0]->c) ? $result[0]->c : 0 );
        
    }
	
}