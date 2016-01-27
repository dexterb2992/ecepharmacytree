<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\Specialty;

class SpecialtyTableSeeder extends Seeder
{
	public function run()
	{
		Specialty::where('id', '>', 0)->delete();
		$specialties = array(
			array('id' => '1','name' => 'Cardiologists','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL)
			);

		Specialty::insert($specialties);
	}
}
