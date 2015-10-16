<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
// use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\ProductSubcategory;

class ProductSubcategoryTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
    	ProductSubcategory::where('id', '>', 0)->delete();

        $values = array(
        	'1' => array(
        			'allergies',
					'cough & colds',
					'deworming agents',
					'ear, nose, mouth & throat preparations',
					'eye preparations',
					'fever & pain relief',
					'hair & scalp'
        		),
        	'2' => array(
        			'allergies',
					'anti-infective agens',
					'apetite enhancers',
					'asthma & other airway diseases',
					'blood pressure & heart medications',
					'brain & nervous system'
        		),
        	'3' => array(
        			"calcium preparations",
					"children's supplements",
					"food supplements",
					"multivitamins & minerals"
        		),
        	'4' => array(
        			"healthcare & monitoring"
        		),
        	'5' => array(
        			"first aid/ wound care",
					"general use",
					"topical antiseptics/ disinfectants"
        		),
        	'6' => array(
        			"personal care & protection"
        		),
        	'7' => array(
        			"skin care",
					"slimming supplements"
        		)
        );
		$x = 1;
        foreach ($values as $category => $subcategories) {
        	foreach ($subcategories as $subcategory) {
        		$_subcategory = new ProductSubcategory;
        		$_subcategory->id = $x;
        		$_subcategory->name = $subcategory;
        		$_subcategory->category_id = $category;
        		$_subcategory->save();
        		$x++;
        	}
        }

    }
}
