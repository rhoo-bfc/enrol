<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDeskSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:sdesk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $results = \DB::select("SELECT asn_reg_id reg_id, ats_que_id que_id, src_centre_name FROM enrol.callouts WHERE reg_mob" );
        
        foreach( $results as $result ) {
            
          $smsMessage = new \App\Models\Message();
          $smsMessage->sendSmsMessage( [ 'regId' => $result->reg_id , 'queId' => $result->que_id, 'params' => [ 'SERVICE_DESK' => $result->src_centre_name ] ], $mtpId = 4 ); 
        }
    }
}
