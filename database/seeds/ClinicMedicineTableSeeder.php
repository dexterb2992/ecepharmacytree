<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

use ECEPharmacyTree\ClinicMedicine;
class ClinicMedicineTableSeeder extends Seeder
{
	public function run()
	{
		ClinicMedicine::where('id', '>', 0)->delete();
		$clinic_medicines = array(
			array('id' => '8','clinic_id' => '2','med_name' => 'Paracetamol Biogesic 500mg Tab','is_new' => '1','created_at' => '2016-01-23 05:54:02','updated_at' => '0000-00-00 00:00:00'),
			array('id' => '9','clinic_id' => '2','med_name' => 'Ibufrofen Medicol 500mg Cap','is_new' => '1','created_at' => '2016-01-23 05:54:02','updated_at' => '2016-01-24 23:21:13'),
			array('id' => '10','clinic_id' => '2','med_name' => 'Cefalexin 500mg exel','is_new' => '1','created_at' => '2016-01-23 05:54:02','updated_at' => '0000-00-00 00:00:00'),
			array('id' => '11','clinic_id' => '2','med_name' => 'Ascorbic Acid Ceelin 60ml','is_new' => '1','created_at' => '2016-01-23 05:54:02','updated_at' => '0000-00-00 00:00:00'),
			array('id' => '12','clinic_id' => '2','med_name' => 'Para 500mg Axmel','is_new' => '1','created_at' => '2016-01-23 05:54:03','updated_at' => '0000-00-00 00:00:00'),
			array('id' => '13','clinic_id' => '2','med_name' => 'Mulit-Vitamins(MaxVit)','is_new' => '1','created_at' => '2016-01-23 05:54:03','updated_at' => '0000-00-00 00:00:00'),
			array('id' => '14','clinic_id' => '2','med_name' => 'Mefanmic 500mg','is_new' => '1','created_at' => '2016-01-23 05:54:03','updated_at' => '0000-00-00 00:00:00')
			);
ClinicMedicine::insert($clinic_medicines);
}
}
