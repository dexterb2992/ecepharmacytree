<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function(Blueprint $table) {
            $table->increments('id');
            $table->double('points'); //referral settings
            $table->double('points_to_peso')->default(1); //referral settings
            $table->integer('level_limit'); //referral settings
            $table->double('referral_commission'); // % per points earned by downlines
            $table->double('commission_variation'); // % deduction per level
            $table->double('delivery_charge');
            $table->double('delivery_minimum');
            $table->integer('safety_stock'); //inventory settings
            $table->integer('critical_stock'); //inventory settings
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}
