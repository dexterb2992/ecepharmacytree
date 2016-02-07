<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\Municipality;

class MunicipalityTableSeeder extends Seeder
{
    public function run()
    {
        Municipality::truncate();
        $columns = ['id', 'name', 'province_id'];
        $municipalities = extract_db_to_array(public_path()."/db-src/municipalities.dex", $columns);

        Municipality::insert( $municipalities );
    }
}