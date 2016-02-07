<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Input;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Region;
use ECEPharmacyTree\Province;
use ECEPharmacyTree\Municipality;
use ECEPharmacyTree\Barangay;
use Response;


class PatientController extends Controller
{

    function save_user_token(){
        $input  = Input::all();
        $response = array();

        $patient =  Patient::findOrFail($input['user_id']);
        $patient->regId = $input['token'];
        
        if($patient->save())
            $response['success'] = true;
        else
            $response['success'] = false;

        // $data = array( 'message' => 'Sample message' );

        // $ids = array( 'fWs0-Oacg0g:APA91bFYMy9zWce6heS54Eb6GTEwmoy_ruOaYEDiZMvEbY6ucuncSg2E--mz_QTBLR4SSOnNiPIEQzgVGh9XQafOWXBpFaMlZKZsttRZCHsa6g8_WP_EBXbzmBOYnB8a4fnRcS1JtJVo' );

        // $this->sendGoogleCloudMessage($data, $to_token);

        return Response::json($response);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $members = Patient::withTrashed()->get();
        $regions = Region::all();

        return view('admin.members')->withMembers($members)->withRegions($regions)->withTitle("Members");
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $member = Patient::findOrFail($id);
        if( isset( $member->id ) ){
            $member->full_address = $member->full_address();
            return $member->toJson();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $id = Input::get('id');
        $member = Patient::findOrFail($id);
        $name = get_person_fullname($member);
        if($member->delete()){
            session()->flash("flash_message", ["msg" => "$name has been blocked successfully.", "type" => "danger"]);
            return json_encode( array("status" => "success") );
        }
        session()->flash("flash_message", ["msg" => "Sorry we failed process your request. Please try again later.", "type" => "danger"]);
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function unblock()
    {
        $id = Input::get('id');
        $member = Patient::withTrashed()->findOrFail($id);

        if($member->restore()){
            $name = get_person_fullname($member);
            session()->flash("flash_message", ["msg" => "$name's access has been restored successfully.", "type" => "info"]);
            return json_encode( array("status" => "success") );
        }

        session()->flash("flash_message", ["msg" => "Sorry we failed process your request. Please try again later.", "type" => "danger"]);
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}
