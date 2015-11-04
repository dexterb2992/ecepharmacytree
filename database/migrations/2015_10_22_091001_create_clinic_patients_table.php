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
            // $table->string('username')->unique();
            // $table->string('password');
            // since the relationship of clinic and doctor is many to many,
            //  we'll need both clinic_id and doctor_id
            // $table->integer('clinic_id')->unsigned();
            // $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            // $table->integer('doctor_id')->unsigned();
            // $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
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
