<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $table       = 'messages';
    
    protected $primaryKey  = 'msg_id';
	
    protected $fillable    = ['msg_reg_id','msg_que_id','msg_message','msg_created_ts'];

    public    $timestamps  = false;
    
    private function alreadySent( $regId, $queId, $mtpId ) {
        
        return ( \DB::table('messages')->
                      where('msg_reg_id', '=', $regId )->
                      where('msg_que_id', '=', $queId )->
                      where('msg_mtp_id', '=', $mtpId )->
                      count() === 0 ) ? false : true;
    }
    
    public function sendSmsMessage( $enrolleeDetails, $mtpId = 1 ) {
        
        if ( false === $this->alreadySent( $enrolleeDetails['regId'] , $enrolleeDetails['queId'], $mtpId ) ) {
                        
            $smsMessage = new \App\Models\Message();
            $smsMessage->msg_reg_id     = $enrolleeDetails['regId'];
            $smsMessage->msg_que_id     = ($enrolleeDetails['queId'] === NULL) ? 
                                          \DB::raw('NULL') : $enrolleeDetails['queId'];
            $smsMessage->msg_mtp_id     = $mtpId;
            $smsMessage->msg_created_ts = \DB::raw('NOW()');
            
            $smsMessage->msg_params = serialize( [] );
            if ( isset( $enrolleeDetails['params'] ) ) {
                $smsMessage->msg_params = serialize( $enrolleeDetails['params'] );
            }
            
            $smsMessage->save();
            
            return true;
                        
        }  
        
        return false;
    }
	
}