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
			1 => 'over-the-counter-medicines',
			2 => 'prescription drugs',
			3 => 'vitamins & supplements',
			4 => 'medical devices',
			5 => 'sexual wellness',
			6 => 'health and beauty',
			7 => 'medical supplies'
		);

		foreach ($values as $key => $value) {
			$category = new ProductCategory;
			$category->id = $key;
			$category->name = $value;
			$category->save();
		}

    }
}
