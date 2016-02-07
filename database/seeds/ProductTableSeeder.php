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

    } 
  

}
