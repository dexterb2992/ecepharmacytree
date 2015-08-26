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
            $table->longText('generic_name');
            $table->string('quanitity');
            $table->longText('prescription');
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
