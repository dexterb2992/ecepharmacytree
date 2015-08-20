<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function(Blueprint $table) {
            $table->increments('id');
            $table->string('lname');
            $table->string('mname');
            $table->string('fname');
            $table->integer('prc_no');
            $table->integer('sub_specialty_id')->unsigned();
            $table->foreign('sub_specialty_id')->references('id')->on('sub_specialties');
            $table->text('photo');
            $table->text('affiliation');
            $table->string('email');
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
        Schema::drop('doctors');
    }
}
