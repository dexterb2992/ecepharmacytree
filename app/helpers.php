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
