<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

use ECEPharmacyTree\ClinicPatientDoctor;
class ClinicPatientDoctorTableSeeder extends Seeder
{
	public function run()
	{
		ClinicPatientDoctor::where('id', '>', 0)->delete();
		$clinic_patient_doctor = array(
			array('id' => '7','clinic_id' => '2','doctor_id' => '3','clinic_patients_id' => '7','patient_id' => '37','username' => 'ylRtGU','password' => 'YLhT7k','is_new' => '1','created_at' => '2016-01-24 23:11:49','updated_at' => '0000-00-00 00:00:00'),
			array('id' => '9','clinic_id' => '2','doctor_id' => '3','clinic_patients_id' => '9','patient_id' => '33','username' => '7It32k','password' => 'w9TsrK','is_new' => '1','created_at' => '2016-01-24 23:20:12','updated_at' => '0000-00-00 00:00:00')
			);

		ClinicPatientDoctor::insert($clinic_patient_doctor);
	}
}
