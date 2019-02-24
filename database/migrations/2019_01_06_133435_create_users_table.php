<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email',60)->unique();
            $table->string('password');
            $table->string('phno')->default('NULL');
            $table->string('type')->default('user');
            $table->string('company')->default('NULL');
            $table->string('no_of_trips_done')->default('0');
            /* enter at the time of feedback*/
            $table->string('rating_of_user')->default('0');  // entered by trip_organiser at the end of trip

            $table->text('api_token');
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
        Schema::dropIfExists('users');
    }
}
