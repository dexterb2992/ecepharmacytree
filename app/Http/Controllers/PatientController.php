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
            $response['success'] = false

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

        return view('admin.members')->withMembers($members)->withRegions($regions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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

        if($member->delete())
            return json_encode( array("status" => "success") );

        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function unblock()
    {
        $id = Input::get('id');
        $member = Patient::withTrashed()->findOrFail($id);

        if($member->restore())
            return json_encode( array("status" => "success") );

        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}
