<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpvoteDownvoteTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('topic_actions_trend', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action');
            $table->string('topic_uuid');
            $table->string('user_uuid');
            $table->timestamps();
        });


        Schema::table('topics', function ($table) {

            //Obj_id such as post
            $table->integer('upvote')->default(0);
            $table->integer('dwnvote')->default(0);
            $table->integer('comments')->default(0);
        });

        //keep track of ppl upvote this user
        Schema::table('users', function ($table) {
            $table->integer('upvote')->nullable();
        });


        Schema::table('tags', function ($table) {
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
        Schema::table('topics', function ($table) {
            $table->dropColumn(['upvote','dwnvote','comments']);
        });

        Schema::table('users', function ($table) {
            $table->dropColumn('upvote');
        });

        Schema::drop('topic_actions_trend');
    }
}
