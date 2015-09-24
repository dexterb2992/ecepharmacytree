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
function generateRandomString($length = 10, $is_number = 0) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if( $is_number  == 1) {
    	$characters = '0123456789';
    }else if( $is_number == 2 ){
    	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }

    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateSku(){
	$sku = generateRandomString(4, 2).generateRandomString(4, 1);
	return strtoupper($sku);
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

function get_patient_referrals($patient){
	return $count = ECEPharmacyTree\Patient::where('referred_by', '=', $patient->referral_id)->count();
}

function get_downlines($referral_id){

	$users = ECEPharmacyTree\Patient::where('referred_by', '=', $referral_id)->get()->toArray(); // Primary Level
	return $users;
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
	$con = mysqli_connect("localhost", "root", "", "ece_pharmacy_tree");

    $sql = "SELECT * FROM settings LIMIT 1";
    $res = mysqli_query($con, $sql);
    if( mysqli_num_rows($res) > 0 ){
        $row = mysqli_fetch_object($res);
    }
    return $row;
}