<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecretariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secretaries', function(Blueprint $table) {
            $table->increments('id');
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('address_house_no', 10)->nullable();
            $table->string('address_street');
            $table->string('address_barangay');
            $table->string('address_city_municipality');
            $table->string('address_province');
            $table->string('address_region');
            $table->string('address_zip', 10);
            $table->string('cell_no', 15)->nullable();
            $table->string('tel_no', 8)->nullable();
            $table->string('email');
            $table->longText('photo')->nullable();
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
        Schema::drop('secretaries');
    }
}
