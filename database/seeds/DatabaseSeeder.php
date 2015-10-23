<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
// use ECEPharmacyTree\Seeder\ProductCategoryTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(Illuminate\Database\Seeder\BranchTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\UserTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductCategoryTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductSubcategoryTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\SettingTableSeeder::class);
        // $this->call(Illuminate\Database\Seeder\RegionTableSeeder::class);
        // $this->call(Illuminate\Database\Seeder\ProvinceTableSeeder::class);
        // $this->call(Illuminate\Database\Seeder\MunicipalityTableSeeder::class);
        // $this->call(Illuminate\Database\Seeder\BarangayTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\PatientTableSeeder::class);
        // Model::reguard();
    }
}
