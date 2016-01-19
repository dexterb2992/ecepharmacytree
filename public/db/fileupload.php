<?php
header("Access-Control-Allow-Origin: *");
require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

// Path to move uploaded files
if( isset($_POST) ){
	$_GET = $_POST;
}

$id = $_GET['patient_id'];

$target_path = "uploads/";
$new_name = "user_".$id; // this is the new folder you'll create
$target_path .= $new_name . '/';

if (!file_exists($target_path)) {  // to make sure the path doesn't exist yet
mkdir($target_path);
chmod($target_path, 0777);
}


// array for final json respone
$response = array();

// final file url that is being uploaded
// $file_upload_url = 'http://192.168.10.1/db/' . $target_path;
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
	$filename = generate_random_string().'.jpg';

	$target_path = $target_path . $filename;

    // reading other post parameters

    // $email = isset($_POST['email']) ? $_POST['email'] : '';

	$purpose = isset($_POST['purpose']) ? $_POST['purpose'] : 'wala';

	// $response['file_name'] = $filename;

	// $response['purpose'] = $purpose;

    // $response['email'] = $email;

    // $response['website'] = $website;

	try {

        // Throws exception incase file is not being moved

		if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {

            // make error flag true

			$response['error'] = true;

			$response['message'] = 'Could not move the file!';

		}

		if($purpose == "profile_upload_update") {

			$patient_data = mysql_query("SELECT * FROM patients WHERE id =".$id) or returnError(mysql_error());
			if(mysql_num_rows($patient_data) > 0) {
				while ($row = mysql_fetch_assoc($patient_data)) {
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
			if( mysql_query($sql) ){
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
		} else {
        // File successfully uploaded
			$sql = "INSERT INTO patient_prescriptions (patient_id, filename) VALUES('$id', '$filename')";
			if( mysql_query($sql) ){
				$response['message'] = 'File uploaded successfully!';
				$response['error'] = false;
				$response['file_path'] = $file_upload_url . $filename;
				$response['file_url'] = $file_upload_url;
				$response['server_id'] = mysql_insert_id();
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