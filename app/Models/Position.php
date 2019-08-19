<?php

namespace App\Models;

class Position
{	
	/*
         * Average enrol time
         */
        private static $avgEnrolTime;
        
        /*
         * Holds the number of active service desks
         */
        private static $activeServiceDesks;
       
        /**
         * Gets the estimated enrol time
         * 
         * @param type $enrolTime
         * @return string
         */
        public static function getPredictedEnrolTime( $enrolTime ) {
            
            /**
             * Number of minutes before we send a txt message notification
             * to the enrollwee
             */
            $smsTriggerWaitMins = \DB::table('config_vars')->where('con_name', 'SMS_WAIT_TIME_MINS' )->get()[0]->con_value;
            
            /*
             * Get the number of active service desks
             */
            if ( self::$activeServiceDesks === null ) {
                self::$activeServiceDesks = (int) \DB::table('active_service_desks')->where('ats_que_id', $enrolTime['queId'] )->count();
            }
            
            /*
             * Determine the average enrol time
             * Either calculate the average enrol time based on the average time
             * taken of previous enrollees, 100 or more previous enrollees
             * Or use the default value for enrol time in the database
             */
            if ( self::$avgEnrolTime === null ) {
                
                if ( \DB::table('summary_enrolled_by_queue')->where('que_id', $enrolTime['queId'] )->count() === 0 ) {                   
                    
                    self::$avgEnrolTime = \DB::table('config_vars')->where('con_name', 'AVG_ENROL_TIME_MINS' )->get()[0]->con_value;
                } else if ( \DB::table('summary_enrolled_by_queue')->where('que_id', $enrolTime['queId'] )->get()[0]->enrolled_count > 100 ) {          
                    
                    self::$avgEnrolTime = ceil( \DB::table('summary_avg_enrol_time_by_queue')->where('que_id', $enrolTime['queId'] )->get()[0]->avg_enrolment_mins );   
                } else {                
                    
                    self::$avgEnrolTime = \DB::table('config_vars')->where('con_name', 'AVG_ENROL_TIME_MINS' )->get()[0]->con_value;
                }
            }
            
            $minutes = 0;
            /**
             * Check we have some 
             * active service desk
             */
            if ( self::$activeServiceDesks > 0 ) {
                
                /**
                 * Calculate the minutes to enrolment
                 */
                $minutes = ( ceil( $enrolTime['position'] / self::$activeServiceDesks ) ) * self::$avgEnrolTime;
                
                /**
                 * Front of the queue, so should be enrolling shortly                *  
                 */
                if ( $minutes <= self::$avgEnrolTime )  {
                    
                    if  ( 
                          ( $enrolTime['mobile'] === 'Y') /* && ( $enrolTime['waitTime'] > $smsTriggerWaitMins  ) */
                        ) {
                        
                        /**
                         * Send a sms message telling them they will enrol shortly
                         */
                        $smsMessage = new \App\Models\Message();
                        $smsMessage->sendSmsMessage( $enrolTime, $mtpId = 2 );
                        
                    }
                
                    $estimatedEnrolTime = '<span class="enrolShortly">Shortly</span>';
                    
                /**
                 * 
                 */    
                } else {
                    
                    if (
                        /* Have regsitered a mobile no */
                        ( $enrolTime['mobile'] === 'Y') &&
                        
                        ( 
                            /* Wait Time  less than/equal to 45 minutes */
                            ( ( $minutes <= 45 ) /** && ( $enrolTime['waitTime'] > $smsTriggerWaitMins ) **/ ) 

                            || 
                            
                            /* check queue length */
                            ( $enrolTime['position'] < 100 )
                        )
                               
                       ) {                     
                            
                            /**
                             * Send a sms message telling them they will enrol shortly
                             */
                            $smsMessage = new \App\Models\Message();
                            $smsMessage->sendSmsMessage( $enrolTime, $mtpId = 2 );
                        
                        }
                        
                    /**
                     * Estimate enrolment time
                     */
                    $enrolTime = \Carbon\Carbon::now()->addMinutes($minutes);
                    $enrolTime->subMinutes( ( $enrolTime->minute % 5 ) );
                    $estimatedEnrolTime = $enrolTime->format('g:i a');
                }
                    
            /**
             * No active service desks
             */        
            } else {
                
                $estimatedEnrolTime = 'NOT ACTIVE';
            }
            
            return $estimatedEnrolTime;
        }

