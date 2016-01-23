<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('order_details', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->double('price'); // product's price per packing, we save the price from products table into the   
                                     // order_details table during the ordering, so that whenever the product's price changes,   
                                     // the already completed orders' amount won't be affected by the changes
            $table->integer('prescription_id')->default(0);
            $table->double('quantity');
            $table->string('type')->default('delivery'); // pickup or delivery
            $table->integer('qty_fulfilled')->default(0);
            $table->integer('promo_id')->unsigned();
            $table->foreign('promo_id')->references('id')->on('patients')->onDelete('cascade');
            $table->string('promo_type');
            $table->double('percentage_discount');
            $table->double('peso_discount');
            $table->integer('free_gift');
            $table->integer('promo_free_product_qty');
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
        Schema::drop('order_details');
    }
}
