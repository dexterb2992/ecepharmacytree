<?php
// array for JSON response
$response = array();
// include db connect class
require_once __DIR__ . '/db_connect.php';
// connecting to db
$db       = new DB_CONNECT();
$conn = $db->connect();
$response = array();
// $_POST = $_GET;
$request  = $_POST['request'];
date_default_timezone_set('Asia/Manila');
$datenow = date("Y-m-d H:i:s", time());
$oldQty  = 0;

$_cleanedPOST = array();
foreach ($_POST as $key => $value) {
	$_cleanedPOST[mysqli_real_escape_string($conn, $key)] = mysqli_real_escape_string($conn, $value);
}

$_POST = array();
$_POST = $_cleanedPOST;

$basket_is_direct_update = "false";
if( isset($_POST['is_direct_update']) ){
	$basket_is_direct_update = $_POST['is_direct_update'];
	unset($_POST['is_direct_update']);
}

if ($request == 'register') {
	if (isset($_POST['cell_no'])) {
		$_POST['mobile_no'] = $_POST['cell_no'];
		unset($_POST['cell_no']);
	}
	if (isset($_POST['email'])) {
		$_POST['email_address'] = $_POST['email'];
		unset($_POST['email']);
	}
	$created_at             = date('Y-m-d H:i:s', time());
	$bdate_formated         = date('Y-m-d', strtotime($_POST['birthdate']));
	$_POST['birthdate']     = $bdate_formated;
	$_POST['created_at']    = $created_at;
    //check if uname exist
	$sql_for_checking_uname = "SELECT * FROM patients WHERE username = '" . $_POST['username'] . "'";
	$result_fetch_patient_checked_uname = mysqli_query($conn, $sql_for_checking_uname) or die(mysqli_error($conn));
    // check for empty result
	if ($result_fetch_patient_checked_uname->num_rows > 0) {
		$response["success"] = 2;
		$response["message"] = "User Already Exist";
		echo json_encode($response);
		exit(0);
	} else {
		$cols    = "";
		$values  = "";
		$length  = count($_POST);
		$counter = 0;
		foreach ($_POST as $key => $value) {
			$counter++;
			if ($key != "request" && $key != "table" && $key != "action") {
				$cols .= $counter != $length ? $key . "," : $key . "";
				$values .= $counter != $length ? "'" . $value . "'," : "'" . $value . "'";
			}
		}
		$sql_insert_patient = "INSERT INTO patients(" . $cols . ") VALUES(" . $values . ")";
		$result_insert_patient = mysqli_query($conn, $sql_insert_patient) or die(mysqli_error($conn));
		$last_patient_id   = mysqli_insert_id($conn);
		$sql_fetch_patient = "SELECT * FROM patients WHERE id = $last_patient_id";
		$result_fetch_patient = mysqli_query($conn, $sql_fetch_patient) or die(mysqli_error($conn));
        // check for empty result
		if ($result_fetch_patient->num_rows > 0) {
			$response["patient"] = array();
			$row                 = mysqli_fetch_assoc($result_fetch_patient);
			array_push($response["patient"], $row);
			$response["success"] = 1;
			echo json_encode($response);
			exit(0);
		} else {
			$response["success"] = 0;
			$response["message"] = "No patient found";
			echo json_encode($response);
			exit(0);
		}
	}
} else if ($request == 'login') {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$sql = "SELECT pt.*, bg.name as address_barangay, m.name as address_city_municipality, p.name as address_province, r.name as address_region FROM patients as pt inner join barangays as bg on pt.address_barangay_id = bg.id inner join municipalities as m on bg.municipality_id = m.id inner join provinces as p on m.province_id = p.id inner join regions as r on p.region_id = r.id WHERE pt.username = '$username' and pt.password = '$password'";
	$result_fetch_patient = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    // check for empty result
	$response["patient"] = array();
	if ($result_fetch_patient->num_rows > 0) {
		$row = mysqli_fetch_assoc($result_fetch_patient);
		array_push($response["patient"], $row);
		$response["success"] = 1;
		die(json_encode($response));

	} else {
		$response["success"] = 0;
		$response["message"] = "No patient found";
		$response["sql"] = $sql;
		die(json_encode($response));
		exit(0);
	}
} else if( $request == 'save_orders' ) {

	$user_id = $_POST['user_id'];
	$recipient_name = $_POST['recipient_name'];
	$recipient_address = $_POST['recipient_address'];
	$recipient_contactNumber = $_POST['recipient_contactNumber'];
	$branch_id = $_POST['branch_id'];
	$modeOfDelivery = $_POST['modeOfDelivery'];
	$payment_method = $_POST['payment_method'];
	$payment_status = "pending";
	$status = $_POST['status'];

	$sql = "SELECT b.*, p.*, b.id as basketID, pr.price FRom patients as p inner join baskets as b on p.id = b.patient_id inner join products as pr on b.product_id = pr.id WHERE p.id = ".$user_id ;

	$result = mysqli_query($conn, $sql) or returnError(mysqli_error($conn));
	$counter = 0;
	$totalAmount = 0;
	$order_saved = false;
	$prescription_id = 0;
	if ($result != 0)
		$db_result = $result->num_rows;
                // check for empty result
	if ($db_result > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
                    // push single row into final response array
			foreach ($row as $key => $value) {
				$quantity = $row['quantity'];
				$price = $row['price'];
				$product_id = $row['product_id'];
				$quantity = $row['quantity'];     
				$basket_id = $row['basketID'];           
				$prescription_id = $row['prescription_id'];
			}

			$totalAmount += $quantity * $price;


			if($counter == 0 ){

				$sql_orders_save = "INSERT INTO orders VALUES ('', $user_id, '$recipient_name', '$recipient_address', '$recipient_contactNumber', '', '$branch_id', '$modeOfDelivery', 'Pending', '$datenow', '', '')";

				if(mysqli_query($conn, $sql_orders_save )){
					$order_id = mysqli_insert_id($conn); 
					$response['order_message'] = "order saved on database";
					$order_saved = true;

				} else {
					$response["order_message"] = "Sorry, we can't process your request right now. ".mysqli_error($conn);
					echo json_encode($response);
					exit(0);
				}

			}

			$counter += 1;

			if($order_saved){
				$sql_order_details_save = "INSERT INTO order_details VALUES ('', $order_id, $product_id, $prescription_id, $quantity, 'type', 0, '$datenow', '', '')";

				if(mysqli_query($conn, $sql_order_details_save)){
					$response['order_details_message_'.$counter] = "order detail saved on database";
					if(mysqli_query($conn, "DELETE FROM baskets where ID=".$basket_id)){
						$response['basket_message'] = "basket/s deleted on database";
					} else {
						$response['basket_message'] = "basket/s  not deleted on database";
					}
				} else {
					$response['order_details_message_'.$counter] = "Sorry, we can't process your request right now. ".mysqli_error($conn);
					echo json_encode($response);
					exit(0);
				}   
			}
		}

		$sql_billings_save = "INSERT INTO billings VALUES ('', $order_id, $totalAmount, $totalAmount, '$payment_status', '$payment_method', '$datenow', '', '')";

		if(mysqli_query($conn, $sql_billings_save)){
			$response['billing_message'] = "order saved on database";
			$response['billing_id'] = mysqli_insert_id($conn);
		} else {
			$response["billing_message"] = "Sorry, we can't process your request right now. ".mysqli_error($conn);
			echo json_encode($response);
			exit(0);
		}

        $response['has_contents'] = true;

		echo json_encode($response);
		exit(0);
	}

} else if ($request == "crud") {
    /**    
    * @param table: asks for table name to insert    
    **/

    // if( $_POST['table'] == "basket" )

    $action = $_POST['action'];
    $lent   = count($_POST);
    $x      = 0;
    if ($action == "insert") {
    	$cols   = "";
    	$values = "";
    	foreach ($_POST as $key => $value) {
    		if ($key != "request" && $key != "table" && $key != "action") {
    			$cols .= $key . ",";
    			$values .= "'" . $value . "',";
    		}
    		$x++;
    	}
    	$cols   = substr($cols, 0, strlen($cols) - 1);
    	$values = substr($values, 0, strlen($values) - 1);
    	$sql    = "INSERT INTO " . $_POST['table'] . "(updated_at,created_at," . $cols . ") VALUES('" . $datenow . "','" . $datenow . "'," . $values . ")";
    	if (mysqli_query($conn, $sql)) {
    		$response["last_inserted_id"] = mysqli_insert_id($conn);
    		$response["success"]          = 1;
    		$response["created_at"] = $datenow;
            $response["has_contents"] = true;
        } else {
          $response["success"] = 0;
          $response["message"] = "Sorry, we can't process your request right now. " . mysqli_error($conn);
          $response["has_contents"] = false;
      }
  } else if($action == "multiple_insert") {
     $collection = json_decode(stripslashes($_POST['jsobj']));

     $str_values = "";

     foreach($collection->json_treatments as $col)
     {
      $cols   = "";
      $values = "";
      foreach($col as $key => $val)
      {
    		// echo $key . ': ' . $val;
    		// echo '<br>';
       $cols .= $key . ",";
       $values .= "'" . $val . "',";
   }
   $cols = substr($cols, 0, strlen($cols) -1);
   $values = substr($values, 0, strlen($values) - 1);
   $str_values .= "('".$datenow."','".$datenow."',".$values."),";
}

$sql = "INSERT INTO ".$_POST['table']." (created_at, updated_at, ".$cols.") VALUES ".substr($str_values, 0, strlen($str_values) - 1);

if(mysqli_query($conn, $sql)) {
  $response['success'] = 1;
  $response['message'] = "relax, you're doing fine";
  $response['last_inserted_id'] = mysqli_insert_id($conn);
  $response['affected_rows'] = mysqli_affected_rows($conn); 
  $response["has_contents"] = true;
} else {
  $response['success'] = 0;
  $response['message'] = "error =".mysqli_error($conn);
  $response['sql_query'] = $sql;
  $response["has_contents"] = false;
}

} else if ($action == "update") {
 $settings = "";
 foreach ($_POST as $key => $value) {
  if ($key != "request" && $key != "table" && $key != "id" && $key != "action") {
   $settings .= $key . "='" . $value . "',";
}
$x++;
}
$settings = substr($settings, 0, strlen($settings) - 1);
$sql      = "UPDATE " . $_POST['table'] . " SET " . $settings . ", updated_at='" . $datenow . "' WHERE id=" . $_POST['id'];
if (mysqli_query($conn,  $sql )) {
  $response["success"] = 1;
  $response["has_contents"] = true;
} else {
    $response["has_contents"] = false;
    $response["success"] = 0;
    $response["message"] = "Sorry, we can't process your request right now. " . mysqli_error($conn);
    $response["query"] = $sql;
}
} else if ($action == "delete") {
 $sql = "DELETE FROM " . $_POST['table'] . " WHERE id=" . $_POST['id'];
 if (mysqli_query($conn, $sql)) {
  $response["success"] = 1;
  $response["has_contents"] = true;
} else {
    $response["has_contents"] = false;
    $response["success"] = 0;
    $response["message"] = "Sorry, we can't process your request right now. " . mysqli_error($conn);
}
} else if ($action == "delete_prescription") {
 $result = mysqli_query($conn, "SELECT * from baskets where prescription_id = ".$_POST['id']." union SELECT * from baskets where prescription_id = ".$_POST['id']) or returnError(mysqli_error($conn));

 if($result->num_rows < 1) {
  $sql = "DELETE FROM " . $_POST['table'] . " WHERE id=" . $_POST['id'];

  if (mysqli_query($conn, $sql)) {
   unlink($_POST['url']);
   $response["success"] = 1;
   $response["has_contents"] = true;
} else {
    $response["has_contents"] = false;
    $response["success"] = 0;
    $response["message"] = "Sorry, we can't process your request right now. " . mysqli_error($conn);
}	
} else {
    $response["has_contents"] = false;
    $response["success"] = 2;
    $response["message"] = "Cannot delete a prescription that is submitted for an order." . mysqli_error($conn);
}

} else if ($action == 'multiple_delete') {
 $sql = "DELETE FROM ".$_POST['table']." WHERE id IN (".$_POST['serverID'].")";

 if(mysqli_query($conn, $sql)) {
  $response["success"] = 1;
  $response["has_contents"] = true;
} else {
    $response["has_contents"] = false;
    $response["success"] = 0;
    $response["message"] = "Sorry, we can't process your request right now. " . mysqli_error($conn);
}
} else if ($action == 'multiple_update_for_basket') {

 $collection = json_decode(stripslashes($_POST['jsobj']));

 $whens = "";
 $ids = "";

 foreach ($collection->jsobj as $col) {
  $whens = $whens." when id = ".$col->id." then ".$col->quantity;
  $ids = $ids.$col->id.","; 
            //if promo_id has value then initialize BasketPromo and fuck the shit up oh. -> save the promo_id and promo_type and discount_promo_value or id
            // if($col->promo_type != ""){
  $sql_remove_bp = "DELETE FROM basket_promos where basket_id = ".$col->id;
  mysqli_query($conn, $sql_remove_bp);

  $sql = "INSERT INTO basket_promos(basket_id, promo_id, promo_type, ".$col->promo_type.", promo_free_product_qty) VALUES (".$col->id.",".$col->promo_id.",'".$col->promo_type."',".$col->promo_value.", ".$col->promo_free_product_qty.") ON DUPLICATE KEY UPDATE promo_id=".$col->promo_id.", promo_type='".$col->promo_type."', ".$col->promo_type."=".$col->promo_value.", promo_free_product_qty=".$col->promo_free_product_qty;
  if(mysqli_query($conn, $sql)){
    $response["has_contents"] = true;
    $response["promo_message"] = 'promo_saved';
} else {
    $response["has_contents"] = false;
    $response["promo_message"] = 'promo_not_saved';
}

$response['queryaf'] = $sql;
            // } 
}

$ids = substr($ids, 0, strlen($ids) -1);
$sql = "update baskets set quantity = ( case".$whens." end ) where id in (".$ids.")";

if (mysqli_query($conn, $sql)) {
  $response["success"] = 1;
  $response["message"] = "rows updated";
  $response["query"] = $sql;
  $response["has_contents"] = true;
} else {
    $response["has_contents"] = false;
    $response["success"] = 0;
    $response["message"] = "Sorry, we can't process your request right now. " . mysqli_error($conn);
}

} else if ($action == "update_with_custom_where_clause") {
 $settings = "";
 foreach ($_POST as $key => $value) {
  if ($key != "request" && $key != "table" && $key != "id" && $key != "action" && $key != "custom_where_clause") {
   $settings .= $key . "='" . $value . "',";
}
$x++;
}
$settings = substr($settings, 0, strlen($settings) - 1);
$sql      = "UPDATE " . $_POST['table'] . " SET " . $settings . ", updated_at='" . $datenow . "' WHERE ".$_POST['custom_where_clause'];

if(mysqli_query($conn, $sql)) {
  if(mysqli_affected_rows($conn) > 0){
   $response["success"] = 1;
   $response['server_id'] = mysqli_affected_rows($conn);
   $response["has_contents"] = true;
} else {
    $response["has_contents"] = false;
    $response["success"] = 0;
    $response["message"] = "No record is updated or deleted";
}
} else {
    $response['has_contents'] = false;
    $response["success"] = 0;
    $response["message"] = "Sorry, we can't process your request right now. " . mysqli_error($conn);
}

} else {
 echo json_encode($response);
 exit(0);
}

echo json_encode($response);
exit(0);
}

function pre($str)
{
	echo "<pre>";
	print_r($str);
	echo "</pre>";
}
?>