<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function ($table) {
            $table->integer('topic_id');
        });


        Schema::table('topics', function ($table) {
            $table->bigInteger('location_id')->nullable();
        });
        


        //Add confirmation code for email
        Schema::table('users', function ($table) {
            $table->boolean('confirmed')->default(0);
            $table->string('confirmation_code')->nullable();
        });


        //Add location
        Schema::create('locations', function ($table) {
            $table->increments('id');
            $table->tinyInteger('flg')->default(1);;
            $table->string('source');
            $table->bigInteger('external_id');
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();
            $table->string('latitude');
            $table->string('longitude');
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
        Schema::table('reviews', function ($table) {
            $table->dropColumn('topic_id');
        });

        Schema::table('topics', function ($table) {
            $table->dropColumn('location_id');
        });

        Schema::table('users', function ($table) {
            $table->dropColumn('confirmed')->default(0);
            $table->dropColumn('confirmation_code')->nullable();
        });

        Schema::drop('locations');
    }
}
