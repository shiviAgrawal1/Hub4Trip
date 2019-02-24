<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('create_trips', function (Blueprint $table) {
            $table->increments('trip_id');
            $table->string('trip_org_email');
            $table->string('trip_org_name');
            $table->string('source');
            $table->string('destination');
            $table->string('max_accomodation');
            $table->string('current_accomodation')->default('0');
            $table->string('status')->default('start');
            $table->string('rating_of_trip')->default('0');      //it is calculated by rating by user for that trip//       
            ///*from this we can calculate rating of trip_organiser and company */
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
        Schema::dropIfExists('create_trips');
    }
}
