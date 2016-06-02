<?php

include_once('db_connect.php');
$response=array();
if(isset($_GET["action_type"])){
	$dbh=connect();

	$last_update=$_GET["last_update"];
	$clinic_id=$_GET["clinic_id"];
    $response["appointments"] = array();
    $response["user_info"] = array();
	switch ($_GET["action_type"]) {
		case 0:
			//Get newly Updated appointments
			$Params=array(1=>2,//action
							2=>1,//sub_action
							3=>0,//id
							4=>0,//patient_id
							5=>$clinic_id,
							6=>0,//date
							7=>0,//time
							8=>0,//is_approved_doctor
							9=>0,//commect_doctor
							10=>0,//is_approved_patient
							11=>0,//commect_patient
							12=>$last_update,
							13=>0);
			$Stored_Procedure='SP_ConsultationRequest  (?,?,?,?,?,?,?,?,?,?,?,?,?)';


			$sql="call ".$Stored_Procedure;
			$stmt = $dbh->prepare($sql);
			foreach( $Params as $key => $val ){
				$stmt->bindValue($key, $val, PDO::PARAM_STR);
			}



			$stmt->execute();
			foreach ($stmt as $row)
  			{
  				$appointments=array();
  				$appointments["id"]  =$row[0];
                $appointments["clinic_id"]= $row[1];
                $appointments["patient_id"] = $row[2];
                $appointments["clinic_patient_id"]= $row[3];
                $appointments["doctor_id"] = $row[4];
                $appointments["appointment_date"] = $row[5];
                $appointments["time"]  =$row[6];

                $appointments["comment_doctor"]= $row[7];
                $appointments["comment_patient"] = $row[8];

                $appointments["is_approved_doctor"]= $row[9];
                $appointments["is_approved_patient"] = $row[10];
                $appointments["patient_record_id"] = $row[11];
                $appointments["is_done"] = $row[12];
                $appointments["created_at"] = $row[13];
  				array_push($response["appointments"], $appointments);
  			}
			
			break;
		
		case 1:
			$Params=array(1=>0,//action_type
										2=>1,//sub_action
										3=>0,//id
										4=>0,//patient_id
										5=>$clinic_id,
										6=>0,//date
										7=>0,//time
										8=>0,//is_approved_doctor
										9=>0,//commect_doctor
										10=>0,//is_approved_patient
										11=>0,//commect_patient
										12=>$last_update,
										13=>0);
			$response=CUSTOM_RETRIEVAL('SP_ConsultationRequest  (?,?,?,?,?,?,?,?,?,?,?,?,?)',$Params);
			break;
		case 2:
			$Params=array(1=>0,//action_type
										2=>0,//sub_action
										3=>$_GET["patient_id"],//id
										4=>0,//patient_id
										5=>0,
										6=>0,//date
										7=>0,//time
										8=>0,//is_approved_doctor
										9=>0,//commect_doctor
										10=>0,//is_approved_patient
										11=>0,//commect_patient
										12=>$last_update,
										13=>0,
										14=>0,
										15=>0,
										16=>0,
										17=>0,
										18=>0,
										19=>0,
										20=>0);


			$Stored_Procedure='SP_Patient  (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';


			$sql="call ".$Stored_Procedure;
			$stmt = $dbh->prepare($sql);
			foreach( $Params as $key => $val ){
				$stmt->bindValue($key, $val, PDO::PARAM_STR);
			}
			$stmt->execute();
			foreach ($stmt as $row)
  			{
  				$patient_info=array();
  				$patient_info["id"]  =$row[0];
                $patient_info["fname"]= $row[1];
                $patient_info["mname"] = $row[2];
                $patient_info["lname"]= $row[3];
                $patient_info["email_address"] = $row[4];
                $patient_info["mobile_no"] = $row[5];
                $patient_info["tel_no"]  =$row[6];
                $patient_info["occupation"]= $row[7];
                $patient_info["birthdate"] = $row[8];
                $patient_info["sex"]= $row[9];
                $patient_info["civil_status"] = $row[10];
                $patient_info["height"] = $row[11];
                $patient_info["weight"] = $row[12];
                $patient_info["optional_address"] = $row[13];

                $patient_info["address_street"]= $row[14];
                $patient_info["address_barangay_id"] = $row[15];
                $patient_info["municipality_id"] = $row[16];
                $patient_info["province_id"] = $row[17];
                $patient_info["region_id"] = $row[17];
                $patient_info["created_at"] = $row[18];
  				array_push($response["user_info"], $patient_info);
  			}
			break;
		
	}
}
echo json_encode($response);
?>