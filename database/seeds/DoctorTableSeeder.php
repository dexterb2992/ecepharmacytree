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
   $this->insertDoctor('Jose', '', 'de Castro', '12345', '1', 'josedecastro@gmail.com', 'abcd', 'e2fc714c4727ee9395f324cd2e7f331f');
   $this->insertDoctor('Leticia', 'D', 'Abesamis', '2147483647', '1', '', 'rara', 'd8830ed2c45610e528dff4cb229524e9');
 }


 public function insertDoctor($fname, $mname, $lname, $prc_no, $sub_specialty_id, $email, $username, $password){
   $doctor = new Doctor;
   $doctor->fname = $fname;
   $doctor->mname = $mname;
   $doctor->lname = $lname;
   $doctor->prc_no = $prc_no;
   $doctor->sub_specialty_id = $sub_specialty_id;
   $doctor->email = $email;
   $doctor->username = $username;
   $doctor->password = $password;
   $doctor->save();
 }
}
