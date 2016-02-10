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
$dateonly = date("Y-m-d", time());
$pre_response = array(
    "success" => 1,
    "message" => ""
    );
switch ($request) {
    /* Doesnt require parameter*/
    case 'get_products':
        // get all products from products table
    $result = mysql_query("call get_products(".$_GET['branch_id'].",".$_GET['patient_id'].")") or returnError(mysql_error());
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

    case 'get_treatments':
    $result = mysql_query("SELECT pt.* FROM patient_treatments as pt INNER JOIN patient_records as pr on pr.id = pt.patient_records_id WHERE pr.patient_id = ".$_GET['patient_id']." AND (pt.deleted_at IS NULL OR pt.deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "patient_treatments";
    break;

    case 'get_branches' : 
    $result = mysql_query("SELECT br.*, bg.name as address_barangay, m.name as address_city_municipality, p.name as address_province, r.name as address_region FROM branches as br inner join barangays as bg on br.barangay_id = bg.id inner join municipalities as m on bg.municipality_id = m.id inner join provinces as p on m.province_id = p.id inner join regions as r on p.region_id = r.id") or returnError(mysql_error());
    $tbl = "branches";
    break;

    case 'get_settings' :
    $result = mysql_query("SELECT * FROM settings") or returnError(mysql_error());
    $tbl = "settings";
    break;

    case 'get_regions':
    $result = mysql_query("SELECT * FROM regions") or returnError(mysql_error());
    $tbl = "regions";
    break;
    

    /* Requires Parameters */

    case 'get_nocode_promos':
    $sql = "SELECT pr.id as pr_promo_id, pr.product_applicability, pr.minimum_purchase_amount, pr.is_free_delivery as pr_free_delivery, pr.percentage_discount as pr_percentage, pr.peso_discount as pr_peso, pr.long_title, pr.start_date, pr.end_date, dfp.*, fp.product_id as free_product_id, p.name, p.packing as free_product_packing,  p.price AS free_prod_price, fp.quantity_free FROM promos as pr left join discounts_free_products as dfp on pr.id = dfp.promo_id left join free_products as fp on dfp.id = fp.dfp_id left join products as p on fp.product_id = p.id where offer_type = 'NO_CODE' AND pr.deleted_at IS NULL AND '".$dateonly."' >= pr.start_date AND '".$dateonly."' <= pr.end_date";
 
    $result = mysql_query($sql) or returnError(mysql_error()); 
    $tbl = "promos";
    break;

    case 'check_if_username_exist':
        //this option is currently unused
    $username = $_GET['username'];
    $result = mysql_query("SELECT * FROM patients WHERE username = '" . $username . "' WHERE (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')") or returnError(mysql_error());
    $tbl = "patients";
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

    case 'get_basket_details': 
    $result = mysql_query("call check_basket(".$_GET['patient_id'].", ".$_GET['branch_id'].")");
    $tbl = "baskets";
    break;

    case 'get_free_products':
    $result = mysql_query("SELECT p.*, dfp.*, fp.promo_free_product_price, fp.product_id, fp.quantity_free from promos as p LEFT JOIN discounts_free_products as dfp 
        on p.id = dfp.promo_id LEFT JOIN free_products as fp on dfp.id = fp.dfp_id where p.id = ".$_GET['promo_id']);
    $tbl = "free_products";
    break;

    case 'get_patient_records':
    $result = mysql_query("SELECT * FROM patient_records where patient_id = ".$_GET['patient_id']) 
    or returnError(mysql_error());
    $tbl = "patient_records";
    break;

    case 'get_prescriptions' : 
    $result = mysql_query("SELECT * FROM patient_prescriptions WHERE patient_id = ".$_GET['patient_id']) or returnError(mysql_error());
    $tbl = "patient_prescriptions";
    break;

    case 'get_orders': 
    $result = mysql_query("SELECT o.*, IFNULL(pr.id, 0) as promo_id FROM orders as o left join promos as pr on o.promo_id = pr.id where patient_id =".$_GET['patient_id']) or returnError(mysql_error());
    $tbl = "orders";
    break;

    case 'get_order_details' : 
    $result = mysql_query("SELECT od.*, p.name as product_name FROM order_details as od inner join products as p on od.product_id = p.id inner join orders as o on od.order_id = o.id where o.patient_id = ".$_GET['patient_id']) or returnError(mysql_error());
    $tbl = "order_details";
    break;

    case 'get_order_billings' : 
    $result = mysql_query("SELECT b.* FROM billings as b inner join orders as o on b.order_id = o.id where o.patient_id = ".$_GET['patient_id']) or returnError(mysql_error());
    $tbl = "billings";
    break;    

    case 'get_order_preference':
    $result = mysql_query("SELECT * FROM order_preference where patient_id = ".$_GET['patient_id']) or returnError(mysql_error());
    $tbl = "order_preference";
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

    case 'get_messages_by_user' :
    $result = mysql_query("SELECT * FROM messages WHERE patient_id = ".$_GET['patient_id']." order by created_at DESC") or returnError(mysql_error());
    $tbl = "messages";
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
    $result = mysql_query("SELECT cpd.*, cpd.id as cpd_id, cp.*, b.municipality_id, m.province_id, p.region_id FROM clinic_patients as cp inner join clinic_patient_doctor as cpd on cp.id = cpd.clinic_patients_id inner join barangays as b on cp.address_barangay_id = b.id inner join municipalities as m on b.municipality_id = m.id inner join provinces as p on m.province_id = p.id inner join regions as r on p.region_id = r.id WHERE BINARY cpd.username = '".$_GET['username']."' and BINARY cpd.password= '".$_GET['password']."'") or returnError(mysql_error());  
    $tbl = "clinic_patients";
    break;

    case 'get_selected_product_with_image':
    $result = mysql_query("SELECT p.*, pg.filename, IFNULL(SUM(DISTINCT inv.available_quantity), 0) as available_quantity FROM products as p left join products_gallery as pg on p.id = pg.product_id left join inventories as inv on p.id = inv.product_id AND inv.branch_id = ".$_GET['branch_id']." WHERE p.id = ".$_GET['product_id']) or returnError(mysql_error());  
    $tbl = "products";
    break;

    case 'get_patient_points':
    $result = mysql_query("SELECT points FROM patients where id = ".$_GET['patient_id']) or returnError(mysql_error());  
    $row_cp = mysql_fetch_object($result);
    echo $row_cp->points;
    exit(0);
    break;

    case 'check_promo_code':
    $result = mysql_query("SELECT * FROM promos where offer_type = 'GENERIC_CODE' and generic_redemption_code = '".$_GET['promo_code']."'") or returnError(mysql_error());  
    $tbl = "promos";
    break;

    case 'get_patient_referral_commissions':
    $result  = mysql_query("call get_patient_referral_commissions(".$_GET['patient_id'].")") or returnError(mysql_error());
    $tbl = "referral_commission";
    break;

    case 'get_used_points':
    $result  = mysql_query("call get_used_points(".$_GET['patient_id'].")") or returnError(mysql_error());
    $tbl = "used_points";
    break;

    case 'get_patients_downlines':
    $result  = mysql_query("SELECT * FROM patients where referred_byUser='".$_GET['referral_id']."'") or returnError(mysql_error());
    $tbl = "downlines";
    break;

    case 'empty_basket_to_change_branch':
    if(mysql_query("DELETE FROM baskets where patient_id = ".$_GET['patient_id'])){
        echo "deleted";
        exit(0);
    } else {
        echo "not_deleted";
        exit(0);
    }
    break;

    case 'get_branch_name_from_id':
    $result = mysql_query("SELECT name FROM branches where id = ".$_GET['branch_id']) or returnError(mysql_error());  
    $row_cp = mysql_fetch_object($result);
    echo $row_cp->name;
    exit(0);
    break;

    case 'get_uname_pword_from_clinic':
    $result = mysql_query("SELECT cpd.*, cpr.*, ct.*, cm.med_name, pr.clinic_patient_record_id as pr_record_id, cpr.id as cpr_id FROM clinic_patient_doctor as  cpd INNER JOIN clinic_patients_records as cpr ON cpd.clinic_patients_id = cpr.patient_id INNER JOIN clinic_treatments as ct on cpr.id = ct.clinic_patients_record_id INNER JOIN clinic_medicines as cm on ct.medicine_id = cm.id LEFT JOIN patient_records AS pr ON cpr.id = pr.clinic_patient_record_id WHERE cpd.patient_id = ".$_GET['patient_id']." order by cpr_id ASC");
    $tbl = "clinic_patient_doctor";
    break;

    case 'get_clinic_records':
    $username = $_GET['username'];
    $password = $_GET['password'];
    $patient_id = $_GET['patient_id'];

    $resultskie = mysql_query("SELECT cpd.*, cpr.created_at as cpr_created_at, cpr.*, ct.*, cm.med_name, cpr.id as cpr_id from clinic_patient_doctor as cpd inner join clinic_patients_records as cpr on cpd.clinic_patients_id = cpr.patient_id INNER JOIN clinic_treatments as ct on cpr.id = ct.clinic_patients_record_id INNER JOIN clinic_medicines as cm  on cm.id = ct.medicine_id where BINARY cpd.username = '".$username."' and BINARY cpd.password = '".$password."' and ( cpd.patient_id = 0 or cpd.patient_id = ".$patient_id.")") or die(mysql_error());
    $result = $resultskie;
    $tbl = "records";

    if (mysql_num_rows($result) > 0) {
    	$row_cp = mysql_fetch_object(mysql_query("SELECT cpd.*, cpr.created_at as cpr_created_at, cpr.*, ct.*, cpr.id as cpr_id from clinic_patient_doctor as cpd inner join clinic_patients_records as cpr on cpd.clinic_patients_id = cpr.patient_id LEFT JOIN clinic_treatments as ct on cpr.id = ct.clinic_patients_record_id where BINARY cpd.username = '".$username."' and BINARY cpd.password = '".$password."' and ( cpd.patient_id = 0 or cpd.patient_id = ".$patient_id.")"));
    	$result2 = mysql_query("SELECT * FROM patient_records where clinic_patient_record_id = ".$row_cp->cpr_id);

        if(mysql_num_rows($result2) > 0){
            $response['has_record'] = 1;
        } else {
            $response['has_record'] = 0;
        }

        $update_row = "UPDATE clinic_patient_doctor SET patient_id = '".$patient_id."' WHERE username = '".$username."' and password = '".$password."' and patient_id = 0";
        if(mysql_query($update_row)) {
            $response['success_update'] = 1;
        } else {
            $response['success_update'] = 0;
        } 
    }
    break;

    case 'google_distance_matrix':
    $tmp_url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$_GET['mylocation_lat'].",".$_GET['mylocation_long']."&destinations=";
    $reverse_geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$_GET['mylocation_lat'].",".$_GET['mylocation_long']."&key=AIzaSyB1RD66hs2KpuH1tHf5MDxScCTCBVM9uk8";
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
        // $responsed["succcess"] = 1;
        $response["sorted_nearest_branches"] = $storage;
        $response["branches"] = $storage;
        // echo json_encode($responsed);
        // exit(0);
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

function strbefore($string, $substring) {
    $pos = strpos($string, $substring);
    if ($pos === false)
        return $string;
    else 
        return(substr($string, 0, $pos));
}