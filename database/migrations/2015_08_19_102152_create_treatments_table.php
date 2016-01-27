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
        Schema::create('patient_treatments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_records_id')->unsigned();
            $table->foreign('patient_records_id')->references('id')->on('patient_records')->onDelete('cascade');
            $table->integer('medicine_id');
            $table->string('medicine_name');
            $table->string('frequency');
            $table->integer('duration');
            $table->integer('duration_type');
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
        Schema::drop('patient_treatments');
    }
}
