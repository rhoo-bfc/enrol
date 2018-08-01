<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('registrations', function (Blueprint $table) {
            $table->increments('reg_id');
            $table->string('reg_first_name',255);
            $table->string('reg_last_name',255);
            $table->string('reg_email',255)->nullable();
			$table->string('reg_mob',255)->nullable();
			$table->timestamp('reg_created_ts');
        });
		
		Schema::create('attendants', function (Blueprint $table) {
            $table->increments('att_id');
            $table->string('att_email',255);
            $table->string('att_first_name',255)->nullable();
            $table->string('att_second_name',255)->nullable();
			$table->char('att_active',1);
        });
		
		Schema::create('service_desks', function (Blueprint $table) {
            $table->increments('src_id');
            $table->string('src_centre_name',255);
            $table->mediumText('src_centre_desc')->nullable();
			$table->char('src_active',1);
        });
		
		Schema::create('assignments', function (Blueprint $table) {
            $table->integer('asn_src_id')->unsigned();
            $table->integer('asn_reg_id')->unsigned();
            $table->char('asn_status',3);
			$table->timestamp('asn_created_ts');
			$table->timestamp('asn_completed_ts');
			$table->foreign('asn_src_id')->references('src_id')->on('service_desks');
			$table->foreign('asn_reg_id')->references('reg_id')->on('registrations');
        });
		
	Schema::create('service_attendant_sessions', function (Blueprint $table) {
            $table->integer('ats_att_id')->unsigned();
            $table->integer('ats_src_id')->unsigned();
	    $table->timestamp('ats_start_ts');
	    $table->timestamp('ats_end_ts');
	    $table->foreign('ats_att_id')->references('att_id')->on('attendants');
	    $table->foreign('ats_src_id')->references('src_id')->on('service_desks');
        });
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('registrations');
		Schema::drop('attendants');
		Schema::drop('service_desks');
		Schema::drop('assignments');
		Schema::drop('service_attendant_sessions');		
    }
}
