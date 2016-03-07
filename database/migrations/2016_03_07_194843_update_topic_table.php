<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Weird.... it won't let us set nullable
        Schema::table('topics', function ($table) {
            $table->dropColumn('tags');
        });


        Schema::table('topics', function ($table) {
            $table->text('text');
            $table->string('tags')->nullable();
        });

        Schema::create('topics_img', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('flg')->default(1);
            $table->string('topic_uuid');
            $table->string('user_uuid');
            $table->string('filename');
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
            $table->dropColumn('text');
        });

        Schema::drop('topics_img');
    }
}
