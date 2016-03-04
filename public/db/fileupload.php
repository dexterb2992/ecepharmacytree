<?php
header("Access-Control-Allow-Origin: *");
require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();
$conn = $db->connect();

// // Path to move uploaded files
// if( isset($_POST) ){
// 	$_GET = $_POST;
// }

$id = $_POST['patient_id'];

$target_path = "uploads/";
$new_name = "user_".$id; // this is the new folder you'll create
$target_path .= $new_name . '/';

if (!file_exists($target_path)) {  // to make sure the path doesn't exist yet
mkdir($target_path);
chmod($target_path, 0777);
}


// array for final json respone
$response = array();

$file_upload_url = $target_path;
date_default_timezone_set('Asia/Manila');
$datenow = date("Y-m-d H:i:s", time());

function generate_random_string($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

if (isset($_FILES['image']['name'])) {
	// $filename = basename($_FILES['image']['name']);

	$purpose = isset($_POST['purpose']) ? $_POST['purpose'] : 'wala';

	$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

	$allowed =  array('jpeg','png' ,'jpg');

	$filename = generate_random_string().'.'.$ext;

	$target_path = $target_path . $filename;


	try {

		if ($_FILES["image"]["size"] > 5242880) {
			$response['error'] = true;

			$response['message'] = "Sorry, your file is too large. File limit - 5 mb";
		}

		if(!in_array($ext,$allowed) ) {
			$response['error'] = true;

			$response['message'] = 'Sorry, only JPG, JPEG, PNG files are allowed.';	
		}


		if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {

			$response['error'] = true;

			$response['message'] = 'Sorry, we cannot upload the file. Please try different image';

		}

		if($purpose == "profile_upload_update") {

			$patient_data = mysqli_query($conn, "SELECT * FROM patients WHERE id =".$id) or returnError(mysqli_error($conn));
			if($patient_data->num_rows > 0) {
				while ($row = mysqli_fetch_assoc($conn, $patient_data)) {
					if($row['photo'] != "") {
						if(unlink(getcwd().'/uploads/user_'.$id.'/'.$row['photo'])){
							$response['deletedoldpicture'] = "deleted old";
						} else {
							$response['deletedoldpicture'] = "unable to delete old";
						}
					}
				}	
			}

			$sql = "UPDATE patients SET photo = '".$filename."', updated_at = '".$datenow."' WHERE id = ".$id;
			if( mysqli_query($conn, $sql) ){
				$response['message'] = 'File uploaded successfully!';
				$response['error'] = false;
				$response['file_path'] = $file_upload_url . $filename;
				$response['file_url'] = $file_upload_url;
				$response['server_id'] = $id;
				$response['file_name'] = $filename;
			}else{
				$response['error'] = true;
				$response['message'] = 'Failed while saving to database.';
			}

		} else if($purpose == "profile_upload_insert"){ 
			$response['message'] = 'File uploaded successfully!';
			$response['error'] = false;
			$response['file_path'] = $file_upload_url . $filename;
			$response['file_url'] = $file_upload_url;
			$response['file_name'] = $filename;
		} else if($purpose == "senior_citizen_upload") {
				$sql = "UPDATE patients SET isSenior = 1, senior_citizen_id_number = '".$_POST['senior_citizen_id_number']."', senior_id_picture='".$filename."' where id = ".$_POST['patient_id'];
			if(mysqli_query($conn, $sql)){
				$response['message'] = 'File uploaded successfully!';
				$response['error'] = false;
				$response['file_path'] = $file_upload_url . $filename;
				$response['file_url'] = $file_upload_url;
				$response['server_id'] = $id;
				$response['file_name'] = $filename;
			} else {
				$response['error'] = true;
				$response['message'] = 'Failed while saving to database.';
			}
		} else {
        // File successfully uploaded
			$sql = "INSERT INTO patient_prescriptions (patient_id, filename) VALUES('$id', '$filename')";
			if( mysqli_query($conn, $sql) ){
				$response['message'] = 'File uploaded successfully!';
				$response['error'] = false;
				$response['file_path'] = $file_upload_url . $filename;
				$response['file_url'] = $file_upload_url;
				$response['server_id'] = mysqli_insert_id($conn);
				$response['file_name'] = $filename;
			}else{
				$response['error'] = true;
				$response['message'] = 'Failed while saving to database.';
			}
		}
	} catch (Exception $e) {

        // Exception occurred. Make error flag true

		$response['error'] = true;

		$response['message'] = $e->getMessage();
	}

} else {

    // File parameter is missing

	$response['error'] = true;

	$response['message'] = 'No file has been received!';

}

// Echo final json response to client
echo json_encode($response);
?>