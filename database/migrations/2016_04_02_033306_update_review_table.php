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

        Schema::table('users', function ($table) {
            $table->boolean('confirmed')->default(0);
            $table->string('confirmation_code')->nullable();
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

        Schema::table('users', function ($table) {
            $table->dropColumn('confirmed')->default(0);
            $table->dropColumn('confirmation_code')->nullable();
        });
    }
}
