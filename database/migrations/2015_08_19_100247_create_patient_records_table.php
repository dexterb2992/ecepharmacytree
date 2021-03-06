<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_records', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('clinic_patient_record_id')->default(0);
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->integer('doctor_id')->default(0);
            $table->integer('clinic_id')->default(0);
            $table->string('doctor_name');
            $table->string('clinic_name');
            $table->longText('complaints');
            $table->longText('findings');
            $table->string('record_date');
            $table->string('created_by')->default('user'); // change value to doctor, if created by doctor
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
        Schema::drop('patient_records');
    }
}
