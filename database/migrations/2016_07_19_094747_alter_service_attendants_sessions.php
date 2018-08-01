<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterServiceAttendantsSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
  
        
        Schema::table('service_attendant_sessions', function ($table) {
            $table->increments('ats_id')->before('ats_att_id');
	});
        
        
        Schema::table('assignments', function ($table) {
            $table->dropForeign('assignments_asn_src_id_foreign');
            $table->renameColumn('asn_src_id','asn_ats_id');
            $table->foreign('asn_ats_id')->references('ats_id')->on('service_attendant_sessions');
	});
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('assignments', function ($table) {
            $table->dropForeign('assignments_asn_ats_id_foreign');
            $table->renameColumn('asn_ats_id','asn_src_id');
            $table->foreign('asn_src_id')->references('src_id')->on('service_desks');
	});
        
        Schema::table('service_attendant_sessions', function ($table) {
            $table->dropColumn('ats_id');
	});
    }
}
