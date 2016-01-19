<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointsActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_type')->default('patient'); // patient or doctor
            $table->integer('user_id');
            $table->double('points_used');
            $table->string('notes');
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
        Schema::drop('points_activity_log');
    }
}
