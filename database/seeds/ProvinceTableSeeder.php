<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\Province;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::where('id', '>', 0)->delete();

        $columns = ['id', 'region_id', 'name'];
        $provinces = extract_db_to_array(public_path()."/db-src/provinces.dat", $columns);

        Province::insert( $provinces );
    }
}
