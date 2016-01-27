<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\Setting;
// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class SettingTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        Setting::truncate();
        Setting::create([
        	"points" => 1,
        	"level_limit" => 3,
            "referral_commission" => 50, // 50%
            "commission_variation" => 50, // 50%
            "delivery_charge" => 25,
            "delivery_minimum" => 200
        ]);
    }
}
