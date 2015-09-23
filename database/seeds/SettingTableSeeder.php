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
        Setting::where("id", ">", 0)->delete();
        Setting::create([
        	"points" => 1,
        	"level_limit" => 3,
        	"safety_stock" => 20,
        	"critical_stock" => 10
        ]);
    }
}
