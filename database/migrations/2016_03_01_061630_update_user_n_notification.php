<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserNNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //Log user IP
        Schema::create('ip_logger', function (Blueprint $table) {
            $table->increments('id');
            $table->string('obj_id');
            $table->string('action');       //reply, post etc
            $table->string('user_uuid');
            $table->string('ip')->nullable();
            $table->string('hostname')->nullable();
            $table->string('org')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('loc')->nullable();
            $table->string('postal')->nullable();
            $table->timestamps();
        });

        Schema::table('notification', function ($table) {
            //Obj_id such as post
            $table->string('obj_id');
        });

        Schema::table('users', function ($table) {
            //Obj_id such as post
            $table->string('current_city');
            $table->string('current_country');
            $table->string('profile_img');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification', function ($table) {
            $table->dropColumn('obj_id');
        });

        Schema::table('users', function ($table) {
            $table->dropColumn(['current_city','profile_img']);
        });

        Schema::drop('ip_logger');
    }
}
