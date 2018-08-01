<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendantSessions extends Model
{
    protected $table      = 'service_attendant_sessions';
	
    protected $primaryKey = 'ats_id';
	
    protected $fillable   = [ 'ats_que_id','ats_end_ts' ];

    public    $timestamps = false;

}