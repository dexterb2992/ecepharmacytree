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
            $table->string('tel_no')->nullable();
            $table->longText('photo')->nullable();
            $table->string('occupation')->nullable();
            $table->string('birthdate');
            $table->string('sex', 6);
            $table->string('civil_status', 20);
            $table->string('height', 10);
            $table->string('weight', 10);
            $table->integer('unit_floor_room_no')->nullable();
            $table->string('building')->nullable();
            $table->integer('lot_no')->nullable();
            $table->integer('block_no')->nullable();
            $table->integer('phase_no')->nullable();
            $table->integer('address_house_no')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_barangay');
            $table->string('address_city_municipality');
            $table->string('address_province');
            $table->string('address_region');
            $table->string('address_zip');
            $table->string('referral_id');
            // $table->string('referred_by')->nullable(); // referral_id of the user who referred this new user
            $table->string('referred_byUser')->nullable();
            $table->string('referred_byDoctor')->nullable();
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
