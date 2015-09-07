<?php
 
/*
 * Following code will list all the products
 */
 
// array for JSON response
$response = array();
 
// include db connect class
require_once __DIR__ . '/db_connect.php';
 
// connecting to db
$db = new DB_CONNECT();
 
// get all products from products table
$result = mysql_query("SELECT * FROM doctors") or die(mysql_error());
 
// check for empty result
if (mysql_num_rows($result) > 0) {
    // looping through all results
    // products node
    $response["doctors"] = array();
 
    while ($row = mysql_fetch_assoc($result)) {
        // temp user array
        // $doctor = array();
        // $doctor["id"] = $row["id"];
        // $doctor["lname"] = $row["lname"];
        // $doctor["mname"] = $row["mname"];
        // $doctor["fname"] = $row["fname"];
        // $doctor["prc_no"] = $row["prc_no"];
        // $doctor["address_house_no"] = $row["address_house_no"];
        // $doctor["address_street"] = $row["address_street"];
        // $doctor["address_barangay"] = $row["address_barangay"];
        // $doctor["address_city_municipality"] = $row["address_city_municipality"];
        // $doctor["address_province"] = $row["address_province"];
        // $doctor["address_region"] = $row["address_region"];
        // $doctor["address_country"] = $row["address_country"];
        // $doctor["address_zip"] = $row["address_zip"];
        // $doctor["specialty"] = $row["specialty"];
        // $doctor["sub_specialty"] = $row["sub_specialty"];
        // $doctor["cell_no"] = $row["cell_no"];
        // $doctor["tel_no"] = $row["tel_no"];
        // $doctor["photo"] = $row["photo"];
        // $doctor["clinic_sched"] = $row["clinic_sched"];
        // $doctor["email"] = $row["email"];
        // $doctor["affiliation"] = $row["affiliation"];
        // $doctor["clinic_id"] = $row["clinic_id"];
        // $doctor["secretary_id"] = $row["secretary_id"];
        // $doctor["created_at"] = $row["created_at"];
        // $doctor["updated_at"] = $row["updated_at"];
        // $doctor["deleted_at"] = $row["deleted_at"];
 
        // push single doctor into final response array
        array_push($response["doctors"], $row);
    }
    // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);
} else {
    // no products found
    $response["success"] = 0;
    $response["message"] = "No doctors found";
 
    // echo no users JSON
    echo json_encode($response);
}
?>