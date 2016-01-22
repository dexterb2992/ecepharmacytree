<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use Input;
use Redirect;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Clinic;
use ECEPharmacyTree\Region;

class ClinicController extends Controller
{
    function __construct() {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $clinics = Clinic::all();
        $regions = Region::all();
        return view('admin.clinics')->withClinics($clinics)
            ->withRegions($regions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = Input::all();
        $clinic = new Clinic();
        $clinic->name = ucfirst( $input['name'] );
        $clinic->contact_no = ucfirst( $input['contact_no'] );
        $clinic->additional_address = ucfirst( $input['additional_address'] );
        $clinic->barangay_id = $input['barangay_id'];

        if( $clinic->save() )
            session()->flash('flash_message', ["msg" => "New Clinic has been added successfully.", "type" => "success"]);
            return Redirect::to( route('clinics') );
        
        session()->flash('flash_message', ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "danger"]);

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $clinic = Clinic::findOrFail($id);
        if( isset( $clinic->id ) )
            return $clinic->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update()
    {
        $input = Input::all();
        $clinic = Clinic::findOrFail($input['id']);
        $clinic->name = ucfirst( $input['name'] );
        $clinic->contact_no = ucfirst( $input['contact_no'] );
        $clinic->additional_address = ucfirst( $input['additional_address'] );
        $clinic->barangay_id = $input['barangay_id'];

        if( $clinic->save() )
            session()->flash("flash_message", ["msg" => "Clinic information has been updated.", "type" => "info"]);
            return Redirect::to( route('clinics') );
        session()->flash('flash_message', ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "danger"]);
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
      $clinic = Clinic::findOrFail(Input::get("id"));
      if( $clinic->delete() ){
        session()->flash("flash_message", ["msg" => "Clinic has deleted successfully.", "type" => "warning"]);
        return json_encode( array("status" => "success") );
    }
        
    session()->flash('flash_message', ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "danger"]);
    return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
}

function getClinicRecords(){
    $input = Input::all();
    return $input;
}
}
