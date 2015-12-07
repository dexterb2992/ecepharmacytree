<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinics', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('contact_no', 30);
            $table->integer('unit_floor_room_no')->nullable();
            $table->string('building')->nullable();
            $table->integer('lot_no')->nullable();
            $table->integer('block_no')->nullable();
            $table->integer('phase_no')->nullable();
            $table->integer('address_house_no')->nullable();
            $table->string('address_street')->nullable();
            $table->integer('barangay_id')->unsigned();
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
            $table->integer('is_new')->default(1);
            // $table->string('address_barangay');
            // $table->string('address_city_municipality');
            // $table->string('address_province');
            // $table->string('address_region');
            // $table->string('address_zip');
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
        Schema::drop('clinics');
    }
}
