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

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateSku(){
	return strtoupper(generateRandomString(8));
}