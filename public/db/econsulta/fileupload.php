<?php
header("Access-Control-Allow-Origin: *");
if(isset($_POST['action'])){
	if($_POST['action']=='create'){
		$imgPath = $_POST['photo'];
		header('Content-Type: image/png'); 
		$img = str_replace('data:image/png;base64,', '', $imgPath); 
		$img = str_replace(' ', '+', $img);
		file_put_contents($_POST['target_path'], base64_decode($img));
	}elseif ($_POST['action']=='delete') {
		unlink($_POST['target_path']);
	}
}

?>
	