<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwitchQueue extends Model
{
    protected $table   = 'switch_queues';
	
    protected $primaryKey = 'swq_id';
	
    protected $fillable = [ 'swq_ats_session_id','swq_que_id','swq_created_ts','swq_actioned_ts' ];

    public    $timestamps = false;


    public static function isSwitchQueueAction($uuid) {

        $sq = self::where('swq_ats_session_id',$uuid)->whereNull('swq_actioned_ts')->get();

        return isset($sq[0]) ? $sq[0] : false;

    }

}