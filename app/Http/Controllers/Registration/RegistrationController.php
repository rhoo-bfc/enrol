<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
     
	 
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		$viewData = ['registration' => null ];
        return \View::make( 'pages.registration', $viewData );
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
	/*
         * Create a new enrollee registration model
         * and validate
         */	
        $newRegistration = new \App\Models\Registration();
        $validator = $newRegistration->validator( $request );
        
        /**
         * Failed validation, redisplay the registration form
         * 
         */
        if ( true === $validator->fails() ) {

                return redirect('registration/create')
                ->withErrors($validator)
                ->withInput();	
        }

        $newRegistration->reg_first_name = ucwords(strtolower($request->input('reg_first_name')));
        $newRegistration->reg_last_name  = ucwords(strtolower($request->input('reg_last_name')));
        $newRegistration->reg_email      = ( trim( $request->input('reg_email') ) === '' ? NULL : strtolower(trim( $request->input('reg_email') )) );
        
        $mobile = NULL;
        if ( trim( $request->input('reg_mob') ) ) {
            /**
             * Clean up and format the mobile number
             */
            $mobile = trim(\libphonenumber\PhoneNumberUtil::getInstance()->
                     format( \libphonenumber\PhoneNumberUtil::getInstance()->parse( trim( $request->input('reg_mob') ) , "GB") , \libphonenumber\PhoneNumberFormat::E164),'+');
        }
        $newRegistration->reg_mob        = $mobile;

        $newRegistration->reg_dob        = ( $request->input('reg_birth_year')   . '-' . 
                                             $request->input('reg_birth_month') . '-' . 
                                             $request->input('reg_birth_day') );
        
        /*
         * Save new enrollee
         */
        if ($newRegistration->save()) {

            /* Send registration confimrmation - SMS message if they have registered a mobile number */
            if ( $newRegistration->reg_mob ) {

                $enrollee = [

                    'regId' => $newRegistration->reg_id,
                    'queId' => NULL

                ];

                $smsMessage = new \App\Models\Message();
                $smsMessage->sendSmsMessage( $enrollee, $mtpId = 1 );
            }
        }

        /*
         * Check for free service desks
         */
        /*
        $allocation = new \App\Models\Allocate();           
        $freeServiceDesk = $allocation->getNextAvailableServiceDesks( $newRegistration->getDefaultQueue() );
         * 
         */
        
        $freeServiceDesk = false;
        
        /*
         * Free service desks, assign enrollee to service desk and send them to 
         * straight to service station.
         */
        if ( $freeServiceDesk ) {

            $allocation->createAllocation( $freeServiceDesk->ats_id, $newRegistration->reg_id );
            $message = trans('messages.reg_enrol_now', [ 'desk' => $freeServiceDesk->src_centre_name ] );
             
        /*
         * No free service desk, direct them to the waiting area
         * 
         */    
        } else {

            $message = trans('messages.reg_wait');
        }

        $data = [
          'message' => $message  
        ];

        return \View::make( 'pages.complete' , $data );
		
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
}