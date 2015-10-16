<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class BranchTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $con = mysqli_connect(getenv('DB_HOST'), getenv('DB_DATABASE'), '01gwapoko01', getenv('DB_DATABASE'));

        $sql = "DELETE * FROM `ece_pharmacy_tree`.`branches`";
        $res = mysqli_query($con, $sql);

        $sql = " INSERT INTO `ece_pharmacy_tree`.`branches` (`id`, `name`, `unit_floor_room_no`, `building`, `lot_no`, `block_no`, `phase_no`, `address_house_no`, `address_street`, `address_barangay`, `address_city_municipality`, `address_province`, `address_region`, `address_zip`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES ('1', 'ECE Marketing - Davao', NULL, NULL, NULL, NULL, NULL, NULL, '50 5th St.', 'Ecoland Subdivision, Matina', 'Davao City', 'Davao del Sur', 'Davao Region (Region XI)', '8000', '1', '2015-10-12 00:00:00', '2015-10-12 00:00:00', NULL);";
        $res = mysqli_query($con, $sql);
        mysqli_close($con);
    }
}
