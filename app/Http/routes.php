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

Route::get("try", function(){
	return view("404");
});

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
Route::post('inventory/create', [ 'as' => 'create_inventory', 'uses' => 'InventoryController@store' ]);
Route::post('inventory/edit', [ 'as' => 'edit_inventory', 'uses' => 'InventoryController@edit' ]);
Route::post('inventory/delete', [ 'as' => 'delete_inventory', 'uses' => 'InventoryController@destroy' ]);
// Route::get('/members', );
