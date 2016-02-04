<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

class SeniorCitizenController extends Controller
{
 public function store(Request $request)
 {
    if($request->hasFile('image')){
        return json_encode(array('esel' => 'gwapa'));
    } else {
        return json_encode(array('esel' => 'pangit'));
    }
}
}
