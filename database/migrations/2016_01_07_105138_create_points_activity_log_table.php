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
            $table->integer('earner_id');
            $table->string('earner_type')->default('patient'); // patient or doctor
            $table->double('points_earned');
            $table->double('old_points');
            $table->double('new_points');
            $table->string('points_origin')->default('sales'); // sales or referral
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
