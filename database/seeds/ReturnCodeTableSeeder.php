<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\StockReturnCode;

class ReturnCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	StockReturnCode::where('id', '>', 0)->delete();
        $return_codes = array(
        	['id' => 1, 'name' => "Change of mind", "created_at" => null, "updated_at" => null],
        	['id' => 2, 'name' => "Ordered Incorrectly", "created_at" => null, "updated_at" => null],
        	['id' => 3, 'name' => "Did not want/need", "created_at" => null, "updated_at" => null],
        	['id' => 4, 'name' => "Poor quality", "created_at" => null, "updated_at" => null],
        	['id' => 5, 'name' => "Defective", "created_at" => null, "updated_at" => null],
        	['id' => 6, 'name' => "Delivered too late", "created_at" => null, "updated_at" => null],
        	['id' => 7, 'name' => "Product mislabeled", "created_at" => null, "updated_at" => null],
        	['id' => 8, 'name' => "Duplicate shipment", "created_at" => null, "updated_at" => null],
        	['id' => 9, 'name' => "Wrong item sent", "created_at" => null, "updated_at" => null],
        	['id' => 10, 'name' =>"Not as picture/described" , "created_at" => null, "updated_at" => null],
        	['id' => 11, 'name' =>"Found item at better price" , "created_at" => null, "updated_at" => null],
        	['id' => 12, 'name' =>"Core return" , "created_at" => null, "updated_at" => null],
        	['id' => 13, 'name' =>"Did not like the item" , "created_at" => null, "updated_at" => null],
        	['id' => 14, 'name' =>"Other", "created_at" => null, "updated_at" => null ]
        );

        StockReturnCode::insert($return_codes);
    }
}
