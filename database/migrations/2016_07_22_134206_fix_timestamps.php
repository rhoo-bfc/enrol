<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
    Schema::table('service_attendant_sessions', function($table) {
        $table->datetime('ats_start_ts')->default( \DB::Raw('CURRENT_TIMESTAMP') )->change();
        $table->datetime('ats_end_ts')->nullable()->change();
    });
    
    Schema::table('assignments', function($table) {
        $table->datetime('asn_created_ts')->default( \DB::Raw('CURRENT_TIMESTAMP') )->change();
        $table->datetime('asn_completed_ts')->nullable()->change();
    });
    
    Schema::table('registrations', function($table) {
        $table->datetime('reg_created_ts')->default( \DB::Raw('CURRENT_TIMESTAMP') )->change();
    });
    
    DB::statement("ALTER TABLE service_attendant_sessions MODIFY COLUMN ats_start_ts DATETIME DEFAULT CURRENT_TIMESTAMP");
    DB::statement("ALTER TABLE service_attendant_sessions MODIFY COLUMN ats_end_ts DATETIME DEFAULT NULL");
    
    DB::statement("ALTER TABLE assignments MODIFY COLUMN asn_completed_ts DATETIME DEFAULT CURRENT_TIMESTAMP");
    DB::statement("ALTER TABLE assignments MODIFY COLUMN asn_completed_ts DATETIME DEFAULT NULL");
    
    DB::statement("ALTER TABLE registrations MODIFY COLUMN reg_created_ts DATETIME DEFAULT CURRENT_TIMESTAMP");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
