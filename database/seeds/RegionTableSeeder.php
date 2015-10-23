<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\Region;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::where('id', '>', 0)->delete();

        $columns = ['id', 'code', 'name'];
        $regions = extract_db_to_array(public_path()."/db-src/regions.dex", $columns);

        Region::insert($regions);


    }
}
