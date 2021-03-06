<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->double('coupon_discount')->default(0);
            $table->double('points_discount')->default(0);
            $table->double('senior_discount')->default(0);
            $table->double('gross_total');
            $table->double('total');
            $table->string('payment_status')->default('pending');
            $table->string('payment_method');
            $table->integer('is_new')->default(1);
            $table->integer('points_computation_status')->default(0); // if 1, it means the points from this sale has been saved to the user's points
            $table->string('or_txn_number');
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
        Schema::drop('billings');
    }
}
