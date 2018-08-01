<?php

use Illuminate\Database\Seeder;

class QueuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //     
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		
	DB::statement('TRUNCATE TABLE queues');
		
	DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('queues')->insert([
            'que_id'     => null,
            'que_name'   => '18 and Under',
            'que_active' => 'Y',
        ]);
        
        DB::table('queues')->insert([
            'que_id'     => null,
            'que_name'   => '19+',
            'que_active' => 'Y',
        ]);
        
        DB::table('queues')->insert([
            'que_id'     => null,
            'que_name'   => 'Missed Appointments',
            'que_active' => 'Y',
        ]);
        
    }
}
