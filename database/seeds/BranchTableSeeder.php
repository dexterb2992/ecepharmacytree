<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\Branch;

class BranchTableSeeder extends Seeder
{
    public function run()
    {
        Branch::truncate();
        $branches = array(
          array('id' => '1','name' => 'ECE Marketing Naga','additional_address' => 'Door C Benmar Bldg. 2','barangay_id' => '18587','latitude' => '13.620693578547499','longitude' => '123.22112048570557','telephone_numbers' => '','telefax' => '(054)472-8899','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-06 21:57:02','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '11','name' => 'ECE Marketing Lipa','additional_address' => 'Arika Heights Compound B. Reyes St.','barangay_id' => '12017','latitude' => '13.941876000000011','longitude' => '121.16461291904909','telephone_numbers' => '','telefax' => '(043)756-0348','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-16 20:03:13','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '13','name' => 'ECE Marketing Cebu','additional_address' => 'Door 3 Rafanan Warehouse Sitio-Rosal, Orel','barangay_id' => '26319','latitude' => '10.3379105','longitude' => '123.9221018','telephone_numbers' => '','telefax' => '(032)343-8135','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-16 20:25:50','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '14','name' => 'ECE Marketing Davao','additional_address' => '150 5th A Street Ecoland Subdivision','barangay_id' => '36286','latitude' => '7.051825158109874','longitude' => '125.59511903812859','telephone_numbers' => '','telefax' => '(082)297-5606 & 297-5145 & 297-8813','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-16 20:34:53','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '15','name' => 'ECE Marketing LA Union','additional_address' => 'Pennsylvania Avenue','barangay_id' => '4583','latitude' => '16.607870401337603','longitude' => '120.36600908518062','telephone_numbers' => '','telefax' => '(072)888-7732','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-16 20:37:56','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '16','name' => 'ECE Marketing Iloilo','additional_address' => 'Door 8, Mayflower Apartment, Mabini Street','barangay_id' => '22650','latitude' => '10.699356128843043','longitude' => '122.56492735952452','telephone_numbers' => '(033)335-1720','telefax' => '(033)335-1738','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-16 20:54:54','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '17','name' => 'ECE Marketing Bacolod','additional_address' => 'Door 4 Josefina D. Arroz Bldg., Lopez Jaena St.','barangay_id' => '24493','latitude' => '10.661557723535221','longitude' => '122.95470658650811','telephone_numbers' => '','telefax' => '(034)434-3601','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-16 21:02:53','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '18','name' => 'ECE Marketing Tacloban','additional_address' => 'chinese cemetery','barangay_id' => '29667','latitude' => '11.214019164987347','longitude' => '125.00051280583489','telephone_numbers' => '','telefax' => '(053) 832-4529','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-16 21:08:21','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '19','name' => 'ECE Marketing Cagayan','additional_address' => 'A- 3 RAC bldg. Pride Bus. Park','barangay_id' => '35352','latitude' => '8.474069312746668','longitude' => '124.68234454788512','telephone_numbers' => '','telefax' => ' (088)858-3684','mobile_numbers' => '','status' => '1','is_new' => '0','created_at' => '2015-11-16 21:13:57','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL),
          array('id' => '20','name' => 'ECE Gensan','additional_address' => '82 Aparente St., Dadiangas Heights, ','barangay_id' => '37642','latitude' => '6.125150946243104','longitude' => '125.17165467934569','telephone_numbers' => '','telefax' => '(083)554-9033 ','mobile_numbers' => '0922-8401630','status' => '1','is_new' => '0','created_at' => '2015-11-16 21:40:34','updated_at' => '2016-01-19 13:26:33','deleted_at' => NULL)
          );

Branch::insert($branches);
}
}