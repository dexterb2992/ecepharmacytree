<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
// use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\ProductCategory;

class ProductCategoryTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        ProductCategory::truncate();

		$values = array(
			1=> 'Uncategorized',
			2 => 'over-the-counter-medicines',
			3 => 'prescription drugs',
			4 => 'vitamins & supplements',
			5 => 'medical devices',
			6 => 'sexual wellness',
			7 => 'health and beauty',
			8 => 'medical supplies'
		);

		foreach ($values as $key => $value) {
			$category = new ProductCategory;
			$category->id = $key;
			$category->name = $value;
			$category->save();
		}

    }
}
