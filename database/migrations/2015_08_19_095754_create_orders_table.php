<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('recipient_address');
            $table->string('recipient_contactNumber');
            $table->date('delivery_sched');
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->integer('promo_id');
            $table->string('promo_type');
            $table->string('modeOfDelivery');
            $table->double('delivery_charge');
            $table->string('status')->default('open');
            $table->integer('is_new')->default(1);
            $table->integer('is_returned')->default(0);
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
        Schema::drop('orders');
    }
}
