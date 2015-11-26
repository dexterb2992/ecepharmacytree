<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;

class ApiController extends Controller
{
    
    public function process()
    {
    	$input = Input::all();
		return DB::select('call '.$input['q'].'()');
    }
}
