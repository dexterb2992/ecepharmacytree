<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPreferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_preference', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->string('mode_of_delivery');
            $table->string('recipient_address');
            $table->string('recipient_name');
            $table->string('recipient_contactNumber');
            $table->string('payment_method');
            $table->integer('branch_id');
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
        Schema::drop('order_preference');
    }
}
