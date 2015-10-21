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
        	"address_barangay" => "Deca Homes Tigatto",
        	"address_city_municipality" => "Davao City",
        	"address_region" => "Region XI",
        	"address_zip" => "8000",
        	"referral_id" => "ABC123",
        	"created_at" => new DateTime;
        ]);
    }
}
