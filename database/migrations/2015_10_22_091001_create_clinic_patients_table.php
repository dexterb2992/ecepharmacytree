<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_patients', function(Blueprint $table) {
            $table->increments('id');
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('username')->unique();
            $table->string('password');
            // since the relationship of clinic and doctor is many to many,
            //  we'll need both clinic_id and doctor_id
            $table->integer('clinic_id')->unsigned();
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            $table->integer('doctor_id')->unsigned();
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
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
        Schema::drop('clinic_patients');
    }
}
