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


// Route::filter('csrf', function()
// {
//     if (Session::token() == "")
//     {
//         Session::regenerateToken();
//     }
//     Session::regenerateToken();
// });

Route::get('/', function () {
    return view('welcome');
});

// Route::get("try", function(){
// 	return view("404");
// });

/**
 * Routes for Branches
 */
Route::get('branches', [ 'as' => 'branches', 'uses' => 'BranchController@index'] );
Route::get('branches/{id}', [ 'as' => 'get_branch', 'uses' => 'BranchController@show'] );
Route::post('branches/create', [ 'as' => 'create_branch', 'uses' => 'BranchController@store'] );
Route::post('branches/edit', [ 'as' => 'edit_branch', 'uses' => 'BranchController@edit'] );
Route::post('branches/deactivate', [ 'as' => 'deactivate_branch', 'uses' => 'BranchController@activate_deactivate' ]);
Route::post('branches/delete', [ 'as' => 'remove_branch', 'uses' => 'BranchController@destroy' ]);


/**
 * Routes for Products and Product Categories & SubCategories
 */
Route::get('products', [ 'as' => 'products', 'uses' => 'ProductController@index' ]);
Route::get('products/{id}', [ 'as' => 'show_product', 'uses' => 'ProductController@show' ]);
Route::get('products-categories', [ 'as' => 'product_categories', 'uses' => 'ProductCategoryController@index' ]);
Route::get('products-categories/{id}', [ 'as' => 'get_product_categories', 'uses' => 'ProductCategoryController@show'] );
Route::get('products-categories/subcategories/{id}', [ 'as' => 'product_subcategories', 'uses' => 'ProductSubcategoryController@show' ]);

/*
	Routes for Members/Patients
*/
Route::get('members', [ 'as' => 'members', 'uses' => 'PatientController@index' ]);
Route::get('members/{id}', [ 'as' => 'get_member', 'uses' => 'PatientController@show'] );
Route::post('members/deactivate', ['as' => 'delete_member', 'uses' => 'PatientController@destroy']);
Route::post('members/unblock', ['as' => 'unblock_member', 'uses' => 'PatientController@unblock']);


Route::get('samplesoftdelete', 'ReferralSettingController@destroy');

Route::post('products/create', [ 'as' => 'create_product', 'uses' => 'ProductController@store' ]);
Route::post('products-categories/create', [ 'as' => 'create_product_category', 'uses' => 'ProductCategoryController@store'] );
Route::post('products-categories/edit', [ 'as' => 'edit_product_category', 'uses' => 'ProductCategoryController@edit'] );
Route::post('products-categories/delete', [ 'as' => 'remove_product_category', 'uses' => 'ProductCategoryController@destroy' ]);
Route::post('products-categories/subcategories/create', [ 'as' => 'create_product_subcategory', 'uses' => 'ProductSubcategoryController@store'] );
Route::post('products-categories/subcategories/edit', [ 'as' => 'edit_product_subcategory', 'uses' => 'ProductSubcategoryController@edit'] );
Route::post('products-categories/subcategories/delete', [ 'as' => 'remove_product_subcategory', 'uses' => 'ProductSubcategoryController@destroy' ]);


/**
 * Routes for inventories
 */

Route::get('inventory', [ 'as' => 'inventory', 'uses' => 'InventoryController@index' ]);
Route::get('inventory/{id}', [ 'show_inventory', 'uses' => 'InventoryController@show' ]);
Route::post('inventory/create', [ 'as' => 'create_inventory', 'uses' => 'InventoryController@store' ]);
Route::post('inventory/edit', [ 'as' => 'edit_inventory', 'uses' => 'InventoryController@edit' ]);
Route::post('inventory/delete', [ 'as' => 'delete_inventory', 'uses' => 'InventoryController@destroy' ]);



/**
 * Routes for Doctors and Doctor Specialties
 */
Route::get("doctor-specialties", [ 'as' => 'doctor_specialties', 'uses' => 'SpecialtyController@index' ]);
Route::get("doctor-specialties/{id}", [ 'as' => 'show_specialties', 'uses' => 'SpecialtyController@show' ]);
Route::get("doctor-specialties/subspecialties/{id}", [ 'as' => 'show_subspecialties', 'uses' => 'SubspecialtyController@show' ]);

Route::get("doctors", ['as' => 'doctors', 'uses' => 'DoctorController@index']);
Route::get("doctors/{id}", ['as' => 'get_doctor', 'uses' => 'DoctorController@show']);

Route::post('doctors/create', ['as' => 'create_doctor', 'uses' => 'DoctorController@store']);
Route::post('doctors/edit', ['as' => 'edit_doctor', 'uses' => 'DoctorController@edit' ]);

Route::post('doctor-specialties/create', [ 'as' => 'create_specialties_category', 'uses' => 'SpecialtyController@store'] );
Route::post('doctor-specialties/edit', [ 'as' => 'edit_specialties_category', 'uses' => 'SpecialtyController@update'] );
Route::post('doctor-specialties/delete', [ 'as' => 'remove_specialties_category', 'uses' => 'SpecialtyController@destroy' ]);

Route::post('doctor-specialties/subspecialties/create', [ 'as' => 'create_doctor_subspecialty', 'uses' => 'SubspecialtyController@store'] );
Route::post('doctor-specialties/subspecialties/edit', [ 'as' => 'edit_doctor_subspecialty', 'uses' => 'SubspecialtyController@update'] );
Route::post('doctor-specialties/subspecialties/delete', [ 'as' => 'remove_doctor_subspecialty', 'uses' => 'SubspecialtyController@destroy' ]);

//Routes for Clinics

Route::get('clinics', ['as' => 'clinics', 'uses' => 'ClinicController@index']);
Route::get('clinics/{id}', ['as' => 'get_clinic', 'uses' => 'ClinicController@show']);

Route::post('clinics/create', ['as' => 'create_clinic', 'uses' => 'ClinicController@store']);
Route::post('clinics/edit', ['as' => 'edit_clinic', 'uses' => 'ClinicController@edit' ]);


// Route::get('/members', );