    /**
     *
     *
     * @param type $view
     * @param type $queId
     * @param type $offset
     * @param type $rows
     * @return type
     */
    public static function getQueueAll( $view, $queId, $offset = 0, $rows = 15 ) {

        $results = \DB::select("SELECT @rownum:=@rownum+1 '&#35;', 
                                           CONCAT(reg_first_name, ' ', reg_last_name, ' (', DATE_FORMAT(reg_dob,'%d-%b'), ')') ' Enrollee - (Date of Birth)', 
                                           ROUND(TIME_TO_SEC(TIMEDIFF(NOW(),reg_created_ts))/60,0) 'wait_time',
                                           reg_id,
                                           IF(reg_mob,'Y','N') mobile,
                                           status,
                                           message,
                                           _order_tag
                                      FROM " . $view . " ,(SELECT @rownum:=?) r 
                                     WHERE 1=1 LIMIT ?,?" ,
            [ $offset, $offset, $rows  ]
        );

        $position = 'Expected Enrol Time';
        $temp = '&#35;';
        foreach( $results as &$result ) {

            if ( $result->_order_tag == -1 ) {
                $a = 'Enrollee - (Date of Birth)';
                $result->$a = '<span style="color:green;font-weight:bold;">' . $result->$a . '</span>' ;
                $result->status = '<span style="color:green;font-weight:bold;">' . $result->status . '</span>' ;
            }
            $enrolTime = [

                'position'  => $result->$temp,
                'queId'     => $queId,
                'waitTime'  => $result->wait_time,
                'regId'     => $result->reg_id,
                'mobile'    => $result->mobile

            ];

            $enrolTime = SELF::getPredictedEnrolTime( $enrolTime );
            $e = 'Enrol Time';
            $result->$e = $enrolTime;

            unset($result->_order_tag);
            unset($result->$temp);
            unset($result->wait_time);
            unset($result->message);
            unset($result->reg_id);
            unset($result->mobile);
        }

        return json_encode([ 'data' => $results ]);
    }

    /**
         * 
         * 
         * @param type $view
         * @param type $queId
         * @param type $offset
         * @param type $rows
         * @return type
         */
        public static function getQueue( $view, $queId, $offset = 0, $rows = 15 ) {
            
            $results = \DB::select("SELECT @rownum:=@rownum+1 '&#35;', 
                                           CONCAT(reg_first_name, ' ', reg_last_name, ' (', DATE_FORMAT(reg_dob,'%d-%b'), ')') ' Enrollee - (Date of Birth)', 
                                           ROUND(TIME_TO_SEC(TIMEDIFF(NOW(),reg_created_ts))/60,0) 'wait_time',
                                           reg_id,
                                           IF(reg_mob,'Y','N') mobile
                                      FROM " . $view . " ,(SELECT @rownum:=?) r 
                                     WHERE 1=1 LIMIT ?,?" ,
                                     [ $offset, $offset, $rows  ]
                                  );
            
            $position = 'Expected Enrol Time';
            $temp = '&#35;';
            foreach( $results as &$result ) {              
                
                $enrolTime = [
                  
                    'position'  => $result->$temp,
                    'queId'     => $queId,
                    'waitTime'  => $result->wait_time,
                    'regId'     => $result->reg_id,
                    'mobile'    => $result->mobile
               
                ];
                
                $enrolTime = SELF::getPredictedEnrolTime( $enrolTime );
                
                unset($result->wait_time);
                unset($result->reg_id);
                unset($result->mobile);
            } 
            
            return json_encode([ 'data' => $results ]);
        }	
        
}