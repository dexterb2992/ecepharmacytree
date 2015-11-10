<?php

// array for JSON response
$response = array();
// include db connect class
require_once __DIR__ . '/db_connect.php';
// connecting to db
$db           = new DB_CONNECT();

$_cleanedGET = array();
foreach ($_GET as $key => $value) {
	$_cleanedGET[mysql_real_escape_string($key)] = mysql_real_escape_string($value);
}

$_GET = array();
$_GET = $_cleanedGET;

$request      = $_GET['q'];
$db_result    = 0;
$result       = 0;
$tbl          = isset($_GET['table']) ? $_GET['table'] : '';
date_default_timezone_set('Asia/Manila');
$datenow = date("Y-m-d H:i:s", time());
$pre_response = array(
	"success" => 1,
	"message" => ""
	);
switch ($request) {
	case 'check_if_username_exist':
        //this option is currently unused
	$username = $_GET['username'];
	$result = mysql_query("SELECT * FROM patients WHERE username = '" . $username . "' WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
	$tbl = "patients";
	case 'get_dosages':
        // get all products from products table
	$result = mysql_query("SELECT * FROM dosage_format_and_strength WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
	$tbl = "dosage_format_and_strength";
	break;
	case 'get_products':
        // get all products from products table
    $result = mysql_query("SELECT * FROM products WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "products";
    break;
    case 'get_doctors':
    $result = mysql_query("SELECT * FROM doctors WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "doctors";
    break;
    case 'get_patients':
    $result = mysql_query("SELECT * from patients where (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "patients";
    break;
    case 'get_clinics':
    $result = mysql_query("SELECT * FROM clinics WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "clinics";
    break;
    case 'get_clinic_doctor':
    $result = mysql_query("SELECT * FROM clinic_doctor WHERE is_active = 1") or returnError(mysql_error());
    $tbl = "clinic_doctor";
    break;
    case 'get_doctor_specialties':
    $result = mysql_query("SELECT * FROM specialties WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "specialties";
    break;
    case 'get_doctor_sub_specialties':
    $result = mysql_query("SELECT * FROM sub_specialties WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "sub_specialties";
    break;
    case 'get_product_categories':
    $result = mysql_query("SELECT * FROM product_categories WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "product_categories";
    break;
    case 'get_product_subcategories':
    if (isset($_GET['cat']) && $_GET['cat'] != "") {
        if ($_GET['cat'] == "all") {
            $result = mysql_query("SELECT * FROM product_subcategories WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
        } else {
            $result = mysql_query("SELECT * FROM product_subcategories WHERE category_id = '" . $_GET['cat'] . "' AND (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
        }
        $tbl = "product_subcategories";
    } else {
        $pre_response = array(
            "success" => 0,
            "message" => 'No category specified.'
            );
    }
    break;
    case 'get_basket_items':
    if (!isset($_GET['patient_id'])) {
        $pre_response = array(
            "success" => 0,
            "message" => 'No patient specified.'
            );
    } else {
        $patient_id = $_GET['patient_id'];
        $result     = mysql_query("SELECT * from baskets WHERE patient_id='" . $patient_id . "'");
        $tbl = "baskets";
    }
    break;
    case 'get_patient_records':
    $result = mysql_query("SELECT * FROM patient_records WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "patient_records";
    break;
    case 'get_treatments':
    $result = mysql_query("SELECT * FROM treatments WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "treatments";
    break;

    case 'get_promo' :
    $sql = "SELECT *  FROM promo WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00') AND start_date <= '".$datenow."' AND end_date >= '".$datenow."'";
    $result = mysql_query($sql) or returnError(mysql_error()); 
    $tbl = "promo";
    break;

    case 'get_discounts_free_products' :
    $sql = "SELECT dfp.*  FROM discounts_free_products as dfp inner join promo as p on p.id = dfp.promo_id 
    WHERE (p.deleted_at IS NULL OR p.deleted_at = '0000-00-00 00:00:00')  AND p.start_date <= '".$datenow."' AND p.end_date >= '".$datenow."'";
    $result = mysql_query($sql) or returnError(mysql_error());
    $tbl = "discounts_free_products";
    break;

    case 'get_free_products' :
    $sql = "SELECT fp.* FROM free_products as fp inner join discounts_free_products as dfp on fp.dfp_id = dfp.id inner join promo as p on dfp.promo_id = p.id
    WHERE (p.deleted_at IS NULL OR p.deleted_at = '0000-00-00 00:00:00') AND p.start_date <= '".$datenow."' AND p.end_date >= '".$datenow."'";
    $result = mysql_query($sql) or returnError(mysql_error());
    $tbl = "free_products";
    break;

    case 'get_referrals_by_user' :
    $result = mysql_query("SELECT * from patients WHERE referred_by = '".$_GET['referred_by']."' ORDER BY created_at DESC") or returnError(mysql_error());
    $tbl = "patients";
    break;


    case 'get_prescriptions' : 
    $result = mysql_query("SELECT * FROM patient_prescriptions WHERE patient_id = ".$_GET['patient_id']) or returnError(mysql_error());
    $tbl = "patient_prescriptions";
    break;

    case 'get_branches' : 
    $result = mysql_query("SELECT br.*, bg.name as address_barangay, m.name as address_city_municipality, p.name as address_province, r.name as address_region FROM branches as br inner join barangays as bg on br.barangay_id = bg.id inner join municipalities as m on bg.municipality_id = m.id inner join provinces as p on m.province_id = p.id inner join regions as r on p.region_id = r.id") or returnError(mysql_error());
    $tbl = "branches";
    break;

    case 'get_orders' : 
    $result = mysql_query("SELECT * FROM orders where patient_id = ".$_GET['patient_id']) or returnError(mysql_error());
    $tbl = "orders";
    break;

    case 'get_order_details' : 
    $result = mysql_query("SELECT * FROM order_details as od inner join orders as o on od.order_id = o.id where o.patient_id = ".$_GET['patient_id']) or returnError(mysql_error());
    $tbl = "order_details";
    break;

    // case 'get_notifications' :
    // $result = mysql_query("SELECT * FROM notifications WHERE patient_id = ".$_GET['patient_ID']) or returnError(mysql_error());
    // $tbl = "notifications";
    // break;

    case 'get_consultations_notif' :
    $result = mysql_query("SELECT * FROM consultations WHERE patient_id = ".$_GET['patient_ID']." and is_approved != 0 and isRead = 0") or returnError(mysql_error());
    $tbl = "consultations";
    break;

    case 'get_consultations':
    $result = mysql_query("SELECT * FROM consultations WHERE patient_id = ".$_GET['patient_id']." and is_deleted != 1") or returnError(mysql_error());
    $tbl = "consultations";
    break;

    case 'get_settings' :
    $result = mysql_query("SELECT * FROM settings") or returnError(mysql_error());
    $tbl = "settings";
    break;

    case 'get_messages_by_user' :
    $result = mysql_query("SELECT * FROM messages WHERE patient_id = ".$_GET['patient_id']." order by created_at DESC") or returnError(mysql_error());
    $tbl = "messages";
    break;

    case 'get_regions':
    $result = mysql_query("SELECT * FROM regions") or returnError(mysql_error());
    $tbl = "regions";
    break;

    case 'get_provinces':
    $result = mysql_query("SELECT * FROM provinces WHERE region_id =".$_GET['region_id']) or returnError(mysql_error());
    $tbl = "provinces";
    break;

    case 'get_municipalities':
    $result = mysql_query("SELECT * FROM municipalities WHERE province_id =".$_GET['province_id']) or returnError(mysql_error());
    $tbl = "municipalities";
    break;

    case 'get_barangays':
    $result = mysql_query("SELECT * FROM barangays WHERE municipality_id =".$_GET['municipality_id']) or returnError(mysql_error());
    $tbl = "barangays";
    break;

    case 'get_clinic_patients';
    $result = mysql_query("SELECT cp.*, b.municipality_id, m.province_id, p.region_id FROM clinic_patients as cp inner join barangays as b on cp.address_barangay_id = b.id inner join municipalities as m on b.municipality_id = m.id inner join provinces as p on m.province_id = p.id inner join regions as r on p.region_id = r.id WHERE username = '".$_GET['username']."' and password= '".$_GET['password']."'") or returnError(mysql_error());  
    $tbl = "clinic_patients";
    break;

    case 'get_medical_records':
    $result = mysql_query("SELECT * from clinic_patients as cp inner join medical_records_requests as mrr on cp.id = mrr.clinic_patients_id where username = '".$_GET['username']."' and password = '".$_GET['password']."'") or returnError(mysql_error());
    break;

    case 'google_distance_matrix':
    $tmp_url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$_GET['mylocation_lat'].",".$_GET['mylocation_lang']."&destinations=";
    $str = "";
    $storage = array();
    $result = mysql_query("SELECT * FROM branches") or returnError(mysql_error());
    while ($row = mysql_fetch_assoc($result)) {
        // push single row into final response array
        

      // foreach ($row as $key => $value) {
            // let's remove some special characters as it causes to return null when converted to json
         // $row[$key] =  preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
            // array_push($storage, $row);
        $str = $str.$row['latitude'].",".$row['longitude']."|";
     // }
            // $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$_GET['mylocation_lat'].",".$_GET['mylocation_lang']."&destinations=7.051969,125.5947593&key=AIzaSyB1RD66hs2KpuH1tHf5MDxScCTCBVM9uk8";
     
 }
 $str = substr($str, 0, strlen($str) - 1);
        $tmp_url = $tmp_url.$str."&key=AIzaSyB1RD66hs2KpuH1tHf5MDxScCTCBVM9uk8";

        // echo $tmp_url;
        $json = file_get_contents($tmp_url); // this WILL do an http request for you
        $data = json_decode($json, true);
        
        // foreach($as = $data->rows->elements){
        //     array_push($storage, $as->distance->value);
        // }
        $row = $data['rows'];
        // $element = json_decode($row, true);

        pre($row[0]);
        // echo $storage;


        exit(0);
    break;

    default:
        # code...
    break;
}

if ($pre_response["success"] == 0) {
   echo json_encode($pre_response);
   exit(0);
}

if ($result != 0)
   $db_result = mysql_num_rows($result);
// check for empty result
if ($db_result > 0) {
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

/* Custom functions */
function returnError($msg) {
	$pre_response = array(
		"success" => 0,
		"message" => $msg
		);
}

function pre($str){
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}

function fetchRows($result, $tbl){
	$response[$tbl] = array();

	while ($row = mysql_fetch_assoc($result)) {
            // push single row into final response array
		foreach ($row as $key => $value) {
                // let's remove some special characters as it causes to return null when converted to json
			$row[$key] =  preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
		}
		array_push($response[$tbl], $row);
	}
	return $response;
}