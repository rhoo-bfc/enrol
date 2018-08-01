<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {	
    return \View::make( 'pages.home' );
})->middleware('status');

Route::get('/offline', function () {	
    return \View::make( 'pages.offline' );
});

Route::resource( 'registration', 'Registration\RegistrationController',
                 [ 'only' => ['index', 'create','store'] ] );
				 
Route::get('/queue/switch/{id}', function ($id) {
    
    $attendantSession = App\Models\AttendantSessions::find( Request::instance()->session()->get('ats_id') );
    $attendantSession->ats_que_id = $id;
    
    if( true === $attendantSession->save() ) {       
       Request::instance()->session()->put('ats_que_id', $attendantSession->ats_que_id  );
       
       $allocation = new \App\Models\Allocate();
       $enrollee = $allocation->getNextInWaitingList( Request::instance()->session()->get('ats_que_id') );
           
       if ( $enrollee ) {		
            $allocation->createAllocation( Request::instance()->session()->get('ats_id') , $enrollee->reg_id );
       }
       
       return response()->json( ['STATUS' => 'OK',  'ACTION' => 'REFRESH' ] );
    }
    
    return response()->json( ['STATUS' => 'ERROR' ] );
})->middleware(['status','attendant']);


Route::post('/enrollee/status/{id}', function ($id) {	

    if ( Request::instance()->has('status') ) {
        
        $status = SUBSTR(strtoupper( Request::instance()->get('status') ),0,3);
        $notes  = Request::instance()->get('notes',null);
        $reason = Request::instance()->get('reason',null);
        
        $assignment = \App\Models\Assignment::find($id);
        $assignment->asn_status       = $status;
        $assignment->asn_notes        = ( empty($notes) ? DB::raw('NULL') : $notes );
        $assignment->asn_reason_code  = ( empty($reason) ? DB::raw('NULL') : $reason );
        
        $action = '';
        if (
            ($assignment->asn_status === 'COM') || 
            ($assignment->asn_status === 'NOS') ||
            ($assignment->asn_status === 'FAI') 
           ) {

           $assignment->asn_completed_ts = DB::raw('NOW()');
           if ( $assignment->save() ) {
                 
               
                 /** 
                  * Send Txt message telling them they have missed appointment
                  */
                 if ( ($assignment->asn_status === 'NOS') ) {
                     
                     /* Check they have registered a telephone number */
                     $registration = \App\Models\Registration::find( $assignment->asn_reg_id );             
                     if ( $registration->reg_mob ) {
                     
                        $enrollee = [

                           'regId' => $assignment->asn_reg_id,
                           'queId' => \DB::table('service_attendant_sessions')->where('ats_id', $assignment->asn_ats_id )->get()[0]->ats_que_id
                       ];
                       /*
                       $smsMessage = new \App\Models\Message();
                       $smsMessage->sendSmsMessage( $enrollee, $mtpId = 3 );
                       */
                    }
                    
                }               
            
           }
           
        }
        if ($assignment->asn_stats === 'NEX'){

            $allocation = new \App\Models\Allocate();
            $enrollee = $allocation->getNextInWaitingList( Request::instance()->session()->get('ats_que_id') );

            if ( $enrollee ) {      
                $allocation->createAllocation( Request::instance()->session()->get('ats_id') , $enrollee->reg_id );
            }

           $action = 'REFRESH';

        } else {

            $assignment->save();
        }
        
        return response()->json( ['STATUS' => 'OK',  'ACTION' => $action ] );
    
    }
        
    return response()->json( ['STATUS' => 'ERROR' ] );
    
})->middleware(['status','attendant']);

Route::get('/attendant/signin', function () {	
	
	Request::instance()->session()->regenerate();
        
	$data = [
            'attendants'   => \App\Models\Attendant::getFreeAttendants(),
            'serviceDesks' => \App\Models\ServiceDesk::getFreeServiceDesks(),
            'queues'       => \App\Models\Queue::where('que_active', 'Y')->get()->lists('que_name','que_id')->all()
	];
	
        App\Models\Session::isValidSession();
        
	return \View::make( 'pages.signin' , $data );
})->middleware( ['status'] );
				 
Route::get('/attendant', function () {	
	
        $atsId = Request::instance()->session()->get('ats_id');
        $attId = Request::instance()->session()->get('ats_att_id');
        $srcId = Request::instance()->session()->get('ats_src_id');
        $queId = Request::instance()->session()->get('ats_que_id');
        
        try {
        
            $data = [
                'enrollee'  => \App\Models\Allocate::getCurrentEnrollee( $srcId, $attId ), 
                'desk'      => \App\Models\ServiceDesk::findOrFail($srcId),
                'attendant' => \App\Models\Attendant::findOrFail($attId),
                'queue'     => \App\Models\Queue::findOrFail($queId),
                'queues'    => \App\Models\Queue::where('que_active', 'Y')->get()->lists('que_name','que_id')->all()
            ];
            
        } catch (  \Illuminate\Database\Eloquent\ModelNotFoundException $e ) {
            
            return redirect('/');
        }
    
	return \View::make( 'pages.attendant' , $data );
})->middleware( 'attendant' );

