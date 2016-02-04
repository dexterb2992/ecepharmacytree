<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('subcategory_id')->unsigned();
            $table->foreign('subcategory_id')->references('id')->on('product_subcategories')->onDelete('cascade');
            $table->string('name');
            $table->longText('generic_name');
            $table->longText('description');
            $table->integer('prescription_required');
            $table->double('unit_cost');
            $table->double('price');  // price will be per packing
            $table->string('unit');
            $table->string('packing');
            $table->integer('qty_per_packing');
            $table->string('sku');
            $table->integer('critical_stock')->nullable();
            $table->integer('product_group_id')->default(0); // optional, foreign to product_groups
            $table->integer('is_freebie')->default(0);
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
        Schema::drop('products');
    }
}
