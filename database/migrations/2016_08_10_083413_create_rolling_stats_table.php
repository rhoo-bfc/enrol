<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRollingStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rolling_summary', function (Blueprint $table) {
            $table->integer('rsu_enrol_count')->default('0');
            $table->integer('rsu_failed_enrol_count')->default('0');
            $table->decimal('rsu_avg_wait_mins',5,2)->nullable();
            $table->decimal('rsu_avg_enrol_mins',5,2)->nullable();
            $table->timestamp('rsu_created_ts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('rolling_summary');
    }
}
