<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\Branch;

class BranchTableSeeder extends Seeder
{
    public function run()
    {
        $branch = new Branch;
        $branch->id = 1;
        $branch->name = "Dexter Drugstore - Cabantian";
        $branch->barangay_id = 4;
        $branch->additional_address = "Only Good-looking St, Dexter Subdivision";
        $branch->telephone_numbers = '(082) 876 090, 123 456';
        $branch->telefax = '324 343 3244';
        $branch->mobile_numbers = '09232404931, 0912133243';
        $branch->save();
    }
}
