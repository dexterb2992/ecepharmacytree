<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // $this->call(Illuminate\Database\Seeder\RegionTableSeeder::class);
        // $this->call(Illuminate\Database\Seeder\ProvinceTableSeeder::class);
        // $this->call(Illuminate\Database\Seeder\MunicipalityTableSeeder::class);
        // $this->call(Illuminate\Database\Seeder\BarangayTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\BranchTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\UserTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductGroupTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductCategoryTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductSubcategoryTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ProductTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\SettingTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\PatientTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ReturnCodeTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\SpecialtyTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\SubSpecialtyTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\DoctorTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ClinicTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ClinicDoctorTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ClinicMedicineTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ClinicPatientTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\ClinicPatientDoctorTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\SecretaryTableSeeder::class);
        $this->call(Illuminate\Database\Seeder\DoctorSecretaryTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::reguard();
    }
}
