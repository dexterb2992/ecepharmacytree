<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;
use ECEPharmacyTree\User;
// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        User::where("id", ">", "0")->delete();

    	$user = new User;
    	$user->id = 2;
    	$user->fname = "John";
    	$user->mname = "Smith";
    	$user->lname = "Doe";
    	$user->email = "johndoe@example.com";
    	$user->password = bcrypt("admin");
    	$user->branch_id = 1;
    	$user->access_level = 1;   /** 
                                    * Note: 1 => Admin, 2 => maybe, employees 
                                    * or any other stuff who are allowed to access
                                    * limited pages.
                                    * 3 => maybe, branch administrators?
                                    */
    	$user->save();
    }
}
