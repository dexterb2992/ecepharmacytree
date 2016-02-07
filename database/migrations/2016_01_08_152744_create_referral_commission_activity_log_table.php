<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralCommissionActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_commission_activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('billing_id')->unsigned();
            $table->foreign('billing_id')->references('id')->on('billings');
            $table->integer('to_upline_id');    // the upline of the patient who owns the purchase : value= patient_id or doctor_id
            $table->string('to_upline_type')->default('patient'); // patient or doctor 
            $table->integer('referral_level');  // relationship level between the one who purchase and the referrer
            $table->double('points_earned');    // points earned by the patient
            $table->double('referral_points_earned'); // points earned by the referrer
            $table->double('old_upline_points');  // old points of the referrer
            $table->double('new_upline_points');  // new points of the referrer
            $table->longtext('notes');
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
        Schema::drop('referral_commission_activity_log');
    }
}
