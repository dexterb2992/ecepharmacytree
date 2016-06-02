<?php
header("Access-Control-Allow-Origin: *");
if(isset($_POST['action'])){
	$target_path='clinic-patient-profile-pic/';
	if(!file_exists($target_path)){
		mkdir($target_path);
		chmod($target_path, 0777);
	}
	
	if($_POST['action']=='create'){
		$imgPath = $_POST['photo'];
		header('Content-Type: image/png'); 
		$img = str_replace('data:image/png;base64,', '', $imgPath); 
		$img = str_replace(' ', '+', $img);
		file_put_contents($_POST['target_path'], base64_decode($img));
	}elseif ($_POST['action']=='delete') {
		unlink(getcwd().$target_path);
	}
}
?>
	