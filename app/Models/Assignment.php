<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table   = 'assignments';
	
    protected $primaryKey = 'asn_id';
	
    protected $fillable = [ 'asn_status','asn_completed_ts' ];

    public    $timestamps = false;

}