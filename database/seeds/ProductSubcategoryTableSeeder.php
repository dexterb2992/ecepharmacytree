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
    	ProductSubcategory::truncate();

        $values = array(
            '1' => array(
                    'Uncategorized'
                ),
        	'2' => array(
        			'allergies',
					'cough & colds',
					'deworming agents',
					'ear, nose, mouth & throat preparations',
					'eye preparations',
					'fever & pain relief',
					'hair & scalp'
        		),
        	'3' => array(
        			'allergies',
					'anti-infective agens',
					'apetite enhancers',
					'asthma & other airway diseases',
					'blood pressure & heart medications',
					'brain & nervous system'
        		),
        	'4' => array(
        			"calcium preparations",
					"children's supplements",
					"food supplements",
					"multivitamins & minerals"
        		),
        	'5' => array(
        			"healthcare & monitoring"
        		),
        	'6' => array(
        			"first aid/ wound care",
					"general use",
					"topical antiseptics/ disinfectants",
                   "contraceptives"
        		),
        	'7' => array(
        			"personal care & protection"
        		),
        	'8' => array(
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
