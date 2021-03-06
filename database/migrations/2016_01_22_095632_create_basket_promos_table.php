<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasketPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basket_promos', function(Blueprint $table) {
            $table->increments('id');        
            $table->integer('basket_id')->unsigned()->unique();
            $table->foreign('basket_id')->references('id')->on('baskets')->onDelete('cascade');
            $table->integer('promo_id')->unsigned();
            $table->foreign('promo_id')->references('id')->on('promos')->onDelete('cascade');
            $table->string('promo_type');
            $table->double('percentage_discount');
            $table->double('peso_discount');
            $table->integer('free_gift');
            $table->integer('promo_free_product_qty');
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
        Schema::drop('basket_promos');
    }
}
