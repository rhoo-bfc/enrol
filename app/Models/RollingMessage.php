<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RollingMessage extends Model
{

    protected $table      = 'rolling_messages';
    
    protected $primaryKey = 'rmg_id';
	
    protected $fillable = ['rmg_que_id','rmg_message','rmg_active'];

    public    $timestamps  = false;
	
}