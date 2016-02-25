<?php

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
            $table->string('uuid')->index();
            $table->string('displayname')->index();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('description')->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->integer('posts');//->default(0);
            $table->integer('followers');//->default(0);
            $table->integer('following');//->default(0);
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
