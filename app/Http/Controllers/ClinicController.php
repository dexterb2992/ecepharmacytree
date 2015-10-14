<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Clinic;
use Input;
use Redirect;

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
        return view('admin.clinics')->withClinics($clinics);
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
        $input = Input::all();
        $clinic = new Clinic();
        $clinic->name = ucfirst( $input['name'] );
        $clinic->contact_no = ucfirst( $input['contact_no'] );
        $clinic->unit_floor_room_no = ucfirst( $input['unit_floor_room_no'] );
        $clinic->building = $input['building'];
        $clinic->lot_no = $input['lot_no'];
        $clinic->block_no = $input['block_no'];
        $clinic->phase_no = $input['phase_no'];
        $clinic->address_house_no = $input['address_house_no'];
        $clinic->address_street = $input['address_street'];
        $clinic->address_barangay = $input['address_barangay'];
        $clinic->address_city_municipality = $input['address_city_municipality'];
        $clinic->address_province = $input['address_province'];
        $clinic->address_region = $input['address_region'];
        $clinic->address_zip = $input['address_zip'];

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
    public function update()
    {
        $input = Input::all();
        $clinic = Clinic::findOrFail($input['id']);
        $clinic->name = ucfirst( $input['name'] );
        $clinic->contact_no = ucfirst( $input['contact_no'] );
        $clinic->unit_floor_room_no = ucfirst( $input['unit_floor_room_no'] );
        $clinic->building = $input['building'];
        $clinic->lot_no = $input['lot_no'];
        $clinic->block_no = $input['block_no'];
        $clinic->phase_no = $input['phase_no'];
        $clinic->address_house_no = $input['address_house_no'];
        $clinic->address_street = $input['address_street'];
        $clinic->address_barangay = $input['address_barangay'];
        $clinic->address_city_municipality = $input['address_city_municipality'];
        $clinic->address_province = $input['address_province'];
        $clinic->address_region = $input['address_region'];
        $clinic->address_zip = $input['address_zip'];

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
}
