<?php
function pre($str){
	echo '<pre>';
	print_r($str);
	echo '</pre>';
}

function get_ph_regions(){
	return array(
		'Ilocos Region (Region I)',
		'Cagayan Valley (Region II)',
		'Central Luzon (Region III)',
		'CALABARZON (Region IV-A)',
		'MIMAROPA (Region IV-B)',
		'Bicol Region (Region V)',
		'Western Visayas (Region VI)',
		'Central Visayas (Region VII)',
		'Eastern Visayas (Region VIII)',
		'Zamboanga Peninsula (Region IX)',
		'Northern Mindanao (Region X)',
		'Davao Region (Region XI)',
		'SOCCSKSARGEN (Region XII)',
		'Caraga (Region XIII)',
		'National Capital Region (NCR)',
		'Cordillera Administrative Region (CAR)',
		'Autonomouse Region in Muslim Mindanao (ARMM)',
		'Negros Island Region (Region XVIII)'
	);
}

/**
 * @var $is_number
 * 			0 = alphanumeric characters
 *			1 = numbers only
 *			2 = letters only
 */

function generateRandomString($length = 10, $is_number = 0, $is_sku = false) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if( $is_number  == 1) {
    	$characters = '0123456789';
    }else if( $is_number == 2 ){
    	$characters = 'abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	
    	if( $is_sku ){
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

function generateSku(){
	$sku = strtoupper( generateRandomString(3, 2, true).generateRandomString(3, 1, true) );

	$check = ECEPharmacyTree\Product::where('sku', '=', $sku)->first();
	if( $check === null )
		return $sku;
	generateSku();
}




function get_str_plural($str){
	$str = str_singular($str);

	$lastChar = ""; $replacement = "";

	$lastChar = substr($str, strlen( $str ) - 2);
	$new_str = substr($str, 0, strlen( $str ) - 2);

	if( $lastChar == "um" ) $replacement = "a";
	if( $lastChar == "fe" ) $replacement = "ves";
	if( $lastChar == "us" ) $replacement = "i";
	if( $lastChar == "ch" )	return $str."es";

	if( $replacement != "" ) return $new_str.$replacement;



	$lastChar = substr($str, strlen($str) -1 );
	$new_str = substr($str, 0, strlen( $str ) - 1);

	if( $lastChar == "f" )	$replacement = "ves";

	if( $lastChar == "y" ) $replacement = "ies";
	
		// return $new_str.$replacement;

	if( $lastChar == "s" || $lastChar == "x" ){
		return $str."es";
	}else{
		return $str."s";
	}


	if( $replacement == "" ){
		$new_str = $str;
	}
	
	return $new_str.$replacement;	
	

}

function str_auto_plural($str, $quantity){
	$pos = strpos($str, "(");
		$suf = "";

		if( $pos !== false ){
			$str = trim( substr($str, 0, $pos) );
			$suf = trim( substr($str, $pos) );
		}

		if( $quantity > 1 )	
			return str_plural($str)." ".$suf;

		return str_singular($str)." ".$suf;
	}

	function rn2br($str){
		$newLineArray = array('\r\n','\n\r','\n','\r');
		return str_replace($newLineArray,'<br/>', nl2br($str));
	}

	function safety_stock(){
		$p = Product::all();
		return $p->toJson();
	}

	function get_patient_fullname($patient){
		return ucfirst($patient->fname)." ".ucfirst($patient->lname);
	}

	function get_patient_full_address($patient){
		return ucfirst($patient->address_street).', '.ucfirst($patient->address_barangay).', '.ucfirst($patient->address_city_municipality);
	}

	function get_patient_referrals($patient){
		return $count = ECEPharmacyTree\Patient::where('referred_by', '=', $patient->referral_id)->count();
	}

function get_all_downlines($referral_id){
	$settings = ECEPharmacyTree\Setting::first();
	$patients = ECEPharmacyTree\Patient::where('referred_by', '=', $referral_id)->get()->toArray(); // Primary Level

	$downlines = array();
	$downlines = $patients;

	foreach($patients as $key => $patient){
		
		$child_downlines = get_all_downlines( $patient["referral_id"] );

		$downlines[$key]["downlines"] = $child_downlines;

	}

	return $downlines;

}

function extract_downlines($downlines = array()){
	$res = "";
	foreach($downlines as $key => $downline){
		$res.= '<li>'.$downline["fname"]." ".$downline["lname"]."<br/>(".$downline["referral_id"].")";
		if( count($downline['downlines']) > 0 ){
			$new_dls = extract_downlines($downline['downlines']);
			$res.= '<ul>'.$new_dls.'</ul>';
		}

		$res.= '</li>';

	}

	return $res;
}

function get_recent_settings(){
	$con = mysqli_connect(getenv('DB_HOST'), getenv('DB_DATABASE'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
	$sql = "SELECT * FROM settings LIMIT 1";
	$res = mysqli_query($con, $sql);
	if( mysqli_num_rows($res) > 0 ){
		$row = mysqli_fetch_object($res);
	}
	return $row;
}


function check_if_not_fulfilled($order){
	if($order->order_details()->count() == $order->order_details()->whereRaw('qty_fulfilled = 0')->count())
		return true;

	return false;
}

function check_if_partially_fulfilled($order){
	if($order->order_details()->whereRaw('quantity != qty_fulfilled')->count() > 0)
		return true;

	return false;
}

function check_for_critical_stock(){
	try {
		$settings = ECEPharmacyTree\Setting::first();

		$critical_stock_products = ECEPharmacyTree\Inventory::where("quantity", "<=", $settings->critical_stock)->get();
		return $critical_stock_products;
	} catch (Exception $e) {
		pre($e);
	}
}

function get_branch_full_address($branch){
	$branch->unit_floor_room_no = $branch->unit_floor_room_no == 0 ? "" : $branch->unit_floor_room_no;
	$branch->building = $branch->building == 0 ? "" : $branch->building;
	$branch->lot_no = $branch->lot_no == 0 ? "" : $branch->lot_no;
	$branch->block_no = $branch->block_no == 0 ? "" : $branch->block_no;
	$branch->phase_no = $branch->phase_no == 0 ? "" : $branch->phase_no;

	$address = $branch->unit_floor_room_no." ".
    $branch->building." ".$branch->lot_no." ".$branch->block_no." ".
    $branch->phase_no." ".
    $branch->address_street." <br>".
    $branch->address_barangay.", ".
    $branch->address_city_municipality.", ".
    $branch->address_province." <br>".
    $branch->address_region.", ".
    $branch->address_zip." ";

    return $address;
}

function _error($msg, $alert_type = 'label'){
	return '<div class="'.$alert_type.' '.$alert_type.'-danger">'.$msg.'</div>';
}

function validate_reminder_token($token){
	$res = DB::table('password_resets')->where('token', '=', $token)
		->where('created_at','>', Carbon\Carbon::now()->subHours( (config("auth.password.expire"))/60 ))->first();

	if( empty($res)  || $res === null)
		return false;
	return $res->email;
}


/**
 * @param int $role
 * @return Response
 */
function get_role($role){
	$roles = [
		1 => 'Administrator',
		2 => 'Branch Manager',
		3 => 'Pharmacist',
		1001 => 'Developer'
	];

	if( array_key_exists($role, $roles) )
		return $roles[$role];
	return '-';
}

function to_money($number, $decimal = 0){
	return number_format( $number , $decimal , "." , "," );
}

// removes commas from an integer/float
function _clean_number($number){
	return str_replace(',', "", $number);
}

function check_if_order_had_approved_prescriptions($order){
	if($order->order_details()->whereRaw('prescription_id != 0')->count() > 0)
		return true;
	return false;

}