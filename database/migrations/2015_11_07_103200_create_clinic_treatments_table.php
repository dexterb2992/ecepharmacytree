<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_treatments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('clinic_patients_record_id')->unsigned();
            $table->foreign('clinic_patients_record_id')->references('id')
                ->on('clinic_patients_records')->onDelete('cascade');
            $table->integer('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')
                ->on('clinic_medicines')->onDelete('cascade');
            $table->integer('no_generics')->default(1);
            $table->integer('quantity');
            $table->string('route');
            $table->string('frequency');
            $table->integer('refills')->default(0);
            $table->string('duration')->default(0);
            $table->string('duration_type');
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
        Schema::drop('treatments');
    }
}
