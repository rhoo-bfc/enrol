<?php

use Illuminate\Database\Seeder;

class ServiceDeskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		
		DB::statement('TRUNCATE TABLE service_desks');
		
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		
		for( $i = 1;  $i <= 55; $i++ ) {
			 DB::table('service_desks')->insert([
				'src_centre_name' => 'Service Desk ' . $i,
				'src_centre_desc' => null,
				'src_active'      => 'Y'
			]);
		 }
		 
		 
    }
}
