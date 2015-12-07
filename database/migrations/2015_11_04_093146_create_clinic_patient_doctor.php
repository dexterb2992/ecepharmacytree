<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicPatientDoctor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_patient_doctor', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('clinic_id')->unsigned();
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            $table->integer('doctor_id')->unsigned();
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->integer('clinic_patients_id')->unsigned();
            $table->foreign('clinic_patients_id')->references('id')->on('clinic_patients')->onDelete('cascade');
            $table->integer('patient_id')->default(0);
            $table->string('username')->unique();
            $table->string('password');
            $table->integer('is_new')->default(1);
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
        Schema::drop('clinic_patient_doctor');
    }
}
