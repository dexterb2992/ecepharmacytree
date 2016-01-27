<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

use ECEPharmacyTree\ClinicDoctor;


class ClinicDoctorTableSeeder extends Seeder
{
	public function run()
	{
		ClinicDoctor::where('id', '>', 0)->delete();
		$clinic_doctor = array(
			array('id' => '1','doctor_id' => '2','clinic_id' => '1','clinic_sched' => 'Monday - Friday
				8:00 am - 5:00 pm','is_active' => '1','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00'),
			array('id' => '2','doctor_id' => '3','clinic_id' => '2','clinic_sched' => 'Everyday - 8:00 am - 7:00 pm','is_active' => '1','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00'),
			array('id' => '3','doctor_id' => '10','clinic_id' => '2','clinic_sched' => 'Daily 8 - 5 PM','is_active' => '1','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00')
			);
		ClinicDoctor::insert($clinic_doctor);
	}
}
