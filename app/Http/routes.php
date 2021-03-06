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

if( Schema::hasTable('settings') && Schema::hasTable('branches') ){
	View::share('recent_settings', ECEPharmacyTree\Setting::latest()->first());
	View::share('branches', ECEPharmacyTree\Branch::all());
}

Route::get('showschema', 'ApiController@showschema');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController'
]);

Route::post('notify_customer', ['as' => 'notify_customer', 'uses' => 'ApiController@sendOrderNotification']);

// Route::get('test_num', function(){
// 	$value = 100;
// 	return $value;
// });
Route::get('/', ['as' => 'dashboard', 'middleware' => 'auth', 'uses' => 'UserController@dashboard']);

Route::get('getSeniorValidity', 'PatientController@getSeniorValidity');

Route::get('home', function(){
	return redirect('/');
});

Route::post('flush_user_basket_promos', 'BasketController@flush_user_basket_promos');
Route::get('upload_sc_id', 'SeniorCitizenController@store');
Route::post('saveBranchPreference', 'BranchController@saveBranchPreference');
Route::get('check_basket', 'BasketController@check_basket');
Route::get('compute-referral-points/{referral_id}', 'PointsController@store');
Route::get('compute_basket_points', 'BasketController@compute_basket_points');
Route::get('get_clinic_records', 'ApiController@getClinicRecords');


## Routes used in user /profile
	Route::post('admin/update-password', ['as' => 'update_password', 'middleware' => 'auth', 'uses' => 'UserController@update_password']);
	Route::get('admin/update-password', ['as' => 'update_password', 'middleware' => 'auth', 'uses' => 'UserController@update_password']);
	Route::get('profile', ['as' => 'profile', 'uses' => 'UserController@show']);
	Route::post('profile', ['as' => 'update_photo', 'middleware' => 'auth', 'uses' => 'UserController@update_photo']);
	Route::post('profile/update', ['as' => 'update_profile', 'uses' => 'UserController@update']);

## EMPLOYEES
	Route::get('employees', ['as' => 'employees', 'uses' => 'UserController@index']);
	Route::post('employees', ['as' => 'employees', 'uses' => 'UserController@create']);
	Route::post('employees/deactivate', ['as' => 'deactivate_employee', 'middleware' => 'admin',
		'uses' => 'UserController@destroy']);
	Route::post('employees/reactivate', ['as' => 'reactivate_employee', 'middleware' => 'admin',
		'uses' => 'UserController@reactivate']);
	Route::post('employee/change/branch', ['as'=> 'update_employee_branch', 'middleware' => 'admin',
		'uses' => 'UserController@update_branch']);


