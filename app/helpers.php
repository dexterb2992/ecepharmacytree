<?php
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use ECEPharmacyTree\ReferralCommissionActivityLog;
use ECEPharmacyTree\Order;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\Doctor;
use ECEPharmacyTree\Billing;

function pre($str) {
	echo '<pre>';
	print_r($str);
	echo '</pre>';
}

function money_format_($value) {
	return number_format((float)$value, 2, '.', '');
}

function generate_random_string($length = 10, $is_number = 0, $is_sku = false) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if ( $is_number  == 1) {
		$characters = '0123456789';
	}else if ( $is_number == 2 ) {
		$characters = 'abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		if ( $is_sku ) {
			$characters = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
		}
	}

	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function strbefore($string, $substring) {
	$pos = strpos($string, $substring);
	if ($pos === false)
		return $string;
	else 
		return(substr($string, 0, $pos));
}

function does_sku_exist($sku, $product_id = 0) {
	// if( $product_id != 0 ){
		// $check = ECEPharmacyTree\Product::where('sku', '=', $sku)->first();
	// }else{
		$check = ECEPharmacyTree\Product::where('sku', '=', $sku)->first();
	// }

	if ( $check === null )
		return false;
	return true;
}

function generate_sku() {
	// $sku = strtoupper( generate_random_string(3, 2, true).generate_random_string(3, 1, true) );
	$sku = strtoupper( generate_random_string(16, 0, true) );

	if ( !does_sku_exist($sku) )
		return $sku;
	generate_sku();
}

function generate_referral_id() {
	$referral_id = strtoupper( generate_random_string(3, 2, true).generate_random_string(3, 1, true) );
	$check = ECEPharmacyTree\Patient::where('referral_id', '=', $referral_id)->first();
	$check2 = ECEPharmacyTree\Doctor::where('referral_id', '=', $referral_id)->first();
	if ( $check === null && $check2 === null)
		return $referral_id;
	
	generate_referral_id();
}

function generate_lot_number() {
	$inventory = ECEPharmacyTree\Inventory::orderBy('lot_number', 'desc')
	->withTrashed()->first();

	if (!isset($inventory->lot_number)) {
		$new_lot_number = 1000;
	}else{
		$new_lot_number = $inventory->lot_number == 0 ? $inventory->lot_number + 1001 : $inventory->lot_number + 1;
	}

	$check = ECEPharmacyTree\Inventory::where('lot_number', '=', $new_lot_number)->withTrashed()->first();
	if( $check === null )

		return $new_lot_number;

	generate_lot_number();
}

function str_auto_plural($singular_noun, $quantity) {
	$pos = strpos($singular_noun, "(");
	$suf = "";

	if ( $pos !== false ) {
		$singular_noun = trim( substr($singular_noun, 0, $pos) );
		$suf = " ".trim( substr($singular_noun, $pos) );
	}

	if ( $quantity > 1 )	
		return str_plural($singular_noun).$suf;

	return str_singular($singular_noun).$suf;
}

function rn2br($str) {
	$newLineArray = array('\r\n','\n\r','\n','\r');
	return str_replace($newLineArray,'<br/>', nl2br($str));
}

function get_person_fullname($person, $reversed = false) {
	$mname = !empty($person->mname) && strlen($person->mname) > 1 ? substr(ucfirst($person->mname), 0, 1).". " : ' ';
	$fname = !empty($person->fname) ? ucfirst($person->fname)." " : '';
	$lname = !empty($person->lname) ? ucfirst($person->lname) : '';

    if ( $reversed )
        return $lname.", ".$fname;
    return $fname." ".$lname;
}

function get_person_number($person) {
	$mobile_no = !empty($person->mobile_no) ? ucfirst($person->mobile_no)." " : '';
	$tel_no = !empty($person->tel_no) ? ucfirst($person->tel_no) : '';

    return $mobile_no.",".$tel_no;
}


function get_senior_age($person) {
	$dob = !empty($person->birthdate) ? ucfirst($person->birthdate)." " : '';
	$time = strtotime($dob);
	$current_time = time();
	$newformat = date('Y-m-d',$time);
	$age_years = date('Y',$current_time) - date('Y',$time);
	$senior_citizen_id_number = !empty($person->senior_citizen_id_number) ? ucfirst($person->senior_citizen_id_number)." " : '';
	$senior_id_picture = !empty($person->senior_id_picture) ? ucfirst($person->senior_id_picture) : '';

    return $age_years;
}

