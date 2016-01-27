<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

use ECEPharmacyTree\SubSpecialty;

class SubSpecialtyTableSeeder extends Seeder
{
	public function run()
	{
		SubSpecialty::where('id', '>', 0)->delete();
		$sub_specialties = array(
			array('id' => '1','name' => ' Cardiac electrophysiologists','specialty_id' => '1','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL)
			);
		SubSpecialty::insert($sub_specialties);
	}
}
