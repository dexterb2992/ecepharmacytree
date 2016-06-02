<?php
include_once('db_connect.php');
$response=array();
if(isset($_POST["action_type"])){

	$dbh=connect();
	$response["ids"]=array();
	switch ($_POST["action_type"]) {
//Upload to Clinic_Patient_Doctor table
		case 0:
			$patient_id=$_POST["patient_id"];
			$clinic_id=$_POST["clinic_id"];
			$doctor_id=$_POST["doctor_id"];
			$username=$_POST["username"];
			$password=$_POST["password"];
			$Params=array(1=>0,//action_type
										2=>0,//sub_action
										3=>0,//id
										4=>$clinic_id,//patient_id
										5=>$doctor_id,
										6=>0,//date
										7=>$patient_id,//time
										8=>$username,//is_approved_doctor
										9=>$password,//commect_doctor
										10=>0,//is_approved_patient
										11=>0,//commect_patient
										12=>$last_update);
			$Stored_Procedure='SP_DoctorPatient  (?,?,?,?,?,?,?,?,?,?,?,?)';

			$sql="call ".$Stored_Procedure;
			$stmt = $dbh->prepare($sql);
			foreach( $Params as $key => $val ){
				$stmt->bindValue($key, $val, PDO::PARAM_STR);
			}



			$stmt->execute();
			foreach ($stmt as $row)
  			{
  				$ids=array();
  				$ids["user_id"]  =$row[0];
                $ids["server_id"]= $row[1];
  				array_push($response["ids"], $ids);
  			}
			break;

		case 1:
			$server_id=$_POST["server_id"];
			$perform=2;
			if($server_id==0){
				$perform=1;
			}
			$Params=array(1=>$perform,//action_type
										2=>0,//sub_action
										3=>$server_id,//id
										4=>$_POST["fname"],//patient_id
										5=>$_POST["mname"],
										6=>$_POST["lname"],//date
										7=>$_POST["mobile_no"],//time
										8=>$_POST["tel_no"],//is_approved_doctor
										9=>'',
										10=>$_POST["occupation"],//commect_doctor
										11=>$_POST["birthdate"],//is_approved_patient
										12=>$_POST["sex"],//commect_patient
										13=>$_POST["civil_status"],
										14=>$_POST["height"],
										15=>$_POST["weight"],
										16=>$_POST["optional_address"],
										17=>$_POST["address_street"],
										18=>$_POST["brgy_id"],
										19=>0,
										20=>$_POST["email"]);


			$Stored_Procedure='SP_Patient  (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';


			$sql="call ".$Stored_Procedure;
			$stmt = $dbh->prepare($sql);
			foreach( $Params as $key => $val ){
				$stmt->bindValue($key, $val, PDO::PARAM_STR);
			}
			$stmt->execute();
			foreach ($stmt as $row)
  			{
  				$ids=array();
                $ids["server_id"]= $row[0];
  				array_push($response["ids"], $ids);
  			}
			break;


		case 2:
			$patient_id=$_POST["patient_id"];
			$clinic_id=$_POST["clinic_id"];
			$doctor_id=$_POST["doctor_id"];
			$app_user_id=$_POST["app_user_id"];
			$username=$_POST["username"];
			$password=$_POST["password"];
			$server_id=$_POST["server_id"];

			$perform=2;
			if($server_id==0){
				$perform=1;
			}
			$Params=array(1=>$perform,//action_type
										2=>0,//sub_action
										3=>$server_id,//id
										4=>$clinic_id,//patient_id
										5=>$doctor_id,
										6=>$patient_id,//date
										7=>$app_user_id,//time
										8=>$username,//is_approved_doctor
										9=>$password,//commect_doctor
										10=>0,//is_approved_patient
										11=>0,//commect_patient
										12=>$last_update);
			$Stored_Procedure='SP_DoctorPatient  (?,?,?,?,?,?,?,?,?,?,?,?)';


			$sql="call ".$Stored_Procedure;
			$stmt = $dbh->prepare($sql);
			foreach( $Params as $key => $val ){
				$stmt->bindValue($key, $val, PDO::PARAM_STR);
			}
			$stmt->execute();
			foreach ($stmt as $row)
  			{
  				$ids=array();
                $ids["server_id"]= $row[0];
  				array_push($response["ids"], $ids);
  			}
			break;

		case 3:
			$patient_id=$_POST["patient_id"];
			$clinic_id=$_POST["clinic_id"];
			$doctor_id=$_POST["doctor_id"];
			
			$date=$_POST["date"];
			$time=$_POST["time"];
			$is_approved_doctor=$_POST["is_approved_doctor"];
			$comment_doctor=$_POST["comment_doctor"];

			$is_approved_patient=$_POST["is_approved_patient"];
			$comment_patient=$_POST["comment_patient"];
			$patient_record_id=$_POST["patient_record_id"];
			$is_done=$_POST["is_done"];
			$server_id=$_POST["server_id"];

			$perform=1;
			if($server_id==0){
				$perform=3;
			}
			$Params=array(1=>$perform,//action
							2=>0,//sub_action
							3=>$server_id,//id
							4=>$patient_id,//patient_id
							5=>$clinic_id,
							6=>$date,//date
							7=>$time,//time
							8=>$is_approved_doctor,//is_approved_doctor
							9=>$comment_doctor,//commect_doctor
							10=>$is_approved_patient,//is_approved_patient
							11=>$comment_patient,//commect_patient
							12=>$last_update,
							13=>$doctor_id);
			$Stored_Procedure='SP_ConsultationRequest  (?,?,?,?,?,?,?,?,?,?,?,?,?)';

			$sql="call ".$Stored_Procedure;
			$stmt = $dbh->prepare($sql);
			foreach( $Params as $key => $val ){
				$stmt->bindValue($key, $val, PDO::PARAM_STR);
			}
			$stmt->execute();
			foreach ($stmt as $row)
  			{
  				$ids=array();
                $ids["server_id"]= $row[0];
  				array_push($response["ids"], $ids);
  			}
			break;

		case 4:
			$patient_id=$_POST["patient_id"];
			$clinic_id=$_POST["clinic_id"];
			$doctor_id=$_POST["doctor_id"];
			
			$record_date=$_POST["record_date"];
			$complaints=$_POST["complaints"];
			$findings=$_POST["findings"];
			$note=$_POST["note"];
			$server_id=$_POST["server_id"];

			$perform=1;
			if($server_id==0){
				$perform=0;
			}
			$Params=array(1=>$perform,//action
							2=>0,//sub_action
							3=>$server_id,//id
							4=>$patient_id,//patient_id
							5=>$doctor_id,
							6=>$clinic_id,//date
							7=>$record_date,//time
							8=>$complaints,//is_approved_doctor
							9=>$findings,//commect_doctor
							10=>'',//is_approved_patient
							11=>$note,//commect_patient
							12=>$last_update);
			$Stored_Procedure='SP_PatientRecord  (?,?,?,?,?,?,?,?,?,?,?,?)';

			$sql="call ".$Stored_Procedure;
			$stmt = $dbh->prepare($sql);
			foreach( $Params as $key => $val ){
				$stmt->bindValue($key, $val, PDO::PARAM_STR);
			}
			$stmt->execute();
			foreach ($stmt as $row)
  			{
  				$ids=array();
                $ids["server_id"]= $row[0];
  				array_push($response["ids"], $ids);
  			}
			break;

		case 5:
			$clinic_id=$_POST["clinic_id"];

			$data = json_decode($_POST["medicines"], true);
			$rows = array();
			$Stored_Procedure='SP_Clinic_Medicines  (?,?,?,?,?,?,?)';

			foreach($data['medicines'] as $key=> $value)  {
				$perform=1;
				if($value['server_id']==0){
					$perform=0;
				}
				$Params=array(1=>$perform,//action
								2=>0,//sub_action
								3=>$value['server_id'],//id
								4=>$clinic_id,//patient_id
								5=>$value['med_name'],
								6=>'',//date
								7=>'');

				$sql="call ".$Stored_Procedure;
				$stmt = $dbh->prepare($sql);
				foreach( $Params as $key => $val ){
					$stmt->bindValue($key, $val, PDO::PARAM_STR);
				}
				$stmt->execute();
				foreach ($stmt as $row)
	  			{
	  				$ids=array();
	  				$ids['user_id']=$value['id'];
	                $ids["server_id"]= $row[0];
	  				array_push($response["ids"], $ids);
	  			}
			}

			break;
	}
}
echo json_encode($response);
?>