function get_senior_Id_number($person) {
	$senior_citizen_id_number = !empty($person->senior_citizen_id_number) ? ucfirst($person->senior_citizen_id_number)." " : '';


    return $senior_citizen_id_number;
}

function get_senior_Id($person) {
	$senior_citizen_id_number = !empty($person->senior_citizen_id_number) ? ucfirst($person->senior_citizen_id_number)." " : '';
	$senior_id_picture = !empty($person->senior_id_picture) ? ucfirst($person->senior_id_picture) : '';

    return $senior_id_picture;
}

function get_IsSenior_Status($person, $reversed = false) {
	$IsSenior_Status = !empty($person->isSenior) ? ucfirst($person->isSenior)." " : '';

    return $IsSenior_Status;
}


function get_beneficiary_senior_Id($person) {
	$beneficiary_id;
	$con = mysqli_connect(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
	$sql = "SELECT * FROM beneficiaries where id ='$person' ";
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	if ( mysqli_num_rows($res) > 0 ) {
		$row = mysqli_fetch_object($res);

		$senior_id_picture = $row->senior_id_picture;
		return $senior_id_picture;
	}
}

function get_beneficiary_senior_Id_number($person) {
	$beneficiary_id;
	$con = mysqli_connect(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
	$sql = "SELECT * FROM beneficiaries where id ='$person' ";
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	if ( mysqli_num_rows($res) > 0 ) {
		$row = mysqli_fetch_object($res);

		$senior_citizen_id_number = $row->senior_citizen_id_number;
		return $senior_citizen_id_number;
	}
}

function get_beneficiary_senior_Id_Contactnumber($person) {
	$beneficiary_id;
	$con = mysqli_connect(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
	$sql = "SELECT * FROM beneficiaries where id ='$person' ";
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	if ( mysqli_num_rows($res) > 0 ) {
		$row = mysqli_fetch_object($res);

		$senior_citizen_id_number = $row->mobile_no;
		return $senior_citizen_id_number;
	}
}

function get_beneficiary_Status($person) {
	$beneficiary_id;
	$con = mysqli_connect(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
	$sql = "SELECT * FROM beneficiaries where id ='$person' ";
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	if ( mysqli_num_rows($res) > 0 ) {
		$row = mysqli_fetch_object($res);

		$status = $row->isSenior;
		return $status;
	}
}


function get_patient_referrals($patient) {
	$count1 = 0;
	$count2 = 0;
	
	$count1 = ECEPharmacyTree\Patient::where('referred_byDoctor', '=', $patient->referral_id)->count();
	$count2 = ECEPharmacyTree\Patient::where('referred_byUser', '=', $patient->referral_id)->count();

	return $count1 + $count2;
}

function get_recent_settings() {
	/*$con = mysqli_connect(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
	$sql = "SELECT * FROM settings LIMIT 1";
	$res = mysqli_query($con, $sql);

	if ( mysqli_num_rows($res) > 0 ) {
		$row = mysqli_fetch_object($res);
	}

	return $row;*/
	$con = mysqli_connect(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
	$sql = "SELECT * FROM settings LIMIT 1";
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	if ( mysqli_num_rows($res) > 0 ) {
		$row = mysqli_fetch_object($res);
	}
	return $row;
}


function get_patient_beneficiariesAge($person) {
	
	$beneficiary_id;
	$con = mysqli_connect(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
	$sql = "SELECT TIMESTAMPDIFF( YEAR, birthdate, CURDATE( ) ) AS age FROM beneficiaries where id ='$person' ";
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	if ( mysqli_num_rows($res) > 0 ) {
		$row = mysqli_fetch_object($res);

		$age = $row->age;
		return $age;
	}
	/*return $patient_id;*/
}
function check_if_not_fulfilled($order) {
	if ($order->order_details()->count() == $order->order_details()->whereRaw('qty_fulfilled = 0')->count())
		return true;

	return false;
}

function check_if_partially_fulfilled($order) {
	if ($order->order_details()->whereRaw('quantity != qty_fulfilled')->count() > 0)
		return true;

	return false;
}

function check_stock_availability($product) {
	$settings = ECEPharmacyTree\Setting::first();
	$critical_stock = isset($product->critical_stock)  && !is_null($product->critical_stock)? $product->critical_stock : 5;

	$total_available_stock = ECEPharmacyTree\Inventory::where('product_id', $product->id)->sum('available_quantity');
	// dd("pID: $product->id :total_available_stock: $total_available_stock");
	if ( $total_available_stock < 1 ):
		return 'out_of_stock';
	elseif ( $total_available_stock <= $critical_stock ):
		return 'critical';
	endif;
	return 'available';
}

function _error($msg, $alert_type = 'label') {
	return '<div class="'.$alert_type.' '.$alert_type.'-danger">'.$msg.'</div>';
}

function validate_reminder_token($token) {
	$res = DB::table('password_resets')->where('token', '=', $token)
	->where('created_at','>', Carbon\Carbon::now()->subHours( (config("auth.password.expire"))/60 ))->first();

	if ( empty($res)  || $res === null)
		return false;
	return $res->email;
}


/**
 * @param int $role
 *
 * @return Response
 */
function get_role($role) {
	$roles = [
	1 => 'Super Admin',
	2 => 'Branch Admin',
	1001 => 'Developer'
	];

	if ( array_key_exists($role, $roles) )
		return $roles[$role];
	return '-';
}

// formats number to money
function to_money($number, $decimal = 0) {
	return number_format( $number , $decimal , "." , "," );
}

// removes commas from an integer/float
function _clean_number($number) {
	return str_replace(',', "", $number);
}

function check_if_order_had_approved_prescriptions($order) {
	if ($order->order_details()->whereRaw('prescription_id != 0')->count() > 0)
		return true;
	return false;

}

/**
 * @param string $path_to_source_file
 * @param array $columns
 *
 * @return array $rows
 */
function extract_db_to_array($path_to_source_file, $columns = array()) {
	// for example, $columns = ['id', 'province_id', 'name'];
	// to use this function properly, the content structure of a file should be
	// 1. each column value should be separated by a Tab
	// 2. each row should be represented by a newline


	$data = file_get_contents($path_to_source_file);
	$arr_data =  explode(PHP_EOL, $data);
	$rows = [];

	foreach ($arr_data as $key => $value) {
		$entry = preg_split("/[\t]/", $value);
		if ( isset($entry[ count($columns)-1 ]) )
			$new_row = [];
		$new_row['created_at'] = new DateTime;
		$new_row['updated_at'] = $new_row['created_at'];

		for($x = 0; $x < count($columns); $x++) {
			if ( isset($entry[$x]) )
				$new_row[$columns[$x]] = $entry[$x];
		}
		$rows[] = $new_row;
	}

	return array_values($rows);
}

function arrayUnique($array, $preserveKeys = false)  
{  
    // Unique Array for return  
	$arrayRewrite = array();  
    // Array with the md5 hashes  
	$arrayHashes = array();  
	foreach($array as $key => $item) {  
        // Serialize the current element and create a md5 hash  
		$hash = md5(serialize($item));  
        // If the md5 didn't come up yet, add the element to  
        // to arrayRewrite, otherwise drop it  
		if (!isset($arrayHashes[$hash])) {  
            // Save the current element hash  
			$arrayHashes[$hash] = $hash;  
            // Add element to the unique Array  
			if ($preserveKeys) {  
				$arrayRewrite[$key] = $item;  
			} else {  
				$arrayRewrite[] = $item;  
			}  
		}  
	}  
	return $arrayRewrite;  
}  

/**
 * Searches a key or value of a multidensional array
 *
 * @param string $needle
 * @param array $array_key  // the array key that you want to compare
 * @param string $haystack
 */
function multi_array_search($needle, $array_key = 0, $haystack) {
	$arrIt = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));

	foreach ($arrIt as $sub) {
		$subArray = $arrIt->getSubIterator();
		if ($subArray[$array_key] === $needle) {
			$outputArray[] = iterator_to_array($subArray);
		}
	}
	return $outputArray;
}

function cp1250_to_utf2($text) {
	$dict  = array(chr(225) => 'á', chr(228) =>  'ä', chr(232) => 'č', chr(239) => 'ď', 
		chr(233) => 'é', chr(236) => 'ě', chr(237) => 'í', chr(229) => 'ĺ', chr(229) => 'ľ', 
		chr(242) => 'ň', chr(244) => 'ô', chr(243) => 'ó', chr(154) => 'š', chr(248) => 'ř', 
		chr(250) => 'ú', chr(249) => 'ů', chr(157) => 'ť', chr(253) => 'ý', chr(158) => 'ž',
		chr(193) => 'Á', chr(196) => 'Ä', chr(200) => 'Č', chr(207) => 'Ď', chr(201) => 'É', 
		chr(204) => 'Ě', chr(205) => 'Í', chr(197) => 'Ĺ',    chr(188) => 'Ľ', chr(210) => 'Ň', 
		chr(212) => 'Ô', chr(211) => 'Ó', chr(138) => 'Š', chr(216) => 'Ř', chr(218) => 'Ú', 
		chr(217) => 'Ů', chr(141) => 'Ť', chr(221) => 'Ý', chr(142) => 'Ž', 
		chr(150) => '-', 'Ã±' => 'ň', 'Ã‘' => 'Ň', '&Ntilde;' => 'Ň', '&ntilde' => 'ň'
		);
	return strtr($text, $dict);
}

function decode_utf8($arrays = array()) {
	foreach ($arrays as $key => $array) {
		$arrays[$key]['name'] = cp1250_to_utf2($array['name']);
	}
	return $arrays;
}


function combine_additional_address(array $addresses) {
    foreach ($addresses as $key => $value) {
    	if ( trim($value) == "" ) {
    		unset($addresses[$key]);
    	}
    }
    return implode(', ', $addresses);
}

function peso() {
	return 'PHP ';
}

function clean($str) {
	return str_replace('_', " ", $str);
}

function check_stock_expiration() {
	//<i class="glyphicon glyphicon-tags"></i>
}

function get_all_downlines($referral_id) {
	$referral_id = trim($referral_id);
	$settings = ECEPharmacyTree\Setting::first();
	$patients = ECEPharmacyTree\Patient::where('referred_byUser', '=', $referral_id)->get()->toArray(); // Primary Level

	if ( empty($patients) )
		$patients = ECEPharmacyTree\Patient::where('referred_byDoctor', '=', $referral_id)->get()->toArray(); // Primary Level Downline of Doctor

	$downlines = array();
	$downlines = $patients;

	foreach($patients as $key => $patient) {
		
		$child_downlines = get_all_downlines( $patient["referral_id"] );

		$downlines[$key]["downlines"] = $child_downlines;

	}

	return $downlines;

}

function extract_downlines($downlines = array()) {
	$res = "";
	foreach($downlines as $key => $downline) {
		$res.= '<li class="bg-teal-active">'
		.'<span data-original-title="'.$downline["fname"]." ".$downline["lname"].'" data-toggle="tooltip">'
		.Str::limit($downline["fname"]." ".$downline["lname"], 15, '').'</span>'
		."<br/>(".$downline["referral_id"].")";
		if ( count($downline['downlines']) > 0 ) {
			$new_dls = extract_downlines($downline['downlines']);
			$res.= '<ul>'.$new_dls.'</ul>';
		}

		$res.= '</li>';

	}

	return $res;
}

function simple_downlines($referral_id, $fucking_array = array(), $counter = 1) //1
{
	$wtf_array = $fucking_array;
	$referral_id = trim($referral_id);
	$patients = ECEPharmacyTree\Patient::where('referred_byUser', '=', $referral_id)->get();
	
	if(count($patients) > 1) 
		$counter = $counter;
	else 
		$counter += 1;
	
	foreach($patients as $patient) {

		if(count($wtf_array) == 0)
			$json = array("fname" => $patient->fname, "referral_id" => $patient->referral_id, "lname" => $patient->lname, "created_at" => $patient->created_at->format('Y-m-d H:i:s'), "level" => 1,"points"=>$patient->points);
		else 
			$json = array("fname" => $patient->fname, "referral_id" => $patient->referral_id, "lname" => $patient->lname, "created_at" => $patient->created_at->format('Y-m-d H:i:s'), "level" => $counter,"points"=>$patient->points);

		array_push($wtf_array, $json);

		$json_arr = simple_downlines($patient['referral_id'], $wtf_array, $counter); //cnb801
		$wtf_array = $json_arr;
	}

	return $wtf_array;
}

global $x, $uplines;
$x = 0;
$uplines = array();

function get_uplines($referral_id, $is_one = false, $generate_clickable_html = false) {
	global $x, $uplines;

	$user = ECEPharmacyTree\Patient::where('referral_id', '=', $referral_id)->first();

	// get the first upline
	if ( !empty($user) ) {
		$parent = [];
		if ( trim($user->referred_byDoctor) == "" || trim($user->referred_byDoctor) == null ) {
			$parent = ECEPharmacyTree\Patient::where('referral_id', '=', $user->referred_byUser)->first();
		}else{
			$parent = ECEPharmacyTree\Doctor::where('referral_id', '=', $user->referred_byDoctor)->first();
		}	
		

		if ( !empty($parent) ) {
			$uplines[$x] = $parent;
			$final_uplines = $uplines;
			$x++;
			// check if parent has a parent
			if ( !empty(trim($parent->referred_byDoctor)) xor !empty(trim($parent->referred_byUser)) ) {
				get_uplines($parent->referral_id);
			}else{
				// reset the global variables
				$x = 0;
			}
		}
	}

	if ( !empty($uplines) ) {
		// dd($uplines);
	}

	if ( $is_one ) {
		if ( count($uplines) > 0  ) {
			if ( $generate_clickable_html ) {
				$prefix = isset($uplines[0]->sub_specialty_id) ? "<i class='fa fa-user-md'></i>" : '';
				$id_prefix = isset($uplines[0]->sub_specialty_id) ? "d" : 'p';
				$html = "<a href='javascript:void(0);' data-id='$id_prefix{$uplines[0]->id}' class='show-downlines'>$prefix "
				.get_person_fullname($uplines[0]).
				"</a>";
				return $html;
				
			}
			return $uplines[0];
			
		}
		return "No referrer.";
		
	}
	
	return $uplines;
	
}


function compute_points($sales_amount) {
	$settings = get_recent_settings();
	$points_per_one_hundred = (double)$settings->points;
	$points_earned = $sales_amount * ( $points_per_one_hundred/100);
	
	return $points_earned;
}

function get_session_branch_name() {
	if ( session()->get('selected_branch') != 0 ) {
		return ECEPharmacyTree\Branch::find(session()->get('selected_branch'))->first()->name;
	}
	return "No branch selected.";
}

function get_earner_from_referral_points_logs(ReferralCommissionActivityLog $log) {
	if ( $log->to_upline_type == "patient" ) {
		$earner = ECEPharmacyTree\Patient::find($log->to_upline_id);
	}else{
		$earner = ECEPharmacyTree\Doctor::find($log->to_upline_id);
	}
	return $earner;
}

function render_pagination($pagination, $extra_params = null) {
	$html = '<hr/>';
    if ( $pagination->total() > 0 && $pagination->total() > 100 ) {
    	$rendered = $pagination->appends($extra_params)->render();
    	$html.= '
	    	<div class="row align-center">
		        <div class="col-md-4">
		            <span class="pagination">Total: '.number_format($pagination->total(), 0)." ".str_auto_plural('entry', $pagination->total()).'</span>
		        </div>
		        <div class="col-md-8">
		            '.$rendered.'
		        </div>
		    </div>
    	';
    }
    return $html;
}

function cmp_available_quantity($a, $b) {

    return strcmp($a->available_quantity, $b->available_quantity);
}


// only for info and danger types of flash messages
function _get_flash_message($type = "success", $request = "", $message = ""){
	if( $type == "error" ){
		return [
			"msg" => $message != "" ? "Sorry, we can't process your $request request right now. Please try again later or contact your programmer." : $message,
			"type" => "danger"
		];
	}else{
		if( $message != "" ){
			return [
				"msg" => $message,
				"type" => "info"
			];
		}
	}
}

function generate_product_link($product_name){
	return link_to_route('product_search', $product_name, ['q' => $product_name], ['target' => '_blank']);
}

function str_excape($subject){
	$search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
	$replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

	return str_replace($search, $replace, $subject);
}

## This is to allow putting a limit to a 'foreach' loop
#  Usage: foreach( limit($array, $limit) as $key => $value )
##
function limit($iterable, $limit) {
    foreach ($iterable as $key => $value) {
        if (!$limit--) break;
        yield $key => $value;
    }
}


#
function get_new_orders(){
	$branch_id = session()->get('selected_branch');

	$orders = Order::where('status', '=', 'pending')->where('branch_id', '=', $branch_id)->count();
	return number_format($orders);
}

function get_all_users(){
	$users = Patient::all()->count();
	return number_format($users);
}

function get_all_products(){
	$products = Product::all()->count();
	return number_format($products);
}

function get_all_doctors(){
	$doctors = Doctor::all()->count();
	return number_format($doctors);
}

function get_total_sales(){
	$total = Billing::select('total', DB::raw("DATE_FORMAT(created_at, '%Y') as y"))
				->where('payment_status', '=', 'paid')->get();
	return $total->groupBy('created_at')->toJson();

}