<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\ProductGroup;

class ProductGroupTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        ProductGroup::truncate();

		$values = array(
			array('id' => 1, "name" => "Group G"),
			array('id' => 2, "name" => "Group B")
		);
		ProductGroup::insert($values);
    }
}
