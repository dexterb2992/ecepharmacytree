<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
// use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\Doctor;

class DoctorTableSeeder extends Seeder
{
  public function run()
  {
        // TestDummy::times(20)->create('App\Post');
   Doctor::where('id', '>', 0)->delete();
   // $this->insertDoctor('Jose', '', 'de Castro', '12345', '1', 'josedecastro@gmail.com', 'abcd', 'e2fc714c4727ee9395f324cd2e7f331f');
   // $this->insertDoctor('Leticia', 'D', 'Abesamis', '2147483647', '1', '', 'rara', 'd8830ed2c45610e528dff4cb229524e9');
   $doctors = array(
    array('id' => '2','lname' => 'Camahalan','mname' => 'Masayao','fname' => 'Royette','prc_no' => '123333','sub_specialty_id' => '1','affiliation' => NULL,'email' => NULL,'username' => 'abcd','password' => 'e2fc714c4727ee9395f324cd2e7f331f','referral_id' => 'MCF123','points' => '0','is_new' => '0','created_at' => '2015-12-17 08:22:34','updated_at' => '2016-01-19 13:26:15','deleted_at' => NULL),
    array('id' => '3','lname' => 'Valeroso','mname' => 'Duterte','fname' => 'Ragesh','prc_no' => '2147483647','sub_specialty_id' => '1','affiliation' => NULL,'email' => NULL,'username' => 'rara','password' => 'd8830ed2c45610e528dff4cb229524e9','referral_id' => 'dwr125','points' => '467.5','is_new' => '0','created_at' => '0000-00-00 00:00:00','updated_at' => '2016-01-25 21:09:23','deleted_at' => NULL)
    );
   Doctor::insert($doctors);
 }


 // public function insertDoctor($fname, $mname, $lname, $prc_no, $sub_specialty_id, $email, $username, $password){
 //   $doctor = new Doctor;
 //   $doctor->fname = $fname;
 //   $doctor->mname = $mname;
 //   $doctor->lname = $lname;
 //   $doctor->prc_no = $prc_no;
 //   $doctor->sub_specialty_id = $sub_specialty_id;
 //   $doctor->email = $email;
 //   $doctor->username = $username;
 //   $doctor->password = $password;
 //   $doctor->save();
 // }
}
