<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

use ECEPharmacyTree\ClinicPatient;

class ClinicPatientTableSeeder extends Seeder
{
	public function run()
	{
		ClinicPatient::where('id', '>', 0)->delete();
		$clinic_patients = array(
			array('id' => '5','fname' => 'Margie','mname' => '','lname' => 'Monera','email_address' => '','mobile_no' => '09123456789','tel_no' => '','photo' => NULL,'occupation' => '','birthdate' => '1995-01-29','sex' => 'female','civil_status' => 'Single','height' => '4\'6','weight' => '43','optional_address' => '','address_street' => 'ecoland','address_barangay_id' => '36285','is_new' => '1','created_at' => '2016-01-22 21:27:28','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL),
			array('id' => '7','fname' => 'Erwin','mname' => '','lname' => 'Bisnar','email_address' => '','mobile_no' => '09121232141243','tel_no' => '','photo' => '','occupation' => '','birthdate' => '1994-11-07','sex' => 'Male','civil_status' => 'Married','height' => '5\'5','weight' => '170','optional_address' => '','address_street' => '150 5th A street','address_barangay_id' => '36286','is_new' => '1','created_at' => '2016-01-25 11:22:17','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL),
			array('id' => '9','fname' => 'Rosell','mname' => 'Bordado','lname' => 'Barnes','email_address' => '','mobile_no' => '09078843578','tel_no' => '','photo' => '','occupation' => '','birthdate' => '1995-02-23','sex' => 'Female','civil_status' => 'Single','height' => '5\'4','weight' => '60','optional_address' => '#4','address_street' => 'R. castillo st.','address_barangay_id' => '36270','is_new' => '1','created_at' => '2016-01-22 16:21:27','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL)
			);

ClinicPatient::insert($clinic_patients);
}
}
