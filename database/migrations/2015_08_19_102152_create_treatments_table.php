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
            $table->foreign('patient_record_id')->references('id')->on('patient_records');
            $table->string('medicine_name');
            $table->text('generic_name');
            $table->string('quanitity');
            $table->text('prescription');
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
        Schema::drop('treatments');
    }
}
