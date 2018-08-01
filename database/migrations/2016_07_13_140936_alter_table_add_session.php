<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::table('service_attendant_sessions', function ($table) {
			$table->string('ats_session_id')->unique();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('service_attendant_sessions', function ($table) {
			$table->dropColumn('ats_session_id');
		});
    }
}
