<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripShedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_shedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trip_id');
            $table->string('travel_timestamp');
            $table->string('return_timestamp');
            $table->string('stay_time')->default('NULL');
            $table->string('Total_travel_time')->default('NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_shedules');
    }
}
