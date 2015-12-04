<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;
use DB;
use Carbon\Carbon;
require 'public/libs/PayPal/autoload.php';


$datenow = Carbon::now('Asia/Manila');
	// die($datenow);
	$pre_response = array(
		"success" => 1,
		"message" => ""
		);

class ApiController extends Controller
{
	
}