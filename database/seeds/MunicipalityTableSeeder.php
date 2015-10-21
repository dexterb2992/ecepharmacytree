<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\Municipality;

class MunicipalityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Municipality::where('id', '>', 0)->delete();
        $columns = ['id', 'province_id', 'name'];
        $municipalities = extract_db_to_array(public_path()."/db-src/municipalities.dat", $columns);

        Municipality::insert( $municipalities );
    }
}