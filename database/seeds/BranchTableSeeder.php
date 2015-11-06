<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\Branch;

class BranchTableSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
        $branch = new Branch;
        $branch->id = 1;
        $branch->name = "Dexter Drugstore - Cabantian";
        $branch->barangay_id = 4;
        $branch->additional_address = "Only Good-looking St, Dexter Subdivision";
        $branch->telephone_numbers = '(082) 876 090, 123 456';
        $branch->telefax = '324 343 3244';
        $branch->mobile_numbers = '09232404931, 0912133243';
        $branch->save();
=======
        // TestDummy::times(20)->create('App\Post');
        $con = mysqli_connect(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

        $sql = "DELETE * FROM `ece_pharmacy_tree`.`branches`";
        $res = mysqli_query($con, $sql);

        // $sql = " INSERT INTO `ece_pharmacy_tree`.`branches` (`id`, `name`, `unit_floor_room_no`, `building`, 
        //         `lot_no`, `block_no`, `phase_no`, `address_house_no`, `address_street`, `address_barangay`, `address_city_municipality`,
        //         `address_province`, `address_region`, `address_zip`, `status`, `created_at`, `updated_at`, `deleted_at`) 
        //     VALUES ('1', 'ECE Marketing - Davao', NULL, NULL, NULL, NULL, NULL, NULL, '50 5th St.', 'Ecoland Subdivision, Matina', 
        //         'Davao City', 'Davao del Sur', 'Davao Region (Region XI)', '8000', '1', '2015-10-12 00:00:00', '2015-10-12 00:00:00', NULL);";

        $sql = "INSERT INTO `ece_pharmacy_tree`.`branches` (`id`, `name`, `additional_address`, `barangay_id`, `status`, `created_at`, `updated_at`, `deleted_at`) 
            VALUES ('1', 'ECE Marketing - Davao', '50 5th St. Ecoland Subdivision', 1378, '1', '2015-10-12 00:00:00', '2015-10-12 00:00:00', NULL);";

        $res = mysqli_query($con, $sql);
        mysqli_close($con);
>>>>>>> 74a1c7bd316a897386984b23bd04eecc29c8fa25
    }
}
