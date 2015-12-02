<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;
use DB;
use Carbon\Carbon;

$datenow = Carbon::now('Asia/Manila');
	// die($datenow);
	$pre_response = array(
		"success" => 1,
		"message" => ""
		);

class ApiController extends Controller
{
	public function process()
	{
		$input = Input::all();

		if(count($input) > 0) {
			$request = $input['q'];
			if(count($input) == 1){
				try {
					$result = DB::select('call '.$request.'()');
				} catch(\Exception $e) {
					return "<h1>Something is wrong with your request, are you sure you've entered the right keyword ? \n Please don't let me remind you about your stupidity, just saying.</h1>";
				}
			} else {
				if($request == "get_clinic_records")  {
					get_clinic_records_process($input);
				} else if ($request == "google_distance_matrix") {
					google_distance_matrix_process($input);
				} else if ($request == "get_product_subcategories") {
					get_product_subcategories_process($input);
				} else if ($request == "get_basket_items") {
					get_basket_items_process($input);
				} else if($request == "get_clinic_patients") {
					get_clinic_patients_process($input);
				} else if($request == "get_promo" || $request == "get_discounts_free_products" || $request == "get_free_products") {
					$result =  DB::select('call '.$request.'('.$datenow.')');
				}else {
					try {
						$result =  DB::select('call '.$request.'('.$input[1].')');
					} catch(\Exception $e) {
						return "<h1>Something is wrong with your request, are you sure you've entered the right keyword ? \n Please don't let me remind you about your stupidity, just saying.</h1>";
					}
				}
			}
			
			// else
			// 	return 'wiggle wiggle little fish. we need some modifications here because the query will require parameter and stuff and the call must be singular';
		} else {
			return '<h1>What the hell do you want ? Include a "q" parameter in your request/link, you Moron.</h1>'; }

			if ($pre_response["success"] == 0) {
				echo json_encode($pre_response);
				exit(0);
			}

			if ($result != 0)
				$db_result = mysql_num_rows($result);
// check for empty result
			if ($db_result > 0) {
				$tbl = $input['tbl_name'];
				$response[$tbl] = array();
				while ($row = mysql_fetch_assoc($result)) {
        // push single row into final response array
					foreach ($row as $key => $value) {
            // let's remove some special characters as it causes to return null when converted to json
						$row[$key] =  preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
					}
					array_push($response[$tbl], $row);
				}
    //get the original time from server
				date_default_timezone_set('Asia/Manila');
				$server_timestamp             = date('Y-m-d H:i:s', time());

				$result_latest_updated_at = mysql_query("SELECT * FROM ".$tbl." order by updated_at DESC limit 1") or returnError(mysql_error());

				if(mysql_num_rows($result_latest_updated_at) > 0){
					$result_latest_updated_at_array = mysql_fetch_assoc($result_latest_updated_at);
					$latest_updated_at = $result_latest_updated_at_array['updated_at'];
				}

				$response["success"]          = 1;
				$response["server_timestamp"] = "$server_timestamp";
				$response["latest_updated_at"] = "$latest_updated_at";
			} else {
    // no products found
				$response["success"] = 0;
				$response["message"] = "No $tbl data found.";
			}

// echo no users JSON
			echo json_encode($response);
		}

		public function get_clinic_patients_process($input){
			$result = mysql_query("SELECT cpd.*, cpd.id as cpd_id, cp.*, b.municipality_id, m.province_id, p.region_id FROM clinic_patients as cp inner join clinic_patient_doctor as cpd on cp.id = cpd.clinic_patients_id inner join barangays as b on cp.address_barangay_id = b.id inner join municipalities as m on b.municipality_id = m.id inner join provinces as p on m.province_id = p.id inner join regions as r on p.region_id = r.id WHERE BINARY cpd.username = '".$input['username']."' and BINARY cpd.password= '".$input['password']."'") or returnError(mysql_error());  
			$tbl = $input['tbl_name'];
		}