Route::post('/signin', function () {	
	
	$signIn = new \App\Models\Signin();
	$validator = $signIn->validator( Request::instance() );
	
	if ( true === $validator->fails() ) {
		
		return redirect('/attendant/signin')
					->withErrors($validator)
					->withInput();	
	}
	
	App\Models\Session::clearSessionsByAttendant( Request::input('ats_att_id') );
        
	$ats_id = $signIn->assignToServiceDesk( Request::input('ats_att_id'), 
				                Request::input('ats_src_id'),
                                                Request::input('ats_que_id'),
				                Request::session()->getId() );
	
        Request::instance()->session()->put('ats_id', $ats_id );
	Request::instance()->session()->put('ats_att_id', Request::input('ats_att_id') );
	Request::instance()->session()->put('ats_src_id', Request::input('ats_src_id') );
        Request::instance()->session()->put('ats_que_id', Request::input('ats_que_id') );
        
        $allocation = new \App\Models\Allocate();
        $enrollee = $allocation->getNextInWaitingList( Request::instance()->session()->get('ats_que_id') );
           
        if ( $enrollee ) {		
            $allocation->createAllocation( Request::instance()->session()->get('ats_id') , $enrollee->reg_id );
        }
        
        return redirect('/attendant');
	
});

Route::get('/logout', function () {	
	
    if ( Request::instance()->session()->has('ats_att_id') && 
         Request::instance()->session()->has('ats_att_id') ) {

            $attId = Request::instance()->session()->get('ats_att_id');
            $srcId = Request::instance()->session()->get('ats_src_id');

            $attendant = \App\Models\Attendant::find( $attId );
            if ( true === $attendant->logout() ) {
                \App\Models\Session::clearClosedAssignments();
            }

            Request::instance()->session()->flush();
    }

    return redirect('/');	

});

Route::get('/dashboard', function () {  
    
    return \View::make( 'pages.dashboard' );
});

Route::get('/stats/{queue?}', function ( $queue = null ) {
    
    if ( null === $queue ) {
        return response()->json( DB::table( 'summary' )->get() );
    } else {     
        return response()->json( DB::table( 'summary_by_queue' )->where('que_id', $queue )->get() );
    }
       
})->where('queue', '[0-9]+');;


Route::get('/feed/{view}/{page?}/{offset?}/{rows?}', function ( $view, $page = 10, $offset = 0, $rows = 15 ) {
    
    if ( strtolower($view) === 'feed_queue_16_to_18' ) {
        return \App\Models\Position::getQueue( 'queue_16_to_18', 1, $offset, $rows  );
    }
    
    if ( strtolower($view) === 'feed_queue_19_plus' ) {    
        return \App\Models\Position::getQueue( 'queue_19_plus', 2, $offset, $rows  );
    }
    
    if ( strtolower($view) === 'feed_queue_missed_appointments' ) {
        return \App\Models\Position::getQueue( 'queue_missed_appointments', 3 );
    }
    
    return DB::table($view)->paginate(20);
    
})->where('page', '[0-9]+');

Route::get('/infoboard/{board}', function ( $board ) {
    
    $view = 'pages.infoboard' . $board;
    
    return \View::make( $view );
})->middleware(['status'])->where('board', '[0-9]+');

Route::get('/admin/config/{var}', function ( $var ) {
    
    $status = (boolean) DB::table('config_vars')
                          ->where('con_name', $var)
                          ->update(['con_value' => Request::input('val') ]);
    
    return response()->json( ['STATUS' => ( $status ? 'OK' : 'FA' ) ] );
    
});

Route::post('/admin/expire', function ( ) {
    
    return response()->json( [ 'STATUS'          => 'OK' , 
                               'CLEARED_SESSION'     => \App\Models\Session::clearAllSessions() ,
                               'CLEARED_ASSIGNMENTS' => \App\Models\Session::clearClosedAssignments() ] );
});

Route::get('/admin/expire/attendant/{id}', function ( $id ) {
    
    $rows = DB::table('service_attendant_sessions')
            ->where('ats_session_id', $id )
            ->update( ['ats_end_ts' => DB::raw('NOW()')] );
    
    return response()->json( [ 'STATUS'          => 'OK' , 
                               'ROWS'            => $rows,
                               'CLEARED_ASSIGNMENTS' => \App\Models\Session::clearClosedAssignments() ] );
});

Route::get('/admin/reinstate/{id}', function ( $id ) {
    
    $result = DB::statement('CALL revert(?)',[ $id ]);
    
    return response()->json( [ 'STATUS'          => $result === true ? 'OK' : 'FAILED' , 
                               'ROWS'            => 0 ] );
});

Route::get('/search', function () {
    
    return \View::make( 'pages.search' );
});