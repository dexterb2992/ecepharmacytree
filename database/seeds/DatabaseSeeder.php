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

        // $this->call('UserTableSeeder');
        $this->call(Illuminate\Database\Seeder\ProductCategoryTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductSubcategoryTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\SettingTableSeeder::class);

        // Model::reguard();
    }
}
