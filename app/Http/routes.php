<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


// View::share('recent_settings', ECEPharmacyTree\Setting::latest()->first());
// View::share('critical_stocks', check_for_critical_stock());
// View::share('branches', ECEPharmacyTree\Branch::all());

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController'
]);


Route::get('/', ['as' => 'dashboard', 'middleware' => 'auth', 'uses' => 'UserController@dashboard']);

Route::get('home', function(){
	return redirect('/');
});

Route::get('try/', function(){
	ini_set('max_execution_time', 300);

	$data = file_get_contents(public_path()."/db-src/mainsource.dat");
		$arr_data =  explode(PHP_EOL, $data);
		$rows = [];
		$barangays = ["name", "municipality"];
		$municipalities = ["name", "province"];
		$provinces = ["name", "region"];
		$regions = ["name"];

		foreach ($arr_data as $key => $value) {
			$entry = preg_split("/[\t]/", $value);
			// $entry[0] = barangay
			// $entry[1] = municipalities		
			// $entry[2] = province
			// $entry[3] = region
			if( isset($entry[0]) && isset($entry[1]) )
				$barangays[$key] = [
					"name" => $entry[0],
					"municipality" => $entry[1]
				];
			if( isset($entry[1]) && isset($entry[2]) )
				$municipalities[$key] = [
					"name" => $entry[1],
					"province" => $entry[2]
				];
			if( isset($entry[2]) && isset($entry[3]))
				$provinces[$key] =  [
					"name" => $entry[2],
					"region" => $entry[3]
				];
			if( isset($entry[3]) )
				$regions[$key] = [
					"name" => $entry[3]
				];
		}

		// let's make sure our arrays are unique

		$regions = arrayUnique($regions);
		$provinces = arrayUnique($provinces);
		$municipalities = arrayUnique($municipalities);
		$barangays = arrayUnique($barangays);

		// add ids to every array

		## regions
		$x = 1;
		$new_array = [];
		$regions_txt = "";
		foreach ($regions as $key => $value) {
			// $new_array[$x] = $value;
			array_push($new_array, ["id" => $x, "name" => $value['name']]);
			// $regions_txt.= $x."\t".$value['name'].PHP_EOL;

			$x++;
		}
		$regions = $new_array;

		// save regions
		// file_put_contents(public_path()."/db-src/regions.final.dat", $regions_txt);
		// dd($regions);


		## provinces
		$x = 1;
		$new_array = [];
		$provinces_txt = "";
		foreach ($provinces as $province) {
			$new_array[$x]["id"] = $x;
			$new_array[$x]["name"] = $province["name"];
			$try_search = multi_array_search($province["region"], 'name', $regions);

			if( is_array($try_search) ){
				$new_array[$x]["region_id"] = $try_search[0]['id'];
			}else{
				$new_array[$x]["region_id"] = '0';
			}

			// dd($new_array[$x]["region_id"]);

			// $provinces_txt.= $x."\t".$province['name']."\t".$new_array[$x]["region_id"].PHP_EOL;
			$x++;
		}
		$provinces = $new_array;

		// file_put_contents(public_path()."/db-src/provinces.final.dat", $provinces_txt);
		// dd($provinces);


		## municipalities
		$x = 1;
		$new_array = [];
		$municipalities_txt = "";
		foreach ($municipalities as $municipality) {
			// $new_array[$x] = $municipality["name"];
			// $new_array["province_id"] = array_search($municipality["province"], $provinces);
			$try_search = multi_array_search($municipality["province"], 'name', $provinces);
			// pre($try_search);
			if(is_array($try_search)){
				$new_array[$x]['name'] = $municipality["name"];
				$new_array[$x]["id"] = $x;
				$new_array[$x]['province_id'] = $try_search[0]['id'];
			}

			// $municipalities_txt.= $x."\t".$municipality['name']."\t".$try_search[0]['id'].PHP_EOL;
			$x++;
		}
		$municipalities = $new_array;
		// file_put_contents(public_path()."/db-src/municipalities.final.dat", $municipalities_txt);


		## barangays
		$x = 1;
		$new_array = [];
		$barangays_txt = "";
		foreach ($barangays as $barangay) {
			// $new_array[$x] = $barangay["name"];
			// $new_array[$x]['id'] = $x;
			// $new_array["municipality_id"] = array_search($barangay["municipality"], $municipalities);

			$try_search = multi_array_search($barangay["municipality"], 'name', $municipalities);
			pre($try_search[0]['name']);
			if(is_array($try_search)){
				$new_array[$x]['id'] = $x;
				$new_array[$x]["name"] = $barangay["name"];
				$new_array[$x]['municipality_id'] = $try_search[0]['id'];

				$barangays_txt.= $x."\t".$barangay["name"]."\t".$new_array[$x]['municipality_id'].PHP_EOL;
			}


			$x++;
		}
		$barangays = $new_array;

		file_put_contents(public_path()."/db-src/barangays.final.dat", $barangays_txt);

		// dd($barangays);


		// return array_values($rows);
		/*$regions = array_unique($regions);

		$regions_new = "";
		// let's replace the regions here with the regions' array key
		$x = 1;
		foreach ($regions as $key => $value) {
			$data = str_replace($value, $key, $data);
			$regions_new .= $x."\t".$value.PHP_EOL;
			$x++;
		}

		file_put_contents(public_path()."/db-src/regions.final.dat", $regions_new);

		$data = file_get_contents(public_path()."/db-src/barangays.php");
		$arr_data =  explode(PHP_EOL, $data);*/

		// $columns = ['id', 'name'];
	 //    $regions = extract_db_to_array(public_path()."/db-src/new-regions.dat", $columns);
	 //    ECEPharmacyTree\Region::where('id', '>', 0)->delete();
		// ECEPharmacyTree\Region::insert($regions);
		// dd($regions);
	
	##########################################################################################
});

