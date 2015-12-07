<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function(Blueprint $table) {
            $table->increments('id');
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email_address');
            $table->string('mobile_no');
            $table->string('tel_no')->nullable();
            $table->longText('photo')->nullable();
            $table->string('occupation')->nullable();
            $table->string('birthdate');
            $table->string('sex', 6);
            $table->string('civil_status', 20);
            $table->string('height', 10);
            $table->string('weight', 10);
            $table->string('optional_address');
            $table->string('address_street');
            $table->integer('address_barangay_id')->unsigned()->nullable();
            $table->foreign('address_barangay_id')->references('id')->on('barangays');
            $table->double('points')->default(0);
            $table->string('referral_id');
            $table->string('referred_byUser')->nullable();
            $table->string('referred_byDoctor')->nullable();
            $table->integer('is_new')->default(1);
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
        Schema::drop('patients');
    }
}
