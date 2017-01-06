<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
// use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\Product;

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        Product::truncate();
        $columns = ['id','subcategory_id','name','generic_name','description','prescription_required',
        			'unit_cost','price','unit','packing','qty_per_packing','sku','critical_stock','product_group_id',
        			'is_freebie','is_new'];
        $products = extract_db_to_array(public_path()."/db-src/products.dex", $columns);

        foreach ($products as $product) {
        	Product::insert( $product );
        }

    } 
  

}
