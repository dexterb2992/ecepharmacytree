<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('long_title');
            $table->string('product_applicability')
                ->default('SPECIFIC_PRODUCTS');                 // ALL_PRODUCTS, SPECIFIC_PRODUCTS
            $table->string('offer_type')->default('NO_CODE') ;      // NO_CODE, GENERIC_CODE
                // If offer_type is GENERIC_CODE (a code that is the same across all users), 
                // the attribute is required: generic_redemption_code
                
            $table->string('generic_redemption_code')->nullable();  // Provide the text code that customers can use online(Example: EXTRA20).  
            $table->double('minimum_purchase_amount');

            $table->date('start_date');
            $table->date('end_date');
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
        Schema::drop('promos');
    }
}
