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
            // $table->longText('photo')->nullable();
            $table->longText('affiliation')->nullable();
            $table->string('email')->nullable();
            $table->string('username');
            $table->string('password');
            $table->string('referral_id');
            $table->double('points')->default(0);
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
        Schema::drop('doctors');
    }
}
