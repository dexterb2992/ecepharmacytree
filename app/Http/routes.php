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

Route::get('/', function () {
	// return HTML::image(URL::asset('dist/img/user4-128x128.jpg'), "", ["class"=>"img-circle", "alt" => "User Image"]);
	// return die(HTML::image(URL::asset('dist/img/user2-160x160.jpg')));
    return view('welcome');
});

Route::get("try", function(){
	return view("404");
});

/**
 * Routes for Branches
 */
Route::get('branches', ['as' => 'branches', 'uses' => 'BranchController@index']);
Route::get('branches/create', ['as' => 'create_branch', 'uses' => 'BranchController@create']);
Route::post('branches/edit', ['as' => 'edit_branch', 'uses' => 'BranchController@edit']);


// Route::get('/members', );