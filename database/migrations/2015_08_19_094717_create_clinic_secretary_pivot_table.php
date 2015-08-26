<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicSecretaryPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_secretary', function(Blueprint $table) {
            $table->integer('clinic_id')->unsigned()->index();
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            $table->integer('secretary_id')->unsigned()->index();
            $table->foreign('secretary_id')->references('id')->on('secretaries')->onDelete('cascade');
            $table->integer('is_active')->default('1');
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
        Schema::drop('clinic_secretary');
    }
}
