<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

use ECEPharmacyTree\Secretary;

class SecretaryTableSeeder extends Seeder
{
	public function run()
	{
		Secretary::truncate();
		$secretaries = array(
			array('id' => '11','fname' => 'meriel','mname' => 'masayao','lname' => 'camahalan','address_house_no' => NULL,'address_street' => '','barangay_id' => '1','cell_no' => NULL,'tel_no' => NULL,'email' => 'meriel@gmail.com','photo' => NULL,'is_new' => '1','created_at' => '2015-12-16 20:55:36','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL),
			array('id' => '12','fname' => 'Enrica Claire','mname' => 'Llego','lname' => 'Javier','address_house_no' => NULL,'address_street' => '','barangay_id' => '1','cell_no' => NULL,'tel_no' => NULL,'email' => 'eclaire@gmail.com','photo' => NULL,'is_new' => '1','created_at' => '2015-12-16 21:17:38','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL),
			array('id' => '13','fname' => 'Maria Sophia Anna Kristina','mname' => 'Makaahon','lname' => 'Makatigbas','address_house_no' => NULL,'address_street' => '','barangay_id' => '1','cell_no' => NULL,'tel_no' => NULL,'email' => 'ana123@gmail.com','photo' => NULL,'is_new' => '1','created_at' => '2015-12-18 03:02:27','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL),
			array('id' => '14','fname' => 'Margie ','mname' => 'Monera','lname' => 'Alviola','address_house_no' => NULL,'address_street' => '','barangay_id' => '1','cell_no' => NULL,'tel_no' => NULL,'email' => 'margie@gmail.com','photo' => NULL,'is_new' => '1','created_at' => '2015-12-18 21:40:55','updated_at' => '0000-00-00 00:00:00','deleted_at' => NULL)
			);
Secretary::insert($secretaries);
}
}
