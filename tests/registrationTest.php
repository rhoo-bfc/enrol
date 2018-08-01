<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class registrationTest extends TestCase
{
    
    private function randomString() {
        
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVWXYZ-';
        
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 10; $i++) {
             $string .= $characters[mt_rand(0, $max)];
        }
        
        return $string;
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegistration()
    {
        $this->visit('/registration/create')
         ->type('Andrew', 'reg_first_name')
         ->type('Hetherington', 'reg_last_name')
         ->press('Register for Enrolment')
         ->seePageIs('/registration/create');
        
        for ( $i=1; $i < 28; $i++ ) {
            
            $this->visit('/registration/create')
             ->type($this->randomString(), 'reg_first_name')
             ->type($this->randomString(), 'reg_last_name')
             ->type($i, 'reg_birth_day')
             ->type(1, 'reg_birth_month')
             ->type(2000 , 'reg_birth_year')
             ->press('Register for Enrolment')
             ->seePageIs('/registration');

        }
        
    }
}
