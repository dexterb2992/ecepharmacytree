<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->integer('doctor_id')->unsigned();
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->integer('clinic_id')->unsigned();
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            $table->string('date');
            $table->string('time');
            $table->integer('is_alarm');
            $table->string('alarm_time');
            $table->integer('is_approved')->default(0);
            $table->integer('isRead')->default(0);
            $table->string('comment_doctor');
            $table->integer('patient_is_approved')->default(0);
            $table->string('comment_patient');
            $table->integer('is_deleted')->default(0);
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
        Schema::drop('consultations');
    }
}
