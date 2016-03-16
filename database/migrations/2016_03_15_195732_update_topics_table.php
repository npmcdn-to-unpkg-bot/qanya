<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function ($table) {
            $table->integer('type');
            $table->integer('num_img');
            $table->boolean('is_edited')->default(false);
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
            $table->dropColumn('type');
            $table->dropColumn('num_img');
            $table->dropColumn('is_edited');
        });

    }
}
