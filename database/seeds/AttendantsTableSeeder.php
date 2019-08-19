<?php

use Illuminate\Database\Seeder;

class AttendantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		
		DB::statement('TRUNCATE TABLE attendants');
		
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                 
                 Excel::load( app_path() .'/../database/seeds/attendants.csv', function($reader) {

                    // Getting all results
                    $results = $reader->toArray();
                    foreach ($results as $row ) {
                        
                        $firstName  = substr( $row['name'], 0, strpos($row['name'],' ') );
                        $secondName = substr( $row['name'], strpos($row['name'],' ') );
                        
                        DB::table('attendants')->insert([
				'att_email'       => str_random(10).'@gmail.com',
				'att_first_name'  => $firstName,
				'att_second_name' => $secondName,
				'att_active'      => 'Y'
			]);
                        
                    }

                });
                 
    }
}
