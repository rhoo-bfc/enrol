<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $primaryKey = 'reg_id';
    
    protected $table   = 'registrations';
	
    protected $fillable = ['reg_id','reg_first_name','reg_last_name','reg_email','reg_mob'];

    public $timestamps = false;
    
       private function isAlreadyRegistered( $foreame, $surname, $dob ) {
           
           $result = 
                   \DB::select("SELECT count(*) rows
                                 FROM registrations
                                WHERE lower(reg_first_name)         = trim(lower(?))
                                  AND lower(reg_last_name)          = trim(lower(?))
                                  AND DATE_FORMAT(reg_dob,'%d%m%Y') = ?", [$foreame, $surname, $dob ] );
           
           return ( $result[0]->rows == '0' ) ? true : false;
           
       }
    
       public function getAge() {
           
           return ( new \Carbon\Carbon( $this->reg_dob ) )->age;
       }
       
       public function getDefaultQueue() {
           
           return ( $this->getAge() < 19 ? 1 : 2 );
       }
	
	public function validator( $request ) {
            
                \Validator::extend('valid_dob', function($field,$value,$parameters){
                    
                $day   = trim( $value );
                $month = trim( $parameters[0] );
                $year  = trim( $parameters[1] );
                
                if ( empty($day) || empty($month) || empty($year) ) {                  
                    return false;
                }
                
                return checkdate($month, $day, $year);
                
               });
               
                \Validator::extend('already_registered', function($attribute, $value, $parameters, $validator) {
                    
                    return $this->isAlreadyRegistered( $parameters[0], $parameters[1], $parameters[2] );
                    
                });
                
                \Validator::extend('mobile_already_registered', function($attribute, $value, $parameters, $validator) {
                    
                    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                    $mobileNumber = $phoneUtil->parse($value, "GB");
                    
                    if ( 0 === \App\Models\Registration::where('reg_mob', trim($phoneUtil->format($mobileNumber, \libphonenumber\PhoneNumberFormat::E164),'+') )->count() ) {
                                           
                        return true;   
                    } 
                    return false;
                    
                });
                
                \Validator::extend('valid_mobile', function($attribute, $value, $parameters, $validator) {
                    
                    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                    $mobileNumber = $phoneUtil->parse($value, "GB");

                    try {
                        $mobileNumber = $phoneUtil->parse($value, "GB");
                        
                        if ( $phoneUtil->isValidNumber( $mobileNumber  ) ) {
                                           
                              return true;         
                        }                    
                        return false;
   
                    } catch (\libphonenumber\NumberParseException $e) {
                       return false;
                    }
                    
                });
                
                $request->replace( array_map( 'trim', $request->all() ) );
                
		$validator = \Validator::make( $request->all() , [
                    
                        'reg_email'       => 'email|unique:registrations',
                        'reg_mob'         => 'numeric|valid_mobile|mobile_already_registered',
                        'reg_birth_day'   => 'required|valid_dob:' . $request->input('reg_birth_month') . ',' . $request->input('reg_birth_year'),
                        'reg_first_name'  => 'required||regex:/^[\D]+$/u|max:255|already_registered:' . strtolower( $request->input('reg_first_name') ) . ',' 
                                                                   . strtolower( $request->input('reg_last_name') ) . ',' 
                                                                   . $request->input('reg_birth_day') . '' 
                                                                   . str_pad($request->input('reg_birth_month'),2,0, STR_PAD_LEFT) . ''
                                                                   . $request->input('reg_birth_year'),
                        'reg_last_name'   => 'required|max:255|regex:/^[\D]+$/u'
                    
                    ], [
                        
                        'reg_email.required'        => 'A valid email address must be entered',
                        'reg_mob.required'          => 'A valid mobile number must be entered',
                        'reg_first_name.required'   => 'Please enter your first name',
                        'reg_first_name.regex'      => 'First name must not contain digits',
                        'reg_last_name.required'    => 'Please enter your last name',
                        'reg_birth_day.required'    => 'Please enter the day of your birthday',
                        'reg_birth_month.required'  => 'Please enter the month of your birthday',
                        'reg_birth_year.required'   => 'Please enter the year of your birthday',
                        'reg_email.email'           => 'Please enter a valid email address',
                        'reg_mob.numeric'           => 'Mobile numbers must be numeric only',
                        'valid_dob'                 => 'Please enter a valid birthday',
                        'reg_email.unique'          => 'This email address already registered',
                        'reg_mob.unique'            => 'This mobile number has already been registered',
                        'already_registered'        => 'These enrollee details appear to have been already registered',
                        'valid_mobile'              => 'The mobile number you have entered does\'t appear to be valid',
                        'reg_last_name.regex'       => 'Last name must not contain digits',
                        'mobile_already_registered' => 'The mobile number has already been registered'
                        
                    ] 
                );
                


              return $validator;		
	}
}