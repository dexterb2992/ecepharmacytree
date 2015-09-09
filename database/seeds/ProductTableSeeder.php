<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
// use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\Product;

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
    	Product::where('id', '>', 0)->delete();

        $this->insertProduct(1, 3, 'Ambrolex', 'Ambroxol', 'Ambrolex 35mg', 1, 11.75, 'tablet', 'strip', 12, generateSku(), '2015-05-20 08:47:24', '2015-06-08 16:00:15', NULL);
		$this->insertProduct(2, 1, 'Biogesic 100 mg/mL Suspension (Oral Drops)', 'Paracetamol ', 'An orange-colored, thick suspension with fruity orange odor, and sweet, juicy, rindy, orange taste using the TasteRiteÂ® Technology. TasteRite technology is a unique tastemasking system developed specifically for liquid dosage forms. This technology significantly reduces the bitterness of medicine so that children taste the flavor and not the medicine.', 0, 40.5, '60 ml bottle', 'box', 1, generateSku(), '2015-05-21 03:07:25', '2015-06-22 16:00:00', NULL);
		$this->insertProduct(3, 1, 'Biogesic 500 mg Tablet', 'Paracetamol', 'For the relief of minor aches and pains such as headache, backache, menstrual cramps, muscular aches, minor arthritis pain, toothache, and pain associated with the common cold and flu;\r\n\r\nFor fever reduction.', 0, 4.75, 'tablet', 'strip', 12, generateSku(), '2015-05-21 03:17:30', '2015-06-22 16:00:00', NULL);
		$this->insertProduct(4, 2, 'Revicon Forte Tablet', 'Multivitamins + Minerals + Amino Acids', 'INDICATION:\r\n\r\nA nutritional supplement to provide essential vitamins, minerals and amino acids for general good health, to help promote physical vigor and help improve stamina during physical activity.\r\n\r\nIt contains B-complex vitamins to help optimize conversion of food into energy and Iron, a cofactor of enzymes involved in energy production. It combines the synergistic actions of Calcium, Vitamin D, Magnesium and Manganese to promote healthy bones. Potassium, coupled with Magnesium, Manganese and Calcium also help regulate musclecontraction and nerve impulses.\r\n\r\nit has the essential amino acids Methionine and Lysine which are vital in muscle tissue building.\r\n\r\nDOSAGE and ADMINISTRATION:\r\n\r\nOrally, 1 to 2 tablets daily. Or, as directed by a doctor.\r\n\r\nCONTRAINDICATION:\r\n\r\nHypersensitivity to any ingredient in the product.', 0, 5, 'tablet', 'bottle', 60, generateSku(), '2015-06-22 04:10:52', NULL, NULL);

    }


    public function insertProduct($id, $subcategory_id, $name, $generic_name, $description,
    	$prescription_required, $price, $unit, $packing, $qty_per_packing, $sku, 
    	$created_at, $updated_at, $deleted_at ){

    	$product = new Product;
    	$product->id = $id;
    	$product->subcategory_id = $subcategory_id;
    	$product->name = $name;
    	$product->generic_name = $generic_name;
    	$product->description = $description;
    	$product->prescription_required = $prescription_required;
    	$product->price = $price;
    	$product->unit = $unit;
    	$product->packing = $packing;
    	$product->qty_per_packing = $qty_per_packing;
    	$product->sku = $sku;
    	$product->created_at = $created_at;
    	$product->updated_at = $updated_at;
    	$product->deleted_at = $deleted_at;

    	$product->save();
    }
}
