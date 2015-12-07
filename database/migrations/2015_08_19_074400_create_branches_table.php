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
            $table->string('additional_address');
            $table->integer('barangay_id')->unsigned();
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('telephone_numbers')->nullable();
            $table->string('telefax')->nullable();
            $table->string('mobile_numbers')->nullable();
            $table->integer('status')->default(1);
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
        Schema::drop('branches');
    }
}
