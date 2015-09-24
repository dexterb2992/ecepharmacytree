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

View::share('recent_settings', ECEPharmacyTree\Setting::latest()->first());

Route::get('/', ['as' => 'dashboard', 'uses' => function () {
	$recently_added_products = ECEPharmacyTree\Product::latest()->limit(4)->get();
	return view('dashboard')->withRecently_added_products($recently_added_products)->withTitle("Dashboard");
}]);

Route::get("try", function (){
	$set = get_recent_settings();
	pre($set);
});

Route::group(['prefix' => 'branches', 'as' => 'Branches::'], function (){
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



Route::group(['prefix' => 'products', 'as' => 'Products::'], function (){
	/**
	 * Routes for Products and Product Categories & SubCategories
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'ProductController@show']);
	Route::post('create', ['as' => 'create', 'uses' => 'ProductController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'ProductController@update']);
	Route::post('delete', ['as' => 'delete', 'uses' => 'ProductController@destroy']);
	
});

Route::group(['prefix' => 'products-categories', 'as' => 'ProductCategory::'], function (){
	/**
	 * Routes for Product Categories & SubCategories
	 */
	Route::get('/', ['as' => 'index','uses' => 'ProductCategoryController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'ProductCategoryController@show']);
	Route::get('subcategories/{id}', ['as' => 'product_subcategories', 'uses' => 'ProductSubcategoryController@show']);
	
	Route::post('create', ['as' => 'create', 'uses' => 'ProductCategoryController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'ProductCategoryController@update']);
	Route::post('delete', ['as' => 'remove', 'uses' => 'ProductCategoryController@destroy']);
	Route::post('subcategories/create', ['as' => 'create_product_subcategory', 'uses' => 'ProductSubcategoryController@store']);
	Route::post('subcategories/edit', ['as' => 'edit_product_subcategory', 'uses' => 'ProductSubcategoryController@update']);
	Route::post('subcategories/delete', ['as' => 'remove_product_subcategory', 'uses' => 'ProductSubcategoryController@destroy']);

});



Route::group(['prefix' => 'members', 'as' => 'Members::'], function (){
	/**
	 *	Routes for Members/Patients
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'PatientController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'PatientController@show']);
	Route::post('deactivate', ['as' => 'delete', 'uses' => 'PatientController@destroy']);
	Route::post('unblock', ['as' => 'unblock', 'uses' => 'PatientController@unblock']);
});


Route::get('samplesoftdelete', 'ReferralSettingController@destroy');


Route::group(['prefix' => 'inventory', 'as' => 'Inventory::'], function (){
	/**
	 * Routes for inventories
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'InventoryController@index']);
	Route::get('{id}', ['get', 'uses' => 'InventoryController@show']);
	Route::post('create', ['as' => 'create', 'uses' => 'InventoryController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'InventoryController@update']);
	Route::post('delete', ['as' => 'delete', 'uses' => 'InventoryController@destroy']);
});


Route::group(['prefix' => 'doctor-specialties', 'as' => 'DoctorSpecialty::'], function (){
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

Route::get("doctors", ['as' => 'doctors', 'uses' => 'DoctorController@index']);
Route::get("doctors/{id}", ['as' => 'get_doctor', 'uses' => 'DoctorController@show']);

Route::post('doctors/create', ['as' => 'create_doctor', 'uses' => 'DoctorController@store']);
Route::post('doctors/edit', ['as' => 'edit_doctor', 'uses' => 'DoctorController@edit' ]);

Route::post('doctor-specialties/create', [ 'as' => 'create_specialties_category', 'uses' => 'SpecialtyController@store'] );
Route::post('doctor-specialties/edit', [ 'as' => 'edit_specialties_category', 'uses' => 'SpecialtyController@update'] );
Route::post('doctor-specialties/delete', [ 'as' => 'remove_specialties_category', 'uses' => 'SpecialtyController@destroy' ]);

Route::group(['prefix' => 'promos', 'as' => 'Promo::'], function (){
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

Route::post('clinics/edit', ['as' => 'edit_clinic', 'uses' => 'ClinicController@edit' ]);


Route::group(['prefix' => 'settings', 'as' => 'Settings::'], function (){
	/**
	 * Routes for Admin Settings
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'SettingsController@index']);
	Route::post('referral/update', ['as' => 'update', 'uses' => 'SettingsController@update']);
});

Route::post('clinics/edit', ['as' => 'edit_clinic', 'uses' => 'ClinicController@update' ]);
Route::post('clinics/delete', ['as' => 'delete_clinic', 'uses' => 'ClinicController@destroy']);

//Routes for Prescription Approval
Route::get('prescription-approval/', ['as' => 'prescription_approval', 'uses' => 'PrescriptionApprovalController@index']);

Route::post('prescription-approval/disapprove', ['as' => 'prescription-approval-disapprove', 'uses' => 'PrescriptionApprovalController@disapprove']);

Route::post('prescription-approval/approve', ['as' => 'prescription-approval-approve', 'uses' => 'PrescriptionApprovalController@approve']);

Route::group(['prefix' => 'affiliates', 'as' => 'Affiliates::'], function (){
	/**
	 * Routes for Affiliates
	 */
	Route::get("/", ["as" => "index", 'uses' => 'AffiliatesController@index']);
});