<?php 
namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\Barangay;

class BarangayTableSeeder extends Seeder
{
    public function run()
    {
        Barangay::truncate();
        $columns = ['id', 'name', 'municipality_id'];
        $barangays = extract_db_to_array(public_path()."/db-src/barangays.dex", $columns);

        foreach ($barangays as $barangay) {
        	Barangay::insert( $barangay );
        }
    }
}