## Routes for Branches
Route::group(['prefix' => 'branches', 'as' => 'Branches::', 'middleware' => 'auth'], function (){
	Route::get('/', ['as' => 'index', 'uses' => 'BranchController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'BranchController@show']);
	Route::post('create', ['as' => 'create', 'uses' => 'BranchController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'BranchController@update']);
	Route::post('deactivate', ['as' => 'deactivate', 'uses' => 'BranchController@activate_deactivate']);
	Route::post('delete', ['as' => 'remove', 'uses' => 'BranchController@destroy']);
});
	Route::get("get-selected-branch", 'BranchController@show_selected_branch');
	Route::get('change-branch', ['uses' => 'BranchController@get_which_branch', 'middleware' => 'admin']);
	Route::post('choose-branch', ['as' => 'choose_branch', 'uses' => 'UserController@setBranchToLogin', 'middleware' => 'admin']);




## Routes for Products and Product Categories & SubCategories
Route::group(['prefix' => 'products', 'middleware' => 'auth', 'as' => 'Products::'], function (){
	Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
	Route::get('with-deleted', ['as' => 'all', 'uses' => 'ProductController@all_include_deleted']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'ProductController@show']);
	Route::post('create', ['as' => 'create', 'uses' => 'ProductController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'ProductController@update']);
	Route::post('delete', ['as' => 'delete', 'uses' => 'ProductController@destroy']);
	Route::post('all', ['as' => 'get_all', 'uses' => 'ProductController@show_all']);
	Route::post('deactivate', ['as' => 'deactivate', 'uses' => 'ProductController@restore']);


	Route::get('gallery/{product_id}', ['as' => 'gallery', 'uses' => 'ProductsGalleryController@show']);
	Route::get('gallery/primary/{product_id}', ['as' => 'gallery_primary', 'uses' => 'ProductsGalleryController@get_primary']);
	Route::post('gallery/upload', ['as' => 'add_gallery', 'uses' => 'ProductsGalleryController@store']);
	Route::post('gallery/delete/{id}', ['as' => 'delete_gallery', 'uses' => 'ProductsGalleryController@destroy']);
	Route::post('gallery/change-primary/{id}', ['as' => 'gallery_change_primary', 'uses' => 'ProductsGalleryController@change_primary']);
});
	Route::get('products-json', ['as' => 'json', 'uses' => 'ProductController@get_json'] );
	Route::get('search/products', ['as' => 'product_search', 'uses' => 'ProductController@search']);

## PRODUCT GROUPS
	Route::get('product-groups', ['as' => 'groups', 'middleware' => 'auth', 'uses' => 'ProductGroupController@index']);
	Route::get('product-groups/{id}', ['as' => 'show_group', 'middleware' => 'auth', 'uses' => 'ProductGroupController@show']);
	Route::post('product-groups/create', ['as' => 'create_groups', 'middleware' => 'auth', 'uses' => 'ProductGroupController@store']);
	Route::post('product-groups/edit', ['as' => 'edit_groups', 'middleware' => 'auth', 'uses' => 'ProductGroupController@update']);
	Route::post('product-groups/delete', ['as' => 'delete_groups', 'middleware' => 'auth', 'uses' => 'ProductGroupController@destroy']);

## Routes for Product Categories & SubCategories
Route::group(['prefix' => 'products-categories', 'as' => 'ProductCategory::', 'middleware' => 'auth'], function (){
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


## Routes for Members/patients
Route::group(['prefix' => 'members', 'as' => 'Members::', 'middleware' => 'auth'], function (){
	Route::get('/', ['as' => 'index', 'uses' => 'PatientController@index']);
	Route::get('{id}', ['as' => 'get', 'uses' => 'PatientController@show']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'PatientController@update']);
	Route::post('edit_beneficiary', ['as' => 'edit_beneficiary', 'uses' => 'PatientController@edit']);
	Route::post('deactivate', ['as' => 'delete', 'uses' => 'PatientController@destroy']);
	Route::post('unblock', ['as' => 'unblock', 'uses' => 'PatientController@unblock']);
});


## Routes for Inventories
Route::group(['prefix' => 'inventory', 'as' => 'Inventory::', 'middleware' => 'auth'], function (){
	Route::get('/', ['as' => 'index', 'uses' => 'InventoryController@index']);
	Route::get('all', ['as' => 'all', 'uses' => 'InventoryController@show_all']);
	Route::post('adjustment', ['as' => 'adjustment', 'uses' => 'InventoryController@add_adjustments']);
	Route::get('{id}', ['get', 'uses' => 'InventoryController@show']);
	Route::post('create', ['as' => 'create', 'uses' => 'InventoryController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'InventoryController@update']);
	Route::post('delete', ['as' => 'delete', 'uses' => 'InventoryController@destroy']);
	Route::post('logs', ['as' => 'inventory_logs', 'uses' => 'InventoryController@get_logs']);
	Route::post('items', ['as' => 'get_items', 'uses' => 'InventoryController@get_items']);
});

## Routes for Doctors and Doctor Specialties
Route::group(['prefix' => 'doctor-specialties', 'as' => 'DoctorSpecialty::', 'middleware' => 'admin'], function (){
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

## Routes for Doctors and Doctor Specialties
	Route::get("doctors", ['as' => 'doctors', 'uses' => 'DoctorController@index']);
	Route::get("doctors/{id}", ['as' => 'get_doctor', 'uses' => 'DoctorController@show']);

	Route::post("get-all-doctors", ['as' => 'get_all_doctors', 'uses' => 'DoctorController@get_all_doctors']);
	Route::post('doctors/create', ['as' => 'create_doctor', 'uses' => 'DoctorController@store']);
	Route::post('doctors/edit', ['as' => 'edit_doctor', 'uses' => 'DoctorController@edit' ]);
	Route::post('doctors/delete', ['as' => 'delete_doctor', 'uses' => 'DoctorController@delete' ]);

	Route::post('doctor-specialties/create', [ 'as' => 'create_specialties_category', 'uses' => 'SpecialtyController@store'] );
	Route::post('doctor-specialties/edit', [ 'as' => 'edit_specialties_category', 'uses' => 'SpecialtyController@update'] );
	Route::post('doctor-specialties/delete', [ 'as' => 'remove_specialties_category', 'uses' => 'SpecialtyController@destroy' ]);


## CLINICS

	Route::get('clinics', ['as' => 'clinics', 'uses' => 'ClinicController@index']);
	Route::get('clinics/{id}', ['as' => 'get_clinic', 'uses' => 'ClinicController@show']);
	
	Route::post('clinics/create', ['as' => 'create_clinic', 'uses' => 'ClinicController@store']);
	Route::post('clinics/edit', ['as' => 'edit_clinic', 'uses' => 'ClinicController@update' ]);
	Route::post('clinics/delete', ['as' => 'delete_clinic', 'uses' => 'ClinicController@destroy']);
	Route::post('clinic-doctor', ['as' => 'clinic_doctor', 'uses' => 'ClinicController@clinic_doctor']);


## SETTINGS
Route::group(['prefix' => 'settings', 'as' => 'Settings::', 'middleware' => 'admin'], function (){
	/**
	 * Routes for Admin Settings
	 */
	Route::get('/', ['as' => 'index', 'uses' => 'SettingsController@index']);
	Route::post('referral/update', ['as' => 'update', 'uses' => 'SettingsController@update']);
});

## PROMOS
Route::group(['prefix' => 'promos', 'as' => 'Promo::', 'middleware' => 'auth'], function (){
	Route::get("/", ["as" => "index", 'uses' => 'PromoController@index']);
	Route::get("{id}", ['as' => 'get', 'uses' => 'PromoController@show']);
	Route::get('details/{id}', ['as' => 'details', 'uses' => 'PromoController@details']);
	Route::post('details/edit', ['as' => 'edit_details', 'uses' => 'PromoController@update_details']);

	Route::post('details/gifts', ['as' => 'gifts', 'uses' => 'PromoController@gifts']);

	Route::post('create', ['as' => 'create', 'uses' => 'PromoController@store']);
	Route::post('edit', ['as' => 'edit', 'uses' => 'PromoController@update']);
	Route::post('delete', ['as' => 'remove', 'uses' => 'PromoController@destroy']);
});



## Routes for Prescription Approval
	Route::get('prescription-approval/', ['as' => 'prescription_approval', 'uses' => 'PrescriptionApprovalController@index']);

	Route::post('prescription-approval/disapprove', ['as' => 'prescription-approval-disapprove', 'uses' => 'PrescriptionApprovalController@disapprove']);

	Route::post('prescription-approval/approve', ['as' => 'prescription-approval-approve', 'uses' => 'PrescriptionApprovalController@approve']);

## REFERRALS
Route::group(['prefix' => 'affiliates', 'as' => 'Affiliates::', 'middleware' => 'auth'], function (){
	/**
	 * Routes for Affiliates
	 */
	Route::get("/", ["as" => "index", 'uses' => 'AffiliatesController@index']);
});

## ORDERS
Route::get('orders', ['as' => 'orders', 'uses' => 'OrderController@index']);
Route::get('orders/{id}', ['as' => 'get_order', 'uses' => 'OrderController@show']);
Route::get('orders/member/{patient_id}', ['as' => 'get_members_order', 'uses' => 'OrderController@show_memberOrder']);
// Route::post('orders/mark_as_paid/{id}', ['as' => 'mark_order_as_paid', 'uses' => 'BillingController@mark_order_as_paid']);
Route::post('mark_as_paid', ['as' => 'mark_order_as_paid', 'uses' => 'BillingController@mark_order_as_paid']);
Route::post('fulfill_orders', ['as' => 'fulfill_orders', 'uses' => 'OrderController@fulfill_orders']);
Route::post('orders/all', ['as' => 'all_orders', 'uses' => 'OrderController@show_all']);


## IMAGES
Route::get('images/{template}/', function($template){
	return redirect(url('images/'.$template."/".config('imagecache.default_image_404')));
});


## SALES
Route::get('sales', ['as' => 'sales', 'uses' => 'SaleController@index']);


## LOCATION
/**
 * @param string $location = ['provinces', 'municipalities']
 * @param int $id
 *
 * @return json $response
 */
Route::get('locations/get/regions/', 'LocationController@show');
Route::get('locations/get/{get_location}/where-{parent_location}/{parent_location_id}', 'LocationController@show');
Route::get('locations/search/{get_location}/{location_name}', 'LocationController@search');


## API
Route::get('api/{type}/{what}', 'ApiController@check_n_generate');
Route::get('api', ['as' => 'api_control', 'uses' => 'ApiController@process']);

Route::post('verifypayment', ['as' => 'verify_payment', 'uses' => 'VerifyPaymentController@verification']);
Route::post('verify_cash_payment', ['as' => 'verify_cash_payment', 'uses' => 'VerifyCashPaymentController@verification']);


## STOCK RETURNS
	Route::post('stock-return-codes/all', 'StockReturnController@stock_return_codes');
	Route::post('stock-return', 'StockReturnController@store');
	Route::post('get-stock-returns/{id}', 'StockReturnController@show_all_returned_products');
	Route::post('update-defective-stocks', 'StockReturnController@update_defective_stocks');


Route::get('emailtest', function(){
	return view('emails.sales_invoice');
});

Route::get('emailtestingservice', ['as' => 'email_testing_service', 'uses' => 'VerifyPaymentController@emailtestingservice' ]);

Route::post('notify', 'NotificationsController@index');
Route::post('read-notification', 'NotificationsController@update');
Route::get('read-notification', 'NotificationsController@update');

Route::get('populate-address/{barangay_id}', ['as' => 'populate_address', 'uses' => 'LocationController@populate_address_by_barangay']);

Route::post('save_user_token', 'PatientController@save_user_token');

## LOT NUMBERS
Route::post('lot-numbers', 'InventoryController@get_lot_numbers');
Route::get('lot-numbers', 'InventoryController@get_lot_numbers');
Route::post('get-product-lotnumbers', 'InventoryController@get_product_lot_numbers');

Route::post('replace-returned-product', 'StockReturnController@replace');

Route::get('try', function (){
	dd(get_total_sales());
});

Route::get('get-sales', function (){
	echo get_total_sales(); 
});