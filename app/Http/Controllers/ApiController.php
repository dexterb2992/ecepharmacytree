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
		// $request = $input['q'];

		return echo count($input);

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