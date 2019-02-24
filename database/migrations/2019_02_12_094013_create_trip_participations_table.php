<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_participations', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('user_email');
            $table->string('trip_id');
            $table->string('trip_status')->default('start');
            $table->string('rating_by_user')->default('0'); 
            /* from this we can calculate rating of particular trip */
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
        Schema::dropIfExists('trip_participations');
    }
}
