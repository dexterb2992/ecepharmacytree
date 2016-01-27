<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

use ECEPharmacyTree\Clinic;
use DB;

class ClinicTableSeeder extends Seeder
{
	public function run()
	{
		Clinic::truncate();
		$clinics = array(
			array('id' => '1','name' => 'Puntaverde Clinic','contact_no' => '09263327674','addition_address' => 'B69 L17 Lapu-lapu Street','barangay_id' => '2','latitude' => '123.1231','longitude' => '1234.12312312','is_new' => '1','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL),
			array('id' => '2','name' => 'Samal Clinic','contact_no' => '299-1127','addition_address' => 'Babak ','barangay_id' => '4','latitude' => '123.123123','longitude' => '123.12312','is_new' => '1','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL)
			);
		Clinic::insert($clinics);
	}
}
