<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics_reply', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('flg')->default(1);
            $table->uuid('topic_uuid');
            $table->uuid('uid');
            $table->text('body');
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
        Schema::drop('topics_reply');
    }
}
