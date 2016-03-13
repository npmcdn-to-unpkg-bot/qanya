<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplyInReply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reply_in_reply', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('flg')->default(1);
            $table->integer('reply_id');
            $table->string('topic_uuid');
            $table->string('user_uuid');
            $table->string('body');
            $table->string('images');
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
        Schema::drop('reply_in_reply');
    }
}
