<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('unit_floor_room_no')->nullable();
            $table->string('building')->nullable();
            $table->integer('lot_no')->nullable();
            $table->integer('block_no')->nullable();
            $table->integer('phase_no')->nullable();
            $table->integer('address_house_no')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_barangay');
            $table->string('address_city_municipality');
            $table->string('address_province');
            $table->string('address_region');
            $table->string('address_zip');
            $table->integer('status')->default(1);
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
        Schema::drop('branches');
    }
}
