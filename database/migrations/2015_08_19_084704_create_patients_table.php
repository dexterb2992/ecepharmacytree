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
            $table->string('email_address')->unique();
            $table->string('mobile_no');
            $table->string('tel_no');
            $table->text('photo');
            $table->string('occupation');
            $table->date('birthdate');
            $table->string('sex', 6);
            $table->string('civil_status', 20);
            $table->string('height', 10);
            $table->string('weight', 10);
            $table->integer('unit_floor_room_no');
            $table->string('building');
            $table->integer('lot_no');
            $table->integer('block_no');
            $table->integer('phase_no');
            $table->integer('address_house_no');
            $table->string('address_street');
            $table->string('address_barangay');
            $table->string('address_city_municipality');
            $table->string('address_province');
            $table->string('address_region');
            $table->string('address_zip');
            $table->string('referral_id');
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
        Schema::drop('patients');
    }
}
