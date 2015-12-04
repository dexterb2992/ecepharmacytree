<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsFreeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts_free_products', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('promo_id')->unsigned();
            $table->foreign('promo_id')->references('id')->on('promos')->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity_required');
            // $table->integer('type');    // % base discount (1)
                                        // Peso value discount (2)
                                        // Free Gift (3)
                                        // Free Delivery (4) 
            
            // $table->double('less');     // value depends on type, ex. less = 5 and type = 1, 
                                        // less is now actually equal to 5% off, else if
                                        // type = 2 then less is 5 pesos off 
                                        // this field is not applicable to type 3 and 4
            $table->integer('is_free_delivery')->default(0);
            $table->double('percentage_discount');
            $table->double('peso_discount');
            $table->integer('has_free_gifts')->default(0);

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
        Schema::drop('discounts_free_products');
    }
}
