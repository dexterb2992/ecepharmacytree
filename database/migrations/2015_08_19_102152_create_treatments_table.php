<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_record_id')->unsigned();
            $table->foreign('patient_record_id')->references('id')->on('patient_records')->onDelete('cascade');
            $table->integer('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('clinic_medicines')->onDelete('cascade');
            $table->integer('no_generics')->default(1);
            $table->integer('quantity');
            $table->string('route');
            $table->string('frequency');
            $table->integer('refills')->default(0);
            $table->integer('duration')->default(0);
            $table->integer('duration_type');
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
