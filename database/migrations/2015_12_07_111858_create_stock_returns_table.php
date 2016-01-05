<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('return_code')->nullable(); // reference on stock_return_codes table

            $table->integer('all_product_is_returned')->default(0); // if set to 1, it means, the customer has returned all 
                                                                    //   products associated with his order

            // $table->integer('return_product_id')->unsigned();   // we'll set the default value to the first product of
            // $table->foreign('return_product_id')->references('id')->on('products'); // an order if the action is "refund",
            // $table->double('return_quantity');
            
            $table->string('action'); // refund or exchange

            $table->double('amount_refunded');

            // $table->integer('exchange_product_id')->nullable(); // the product to return to customer in exchange
            // $table->integer('exchange_quantity')->nullable();

            $table->string('brief_explanation')->nullable();
            $table->integer('is_new')->default(1);
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
        Schema::drop('stock_returns');
    }
}
