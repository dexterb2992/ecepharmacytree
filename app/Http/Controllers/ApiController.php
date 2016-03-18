<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;
use DB;
use Carbon\Carbon;
use Response;
use Illuminate\Mail\Mailer;


$datenow = Carbon::now('Asia/Manila');
// die($datenow);
$pre_response = array(
	"success" => 1,
	"message" => ""
	);

class ApiController extends Controller
{
	private $datenow;
	private $pre_response;
	private $result;
	protected $mailer;

	function __construct(Mailer $mailer)
	{
		$this->mailer = $mailer;
		$this->datenow = Carbon::now('Asia/Manila');
		$this->pre_response  = array("success" => 1, "message" => "");
	}

	function testemail(){
		$email = 'lourdrivera123@gmail.com';
		$res = $this->mailer->send( 'emails.sample', 
			compact('email'), function ($m) use ($email) {
				$m->subject('Pharmacy Tree Email');
				$m->to($email);
			});
	}

	function getClinicRecords(){
		$response = array();
		$input = Input::all();
		$username = $input['username'];
		$password = $input['password'];
		$patient_id = $input['patient_id'];

		$result = DB::select("SELECT cpd.*, cpr.created_at as cpr_created_at, cpr.*, ct.*, cpr.id as cpr_id from clinic_patient_doctor as cpd inner join clinic_patients_records as cpr on cpd.clinic_patients_id = cpr.patient_id LEFT JOIN clinic_treatments as ct on cpr.id = ct.clinic_patients_record_id where BINARY cpd.username = '".$username."' and BINARY cpd.password = '".$password."' and ( cpd.patient_id = 0 or cpd.patient_id = ".$patient_id.")");
		$response['records'] = $result;

		if(!empty($result)){

			$update_row = "UPDATE clinic_patient_doctor SET patient_id = '".$patient_id."' WHERE username = '".$username."' and password = '".$password."' and patient_id = 0";
			
			$update_row =  DB::table('clinic_patient_doctor')
			->where('username', $username)
			->where('password', $password)
			->where('patient_id', 0)
			->update(['patient_id' => $patient_id]);

			if($update_row) {
				$response['success_update'] = 1;
			} else {
				$response['success_update'] = 0;
			} 
		}
		return json_encode($response);
	}

	function generate_response($input)
	{	
		$tbl = $input['tbl_name'];

		$response = array();

		if(sizeof($result) > 0){
			$tbl_last_updated_at = DB::table($tbl)->select('updated_at')->orderBy('updated_at', 'desc')->first();
			
			$response[$tbl] = $this->result;
			$response["success"]          = 1;
			$response["server_timestamp"] = "$this->datenow";
			$response["latest_updated_at"] = "$tbl_last_updated_at->updated_at";
		} else {
			$response["success"] = 0;
			$response["message"] = "No $tbl data found.";
		}

		return Response::json($response);
	}

	function strbefore($string, $substring) {
		$pos = strpos($string, $substring);
		if ($pos === false)
			return $string;
		else 
			return(substr($string, 0, $pos));
	}

	/* Custom functions */
	function returnError($msg) {
		$pre_response = array(
			"success" => 0,
			"message" => $msg
			);
	}

	function check_n_generate($type, $what){
		$response = array();

		if( $type == 'generate' ){
			if( $what == "sku" )
				return generate_sku();
			if( $what == "referral_id" )
				return generate_referral_id();

			if( $what == "lot_number" )
				return generate_lot_number();
		}else if( $type == 'check' ){
			if( $what == 'sku' )
				return does_sku_exist(Input::get('sku'), Input::get('product_id')) ? 'true' : 'false';
		}else if( $type == 'str_singular' ){
			return str_singular($what);
		}else if( $type == "get-downlines" ){
			$response['downlines'] = get_all_downlines($what);
			$response['success'] = 1;
			$response['server_timestamp'] = Carbon::now()->format('Y-m-d H:i:s');
			$response['last_updated_at'] = '';
			$response['downlines'] = simple_downlines($what);
			return $response;
		}
	}

	function showschema(){
		$response = array();
		$final = array();
		$columns = DB::select("SHOW COLUMNS FROM ". "patients");
		foreach($columns as $column) {
			// $new_column = array();
			$column->Type = strbefore($column->Type,'(');
			$obj = (object) array('field' => $column->Field, 'type' => $column->Type);
			array_push($final, $obj);
		}

		$response['columns'] = $final;

		return json_encode($response);
	}
}