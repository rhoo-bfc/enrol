<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call( AttendantsTableSeeder::class );
	$this->call( ServiceDeskTableSeeder::class );
        $this->call( QueuesTableSeeder::class );
    }
}