		public function get_clinic_records_process($input){
			$username = $input['username'];
			$password = $input['password'];
			$patient_id = $input['patient_id'];

			$result = mysql_query("SELECT cpd.*, cpr.created_at as cpr_created_at, cpr.*, ct.*, cpr.id as cpr_id from clinic_patient_doctor as cpd inner join clinic_patients_records as cpr on cpd.clinic_patients_id = cpr.patient_id inner join clinic_treatments as ct on cpr.id = ct.clinic_patients_record_id where BINARY cpd.username = '".$username."' and BINARY cpd.password = '".$password."' and ( cpd.patient_id = 0 or cpd.patient_id = ".$patient_id.")") or returnError(mysql_error());

			$tbl = $input['tbl_name'];
        // echo count($result);
			if (count($result) != 0) {
				$row_cp = mysql_fetch_object($result);
				$sql_cp = mysql_query("SELECT * FROM patient_records where clinic_patient_record_id = ".$row_cp->cpr_id);

				if(mysql_num_rows($sql_cp) > 0){
					$response['has_record'] = 1;
				} else {
					$response['has_record'] = 0;
				}

				$update_row = "UPDATE clinic_patient_doctor SET patient_id = $patient_id WHERE username = '".$username."' and password = '".$password."' and patient_id = 0";
				if(mysql_query($update_row)) {
					$response['success_update'] = 1;
				} else {
					$response['success_update'] = 0;
				} 

			}
		}

		public function google_distance_matrix_process($input){
			$tmp_url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$input['mylocation_lat'].",".$input['mylocation_long']."&destinations=";
			$reverse_geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$input['mylocation_lat'].",".$input['mylocation_long']."&key=AIzaSyB1RD66hs2KpuH1tHf5MDxScCTCBVM9uk8";
    $json_reverse_geocode = file_get_contents($reverse_geocode_url); // this WILL do an http request for you
    $data_reverse_geocode = json_decode($json_reverse_geocode);
    $address_region_reverse_geocode = $data_reverse_geocode->results[count($data_reverse_geocode->results)-2]->formatted_address;
    $nearest_region = strbefore($address_region_reverse_geocode, ",");

    $str = "";
    $distance = array();
    $storage = array();
    $responsed = array();
    $branches_in_the_same_region = array();

    $result = mysql_query("SELECT br.*, bg.name as address_barangay, m.name as address_city_municipality, p.name as address_province, r.name as address_region, r.code as address_region_code FROM branches as br inner join barangays as bg on br.barangay_id = bg.id inner join municipalities as m on bg.municipality_id = m.id inner join provinces as p on m.province_id = p.id inner join regions as r on p.region_id = r.id") or returnError(mysql_error());

    while ($row1 = mysql_fetch_object($result)) {
    	$str = $str.$row1->latitude.",".$row1->longitude."|";
    	if($row1->address_region == $nearest_region || $row1->address_region_code == $nearest_region ) 
            // array_push($branches_in_the_same_region, $row1);
    		$row1->same_region = 1;
    	else
    		$row1->same_region = 0;

    	array_push($storage, $row1);
    }

    $str = substr($str, 0, strlen($str) - 1);
    $tmp_url = $tmp_url.$str."&key=AIzaSyB1RD66hs2KpuH1tHf5MDxScCTCBVM9uk8";
    $response['url_for_distance'] = $tmp_url;
        $json = file_get_contents($tmp_url); // this WILL do an http request for you
        $data = json_decode($json);

        $elements = $data->rows[0]->elements;

        foreach ($elements as $key => $value) {
        	$distance[$key] = $elements[$key]->distance->value;
        	$storage[$key]->distance_from_user =  $elements[$key]->distance->value;
        }

        array_multisort($distance, SORT_ASC, $storage);
        $response["sorted_nearest_branches"] = $storage;
        $response["branches"] = $storage;
    }

    public function get_basket_items_process($input){
    	if (!isset($input['patient_id'])) {
    		$pre_response = array(
    			"success" => 0,
    			"message" => 'No patient specified.'
    			);
    	} else {
    		$patient_id = $input['patient_id'];
    		$result     = mysql_query("SELECT * from baskets WHERE patient_id='" . $patient_id . "'");
    		$tbl = $input['tbl_name'];
    	}
    }

    public function get_product_subcategories_process($input){
    	if (isset($input['cat']) && $input['cat'] != "") {
    		if ($input['cat'] == "all") {
    			$result = mysql_query("SELECT * FROM product_subcategories WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    		} else {
    			$result = mysql_query("SELECT * FROM product_subcategories WHERE category_id = '" . $input['cat'] . "' AND (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    		}
    		$tbl = $input['tbl_name'];
    	} else {
    		$pre_response = array(
    			"success" => 0,
    			"message" => 'No category specified.'
    			);
    	}
    }

    function strbefore($string, $substring) {
    	$pos = strpos($string, $substring);
    	if ($pos === false)
    		return $string;
    	else 
    		return(substr($string, 0, $pos));
    }
}