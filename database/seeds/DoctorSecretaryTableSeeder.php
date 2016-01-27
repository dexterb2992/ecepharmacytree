<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

use ECEPharmacyTree\DoctorSecretary;


class DoctorSecretaryTableSeeder extends Seeder
{
	public function run()
	{
		DoctorSecretary::where('id', '>', 0)->delete();
		$doctor_secretary = array(
			array('doctor_id' => '2','secretary_id' => '11','is_active' => '0','username' => 'asdf','password' => '912ec803b2ce49e4a541068d495ab570','created_at' => '2015-12-16 20:55:36','updated_at' => '0000-00-00 00:00:00'),
			array('doctor_id' => '2','secretary_id' => '12','is_active' => '1','username' => 'asdff','password' => '912ec803b2ce49e4a541068d495ab570','created_at' => '2015-12-16 21:57:55','updated_at' => '0000-00-00 00:00:00'),
			array('doctor_id' => '2','secretary_id' => '13','is_active' => '0','username' => 'asfgbb','password' => '0ec53c34ceb021b4c7907d31db2ff752','created_at' => '2015-12-18 03:02:27','updated_at' => '0000-00-00 00:00:00'),
			array('doctor_id' => '3','secretary_id' => '14','is_active' => '1','username' => 'marg','password' => '472c9ee8ef06a0cb7d5585dd5c85b625','created_at' => '2015-12-18 22:45:47','updated_at' => '0000-00-00 00:00:00'),
			array('doctor_id' => '2','secretary_id' => '14','is_active' => '1','username' => 'marj','password' => 'af6c609ae89ebd77f4fb68af1115bd69','created_at' => '2015-12-18 21:40:55','updated_at' => '0000-00-00 00:00:00'),
			array('doctor_id' => '3','secretary_id' => '11','is_active' => '1','username' => 'qwer','password' => '962012d09b8170d912f0669f6d7d9d07','created_at' => '2015-12-18 22:45:25','updated_at' => '0000-00-00 00:00:00')
			);
DoctorSecretary::insert($clinic_doctor);
}
}