Route::post('choose-branch', ['as' => 'choose_branch', 'uses' => 'UserController@setBranchToLogin']);

// Routes used for /profile
	Route::post('admin/update-password', ['as' => 'update_password', 'middleware' => 'auth', 'uses' => 'UserController@update_password']);
	Route::get('admin/update-password', ['as' => 'update_password', 'middleware' => 'auth', 'uses' => 'UserController@update_password']);
	Route::get('profile', ['as' => 'profile', 'uses' => 'UserController@show']);
	Route::post('profile', ['as' => 'update_photo', 'middleware' => 'auth', 'uses' => 'UserController@update_photo']);
	Route::post('profile/update', ['as' => 'update_profile', 'uses' => 'UserController@update']);

	Route::get('employees', ['as' => 'employees', 'uses' => 'UserController@index']);


Route::group(['prefix' => 'branches', 'as' => 'Branches::', 'middleware' => 'auth'], function (){
	/**
	 * Routes for Branches
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'BranchController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'BranchController@show']);
	Route::post('create', ['as' => 'create', 'uses' => 'BranchController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'BranchController@update']);
	Route::post('deactivate', ['as' => 'deactivate', 'uses' => 'BranchController@activate_deactivate']);
	Route::post('delete', ['as' => 'remove', 'uses' => 'BranchController@destroy']);
});



Route::group(['prefix' => 'products', 'middleware' => 'auth', 'as' => 'Products::'], function (){
	/**
	 * Routes for Products and Product Categories & SubCategories
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'ProductController@show']);
	Route::post('create', ['as' => 'create', 'uses' => 'ProductController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'ProductController@update']);
	Route::post('delete', ['as' => 'delete', 'uses' => 'ProductController@destroy']);
	
});

Route::group(['prefix' => 'products-categories', 'as' => 'ProductCategory::', 'middleware' => 'auth'], function (){
	/**
	 * Routes for Product Categories & SubCategories
	 */
	Route::get('/', ['as' => 'index','uses' => 'ProductCategoryController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'ProductCategoryController@show']);
	Route::get('subcategories/{id}', ['as' => 'product_subcategories', 'uses' => 'ProductSubCategoryController@show']);
	
	Route::post('create', ['as' => 'create', 'uses' => 'ProductCategoryController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'ProductCategoryController@update']);
	Route::post('delete', ['as' => 'remove', 'uses' => 'ProductCategoryController@destroy']);
	Route::post('subcategories/create', ['as' => 'create_product_subcategory', 'uses' => 'ProductSubCategoryController@store']);
	Route::post('subcategories/edit', ['as' => 'edit_product_subcategory', 'uses' => 'ProductSubCategoryController@update']);
	Route::post('subcategories/delete', ['as' => 'remove_product_subcategory', 'uses' => 'ProductSubCategoryController@destroy']);

});



Route::group(['prefix' => 'members', 'as' => 'Members::', 'middleware' => 'auth'], function (){
	/**
	 *	Routes for Members/parents
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'PatientController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'PatientController@show']);
	Route::post('deactivate', ['as' => 'delete', 'uses' => 'PatientController@destroy']);
	Route::post('unblock', ['as' => 'unblock', 'uses' => 'PatientController@unblock']);
});


Route::group(['prefix' => 'inventory', 'as' => 'Inventory::', 'middleware' => 'auth'], function (){
	/**
	 * Routes for inventories
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'InventoryController@index']);
	Route::get('{id}', ['get', 'uses' => 'InventoryController@show']);
	Route::post('create', ['as' => 'create', 'uses' => 'InventoryController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'InventoryController@update']);
	Route::post('delete', ['as' => 'delete', 'uses' => 'InventoryController@destroy']);
});


Route::group(['prefix' => 'doctor-specialties', 'as' => 'DoctorSpecialty::', 'middleware' => 'admin'], function (){
	/**
	 * Routes for Doctors and Doctor Specialties
	 */
	Route::get("/", ['as' => 'index', 'uses' => 'SpecialtyController@index']);
	Route::get("{id}", ['as' => 'get', 'uses' => 'SpecialtyController@show']);
	Route::get("subspecialties/{id}", ['as' => 'show_subspecialties', 'uses' => 'SubspecialtyController@show']);

	Route::post('create', ['as' => 'create', 'uses' => 'SpecialtyController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'SpecialtyController@update']);
	Route::post('delete', ['as' => 'remove', 'uses' => 'SpecialtyController@destroy']);

	Route::post('subspecialties/create', ['as' => 'create_doctor_subspecialty', 'uses' => 'SubspecialtyController@store']);
	Route::post('subspecialties/edit', ['as' => 'edit_doctor_subspecialty', 'uses' => 'SubspecialtyController@update']);
	Route::post('subspecialties/delete', ['as' => 'remove_doctor_subspecialty', 'uses' => 'SubspecialtyController@destroy']);
});

/** Routes for Doctors and Doctor Specialties
 * 
 */
	Route::get("doctors", ['as' => 'doctors', 'uses' => 'DoctorController@index']);
	Route::get("doctors/{id}", ['as' => 'get_doctor', 'uses' => 'DoctorController@show']);

	Route::post('doctors/create', ['as' => 'create_doctor', 'uses' => 'DoctorController@store']);
	Route::post('doctors/edit', ['as' => 'edit_doctor', 'uses' => 'DoctorController@edit' ]);

	Route::post('doctor-specialties/create', [ 'as' => 'create_specialties_category', 'uses' => 'SpecialtyController@store'] );
	Route::post('doctor-specialties/edit', [ 'as' => 'edit_specialties_category', 'uses' => 'SpecialtyController@update'] );
	Route::post('doctor-specialties/delete', [ 'as' => 'remove_specialties_category', 'uses' => 'SpecialtyController@destroy' ]);

Route::group(['prefix' => 'promos', 'as' => 'Promo::', 'middleware' => 'auth'], function (){
	/**
	 * Routes for Promo
	 */
	Route::get("/", ["as" => "index", 'uses' => 'PromoController@index']);
	Route::get("{id}", ['as' => 'get', 'uses' => 'PromoController@show']);

	Route::post('create', ['as' => 'create', 'uses' => 'PromoController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'PromoController@update']);
	Route::post('delete', ['as' => 'remove', 'uses' => 'PromoController@destroy']);
});



//Routes for Clinics

	Route::get('clinics', ['as' => 'clinics', 'uses' => 'ClinicController@index']);
	Route::get('clinics/{id}', ['as' => 'get_clinic', 'uses' => 'ClinicController@show']);

	Route::post('clinics/create', ['as' => 'create_clinic', 'uses' => 'ClinicController@store']);

	Route::post('clinics/edit', ['as' => 'edit_clinic', 'uses' => 'ClinicController@update' ]);
	Route::post('clinics/delete', ['as' => 'delete_clinic', 'uses' => 'ClinicController@destroy']);

Route::group(['prefix' => 'settings', 'as' => 'Settings::', 'middleware' => 'admin'], function (){
	/**
	 * Routes for Admin Settings
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'SettingsController@index']);
	Route::post('referral/update', ['as' => 'update', 'uses' => 'SettingsController@update']);
});



//Routes for Prescription Approval
	Route::get('prescription-approval/', ['as' => 'prescription_approval', 'uses' => 'PrescriptionApprovalController@index']);

	Route::post('prescription-approval/disapprove', ['as' => 'prescription-approval-disapprove', 'uses' => 'PrescriptionApprovalController@disapprove']);

	Route::post('prescription-approval/approve', ['as' => 'prescription-approval-approve', 'uses' => 'PrescriptionApprovalController@approve']);

Route::group(['prefix' => 'affiliates', 'as' => 'Affiliates::', 'middleware' => 'auth'], function (){
	/**
	 * Routes for Affiliates
	 */
	Route::get("/", ["as" => "index", 'uses' => 'AffiliatesController@index']);
});

Route::get('orders', ['as' => 'orders', 'uses' => 'OrderController@index']);
Route::get('orders/{id}', ['as' => 'get_order', 'uses' => 'OrderController@show']);
Route::post('orders/mark_as_paid/{id}', ['as' => 'mark_order_as_paid', 'uses' => 'BillingController@mark_order_as_paid']);
Route::post('fulfill_orders', ['as' => 'fulfill_orders', 'uses' => 'OrderController@fulfill_orders']);

Route::get('images/{template}/', function($template){
	return redirect(url('images/'.$template."/nophoto.png"));
});

Route::get('sales', ['as' => 'sales', 'uses' => 'SaleController@index']);

/**
 * @param string $location = ['provinces', 'municipalities']
 * @param int $id
 *
 * @return json $response
 */
Route::get('locations/get/regions/', 'LocationController@show');
Route::get('locations/get/{get_location}/where-{parent_location}/{parent_location_id}', 'LocationController@show');

