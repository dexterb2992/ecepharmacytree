<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\Patient;

class PatientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Patient::where("id", ">", 0)->delete();
         Patient::create([
        	"fname" => "Zemiel",
        	"lname" => "Asma",
            "username" => "zem123", // 50%
            "password" => "2908a16d7643932b12227f7dfa3db449", // 50%
        	"mobile_no" => "09095331440",
            "birthdate" => "1994-12-7",
            "sex" => "Male",
        	"civil_status" => "Single",
        	"height" => "5.4",
        	"weight" => "75",
        	"address_barangay_id" => 2,
            "address_street" => "150th A St.",
        	"referral_id" => "ABC123"
        ]);
    }
}
