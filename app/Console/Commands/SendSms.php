<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends SMS via gateway';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $messages = \DB::table('messages')->
                        where( 'msg_status',        '=' , 'RTS' )->
                        where( 'msg_sent_attempts', '<' , '3' )->
                        lockForUpdate()->
                        get();
        
        foreach ( $messages as $message ) {
            
           $enrollee = \App\Models\Registration::find($message->msg_reg_id);
           if (!$enrollee) {             
               continue;
           }
           
           $contents = \DB::table('message_types')->where('mtp_id', $message->msg_mtp_id )->get()[0]->mtp_message;
           
           $params = unserialize( $message->msg_params );
           foreach ($params as $key => $value ) {
               
               $contents = str_replace( strtoupper('!!' . $key . '!!') , strtolower($value), $contents  );
           }
           
           $sentResponse = \Sms::send( $enrollee['reg_mob'] ,'sms.message', [ 'name' => $enrollee['reg_first_name'], 'message' => $contents ] );
           
           $sent = false;
           if ( strtolower( preg_match('/^ID:([\s]{1})(.{1,})+/', $sentResponse->response ) ) ) {
               $sent = true;
           }
           
            \DB::table('messages')
               ->where('msg_id', $message->msg_id)
               ->update( [ 
                           'msg_gateway_response' => $sentResponse->response , 
                           'msg_sent_attempts'    => $message->msg_sent_attempts + 1, 
                           'msg_status'           => ( $sent ? 'SUC' : 'RTS' ),
                           'msg_sent_ts'          => ( $sent ? \DB::raw('NOW()') : \DB::raw('NULL') )
                   
                         ]);
           
        }
    }
}
