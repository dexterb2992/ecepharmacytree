<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;
use DB;

class ApiController extends Controller
{

	public function process()
	{
		$input = Input::all();

		if(count($input) > 0) {
			$request = $input['q'];
			if(count($input) == 1)
				try {
					return DB::select('call '.$request.'()');
				} catch(\Exception $e) {
					return "<h1>Something is wrong with your request, are you sure you've entered the right keyword ? \n Please don't let me remind you about your stupidity, just saying.</h1>";
				}
			else
				return 'wiggle wiggle little fish. we need some modifiations here because the query will require parameter and stuff and the call must be singular';
		} else
			return '<h1>What the hell do you want ? Include a "q" parameter in your request/link, you Moron.</h1>';
		// $result;

		// if($request == "get_product_subcategories"){
		// 	if (isset($['cat']) && $_GET['cat'] != "") {
		// 		if ($input['cat'] != "all") {
		// 			$result = DB::select('call get_product_subcategory('.$input['cat'].')');
		// 			return $result;
		// 		}
		// 	}
		// } else {
		// 	$result = DB::select('call '.$request.'()');
		// }
	}
}